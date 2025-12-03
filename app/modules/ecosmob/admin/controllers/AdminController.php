<?php

namespace app\modules\ecosmob\admin\controllers;

use app\components\CommonHelper;
use app\components\ConstantHelper;
use app\models\Channels;
use app\models\ChannelsSearch;
use app\models\LicenseTicketManagement;
use app\modules\ecosmob\admin\AdminModule;
use app\modules\ecosmob\admin\models\AdminMaster;
use app\modules\ecosmob\admin\models\SystemUsage;
use app\modules\ecosmob\blacklist\BlackListModule;
use app\modules\ecosmob\campaign\models\Campaign;
use app\modules\ecosmob\carriertrunk\models\TrunkMaster;
use app\modules\ecosmob\cdr\models\Cdr;
use app\modules\ecosmob\didmanagement\models\DidManagement;
use app\modules\ecosmob\extension\models\Extension;
use Throwable;
use Yii;
use yii\base\InvalidArgumentException;
use yii\data\ActiveDataProvider;
use yii\db\Exception;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use DateTime;

/*use app\modules\ecosmob\tenantmaster\models\TenantMaster;*/

/**
 * Class AdminController
 *
 * Admin Management : Admin Dashboard Activity, Profile Updating, Change Password etc.
 *
 * @model   AdminMaster require
 * @package app\modules\ecosmob\admin\controllers
 */
