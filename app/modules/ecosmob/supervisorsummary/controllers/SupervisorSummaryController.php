<?php

namespace app\modules\ecosmob\supervisorsummary\controllers;

use app\components\CommonHelper;
use app\modules\ecosmob\supervisorsummary\models\UsersActivityLog;
use app\modules\ecosmob\supervisorsummary\models\UsersActivityLogSearch;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * SupervisorSummaryController implements the CRUD actions for UsersActivityLog model.
 */
class SupervisorSummaryController extends Controller
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
     * Lists all UsersActivityLog models.
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new UsersActivityLogSearch();
        $searchModel->from = date('Y-m-d');
        $searchModel->to = date('Y-m-d');
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        Yii::$app->session->set('supervisorsummary', $dataProvider->query);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single UsersActivityLog model.
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
     * Finds the UsersActivityLog model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return UsersActivityLog the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = UsersActivityLog::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    /**
     * Creates a new UsersActivityLog model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return string|Response
     */
    public function actionCreate()
    {
        $model = new UsersActivityLog();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Created Successfully'));
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing UsersActivityLog model.
     * If update is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return Response|string
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
     * Deletes an existing UsersActivityLog model.
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
        $fileName = "Time-Clock-Report-" . time() . ".csv";
        $model = new UsersActivityLog();
        $query = Yii::$app->session->get('supervisorsummary');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false
        ]);

        $records = $dataProvider->getModels();
        $headers = [
            'adm_firstname',
            'date',
            'login_time',
            'logout_time',
            'break_time',
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

                    if ($head == 'adm_firstname') {
                        $row[$head] = (!empty($record->adm_firstname) ? $record->adm_firstname . ' ' . $record->adm_lastname : '-');
                    }
                    if ($head == 'date') {
                        $row[$head] = (!empty($record->login_time) ? date('Y-m-d', strtotime(CommonHelper::tsToDt($record->login_time))) : '-');
                    }
                    if ($head == 'login_time') {
                        $row[$head] = (!empty($record->login_time) ? date('H:i:s', strtotime(CommonHelper::tsToDt($record->login_time))) : '-');
                    }
                    if ($head == 'logout_time') {
                        $row[$head] = ($record->logout_time != '0000-00-00 00:00:00' ? date('H:i:s', strtotime(CommonHelper::tsToDt($record->logout_time))) : '-');
                    }
                    if ($head == 'break_time') {
                        $row[$head] = (!empty($record->break_time) ? gmdate("H:i:s", $record->break_time) : '-');
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
