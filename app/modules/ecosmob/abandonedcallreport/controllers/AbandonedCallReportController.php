<?php

namespace app\modules\ecosmob\abandonedcallreport\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use app\components\CommonHelper;
use app\modules\ecosmob\abandonedcallreport\models\QueueAbandonedCalls;
use app\modules\ecosmob\abandonedcallreport\models\QueueAbandonedCallsSearch;

/**
 * AbandonedCallReportController implements the CRUD actions for QueueAbandonedCalls model.
 */
class AbandonedCallReportController extends Controller
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
     * Lists all QueueAbandonedCalls models.
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new QueueAbandonedCallsSearch();
        $searchModel->start_time = date('Y-m-d 00:00:00');
        $searchModel->end_time = CommonHelper::tsToDt(date('Y-m-d H:i:s'));
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        Yii::$app->session->set('abandonedcallreport', $dataProvider->query);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single QueueAbandonedCalls model.
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
     * Finds the QueueAbandonedCalls model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return QueueAbandonedCalls the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = QueueAbandonedCalls::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    /**
     * Creates a new QueueAbandonedCalls model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new QueueAbandonedCalls();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Created Successfully'));
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing QueueAbandonedCalls model.
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
     * Deletes an existing QueueAbandonedCalls model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return Response
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
        $fileName = "Abandoned-Call-Report-" . time() . ".csv";
        $model = new QueueAbandonedCallsSearch();
        $query = Yii::$app->session->get('abandonedcallreport');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]],
        ]);

        $records = $dataProvider->getModels();
        $headers = [
            'queue_name',
            'queue_number',
            'caller_id_number',
            'call_status',
            'start_time',
            'end_time',
            'hold_time',

        ];

        $row = array();
        foreach ($headers as $header) {
            $row[] = $model->getAttributeLabel($header);
        }

        fputcsv($fp, $row);
        if (!empty($records)) {
            foreach ($records as $record) {
                $row = array();
                foreach ($headers as $head) {
                    $row[$head] = $record->$head;

                    if ($head == 'queue_name') {
                        $row[$head] = (!empty($record->queue_name) ? $record->queue_name : '-');
                    }
                    if ($head == 'queue_number') {
                        $row[$head] = (!empty($record->queue_number) ? $record->queue_number : '-');
                    }
                    if ($head == 'caller_id_number') {
                        $row[$head] = (!empty($record->caller_id_number) ? $record->caller_id_number : '-');
                    }
                    if ($head == 'call_status') {
                        $row[$head] = (!empty($record->call_status) ? $record->call_status : '-');
                    }
                    if ($head == 'start_time') {
                        $row[$head] = (!empty($record->start_time) ? CommonHelper::tsToDt(date("Y-m-d H:i:s", substr($record->start_time, 0, 10))) : '-');
                    }
                    if ($head == 'end_time') {
                        $row[$head] = (!empty($record->end_time) ? CommonHelper::tsToDt(date("Y-m-d H:i:s", substr($record->end_time, 0, 10))) : '-');
                    }
                    if ($head == 'hold_time') {
                        $row[$head] = (!empty($record->hold_time) ? $record->hold_time : '-');
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
