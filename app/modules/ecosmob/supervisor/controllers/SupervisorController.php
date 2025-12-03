<?php

namespace app\modules\ecosmob\supervisor\controllers;

use app\components\CommonHelper;
use app\models\SipRegistrations;
use app\modules\ecosmob\activecalls\models\ActiveCallsSearch;
use app\modules\ecosmob\agent\models\Agent;
use app\modules\ecosmob\agents\models\CampaignMappingAgents;
use app\modules\ecosmob\campaign\models\Campaign;
use app\modules\ecosmob\campaign\models\CampaignMappingUser;
use app\modules\ecosmob\crm\models\ActiveCalls;
use app\modules\ecosmob\extension\models\Extension;
use app\modules\ecosmob\globalconfig\models\GlobalConfig;
use app\modules\ecosmob\queue\models\QueueMaster;
use app\modules\ecosmob\supervisor\models\AdminMaster;
use app\modules\ecosmob\supervisor\models\AdminMasterSearch;
use app\modules\ecosmob\supervisor\models\BreakReasonMapping;
use app\modules\ecosmob\supervisor\models\Members;
use app\modules\ecosmob\supervisor\models\UsersActivityLog;
use app\modules\ecosmob\supervisor\SupervisorModule;
use Exception;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\rbac\DbManager;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * SupervisorController implements the CRUD actions for AdminMaster model.
 */
