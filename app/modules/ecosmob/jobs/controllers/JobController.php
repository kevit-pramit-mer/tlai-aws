<?php

namespace app\modules\ecosmob\jobs\controllers;

use app\modules\ecosmob\campaign\models\Campaign;
use app\modules\ecosmob\jobs\JobsModule;
use app\modules\ecosmob\jobs\models\Job;
use app\modules\ecosmob\jobs\models\JobSearch;
use app\modules\ecosmob\timecondition\models\TimeCondition;
use app\modules\ecosmob\timezone\models\Timezone;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * JobController implements the CRUD actions for Job model.
 */
class JobController extends Controller
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
                            'change-job-status',
                            'copy-job',
                            'get-job',
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
     * Lists all Job models.
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new JobSearch();
        $model = new job();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $campaignList = ArrayHelper::map(Campaign::find()->select(['cmp_id', 'cmp_name'])->andWhere(['cmp_status' => 'Active'])->andWhere(['<>', 'cmp_type', 'Inbound'])->all(), 'cmp_id', 'cmp_name');
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'campaignList' => $campaignList,
            'model' => $model,
        ]);
    }

    /**
     * Displays a single Job model.
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
     * Finds the Job model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Job the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Job::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(JobsModule::t('jobs', 'page_not_exits'));
    }

    /**
     * Creates a new Job model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return Response|string
     */
    public function actionCreate()
    {
        $model = new Job();
        $model->scenario = 'creat';

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            $model->save(false);

            Yii::$app->session->setFlash('success', JobsModule::t('jobs', 'created_success'));
            return $this->redirect(['index']);
        }
        /* For Time Zone List */
        $timeZoneList = Timezone::find()->select(['tz_id', 'tz_zone'])->all();
        /* For Time Condition */
        $timeConditionList = TimeCondition::find()->select(['tc_id', 'tc_name'])->all();
        /* For Campaign List */
        $campaignList = Campaign::find()->select(['cmp_id', 'cmp_name'])->andWhere(['cmp_status' => 'Active'])->andWhere(['<>', 'cmp_type', 'Inbound'])->all();

        return $this->render('create', [
            'model' => $model,
            'timeZoneList' => $timeZoneList,
            'timeConditionList' => $timeConditionList,
            'campaignList' => $campaignList,
        ]);
    }

    public function actionCopyJob()
    {
        $model = new job();
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {
            $requestOutboundId = Yii::$app->request->post('jobId');
            $jobModel = job::find()->where(['job_id' => $requestOutboundId])->asArray()->one();

            $newModel = new job();
            $newModel->job_name = $model->job_name;
            $newModel->timezone_id = $jobModel['timezone_id'];
            $newModel->campaign_id = $jobModel['campaign_id'];
            $newModel->concurrent_calls_limit = $jobModel['concurrent_calls_limit'];
            $newModel->answer_timeout = $jobModel['answer_timeout'];
            $newModel->ring_timeout = $jobModel['ring_timeout'];
            $newModel->retry_on_no_answer = $jobModel['retry_on_no_answer'];
            $newModel->retry_on_busy = $jobModel['retry_on_busy'];
            $newModel->job_status = '1';//'Stopped';
            $newModel->activation_status = $jobModel['activation_status'];
            $newModel->time_id = $jobModel['time_id'];
            $newModel->job_dial_status = $jobModel['job_dial_status'];
            $newModel->save();

            Yii::$app->session->setFlash('success', JobsModule::t('jobs', 'created_success'));

        } else {
            Yii::$app->session->setFlash('error', JobsModule::t('jobs', 'Job name must be unique!'));
        }
        return $this->redirect(['index']);
    }


    public function actionGetJob()
    {
        $jobId = Yii::$app->request->post('id');
        $jobName = Job::getJobNameById($jobId);

        $job = [$jobId, $jobName];

        return json_encode($job);
    }

    /**
     * Updates an existing Job model.
     * If update is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return Response|string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->scenario = 'update';

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->save(false);
            if (Yii::$app->request->post('apply') == 'update') {
                Yii::$app->session->setFlash('success', JobsModule::t('jobs', 'applied_success'));
                return $this->refresh();
            } else {
                Yii::$app->session->setFlash('success', JobsModule::t('jobs', 'updated_success'));
                return $this->redirect(['index']);
            }
        }

        /* For Time Zone List */
        $timeZoneList = Timezone::find()->select(['tz_id', 'tz_zone'])->all();

        /* For Time Condition */
        $timeConditionList = TimeCondition::find()->select(['tc_id', 'tc_name'])->all();
        /* For Campaign List */
        $campaignList = Campaign::find()->select(['cmp_id', 'cmp_name'])->andWhere(['cmp_status' => 'Active'])->andWhere(['<>', 'cmp_type', 'Inbound'])->all();
        return $this->render('update', [
            'model' => $model,
            'timeZoneList' => $timeZoneList,
            'timeConditionList' => $timeConditionList,
            'campaignList' => $campaignList,
        ]);
    }

    /**
     * Deletes an existing Job model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if ($model->job_status == '0') {
            $model->delete();
            Yii::$app->session->setFlash('success', JobsModule::t('jobs', 'deleted_success'));
            return $this->redirect(['index']);
        } else {
            Yii::$app->session->setFlash('error', JobsModule::t('jobs', 'job_running'));
            return $this->redirect(['index']);
        }
    }

    /**
     * @return void
     * @throws NotFoundHttpException
     */
    public function actionChangeJobStatus()
    {
        if (!isset($_POST)) {
            echo 'failed';
            die;
        }
        $current_job_status = $_POST['current_job_status'];
        $job_id = $_POST['job_id'];
        $jobModel = $this->findModel($job_id);
        $jobModel->job_status = ($current_job_status) ? 0 : 1;
        $jobModel->save(false);
        echo 'saved';
        die;
    }
}
