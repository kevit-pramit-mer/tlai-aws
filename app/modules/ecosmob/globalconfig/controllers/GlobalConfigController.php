<?php

namespace app\modules\ecosmob\globalconfig\controllers;

use app\modules\ecosmob\globalconfig\GlobalConfigModule;
use app\modules\ecosmob\globalconfig\models\GlobalConfig;
use app\modules\ecosmob\globalconfig\models\GlobalConfigSearch;
use Yii;
use yii\base\InvalidArgumentException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * GlobalConfigController implements the CRUD actions for GlobalConfigModule model.
 */
class GlobalConfigController extends Controller
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
                            'update',
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
     * Lists all GlobalConfigModule models.
     *
     * @return string
     * @throws InvalidArgumentException
     */
    public function actionIndex()
    {
        $searchModel = new GlobalConfigSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index',
            [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
    }

    /**
     * Updates an existing GlobalConfigModule model.
     * If update is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     *
     * @return string|Response
     * @throws InvalidArgumentException
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $oldModel = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {

            if ($model->gwc_key == 'moh_file') {
                $model->gwc_value = UploadedFile::getInstance($model, 'gwc_value');

                if ($model->gwc_value) {
                    $audioFilePath = Url::to(Yii::$app->params['tenantStorageFullPath'] .'audio-libraries/'. $GLOBALS['tenantID'] . '/moh/default/');

                    // Deleting all the files in the list
                    array_map('unlink', array_filter((array) glob($audioFilePath.'*')));

                    if (!is_dir($audioFilePath)) {
                        Yii::$app->storageHelper->makeDirAndGivePermission($audioFilePath);
                    }
                    $extension = pathinfo($model->gwc_value, PATHINFO_EXTENSION);
                    $gwc_key = urlencode($model->gwc_key).'_'.time();
                    $uploadFileName = $audioFilePath . $gwc_key . "." . $extension;
                    $model->gwc_value->saveAs($uploadFileName, FALSE);
                    $model->gwc_value = $GLOBALS['tenantID'] . '/moh/default/'.$gwc_key . "." . $extension;
                } else {
                    $model->gwc_value = $model->getOldAttribute('gwc_value');
                }

                if ($model->save()) {
                    Yii::$app->session->setFlash('success', GlobalConfigModule::t('gc', 'updated_successfully'));
                    return $this->redirect(['index']);
                }
            } else {

                if ($oldModel->gwc_value != $model->gwc_value) {
                    $data = $model->gwc_value;
                    if ($data['resultStatus'] == 'SUCCESS') {
                        Yii::$app->fsofiapi->methodSofiaOriginate($data['data'], $model->gwc_value);
                    }
                }
                if ($model->save()) {
                    Yii::$app->session->setFlash('success', GlobalConfigModule::t('gc', 'updated_successfully'));
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
     * Finds the GlobalConfigModule model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     *
     * @return GlobalConfig|null the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = GlobalConfig::findOne($id)) !== NULL) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
