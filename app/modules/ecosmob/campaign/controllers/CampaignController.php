<?php

namespace app\modules\ecosmob\campaign\controllers;

use app\modules\ecosmob\agents\models\CampaignMappingAgents;
use app\modules\ecosmob\autoattendant\models\AutoAttendantMaster;
use app\modules\ecosmob\campaign\CampaignModule;
use app\modules\ecosmob\campaign\models\Campaign;
use app\modules\ecosmob\campaign\models\CampaignMappingUser;
use app\modules\ecosmob\campaign\models\CampaignSearch;
use app\modules\ecosmob\carriertrunk\models\TrunkMaster;
use app\modules\ecosmob\conference\models\ConferenceMaster;
use app\modules\ecosmob\disposition\models\DispositionMaster;
use app\modules\ecosmob\extension\models\Extension;
use app\modules\ecosmob\fax\models\Fax;
use app\modules\ecosmob\leadgroup\models\LeadgroupMaster;
use app\modules\ecosmob\playback\models\Playback;
use app\modules\ecosmob\queue\models\QueueMaster;
use app\modules\ecosmob\ringgroup\models\RingGroup;
use app\modules\ecosmob\script\models\Script;
use app\modules\ecosmob\services\models\Services;
use app\modules\ecosmob\supervisor\models\AdminMaster;
use app\modules\ecosmob\supervisor\models\UsersActivityLog;
use app\modules\ecosmob\timezone\models\Timezone;
use Yii;
use yii\base\Exception;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;


/**
 * CampaignController implements the CRUD actions for Campaign model.
 */
