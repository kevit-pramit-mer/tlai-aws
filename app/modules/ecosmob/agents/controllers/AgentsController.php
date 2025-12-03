<?php

namespace app\modules\ecosmob\agents\controllers;

use app\components\CommonHelper;
use app\modules\ecosmob\agent\models\Agent;
use app\modules\ecosmob\agents\AgentsModule;
use app\modules\ecosmob\agents\models\AdminMaster;
use app\modules\ecosmob\agents\models\AdminMasterSearch;
use app\modules\ecosmob\callhistory\models\CampCdr;
use app\modules\ecosmob\campaign\models\Campaign;
use app\modules\ecosmob\supervisor\models\BreakReasonMapping;
use Exception;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use yii\rbac\DbManager;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * AgentsController implements the CRUD actions for AdminMaster model.
 */
class AgentsController extends Controller
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
                            'customdashboard',
                            'updatedashboard',
                            'in-call'
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
     * @return mixed
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

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    /**
     * Creates a new AdminMaster model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     * @throws Exception
     */
    public function actionCreate()
    {
        $licenseData = Yii::$app->commonHelper->getLicenseData($_SERVER['HTTP_HOST']);
        if(!empty($licenseData)) {
            $maxAgents = $licenseData['maxAgents'];
            $totalAgents = AdminMaster::find()->where(['adm_is_admin' => 'agent'])->count();
            if ($totalAgents >= $maxAgents) {
                Yii::$app->session->setFlash('error', Yii::t('app', 'license_limit_exceed'));
                return $this->redirect(['index']);
            }
        }
        $model = new AdminMaster();
        $agents_model = new Agent();
        $model->adm_username = '';
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->adm_is_admin = 'agent';
            $model->adm_password_hash = base64_encode($model->adm_password);
            $model->adm_password = md5($model->adm_password);
            $model->save(false);

            $agents_model->name = $model->adm_id.'_'.$GLOBALS['tenantID'];
            $agents_model->contact =
                '{ignore_early_media=ring_ready,loopback_bowout_on_execute=true,leg_timeout=60}user/1092@$${domain_name}';
            if ($agents_model->save(false)) {
                $role = (object)['name' => 'agent'];
                $role_id = $model->adm_id;

                /** @var DbManager $assignment */
                $assignment = new DbManager();
                $assignment->assign($role, $role_id);

                Yii::$app->session->setFlash('success', AgentsModule::t('agents', 'created_success'));
                return $this->redirect(['index']);
            }
        }

        return $this->render('create', [
            'model' => $model,
            'agents_model' => $agents_model
        ]);
    }

    /**
     * Updates an existing AdminMaster model.
     * If update is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->adm_is_admin = 'agent';
            $model->adm_password_hash = base64_encode($model->adm_password);
            $model->adm_password = md5($model->adm_password);
            $model->adm_mapped_extension = (int)$model->adm_mapped_extension;
            $model->save(false);
            Yii::$app->session->setFlash('success', AgentsModule::t('agents', 'updated_success'));
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
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', AgentsModule::t('agents', 'deleted_success'));
        return $this->redirect(['index']);
    }

    public function actionDashboard()
    {
        $session = yii::$app->session;
        $agentCamp = $session->get('selectedCampaign');

        /* Total Calls  */
        $currentDate = date('Y-m-d');

        $totalCalls = CampCdr::find()
            ->where(['agent_id' => Yii::$app->user->identity->adm_id])
            ->andWhere(['camp_name' => $agentCamp])
            ->andWhere(['>=', 'DATE_FORMAT(start_time, "%Y-%m-%d")', $currentDate])
            ->andWhere(['<=', 'DATE_FORMAT(start_time, "%Y-%m-%d")', $currentDate])
            ->count();

        $totalTalkTime = Yii::$app->db->createCommand("select SUM(TIMESTAMPDIFF(SECOND ,ans_time,end_time) ) as total FROM camp_cdr
          WHERE DATE(ans_time) = '" . $currentDate . "' AND DATE(end_time) = '" . $currentDate . "' AND agent_id = '" . Yii::$app->user->identity->adm_id . "' AND camp_name = '" . $agentCamp . "' ")
            ->queryAll();

        $totalSecond = round($totalTalkTime[0]['total']);
        $totalSecondFormatTime = sprintf('%02d:%02d:%02d', ($totalSecond / 3600), ($totalSecond / 60 % 60), $totalSecond % 60);

        $query = Yii::$app->db->createCommand("select SUM(TIMESTAMPDIFF(SECOND ,in_time,out_time) ) as total FROM break_reason_mapping
          WHERE DATE(in_time) = '" . $currentDate . "' AND DATE(out_time) = '" . $currentDate . "' AND user_id = '" . Yii::$app->user->identity->adm_id . "' AND camp_id = '" . $agentCamp . "' ")
            ->queryAll();


        $totalSecond = round($query[0]['total']);
        $totalSecondFormat = sprintf('%02d:%02d:%02d', ($totalSecond / 3600), ($totalSecond / 60 % 60), $totalSecond % 60);


        $model = new AdminMaster();
        $breakReason = new BreakReasonMapping();
        $searchModel = new AdminMasterSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('dashboard',
            [
                'model' => $model,
                'breakReason' => $breakReason,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'query' => $totalSecondFormat,
                'totalCalls' => $totalCalls,
                'totalTalkTimeMinute' => $totalSecondFormatTime
            ]);
    }

    public function actionCustomdashboard()
    {
        $session = yii::$app->session;
        $agentCamp = $session->get('selectedCampaign');

        /* Total Calls  */
        $currentDate = date('Y-m-d');
        $from = CommonHelper::DtTots($currentDate.' 00:00:00');
        $to = CommonHelper::DtTots($currentDate.' 23:59:59');

        $totalCalls = Campaign::find()
            ->from('ct_call_campaign ccc')
            ->innerjoin('camp_cdr cc', 'ccc.cmp_id = cc.camp_name')
            ->andWhere(['cc.agent_id' => Yii::$app->user->identity->adm_id])
            ->andWhere(['(CASE WHEN current_active_camp IS NULL THEN cc.camp_name ELSE current_active_camp END)' => explode(',', $agentCamp)])
            ->andWhere(['ccc.cmp_status' => 'Active'])
            ->andWhere(['>=', 'start_time', trim($from)])
            ->andWhere(['<=', 'start_time', trim($to)])
            ->groupBy(['cc.id'])
            ->count();

        /* Total Talk Time */

        $totalTalkTime = Yii::$app->db->createCommand("select SUM(TIMESTAMPDIFF(SECOND ,ans_time,end_time) ) as total FROM camp_cdr
          WHERE ans_time >= '" . $from . "' AND end_time <= '" . $to . "' AND agent_id = '" . Yii::$app->user->identity->adm_id . "' AND ((CASE WHEN current_active_camp IS NULL THEN camp_name ELSE current_active_camp END) IN (" . $agentCamp . ")) AND end_time IS NOT NULL AND ans_time < end_time")
            ->queryAll();

        $totalSecond = round($totalTalkTime[0]['total']);
        $totalSecondFormatTime = sprintf('%02d:%02d:%02d', ($totalSecond / 3600), ($totalSecond / 60 % 60), $totalSecond % 60);


        $query = Yii::$app->db->createCommand("select SUM(TIMESTAMPDIFF(SECOND ,in_time,out_time) ) as total FROM break_reason_mapping
		  WHERE DATE(in_time) = '" . $currentDate . "' AND DATE(out_time) = '" . $currentDate . "' AND user_id = '" . Yii::$app->user->identity->adm_id . "'")
            ->queryAll();

        $query1 = Yii::$app->db->createCommand("select SUM(TIMESTAMPDIFF(SECOND ,in_time, UTC_TIMESTAMP()) ) as total FROM break_reason_mapping WHERE DATE(in_time) = '" . $currentDate . "' AND user_id = '" . Yii::$app->user->identity->adm_id . "' AND out_time = '0000-00-00 00:00:00'")
            ->queryAll();


        $totalSecond = round($query[0]['total'] + $query1[0]['total']);
        $totalSecondFormat = sprintf('%02d:%02d:%02d', ($totalSecond / 3600), ($totalSecond / 60 % 60), $totalSecond % 60);


        $model = new AdminMaster();
        $breakReason = new BreakReasonMapping();
        $searchModel = new AdminMasterSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->renderPartial('customdashboard',
            [
                'model' => $model,
                'breakReason' => $breakReason,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'query' => $totalSecondFormat,
                'totalCalls' => $totalCalls,
                'totalTalkTimeMinute' => $totalSecondFormatTime
            ]);
    }

    public function actionUpdatedashboard()
    {
        $session = yii::$app->session;
        $agentCamp = $session->get('selectedCampaign');

        /* Total Calls  */
        $currentDate = date('Y-m-d');
        if(!empty($_GET['camp_id'])){
            $agentCamp = $_GET['camp_id'];
        }

        $from = CommonHelper::DtTots($currentDate.' 00:00:00');
        $to = CommonHelper::DtTots($currentDate.' 23:59:59');

        /*$totalCalls = CampCdr::find()
            ->innerjoin('admin_master am', 'am.adm_id = camp_cdr.agent_id')
            ->innerjoin('ct_call_campaign ccc', 'ccc.cmp_id = camp_cdr.camp_name')
            ->leftJoin('ct_lead_group_member lgm', 'lgm.ld_id = ccc.cmp_lead_group')
            ->leftjoin('ct_queue_master qm', 'qm.qm_id = camp_cdr.queue')
            ->andWhere(['am.adm_is_admin' => 'agent'])
            ->andWhere(['ccc.cmp_status' => 'Active'])
            ->andWhere(['am.adm_id' => Yii::$app->user->identity->adm_id])
            ->andWhere(['(CASE WHEN current_active_camp IS NULL THEN camp_cdr.camp_name ELSE current_active_camp END)' => explode(',', $agentCamp)])
            ->andWhere(['>=', 'start_time', trim($from)])
            ->andWhere(['<=', 'start_time', trim($to)])
            ->groupBy('camp_cdr.id')
            ->count();*/

        $totalCalls = Campaign::find()
            ->from('ct_call_campaign ccc')
            ->innerjoin('camp_cdr cc', 'ccc.cmp_id = cc.camp_name')
            ->andWhere(['cc.agent_id' => Yii::$app->user->identity->adm_id])
            ->andWhere(['(CASE WHEN current_active_camp IS NULL THEN cc.camp_name ELSE current_active_camp END)' => explode(',', $agentCamp)])
            ->andWhere(['ccc.cmp_status' => 'Active'])
            ->andWhere(['>=', 'start_time', trim($from)])
            ->andWhere(['<=', 'start_time', trim($to)])
            ->groupBy(['cc.id'])
            ->count();

        $totalTalkTime = Yii::$app->db->createCommand("select SUM(TIMESTAMPDIFF(SECOND ,ans_time,end_time) ) as total FROM camp_cdr
          WHERE ans_time >= '" . $from . "' AND end_time <= '" . $to . "' AND agent_id = '" . Yii::$app->user->identity->adm_id . "' AND ((CASE WHEN current_active_camp IS NULL THEN camp_name ELSE current_active_camp END) IN (" . $agentCamp . ")) AND end_time IS NOT NULL AND ans_time < end_time")
            ->queryAll();

        $totalSecond = round($totalTalkTime[0]['total']);
        $totalSecondFormatTime = sprintf('%02d:%02d:%02d', ($totalSecond / 3600), ($totalSecond / 60 % 60), $totalSecond % 60);

        $query = Yii::$app->db->createCommand("select SUM(TIMESTAMPDIFF(SECOND ,in_time,out_time) ) as total FROM break_reason_mapping WHERE DATE(in_time) = '" . $currentDate . "' AND DATE(out_time) = '" . $currentDate . "' AND user_id = '" . Yii::$app->user->identity->adm_id . "'")
            ->queryAll();

        $query1 = Yii::$app->db->createCommand("select SUM(TIMESTAMPDIFF(SECOND ,in_time, UTC_TIMESTAMP()) ) as total FROM break_reason_mapping WHERE DATE(in_time) = '" . $currentDate . "' AND user_id = '" . Yii::$app->user->identity->adm_id . "' AND out_time = '0000-00-00 00:00:00'")
            ->queryAll();

        $totalSecond = round($query[0]['total'] + $query1[0]['total']);
        $totalSecondFormat = sprintf('%02d:%02d:%02d', ($totalSecond / 3600), ($totalSecond / 60 % 60), $totalSecond % 60);

        $result = [];

        $result['query'] = $totalSecondFormat;
        $result['totalCalls'] = $totalCalls;
        $result['totalTalkTimeMinute'] = $totalSecondFormatTime;

        return Json::encode($result);

    }

    public function actionInCall(){
        $agent = Agent::find()->andWhere(['name' => Yii::$app->user->identity->adm_id . '_' . $GLOBALS['tenantID']])
            ->andWhere(['OR',
                ['status' => 'Logged Out', 'state' => 'Waiting'],
                ['status' => 'Available', 'state' => 'Waiting'],
                ['status' => 'On Break', 'state' => 'Waiting']])
            ->asArray()->all();
        if(!empty($agent)){
            return false;
        }else{
            return true;
        }
    }
}
