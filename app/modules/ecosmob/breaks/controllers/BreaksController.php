<?php

namespace app\modules\ecosmob\breaks\controllers;

use app\modules\ecosmob\breaks\BreaksModule;
use app\modules\ecosmob\breaks\models\Breaks;
use app\modules\ecosmob\breaks\models\BreaksSearch;
use Throwable;
use Yii;
use yii\base\InvalidArgumentException;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * BreaksController implements the CRUD actions for Breaks model.
 */
class BreaksController extends Controller
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
     * Lists all Breaks models.
     *
     * @return string
     * @throws InvalidArgumentException
     */
    public function actionIndex()
    {
        $searchModel = new BreaksSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index',
            [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
    }

    /**
     * Creates a new Breaks model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     *
     * @return string|Response
     * @throws InvalidArgumentException
     */
    public function actionCreate()
    {
        $model = new Breaks();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', BreaksModule::t('breaks', 'created_success'));

            return $this->redirect(['index']);
        }

        return $this->render('create',
            [
                'model' => $model,
            ]);
    }

    /**
     * Updates an existing Breaks model.
     * If update is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     *
     * @return Response|string
     * @throws InvalidArgumentException
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', BreaksModule::t('breaks', 'updated_success'));
            return $this->redirect(['index']);
        }

        return $this->render('update',
            [
                'model' => $model,
            ]);
    }

    /**
     * Finds the Breaks model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     *
     * @return Breaks the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Breaks::findOne($id)) !== NULL) {
            return $model;
        }

        throw new NotFoundHttpException(BreaksModule::t('breaks', 'not_exists'));
    }

    /**
     * Deletes an existing Breaks model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     *
     * @return Response
     * @throws Throwable
     * @throws StaleObjectException
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', BreaksModule::t('breaks', 'deleted_success'));

        return $this->redirect(['index']);
    }
}
