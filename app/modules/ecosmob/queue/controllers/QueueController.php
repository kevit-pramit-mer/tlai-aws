<?php

namespace app\modules\ecosmob\queue\controllers;

use app\modules\ecosmob\autoattendant\models\AutoAttendantMaster;
use app\modules\ecosmob\campaign\models\Campaign;
use app\modules\ecosmob\conference\models\ConferenceMaster;
use app\modules\ecosmob\extension\models\Extension;
use app\modules\ecosmob\fax\models\Fax;
use app\modules\ecosmob\playback\models\Playback;
use app\modules\ecosmob\queue\models\QueueMaster;
use app\modules\ecosmob\queue\models\QueueMasterSearch;
use app\modules\ecosmob\queue\models\Tiers;
use app\modules\ecosmob\queue\QueueModule;
use app\modules\ecosmob\ringgroup\models\RingGroup;
use app\modules\ecosmob\services\models\Services;
use app\modules\ecosmob\supervisor\models\AdminMaster;
use Throwable;
use Yii;
use yii\base\Exception;
use yii\base\InvalidArgumentException;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * QueueController implements the CRUD actions for QueueMaster model.
 */
class QueueController extends Controller
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
                            'update',
                            'delete',
                            'change-action',
                        ],
                        'allow' => TRUE,
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
     * Lists all QueueMaster models.
     *
     * @return string
     * @throws InvalidArgumentException
     */
    public function actionIndex()
    {
        $searchModel = new QueueMasterSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index',
            [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
    }

    /**
     * Creates a new QueueMaster model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     *
     * @return string|Response
     * @throws InvalidArgumentException
     */
    public function actionCreate()
    {

        $availableAgents = AdminMaster::getAllAgents();
        $model = new QueueMaster();
        $transaction = Yii::$app->db->beginTransaction();

        try {
            if ($model->load(Yii::$app->request->post())) {
                $model->qm_max_waiting_calls = "1";
                $model->qm_wrap_up_time = "60";
                $model->qm_display_name_in_caller_id = '0';
                if ($model->validate()) {
                    $model->qm_name = $model->qm_name.'_'.$GLOBALS['tenantID'];
                    $model->qm_strategy = empty($model->qm_strategy) ? 'ring-all' : $model->qm_strategy;
                    $model->save(FALSE);

                    if ($model->agents) {
                        $qmId = $model->qm_name;
                        $agents = explode(',', $model->agents);
                        $position = 1;
                        foreach ($agents as $value) {
                            $tiersModel = new Tiers();
                            $tiersModel->queue = $qmId;
                            $tiersModel->agent = $value.'_'.$GLOBALS['tenantID'];
                            $tiersModel->position = $position;
                            $tiersModel->save(FALSE);
                            $position++;
                          /*  $agentId = $value.'_'.$GLOBALS['tenantID'];
                            Yii::$app->db->createCommand()
                                ->update('agents', (['status' => 'Available', 'state' => 'Waiting']), ['name' => $agentId])
                                ->execute();*/
                        }
                    }

                    $transaction->commit();
                    Yii::$app->fsofiapi->methodReloadQueue("create", $model->qm_name);
                    Yii::$app->session->setFlash('success', QueueModule::t('queue', 'created_success'));

                    return $this->redirect(['index']);
                } else {
                    $transaction->rollBack();
                    //Yii::$app->session->setFlash('error', QueueModule::t('queue', 'something_wrong'));
                    $model->qm_name = QueueMaster::getQueueName($model->qm_name);
                    return $this->render('create',
                        [
                            'model' => $model,
                            'availableAgents' => $availableAgents,
                        ]);
                }
            }
        } catch (Exception $e) {
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', QueueModule::t('queue', 'something_wrong'));

            $model->qm_name = QueueMaster::getQueueName($model->qm_name);
            return $this->render('create',
                [
                    'model' => $model,
                    'availableAgents' => $availableAgents,
                ]);
        }
        $model->qm_name = QueueMaster::getQueueName($model->qm_name);
        return $this->render('create',
            [
                'model' => $model,
                'availableAgents' => $availableAgents,
            ]);
    }

    /**
     * Updates an existing QueueMaster model.
     * If update is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     *
     * @return Response|string
     * @throws NotFoundHttpException if the model cannot be found
     * @throws InvalidArgumentException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $allAgentsData = AdminMaster::getAllAgents();
        $agentList = Tiers::find()->select(['SUBSTRING_INDEX(agent, \'_\', 1) as agent'])->where(['queue' => $model->qm_name])->orderBy(['position' => SORT_ASC])->asArray()->all();

        $agents = array_intersect(array_column($agentList, 'agent'), array_keys($allAgentsData));

        $availableAgentsUpdate = array_diff($allAgentsData, $agents);

        if (!empty($agentList)) {
            foreach ($agentList as $single) {

                if (isset($availableAgentsUpdate[$single['agent']])) {
                    unset($availableAgentsUpdate[$single['agent']]);
                }
            }
        }
        $preAgent = $agents;
        $model->agents = implode(',', $agents);

        $agentDetails = AdminMaster::getDetails($model->agents);
        $agents = array();
        foreach ($preAgent as $key =>$val) {
            $agents[$val] = $agentDetails[$val];
        }
        $transaction = Yii::$app->db->beginTransaction();
        try {
            Tiers::deleteAll(['queue' => $model->qm_name]);
            if ($model->load(Yii::$app->request->post())) {
                $model->qm_max_waiting_calls = "1";
                $model->qm_wrap_up_time = "60";
                if ($model->validate()) {
                    $model->qm_name = $model->qm_name.'_'.$GLOBALS['tenantID'];
                    $model->qm_strategy = empty($model->qm_strategy) ? 'ring-all' : $model->qm_strategy;
                    $model->save(FALSE);

                    //Tiers::deleteAll( [ 'queue' => $model->qm_name ] );
                    if ($model->agents) {
                        $qmId = $model->qm_name;
                        $agents = explode(',', $model->agents);
                        $position = 1;
                        foreach ($agents as $value) {
                            $tiersModel = new Tiers();
                            $tiersModel->queue = $qmId;
                            $tiersModel->agent = $value.'_'.$GLOBALS['tenantID'];
                            $tiersModel->position = $position;
                            $tiersModel->save(FALSE);
                            $position++;
                          /*  $agentId = $value.'_'.$GLOBALS['tenantID'];
                            Yii::$app->db->createCommand()
                                ->update('agents', (['status' => 'Available', 'state' => 'Waiting']), ['name' => $agentId])
                                ->execute();*/
                        }
                    }

                    $transaction->commit();
                    Yii::$app->fsofiapi->methodReloadQueue("update", $model->qm_name);

                    Yii::$app->session->setFlash('success', QueueModule::t('queue', 'updated_success'));
                    return $this->redirect(['index']);

                } else {
                    $transaction->rollBack();
                    //Yii::$app->session->setFlash('error', QueueModule::t('queue', 'something_wrong'));

                    $model->qm_name = QueueMaster::getQueueName($model->qm_name);
                    return $this->render('update',
                        [
                            'model' => $model,
                            'agents' => $agents,
                            'availableAgentsUpdate' => $availableAgentsUpdate,
                        ]);
                }
            }
        } catch (Exception $e) {
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', QueueModule::t('queue', 'something_wrong'));
            $model->qm_name = QueueMaster::getQueueName($model->qm_name);
            return $this->render('update',
                [
                    'model' => $model,
                    'agents' => $agents,
                    'availableAgentsUpdate' => $availableAgentsUpdate,
                ]);
        }
        $model->qm_name = QueueMaster::getQueueName($model->qm_name);
        return $this->render('update',
        [
            'model' => $model,
            'agents' => $agents,
            'availableAgentsUpdate' => $availableAgentsUpdate,
        ]);
    }

    /**
     * Finds the QueueMaster model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     *
     * @return QueueMaster the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = QueueMaster::findOne($id)) !== NULL) {
            return $model;
        }
        throw new NotFoundHttpException(QueueModule::t('queue', 'page_not_exits'));
    }

    /**
     * Deletes an existing QueueMaster model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     *
     * @return Response
     * @throws Throwable
     * @throws StaleObjectException
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $this->findModel($id)->delete();
        Yii::$app->fsofiapi->methodReloadQueue("delete", $model->qm_name);
        Yii::$app->session->setFlash('success', QueueModule::t('queue', 'deleted_success'));

        return $this->redirect(['index']);
    }

    /**
     * @return string
     * @throws InvalidArgumentException
     */
    public function actionChangeAction()
    {
        $action_value = "";

        if (isset($_POST['action_id'])) {
            $action_id = $_POST['action_id'];
            $action_value = $_POST['action_value'];
            /** @var Services $data */
            $data = Services::find()->where(['ser_id' => $action_id])->asArray()->one();
            if (sizeof($data)) {
                $ser_name = $data['ser_name'];
                if ($ser_name == 'EXTENSION') {
                    $data = Extension::find()->select(['em_id AS id', 'CONCAT(em_extension_name, " - ", em_extension_number) AS name'])->asArray()->all();
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
                }else if ($ser_name == 'CAMPAIGN') {
                    $data = Campaign::find()->select(['cmp_id AS id', 'cmp_name AS name'])->where(['=','cmp_type','Inbound'])->asArray()->all();
                }else {
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

    public function sorter($a, $b){

    }
}
