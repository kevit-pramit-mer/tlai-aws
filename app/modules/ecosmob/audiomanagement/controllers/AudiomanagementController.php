<?php

namespace app\modules\ecosmob\audiomanagement\controllers;

use app\modules\ecosmob\audiomanagement\AudioManagementModule;
use app\modules\ecosmob\audiomanagement\models\AudioManagement;
use app\modules\ecosmob\audiomanagement\models\AudioManagementSearch;
use Throwable;
use Yii;
use yii\base\InvalidArgumentException;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * AudiomanagementController implements the CRUD actions for AudioManagement model.
 */
class AudiomanagementController extends Controller
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
     * Lists all AudioManagement models.
     *
     * @return string
     * @throws InvalidArgumentException
     */
    public function actionIndex()
    {
        $searchModel = new AudioManagementSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index',
            [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
    }

    /**
     * Creates a new AudioManagement model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     *
     * @return string|Response
     * @throws InvalidArgumentException
     */
    public function actionCreate()
    {
        $model = new AudioManagement();
        $model->setScenario('create');

        if ($model->load(Yii::$app->request->post()) && $model->validate(['af_name', 'af_type', 'af_language', 'af_description', 'af_extension'])) {

            if ($model->af_type != 'Recording') {
                $audioFilePath = Url::to(Yii::$app->params['tenantStorageFullPath'] . '/audio-libraries/'.$GLOBALS['tenantID'].'/' );

                if (!is_dir($audioFilePath)) {
                    Yii::$app->storageHelper->makeDirAndGivePermission($audioFilePath);
                }
                $fileName = UploadedFile::getInstance($model, 'af_file');
                $model->af_file = $fileName;
                if (!$model->af_file->hasError) {
                    $extension = pathinfo($model->af_file, PATHINFO_EXTENSION);
                    $af_name = urlencode($model->af_name);
                    $uploadFileName = $audioFilePath . $af_name . "." . $extension;

                    if ($model->af_file->saveAs($uploadFileName, FALSE)) {
                        $model->af_file = $GLOBALS['tenantID'].'/'.$af_name . "." . $extension;
                        if ($model->save()) {
                            Yii::$app->session->setFlash('success', AudioManagementModule::t('am', 'created_success'));

                            return $this->redirect(['index']);
                        }
                    }
                }
            } else {
                $model->af_file = str_replace(" ", "", $model->af_name) . "_" . $model->af_extension . '.wav';

                Yii::$app->fsofiapi->methodSofiaOriginateNew("loopback/record_session/calltech " . $model->af_file, $model->af_extension);

                if ($model->save()) {
                    Yii::$app->session->setFlash('success', AudioManagementModule::t('am', 'created_success'));

                    return $this->redirect(['index']);
                }
            }
        }

        return $this->render('create',
            [
                'model' => $model,
            ]);
    }

    /**
     * Updates an existing AudioManagement model.
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
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            if ($model->af_type != 'Recording') {
                $model->af_file = UploadedFile::getInstance($model, 'af_file');

                if ($model->af_file) {
                    $audioFilePath = Url::to(Yii::$app->params['tenantStorageFullPath'] .'/audio-libraries/'. $GLOBALS['tenantID'].'/');
                    $extension = pathinfo($model->af_file, PATHINFO_EXTENSION);
                    $af_name = urlencode($model->af_name);
                    $uploadFileName = $audioFilePath . $af_name . "." . $extension;
                    $model->af_file->saveAs($uploadFileName, FALSE);
                    $model->af_file = $GLOBALS['tenantID'].'/'.$af_name . "." . $extension;
                } else {
                    $model->af_file = $model->getOldAttribute('af_file');
                }
                if ($model->save()) {
                    Yii::$app->session->setFlash('success', AudioManagementModule::t('am', 'updated_success'));
                    return $this->redirect(['index']);
                }
            } else {
                $model->af_file = str_replace(" ", "", $model->af_name) . "_" . $model->af_extension . '.wav';

                Yii::$app->fsofiapi->methodSofiaOriginateNew("loopback/record_session/calltech " . $model->af_file, $model->af_extension);

                if ($model->save()) {
                    Yii::$app->session->setFlash('success', AudioManagementModule::t('am', 'updated_success'));
                    return $this->redirect(['index']);
                }
            }

        }

        return $this->render('update',
            [
                'model' => $model,
            ]);
    }

    /**
     * Finds the AudioManagement model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     *
     * @return AudioManagement the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AudioManagement::findOne($id)) !== NULL) {
            return $model;
        }

        throw new NotFoundHttpException(AudioManagementModule::t('am', 'page_not_found'));
    }

    /**
     * Deletes an existing AudioManagement model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     *
     * @return Response
     * @throws Throwable
     * @throws InvalidArgumentException
     * @throws StaleObjectException
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        $audioFilePath = Url::to(Yii::$app->params['tenantStorageFullPath'] . '/audio-libraries/'. $model->af_file);

        if (file_exists($audioFilePath)) {
            unlink($audioFilePath);
        }

        $model->delete();
        Yii::$app->session->setFlash('success', AudioManagementModule::t('am', 'deleted_success'));

        return $this->redirect(['index']);
    }
}
