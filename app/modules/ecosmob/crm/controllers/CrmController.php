<?php

namespace app\modules\ecosmob\crm\controllers;

use app\models\SipRegistrations;
use app\modules\ecosmob\agent\models\Agent;
use app\modules\ecosmob\auth\models\AdminMaster;
use app\modules\ecosmob\callhistory\models\CampCdr;
use app\modules\ecosmob\campaign\models\Campaign;
use app\modules\ecosmob\crm\models\ActiveCalls;
use app\modules\ecosmob\crm\models\AgentDispositionMapping;
use app\modules\ecosmob\crm\models\LeadCommentMapping;
use app\modules\ecosmob\crm\models\LeadGroupMember;
use app\modules\ecosmob\crm\models\LeadGroupMemberSearch;
use app\modules\ecosmob\disposition\models\DispositionGroupStatusMapping;
use app\modules\ecosmob\dispositionType\models\DispositionType;
use app\modules\ecosmob\extension\models\Extension;
use app\modules\ecosmob\queue\models\QueueMaster;
use app\modules\ecosmob\queue\models\Tiers;
use app\modules\ecosmob\realtimedashboard\controllers\UserMonitorController;
use app\modules\ecosmob\script\models\Script;
use app\modules\ecosmob\admin\models\ActiveCallsCount;
use app\modules\ecosmob\supervisor\models\UsersActivityLog;
use Yii;
use yii\db\Expression;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;
use app\components\CommonHelper;

/**
 * CrmController implements the CRUD actions for LeadGroupMember model.
 */