class CampaignController extends Controller
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
                            'change-action'
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
     * Lists all Campaign models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CampaignSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $timeZoneList = ArrayHelper::map(Timezone::find()->select(['tz_id', 'tz_zone'])->all(), 'tz_id', 'tz_zone');
        $dispositionList = ArrayHelper::map(DispositionMaster::find()->select(['ds_id', 'ds_name'])->where(['NOT IN', 'is_default', [1, 2]])->all(), 'ds_id', 'ds_name');
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'timeZoneList' => $timeZoneList,
            'dispositionList' => $dispositionList
        ]);
    }

    /**
     * Displays a single Campaign model.
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
     * Finds the Campaign model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Campaign the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Campaign::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    /**
     * Creates a new Campaign model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */

    public function actionCreate()
    {
        $model = new Campaign();
        $availableSupervisors = AdminMaster::getAllSupervisors();
        $availableAgents = AdminMaster::getAllAgents();
        /* For Time Zone List */
        $timeZoneList = Timezone::find()->select(['tz_id', 'tz_zone'])->all();
        /* For Queue List */
        $queueList = QueueMaster::find()->select(['qm_id', new \yii\db\Expression("SUBSTRING_INDEX(qm_name, '_', 1) AS qm_name")])->all();
        /* For Disposition List */
        $dispositionList = DispositionMaster::find()->select(['ds_id', 'ds_name'])->where(['NOT IN', 'is_default', [1, 2]])->all();
        /* For Lead Group List  */
        $leadGroupList = LeadgroupMaster::find()->select(['ld_id', 'ld_group_name'])->all();
        /* For Trunk */
        $trunkList = TrunkMaster::find()->select(['trunk_id', 'trunk_name'])->where(['trunk_status' => 'Y'])->all();
        /* For Script List */
        $scriptList = Script::find()->select(['scr_id', 'scr_name'])->where(['scr_status' => '1'])->all();

        $transaction = Yii::$app->db->beginTransaction();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->validate()) {
                if ($model->cmp_type == 'Blended') {
                    $model->cmp_dialer_type = 'AUTO';
                }
                if ($model->amd_detect == '') {
                    $model->amd_detect = '0';
                }
                if ($model->abandoned_call_try == '') {
                    $model->abandoned_call_try = '0';
                }
                $model->save(false);

                if ($model->supervisors) {
                    $qmIdSupervisor = $model->cmp_id;
                    $supervisors = explode(',', $model->supervisors);
                    $supervisors = array_unique($supervisors);
                    foreach ($supervisors as $value) {

                        $tiersModel = new CampaignMappingUser();
                        $tiersModel->campaign_id = $qmIdSupervisor;
                        $tiersModel->supervisor_id = $value;
                        $tiersModel->save(FALSE);
                    }
                }


                if ($model->agents) {
                    $qmId = $model->cmp_id;
                    $agents = explode(',', $model->agents);
                    $agents = array_unique($agents);
                    foreach ($agents as $value) {

                        $tiersModelAgents = new CampaignMappingAgents();
                        $tiersModelAgents->campaign_id = $qmId;
                        $tiersModelAgents->agent_id = $value;
                        $tiersModelAgents->save(FALSE);
                    }
                }

                $transaction->commit();

                Yii::$app->session->setFlash('success', CampaignModule::t('campaign', 'created_success'));

                return $this->redirect(['index']);
            } else {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', CampaignModule::t('campaign', 'something_wrong'));


                return $this->render('create',
                    [
                        'model' => $model,
                        'availableSupervisors' => $availableSupervisors,
                        'availableAgents' => $availableAgents,
                        'timeZoneList' => $timeZoneList,
                        'queueList' => $queueList,
                        'dispositionList' => $dispositionList,
                        'leadGroupList' => $leadGroupList,
                        'trunkList' => $trunkList,
                        'scriptList' => $scriptList,
                    ]);
            }
        }

        return $this->render('create',
            [
                'model' => $model,
                'availableSupervisors' => $availableSupervisors,
                'availableAgents' => $availableAgents,
                'timeZoneList' => $timeZoneList,
                'queueList' => $queueList,
                'dispositionList' => $dispositionList,
                'leadGroupList' => $leadGroupList,
                'trunkList' => $trunkList,
                'scriptList' => $scriptList,

            ]);
    }

    /**
     * Updates an existing Campaign model.
     * If update is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);


        $allSupervisorsData = AdminMaster::getAllSupervisors();
        $allAgentsData = AdminMaster::getAllAgents();

        $supervisorList = CampaignMappingUser::find()->select('supervisor_id')->where(['campaign_id' => $id])->asArray()->all();
        $agentList = CampaignMappingAgents::find()->select('agent_id')->where(['campaign_id' => $id])->asArray()->all();

        $supervisors = array_intersect(array_column($supervisorList, 'supervisor_id'), array_keys($allSupervisorsData));
        $agents = array_intersect(array_column($agentList, 'agent_id'), array_keys($allAgentsData));

        $availableSupervisorsUpdate = array_diff($allSupervisorsData, $supervisors);
        $availableAgentsUpdate = array_diff($allAgentsData, $agents);
        if (!empty($agentList)) {
            foreach ($agentList as $single) {
                if (isset($availableAgentsUpdate[$single['agent_id']])) {
                    unset($availableAgentsUpdate[$single['agent_id']]);
                }
            }
        }
        if (!empty($supervisorList)) {
            foreach ($supervisorList as $single) {
                if (isset($availableSupervisorsUpdate[$single['supervisor_id']])) {
                    unset($availableSupervisorsUpdate[$single['supervisor_id']]);
                }

            }
        }

        $model->agents = implode(',', $agents);
        $model->supervisors = implode(',', $supervisors);

        /** @var AdminMaster $supervisors */
        $supervisors = AdminMaster::getDetails($model->supervisors);

        /** @var AdminMaster $agents */
        $agents = AdminMaster::getDetails($model->agents);

        /* For Time Zone List */
        $timeZoneList = Timezone::find()->select(['tz_id', 'tz_zone'])->all();
        /* For Queue List */
        $queueList = QueueMaster::find()->select(['qm_id', new \yii\db\Expression("SUBSTRING_INDEX(qm_name, '_', 1) AS qm_name")])->all();
        /* For Disposition List */
        $dispositionList = DispositionMaster::find()->select(['ds_id', 'ds_name'])->where(['NOT IN', 'is_default', [1, 2]])->all();
        /* For Lead Group List  */
        $leadGroupList = LeadgroupMaster::find()->select(['ld_id', 'ld_group_name'])->all();
        /* For Trunk */
        $trunkList = TrunkMaster::find()->select(['trunk_id', 'trunk_name'])->where(['trunk_status' => 'Y'])->all();
        /* For Script List */
        $scriptList = Script::find()->select(['scr_id', 'scr_name'])->where(['scr_status' => '1'])->all();

        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($model->load(Yii::$app->request->post())) {

                if ($model->validate()) {
                    $agentLogin = UsersActivityLog::find()->andWhere(['IN', 'user_id', explode(',',$model->agents)])->andWhere(['logout_time' => '0000-00-00 00:00:00'])->andWhere(['campaign_name' => $id])->count();
                    if($agentLogin > 0){
                        $transaction->rollBack();
                        Yii::$app->session->setFlash('error', CampaignModule::t('campaign', 'agent_already_logg_in'));

                        return $this->render('update',
                            [
                                'model' => $model,
                                'supervisors' => $supervisors,
                                'agents' => $agents,
                                'availableSupervisorsUpdate' => $availableSupervisorsUpdate,
                                'availableAgentsUpdate' => $availableAgentsUpdate,
                                'timeZoneList' => $timeZoneList,
                                'queueList' => $queueList,
                                'dispositionList' => $dispositionList,
                                'leadGroupList' => $leadGroupList,
                                'trunkList' => $trunkList,
                                'scriptList' => $scriptList,
                            ]);
                    }
                    if ($model->cmp_type == 'Blended') {
                        $model->cmp_dialer_type = 'AUTO';
                    }
                    if ($model->cmp_type == 'Outbound') {
                        $model->cmp_week_off_type = '';
                        $model->cmp_week_off_name = '';
                        $model->cmp_holiday_type = '';
                        $model->cmp_holiday_name = '';
                    }
                    if ($model->cmp_type == 'Inbound') {
                        $model->cmp_dialer_type = '';
                        $model->cmp_caller_id = '';
                        $model->cmp_caller_name = '';
                    }
                    $model->save();
                    CampaignMappingUser::deleteAll(['campaign_id' => $id]);
                    CampaignMappingAgents::deleteAll(['campaign_id' => $id]);

                    if ($model->agents) {
                        $agents = explode(',', $model->agents);
                        $agents = array_unique($agents);
                        foreach ($agents as $value) {
                            $tiersModel = new CampaignMappingAgents();
                            $tiersModel->campaign_id = $id;
                            $tiersModel->agent_id = $value;
                            $tiersModel->save(FALSE);
                        }
                    }

                    if ($model->supervisors) {
                        $supervisors = explode(',', $model->supervisors);
                        $supervisors = array_unique($supervisors);
                        foreach ($supervisors as $value) {
                            $tiersModelAgent = new CampaignMappingUser();
                            $tiersModelAgent->campaign_id = $id;
                            $tiersModelAgent->supervisor_id = $value;
                            $tiersModelAgent->save(FALSE);
                        }
                    }

                    $transaction->commit();

                    Yii::$app->session->setFlash('success', CampaignModule::t('campaign', 'updated_success'));
                    return $this->redirect(['index']);
                } else {
                    $transaction->rollBack();
                    if(!$model->getErrors()) {
                        Yii::$app->session->setFlash('error', CampaignModule::t('campaign', 'something_wrong'));
                    }
                    return $this->render('update',
                        [
                            'model' => $model,
                            'supervisors' => $supervisors,
                            'agents' => $agents,
                            'availableSupervisorsUpdate' => $availableSupervisorsUpdate,
                            'availableAgentsUpdate' => $availableAgentsUpdate,
                            'timeZoneList' => $timeZoneList,
                            'queueList' => $queueList,
                            'dispositionList' => $dispositionList,
                            'leadGroupList' => $leadGroupList,
                            'trunkList' => $trunkList,
                            'scriptList' => $scriptList,
                        ]);
                }
            }
        } catch (Exception $e) {
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', CampaignModule::t('campaign', 'something_wrong'));

            return $this->render('update',
                [
                    'model' => $model,
                    'supervisors' => $supervisors,
                    'agents' => $agents,
                    'availableSupervisorsUpdate' => $availableSupervisorsUpdate,
                    'availableAgentsUpdate' => $availableAgentsUpdate,
                    'timeZoneList' => $timeZoneList,
                    'queueList' => $queueList,
                    'dispositionList' => $dispositionList,
                    'leadGroupList' => $leadGroupList,
                    'trunkList' => $trunkList,
                    'scriptList' => $scriptList,
                ]);
        }

        return $this->render('update', [
            'model' => $model,
            'timeZoneList' => $timeZoneList,
            'queueList' => $queueList,
            'dispositionList' => $dispositionList,
            'leadGroupList' => $leadGroupList,
            'trunkList' => $trunkList,
            'scriptList' => $scriptList,
            'availableSupervisorsUpdate' => $availableSupervisorsUpdate,
            'availableAgentsUpdate' => $availableAgentsUpdate,
            'supervisors' => $supervisors,
            'agents' => $agents,
            /*'timeConditionList' => $timeConditionList,*/
        ]);
    }

    /**
     * Deletes an existing Campaign model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', CampaignModule::t('campaign', 'deleted_success'));
        return $this->redirect(['index']);
    }

    public function actionChangeAction()
    {
        $action_value = "";

        if (isset($_POST['action_id'])) {
            $action_id = $_POST['action_id'];
            $action_value = (isset($_POST['action_value']) ? $_POST['action_value'] : '');
            /** @var Services $data */
            $data = Services::find()->where(['ser_id' => $action_id])->asArray()->one();
            if (sizeof($data)) {
                $ser_name = $data['ser_name'];
                if ($ser_name == 'EXTENSION') {
                    $data = Extension::find()->select(['em_id AS id', 'CONCAT(em_extension_name," - ", em_extension_number)  AS name'])->asArray()->all();
                } else if ($ser_name == 'IVR' || $ser_name == 'AUDIO TEXT') {
                    $data = AutoAttendantMaster::find()->select(['aam_id AS id', 'aam_name AS name'])->asArray()->all();
                } else if ($ser_name == 'QUEUE') {
                    $data = QueueMaster::find()->select(['qm_id AS id', new \yii\db\Expression("SUBSTRING_INDEX(qm_name, '_', 1) AS name")])->asArray()->all();
                } else if ($ser_name == 'VOICEMAIL') {
                    $data = Extension::find()->select(['ct_extension_master.em_id AS id', 'ct_extension_master.em_extension_name AS name'])
                        ->leftJoin('ct_extension_call_setting as ecs', 'ecs.em_id = ct_extension_master.em_id')
                        ->where(['ecs.ecs_voicemail' => '1'])
                        ->asArray()->all();
                } else if ($ser_name == 'RING GROUP') {
                    $data = RingGroup::find()->select(['rg_id AS id', 'rg_name AS name'])->asArray()->all();
                } else if ($ser_name == 'EXTERNAL') {
                    $data = '';
                } else if ($ser_name == 'CONFERENCE') {
                    $data = ConferenceMaster::find()->select(['cm_id AS id', new \yii\db\Expression("SUBSTRING_INDEX(cm_name, '_', 1) AS name")])->asArray()->all();
                } else if ($ser_name == 'PLAYBACK') {
                    $data = Playback::find()->select(['pb_id AS id', 'pb_name AS name'])->asArray()->all();
                } else if ($ser_name == 'FAX') {
                    $data = Fax::find()->select(['id AS id', 'fax_name AS name'])->asArray()->all();
                } else if ($ser_name == 'CAMPAIGN') {
                    $data = Campaign::find()->select(['cmp_id AS id', 'cmp_name AS name'])->where(['=', 'cmp_type', 'Inbound'])->asArray()->all();
                } else {
                    $data = '';
                }
            }
        } else {
            $data = '';
        }

        return $this->renderPartial('change-action',
            [
                'action_value' => $action_value,
                'data' => $data,
            ]);
    }
}
