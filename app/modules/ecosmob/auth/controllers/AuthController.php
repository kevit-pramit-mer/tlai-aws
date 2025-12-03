<?php

namespace app\modules\ecosmob\auth\controllers;

use app\components\CommonHelper;
use app\models\SipRegistrations;
use app\modules\ecosmob\agent\models\Agent;
use app\modules\ecosmob\agents\models\CampaignMappingAgents;
use app\modules\ecosmob\auth\AuthModule;
use app\modules\ecosmob\auth\models\AdminMaster;
use app\modules\ecosmob\auth\models\ForgotPassword;
use app\modules\ecosmob\auth\models\LoginForm;
use app\modules\ecosmob\campaign\models\Campaign;
use app\modules\ecosmob\campaign\models\CampaignMappingUser;
use app\modules\ecosmob\extension\models\Extension;
use app\modules\ecosmob\queue\models\QueueMaster;
use app\modules\ecosmob\supervisor\models\BreakReasonMapping;
use app\modules\ecosmob\supervisor\models\UsersActivityLog;
use Exception;
use Throwable;
use Yii;
use yii\base\InvalidArgumentException;
use yii\base\Response;
use yii\db\Expression;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\Request;
use app\modules\ecosmob\queue\models\Tiers;
use app\modules\ecosmob\globalconfig\models\GlobalConfig;

/**
 * Class AuthController
 *
 * Auth Controller responsible to perform method like login, logout, forgot password etc.
 *
 * Only Guest user can perform this action
 *
 * @package app\modules\ecosmob\auth\controllers
 */
class AuthController extends Controller
{
    /**
     * @var bool
     */
    public $enableCsrfValidation = false;