class CrmController extends Controller
{
    CONST BROWSER_TAB_CLOSE_DISPOSITION = 2;
    /**
     * {@inheritdoc}
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
                            'create',
                            'view',
                            'update',
                            'delete',
                            'dial-next',
                            'dial-next-data',
                            'script',
                            'crm',
                            'submit-disposition',
                            'update-lead',
                            'cancel-lead',
                            'progresive-data',
                            'pause-effect',
                            'resume-effect',
                            'active-call',
                            'active-call-delete',
                            'answer-update',
                            'campaign-cdr',
                            'call-status-update',
                            'ringing-status-update',
                            'hangup-update',
                            'update-call-end-time',
                            'hangup-updatecustom',
                            'dispostion-list-type',
                            'customindex',
                            'update-disposition-and-logout',
                            'logout-agent',
                            'login-agent',
                            'idle-time'
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all LeadGroupMember models.
     * @return mixed
     */
    public function actionIndex()
    {
        $selectedCampaignData = (isset($_SESSION['selectedCampaign']) && !empty($_SESSION['selectedCampaign'])) ? $_SESSION['selectedCampaign'] : '0';

        $selectedCampaign = $selectedCampaignData;
        $agentId = Yii::$app->user->identity->adm_id;

        $campaignDialerType = Campaign::find()->select(['cmp_dialer_type', 'cmp_type'])->where(['cmp_id' => $selectedCampaign])->one();

        //$campaignDialerType = !empty($campaignDialerType)?$campaignDialerType:'';

        //$campaignDialerType->cmp_type = !empty($campaignDialerType) ? $campaignDialerType->cmp_type : "0";

        if ($campaignDialerType->cmp_type == 'Outbound' && $campaignDialerType->cmp_dialer_type == 'PREVIEW') {
            $dispotionData = Campaign::find()->select(['cmp_disposition'])->where(['cmp_id' => $selectedCampaign])->one();
            $dispotionData = isset($dispotionData) ? $dispotionData : '';
        } else if ($campaignDialerType->cmp_type == 'Outbound' && $campaignDialerType->cmp_dialer_type == 'PROGRESSIVE') {
            $dispotionData = Campaign::find()->select(['cmp_disposition'])->where(['cmp_id' => $selectedCampaign])->one();
            $dispotionData = isset($dispotionData) ? $dispotionData : '';
        } else if ($campaignDialerType->cmp_type == 'Blended' && $campaignDialerType->cmp_dialer_type == 'AUTO') {
            $dispotionData = Campaign::find()->select(['cmp_disposition'])->where(['cmp_id' => $selectedCampaign])->one();
            $dispotionData = isset($dispotionData) ? $dispotionData : '';
        } else if ($campaignDialerType->cmp_type == 'Inbound') {

            $dispotionData = Campaign::find()->select(['cmp_disposition'])->where(['cmp_id' => $selectedCampaign])->one();
            $dispotionData = isset($dispotionData) ? $dispotionData : '';
        }
        $dispotionData = isset($dispotionData) ? $dispotionData : '';

        $disposionList = DispositionType::find()->select(['ds_type_id'])
            ->from('ct_disposition_type cdt')
            ->innerJoin('ct_disposition_group_status_mapping dgm','dgm.ds_status_id = cdt.ds_type_id')
            ->where(['dgm.ds_group_id' => $dispotionData->cmp_disposition])
            ->asArray()->all();

        $disposionList = isset($disposionList) ? $disposionList : '';

        $disposionIds = implode(",", array_map(function ($a) {
            return implode("~", $a);
        }, $disposionList));

        $disposionData = DispositionType::find()->select(['ds_type_id', 'ds_type'])
            ->andWhere(new Expression('FIND_IN_SET(ds_type_id,"' . $disposionIds . '")'))
            ->asArray()->all();

        $disposionListType = ArrayHelper::map($disposionData, 'ds_type_id', 'ds_type');

        $searchModel = new LeadGroupMemberSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $scrdataProvider = $searchModel->scrsearch(Yii::$app->request->queryParams);
        $extentationNumber = $_SESSION['extentationNumber'];
        $extensionInformation = Extension::find()->select(['em_extension_number', 'em_password', 'em_extension_name'])->where(['em_extension_number' => $extentationNumber])->one();

        $crmList = new LeadGroupMember();
        $progresiveDataList = new LeadGroupMember();
        $LeadComment = new LeadCommentMapping();
        $agentDispoMapping = new AgentDispositionMapping();

        $selectedCampaign = $_SESSION['selectedCampaign'];

        $campaignData = Campaign::find()->select(['cmp_caller_id', 'cmp_caller_name'])->where(['cmp_id' => $selectedCampaign])->asArray()->one();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'crmList' => $crmList,
            'progresiveDataList' => $progresiveDataList,
            'leadCommentMapping' => $LeadComment,
            'dataProvider' => $dataProvider,
            'scrdataProvider' => $scrdataProvider,
            'extensionInformation' => $extensionInformation,
            'campaignDialerType' => $campaignDialerType,
            'disposionListType' => $disposionListType,
            'agentDispoMapping' => $agentDispoMapping,
            'selectedCampaign' => $selectedCampaign,
            'agentId' => $agentId,
            'campaignData' => $campaignData,
        ]);
    }



    /**
     * Lists all LeadGroupMember models.
     * @return mixed
     */
    public function actionCustomindex()
    {
        $selectedCampaignData = (isset($_SESSION['selectedCampaign']) && !empty($_SESSION['selectedCampaign'])) ? $_SESSION['selectedCampaign'] : '0';

        $selectedCampaign = $selectedCampaignData;
        $agentId = Yii::$app->user->identity->adm_id;

        $campaignDialerType = Campaign::find()->select(['cmp_dialer_type', 'cmp_type'])->where(['cmp_id' => $selectedCampaign])->one();

        //$campaignDialerType = !empty($campaignDialerType)?$campaignDialerType:'';

        //$campaignDialerType->cmp_type = !empty($campaignDialerType) ? $campaignDialerType->cmp_type : "0";

        if ($campaignDialerType->cmp_type == 'Outbound' && $campaignDialerType->cmp_dialer_type == 'PREVIEW') {
            $dispotionData = Campaign::find()->select(['cmp_disposition'])->where(['cmp_id' => $selectedCampaign])->one();
            $dispotionData = isset($dispotionData) ? $dispotionData : '';
        } else if ($campaignDialerType->cmp_type == 'Outbound' && $campaignDialerType->cmp_dialer_type == 'PROGRESSIVE') {
            $dispotionData = Campaign::find()->select(['cmp_disposition'])->where(['cmp_id' => $selectedCampaign])->one();
            $dispotionData = isset($dispotionData) ? $dispotionData : '';
        } else if ($campaignDialerType->cmp_type == 'Blended' && $campaignDialerType->cmp_dialer_type == 'AUTO') {
            $dispotionData = Campaign::find()->select(['cmp_disposition'])->where(['cmp_id' => $selectedCampaign])->one();
            $dispotionData = isset($dispotionData) ? $dispotionData : '';
        } else if ($campaignDialerType->cmp_type == 'Inbound') {

            $dispotionData = Campaign::find()->select(['cmp_disposition'])->where(['cmp_id' => $selectedCampaign])->one();
            $dispotionData = isset($dispotionData) ? $dispotionData : '';
        }
        $dispotionData = isset($dispotionData) ? $dispotionData : '';

        $disposionList = DispositionType::find()->select(['ds_type_id'])
            ->from('ct_disposition_type cdt')
            ->innerJoin('ct_disposition_group_status_mapping dgm','dgm.ds_status_id = cdt.ds_type_id')
            ->where(['dgm.ds_group_id' => $dispotionData->cmp_disposition])
            ->asArray()->all();

        $disposionList = isset($disposionList) ? $disposionList : '';

        $disposionIds = implode(",", array_map(function ($a) {
            return implode("~", $a);
        }, $disposionList));

        $disposionData = DispositionType::find()->select(['ds_type_id', 'ds_type'])
            ->andWhere(new Expression('FIND_IN_SET(ds_type_id,"' . $disposionIds . '")'))
            ->asArray()->all();

        $disposionListType = ArrayHelper::map($disposionData, 'ds_type_id', 'ds_type');

        $searchModel = new LeadGroupMemberSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $scrdataProvider = $searchModel->scrsearch(Yii::$app->request->queryParams);
        $extentationNumber = $_SESSION['extentationNumber'];
        $extensionInformation = Extension::find()->select(['em_extension_number', 'em_password', 'em_extension_name'])->where(['em_extension_number' => $extentationNumber])->one();

        $crmList = new LeadGroupMember();
        $progresiveDataList = new LeadGroupMember();
        $LeadComment = new LeadCommentMapping();
        $agentDispoMapping = new AgentDispositionMapping();

        $selectedCampaign = $_SESSION['selectedCampaign'];

        $campaignData = Campaign::find()->select(['cmp_caller_id', 'cmp_caller_name'])->where(['cmp_id' => $selectedCampaign])->asArray()->one();
        return $this->renderPartial('customindex', [
            'searchModel' => $searchModel,
            'crmList' => $crmList,
            'progresiveDataList' => $progresiveDataList,
            'leadCommentMapping' => $LeadComment,
            'dataProvider' => $dataProvider,
            'scrdataProvider' => $scrdataProvider,
            'extensionInformation' => $extensionInformation,
            'campaignDialerType' => $campaignDialerType,
            'disposionListType' => $disposionListType,
            'agentDispoMapping' => $agentDispoMapping,
            'selectedCampaign' => $selectedCampaign,
            'agentId' => $agentId,
            'campaignData' => $campaignData,
        ]);
    }

    public function actionUpdateCallEndTime()
    {
        $current_date = date('Y-m-d H:i:s');
        $callId = Yii::$app->request->post()['callId'];
        Yii::$app->db->createCommand()
            ->update('camp_cdr', (['end_time' => $current_date]), ['call_id' => $callId, 'agent_id' => Yii::$app->user->identity->adm_id])
            ->execute();
    }
    public function actionSubmitDisposition()
    {
        $agentDispoMapping = new AgentDispositionMapping();
        $selectedCampaign = $this->getCampIdWithQueueOrCampName();
        if ($agentDispoMapping->load(Yii::$app->request->post())) {
            if ($agentDispoMapping->validate()) {
                if ($selectedCampaign) {
                    $agentDispoMapping->campaign_id = $selectedCampaign;
                    $agentDispoMapping->agent_id = Yii::$app->user->identity->adm_id;
                    $agentDispoMapping->save();
                }
            }
            $current_date = date('Y-m-d H:i:s');
            $callId = Yii::$app->request->post()['callId'];
            $cause = Yii::$app->request->post()['cause'];

            $dispotionData = Campaign::find()->select(['cmp_disposition'])->where(['cmp_id' => $selectedCampaign])->one();

            $dispotionData = isset($dispotionData) ? $dispotionData : '';
            $dispositionCategory = DispositionGroupStatusMapping::find()->select(['ds_category_id'])
                ->where(['ds_group_id' => $dispotionData->cmp_disposition])
                ->andWhere(['ds_status_id' => Yii::$app->request->post()['AgentDispositionMapping']['disposition']])
                ->one();

            //Update Disposition Data In CMP CDR Table Data
            $disposition_type = '';
            if (isset(Yii::$app->request->post()['AgentDispositionMapping']['disposition']) && !empty(Yii::$app->request->post()['AgentDispositionMapping']['disposition'])) {
                $disposition = DispositionType::findOne(['ds_type_id' => Yii::$app->request->post()['AgentDispositionMapping']['disposition']]);
                if (!empty($disposition))
                    $disposition_type = $disposition->ds_type;
            }
            Yii::$app->db->createCommand()
                ->update('camp_cdr', (['call_disposition_category' => $dispositionCategory->ds_category_id ,'call_disposion_decription' => Yii::$app->request->post()['AgentDispositionMapping']['comment'], 'call_disposion_name' => $disposition_type, 'call_disposion_start_time' => $current_date]), ['call_id' => $callId, 'agent_id' => Yii::$app->user->identity->adm_id])
                ->execute();

            $leadGroupMember = LeadGroupMember::findone($agentDispoMapping->lead_id);
            if($leadGroupMember){
                Yii::$app->db->createCommand()
                    ->update('ct_redial_calls', (['ds_type_id' => Yii::$app->request->post()['AgentDispositionMapping']['disposition'], 'ds_category_id' => $dispositionCategory->ds_category_id]), ['ld_id' => $leadGroupMember->ld_id, 'lgm_id' => $agentDispoMapping->lead_id, 'rd_status' => 1, 'ds_type_id' => null, 'ds_category_id' => null])
                    ->execute();
            }
        }
    }

    public function actionUpdateLead()
    {
        $data = array();
        $crmList = new LeadGroupMember();
        $LeadComment = new LeadCommentMapping();
        if ($crmList->load(Yii::$app->request->post()) && $LeadComment->load(Yii::$app->request->post())) {

            // Save comment
            $LeadComment = LeadCommentMapping::findOne(['lead_id' => $LeadComment->lead_id]);
            $LeadComment->load(Yii::$app->request->post());
            $LeadComment->save(false);

            //Update Lead Data
            $crmList = LeadGroupMember::findOne($LeadComment->lead_id);
            $crmList->load(Yii::$app->request->post());
            if ($crmList->validate()) {
                $crmList->save();
                $data['crmlist'] = $crmList;
                $data['lg_id'] = $crmList->lg_id;
                $data['pk_id'] = $LeadComment->id;
                $data['comment'] = $LeadComment->comment;
                $data['message'] = Yii::t('app', 'updated_success');
                return Json::encode($data);
            }
        }
    }

    public function actionCancelLead()
    {
        $data = array();
        $db_array = LeadGroupMember::find()->where(['lg_id' => $_POST['LeadCommentMapping']['lead_id']])->asArray()->one();
        $db_array_comment = LeadCommentMapping::find()->where(['lead_id' => $_POST['LeadCommentMapping']['lead_id']])->asArray()->one();

        $data['db_array'] = $db_array;
        $data['db_array_comment'] = $db_array_comment;
        return Json::encode($data);
    }

    /**
     * Displays a single LeadGroupMember model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Finds the LeadGroupMember model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return LeadGroupMember the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = LeadGroupMember::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    /**
     * Creates a new LeadGroupMember model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new LeadGroupMember();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Created Successfully'));
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing LeadGroupMember model.
     * If update is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if (Yii::$app->request->post('apply') == 'update') {
                Yii::$app->session->setFlash('success', Yii::t('app', 'applied_success'));
                return $this->refresh();
            } else {
                Yii::$app->session->setFlash('success', Yii::t('app', 'updated_success'));
                return $this->redirect(['index']);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing LeadGroupMember model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', Yii::t('app', 'deleted_success'));
        return $this->redirect(['index']);
    }

    /**
     * @return string|Response
     * @throws \yii\db\Exception
     */
    public function actionDialNextData()
    {
        $selectedCampaignSession = $_SESSION['selectedCampaign'];

        $selectedCampaignArr =  explode(",", $selectedCampaignSession);
        // round and robin logic
        if (count($selectedCampaignArr) > 0) {
            $first = $selectedCampaignArr[0];
            array_shift($selectedCampaignArr);
            array_push($selectedCampaignArr, $first);
            $_SESSION['selectedCampaign'] =  implode(',', $selectedCampaignArr);
        }
        foreach ($selectedCampaignArr as $selectedCampaign) {

            $campaignDataList = Campaign::find()->select(['cmp_lead_group'])->andwhere(['cmp_id' => $selectedCampaign])->andwhere(['cmp_type' => 'Outbound'])->andwhere(['cmp_dialer_type' => 'PREVIEW'])->one();
            $leadData = $campaignDataList['cmp_lead_group'];

            $crmList = LeadGroupMember::find()->select([
                "ct_lead_group_member.lg_id",
                "ct_lead_group_member.ld_id",
                "ct_lead_group_member.lg_first_name",
                "ct_lead_group_member.lg_last_name",
                "ct_lead_group_member.lg_contact_number",
                "ct_lead_group_member.lg_contact_number_2",
                "ct_lead_group_member.lg_email_id",
                "ct_lead_group_member.lg_address",
                "ct_lead_group_member.lg_alternate_number",
                "ct_lead_group_member.lg_pin_code",
                "ct_lead_group_member.lg_permanent_address",
                "cmp.cmp_lead_group AS cmp_lead_group",
                "cmp.cmp_id AS cmp_id",
            ])
                ->join('LEFT JOIN', 'lead_comment_mapping lcm', 'lcm.lead_id = ct_lead_group_member.lg_id')
                ->join('INNER JOIN', 'ct_call_campaign cmp', 'cmp.cmp_lead_group = ct_lead_group_member.ld_id')
                ->where("cmp.cmp_id = '" . $selectedCampaign . "' AND  ( id IS NULL OR lcm.lead_status = 0 )  AND ct_lead_group_member.ld_id = '" . $leadData . "' ")
                ->groupBy('ct_lead_group_member.lg_id')
                ->asArray()
                ->one();


            if ($crmList) {
                //Save on first click of dial next button

                $leadCommentMapping = LeadCommentMapping::find()->where(['lead_id' => $crmList['lg_id']])->one();

                if ((!empty($leadCommentMapping))) {
                    $leadCommentMapping->lead_status = 1;
                    $leadCommentMapping->save(false);
                } else {
                    $leadCommentMapping = new LeadCommentMapping();
                    $leadCommentMapping->lead_id = $crmList['lg_id'];
                    $leadCommentMapping->campaign_id = $selectedCampaign;
                    $leadCommentMapping->agents_id = Yii::$app->user->identity->adm_id;
                    $leadCommentMapping->lead_status = 1;
                    $leadCommentMapping->save(false);

                }
                $crmList['pk_id'] = $leadCommentMapping->id;
                $crmList['comment'] = $leadCommentMapping->comment;

                Yii::$app->db->createCommand()
                    ->update('ct_redial_calls', (['rd_status' => 1]), ['ld_id' => $crmList['ld_id'], 'lgm_id' => $crmList['lg_id'], 'rd_status' => 0])
                    ->execute();

                /*$crmList['pk_id']=$leadCommentMapping->id;
                $crmList['lg_id']=$leadCommentMapping->lead_id;*/
                echo json_encode($crmList, true);
            } else {
                echo '';
            }
            die;
        }

    }

    public function actionProgresiveData()
    {
        $agentsStatus = Agent::find()->select('status')->where(['name' => Yii::$app->user->identity->adm_id.'_'.$GLOBALS['tenantID']])->one();

        if ($agentsStatus['status'] == 'Available') {
            $leadIdData = isset($_POST['lead_id']) ? $_POST['lead_id'] : '';

            $leadCallerIdNumber = isset($_POST['leadCallerIdNumber']) ? $_POST['leadCallerIdNumber'] : '';

            $selectedCampaign = $_SESSION['selectedCampaign'];
            $selectedCampaignArr =  explode(",", $_SESSION['selectedCampaign']);

            $campaignDialerType = Campaign::find()->select(['cmp_dialer_type', 'cmp_type'])->where(['cmp_id' => $selectedCampaign])->one();
            if ($campaignDialerType->cmp_type == 'Outbound' && $campaignDialerType->cmp_dialer_type == 'PROGRESSIVE') {
                $campaignDataList = Campaign::find()->select(['cmp_lead_group'])->where(['cmp_id' => $selectedCampaign])->one();

            } else if ($campaignDialerType->cmp_type == 'Outbound' && $campaignDialerType->cmp_dialer_type == 'PREVIEW') {
                $campaignDataList = Campaign::find()->select(['cmp_lead_group'])->where(['cmp_id' => $selectedCampaign])->one();

            } else if ($campaignDialerType->cmp_type == 'Blended' && $campaignDialerType->cmp_dialer_type == 'AUTO') {
                $campaignDataList = Campaign::find()->select(['cmp_lead_group'])->where(['cmp_id' => $selectedCampaign])->one();
            } else if ($campaignDialerType->cmp_type == 'Inbound') {
                $campaignDataList = Campaign::find()->select(['cmp_lead_group'])->where(['in','cmp_id',$selectedCampaignArr])->asArray()->all();
            }

            $leadData = array_column($campaignDataList,'cmp_lead_group');

            if ($leadIdData) {
                $progresiveDataList = LeadGroupMember::find()->select([
                    "ct_lead_group_member.lg_id",
                    "ct_lead_group_member.lg_first_name",
                    "ct_lead_group_member.lg_last_name",
                    "ct_lead_group_member.lg_contact_number",
                    "ct_lead_group_member.lg_contact_number_2",
                    "ct_lead_group_member.lg_email_id",
                    "ct_lead_group_member.lg_address",
                    "ct_lead_group_member.lg_alternate_number",
                    "ct_lead_group_member.lg_pin_code",
                    "ct_lead_group_member.lg_permanent_address",
                    "cmp.cmp_lead_group AS cmp_lead_group",
                    "cmp.cmp_id AS cmp_id",
                ])
                    ->join('LEFT JOIN', 'lead_comment_mapping lcm', 'lcm.lead_id = ct_lead_group_member.lg_id')
                    ->join('INNER JOIN', 'ct_call_campaign cmp', 'cmp.cmp_lead_group = ct_lead_group_member.ld_id')
                    ->where("cmp.cmp_id = '" . $selectedCampaign . "'
            AND ct_lead_group_member.ld_id = '" . $leadData . "' ");
                if ($leadIdData != '') {
                    $progresiveDataList->andWhere("lg_id = '" . $leadIdData . "'");
                }

                $progresiveDataList->groupBy('ct_lead_group_member.lg_id');
                $data = $progresiveDataList->asArray()->one();
                $progresiveDataList->one();

                if ($data != '') {
                    if ($campaignDialerType->cmp_type == 'Blended' && $campaignDialerType->cmp_dialer_type == 'AUTO') {
                        Yii::$app->db->createCommand()
                            ->update('ct_lead_group_member', (['lg_dial_status' => 'Done']), ['lg_id' => $data['lg_id']])
                            ->execute();
                        Yii::$app->db->createCommand()
                            ->update('ct_redial_calls', (['rd_status' => 1]), ['ld_id' => $leadData, 'lgm_id' => $data['lg_id'], 'rd_status' => 0])
                            ->execute();
                    }

                    $leadCommentMapping = LeadCommentMapping::find()->where(['lead_id' => $data['lg_id']])->one();
                    if (!$leadCommentMapping) {
                        $leadCommentMapping = new LeadCommentMapping();
                    }
                    $leadCommentMapping->lead_id = $data['lg_id'];
                    $leadCommentMapping->campaign_id = $_SESSION['selectedCampaign'];
                    $leadCommentMapping->agents_id = Yii::$app->user->identity->adm_id;
                    $leadCommentMapping->lead_status = 1;
                    $leadCommentMapping->save(false);

                    $data['pk_id'] = $leadCommentMapping->id;
                    $data['comment'] = $leadCommentMapping->comment;
                    $data['data_found'] = 1;
                    echo json_encode($data, true);

                } else {
                    $progresiveData = LeadGroupMember::find()->where(['lg_id' => $_POST['lead_id']])->one();
                    if (!$progresiveData) {
                        $progresiveData = new LeadGroupMember();
                    }

                    $progresiveData->ld_id = $leadData;
                    $progresiveData->lg_id = $_POST['lead_id'];
                    if ($campaignDialerType->cmp_type == 'Blended' && $campaignDialerType->cmp_dialer_type == 'AUTO') {
                        $progresiveData->lg_dial_status = 'Done';
                    }
                    $progresiveData->save(false);

                    Yii::$app->db->createCommand()
                        ->update('ct_redial_calls', (['rd_status' => 1]), ['ld_id' => $leadData, 'lgm_id' => $_POST['lead_id'], 'rd_status' => 0])
                        ->execute();

                    $leadCommentMapping = LeadCommentMapping::find()->where(['lead_id' => $progresiveData->lg_id])->one();
                    if (!$leadCommentMapping) {
                        $leadCommentMapping = new LeadCommentMapping();
                    }
                    $leadCommentMapping->lead_id = $progresiveData->lg_id;
                    $leadCommentMapping->campaign_id = $_SESSION['selectedCampaign'];
                    $leadCommentMapping->agents_id = Yii::$app->user->identity->adm_id;
                    $leadCommentMapping->lead_status = 1;
                    $leadCommentMapping->save(false);

                    $data['lg_id'] = $progresiveData->lg_id;
                    $data['pk_id'] = $leadCommentMapping->id;
                    $data['comment'] = $leadCommentMapping->comment;
                    $data['leadCallerIdNumber'] = $_POST['lead_id'];
                    $data['lead_id'] = $_POST['lead_id'];
                    $data['data_found'] = 0;
                    echo json_encode($data, true);
                }
            } else if ($leadCallerIdNumber) {
                $campaignFromQueue = Campaign::find()->select(['cmp_id','cmp_lead_group'])->where(['cmp_queue_id' => $_POST['activeQueueId']])->one();

                $progresiveDataList = LeadGroupMember::find()->select([
                    "ct_lead_group_member.lg_id",
                    "ct_lead_group_member.lg_first_name",
                    "ct_lead_group_member.lg_last_name",
                    "ct_lead_group_member.lg_contact_number",
                    "ct_lead_group_member.lg_contact_number_2",
                    "ct_lead_group_member.lg_email_id",
                    "ct_lead_group_member.lg_address",
                    "ct_lead_group_member.lg_alternate_number",
                    "ct_lead_group_member.lg_pin_code",
                    "ct_lead_group_member.lg_permanent_address",
                    "cmp.cmp_lead_group AS cmp_lead_group",
                    "cmp.cmp_id AS cmp_id",
                ])
                    ->join('LEFT JOIN', 'lead_comment_mapping lcm', 'lcm.lead_id = ct_lead_group_member.lg_id')
                    ->join('INNER JOIN', 'ct_call_campaign cmp', 'cmp.cmp_lead_group = ct_lead_group_member.ld_id')
                    ->where("cmp.cmp_id = " . $campaignFromQueue->cmp_id . " AND cmp.cmp_queue_id = ".$_POST['activeQueueId']."
AND ct_lead_group_member.ld_id =  " . $campaignFromQueue->cmp_lead_group);
                if ($leadCallerIdNumber != '') {
                    $progresiveDataList->andWhere("lg_contact_number = '" . $leadCallerIdNumber . "' OR lg_contact_number_2 = '" . $leadCallerIdNumber . "' OR lg_alternate_number = '" . $leadCallerIdNumber . "'");
                }

                $progresiveDataList->groupBy('ct_lead_group_member.lg_id');
                $data = $progresiveDataList->asArray()->one();
                $progresiveDataList->one();

                if ($data != '') {
                    if ($campaignDialerType->cmp_type == 'Blended' && $campaignDialerType->cmp_dialer_type == 'AUTO') {
                        Yii::$app->db->createCommand()
                            ->update('ct_lead_group_member', (['lg_dial_status' => 'Done']), ['lg_id' => $data['lg_id']])
                            ->execute();
                        Yii::$app->db->createCommand()
                            ->update('ct_redial_calls', (['rd_status' => 1]), ['ld_id' => $leadData, 'lgm_id' => $data['lg_id'], 'rd_status' => 0])
                            ->execute();
                    }
                    $leadCommentMapping = LeadCommentMapping::find()->where(['lead_id' => $data['lg_id']])->one();
                    if (!$leadCommentMapping) {
                        $leadCommentMapping = new LeadCommentMapping();
                    }
                    $leadCommentMapping->lead_id = $data['lg_id'];
                    $leadCommentMapping->campaign_id = $_SESSION['selectedCampaign'];
                    $leadCommentMapping->agents_id = Yii::$app->user->identity->adm_id;
                    $leadCommentMapping->lead_status = 1;
                    $leadCommentMapping->save(false);

                    $data['pk_id'] = $leadCommentMapping->id;
                    $data['comment'] = $leadCommentMapping->comment;
                    $data['data_found'] = 1;
                    echo json_encode($data, true);

                } else {
                    $queueCamp = $this->getQueueCampName();
                    $camp = Campaign::findOne($queueCamp);
                    $progresiveData = new LeadGroupMember();
                    $progresiveData->ld_id = (!empty($camp) ? $camp->cmp_lead_group : '');

                    $progresiveData->lg_contact_number = $_POST['leadCallerIdNumber'];
                    if ($campaignDialerType->cmp_type == 'Blended' && $campaignDialerType->cmp_dialer_type == 'AUTO') {
                        $progresiveData->lg_dial_status = 'Done';
                    }
                    $progresiveData->save(false);

                    Yii::$app->db->createCommand()
                        ->update('ct_redial_calls', (['rd_status' => 1]), ['ld_id' => $leadData, 'lgm_id' => $progresiveData->lg_id, 'rd_status' => 0])
                        ->execute();

                    $leadCommentMapping = LeadCommentMapping::find()->where(['lead_id' => $progresiveData->lg_id])->one();
                    if (!$leadCommentMapping) {
                        $leadCommentMapping = new LeadCommentMapping();
                    }
                    $leadCommentMapping->lead_id = $progresiveData->lg_id;
                    $leadCommentMapping->campaign_id = $_SESSION['selectedCampaign'];
                    $leadCommentMapping->agents_id = Yii::$app->user->identity->adm_id;
                    $leadCommentMapping->lead_status = 1;
                    $leadCommentMapping->save(false);

                    $data['lg_id'] = $progresiveData->lg_id;
                    $data['pk_id'] = $leadCommentMapping->id;
                    $data['comment'] = $leadCommentMapping->comment;
                    $data['leadCallerIdNumber'] = $_POST['leadCallerIdNumber'];
                    $data['data_found'] = 0;
                    echo json_encode($data, true);
                }
            }

        }
        /*else{
           return $this->renderPartial('_pauseMsg');
        }*/
        die;

    }

    public function actionScript()
    {
        // $selectedCampaignStr = $_SESSION['selectedCampaign'];
        // $selectedCampaignArr = explode(',',$selectedCampaignStr);
        $arr = [];
        //foreach($selectedCampaignArr as $selectedCampaign) {

        $selectedCampaign = $this->getCampIdWithQueueOrCampName();
        if (empty($selectedCampaign)) {
            return " No active call found";
        }

        $campaignDialerType = Campaign::find()->select(['cmp_dialer_type', 'cmp_type'])->where(['cmp_id' => $selectedCampaign])->one();

        if ($campaignDialerType->cmp_type == 'Outbound' && $campaignDialerType->cmp_dialer_type == 'PREVIEW') {
            $scriptListOfDialerTypePrvPro = Campaign::find()->select(['cmp_script', 'cmp_name'])->where(['cmp_id' => $selectedCampaign])->one();

            $scriptListOfDialerTypePrvPro = isset($scriptListOfDialerTypePrvPro) ? $scriptListOfDialerTypePrvPro : '';
            $scriptPrvPro = Script::find()->select(['scr_description'])->where(['scr_id' => $scriptListOfDialerTypePrvPro])->one();

            array_push($arr, [$scriptListOfDialerTypePrvPro->cmp_name => $scriptPrvPro->scr_description]);
        } else if ($campaignDialerType->cmp_type == 'Outbound' && $campaignDialerType->cmp_dialer_type == 'PROGRESSIVE') {
            $scriptListOfDialerTypePrvPro = Campaign::find()->select(['cmp_script', 'cmp_name'])->where(['cmp_id' => $selectedCampaign])->one();
            $scriptListOfDialerTypePrvPro = isset($scriptListOfDialerTypePrvPro) ? $scriptListOfDialerTypePrvPro : '';
            $scriptPrvPro = Script::find()->select(['scr_description'])->where(['scr_id' => $scriptListOfDialerTypePrvPro])->one();

            array_push($arr, [$scriptListOfDialerTypePrvPro->cmp_name => $scriptPrvPro->scr_description]);
        } else if ($campaignDialerType->cmp_type == 'Blended' && $campaignDialerType->cmp_dialer_type == 'AUTO') {
            $scriptListOfDialerTypePrvPro = Campaign::find()->select(['cmp_script', 'cmp_name'])->where(['cmp_id' => $selectedCampaign])->one();
            $scriptListOfDialerTypePrvPro = isset($scriptListOfDialerTypePrvPro) ? $scriptListOfDialerTypePrvPro : '';
            $scriptPrvPro = Script::find()->select(['scr_description'])->where(['scr_id' => $scriptListOfDialerTypePrvPro])->one();

            array_push($arr, [$scriptListOfDialerTypePrvPro->cmp_name => $scriptPrvPro->scr_description]);
        } else if ($campaignDialerType->cmp_type == 'Inbound') {
            $scriptListOfDialerTypePrvPro = Campaign::find()->select(['cmp_script', 'cmp_name'])->where(['cmp_id' => $selectedCampaign])->one();
            $scriptListOfDialerTypePrvPro = isset($scriptListOfDialerTypePrvPro) ? $scriptListOfDialerTypePrvPro : '';
            $scriptPrvPro = Script::find()->select(['scr_description'])->where(['scr_id' => $scriptListOfDialerTypePrvPro])->one();

            array_push($arr, [$scriptListOfDialerTypePrvPro->cmp_name => $scriptPrvPro->scr_description]);
            // }

        }

        if ($scriptPrvPro) {
            return $this->renderPartial('_scriptList', [
                'script' => $scriptPrvPro,
            ]);
        } else {
            return $this->renderPartial('_scriptMsg');
        }
    }

    public function actionCrm()
    {
        $crm = new LeadGroupMember();
        $progresiveDataList = new LeadGroupMember();
        $leadCommentMapping = new LeadCommentMapping();

        return $this->renderPartial('_crm', [
            'crm' => $crm,
            'progresiveDataList' => $progresiveDataList,
            'leadCommentMapping' => $leadCommentMapping
        ]);
    }

    public function actionPauseEffect()
    {
        $agentId = Yii::$app->user->identity->adm_id.'_'.$GLOBALS['tenantID'];
        Yii::$app->db->createCommand()
            ->update('agents', (['status' => 'Logged Out', 'state' => 'Waiting']), ['name' => $agentId])
            ->execute();
    }

    public function actionResumeEffect()
    {
        $agentId = Yii::$app->user->identity->adm_id.'_'.$GLOBALS['tenantID'];

        Yii::$app->db->createCommand()
            ->update('agents', (['status' => 'Available', 'state' => 'Waiting']), ['name' => $agentId])
            ->execute();
    }

    public function actionActiveCall()
    {
        $model = new ActiveCalls();
        $current_date = date('Y-m-d H:i:s');
        if ($model) {
            $selectCampaign = $this->getCampIdWithQueueOrCampName();
            $model->caller_id = Yii::$app->request->post()['activeCallerId'];
            $model->uuid = Yii::$app->request->post()['activeCallId'];
            $model->destination_number = Yii::$app->request->post()['activeDestiNumber'];
            $model->status = Yii::$app->request->post()['activeStateName'];
            $model->agent = Yii::$app->request->post()['activeAgentName'];
            $model->campaign_id = $selectCampaign;
            $model->queue = (isset(Yii::$app->request->post()['activeQueueName']) ? explode('_', Yii::$app->request->post()['activeQueueName'])[0] : '');
            $model->call_start_time = $current_date;
            $model->whisper_uuid = Yii::$app->request->post()['whisperUuid'];
            $model->save(false);
        }

        /* $systemLoad = ActiveCallsCount::find()
            ->where([ 'date' => date('Y-m-d') ])
            ->orderBy(['id'=>SORT_DESC])->one();

        if (!empty($systemLoad) && $systemLoad->start_time == date('H').':00:00')
        {
            $systemLoad->count = $systemLoad->count + 1;
            $systemLoad->save(false);
        }
        else
        {
            $newCount = new ActiveCallsCount();

            $newCount->date = date('Y-m-d');
            $newCount->start_time = date('H').':00:00';
            $newCount->count = 1;

            $newCount->save(false);
        } */


    }

    public function actionCampaignCdr()
    {
        $model = new CampCdr();
        $current_date = date('Y-m-d H:i:s');
        /*if ($model->load(Yii::$app->request->post())) {*/
        if (Yii::$app->request->post()) {
            //$queue = QueueMaster::findone(['BINARY qm_name' => Yii::$app->request->post()['activeQueueName']]);
            $queue = QueueMaster::find()->andWhere(['BINARY(qm_name)' => Yii::$app->request->post()['activeQueueName']])->one();
            $curCamp = null;
            if (!empty($queue)) {
                $campaign = Campaign::find()->select('cmp_id')->andWhere(['cmp_queue_id' => $queue->qm_id])->andWhere(['IN', 'cmp_id', explode(',', Yii::$app->request->post()['activeCampaignName'])])->one();
                if (!empty($campaign)) {
                    $curCamp = $campaign->cmp_id;
                }
            }
            $model->agent_id = Yii::$app->request->post()['activeAgentName'];
            $model->queue = (!empty($queue) ? $queue->qm_id : Yii::$app->request->post()['activeQueueName']);
            $model->caller_id_num = Yii::$app->request->post()['activeCallerId'];
            $model->dial_number = Yii::$app->request->post()['activeDestiNumber'];
            $model->extension_number = Yii::$app->request->post()['extensionNumber'];
            $model->call_id = Yii::$app->request->post()['activeCallId'];
            $model->call_status = Yii::$app->request->post()['activeStateName'];
            $model->start_time = $current_date;
            $model->camp_name = Yii::$app->request->post()['activeCampaignName'];
            $model->lead_member_id = Yii::$app->request->post()['activeLeadGrpMemberId'];
            $model->recording_file = (isset(Yii::$app->request->post()['activeRecording_file'])) ? Yii::$app->request->post()['activeRecording_file'] : '';
            $model->queue_join_time = (isset(Yii::$app->request->post()['activeQueueJoinTime'])) ? Yii::$app->request->post()['activeQueueJoinTime'] : '';
            $model->current_active_camp = $curCamp;
            $model->save();
        }
    }

    public function actionActiveCallDelete()
    {
        if ($_POST['uuID']) {
            $uuID = $_POST['uuID'];
            Yii::$app->db->createCommand()
                ->delete('active_calls', ['uuid' => $uuID, 'agent' => Yii::$app->user->identity->adm_id])
                ->execute();
            Yii::$app->db->createCommand()
                ->delete('active_calls', ['status' => 'destroy'])
                ->execute();
        }

    }

    public function actionAnswerUpdate()
    {
        $current_date = date('Y-m-d H:i:s');
        $updateAnsweringStatus = $_POST['uuIdCallStatus'];
        /*Yii::$app->db->createCommand()
            ->update('active_calls', (['status'=>'Answering', 'call_agent_time'=>$current_date]), ['uuid'=>$updateAnsweringStatus])
            ->execute();*/
        /*Yii::$app->db->createCommand()
                ->update('active_calls', (['status' => 'Answering', 'call_agent_time' => $current_date]), ['uuid' => $updateAnsweringStatus])
                ->execute();*/
        /*Yii::$app->db->createCommand()
            ->update('active_calls', (['status' => 'Answering', 'call_agent_time' => $current_date]), ["uuid = '$updateAnsweringStatus' AND call_agent_time = IS NULL"])
            ->execute(); */

        Yii::$app->db->createCommand("UPDATE active_calls SET status = 'Answering', call_agent_time = '$current_date' WHERE uuid = '$updateAnsweringStatus' AND call_agent_time IS NULL")->execute();
        $agentId = Yii::$app->user->identity->adm_id.'_'.$GLOBALS['tenantID'];

        Yii::$app->db->createCommand()
            ->update('agents', (['state' => 'In a queue call']), ['name' => $agentId])
            ->execute();

        /* $data = Yii::$app->db->createCommand()
                    ->select('call_agent_time')
                    ->from('active_calls')
                    ->where('uuid =:uuid, CharityId=:cha', array(':uuid'=>$updateAnsweringStatus))
                    ->query();
        print_r($data);exit; */

        $data = Yii::$app->db->createCommand("SELECT call_agent_time FROM active_calls WHERE uuid = '$updateAnsweringStatus' AND call_agent_time IS NOT NULL")->queryOne();
        if(!empty($data))
        {
            echo $seconds = strtotime($current_date) - strtotime($data['call_agent_time']);	exit;
            //echo gmdate("H:i:s", $seconds); exit;
        }
        $seconds = 0;
        echo $seconds;exit;
        //echo gmdate("H:i:s", $seconds);exit;
    }

    public function actionHangupUpdatecustom()
    {
        $agentId = Yii::$app->user->identity->adm_id.'_'.$GLOBALS['tenantID'];
        Yii::$app->db->createCommand()
            ->update('agents', (['state' => 'Waiting']), ['name' => $agentId])
            ->execute();
    }

    public function actionHangupUpdate()
    {
        /*$current_date=date('Y-m-d H:i:s');
        $callId=Yii::$app->request->post()['callId'];*/

        /* Yii::$app->db->createCommand()
            ->update('agents', (['state' => 'Waiting']), ['name' => Yii::$app->user->identity->adm_id])
            ->execute(); */
        $agentId = Yii::$app->user->identity->adm_id.'_'.$GLOBALS['tenantID'];
        Yii::$app->db->createCommand()
            ->update('agents', (['state' => 'In a queue call']), ['name' => $agentId])
            ->execute();

        //Update Disposition Data In CMP CDR Table Data
        /* Yii::$app->db->createCommand()
             ->update('camp_cdr', (['end_time'=>$current_date, 'call_disposion_decription'=>Yii::$app->request->post()['AgentDispositionMapping']['comment'], 'call_disposion_name'=>Yii::$app->request->post()['AgentDispositionMapping']['disposition'], 'call_disposion_start_time'=>$current_date]), ['call_id'=>$callId])
             ->execute();*/

    }

    public function actionRingingStatusUpdate()
    {
        if (isset($_GET['anstime'])) {
            $current_date = date('Y-m-d H:i:s');
            $callId = $_POST['uuIdCallStatus'];
            Yii::$app->db->createCommand()
                ->update('camp_cdr', (['ans_time' => $current_date]), ['call_id' => $callId, 'agent_id' => Yii::$app->user->identity->adm_id])
                ->execute();
        } else {
            $current_date = date('Y-m-d H:i:s');
            $callId = $_POST['uuIdCallStatus'];
            Yii::$app->db->createCommand()
                ->update('camp_cdr', (['start_time' => $current_date]), ['call_id' => $callId, 'agent_id' => Yii::$app->user->identity->adm_id])
                ->execute();
        }
    }

    public function actionCallStatusUpdate()
    {
        $callId = $_POST['uuIdStatus'];
        $updateCallStatus = $_POST['activeStateName'];
        Yii::$app->db->createCommand()
            ->update('camp_cdr', (['call_status' => $updateCallStatus]), ['call_id' => $callId, 'agent_id' => Yii::$app->user->identity->adm_id])
            ->execute();
    }
    public function actionDispostionListType()
    {
        $result = ['msg'=>'','data'=>''];
        $selectedCampaign =   $this->getCampIdWithQueueOrCampName();
        if (empty($selectedCampaign)) {
            $result['msg']="No active call found";
        }else{

            $campaignDialerType = Campaign::find()->select(['cmp_dialer_type', 'cmp_type'])->where(['cmp_id' => $selectedCampaign])->one();

            $campaignDialerType = !empty($campaignDialerType) ? $campaignDialerType : '';

            $campaignDialerType->cmp_type = !empty($campaignDialerType) ? $campaignDialerType->cmp_type : "0";

            if ($campaignDialerType->cmp_type == 'Outbound' && $campaignDialerType->cmp_dialer_type == 'PREVIEW') {
                $dispotionData = Campaign::find()->select(['cmp_disposition'])->where(['cmp_id' => $selectedCampaign])->one();
                $dispotionData = isset($dispotionData) ? $dispotionData : '';
            } else if ($campaignDialerType->cmp_type == 'Outbound' && $campaignDialerType->cmp_dialer_type == 'PROGRESSIVE') {
                $dispotionData = Campaign::find()->select(['cmp_disposition'])->where(['cmp_id' => $selectedCampaign])->one();
                $dispotionData = isset($dispotionData) ? $dispotionData : '';
            } else if ($campaignDialerType->cmp_type == 'Blended' && $campaignDialerType->cmp_dialer_type == 'AUTO') {
                $dispotionData = Campaign::find()->select(['cmp_disposition'])->where(['cmp_id' => $selectedCampaign])->one();
                $dispotionData = isset($dispotionData) ? $dispotionData : '';
            } else if ($campaignDialerType->cmp_type == 'Inbound') {

                $dispotionData = Campaign::find()->select(['cmp_disposition'])->where(['cmp_id' => $selectedCampaign])->one();
                $dispotionData = isset($dispotionData) ? $dispotionData : '';
            }
            $dispotionData = isset($dispotionData) ? $dispotionData : '';

//            $disposionList = DispositionType::find()->select(['ds_type_id'])->where(['ds_id' => $dispotionData])->asArray()->all();
//            $disposionList = isset($disposionList) ? $disposionList : '';

            $disposionList = DispositionType::find()->select(['ds_type_id'])
                ->from('ct_disposition_type cdt')
                ->innerJoin('ct_disposition_group_status_mapping dgm','dgm.ds_status_id = cdt.ds_type_id')
                ->where(['dgm.ds_group_id' => $dispotionData->cmp_disposition])
                ->asArray()->all();

            $disposionList = isset($disposionList) ? $disposionList : '';

            $disposionIds = implode(",", array_map(function ($a) {
                return implode("~", $a);
            }, $disposionList));

//            $disposionData = DispositionType::find()->select(['ds_type_id', 'ds_type'])
//                ->andWhere(new Expression('FIND_IN_SET(ds_type_id,"' . $disposionIds . '")'))
//                ->asArray()->all();
//
//            $disposionListType = ArrayHelper::map($disposionData, 'ds_type_id', 'ds_type');

            $disposionData = DispositionType::find()->select(['ds_type_id', 'ds_type'])
                ->andWhere(new Expression('FIND_IN_SET(ds_type_id,"' . $disposionIds . '")'))
                ->asArray()->all();

            $disposionListType = ArrayHelper::map($disposionData, 'ds_type_id', 'ds_type');

            $result['data'] = $disposionListType;
        }
        echo json_encode($result, true);
        exit;
    }
    public function getCampIdWithQueueOrCampName()
    {
        $selectedCampaign = "";
        $postData = Yii::$app->request->post();
        if(isset($postData['activeCampaignName'])){
            $selectedCampaign = Yii::$app->request->post()['activeCampaignName'];
        }

        if (empty($selectedCampaign)) {
            $queueId = isset($postData['activeQueueId']) ? $postData['activeQueueId'] : "";
            $queueName = isset($postData['activeQueueName']) ? $postData['activeQueueName'] : "";
            if(!empty($queueId)) {
                $connection = Yii::$app->getDb();
                $command = $connection->createCommand("
                            SELECT *
                            FROM ct_call_campaign 
                            WHERE cmp_queue_id = '$queueId'");
                $result = $command->queryAll();
                $selectedCampaign = !empty($result) ? $result[0]['cmp_id'] : '';
            }elseif(!empty($queueName)) {
                $connection = Yii::$app->getDb();
                $command = $connection->createCommand("
                            SELECT ct_call_campaign.cmp_id
                            FROM ct_call_campaign 
                            LEFT JOIN ct_queue_master ON ct_queue_master.qm_id = ct_call_campaign.cmp_queue_id
                            WHERE ct_queue_master.qm_name = '".$queueName."'");
                $result = $command->queryAll();
                $selectedCampaign = !empty($result) ? $result[0]['cmp_id'] : '';
            }else{
                return;
            }
        }
        return $selectedCampaign;
    }
    public function getQueueCampName()
    {
        $selectedCampaign = "";
        $postData = Yii::$app->request->post();

        $queueId = isset($postData['activeQueueId']) ? $postData['activeQueueId'] : "";
        $queueName = isset($postData['activeQueueName']) ? $postData['activeQueueName'] : "";
        if (!empty($queueId)) {
            $connection = Yii::$app->getDb();
            $command = $connection->createCommand("
                        SELECT *
                        FROM ct_call_campaign
                        WHERE cmp_id IN (".$_SESSION['selectedCampaign'].") AND cmp_queue_id = '$queueId'");
            $result = $command->queryAll();
            $selectedCampaign = !empty($result) ? $result[0]['cmp_id'] : '';
        } elseif (!empty($queueName)) {
            $connection = Yii::$app->getDb();
            $command = $connection->createCommand("
                        SELECT ct_call_campaign.cmp_id
                        FROM ct_call_campaign
                        LEFT JOIN ct_queue_master ON ct_queue_master.qm_id = ct_call_campaign.cmp_queue_id
                        WHERE ct_call_campaign.cmp_id IN (".$_SESSION['selectedCampaign'].") AND ct_queue_master.qm_name = '" . $queueName . "'");
            $result = $command->queryAll();
            $selectedCampaign = !empty($result) ? $result[0]['cmp_id'] : '';
        }
        return $selectedCampaign;
    }

    public function actionUpdateDispositionAndLogout()
    {
        $current_date = date('Y-m-d H:i:s');
        $callId = Yii::$app->request->post()['callId'];

        Yii::$app->db->createCommand()
            ->update('camp_cdr', (['end_time' => $current_date]), ['call_id' => $callId, 'agent_id' => Yii::$app->user->identity->adm_id])
            ->execute();

        $id = Yii::$app->user->identity->adm_id;
        $campCdr = CampCdr::find()->where(['call_id' => $callId, 'agent_id' => $id])->all();
        if(!empty($campCdr)) {
            $dispositionType = DispositionType::find()->where(['is_default' => self::BROWSER_TAB_CLOSE_DISPOSITION])->one();
            Yii::$app->db->createCommand()
                ->update('camp_cdr', (['call_disposion_start_time' => $current_date, 'call_disposion_name' => $dispositionType->ds_type, 'call_disposition_category' => 2]), ['call_id' => $callId, 'agent_id' => $id])
                ->execute();

            foreach ($campCdr as $_campCdr) {

                $leadId = (!empty($_campCdr->lead_member_id) ? $_campCdr->lead_member_id : null);
                $campId = (!empty($_campCdr->current_active_camp) ? $_campCdr->current_active_camp : $_campCdr->camp_name);
                $dsId = $dispositionType->ds_type_id;

                $agentMapping = new AgentDispositionMapping();
                $agentMapping->agent_id = $id;
                $agentMapping->lead_id = $leadId;
                $agentMapping->disposition = $dsId;
                $agentMapping->campaign_id = $campId;
                $agentMapping->save(false);

            }
        }

        Yii::$app->db->createCommand()
            ->delete('active_calls', ['agent' => $id])
            ->execute();

        Yii::$app->db->createCommand()
            ->update('agents', (['status' => 'Logged Out', 'state' => 'Waiting']), ['name' => $id . '_' . $GLOBALS['tenantID']])
            ->execute();

        $extension = Extension::findOne(Yii::$app->user->identity->adm_mapped_extension);
        SipRegistrations::deleteAll(['AND', ['sip_user' => $extension->em_extension_number], ['sip_host' => $_SERVER['HTTP_HOST']], ['LIKE', 'user_agent', 'SIP.js/0.21.2%', false]]);

        UserMonitorController::agentLogoutEntry($id);
        Yii::$app->session->destroySession($id);
        Yii::$app->db->createCommand()
            ->update('admin_master', (['adm_token' => Yii::$app->security->generateRandomString()]), ['adm_id' => $id])
            ->execute();
    }
    public function actionLogoutAgent()
    {
        $id = Yii::$app->user->identity->adm_id;
        Yii::$app->db->createCommand()
            ->update('agents', (['status' => 'Logged Out', 'state' => 'Waiting']), ['name' => $id . '_' . $GLOBALS['tenantID']])
            ->execute();

        $extension = Extension::findOne(Yii::$app->user->identity->adm_mapped_extension);
        SipRegistrations::deleteAll(['AND', ['sip_user' => $extension->em_extension_number], ['sip_host' => $_SERVER['HTTP_HOST']], ['LIKE', 'user_agent', 'SIP.js/0.21.2%', false]]);

        //UserMonitorController::agentLogoutEntry($id);
        Tiers::deleteAll(['agent' => $id . '_' . $GLOBALS['tenantID']]);
        if (!Yii::$app->user->id) {
            Yii::$app->db->createCommand()
                ->update('break_reason_mapping', (['break_status' => 'Out', 'out_time' => date('Y-m-d H:i:s')]), ['user_id' => $id, 'out_time' => '0000-00-00 00:00:00'])
                ->execute();
        }
        Yii::$app->db->createCommand()
            ->update('users_activity_log', (['logout_time' => date('Y-m-d H:i:s')]), ['user_id' => $id, 'logout_time' => '0000-00-00 00:00:00'])
            ->execute();
        /*Yii::$app->session->destroySession($id);
        Yii::$app->db->createCommand()
            ->update('admin_master', (['adm_token' => Yii::$app->security->generateRandomString()]), ['adm_id' => $id])
            ->execute();*/
    }
    public function actionLoginAgent()
    {
        $id = Yii::$app->user->identity->adm_id;
        $agentName = Yii::$app->user->identity->adm_id . '_' . $GLOBALS['tenantID'];
        $userLog = UsersActivityLog::find()
            ->where(['user_id' => $id])
            ->andWhere(['logout_time' => '0000-00-00 00:00:00'])
            ->asArray()->all();
        if(empty($userLog)) {
            Yii::$app->db->createCommand()
                ->update('agents', (['status' => 'Available', 'state' => 'Waiting']), ['name' => $agentName])
                ->execute();
            Yii::$app->db->createCommand()
                ->delete('active_calls', ['agent' => Yii::$app->user->id])
                ->execute();

            Tiers::deleteAll(['agent' => $agentName]);
            $cmpListData = Yii::$app->session->get('selectedCampaign');
            $cmpListData = explode(',', $cmpListData);
            foreach ($cmpListData as $cmpList) {
                $queue = Campaign::find()->select('cmp_queue_id')->where(['cmp_id' => $cmpList])->one();
                $queueName = QueueMaster::find()->select(['qm_name'])->where(['qm_id' => $queue['cmp_queue_id']])->one();

                if(!empty($queueName)) {
                    Yii::$app->db->createCommand()->insert('tiers',
                        [
                            'queue' => $queueName['qm_name'],
                            'agent' => $agentName,
                        ])->execute();
                }
            }
            $activityLog = new UsersActivityLog();
            $activityLog->user_id = Yii::$app->user->identity->adm_id;
            $activityLog->login_time = date('Y-m-d H:i:s');
            $activityLog->campaign_name = Yii::$app->session->get('selectedCampaign');
            $activityLog->last_activity_time = date('Y-m-d H:i:s');
            $activityLog->save(false);
        }
    }

    public function actionIdleTime()
    {
        if(isset(Yii::$app->user->identity->adm_is_admin)) {
            if (isset($_GET['lastInteractionTime']) && Yii::$app->user->identity->adm_is_admin == 'agent') {
                $id = Yii::$app->user->identity->adm_id;
                $lastActivityTime = CommonHelper::DtTots(date('Y-m-d') . ' ' . $_GET['lastInteractionTime']);
                Yii::$app->db->createCommand()
                    ->update('users_activity_log', (['last_activity_time' => $lastActivityTime]), ['user_id' => $id, 'logout_time' => '0000-00-00 00:00:00'])
                    ->execute();
            }
        }
    }
}
