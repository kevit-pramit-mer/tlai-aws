<?php

namespace app\modules\ecosmob\license\controllers;


use app\models\TenantModuleConfig;
use app\modules\ecosmob\globalconfig\models\GlobalConfig;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use app\modules\ecosmob\license\models\LicenseTicketManagement;
use app\modules\ecosmob\license\LicenseModule;
use app\modules\ecosmob\admin\models\AdminMaster;
use app\modules\ecosmob\carriertrunk\models\TrunkMaster;
use app\modules\ecosmob\didmanagement\models\DidManagement;
use app\modules\ecosmob\extension\models\Extension;


/**
 * Class LicenseController
 *
 * License Management.
 *
 * @model   LicenseTicketManagement require
 * @package app\modules\ecosmob\LicenseController\controllers
 */
class LicenseController extends Controller
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'change-status'
                        ],
                        'allow' => TRUE,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        try {
            $searchModel = new LicenseTicketManagement();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            $licenseData = Yii::$app->commonHelper->getLicenseData($_SERVER['HTTP_HOST']);
            if (!empty($licenseData)) {
                $data['licenseData'] = $licenseData;
                /*$data['licenseData'] = [
                    "licenseId" => "12d048cd-4695-4fb1-aca5-c46966231ce1",
                    "licenseName" => "premium",
                    "maxExtensions" => "20",
                    "concurrentCalls" => "100",
                    "callsPerSecond" => "10",
                    "maxDID" => "5",
                    "maxSipTrunk" => "2",
                    "maxAgents" => "15",
                    "maxSupervisors" => "3"
                ];*/

                $removeDays = GlobalConfig::getValueByKey('TICKET_ON_HOLD_REMOVE_DAYS');
                if(!empty($removeDays)) {
                    $date = date('Y-m-d', strtotime('-' . $removeDays . 'days'));
                    LicenseTicketManagement::deleteAll(['AND', ['status' => 'On-hold'], ['<=', 'DATE(created_at)', $date]]);
                }

                $licenseTicketModal = LicenseTicketManagement::find()->orderBy(['id' => SORT_DESC])->all();
                $usedExt = Extension::find()->count();
                $usedAgent = AdminMaster::find()->where(['adm_is_admin' => 'agent'])->count();
                $usedSup = AdminMaster::find()->where(['adm_is_admin' => 'supervisor'])->count();
                $usedTrunk = TrunkMaster::find()->where(['from_service' => '0'])->count();
                $usedDid = DidManagement::find()->where(['from_service' => '0'])->count();
                $data['usedExt'] = $usedExt;
                $data['freeExt'] = $data['licenseData']['maxExtensions'] - $usedExt;
                $data['usedAgent'] = $usedAgent;
                $data['freeAgent'] = $data['licenseData']['maxAgents'] - $usedAgent;
                $data['usedSup'] = $usedSup;
                $data['freeSup'] = $data['licenseData']['maxSupervisors'] - $usedSup;
                $data['usedTrunk'] = $usedTrunk;
                $data['freeTrunk'] = $data['licenseData']['maxSipTrunk'] - $usedTrunk;
                $data['usedDid'] = $usedDid;
                $data['freeDid'] = $data['licenseData']['maxDID'] - $usedDid;

                if (!empty($_POST)) {
                    $reqData = $allocatedData = [];
                    $reqData['Extension'] = $_POST['maxExtensions'];
                    $reqData['Agents'] = $_POST['maxAgents'];
                    $reqData['Supervisor'] = $_POST['maxSupervisors'];

                    $allocatedData['Extension'] = $data['licenseData']['maxExtensions'];
                    $allocatedData['Agents'] = $data['licenseData']['maxAgents'];
                    $allocatedData['Supervisor'] = $data['licenseData']['maxSupervisors'];

                    if (TenantModuleConfig::isTrunkDidRoutingEnabled() == true) {
                        $reqData['Trunk'] = $_POST['maxSipTrunk'];
                        $reqData['DID'] = $_POST['maxDID'];
                        $allocatedData['Trunk'] = $data['licenseData']['maxSipTrunk'];
                        $allocatedData['DID'] = $data['licenseData']['maxDID'];
                    }
                    $reqData['Concurrent Calls'] = $_POST['concurrentCalls'];
                    $allocatedData['Concurrent Calls'] = $data['licenseData']['concurrentCalls'];

                    if (!empty($reqData)) {
                        $ticketModel = new LicenseTicketManagement();
                        $ticketModel->ticket_unique_id = substr(str_shuffle("0123456789"), 0, 5);
                        $ticketModel->allocated = json_encode($allocatedData);
                        $ticketModel->requested = json_encode($reqData);
                        $ticketModel->status = $_POST['ticket_status'];
                        $ticketModel->created_at = date('Y-m-d H:i:s');
                        if ($ticketModel->save()) {
                            if ($ticketModel->status != 'On-hold') {
                                Yii::$app->commonHelper->addTicket([
                                    "ticketId" => $ticketModel->id,
                                    "tenantId" => $GLOBALS['tenantID'],
                                    "ticket_unique_id" => $ticketModel->ticket_unique_id,
                                    "requestType" => 'Licence Modification',
                                    "allocated" => $ticketModel->allocated,
                                    "requested" => $ticketModel->requested,
                                    "date" => $ticketModel->created_at
                                ]);

                                Yii::$app->commonHelper->sendLicenseMail($ticketModel->ticket_unique_id, $ticketModel->status, $allocatedData, $reqData);

                                Yii::$app->session->setFlash('success', LicenseModule::t('app', 'ticket_created_success'));
                                return $this->redirect(['index']);
                            } else {
                                Yii::$app->session->setFlash('success', LicenseModule::t('app', 'ticket_on_hold_success'));
                                return $this->redirect(['index']);
                            }

                        }
                    }
                }
                return $this->render('accountInfo',
                    [
                        'data' => $data,
                        'licenseTicketModal' => $licenseTicketModal,
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider
                    ]);
            } else {
                Yii::$app->session->setFlash('error', LicenseModule::t('app', 'license_not_assign'));
                return $this->redirect(['/admin/admin/index']);
            }
        }catch(\Exception $e){
            Yii::$app->session->setFlash('error', Yii::t('app', 'something_wrong'));
            return $this->redirect(['/admin/admin/index']);
        }
    }

    public function actionChangeStatus()
    {
        if (!empty($_POST)) {
            if (isset($_POST['id']) && isset($_POST['status'])) {
                $model = LicenseTicketManagement::findOne($_POST['id']);
                $oldStatus = $model->status;
                if($oldStatus == 'On-hold' && $oldStatus != $_POST['status'] && $_POST['status'] != 'Cancelled'){
                    Yii::$app->commonHelper->addTicket([
                        "ticketId" => $model->id,
                        "tenantId" => $GLOBALS['tenantID'],
                        "ticket_unique_id" => $model->ticket_unique_id,
                        "requestType" => 'Licence Modification',
                        "allocated" => $model->allocated,
                        "requested" => $model->requested,
                        "date" => $model->created_at
                    ]);
                }
                $model->status = $_POST['status'];
                if ($model->save()) {
                    if($oldStatus == 'Open' && $_POST['status'] == 'Cancelled'){
                        Yii::$app->commonHelper->updateTicket([
                            "ticketId" => $model->id,
                            "tenantId" => $GLOBALS['tenantID'],
                            "status" => $model->status,
                        ]);

                        Yii::$app->commonHelper->sendLicenseMail($model->ticket_unique_id, $model->status, json_decode($model->allocated, true), json_decode($model->requested, true));
                    }
                    if($oldStatus == 'On-hold' && $_POST['status'] == 'Open'){
                        Yii::$app->commonHelper->sendLicenseMail($model->ticket_unique_id, $model->status, json_decode($model->allocated, true), json_decode($model->requested, true));
                    }
                    Yii::$app->session->setFlash('success', LicenseModule::t('app', 'ticket_status_change_success'));
                    return $this->redirect(['index']);
                }
            }
        }
    }
}