class AdminController extends Controller
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
                            'tenant-dashboard',
                            'update-profile',
                            'change-password',
                            'real-time-dashboard',
                            'customupdate-profile',
                            'customchange-password',
                            'get-data',
                            'real-time-dashboard',
                            'active-calls-export'
                        ],
                        'allow' => TRUE,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Action Index Activity
     *
     * Render the index page on index action activity.
     *Search123
     *
     * @return string
     * @throws InvalidArgumentException
     */

    public function actionIndex()
    {
        //return $this->adminDashboard();
        if (Yii::$app->session->get('loginAsExtension')) {
            //$this->redirect(['/extension/extension/dashboard']);
            $this->redirect(Url::base(true) . "/index.php?r=extension/extension");
        } /* if (Yii::$app->user->identity->adm_is_admin == 'super_admin') {
         }*/ else if (Yii::$app->user->identity->adm_is_admin == 'tenant_admin') {
            return $this->actionTenantDashboard();
        } else if (Yii::$app->user->identity->adm_is_admin == 'supervisor') {
            //return $this->adminDashboard();
            $this->redirect(['/supervisor/supervisor/dashboard']);
        } else if (Yii::$app->user->identity->adm_is_admin == 'agent') {
            if (!empty(Yii::$app->session->get('selectedCampaign'))) {
                //$this->redirect(['/agents/agents/dashboard']);

                //$this->redirect(['/agents/agents/customashboard']);
                $this->redirect(Url::base(true) . "/index.php?r=agents/agents");


                /* if(isset(Yii::$app->user->identity->adm_username) && (Yii::$app->user->identity->adm_username == "hardiksarodiya123" || Yii::$app->user->identity->adm_username == "manishthakor123"))
                 {
                    $this->redirect(['/agents/agents/customdashboard']);
                 }
                 else
                 {
                    $this->redirect(['/agents/agents/dashboard']);
                 }*/

            } else {
                Yii::$app->user->logout();
                $this->goHome();
            }
            //return $this->adminDashboard();
        } else if (Yii::$app->user->identity->adm_is_admin == 'tenant_operator') {
            return $this->actionOperatorDashboard();
        } else {
            return $this->adminDashboard();
        }
    }

    /**
     * @return string
     * @throws InvalidArgumentException
     */
    public function actionTenantDashboard()
    {
        $totalOperator = 10;//OperatorMaster::find()->count();
        $activeOperator = 10;//OperatorMaster::find()->where( [ 'op_status' => '1' ] )->count();
        $inactiveOperator = 10;//OperatorMaster::find()->where( [ 'op_status' => '0' ] )->count();

        $isTcAccepted = 10;//TenantMaster::getDataFromEmail( Yii::$app->user->identity->adm_email );

        if (Yii::$app->request->post('tm_terms_status') == '1') {
            $isTcAccepted->tm_terms_status = '0';
            try {
                $isTcAccepted->save();
            } catch (Exception $exception) {

            }
        }

        return $this->render('tenantDashboard',
            [
                'totalOperator' => $totalOperator,
                'activeOperator' => $activeOperator,
                'inactiveOperator' => $inactiveOperator,
                'tAndCStatus' => $isTcAccepted->tm_terms_status,
            ]);

    }

    /**
     * @return string
     * @throws InvalidArgumentException
     */
    public function actionOperatorDashboard()
    {

        $totalPaymentId = 10;/*Order::find()
            ->where(['operator_id' => Yii::$app->user->identity->adm_user_id])
            ->count();*/
        $activePaymentId = 10;/*Order::find()
            ->where(['operator_id' => Yii::$app->user->identity->adm_user_id])
            ->andWhere( [ 'transaction_status' => '0' ] )
            ->count();*/
        $inactivePaymentId = 10;/*Order::find()
            ->where(['operator_id' => Yii::$app->user->identity->adm_user_id])
            ->andWhere( [ 'transaction_status' => '0' ] )
            ->andWhere( [ '<','payment_id_expiry_date',date('Y-m-d') ] )
            ->count();*/

        $totalTransaction = 10;/*Order::find()
            ->where(['operator_id' => Yii::$app->user->identity->adm_user_id])
            ->count();*/
        $successTransaction = 10;/*Order::find()
            ->where(['operator_id' => Yii::$app->user->identity->adm_user_id])
            ->andWhere( [ 'transaction_status' => '1' ] )
            ->count();*/
        $failTransaction = 10;/*Order::find()
            ->where(['operator_id' => Yii::$app->user->identity->adm_user_id])
            ->andWhere( [ 'transaction_status' => '0' ] )
            ->count();*/

        $tnId = 1;// OperatorMaster::getTenantEmail(Yii::$app->user->identity->adm_user_id);
        $merchantId = 2;//TenantMaster::getPlanExpiryDate($tnId);


        return $this->render('operator-dashboard',
            [
                'totalTransaction' => $totalTransaction,
                'successTransaction' => $successTransaction,
                'failTransaction' => $failTransaction,

                'merchantId' => 10,//$merchantId->tm_merchant_id,

                'totalPaymentId' => $totalPaymentId,
                'activePaymentId' => $activePaymentId,
                'inactivePaymentId' => $inactivePaymentId,

            ]);
    }

    /**
     * @return string
     * @throws InvalidArgumentException
     */
    public function adminDashboard()
    {
        $subAdmin = AdminMaster::find()->where(['not in', 'adm_is_admin', ['supervisor', 'agent', 'super_admin']])->count();
        $supervisor = AdminMaster::find()->where(['adm_is_admin' => 'supervisor'])->count();
        $agent = AdminMaster::find()->where(['adm_is_admin' => 'agent'])->count();
        $extension = Extension::find()->count();
        $systemLoad = SystemUsage::find()->orderBy(['sys_id' => SORT_DESC])->asArray()->one();
        $inbound = Campaign::find()->where(['cmp_type' => 'Inbound'])->count();
        $outbound = Campaign::find()->where(['cmp_type' => 'Outbound'])->count();
        $blended = Campaign::find()->where(['cmp_type' => 'Blended'])->count();

//        $currentTime = date('Y-m-d H:i:s',strtotime(CommonHelper::DtTots(date('Y-m-d H:i:s'))));
//        $oneHourAgo = date('Y-m-d H:i:s',strtotime(CommonHelper::DtTots(date('Y-m-d H:i:s',strtotime('-1 hour')))));

        $oneHourAgo = gmdate('Y-m-d H:i:s', strtotime('-1 hour'));
        $currentTime = gmdate('Y-m-d H:i:s');

        list($CPS,$ACD,$ASR) = $this->getCallSummaryStatastics($oneHourAgo, $currentTime);

        $totalDid = 10;//Didmaster::find()->count();
        $activeDid = 10;//Didmaster::find()->where( [ 'did_status' => 'Y' ] )->count();
        $inactiveDid = 10;//Didmaster::find()->where( [ 'did_status' => 'N' ] )->count();

        $totalTenant = 10;//TenantMaster::find()->count();
        $activeTenant = 10;//TenantMaster::find()->where( [ 'tm_status' => '1' ] )->count();
        $inActiveTenant = 10;//TenantMaster::find()->where( [ 'tm_status' => '0' ] )->count();

        $inboundTrunk = 10;//InboundTrunk::find()->count();
        $outboundTrunk = 10;//OutboundTrunk::find()->count();
        $totalTrunk = $inboundTrunk + $outboundTrunk;

        $totalOperators = 10;//OperatorMaster::find()->count();
        $activeOperators = 10;//OperatorMaster::find()->where( [ 'op_status' => '1' ] )->count();
        $inActiveOperators = 10;//OperatorMaster::find()->where( [ 'op_status' => '0' ] )->count();

        return $this->render('index',
            [
                'CPS' => $CPS,
                'ACD' => $ACD,
                'ASR' => $ASR,
                'subAdmin' => $subAdmin,
                'supervisor' => $supervisor,
                'agent' => $agent,
                'extension' => $extension,
                'inbound' => $inbound,
                'outbound' => $outbound,
                'blended' => $blended,
                'totalDid' => $totalDid,
                'activeDid' => $activeDid,
                'inactiveDid' => $inactiveDid,
                'totalTenant' => $totalTenant,
                'activeTenant' => $activeTenant,
                'inActiveTenant' => $inActiveTenant,
                'totalTrunk' => $totalTrunk,
                'inboundTrunk' => $inboundTrunk,
                'outboundTrunk' => $outboundTrunk,
                'totalOperators' => $totalOperators,
                'activeOperators' => $activeOperators,
                'inActiveOperators' => $inActiveOperators,
                'sys_server_time' => strtotime($systemLoad['sys_server_time']),
                'currentTime' => date('H:i:s',strtotime($currentTime)),
                'oneHourAgo' => date('H:i:s',strtotime($oneHourAgo))
            ]);
    }
    public function convertDecimalToTime($decimalHours) {
        // Extract hours
        $hours = floor($decimalHours);

        // Calculate remaining minutes
        $decimalMinutes = ($decimalHours - $hours) * 60;
        $minutes = floor($decimalMinutes);

        // Calculate remaining seconds
        $seconds = round(($decimalMinutes - $minutes) * 60);

        // Format as h:i:s
        return sprintf("%02d:%02d:%02d", $hours, $minutes, $seconds);
    }

