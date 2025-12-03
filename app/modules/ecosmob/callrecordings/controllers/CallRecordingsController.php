<?php

namespace app\modules\ecosmob\callrecordings\controllers;

use Yii;
use app\modules\ecosmob\callrecordings\models\CallRecordings;
use app\modules\ecosmob\callrecordings\models\CallRecordingsSearch;
use yii\helpers\FileHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\modules\ecosmob\callrecordings\CallRecordingsModule;

/**
 * CallRecordingsController implements the CRUD actions for CallRecordings model.
 */
class CallRecordingsController extends Controller
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
     * Lists all CallRecordings models.
     * @return mixed
     */
    public function actionIndex()
    {
        $data_result = array();
        $files = FileHelper::findFiles('/usr/local/freeswitch/recordings');
        foreach ($files as $key=>$file){
            $filename = $file;
            if (file_exists($filename)) {
                $file_date = date("d-m-Y H:i:s.", filectime($filename));
                $audio_file = "<audio controls='controls'>
                <source src='" . $filename . "' type='audio/mp3' />
               </audio>";

                $data_result[$key]['name'] = basename($filename);
                $data_result[$key]['created_date'] = $file_date;

            }
        }

        $namefilter = Yii::$app->request->getQueryParam('name', '');
        $datefilter = Yii::$app->request->getQueryParam('created_date', '');

        $searchModel = ['name' => $namefilter, 'created_date' => $datefilter];

        $dataProvider = new \yii\data\ArrayDataProvider([
            'key'=>'created_date',
            'allModels' => $data_result,
            'sort' => [
                'attributes' => [ 'name', 'created_date'],
            ],
        ]);


        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider

        ]);
    }

    function filter($item) {
        $mailfilter = Yii::$app->request->getQueryParam('name', '');
        if (strlen($mailfilter) > 0) {
            if (strpos($item['name'], $mailfilter) != false) {
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    }

    /**
     * Displays a single CallRecordings model.
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
     * Creates a new CallRecordings model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CallRecordings();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success',CallRecordingsModule::t('app','created_success'));
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing CallRecordings model.
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
                Yii::$app->session->setFlash('success', CallRecordingsModule::t('app', 'applied_success'));
                return $this->refresh();
            } else {
                Yii::$app->session->setFlash('success', CallRecordingsModule::t('app', 'updated_success'));
                return $this->redirect(['index']);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing CallRecordings model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', CallRecordingsModule::t('app', 'deleted_success'));
        return $this->redirect(['index']);
    }

    /**
     * Finds the CallRecordings model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CallRecordings the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CallRecordings::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(CallRecordingsModule::t('app', 'page_not_exits'));
    }
}
