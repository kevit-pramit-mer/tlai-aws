<?php

namespace app\modules\ecosmob\voicemsg\controllers;

use app\modules\ecosmob\voicemsg\models\VoicemailMsgs;
use app\modules\ecosmob\voicemsg\models\VoicemailMsgsSearch;
use app\modules\ecosmob\voicemsg\VoiceMsgModule;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use ZipArchive;

/**
 * VoicemailMsgsController implements the CRUD actions for VoicemailMsgs model.
 */
class VoicemailMsgsController extends Controller
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
                            //'bulk-delete',
                            'bulk-data',
                            'test',
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
     * Lists all VoicemailMsgs models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new VoicemailMsgsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->renderPartial('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single VoicemailMsgs model.
     * @param string $id
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
     * Finds the VoicemailMsgs model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return VoicemailMsgs the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = VoicemailMsgs::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    /**
     * Creates a new VoicemailMsgs model.
     * If creation is successful, the browser will be redirected to the 'list' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new VoicemailMsgs();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'Created Successfully'));
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing VoicemailMsgs model.
     * If update is successful, the browser will be redirected to the 'list' page.
     * @param string $id
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

    public function actionBulkData()
    {
        $selection = (array)Yii::$app->request->post('selection');
        $total_record = count($selection);

        if ($_POST['optype'] == 'delete') {
            if ($total_record > 0) {
                foreach ($selection as $ids) {

                    $model = VoicemailMsgs::find()->where(['uuid' => $ids])->one();

                    $model->delete();
                }
            }

            Yii::$app->session->setFlash('success', Yii::t('app', 'Deleted Successfully.'));
            return $this->redirect(['index']);
        } else {

            $selection = (array)Yii::$app->request->post('selection');
            $total_record = count($selection);

            $zipFilename = 'audiofiles_' . time() . '.zip';
            //zip file name with path
            $zip_folder = Yii::$app->basePath . DIRECTORY_SEPARATOR . 'web/media/admin/audio-download' . DIRECTORY_SEPARATOR;
            $zip_path = $zip_folder . $zipFilename;

            if (!is_dir($zip_folder)) {
//                FileHelper::createDirectory($zip_folder);
                mkdir($zip_folder, 0777, TRUE);
                chmod($zip_folder, 0777);
            }
            $zip = new ZipArchive;

            $zip->open($zip_path, ZipArchive::CREATE);

            if ($total_record > 0) {
                exec("chmod -R 0777 " . Yii::$app->params['VOICEMAIL_PATH'] . "/" . $GLOBALS['tenantID'] . "/");
                foreach ($selection as $ids) {

                    $model = VoicemailMsgs::find()->where(['uuid' => $ids])->one();

                    /* $files=$model->file_path;

                     exec("find ".Yii::$app->params['VOICEMAIL_PATH']."/".$GLOBALS['tenantID']."/"." -type f -exec chmod 0777 {} +");

                     exec("chmod -R 0777 ".Yii::$app->params['VOICEMAIL_PATH']."/".$GLOBALS['tenantID']."/");*/
                    $data = explode('/', $model->file_path);
                    $end = array_reverse($data)[0];
                    $audioFilePath = Url::to('@webroot' . Yii::$app->params['VOICEMAIL_PATH_WEB'] .$_SERVER['HTTP_HOST'].'/'. Yii::$app->user->identity->em_extension_number . "/");
                    $files = $audioFilePath . $end;

                    /*  $data=explode('/', $files);
                      $end=rand() . '_' . array_reverse($data)[0];*/

                    if (file_exists($files) && is_file($files)) {
                        exec("chmod -R 0777 " . Yii::$app->params['WEB_PATH'] . "" . $files);
                        $zip->addFile($files, $end);
                    } else {
                        Yii::$app->session->setFlash('error', Yii::t('app', 'no_file_exists'));
                        return $this->redirect(['index']);
                    }
                }
                $zip->close();

                header("Content-type: application/zip");
                header("Content-Disposition: attachment; filename=$zipFilename");
                readfile($zip_path);
            }
        }
    }

    /**
     * Deletes an existing VoicemailMsgs model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', VoiceMsgModule::t('voicemsg', 'deleted_success'));
        return $this->redirect(['index']);
    }
}