// Example usage
    /**
     * Change Password Activity
     *
     * Admin User Change his password
     *
     * @return string|Response
     * @throws NotFoundHttpException
     * @throws \Exception
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function actionChangePassword()
    {
        if (Yii::$app->session->get('loginAsExtension')) {
            /** @var Extension $model */
            $model = Extension::findOne(['em_extension_number' => Yii::$app->user->identity->em_extension_number]);
            $oldPassword = $model->em_web_password;
        } else {
            /** @var AdminMaster $model */
            $model = $this->findModelByEmail(Yii::$app->user->identity->adm_email);
            $oldPassword = $model->adm_password;
        }
        // Set Scenario
        $model->setScenario('changePassword');
        // Load form attributes in $Model and Validate values
        if ($model->load(Yii::$app->request->post()) && $model->validate(['oldPassword', 'newPassword', 'confirmPassword'])) {

            if (Yii::$app->session->get('loginAsExtension')) {
                $enc_pass = $model->em_web_password = $model->newPassword;
            } else {
                $enc_pass = $model->adm_password = md5($model->newPassword);
                $model->adm_password_hash = base64_encode($model->newPassword);
            }

            if ($oldPassword == $enc_pass) {
                Yii::$app->getSession()->setFlash('error', AdminModule::t('admin', 'same_password'), TRUE);

                return $this->render('changePassword', ['model' => $model]);
            }
            if (Yii::$app->session->get('loginAsExtension')) {
                $status = $model->update(FALSE, ['em_web_password']);
            } else {
                $status = $model->update(FALSE, ['adm_password', 'adm_password_hash']);
            }
            // Update only Password Field

            if ($status) {
                // Set success flash message
                Yii::$app->getSession()->setFlash(
                    'success',
                    AdminModule::t('admin', 'changed_success'),
                    TRUE
                );

                //return $this->redirect('index');
                return $this->refresh();
            } else {
                // Set error flash message
                Yii::$app->getSession()->setFlash('error', AdminModule::t('admin', 'something_wrong'), TRUE);

                return $this->render('changePassword', ['model' => $model]);
            }
        }
        if (Yii::$app->session->get('loginAsExtension')) {
            return $this->renderPartial(
                'changePassword',
                [
                    'model' => $model,
                ]);
        }else{
            return $this->render(
                'changePassword',
                [
                    'model' => $model,
                ]);
        }
    }

    /**
     * Finds the Admin model based on its email id.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param $email
     *
     * @return AdminMaster
     * @throws NotFoundHttpException if the model cannot be found
     *
     */
    protected function findModelByEmail($email)
    {
        /** @var AdminMaster $model */
        if (($model = AdminMaster::findOne(['adm_email' => $email])) !== NULL) {
            return $model;
        } else {
            throw new NotFoundHttpException(AdminModule::t('admin', 'page_not_exit'));
        }
    }

    /**
     * Agent Change Password Activity
     *
     * Admin User Change his password
     *
     * @return string|Response
     * @throws NotFoundHttpException
     * @throws \Exception
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function actionCustomchangePassword()
    {
        if (Yii::$app->session->get('loginAsExtension')) {
            /** @var Extension $model */
            $model = Extension::findOne(['em_extension_number' => Yii::$app->user->identity->em_extension_number]);
            $oldPassword = $model->em_web_password;
        } else {
            /** @var AdminMaster $model */
            $model = $this->findModelByEmail(Yii::$app->user->identity->adm_email);
            $oldPassword = $model->adm_password;
        }
        // Set Scenario
        $model->setScenario('changePassword');
        // Load form attributes in $Model and Validate values
        if ($model->load(Yii::$app->request->post()) && $model->validate(['oldPassword', 'newPassword', 'confirmPassword'])) {

            if (Yii::$app->session->get('loginAsExtension')) {
                $enc_pass = $model->em_web_password = $model->newPassword;
            } else {
                $enc_pass = $model->adm_password = md5($model->newPassword);
                $model->adm_password_hash = base64_encode($model->newPassword);
            }

            if ($oldPassword == $enc_pass) {
                Yii::$app->getSession()->setFlash('error', AdminModule::t('admin', 'same_password'), TRUE);

                //return $this->render('customchangePassword', ['model'=>$model]);
                return $this->refresh();
                //return $this->redirect(['customchange-password', 'action' => 'update']);
            }
            if (Yii::$app->session->get('loginAsExtension')) {
                $status = $model->update(FALSE, ['em_web_password']);
            } else {
                $status = $model->update(FALSE, ['adm_password', 'adm_password_hash']);
            }
            // Update only Password Field

            if ($status) {
                // Set success flash message
                Yii::$app->getSession()->setFlash(
                    'success',
                    AdminModule::t('admin', 'changed_success'),
                    TRUE
                );
                //return $this->redirect('index');
                return $this->refresh();
                //return $this->redirect(['customdashboard']);
            } else {
                // Set error flash message
                Yii::$app->getSession()->setFlash('error', AdminModule::t('admin', 'something_wrong'), TRUE);

                return $this->render('customchangePassword', ['model' => $model]);
            }
        }

        return $this->renderPartial(
            'customchangePassword',
            [
                'model' => $model,
            ]);
    }

    /**
     * Update Profile Activity
     *
     * Admin User Update his information
     *
     * @return string|Response
     * @throws NotFoundHttpException
     * @throws InvalidArgumentException
     */
    public function actionUpdateProfile()
    {
        /** @var AdminMaster $model */
        $model = $this->findModelByEmail(Yii::$app->user->identity->adm_email);
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {

                $requestdata = Yii::$app->request->post();
                $admindata = $requestdata['AdminMaster'];

                $model->adm_contact = str_replace(['(', ')', ' '], '', $model->adm_contact);
                $model->is_auto_login =  (isset($_POST['is_auto_login']) ? $_POST['is_auto_login'] : 0);
                if(Yii::$app->user->identity->adm_is_admin == 'super_admin') {
                    $updateTenant = Yii::$app->commonHelper->updateTenantProfile(["uuid" => $model->uuid,
                        "firstName" => $model->adm_firstname,
                        "lastName" => $model->adm_lastname,
                        "mobile" => $model->adm_contact,
                        "timezoneId" => $model->adm_timezone_id,
                        "language" => Yii::$app->language,
                        "isQuickLogin" => $model->is_auto_login]);
                }else{
                    $updateTenant['status'] = 200;
                }
                if (!empty($updateTenant) && isset($updateTenant['status'])) {
                    if ($updateTenant['status'] == 200) {
                        unset($model->adm_email);
                        unset($model->adm_is_admin);
                        if ($model->save(FALSE)) {
                            $transaction->commit();
                            if (Yii::$app->request->post('apply') == 'update') {
                                //Yii::$app->session->setFlash('success', AdminModule::t('admin', 'applied_success'));
                                Yii::$app->session->setFlash('success', AdminModule::t('admin', 'updated_success'));
                                return $this->refresh();
                            } else {
                                Yii::$app->session->setFlash('success', AdminModule::t('admin', 'updated_success'));

                                return $this->redirect(['index']);
                            }
                        }
                    } else {
                        $transaction->rollBack();
                        Yii::$app->getSession()->setFlash('error', 'Update Tenant API Response : ' . $updateTenant['message']);
                    }
                } else {
                    $transaction->rollBack();
                    Yii::$app->getSession()->setFlash('error', AdminModule::t('admin', 'Not Getting Response from API.'));
                }

            }
        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::$app->getSession()->setFlash('error', $e->getMessage());
        }

        return $this->render(
            'updateProfile',
            [
                'model' => $model,
            ]);
    }


    /**
     * Update Profile Activity
     *
     * Admin User Update his information
     *
     * @return string|Response
     * @throws NotFoundHttpException
     * @throws InvalidArgumentException
     */
    public function actionCustomupdateProfile()
    {
        /** @var AdminMaster $model */
        $model = $this->findModelByEmail(Yii::$app->user->identity->adm_email);

        $oldEmail = $model->adm_email;
        $oldIsAdmin = $model->adm_is_admin;
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

           /* $requestdata = Yii::$app->request->post();
            $admindata = $requestdata['AdminMaster'];
            unset($model->adm_email);
            unset($model->adm_is_admin);*/
            $model->adm_email = $oldEmail;
            $model->adm_is_admin = $oldIsAdmin;
            $model->adm_contact = str_replace(['(', ')', ' '], '', $model->adm_contact);
            if ($model->save()) {
                if (Yii::$app->request->post('apply') == 'update') {
                    Yii::$app->session->setFlash('success', AdminModule::t('admin', 'applied_success'));
                    Yii::$app->session->setFlash('success', AdminModule::t('admin', 'updated_success'));
                    return $this->refresh();
                    //return $this->redirect(['customupdate-profile', 'action' => 'update']);
                } else {
                    Yii::$app->session->setFlash('success', AdminModule::t('admin', 'updated_success'));
//                    return $this->redirect(['index']);
//                    return $this->redirect(['agents/agents/customdashboard']);
                    //return $this->refresh();
                    return $this->redirect(['customupdate-profile', 'action' => 'update']);
                }
            }
            Yii::$app->getSession()->setFlash(
                'error',
                AdminModule::t('admin', 'something_wrong'),
                TRUE
            );
        }

        return $this->renderPartial(
            'customupdateProfile',
            [
                'model' => $model,
            ]);
    }

    /**
     * @return string
     */
    public function actionGetData()
    {
        $ACD = 0;
        $ASR = '00:00:00';
        $CPS = 0;
        if(isset($_GET['timeInterval'])){
            $fromTime = gmdate('Y-m-d H:i:s', strtotime("-".$_GET['timeInterval']." hour"));
            $currentTime = gmdate('Y-m-d H:i:s');
            list($CPS, $ACD, $ASR) = $this->getCallSummaryStatastics($fromTime, $currentTime, $_GET['timeInterval']);
        }
        $sys_active_calls = 0;
        //$total_active_calls = Yii::$app->db->createCommand("SELECT count(uuid) as total FROM fs_core.channels WHERE CONVERT_TZ(created, @@session.time_zone, '+0:00') >= DATE_SUB(CONVERT_TZ(NOW(), @@session.time_zone, '+0:00'), INTERVAL 30 MINUTE) AND ( (uuid != call_uuid) OR (call_uuid IS NULL))")->queryAll();
        $total_active_calls = Yii::$app->fscoredb->createCommand("SELECT count(uuid) as total FROM fs_core.channels WHERE ( (uuid != call_uuid) OR (call_uuid IS NULL))")->queryAll();
        if (!empty($total_active_calls)) {
            if (isset($total_active_calls[0]['total'])) {
                $sys_active_calls = $total_active_calls[0]['total'];
            }
        }

        $systemLoad = Yii::$app->db->createCommand("SELECT * FROM `dash_active_calls_count` ORDER BY `update_time` DESC LIMIT 1")->queryAll();
        if (!empty($systemLoad)) {
            Yii::$app->db->createCommand("UPDATE `dash_active_calls_count` SET `count` = '" . $sys_active_calls . "' WHERE `update_time`= '" . $systemLoad[0]['update_time'] . "'")->execute();
        }

        $systemLoad = Yii::$app->db->createCommand("SELECT dash_count as count, CONVERT_TZ(update_time, @@session.time_zone, '+0:00') as update_time FROM `dash_active_calls_count` ORDER BY update_time DESC LIMIT 30")->queryAll();

        $countCall = [];
        foreach (array_reverse($systemLoad) as $key => $value) {
            $date = CommonHelper::tsToDt($value['update_time']);
            $countCall[] = [
                'time' => date("i", strtotime($date)),
                'count' => (int)($value['count']),
                'hover' => date("Y-m-d", strtotime($date)) . ' ' . date("H:i", strtotime($date)) . ':00 - ' . (date("H:i", strtotime("$date + 1 minute"))) . ':00'
            ];
        }

        $concurrentHourCountCall = [];
        //$concurrentHourSystemLoad = Yii::$app->db->createCommand("SELECT dash_count as count, CONVERT_TZ(update_time, @@session.time_zone, '+0:00') as update_time FROM `dash_active_calls_count` where update_time > '".date('Y-m-d H:i:s', strtotime('-1 hour'))."' ORDER BY update_time DESC LIMIT 60")->queryAll();
        $concurrentHourSystemLoad = Yii::$app->db->createCommand("SELECT count(id) as count, CONVERT_TZ(created, @@session.time_zone, '+0:00') as update_time FROM fs_core.dash_active_calls_count WHERE callstate = 'ACTIVE' AND (presence_id LIKE '%".$_SERVER['HTTP_HOST']."%') AND created > '".date('Y-m-d H:i:s', strtotime('-1 hour'))."' GROUP BY created ORDER BY created DESC LIMIT 60")->queryAll();

        foreach (array_reverse($concurrentHourSystemLoad) as $hk => $hv) {
            $date = CommonHelper::tsToDt($hv['update_time']);
            $concurrentHourCountCall[(int)date("i", strtotime($date))] = [
                'time' => (int)date("i", strtotime($date)),
                'count' => (int)($hv['count']),
                'hover' => date("Y-m-d", strtotime($date)) . ' ' . date("H:i", strtotime($date)) . ':00 - ' . (date("H:i", strtotime("$date + 1 minute"))) . ':00'
            ];
        }

        $sortHourArray = $this->getHourData($concurrentHourCountCall);
        //ksort(array_column($sortHourArray, 'time'));
        array_multisort( array_column($sortHourArray, "time"), SORT_ASC, $sortHourArray);
        //echo '<pre>';print_r($sortHourArray);exit;

        $concurrentDayCountCall = [];
        //$concurrentDaySystemLoad = Yii::$app->db->createCommand("SELECT SUM(dash_count) as count, CONVERT_TZ(update_time, @@session.time_zone, '+0:00') as update_time FROM `dash_active_calls_count` where date = '".date('Y-m-d')."' group by hour(update_time) ORDER BY update_time DESC LIMIT 24")->queryAll();
        $concurrentDaySystemLoad = Yii::$app->db->createCommand("SELECT count(id) as count, CONVERT_TZ(created, @@session.time_zone, '+0:00') as update_time FROM fs_core.dash_active_calls_count WHERE callstate = 'ACTIVE' AND (presence_id LIKE '%".$_SERVER['HTTP_HOST']."%') AND DATE(created) = '".date('Y-m-d')."' GROUP BY hour(created) ORDER BY created DESC LIMIT 24")->queryAll();
        foreach (array_reverse($concurrentDaySystemLoad) as $dk => $dv) {
            $date = CommonHelper::tsToDt($dv['update_time']);
            $concurrentDayCountCall[(int)date("H", strtotime($date))] = [
                'time' => (int)date("H", strtotime($date)),
                'count' => (int)($dv['count']),
                'hover' => date("Y-m-d", strtotime($date)) . ' ' . date("H", strtotime($date)) . ':00:00 - ' . (date("H", strtotime("$date + 1 hour"))) . ':00:00'
            ];
        }

        $concurrentWeekCountCall = [];
        //$concurrentWeekSystemLoad = Yii::$app->db->createCommand("SELECT SUM(dash_count) as count, CONVERT_TZ(update_time, @@session.time_zone, '+0:00') as update_time FROM `dash_active_calls_count` where date <= '".date('Y-m-d')."' and date > '".date('Y-m-d', strtotime ( "-1 week" ))."' group by day(update_time) ORDER BY update_time DESC LIMIT 60")->queryAll();
        $concurrentWeekSystemLoad = Yii::$app->db->createCommand("SELECT count(id) as count, CONVERT_TZ(created, @@session.time_zone, '+0:00') as update_time FROM fs_core.dash_active_calls_count WHERE callstate = 'ACTIVE' AND (presence_id LIKE '%".$_SERVER['HTTP_HOST']."%') AND created > '".date('Y-m-d', strtotime('-1 week'))."' GROUP BY day(created) ORDER BY created DESC LIMIT 60")->queryAll();
        foreach (array_reverse($concurrentWeekSystemLoad) as $wk => $wv) {
            $date = CommonHelper::tsToDt($wv['update_time']);
            //if(date('Y', strtotime($date)) == date('Y')) {
                $concurrentWeekCountCall[date("Y-m-d", strtotime($date))] = [
                    'time' => date("Y-m-d", strtotime($date)),
                    'count' => (int)($wv['count']),
                    'hover' => date("Y-m-d", strtotime($date)) . '-' . date("Y-m-d", strtotime("$date +1 day")) . ''
                ];
            //}
        }
        $sortWeekArray = $this->getWeekData($concurrentWeekCountCall);
        ksort($sortWeekArray);

        $concurrentMonthCountCall = [];
        //$concurrentWeekSystemLoad = Yii::$app->db->createCommand("SELECT SUM(dash_count) as count, CONVERT_TZ(update_time, @@session.time_zone, '+0:00') as update_time FROM `dash_active_calls_count` where date <= '".date('Y-m-d')."' and date > '".date('Y-m-d', strtotime ( "-1 week" ))."' group by day(update_time) ORDER BY update_time DESC LIMIT 60")->queryAll();
        $concurrentMonthSystemLoad = Yii::$app->db->createCommand("SELECT count(id) as count, CONVERT_TZ(created, @@session.time_zone, '+0:00') as update_time FROM fs_core.dash_active_calls_count WHERE callstate = 'ACTIVE' AND (presence_id LIKE '%".$_SERVER['HTTP_HOST']."%') AND MONTH(created) = '".date('m')."' GROUP BY day(created) ORDER BY created DESC LIMIT 31")->queryAll();
        foreach (array_reverse($concurrentMonthSystemLoad) as $wk => $wv) {
            $date = CommonHelper::tsToDt($wv['update_time']);
            //if(date('Y', strtotime($date)) == date('Y')) {
            $concurrentMonthCountCall[date("Y-m-d", strtotime($date))] = [
                'time' => (int)date("d", strtotime($date)),
                'count' => (int)($wv['count']),
                'hover' => date("Y-m-d", strtotime($date))
            ];
            //}
        }

        $sortMonthArray = $this->getMonthData($concurrentMonthCountCall);
        //ksort($sortMonthArray);
        $keys = array_column($sortMonthArray, 'time');
        array_multisort($keys, SORT_ASC, $sortMonthArray);
        //print_r($sortMonthArray);exit;

        /*

        $start_date = date('Y-m-d H:i:s');
        $current_time = Yii::$app->db->createCommand("SELECT date_format(DATE_SUB(CONVERT_TZ(NOW(), @@session.time_zone, '+0:00'), INTERVAL 30 MINUTE), '%Y-%m-%d %H:%i') time")->queryAll();
        if(!empty($current_time))
        {
            $start_date = $current_time[0]['time'];
        }

        $calls_data = [];
        //$total_active_calls = Yii::$app->db->createCommand("SELECT date_format(CONVERT_TZ(call_created, @@session.time_zone, '+0:00'),'%Y-%m-%d %H:%i') as time, count(call_uuid) as total, MINUTE(CONVERT_TZ(call_created, @@session.time_zone, '+0:00')) as minute FROM fs_core.calls WHERE CONVERT_TZ(call_created, @@session.time_zone, '+0:00') >= DATE_SUB(CONVERT_TZ(NOW(), @@session.time_zone, '+0:00'), INTERVAL 30 MINUTE) GROUP BY MINUTE(CONVERT_TZ(call_created, @@session.time_zone, '+0:00'))")->queryAll();

        // TOTAL ACTIVE CALLS COUNT
        $sys_active_calls = 0;
        //$total_active_calls = Yii::$app->db->createCommand("SELECT count(uuid) as total FROM fs_core.channels WHERE CONVERT_TZ(created, @@session.time_zone, '+0:00') >= DATE_SUB(CONVERT_TZ(NOW(), @@session.time_zone, '+0:00'), INTERVAL 30 MINUTE) AND ( (uuid != call_uuid) OR (call_uuid IS NULL))")->queryAll();
        $total_active_calls = Yii::$app->db->createCommand("SELECT count(uuid) as total FROM fs_core.channels WHERE ( (uuid != call_uuid) OR (call_uuid IS NULL))")->queryAll();
        if(!empty($total_active_calls))
        {
            if(isset($total_active_calls[0]['total']))
            {
                $sys_active_calls = $total_active_calls[0]['total'];
            }
        }

        // TOTAL ACTIVE CALLS COUNT BEFORE 30 MINUTE
        $count_calls_before = 0;
        $total_active_calls = Yii::$app->db->createCommand("SELECT count(uuid) as total FROM fs_core.channels WHERE CONVERT_TZ(created, @@session.time_zone, '+0:00') < DATE_SUB(CONVERT_TZ(NOW(), @@session.time_zone, '+0:00'), INTERVAL 30 MINUTE) AND ( (uuid != call_uuid) OR (call_uuid IS NULL))")->queryAll();
        if(!empty($total_active_calls))
        {
            if(isset($total_active_calls[0]['total']))
            {
                $count_calls_before = $total_active_calls[0]['total'];
            }
        }

        $total_active_calls = Yii::$app->db->createCommand("SELECT date_format(CONVERT_TZ(created, @@session.time_zone, '+0:00'),'%Y-%m-%d %H:%i') as time, count(uuid) as total, MINUTE(CONVERT_TZ(created, @@session.time_zone, '+0:00')) as minute FROM fs_core.channels WHERE CONVERT_TZ(created, @@session.time_zone, '+0:00') >= DATE_SUB(CONVERT_TZ(NOW(), @@session.time_zone, '+0:00'), INTERVAL 30 MINUTE) AND ( (uuid != call_uuid) OR (call_uuid IS NULL)) GROUP BY MINUTE(CONVERT_TZ(created, @@session.time_zone, '+0:00'))")->queryAll();
        foreach($total_active_calls as $single)
        {
            $calls_data[$single['time'].":00"] = $single;
        }

        $countCall = [];
        for($i = 1; $i <= 30; $i++)
        {
            $temp_date = date("Y-m-d H:i:s",strtotime("$start_date +".($i)." minutes"));
            $temp_date_to_date = date("Y-m-d H:i:s",strtotime("$start_date + " . ($i + 1)." minutes"));

            if(isset($calls_data[$temp_date]))
            {
                $date = CommonHelper::tsToDt($temp_date);
                $temp_date_to_date = CommonHelper::tsToDt($temp_date_to_date);

                $count_calls_before =  $count_calls_before + (int) ($calls_data[$temp_date]['total']);

                    $countCall[]=[
                        'time'=> date("i", strtotime($date)),
                        'count'=> $count_calls_before,
                        //'hover' =>  'Count: '.($calls_data[$temp_date]['total']).' '.date("Y-m-d", strtotime($date)).' '.date("H:i", strtotime($date)).':00 - '.date("H:i", strtotime($temp_date_to_date)).':00'
                    'hover' =>  'Count: '.($count_calls_before).' '.date("Y-m-d", strtotime($date)).' '.date("H:i", strtotime($date)).':00 - '.date("H:i", strtotime($temp_date_to_date)).':00'
                    ];
            }
            else
            {
                $date = CommonHelper::tsToDt($temp_date);
                $temp_date_to_date = CommonHelper::tsToDt($temp_date_to_date);

                    $countCall[]=[
                        'time'=> date("i", strtotime($date)),
                        'count'=> (int) $count_calls_before,
                        'hover' =>  'Count: '.($count_calls_before).' '.date("Y-m-d", strtotime($date)).' '.date("H:i", strtotime($date)).':00 - '.date("H:i", strtotime($temp_date_to_date)).':00'
                    ];
            }

        } */


        //$systemLoad = SystemUsage::find()->orderBy(['sys_id' => SORT_DESC])->asArray()->one();
        //$systemLoad = Yii::$app->masterdb->createCommand("SELECT * FROM `uc_server_usases` WHERE server_ip = '".$_GET['serverIp']."'")->queryOne();
        $systemLoad = Yii::$app->masterdb->createCommand("SELECT * FROM `uc_server_usases` ORDER BY updatedAt DESC")->queryOne();
        if(!empty($systemLoad)) {
            $allData['sys_cpu_usage'] = $systemLoad['cpu_utilisation'];
            $allData['sys_disk_used'] = $systemLoad['disc_utilisation'];
            $allData['sys_mem_used'] = $systemLoad['ram_utilisation'];
            $allData['sys_nginx_status'] = $systemLoad['nginx'];
            $allData['sys_mysql_status'] = $systemLoad['maria_db'];
            $allData['sys_mongo_status'] = $systemLoad['mongo_db'];
            $allData['sys_freeswitch_status'] = $systemLoad['telephony'];
            $allData['sys_last_reboot'] = $systemLoad['last_reboot_time'];
        }else{
            $allData['sys_cpu_usage'] = 0;
            $allData['sys_disk_used'] = 0;
            $allData['sys_mem_used'] = 0;
            $allData['sys_nginx_status'] = 0;
            $allData['sys_mysql_status'] = 0;
            $allData['sys_mongo_status'] = 0;
            $allData['sys_freeswitch_status'] = 0;
            $allData['sys_last_reboot'] = date('Y-m-d H:i:s');
        }
        $allData['sys_active_calls'] = $sys_active_calls;
        $allData['date'] = date('Y-m-d H:i:s'); //CommonHelper::tsToDt($systemLoad['sys_server_time']);
        $allData['call_count'] = $countCall;
        $allData['ASR'] = $ASR;
        $allData['ACD'] = $ACD;
        $allData['CPS'] = $CPS;
        $allData['concurrent_hour_call_count'] = $sortHourArray;
        $allData['concurrent_day_call_count'] = $this->getDayData($concurrentDayCountCall);
        $allData['concurrent_week_call_count'] = $sortWeekArray;
        $allData['concurrent_month_call_count'] = $sortMonthArray;

        // $allData['hard_disk']=$loadAverage;

        return json_encode($allData);
    }

    /**
     * Finds the Admin model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     *
     * @return AdminMaster
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        /** @var AdminMaster $model */
        if (($model = AdminMaster::findOne($id)) !== NULL) {
            return $model;
        } else {
            throw new NotFoundHttpException(
                AdminModule::t('admin', 'page_not_exit')
            );
        }
    }
    public function getCallSummaryStatastics($startTime, $endTime, $interval = 1){

        $from = strtotime($startTime);
        $to = strtotime($endTime);

        $cdrData = Cdr::find()->where(['>=', 'start_epoch', trim($from)])
            ->andWhere(['<=', 'start_epoch', trim($to)])
            ->asArray()->all();

        $totalCalls = count($cdrData);
        $CPS = number_format($totalCalls / ($interval * 3600),2, '.', '');
        $answeredCalls = array_filter($cdrData, function ($entry) {
            return isset($entry['duration']) && isset($entry['answer_epoch']) && $entry['answer_epoch'] != 0;
        });
        $totalAnsweredCalls = count($answeredCalls);
        $totalAnsweredCallDuration = array_sum(array_column($answeredCalls, 'duration'));

        $ACD = '00:00:00';
        $ASR = 0;

        if($totalAnsweredCalls){
            $decimalHours  = $totalAnsweredCallDuration/$totalAnsweredCalls;
            //$ACD = $this->convertDecimalToTime($decimalHours);
            $ACD = date('H:i:s', $decimalHours);
            $ASR = number_format(($totalAnsweredCalls / $totalCalls) * 100,2, '.', '');
        }
        return [$CPS,$ACD,$ASR];
    }

    public function getDayData($concurrentDayCountCall){
        $array = array_diff(ConstantHelper::getHour(), array_column($concurrentDayCountCall, 'time'));
        if(!empty($array)){
            foreach($array as $_array){
                $date = date('Y-m-d')." ".$_array.":00:00";
                $concurrentDayCountCall[$_array] = [
                    'time' => (int)$_array,
                    'count' => 0,
                    'hover' => date("Y-m-d", strtotime($date)) . ' ' . date("H", strtotime($date)) . ':00:00 - ' . (date("H", strtotime("$date + 1 hour"))) . ':00:00'
                ];
            }
        }
        return $concurrentDayCountCall;
    }

    public function getWeekData($concurrentWeekCountCall){
        $lastWeekDate = date('Y-m-d', strtotime('-1 week'));
        $date = [];
        for($i=1; $i<=7; $i++){
            $date[date('Y-m-d', strtotime($lastWeekDate.' +'.$i.' day'))] = date('Y-m-d', strtotime($lastWeekDate.' +'.$i.' day'));

        }

        $array = array_diff($date, array_column($concurrentWeekCountCall, 'time'));
        if(!empty($array)){
            foreach($array as $_array){
                $date = date('Y-m-d')." ".$_array.":00:00";
                //if(date('Y', strtotime($_array)) == date('Y')){
                    $concurrentWeekCountCall[$_array] = [
                        'time' => $_array,
                        'count' => 0,
                        'hover' => date("Y-m-d", strtotime($_array)) . ' - ' . date("Y-m-d", strtotime("$_array + 1 day"))
                    ];
                //}
            }
        }

        return $concurrentWeekCountCall;
    }

    public function getHourData($concurrentHourCountCall){
        $minutes = [];
        for($i=0; $i<60; $i++){
            $minutes[$i] = $i;
        }

        $array = array_diff($minutes, array_column($concurrentHourCountCall, 'time'));
        if(!empty($array)){
            foreach($array as $_array){
                $date = date('Y-m-d H').":".$_array.":00";
                $concurrentHourCountCall[(int)$_array] = [
                    'time' => (int)$_array,
                    'count' => 0,
                    'hover' => date("Y-m-d", strtotime($date)) . ' ' . date("H:i", strtotime($date)) . ':00 - ' . (date("H:i", strtotime("$date + 1 minute"))) . ':00'
                ];
            }
        }
        return $concurrentHourCountCall;
    }


    public function getMonthData($concurrentMonthCountCall){
        $lastDate = date('t');
        $data = [];
        for($i=1; $i<=$lastDate; $i++){
            $data[$i] = $i;

        }

        $array = array_diff($data, array_column($concurrentMonthCountCall, 'time'));

        if(!empty($array)){
            foreach($array as $_array){
                $date = date('Y-m-'.$_array);
                //if(date('Y', strtotime($_array)) == date('Y')){
                $concurrentMonthCountCall[$date] = [
                    'time' => $_array,
                    'count' => 0,
                    'hover' => date("Y-m-".$_array)
                ];
                //}
            }
        }

        return $concurrentMonthCountCall;
    }
}