class SupervisorController extends Controller
{
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
                            'dashboard',
                            'new-dashboard',
                            'submit-break-reason',
                            'login-link',
                            'break-count',
                            'agent-report',
                            'active-call-list',
                            'new-active-call-list',
                            'active-agent-list',
                            'waiting-members',
                            'break-time',
                            'remove-sip'
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
     * Lists all AdminMaster models.
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new AdminMasterSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single AdminMaster model.
     * @param integer $id
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Finds the AdminMaster model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AdminMaster the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AdminMaster::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(SupervisorModule::t('supervisor', 'page_not_exits'));
    }

    /**
     * Creates a new AdminMaster model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return string|Response
     * @throws Exception
     */
    public function actionCreate()
    {
        $licenseData = Yii::$app->commonHelper->getLicenseData($_SERVER['HTTP_HOST']);
        if(!empty($licenseData)) {
            $maxSupervisors = $licenseData['maxSupervisors'];
            $totalSupervisor = AdminMaster::find()->where(['adm_is_admin' => 'supervisor'])->count();
            if ($totalSupervisor >= $maxSupervisors) {
                Yii::$app->session->setFlash('error', Yii::t('app', 'license_limit_exceed'));
                return $this->redirect(['index']);
            }
        }
        $model = new AdminMaster();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->adm_is_admin = 'supervisor';
            $model->adm_password_hash = base64_encode($model->adm_password);
            $model->adm_password = md5($model->adm_password);
            if ($model->save(false)) {
                $role = (object)['name' => 'supervisor'];
                $role_id = $model->adm_id;

                $assignment = new DbManager();
                $assignment->assign($role, $role_id);
                Yii::$app->session->setFlash('success', SupervisorModule::t('supervisor', 'created_success'));
                return $this->redirect(['index']);
            }

        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing AdminMaster model.
     * If update is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return Response|string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->adm_is_admin = 'supervisor';
            $model->adm_password_hash = base64_encode($model->adm_password);
            $model->adm_password = md5($model->adm_password);
            $model->save(false);
            Yii::$app->session->setFlash('success', SupervisorModule::t('supervisor', 'updated_success'));
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing AdminMaster model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->delete();
        Yii::$app->session->setFlash('success', SupervisorModule::t('supervisor', 'deleted_success'));
        return $this->redirect(['index']);
    }

    public function actionDashboard()
    {
        $globalTime = GlobalConfig::find()->select('gwc_value')->where(['gwc_key' => 'refresh_interval'])->one();
        $refreshInterval = $globalTime->gwc_value;

        if (yii::$app->session->get('extentationNumber')) {
            $extensionNumber = yii::$app->session->get('extentationNumber');
        } else {
            $extensionNumber = '';
        }
        $extensionInformation = Extension::find()->select(['em_extension_number', 'em_password', 'em_extension_name'])->where(['em_extension_number' => $extensionNumber])->one();

        $model = new ActiveCalls();
        $breakReason = new BreakReasonMapping();
        if ($breakReason->load(Yii::$app->request->post()) && $breakReason->validate()) {
            $breakReason->save();
        }

        $searchModel = new ActiveCallsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('dashboard',
            [
                'model' => $model,
                'breakReason' => $breakReason,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'extensionInformation' => $extensionInformation,
                'refreshInterval' => $refreshInterval,
            ]);
    }

    public function actionNewDashboard()
    {
        $globalTime = GlobalConfig::find()->select('gwc_value')->where(['gwc_key' => 'refresh_interval'])->one();
        $refreshInterval = $globalTime->gwc_value;

        if (yii::$app->session->get('extentationNumber')) {
            $extensionNumber = yii::$app->session->get('extentationNumber');
        } else {
            $extensionNumber = '';
        }
        $extensionInformation = Extension::find()->select(['em_extension_number', 'em_password', 'em_extension_name'])->where(['em_extension_number' => $extensionNumber])->one();

        $model = new ActiveCalls();
        $breakReason = new BreakReasonMapping();
        if ($breakReason->load(Yii::$app->request->post()) && $breakReason->validate()) {
            $breakReason->save();
        }

        $searchModel = new ActiveCallsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('new_dashboard',
            [
                'model' => $model,
                'breakReason' => $breakReason,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'extensionInformation' => $extensionInformation,
                'refreshInterval' => $refreshInterval,
            ]);
    }

    public function actionBreakCount()
    {
        $agentsCallStatus = array();
        $agentsList = array();
        $cmpType = $_POST['camp_type'];
        $current_date = date('Y-m-d');
        $agentsIdsList = array();
        $campaignIds = CampaignMappingAgents::find()->select(['campaign_id'])->asArray()->all();
        $agentsIds = CampaignMappingAgents::find()->select(['agent_id'])->asArray()->all();

        foreach ($agentsIds as $agentsValue) {
            $agentsIdsList[] = $agentsValue['agent_id'];
        }
        $ids = implode(",", array_map(function ($a) {
            return implode("~", $a);
        }, $campaignIds));

        $supervisorList = CampaignMappingUser::find()->select(['campaign_id'])
            ->andWhere(new Expression('FIND_IN_SET(campaign_id,"' . $ids . '")'))
            ->asArray()
            ->all();

        $breakCount = BreakReasonMapping::find()
            ->leftjoin('admin_master am', 'am.adm_id = break_reason_mapping.user_id')
            ->andWhere(['break_reason_mapping.out_time' => '0000-00-00 00:00:00'])
            //->andWhere(['break_reason_mapping.camp_id' => $cmpType])
            ->andWhere(new Expression('FIND_IN_SET("' . $cmpType . '", break_reason_mapping.camp_id)'))
            //->andWhere(['break_reason_mapping.camp_id' => !0 ])
            ->andWhere(['am.adm_is_admin' => 'agent'])
            ->andWhere("DATE_FORMAT(in_time,'%Y-%m-%d') = '$current_date'")
            ->count();


        $supervisorCampaignList = CampaignMappingUser::find()->select(['campaign_id'])
            ->where(['supervisor_id' => Yii::$app->user->identity->adm_id])
            ->asArray()
            ->all();

        foreach ($supervisorCampaignList as $supervisorCampaignListValue) {
            $supervisorList[] = $supervisorCampaignListValue['campaign_id'];
        }

        $agentsCampIds = CampaignMappingAgents::find()
            ->select(['agent_id'])
            ->where(['campaign_id' => $supervisorList])
            ->andWhere(['campaign_id' => $cmpType])
            ->asArray()
            ->all();

        foreach ($agentsCampIds as $agentsValue) {
            $agentsList[] = $agentsValue['agent_id'];
        }

        $agentsAvailableList = Agent::find()
            ->leftjoin('users_activity_log brm', 'brm.user_id = SUBSTRING_INDEX(agents.name, \'_\', 1)')
            ->where(['SUBSTRING_INDEX(agents.name, \'_\', 1)' => $agentsList])
            ->andWhere(['agents.status' => 'Available'])
            ->andWhere(['agents.state' => 'Waiting'])
            ->andWhere(new Expression('FIND_IN_SET("' . $cmpType . '", brm.campaign_name)'))
            ->andWhere(['brm.logout_time' => '0000-00-00 00:00:00'])
            ->groupBy('brm.user_id')
            ->count();

        $agentCount = UsersActivityLog::find()
            ->leftjoin('admin_master am', 'am.adm_id = users_activity_log.user_id')
            ->where(['users_activity_log.logout_time' => '0000-00-00 00:00:00'])
            ->andWhere(new Expression('FIND_IN_SET("' . $cmpType . '", users_activity_log.campaign_name)'))
            ->andWhere(['am.adm_is_admin' => 'agent'])
            ->groupBy('users_activity_log.user_id')
            ->count();

        $supervisorCount = UsersActivityLog::find()
            ->leftjoin('admin_master am', 'am.adm_id = users_activity_log.user_id')
            ->leftjoin('campaign_mapping_user cmu', 'cmu.supervisor_id = users_activity_log.user_id')
            ->where(['users_activity_log.logout_time' => '0000-00-00 00:00:00'])
            ->andWhere(new Expression('FIND_IN_SET("' . $cmpType . '", users_activity_log.campaign_name)'))
            ->andWhere(['cmu.campaign_id' => $cmpType])
            ->andWhere(['am.adm_is_admin' => 'supervisor'])
            ->groupBy('users_activity_log.user_id')
            ->count();

        $activeCallsCount = ActiveCalls::find()->andWhere(['campaign_id' => $cmpType])->count();
        $inOut = 'in';
        $break = BreakReasonMapping::find()->where(['user_id' => Yii::$app->user->identity->adm_id, 'camp_id' => $cmpType])->orderBy(['id' => SORT_DESC])->limit(1)->one();
        if(!empty($break)){
            $inOut = ($break->break_status == 'In' ? 'out' : 'in');
        }

        $query = Yii::$app->db->createCommand("select SUM(TIMESTAMPDIFF(SECOND ,in_time,out_time) ) as total FROM break_reason_mapping
		  WHERE DATE(in_time) = '" . $current_date . "' AND DATE(out_time) = '" . $current_date . "' AND user_id = '" . Yii::$app->user->identity->adm_id . "'  AND camp_id = '" . $cmpType . "'")
            ->queryAll();

        $query1 = Yii::$app->db->createCommand("select SUM(TIMESTAMPDIFF(SECOND ,in_time, UTC_TIMESTAMP()) ) as total FROM break_reason_mapping WHERE DATE(in_time) = '" . $current_date . "' AND user_id = '" . Yii::$app->user->identity->adm_id . "' AND out_time = '0000-00-00 00:00:00'  AND camp_id = '" . $cmpType . "'")
            ->queryAll();


        $totalSecond = round($query[0]['total'] + $query1[0]['total']);
        $totalSecondFormat = sprintf('%02d:%02d:%02d', ($totalSecond / 3600), ($totalSecond / 60 % 60), $totalSecond % 60);


        $agentsCallStatus['activeCallsCount'] = $activeCallsCount;
        $agentsCallStatus['breakCount'] = $breakCount;
        $agentsCallStatus['agentsAvailableList'] = $agentsAvailableList;
        $agentsCallStatus['total'] = $breakCount + $agentsAvailableList;
        $agentsCallStatus['agentCount'] = $agentCount;
        $agentsCallStatus['supervisorCount'] = $supervisorCount;
        $agentsCallStatus['inOut'] = $inOut;
        $agentsCallStatus['breakTime'] = $totalSecondFormat;

        return Json::encode($agentsCallStatus);
    }

    public function actionSubmitBreakReason()
    {
        $breakReasonMapping = new BreakReasonMapping();
        $id = 0;
        $selectedCampaignData = '';

        if ($breakReasonMapping->load(Yii::$app->request->post())) {

            if (Yii::$app->user->identity->adm_is_admin == 'agent') {

                $selectedCampaignData = (isset($_SESSION['selectedCampaign']) && !empty($_SESSION['selectedCampaign'])) ? $_SESSION['selectedCampaign'] : 0;
            } else {
                $selectedCampaignData = (isset($_POST['cmp_id']) && !empty($_POST['cmp_id'])) ? $_POST['cmp_id'] : 0;
            }
            //insert record
            if ($_POST['type'] == 'in') {

                if (!empty($selectedCampaignData)) {
                    $breakReasonMapping->in_time = date('Y-m-d H:i:s');
                    $breakReasonMapping->out_time = '0000-00-00 00:00:00';
                    $breakReasonMapping->break_status = 'In';
                    $breakReasonMapping->user_id = Yii::$app->user->identity->adm_id;
                    $breakReasonMapping->camp_id = $selectedCampaignData;
                    $breakReasonMapping->save(false);
                }

                if (Yii::$app->user->identity->adm_is_admin == 'agent') {
                    Yii::$app->db->createCommand()
                        ->update('agents', (['status' => 'On Break', 'state' => 'Waiting']), ['name' => Yii::$app->user->identity->adm_id . '_' . $GLOBALS['tenantID']])
                        ->execute();
                }

                $id = $breakReasonMapping->id;
            } else if ($_POST['type'] == 'out') {
                //Update Break Data
                $data = BreakReasonMapping::find()->where(['user_id' => Yii::$app->user->identity->adm_id, 'camp_id' => $selectedCampaignData])
                    ->orderBy([
                        'id' => SORT_DESC,
                    ])->limit(1)->one();

                if ($data) {
                    $breakId = $id = $data->id;

                    $breakUpdate = BreakReasonMapping::findOne($breakId);
                    $breakUpdate->break_status = 'Out';
                    $breakUpdate->out_time = date('Y-m-d H:i:s');
                    $breakUpdate->save(false);
                }

                if (Yii::$app->user->identity->adm_is_admin == 'agent') {
                    Yii::$app->db->createCommand()
                        ->update('agents', (['status' => 'Available', 'state' => 'Waiting']), ['name' => Yii::$app->user->identity->adm_id . '_' . $GLOBALS['tenantID']])
                        ->execute();

                }
            }
        }
        echo $id;
        die;
    }


    public function actionLoginLink()
    {
        Yii::$app->user->logout();
        $this->goHome();

    }

    /**
     * Lists all AdminMaster models.
     * @return string
     */
    public function actionAgentReport()
    {
        $searchModel = new AdminMasterSearch();
        $dataProvider = $searchModel->agentsearch(Yii::$app->request->queryParams);
        return $this->render('agent-report', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionActiveCallList()
    {
        $searchModel = new ActiveCallsSearch();
        $dataProvider1 = $searchModel->search(Yii::$app->request->queryParams);
        if ($dataProvider1->getTotalCount() == 0) {
            die();
        }
        return $this->renderPartial('_active-calls',
            [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider1,

            ]);
    }

    public function actionNewActiveCallList()
    {
        $searchModel = new ActiveCallsSearch();
        $dataProvider1 = $searchModel->search(Yii::$app->request->queryParams);

        $query = $dataProvider1->query->all();

        $activeRecord = [];
        if (!empty($query)) {
            foreach ($query as $dataAll) {
                $cmp_type = Campaign::findOne(['cmp_id' => $dataAll['campaign_id']]);

                if (($cmp_type->cmp_type == 'Outbound' && $cmp_type->cmp_dialer_type == 'PROGRESSIVE') || ($cmp_type->cmp_type == 'Outbound' && $cmp_type->cmp_dialer_type == 'PREVIEW')) {
                    if (!empty($dataAll['destination_number'])) {
                        $activeRecord['caller_id'] = $dataAll['destination_number'];
                    } else {
                        $activeRecord['caller_id'] = '-';
                    }
                } else {
                    if (!empty($dataAll['caller_id'])) {
                        $activeRecord['caller_id'] = $dataAll['caller_id'];
                    } else {
                        $activeRecord['caller_id'] = '-';
                    }
                }

                $activeRecord['queue'] = !empty($dataAll['queue']) ? $dataAll['queue'] : '-';

                if (isset($dataAll['agent'])) {
                    $agentName = AdminMaster::findOne($dataAll['agent']);
                    $activeRecord['agent'] = $agentName->adm_username;
                } else {
                    $activeRecord['agent'] = '-';
                }

                if (isset($dataAll['campaign_id'])) {
                    $campName = Campaign::findOne($dataAll['campaign_id']);
                    $activeRecord['campaign_id'] = $campName->cmp_name;
                } else {
                    $activeRecord['campaign_id'] = '-';
                }

                if (isset($dataAll['call_start_time'])) {
                    $activeRecord['call_start_time'] = CommonHelper::tsToDt($dataAll['call_start_time']);
                } else {
                    $activeRecord['call_start_time'] = '-';
                }

                if ($dataAll['call_agent_time'] != "") {
                    $time1 = strtotime($dataAll['call_agent_time']);
                    $time2 = strtotime(date("Y-m-d H:i:s"));
                    $total = $time2 - $time1;

                    $hours = floor($total / 3600);
                    if ($hours < 10) {
                        $hours = "0" . $hours;
                    }
                    $minutes = floor(($total / 60) % 60);
                    if ($minutes < 10) {
                        $minutes = "0" . $minutes;
                    }
                    $seconds = $total % 60;
                    if ($seconds < 10) {
                        $seconds = "0" . $seconds;
                    }
                    $activeRecord['call_agent_time'] = "$hours:$minutes:$seconds";
                } else {
                    $activeRecord['call_agent_time'] = '-';
                }

                $activeRecord['uuid'] = $dataAll['uuid'];
                $activeRecord['active_id'] = $dataAll['active_id'];
                $activeRecord['whisper_uuid'] = $dataAll['whisper_uuid'];
            }
        }

        return json_encode($activeRecord);

    }

    public function actionActiveAgentList()
    {
        $camp_id = $_GET['camp_id'];
        $query = UsersActivityLog::find()
            ->select(['ual.*', 'a.*', 'CONCAT(adm.adm_firstname," ",adm.adm_lastname) as agent_name'])
            ->from('users_activity_log ual')
            ->innerJoin('agents a', 'a.name = CONCAT(ual.user_id, "_", "'.$GLOBALS['tenantID'].'")')
            ->innerJoin('admin_master adm', 'adm.adm_id = ual.user_id')
            ->andWhere(new Expression('FIND_IN_SET("' . $camp_id . '", ual.campaign_name)'))
            ->andWhere('ual.logout_time = "0000-00-00 00:00:00" OR ual.logout_time = "" ')
            ->andWhere('adm.adm_is_admin = "agent"');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if ($dataProvider->getTotalCount() == 0) {
            die();
        }
        return $this->renderPartial('_active-agents', ['dataProvider' => $dataProvider]);
    }


    public function actionWaitingMembers()
    {
        $camp_id = $_GET['camp_id'];
        $cmpQueue = Campaign::find()->select(['cmp_queue_id'])->where(['cmp_id' => $camp_id])->one();
        $cmpQueueId = $cmpQueue->cmp_queue_id;
        $queueId = QueueMaster::find()->select(['qm_name'])->where(['qm_id' => $cmpQueueId])->one();
        $queueName = isset($queueId->qm_name) ? $queueId->qm_name : '';

        $query1 = Members::find()->select([
            "queue", "cid_number", "cid_name", "system_epoch", "joined_epoch", "state", 'rejoined_epoch'])
            ->where(['queue' => $queueName])
            ->andWhere(['OR', ['state' => 'Waiting'], ['state' => 'Trying']]);

        $dataProvider2 = new ActiveDataProvider([
            'query' => $query1,
        ]);
        if ($dataProvider2->getTotalCount() == 0) {
            die();
        }
        return $this->renderPartial('_waiting-member', ['dataProvider' => $dataProvider2]);
    }

    public function actionBreakTime(){
        $current_date = date('Y-m-d');
        $cmpType = $_POST['camp_type'];
        $query = Yii::$app->db->createCommand("select SUM(TIMESTAMPDIFF(SECOND ,in_time,out_time) ) as total FROM break_reason_mapping
		  WHERE DATE(in_time) = '" . $current_date . "' AND DATE(out_time) = '" . $current_date . "' AND user_id = '" . Yii::$app->user->identity->adm_id . "'  AND camp_id = '" . $cmpType . "'")
            ->queryAll();

        $query1 = Yii::$app->db->createCommand("select SUM(TIMESTAMPDIFF(SECOND ,in_time, UTC_TIMESTAMP()) ) as total FROM break_reason_mapping WHERE DATE(in_time) = '" . $current_date . "' AND user_id = '" . Yii::$app->user->identity->adm_id . "' AND out_time = '0000-00-00 00:00:00'  AND camp_id = '" . $cmpType . "'")
            ->queryAll();


        $totalSecond = round($query[0]['total'] + $query1[0]['total']);
        $totalSecondFormat = sprintf('%02d:%02d:%02d', ($totalSecond / 3600), ($totalSecond / 60 % 60), $totalSecond % 60);

        return json_encode([$totalSecondFormat]);

    }

    public function actionRemoveSip(){
        $extension = Extension::findOne(Yii::$app->user->identity->adm_mapped_extension);
        SipRegistrations::deleteAll(['AND', ['sip_user' => $extension->em_extension_number], ['sip_host' => $_SERVER['HTTP_HOST']], ['LIKE', 'user_agent', 'SIP.js/0.21.2%', false]]);
    }

}