    /**
     * Behaviors manage Action access control and Action request method flow
     *
     * @return array
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['logout', 'get_qrcode', 'session-check', 'isuserlogin'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['login-google', 'login-azure', 'google-acs', 'azure-acs', 'saml-success-login', 'saml-login', 'metadata', 'sls', 'acs', 'reset', 'login',  'auto-login','error', 'tenant-login', 'subtenant-login', 'cron'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['forgot', 'update-password'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                    'reset-login' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
            /*  'saml-login' => [
                  'class' => 'asasmoyo\yii2saml\actions\LoginAction',
                  //'returnTo' => 'https://ucdemo2.ecosmob.net/index.php?r=auth/auth/login', // Adjust based on your application's return URL after login
              ],*/
            'metadata' => [
                'class' => 'asasmoyo\yii2saml\actions\MetadataAction'
            ],
            'sls' => [
                'class' => 'asasmoyo\yii2saml\actions\SlsAction',
                'successUrl' => Yii::$app->urlManager->createUrl(['auth/auth/login']),
                'logoutIdP' => false, // Set to true if you want to logout from the IdP as well
            ],
            /* 'acs' => [
                 'class' => 'asasmoyo\yii2saml\actions\AcsAction',
                 'successUrl' => Yii::$app->urlManager->createUrl(['/auth/auth/login']),
                 'successCallback' => function($attributes) {
                     try {
                         $userName = $attributes['attributes']['username'][0];
                         return Yii::$app->response->redirect(['auth/auth/saml-success-login', 'username' => $userName]);
                     } catch (\Exception $e) {
                         Yii::error('Error in successCallback: ' . $e->getMessage());
                         throw new \yii\web\HttpException(500, 'Internal Server Error');
                     }
                 },
             ],*/
        ];
    }

    public function actionIsuserlogin()
    {
        return true;
    }

    public function actionSamlLogin()
    {
        $isSSOConfig = false;
        $ssoConfig = GlobalConfig::getValueByKey('SSO_identity_id');
        if (!empty($ssoConfig)) {
            $ssoConfig = GlobalConfig::getValueByKey('SSO_login_url');
            if (!empty($ssoConfig)) {
                $ssoConfig = GlobalConfig::getValueByKey('SSO_certificate');
                if (!empty($ssoConfig)) {
                    $isSSOConfig = true;
                }
            }
        }

        if($isSSOConfig == true) {
            try {
                // Access the SAML component for Google from Yii::$app
                $saml = Yii::$app->saml;

                // Redirect the user to the Google IdP for authentication
                $saml->login();
            }catch(Exception $e){
                Yii::$app->session->setFlash('error', AuthModule::t('auth', 'Invalid SSO Configuration. Please contact your admin for further assistance.'));
                return $this->redirect(['login']);
            }
        }else{
            Yii::$app->session->setFlash('error', AuthModule::t('auth', 'SSO details are not configured. Please contact your admin for further assistance.'));
            return $this->redirect(['login']);
        }
    }

    public function actionAcs()
    {
        $saml = Yii::$app->saml;
        $saml->processResponse();

        if ($saml->isAuthenticated()) {

            $attributes = $saml->getAttributes();
            $userName = $attributes['username'][0];
            return Yii::$app->response->redirect(['auth/auth/saml-success-login', 'username' => $userName]);
        } else {
            Yii::$app->session->setFlash('error', AuthModule::t('auth', 'something_wrong'));
            return $this->redirect(['auth/auth/login']);
        }
    }

    public function actionLoginGoogle()
    {
        // Access the SAML component for Google from Yii::$app
        $saml = Yii::$app->saml_google;

        // Redirect the user to the Google IdP for authentication
        $saml->login();
    }

    public function actionLoginAzure()
    {
        // Access the SAML component for Azure from Yii::$app
        $saml = Yii::$app->saml_azure;

        // Redirect the user to the Azure IdP for authentication
        $saml->login();
    }

    public function actionGoogleAcs()
    {
        $saml = Yii::$app->saml_google;
        $saml->processResponse();

        if ($saml->isAuthenticated()) {

            $attributes = $saml->getAttributes();
            $userName = $attributes['username'][0];
            return Yii::$app->response->redirect(['auth/auth/saml-success-login', 'username' => $userName]);
        } else {
            Yii::$app->session->setFlash('error', AuthModule::t('auth', 'something_wrong'));
            return $this->redirect(['auth/auth/login']);
        }
    }

    public function actionAzureAcs()
    {
        $saml = Yii::$app->saml_azure;
        $saml->processResponse();

        if ($saml->isAuthenticated()) {
            $attributes = $saml->getAttributes();
            $userName = $attributes['username'][0];
            return Yii::$app->response->redirect(['auth/auth/saml-success-login', 'username' => $userName]);
        } else {
            Yii::$app->session->setFlash('error', AuthModule::t('auth', 'something_wrong'));
            return $this->redirect(['/site/login']);
        }
    }

    /**
     * @return string
     * @throws InvalidArgumentException
     */
    public function actionLogin()
    {

        if (!Yii::$app->user->isGuest) {
            $this->goHome();
        }

        $model = new LoginForm();

        $model->loginAsExtension = isset($_POST['loginAsExtension']) ? $_POST['loginAsExtension'] : '0';

        $model->loginType = isset($_POST['logintype']) ? $_POST['logintype'] : '0';
        $model->extension = '0';

        $model->campaign_type = isset($_POST['campaign_type']) ? $_POST['campaign_type'] : '0';

        if (isset(Yii::$app->request->post()['LoginForm']['campaign_type']) && isset(Yii::$app->user->identity->adm_id)) {
            $agentId = Yii::$app->user->identity->adm_id . '_' . $GLOBALS['tenantID'];
            $extension = Extension::findOne(Yii::$app->user->identity->adm_mapped_extension);
            SipRegistrations::deleteAll(['AND', ['sip_user' => $extension->em_extension_number], ['sip_host' => $_SERVER['HTTP_HOST']], ['LIKE', 'user_agent', 'SIP.js/0.21.2%', false]]);
            $agent = Agent::find()->andWhere(['name' => $agentId])
                ->andWhere(['status' => 'Logged Out', 'state' => 'Waiting'])->asArray()->all();
            if (empty($agent)) {
                Yii::$app->session->setFlash('error', AuthModule::t('auth', 'agentErrorMsg'));
                return $this->render('login', [
                    'model' => $model,
                ]);
            }else{

                Yii::$app->db->createCommand()
                    ->update('agents', (['status' => 'Available', 'state' => 'Waiting']), ['name' => $agentId])
                    ->execute();

                $cmpListData = Yii::$app->request->post()['LoginForm']['campaign_type'];
                if ($cmpListData > 0) {
                    Yii::$app->db->createCommand()
                        ->delete('active_calls', ['agent' => Yii::$app->user->id])
                        ->execute();

                    $agentName = Yii::$app->user->identity->adm_id . '_' . $GLOBALS['tenantID'];
                    Tiers::deleteAll(['agent' => $agentId]);
                    foreach ($cmpListData as $cmpList) {
                        $queue = Campaign::find()->select('cmp_queue_id')->where(['cmp_id' => $cmpList])->one();
                        $queueName = QueueMaster::find()->select(['qm_name'])->where(['qm_id' => $queue['cmp_queue_id']])->one();

                        if(!empty($queueName)) {
                            Yii::$app->db->createCommand()->insert('tiers',
                                [
                                    'queue' => $queueName['qm_name'],
                                    'agent' => $agentName,
                                ])
                                ->execute();
                        }

                        $cmpRecord = Campaign::find()->where(['cmp_id' => $cmpList])->one();

                        Yii::$app->db->createCommand()->insert(
                            'ct_login_campaign',
                            [
                                'adm_id' => Yii::$app->user->identity->adm_id,
                                'cmp_id' => $cmpRecord['cmp_id'],
                                'cmp_type' => $cmpRecord['cmp_type'],
                                'cmp_name' => $cmpRecord['cmp_name'],
                                'cmp_dialer_type' => $cmpRecord['cmp_dialer_type'],

                            ]
                        )
                            ->execute();
                    }


                    $cmpListData = implode(",", $cmpListData);
                    Yii::$app->session->set('selectedCampaign', $cmpListData);

                } else {
                    return false;
                }
            }
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->login()) {

            if ($model->loginAsExtension) {
                Yii::$app->session->set('loginAsExtension', $model->loginAsExtension);
                $extension = Extension::findOne(['em_extension_number' => $model->username]);

                    $userExt = AdminMaster::find()->where(['adm_mapped_extension' => $extension->em_id, 'adm_status' => '1'])->count();
                    if ($userExt > 0) {
                        Yii::$app->session->setFlash('error', AuthModule::t('auth', 'extension_already_assigned'));
                        return $this->render('login', [
                            'model' => $model,
                        ]);
                    }else {
                        $extension->em_token = Yii::$app->session->getId();
                        $extension->save(false);
                    }
            } else {
                $admin = AdminMaster::findOne(Yii::$app->user->identity->adm_id);
                $extensionObj = Extension::findOne(['em_id' => $admin->adm_mapped_extension]);
                if ($admin->adm_is_admin == 'agent') {
                    $agentId = Yii::$app->user->identity->adm_id . '_' . $GLOBALS['tenantID'];
                    $agent = Agent::find()->andWhere(['name' => $agentId])
                        ->andWhere(['status' => 'Logged Out', 'state' => 'Waiting'])->asArray()->all();
                    if (empty($agent)) {
                        Yii::$app->session->remove('loginAsExtension');
                        Yii::$app->session->remove('extentationNumber');
                        Yii::$app->session->remove('selectedCampaign');
                        Yii::$app->user->logout();
                        Yii::$app->session->setFlash('error', AuthModule::t('auth', 'agentErrorMsg'));
                        return $this->render('login', [
                            'model' => $model,
                        ]);
                    }
                }

                Yii::$app->session->set('time_zone', $admin->adm_timezone_id);

                if (!is_object($extensionObj) && ($admin->adm_is_admin == 'supervisor' || $admin->adm_is_admin == 'agent')) {
                    Yii::$app->session->setFlash('error', AuthModule::t('auth', 'extension_not_mapped_please_contact_your_admin'));
                    return $this->render('login', [
                        'model' => $model,
                    ]);
                }
                $model->extension = $extensionObj ? $extensionObj->em_extension_number : '0';
                $admin->adm_token = Yii::$app->session->getId();
                $admin->save(false);
                if($admin->adm_is_admin == 'super_admin') {
                    return $this->redirect(['/admin/admin/index']);
                }
            }
            if ($model->loginType == 'supervisor') {

                $supervisorCamp = CampaignMappingUser::find()
                    ->where(['supervisor_id' => Yii::$app->user->id])
                    ->all();

                if (!empty($supervisorCamp)) {
                    foreach ($supervisorCamp as $supervisorCamps) {
                        $prevLog = UsersActivityLog::find()
                            ->where(['user_id' => Yii::$app->user->id])
                            ->andWhere(['logout_time' => '0000-00-00 00:00:00'])
                            ->andWhere(['campaign_name' => $supervisorCamps['campaign_id']])
                            ->asArray()->all();
                        if (!empty($prevLog)) {
                            foreach ($prevLog as $prevLog) {
                                $prevOne = UsersActivityLog::findOne($prevLog['id']);
                                if (date('Y-m-d') != date('Y-m-d', strtotime($prevOne->login_time))) {
                                    $logOutTime = $this->getLogoutTime($prevOne->user_id, $prevOne->login_time);
                                    $prevOne->logout_time = $logOutTime;
                                } else {
                                    $prevOne->logout_time = date('Y-m-d H:i:s');
                                }
                                $prevOne->save(false);
                            }
                        }
                        $activityLog = new UsersActivityLog();
                        $activityLog->user_id = Yii::$app->user->id;
                        $activityLog->login_time = date('Y-m-d H:i:s');
                        $activityLog->campaign_name = $supervisorCamps['campaign_id'];
                        $activityLog->save(false);
                    }
                } else {
                    $prevLog = UsersActivityLog::find()
                        ->where(['user_id' => Yii::$app->user->id])
                        ->andWhere(['logout_time' => '0000-00-00 00:00:00'])
                        ->asArray()->all();
                    if (!empty($prevLog)) {
                        foreach ($prevLog as $prevLog) {
                            $prevOne = UsersActivityLog::findOne($prevLog['id']);
                            if (date('Y-m-d') != date('Y-m-d', strtotime($prevOne->login_time))) {
                                $logOutTime = $this->getLogoutTime($prevOne->user_id, $prevOne->login_time);
                                $prevOne->logout_time = $logOutTime;
                            }else{
                                $prevOne->logout_time = date('Y-m-d H:i:s');
                            }
                            $prevOne->save(false);
                        }
                    }
                }
                $data = BreakReasonMapping::find()->where(['user_id' => Yii::$app->user->identity->adm_id])
                    ->orderBy([
                        'id' => SORT_DESC,
                    ])->limit(1)->one();
                if ($data) {
                    if ($data->out_time == '0000-00-00 00:00:00') {
                        $breakId = $id = $data->id;

                        $breakUpdate = BreakReasonMapping::findOne($breakId);
                        $breakUpdate->break_status = 'Out';
                        $breakUpdate->out_time = date('Y-m-d H:i:s');
                        $breakUpdate->save(false);
                    }
                }
            }

            if (isset(Yii::$app->user->identity->adm_id)) {

                if (isset($extensionObj) && $extensionObj->em_extension_number) {
                    $extensionNumber = $extensionObj->em_extension_number;

                    $agentId = Yii::$app->user->identity->adm_id.'_'.$GLOBALS['tenantID'];
                    Yii::$app->db->createCommand()
                        ->update('agents', (
                        ['contact' =>
                            '{ignore_early_media=ring_ready,loopback_bowout_on_execute=true,leg_timeout=60,sip_h_X-agent_id=' . Yii::$app->user->identity->adm_id . '}user/' . $extensionNumber . '@$${domain_name}',
                            'login_extension' => $extensionNumber]), ['name' => $agentId])
                        ->execute();
                    Yii::$app->session->set('extentationNumber', $extensionNumber);
                }
            }

            Yii::$app->session->set('loginAsExtension', $model->loginAsExtension);

            if (!$model->loginAsExtension) {
                if (!Yii::$app->user->identity->adm_status) {
                    Yii::$app->session->setFlash('success', AuthModule::t('auth', 'not_allowed_to_login_please_contact_your_admin'));
                    Yii::$app->user->logout();
                    $this->goHome();
                }

                $adm_is_admin_status = AdminMaster::find()->select(['adm_is_admin', 'adm_timezone_id'])->where(['adm_username' => $model->username])->one();

                Yii::$app->session->set('time_zone', $adm_is_admin_status->adm_timezone_id);

                if ($adm_is_admin_status->adm_is_admin == 'agent') {
                    $model->scenario = 'agent';
                    return $this->render('agent-login', [
                        'model' => $model,
                    ]);
                }
            }

            return $this->redirect('');
        }

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * @return string|\yii\web\Response
     * @throws InvalidArgumentException
     */
    public function actionTenantLogin()
    {
        if (!Yii::$app->user->isGuest) {
            $this->goHome();
        }
        /** @var LoginForm $model */
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            Yii::$app->session->remove('login_user');
            Yii::$app->session->remove('prev_user_name');
            return $this->redirect('/tenantmaster/tenant');
        }

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * @return string|\yii\web\Response
     */
    /*public function actionUserLogin()
    {
        if (!Yii::$app->user->isGuest) {
            $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            Yii::$app->session->set('login_user', 'extension');
            return $this->redirect('/extensionmaster/extension');
        }

        return $this->render('login', [
            'model' => $model,
        ]);
    }*/

    /**
     * @return string|\yii\web\Response
     */
    public function actionSubtenantLogin()
    {
        if (!Yii::$app->user->isGuest) {
            $this->goHome();
        }
        $user_name = Yii::$app->user->identity->user_name;
        /** @var LoginForm $model */
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            Yii::$app->session->set('login_user', 'subtenant');
            Yii::$app->session->set('prev_user_name', $user_name);
            return $this->redirect('/tenantmaster/tenant');
        }

        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action :
     *
     * @return Response
     */
    public function actionLogout()
    {
        if (isset(Yii::$app->user->identity->adm_id)) {
            $agentId = Yii::$app->user->identity->adm_id . '_' . $GLOBALS['tenantID'];
            Yii::$app->db->createCommand()
                ->update('agents', (['status' => 'Logged Out', 'state' => 'Waiting']), ['name' => $agentId])
                ->execute();
        }
        if (empty(yii::$app->session->get('loginAsExtension'))) {
            $user = AdminMaster::findOne(Yii::$app->user->identity->adm_id);
            if ($user->adm_is_admin == 'agent' || $user->adm_is_admin == 'supervisor') {
                if ($user->adm_is_admin == 'supervisor') {
                    $supervisorCamp = CampaignMappingUser::find()
                        ->where(['supervisor_id' => Yii::$app->user->identity->adm_id])
                        ->all();

                    if (!empty($supervisorCamp)) {
                        foreach ($supervisorCamp as $supervisorCamps) {
                            $prevLog = UsersActivityLog::find()
                                ->where(['user_id' => Yii::$app->user->identity->adm_id])
                                ->andWhere(['logout_time' => '0000-00-00 00:00:00'])
                                ->andWhere(['campaign_name' => $supervisorCamps['campaign_id']])
                                ->one();
                            if (!empty($prevLog)) {
                                $prevLog->logout_time = date('Y-m-d H:i:s');
                                $prevLog->save(false);
                            }
                            $data = BreakReasonMapping::find()
                                ->andWhere(['user_id' => Yii::$app->user->identity->adm_id])
                                ->andWhere(['camp_id' => $supervisorCamps['campaign_id']])
                                ->andWhere(['out_time' => '0000-00-00 00:00:00'])
                                ->all();
                            if (!empty($data)) {
                                foreach ($data as $_data) {
                                    $_data->break_status = 'Out';
                                    $_data->out_time = date('Y-m-d H:i:s');
                                    $_data->save();
                                }
                            }
                        }
                    }
                } else {
                    $agentName = Yii::$app->user->identity->adm_id . '_' . $GLOBALS['tenantID'];
                    Tiers::deleteAll(['agent' => $agentName]);
                    $agentCamp = CampaignMappingAgents::find()
                        ->where(['agent_id' => Yii::$app->user->id])
                        ->groupBy('campaign_id')
                        ->all();
                    foreach ($agentCamp as $_agentCamp) {

                        $activityLog = UsersActivityLog::find()
                            ->andWhere(['user_id' => Yii::$app->user->identity->adm_id])
                            ->andWhere(['logout_time' => '0000-00-00 00:00:00'])
                            ->andWhere(['campaign_name' => $_agentCamp['campaign_id']])
                            ->all();
                        if (!empty($activityLog)) {
                            foreach ($activityLog as $_activityLog) {
                                $_activityLog->logout_time = date('Y-m-d H:i:s');
                                $_activityLog->save(false);
                            }
                        }

                        $data = BreakReasonMapping::find()
                            ->andWhere(['user_id' => Yii::$app->user->identity->adm_id])
                            ->andWhere(['out_time' => '0000-00-00 00:00:00'])
                            ->one();
                        if (!empty($data)) {
                            $data->break_status = 'Out';
                            $data->out_time = date('Y-m-d H:i:s');
                            $data->save();
                        }
                    }
                }
            }
        }
        Yii::$app->user->logout();
        Yii::$app->session->remove('loginAsExtension');
        Yii::$app->session->remove('extentationNumber');
        Yii::$app->session->remove('selectedCampaign');
        //$this->goHome();
        $this->redirect(['login']);
    }

    /**
     * @return string|\yii\web\Response
     * @throws Exception
     * @throws Throwable
     */
    public function actionForgot()
    {
        /** @var AdminMaster $model */
        $model = new AdminMaster();
        $error = '';

        if (Yii::$app->request->post()) {
            $credentials = Yii::$app->commonHelper->initialGetTenantConfig($_SERVER['HTTP_HOST']);
            if(empty($credentials)){
                Yii::$app->session->setFlash("error", AuthModule::t('auth', 'tenant_inactive'), TRUE);

                return $this->redirect(['/auth/auth/forgot']);
            }

            /** @var  $requestData */
            $requestData = Yii::$app->request->post();
            if ($_POST['asExtension'] == 1) {
                /** @var $extensionNumber */
                $extensionNumber = $requestData['AdminMaster']['adm_username'];
                /** @var Extension $extension */
                $extension = Extension::findOne(['em_extension_number' => $extensionNumber, 'em_status' => '1']);

                if (is_null($extension)) {
                    Yii::$app->session->setFlash("error", AuthModule::t('auth', 'extension_not_exist'), TRUE);

                    return $this->redirect(['/auth/auth/forgot']);
                }

                if ($extension instanceof Extension) {
                    /** @var  $token */
                    $token = md5(uniqid());
                    /** @var  $resetPasswordLink */
                    $resetPasswordLink = Url::toRoute(['/auth/auth/reset', 'token' => $token], true);

                    /** @var ForgotPassword $forgotPasswordModel */
                    $forgotPasswordModel = new ForgotPassword();
                    $forgotPasswordModel->fp_user_id = $extensionNumber;
                    //$forgotPasswordModel->fp_user_type = ($userMaster->getAttribute('adm_is_admin') == 'Y' ? 'admin' : 'user');
                    $forgotPasswordModel->fp_user_type = 'Extension';
                    $forgotPasswordModel->fp_token = $token;
                    $forgotPasswordModel->fp_reset_url = $resetPasswordLink;
                    $forgotPasswordModel->fp_status = 1;
                    $forgotPasswordModel->fp_update_at = new Expression('NOW()');

                    if ($forgotPasswordModel->insert(false)) {

                        $name = $extension->em_extension_name;

                        $result = CommonHelper::resetPasswordLink($name, $extension->em_email, $extensionNumber);
                        if ($result) {
                            Yii::$app->session->setFlash("success", AuthModule::t('auth', 'reset_password_link_sent'), TRUE);
                            return $this->redirect(['login']);
                        } else {
                            Yii::$app->session->setFlash("error", AuthModule::t('auth', 'something_wrong'), TRUE);
                            return $this->redirect(['login']);
                        }
                    } else {
                        Yii::$app->session->setFlash("error", AuthModule::t('auth', 'something_wrong'), TRUE);
                        return $this->redirect(['login']);
                    }
                } else {
                    Yii::$app->session->setFlash("error", AuthModule::t('auth', 'extension_not_exist'), TRUE);
                    return $this->redirect(['login']);
                }
            } else {
                /** @var $userEmailId */
                $userEmailId = $requestData['AdminMaster']['adm_username'];
                /** @var AdminMaster $adminMaster */
                $adminMaster = AdminMaster::findByUsername($userEmailId);

                if (is_null($adminMaster)) {
                    Yii::$app->session->setFlash("error", AuthModule::t('auth', 'user_not_exist'), TRUE);

                    return $this->redirect(['/auth/auth/forgot']);
                }
                if ($adminMaster instanceof AdminMaster) {
                    /** @var  $token */
                    $token = md5(uniqid());
                    /** @var  $resetPasswordLink */
                    $resetPasswordLink = Url::toRoute(['/auth/auth/reset', 'token' => $token], true);

                    /** @var ForgotPassword $forgotPasswordModel */
                    $forgotPasswordModel = new ForgotPassword();
                    $forgotPasswordModel->fp_user_id = $userEmailId;
                    //$forgotPasswordModel->fp_user_type = ($userMaster->getAttribute('adm_is_admin') == 'Y' ? 'admin' : 'user');
                    $forgotPasswordModel->fp_user_type = 'Admin';
                    $forgotPasswordModel->fp_token = $token;
                    $forgotPasswordModel->fp_reset_url = $resetPasswordLink;
                    $forgotPasswordModel->fp_status = 1;
                    $forgotPasswordModel->fp_update_at = new Expression('NOW()');

                    if ($forgotPasswordModel->insert(false)) {
                        /** @var AdminMaster */
                        $admin_array = AdminMaster::findOne(['adm_username' => $userEmailId]);
                        if ($admin_array instanceof AdminMaster) {
                            $firstName = $admin_array->adm_firstname;
                        }

                        $result = CommonHelper::resetPasswordLink($firstName, $admin_array->adm_email, $userEmailId);
                        if ($result) {
                            Yii::$app->session->setFlash("success", AuthModule::t('auth', 'reset_password_link_sent'), TRUE);
                            return $this->redirect(['login']);
                        } else {
                            Yii::$app->session->setFlash("error", AuthModule::t('auth', 'something_wrong'), TRUE);
                            return $this->redirect(['login']);
                        }
                    } else {
                        Yii::$app->session->setFlash("error", AuthModule::t('auth', 'something_wrong'), TRUE);
                        return $this->redirect(['login']);
                    }
                } else {
                    Yii::$app->session->setFlash("error", AuthModule::t('auth', 'user_not_exist'), TRUE);
                    return $this->redirect(['login']);
                }
            }
        }

        return $this->render('forgot', [
            'model' => $model,
            'error' => $error,
        ]);
    }

    /**
     * @return string|\yii\web\Response
     * @throws InvalidArgumentException
     */
    public function actionReset()
    {
        if(!Yii::$app->user->isGuest){
            Yii::$app->user->logout();
        }
        if (Yii::$app->request->get() && Yii::$app->request->get('token')) {
            /** @var Request $resetPasswordToken */
            $resetPasswordToken = Yii::$app->request->get('token');
            /** @var ForgotPassword $forgotPasswordCollection */
            $forgotPasswordCollection = ForgotPassword::findOne([
                'fp_token' => $resetPasswordToken,
                'fp_status' => '1',
            ]);

            if ($forgotPasswordCollection) {
                if (date('Y-m-d H:i:s') > date('Y-m-d H:i:s', strtotime($forgotPasswordCollection->fp_update_at . "+1 days"))) {
                    Yii::$app->session->setFlash("error", AuthModule::t('auth', 'reset_link_expired'), TRUE);
                    return $this->redirect(['login']);
                } else {
                    if ($forgotPasswordCollection instanceof ForgotPassword) {

                        $userId = $forgotPasswordCollection->fp_user_id;
                        $userType = $forgotPasswordCollection->fp_user_type;

                        /** @var AdminMaster $userModel */
                        $userModel = new AdminMaster();

                        return $this->render('resetform', [
                            'model' => $userModel,
                            'userId' => $userId,
                            'userType' => $userType,
                            'token' => $resetPasswordToken,
                        ]);
                    }

                }
            }
        }
        Yii::$app->session->setFlash("error", AuthModule::t('auth', 'reset_link_expired'), TRUE);
        return $this->redirect(['login']);
    }

    /**
     * @return string|\yii\web\Response
     * @throws Exception
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function actionUpdatePassword()
    {
        if (Yii::$app->request->post() && Yii::$app->request->post('userId')) {
            $token = Yii::$app->request->post('token');
            /** @var Request $updatePasswordRequest */
            $updatePasswordRequest = Yii::$app->request->post('AdminMaster');
            /** @var Request $userId */
            $userId = Yii::$app->request->post('userId');
            $userType = Yii::$app->request->post('userType');
            $userMasterModel = new AdminMaster();
            $userMasterModel->newPassword = $updatePasswordRequest['newPassword'];
            /** @var request confirmPassword */
            $userMasterModel->confirmPassword = $updatePasswordRequest['confirmPassword'];
            $userMasterModel->setScenario('resetPassword');
            if($userType == 'Extension'){

                $extensionModel = Extension::findOne(['em_extension_number' => $userId]);
                if ($extensionModel->em_web_password == $updatePasswordRequest['newPassword']) {
                    $forgotPasswordCollection = ForgotPassword::findOne([
                        'fp_token' => $token,
                        'fp_status' => '1',
                    ]);
                    if (isset($forgotPasswordCollection)) {
                        $forgotPasswordCollection->fp_status = '0';
                        $forgotPasswordCollection->save(false);
                    }
                    /** @var AdminMaster $userModel */
                    $userModel = new AdminMaster();
                    Yii::$app->session->setFlash("error", AuthModule::t('auth', 'same_password'), TRUE);
                    return $this->render('resetform', [
                        'model' => $userModel,
                        'userId' => $userId,
                        'userType' => $userType,
                        'token' => $token
                    ]);
                }
            }else {

                /** @var AdminMaster $userMasterModel */
                $userMasterModel = AdminMaster::findOne(['adm_username' => $userId]);
                $userMasterModel->newPassword = $updatePasswordRequest['newPassword'];
                /** @var request confirmPassword */
                $userMasterModel->confirmPassword = $updatePasswordRequest['confirmPassword'];
                /** changePassword scenario */
                $userMasterModel->setScenario('resetPassword');

                if ($userMasterModel->adm_password == md5($userMasterModel->newPassword)) {
                    $forgotPasswordCollection = ForgotPassword::findOne([
                        'fp_token' => $token,
                        'fp_status' => '1',
                    ]);
                    if (isset($forgotPasswordCollection)) {
                        $forgotPasswordCollection->fp_status = '0';
                        $forgotPasswordCollection->save(false);
                    }
                    Yii::$app->session->setFlash("error", AuthModule::t('auth', 'same_password'), TRUE);
                    return $this->render('resetform', [
                        'model' => $userMasterModel,
                        'userId' => $userId,
                        'userType' => $userType,
                        'token' => $token
                    ]);

                }
            }

            if ($userMasterModel->validate(['newPassword', 'confirmPassword'])) {
                $token = Yii::$app->request->post('token');
                if ($userType == 'Extension') {
                    $extensionModel = Extension::findOne(['em_extension_number' => $userId]);
                    $extensionModel->em_web_password = $updatePasswordRequest['newPassword'];
                    if($extensionModel->update(false, ['em_web_password'])){

                        $forgotPasswordCollection = ForgotPassword::findOne([
                            'fp_token' => $token,
                            'fp_status' => '1',
                        ]);
                        if (isset($forgotPasswordCollection)) {
                            $forgotPasswordCollection->fp_status = '0';
                            $forgotPasswordCollection->save(false);
                        }
                        $mailer = Yii::$app->mailer;
                        $mailer->view->params['name'] = $extensionModel->em_extension_name;
                        $mailer->compose('@app/mail/layouts/success.php')
                            ->setFrom('vivek.dhamecha@ecosmob.com')
                            ->setTo($extensionModel->em_email)
                            ->setSubject('Password changed successfully.')
                            ->send();

                        Yii::$app->session->setFlash("success", AuthModule::t('auth', 'password_changed'), TRUE);
                        return $this->redirect(['login']);
                    }else {
                        Yii::$app->session->setFlash("error", AuthModule::t('auth', 'something_wrong'), TRUE);
                        return $this->redirect(['login']);
                    }
                } else {
                    $userMasterModel->adm_password = md5($userMasterModel->newPassword);
                    $userMasterModel->adm_password_hash = base64_encode($userMasterModel->newPassword);
                    if ($userMasterModel->update(false, ['adm_password', 'adm_password_hash'])) {

                        $forgotPasswordCollection = ForgotPassword::findOne([
                            'fp_token' => $token,
                            'fp_status' => '1',
                        ]);
                        if (isset($forgotPasswordCollection)) {
                            $forgotPasswordCollection->fp_status = '0';
                            $forgotPasswordCollection->save(false);
                        }
                        $mailer = Yii::$app->mailer;
                        $mailer->view->params['name'] = $userMasterModel->adm_firstname . ' ' . $userMasterModel->adm_lastname;
                        $mailer->compose('@app/mail/layouts/success.php')
                            ->setFrom('vivek.dhamecha@ecosmob.com')
                            ->setTo($userMasterModel->adm_email)
                            ->setSubject('Password changed successfully.')
                            ->send();

                        Yii::$app->session->setFlash("success", AuthModule::t('auth', 'password_changed'), TRUE);
                        return $this->redirect(['login']);
                    } else {
                        Yii::$app->session->setFlash("error", AuthModule::t('auth', 'something_wrong'), TRUE);
                        return $this->redirect(['login']);
                    }
                }
            }

            return $this->render('resetform', [
                'model' => $userMasterModel,
                'userId' => $userId,
                'userType' => $userType,
                'token' => $token
            ]);
        }
    }

    /**
     * @return bool
     */
    public function actionSessionCheck()
    {
        return true;
    }

    public function actionCron()
    {
        echo "https://78.47.30.169/web/index.php?r=auth%2Fauth%2Flogin";
    }

    public function actionAutoLogin()
    {

        $token = $_GET['token'];
        $adm_user = AdminMaster::find()->andWhere(['=', 'auto_login_token', $token])->andWhere(['<>', 'adm_is_admin', 'supervisor'])->andWhere(['<>', 'adm_is_admin', 'agent'])->one();
        if ($adm_user) {
            Yii::$app->user->login($adm_user, true ? 3600 * 24 * 30 : 0);
            Yii::$app->session->set('time_zone', $adm_user->adm_timezone_id);
            $adm_user->adm_token = Yii::$app->session->getId();

            $adm_user->save(false);
            $this->goHome();
        } else {
            return $this->redirect(['login']);
        }

    }

    public function getLogoutTime($userId, $inTime){
        $logoutTime = date('Y-m-d', strtotime($inTime))." 23:59:59";
        $shift = AdminMaster::find()->select(['ct_shift.sft_start_time', 'ct_shift.sft_end_time'])
            ->leftJoin('ct_extension_master', 'ct_extension_master.em_id = admin_master.adm_mapped_extension')
            ->leftJoin('ct_shift', 'ct_shift.sft_id = ct_extension_master.em_shift_id')
            ->where(['admin_master.adm_id' => $userId])
            ->asArray()
            ->one();
        if(!empty($shift)){
            if(date('H:i:s', strtotime($inTime)) >= $shift['sft_start_time'] && date('H:i:s', strtotime($inTime)) <= $shift['sft_end_time']){
                $logoutTime = date('Y-m-d', strtotime($inTime))." ".$shift['sft_end_time'];
            }
        }
        return $logoutTime;
    }
    public function actionSamlSuccessLogin($username){
        Yii::$app->session->set('loginAsExtension', '0');
        $dropUser = [];
        $user = $type = '';
        if(isset($_POST['type'])){
            $type = $_POST['type'];
        }

        if(!empty($username) && empty($type)) {
            $admin = AdminMaster::find()->where(['adm_email' => $username])->one();
            $ext = Extension::findOne(['em_email' => $username]);
            if (!empty($admin) && !empty($ext)) {
                $dropUser[$admin->adm_is_admin] = $admin->adm_is_admin;//$admin->adm_firstname.'  '.$admin->adm_lastname;
                $dropUser['extension'] = 'Extension';//$ext->em_extension_name.' - '.$ext->em_extension_number;
                return $this->render('multi-user-login', [
                    'users' => $dropUser,
                    'username' => $username,
                ]);
            }else{
                if(!empty($admin)) {
                    $type = 'admin';
                }elseif (!empty($ext)){
                    $type = 'extension';
                }
            }
        }

        if(!empty($type)) {
           if($type == 'extension'){
               $user = Extension::findOne(['em_email' => $username]);
               Yii::$app->session->set('loginAsExtension', '1');
           }else{
               $user = AdminMaster::find()->where(['adm_email' => $username])->one();
           }

            if (!empty($user)) {
                $model = new LoginForm();
                $model->loginAsExtension = Yii::$app->session->get('loginAsExtension');
                $model->username = (Yii::$app->session->get('loginAsExtension') == '1' ? $user->em_extension_number : $user->adm_username);
                $model->password = (Yii::$app->session->get('loginAsExtension') == '1' ? $user->em_web_password : $user->adm_password);

                if (Yii::$app->session->get('loginAsExtension')) {
                    $model->loginType = 'admin';
                } else {
                    $model->loginType = ($user->adm_is_admin == 'super_admin' ? 'admin' : 'supervisor');
                }
                $model->extension = '0';

                $model->campaign_type = '0';

                if (Yii::$app->user->login($user)) {
                    Yii::$app->session->set('loginAsExtension', $model->loginAsExtension);
                    if ($model->loginAsExtension) {
                        $extension = Extension::findOne(['em_extension_number' => $model->username]);
                        $userExt = AdminMaster::find()->where(['adm_mapped_extension' => $extension->em_id, 'adm_status' => '1'])->count();
                        if ($userExt > 0) {
                            Yii::$app->session->setFlash('error', AuthModule::t('auth', 'extension_already_assigned'));
                            return $this->redirect(['login']);
                        }else {
                            $extension->em_token = Yii::$app->session->getId();
                            $extension->save(false);
                        }
                        return $this->redirect('/');
                    } else {
                        $admin = AdminMaster::findOne(Yii::$app->user->identity->adm_id);
                        $extensionObj = Extension::findOne(['em_id' => $admin->adm_mapped_extension]);
                        if ($admin->adm_is_admin == 'agent') {
                            $agentId = Yii::$app->user->identity->adm_id . '_' . $GLOBALS['tenantID'];
                            $agent = Agent::find()->andWhere(['name' => $agentId])
                                ->andWhere(['status' => 'Logged Out', 'state' => 'Waiting'])->asArray()->all();
                            if (empty($agent)) {
                                Yii::$app->session->remove('loginAsExtension');
                                Yii::$app->session->remove('extentationNumber');
                                Yii::$app->session->remove('selectedCampaign');
                                Yii::$app->user->logout();
                                Yii::$app->session->setFlash('error', AuthModule::t('auth', 'agentErrorMsg'));
                                return $this->redirect(['login']);
                            }
                        }

                        Yii::$app->session->set('time_zone', $admin->adm_timezone_id);

                        if (!is_object($extensionObj) && ($admin->adm_is_admin == 'supervisor' || $admin->adm_is_admin == 'agent')) {
                            Yii::$app->session->setFlash('error', AuthModule::t('auth', 'extension_not_mapped_please_contact_your_admin'));
                            return $this->redirect(['login']);
                        }

                        $model->extension = $extensionObj ? $extensionObj->em_extension_number : '0';
                        $admin->adm_token = Yii::$app->session->getId();
                        $admin->save(false);
                        if ($admin->adm_is_admin == 'super_admin') {
                            return $this->redirect(['/admin/admin/index']);
                        } else if ($admin->adm_is_admin == 'supervisor') {

                            $supervisorCamp = CampaignMappingUser::find()
                                ->where(['supervisor_id' => Yii::$app->user->id])
                                ->all();

                            if (!empty($supervisorCamp)) {
                                Yii::$app->db->createCommand()
                                    ->update('users_activity_log', (['logout_time' => date('Y-m-d H:i:s')]), ['user_id' => Yii::$app->user->id, 'logout_time' => '0000-00-00 00:00:00'])
                                    ->execute();
                                foreach ($supervisorCamp as $supervisorCamps) {
                                    $activityLog = new UsersActivityLog();
                                    $activityLog->user_id = Yii::$app->user->id;
                                    $activityLog->login_time = date('Y-m-d H:i:s');
                                    $activityLog->campaign_name = $supervisorCamps['campaign_id'];
                                    $activityLog->save(false);
                                }

                                $data = BreakReasonMapping::find()->where(['user_id' => Yii::$app->user->identity->adm_id])
                                    ->orderBy([
                                        'id' => SORT_DESC,
                                    ])->limit(1)->one();
                                if ($data) {
                                    if ($data->out_time == '0000-00-00 00:00:00') {
                                        $breakId = $id = $data->id;

                                        $breakUpdate = BreakReasonMapping::findOne($breakId);
                                        $breakUpdate->break_status = 'Out';
                                        $breakUpdate->out_time = date('Y-m-d H:i:s');
                                        $breakUpdate->save(false);
                                    }
                                }

                            } else {
                                $activityLog = new UsersActivityLog();
                                $activityLog->user_id = Yii::$app->user->id;
                                $activityLog->login_time = date('Y-m-d H:i:s');
                                $activityLog->save(false);
                            }
                            $extensionNumber = $extensionObj->em_extension_number;
                            Yii::$app->session->set('extentationNumber', $extensionNumber);
                            return $this->redirect(['/supervisor/supervisor/dashboard']);
                        } else if ($admin->adm_is_admin == 'agent') {
                            $agentId = Yii::$app->user->identity->adm_id . '_' . $GLOBALS['tenantID'];
                            $extensionNumber = $extensionObj->em_extension_number;
                            Yii::$app->db->createCommand()
                                ->update('agents', (
                                ['contact' =>
                                    '{ignore_early_media=ring_ready,loopback_bowout_on_execute=true,leg_timeout=60,sip_h_X-agent_id=' . Yii::$app->user->identity->adm_id . '}user/' . $extensionNumber . '@$${domain_name}',
                                    'login_extension' => $extensionNumber]), ['name' => $agentId])
                                ->execute();
                            Yii::$app->session->set('extentationNumber', $extensionNumber);
                            $model->scenario = 'agent';
                            return $this->render('agent-login', [
                                'model' => $model,
                            ]);
                        } else {
                            return $this->redirect('/');
                        }
                    }

                    return $this->redirect('');
                }

            } else {
                Yii::$app->session->setFlash('error', AuthModule::t('auth', 'You email not added, please contact to your administrator.'));
                return $this->redirect(['login']);
            }
        }else {
            Yii::$app->session->setFlash('error', AuthModule::t('auth', 'You email not added, please contact to your administrator.'));
            return $this->redirect(['login']);
        }
    }
}
