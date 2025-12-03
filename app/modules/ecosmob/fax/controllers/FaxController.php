<?php

namespace app\modules\ecosmob\fax\controllers;

use app\modules\ecosmob\fax\FaxModule;
use app\modules\ecosmob\fax\models\Fax;
use app\modules\ecosmob\fax\models\FaxSearch;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * FaxController implements the CRUD actions for Fax model.
 */
class FaxController extends Controller
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
     * Lists all Fax models.
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new FaxSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Fax model.
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
     * Finds the Fax model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Fax the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Fax::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(FaxModule::t('fax', 'page_not_exits'));
    }

    /*
     * Creates a new Fax model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Fax();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {

                //$model->fax_failure = Yii::$app->request->post('fax_failure');
                $model->save();
                Yii::$app->session->setFlash('success', FaxModule::t('fax', 'created_success'));
                return $this->redirect(['index']);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Fax model.
     * If update is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return Response|string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            //$model->fax_failure = Yii::$app->request->post('fax_failure');
            $model->save();
            Yii::$app->session->setFlash('success', FaxModule::t('fax', 'updated_success'));
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /*
     * Deletes an existing Fax model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', FaxModule::t('fax', 'deleted_success'));
        return $this->redirect(['index']);
    }
}
