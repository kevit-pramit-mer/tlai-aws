<?php

namespace app\modules\ecosmob\extensionsettings\controllers;

use Yii;
use app\modules\ecosmob\extensionsettings\models\ExtensionCallSetting;
use app\modules\ecosmob\extensionsettings\models\ExtensionCallSettingSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\modules\ecosmob\extensionsettings\ExtensionSettingsModule;

/**
 * ExtensionCallSettingController implements the CRUD actions for ExtensionCallSetting model.
 */
class ExtensionCallSettingController extends Controller
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
     * Lists all ExtensionCallSetting models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ExtensionCallSettingSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ExtensionCallSetting model.
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
     * Creates a new ExtensionCallSetting model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ExtensionCallSetting();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success',ExtensionSettingsModule::t('extensionsettings','created_success'));
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ExtensionCallSetting model.
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
                Yii::$app->session->setFlash('success', ExtensionSettingsModule::t('extensionsettings', 'applied_success'));
                return $this->refresh();
            } else {
                Yii::$app->session->setFlash('success', ExtensionSettingsModule::t('extensionsettings', 'updated_success'));
                return $this->redirect(['index']);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ExtensionCallSetting model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', ExtensionSettingsModule::t('extensionsettings', 'deleted_success'));
        return $this->redirect(['index']);
    }

    /**
     * Finds the ExtensionCallSetting model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ExtensionCallSetting the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ExtensionCallSetting::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(ExtensionSettingsModule::t('extensionsettings', 'page_not_exits'));
    }
}
