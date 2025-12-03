<?php

namespace app\modules\ecosmob\playback\controllers;

use app\modules\ecosmob\playback\models\Playback;
use app\modules\ecosmob\playback\models\PlaybackSearch;
use app\modules\ecosmob\playback\PlaybackModule;
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
 * PlaybackController implements the CRUD actions for Playback model.
 */
class PlaybackController extends Controller
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
     * Lists all Playback models.
     *
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function actionIndex()
    {
        $searchModel = new PlaybackSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index',
            [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
    }

    /**
     * Creates a new Playback model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     *
     * @return string|Response
     * @throws InvalidArgumentException
     */
    public function actionCreate()
    {
        $model = new Playback();

        if ($model->load(Yii::$app->request->post()) && $model->validate(['pb_name', 'pb_language'])) {
            $audioFilePath = Url::to(Yii::$app->params['tenantStorageFullPath'] . $GLOBALS['tenantID'] . '/playback/');

            if (!is_dir($audioFilePath)) {
                Yii::$app->storageHelper->makeDirAndGivePermission($audioFilePath);
            }

            $fileName = UploadedFile::getInstance($model, 'pb_file');
            $model->pb_file = $fileName;

            if (!$model->pb_file->hasError) {
                $extension = pathinfo($model->pb_file, PATHINFO_EXTENSION);
                $uploadFileName = $audioFilePath . $model->pb_name . "." . $extension;

                if ($model->pb_file->saveAs($uploadFileName, FALSE)) {

                    $model->pb_file = $model->pb_name . "." . $extension;
                    if ($model->save()) {
                        Yii::$app->session->setFlash('success', PlaybackModule::t('pb', 'created_success'));

                        return $this->redirect(['index']);
                    }
                }
            }
        }

        return $this->render('create',
        [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Playback model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     *
     * @return Response
     * @throws StaleObjectException
     * @throws NotFoundHttpException|Throwable if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

            $audioFilePath = Url::to(Yii::$app->params['tenantStorageFullPath'] . $GLOBALS['tenantID'] . '/playback/' . $model->pb_file);

        if (file_exists($audioFilePath)) {
            unlink($audioFilePath);
        }

        $model->delete();
        Yii::$app->session->setFlash('success', PlaybackModule::t('pb', 'deleted_success'));

        return $this->redirect(['index']);
    }

    /**
     * Finds the Playback model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     *
     * @return Playback the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Playback::findOne($id)) !== NULL) {
            return $model;
        }

        throw new NotFoundHttpException(PlaybackModule::t('pb', 'page_not_found'));
    }
}
