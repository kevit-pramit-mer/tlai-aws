<?php

namespace app\modules\ecosmob\weekoff\controllers;

use app\modules\ecosmob\weekoff\models\WeekOff;
use app\modules\ecosmob\weekoff\models\WeekOffSearch;
use app\modules\ecosmob\weekoff\WeekOffModule;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * WeekOffController implements the CRUD actions for WeekOff model.
 */
class WeekOffController extends Controller
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
     * Lists all WeekOff models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new WeekOffSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single WeekOff model.
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
     * Finds the WeekOff model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return WeekOff the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = WeekOff::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(WeekOffModule::t('wo', 'page_not_exits'));
    }

    /**
     * Creates a new WeekOff model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return Response|string
     */
    public function actionCreate()
    {
        $model = new WeekOff();

        if ($model->load(Yii::$app->request->post())) {
            $model->updated_date = date('Y-m-d H:i:s');
            $model->created_date = date('Y-m-d H:i:s');
            if ($model->validate()) {
                if ($model->save()) {
                    Yii::$app->session->setFlash('success', WeekOffModule::t('wo', 'created_success'));
                    return $this->redirect(['index']);
                }
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing WeekOff model.
     * If update is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return Response|string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $model->updated_date = date('Y-m-d H:i:s');

            if ($model->validate()) {
                if ($model->save()) {
                    Yii::$app->session->setFlash('success', WeekOffModule::t('wo', 'updated_success'));
                    return $this->redirect(['index']);
                }
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * @param $id
     * @return Response
     * @throws NotFoundHttpException
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', WeekOffModule::t('wo', 'deleted_success'));
        return $this->redirect(['index']);
    }
}
