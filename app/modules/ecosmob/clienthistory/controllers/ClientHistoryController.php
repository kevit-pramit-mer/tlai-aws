<?php

namespace app\modules\ecosmob\clienthistory\controllers;

use app\components\CommonHelper;
use app\modules\ecosmob\clienthistory\models\CampCdr;
use app\modules\ecosmob\clienthistory\models\CampCdrSearch;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * ClientHistoryController implements the CRUD actions for CampCdr model.
 */
class ClientHistoryController extends Controller
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
                            'customindex',
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
     * Lists all CampCdr models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CampCdrSearch();
        $searchModel->from = date('Y-m-d 00:00:00');
        $searchModel->to = CommonHelper::tsToDt(date('Y-m-d H:i:s'));
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        Yii::$app->session->set('clienthistory', $dataProvider->query);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Lists all CampCdr models.
     * @return mixed
     */
    public function actionCustomindex()
    {
        $searchModel = new CampCdrSearch();
        $searchModel->from = date('Y-m-d 00:00:00');
        $searchModel->to = CommonHelper::tsToDt(date('Y-m-d H:i:s'));
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        Yii::$app->session->set('clienthistory', $dataProvider->query);

        return $this->renderPartial('customindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single CampCdr model.
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
     * Finds the CampCdr model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CampCdr the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CampCdr::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    /**
     * Creates a new CampCdr model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CampCdr();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Created Successfully'));
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing CampCdr model.
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
     * Deletes an existing CampCdr model.
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

    /**
     * Export records shown in Index page
     */
    public function actionExport()
    {
        ini_set("memory_limit", -1);
        $fp = fopen('php://temp', 'w');
        $fileName = "ClientHistory-" . time() . ".csv";
        $model = new CampCdr();
        $query = Yii::$app->session->get('clienthistory');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]],
        ]);

        $records = $dataProvider->getModels();
        $headers = [
            'dial_number',
            'call_disposion_name',
            'disposition_comment',
            //'call_disposion_decription',
            'agent_first_name',
            'agent_last_name',
            'customer_first_name',
            'customer_last_name',
            'call_disposion_start_time',
            'start_time',
            'campaign_name',
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

                    if ($head == 'dial_number') {
                        $row[$head] = (!empty($record->dial_number) ? $record->dial_number : '-');
                    }
                    if ($head == 'call_disposion_name') {
                        $row[$head] = (!empty($record->call_disposion_name) ? $record->call_disposion_name : '-');
                    }
                    if ($head == 'disposition_comment') {
                        $row[$head] = (!empty($record->disposition_comment) ? $record->disposition_comment : '-');
                    }
                   /* if ($head == 'call_disposion_decription') {
                        $row[$head] = (!empty($record->call_disposion_decription) ? $record->call_disposion_decription : '-');
                    }*/
                    if ($head == 'agent_first_name') {
                        $row[$head] = (!empty($record->agent_first_name) ? $record->agent_first_name : '-');
                    }
                    if ($head == 'agent_last_name') {
                        $row[$head] = (!empty($record->agent_last_name) ? $record->agent_last_name : '-');
                    }
                    if ($head == 'customer_first_name') {
                        $row[$head] = (!empty($record->customer_first_name) ? $record->customer_first_name : '-');
                    }
                    if ($head == 'customer_last_name') {
                        $row[$head] = (!empty($record->customer_last_name) ? $record->customer_last_name : '-');
                    }
                    if ($head == 'call_disposion_start_time') {
                        $row[$head] = (!empty($record->call_disposion_start_time) ? CommonHelper::tsToDt($record->call_disposion_start_time) : '-');
                    }
                    if ($head == 'start_time') {
                        $row[$head] = (!empty($record->start_time) ? CommonHelper::tsToDt($record->start_time) : '-');
                    }
                    if ($head == 'campaign_name') {
                        $row[$head] = (!empty($record->campaign_name) ? $record->campaign_name : '-');
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
