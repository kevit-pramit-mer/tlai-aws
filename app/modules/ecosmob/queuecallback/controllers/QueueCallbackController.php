<?php

namespace app\modules\ecosmob\queuecallback\controllers;

use app\components\CommonHelper;
use app\modules\ecosmob\queuecallback\models\QueueCallback;
use app\modules\ecosmob\queuecallback\models\QueueCallbackSearch;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * QueueCallbackController implements the CRUD actions for QueueCallback model.
 */
class QueueCallbackController extends Controller
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
     * Lists all QueueCallback models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new QueueCallbackSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        Yii::$app->session->set('queuecallbackreport', $dataProvider->query);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single QueueCallback model.
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
     * Finds the QueueCallback model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return QueueCallback the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = QueueCallback::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    /**
     * Creates a new QueueCallback model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new QueueCallback();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Created Successfully'));
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing QueueCallback model.
     * If update is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return string|Response
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
     * Deletes an existing QueueCallback model.
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

    /**
     * Export records shown in Index page
     */
    public function actionExport()
    {
        ini_set("memory_limit", -1);
        $fp = fopen('php://temp', 'w');
        $fileName = "Queue-Callback-Report-" . time() . ".csv";
        $model = new QueueCallback();
        $query = Yii::$app->session->get('queuecallbackreport');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]],
        ]);

        $records = $dataProvider->getModels();
        $headers = [
            'queue_name',
            'phone_number',
            'created_at',
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
                    if ($head == 'phone_number') {
                        $row[$head] = (!empty($record->phone_number) ? $record->phone_number : '-');
                    }
                    if ($head == 'created_at') {
                        $row[$head] = (!empty($record->created_at) ? CommonHelper::tsToDt($record->created_at) : '-');
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
