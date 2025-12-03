<?php

namespace app\modules\ecosmob\holiday\controllers;

use app\modules\ecosmob\holiday\HolidayModule;
use app\modules\ecosmob\holiday\models\Holiday;
use app\modules\ecosmob\holiday\models\HolidaySearch;
use Yii;
use yii\base\InvalidArgumentException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * HolidayController implements the CRUD actions for Holiday model.
 */
class HolidayController extends Controller
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
     * Lists all Holiday models.
     *
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function actionIndex()
    {
        $searchModel = new HolidaySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Holiday model.
     *
     * @param integer $id
     *
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     * @throws InvalidArgumentException
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Finds the Holiday model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Holiday the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Holiday::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    /**
     * Creates a new Holiday model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     *
     * @return Response|string
     * @throws InvalidArgumentException
     */
    public function actionCreate()
    {
        $model = new Holiday();

        if ($model->load(Yii::$app->request->post())) {
            $model->updated_date = date('Y-m-d H:i:s');
            $model->created_date = date('Y-m-d H:i:s');
            $model->hd_end_date = gmdate('Y-m-d', strtotime($model->hd_end_date));
            $model->hd_date = gmdate('Y-m-d', strtotime($model->hd_date));
            if ($model->validate()) {
                if ($model->save()) {
                    Yii::$app->session->setFlash('success', HolidayModule::t('hd', 'created_success'));
                    return $this->redirect(['index']);
                }
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Holiday model.
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

        if ($model->load(Yii::$app->request->post())) {
            $model->updated_date = date('Y-m-d H:i:s');
            $model->hd_end_date = gmdate('Y-m-d', strtotime($model->hd_end_date));
            $model->hd_date = gmdate('Y-m-d', strtotime($model->hd_date));

            if ($model->validate()) {
                if ($model->save()) {
                    Yii::$app->session->setFlash('success', HolidayModule::t('hd', 'updated_success'));
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
        Yii::$app->session->setFlash('success', HolidayModule::t('hd', 'deleted_success'));
        return $this->redirect(['index']);
    }
}
