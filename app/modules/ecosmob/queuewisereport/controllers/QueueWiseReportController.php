<?php

namespace app\modules\ecosmob\queuewisereport\controllers;

use app\components\CommonHelper;
use app\modules\ecosmob\globalconfig\models\GlobalConfig;
use app\modules\ecosmob\queue\models\QueueMaster;
use app\modules\ecosmob\queuewisereport\models\QueueWiseReport;
use app\modules\ecosmob\queuewisereport\models\QueueWiseReportSearch;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * QueueWiseReportController implements the CRUD actions for QueueWiseReport model.
 */
class QueueWiseReportController extends Controller
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
                            'export',
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
     * Lists all QueueWiseReport models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new QueueWiseReportSearch();
        $searchModel->queue_started = date('Y-m-d');
        $searchModel->queue_ended = date('Y-m-d');
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        Yii::$app->session->set('queuewisereport', $dataProvider->allModels);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single QueueWiseReport model.
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
     * Finds the QueueWiseReport model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return QueueWiseReport the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = QueueWiseReport::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    /**
     * Creates a new QueueWiseReport model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new QueueWiseReport();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Created Successfully'));
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing QueueWiseReport model.
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
                Yii::$app->session->setFlash('success', Yii::t('app', 'Applied Successfully.'));
                return $this->refresh();
            } else {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Updated Successfully.'));
                return $this->redirect(['index']);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing QueueWiseReport model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', Yii::t('app', 'Deleted Successfully.'));
        return $this->redirect(['index']);
    }

    public function actionExport()
    {
        ini_set("memory_limit", -1);
        $fp = fopen('php://temp', 'w');

        $fileName = "Queue-Summary-Report-" . time() . ".csv";
        $model = new QueueWiseReport();

        $query = Yii::$app->session->get('queuewisereport');
        $query =  array_slice($query, 0, GlobalConfig::getValueByKey('export_limit'));

        $records = $query;


        $attr = [
            'queue',
            'queue_num',
            'incoming_call',
            'answered',
            'abandoned',
            'agent',
            'total_call_duration',
            'avg_call_duration',
            'avg_waiting_time',
        ];

        $row = [];
        foreach ($attr as $header) {
            $row[] = $model->getAttributeLabel($header);
        }
        fputcsv($fp, $row);
        if (!empty($records)) {
            foreach ($records as $key => $record) {
                $row = [];
                foreach ($attr as $head) {

                    if ($head == 'queue') {
                        $row[$head] = (!empty($record['queue']) ? QueueMaster::getQueueName($record['queue']) : '-');
                    }
                    if ($head == 'queue_num') {
                        $row[$head] = $record['queue_num'];
                    }
                    if ($head == 'incoming_call') {
                        $row[$head] = $record['incoming_call'];
                    }
                    if ($head == 'answered') {
                        $row[$head] = $record['answered'];
                    }
                    if ($head == 'abandoned') {
                        $row[$head] = $record['abandoned'];
                    }
                    if ($head == 'agent') {
                        $row[$head] = $record['agent'];
                    }
                    if ($head == 'total_call_duration') {
                        $row[$head] = (!empty($record['total_call_duration']) ? gmdate("H:i:s", $record['total_call_duration']) : '-');
                    }
                    if ($head == 'avg_call_duration') {
                        $row[$head] = (!empty($record['avg_call_duration']) ? gmdate("H:i:s", $record['avg_call_duration']) : '-');
                    }
                    if ($head == 'avg_waiting_time') {
                        $row[$head] = (!empty($record['avg_waiting_time']) ? gmdate("H:i:s", $record['avg_waiting_time']) : '-');
                    }
                }
                fputcsv($fp, $row);
            }
        }

        rewind($fp);
        header("Content-type:text/csv");
        header("Content-Disposition:attachment; filename=" . $fileName);
        $file = stream_get_contents($fp);
        echo "\xEF\xBB\xBF";
        echo $file;
        fclose($fp);
        exit;
    }
}
