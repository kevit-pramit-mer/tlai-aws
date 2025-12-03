<?php

namespace app\modules\ecosmob\speeddial\controllers;

use app\modules\ecosmob\speeddial\models\ExtensionSpeeddial;
use app\modules\ecosmob\speeddial\models\ExtensionSpeeddialSearch;
use app\modules\ecosmob\speeddial\SpeeddialModule;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * ExtensionSpeeddialController implements the CRUD actions for ExtensionSpeeddial model.
 */
class ExtensionSpeeddialController extends Controller
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
                            'speeddial',
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
     * Lists all ExtensionSpeeddial models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ExtensionSpeeddialSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);


        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,

        ]);
    }

    /**
     * Displays a single ExtensionSpeeddial model.
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
     * Finds the ExtensionSpeeddial model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ExtensionSpeeddial the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ExtensionSpeeddial::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    /**
     * Creates a new ExtensionSpeeddial model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ExtensionSpeeddial();


        if ($model->load(Yii::$app->request->post()) && $model->save()) {


            Yii::$app->session->setFlash('success', SpeeddialModule::t('app', 'created_success'));
            return $this->redirect(['index']);
        }
        return $this->render('create', [
            'model' => $model,
            //'all_attribute' => $all_attribute

        ]);
    }

    /**
     * Updates an existing ExtensionSpeeddial model.
     * If update is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */

    public function actionSpeeddial()
    {

        $extension_number = Yii::$app->user->identity->em_extension_number;
        $model = ExtensionSpeeddial::find()->where(['es_extension' => $extension_number])->one();

        if (!$model) {
            $model = new ExtensionSpeeddial();
            $model->es_extension = $extension_number;
            $model->save(false);
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if (Yii::$app->request->post('apply') == 'update') {
                Yii::$app->session->setFlash('success', SpeeddialModule::t('app', 'applied_success'));
                return $this->refresh();
            } else {
                Yii::$app->session->setFlash('success', SpeeddialModule::t('app', 'updated_success'));
                return $this->redirect(['index']);
            }
        }

        return $this->renderPartial('update', [
            'model' => $model,
        ]);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if (Yii::$app->request->post('apply') == 'update') {
                Yii::$app->session->setFlash('success', SpeeddialModule::t('app', 'applied_success'));
                return $this->refresh();
            } else {
                Yii::$app->session->setFlash('success', SpeeddialModule::t('app', 'updated_success'));
                return $this->redirect(['index']);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ExtensionSpeeddial model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', SpeeddialModule::t('app', 'deleted_success'));
        return $this->redirect(['index']);
    }
}
