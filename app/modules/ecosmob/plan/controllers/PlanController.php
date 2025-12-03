<?php

namespace app\modules\ecosmob\plan\controllers;

use app\modules\ecosmob\plan\models\Plan;
use app\modules\ecosmob\plan\models\PlanSearch;
use app\modules\ecosmob\plan\PlanModule;
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
 * PlanController implements the CRUD actions for Plan model.
 */
class PlanController extends Controller
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
     * Lists all Plan models.
     *
     * @return string
     * @throws InvalidArgumentException
     */
    public function actionIndex()
    {
        $searchModel = new PlanSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Plan model.
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
     * Finds the Plan model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Plan the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Plan::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(PlanModule::t('pl', 'page_not_exits'));
    }

    /**
     * Creates a new Plan model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     *
     * @return Response|string
     * @throws InvalidArgumentException
     */
    public function actionCreate()
    {
        $model = new Plan();

        if ($model->load(Yii::$app->request->post())) {

            $model->pl_holiday = Yii::$app->request->post('pl_holiday');
            $model->pl_week_off = Yii::$app->request->post('pl_week_off');
            $model->pl_bargain = Yii::$app->request->post('pl_bargain');
            $model->pl_dnd = Yii::$app->request->post('pl_dnd');
            $model->pl_park = Yii::$app->request->post('pl_park');
            $model->pl_transfer = Yii::$app->request->post('pl_transfer');
            $model->pl_call_record = Yii::$app->request->post('pl_call_record');
            $model->pl_white_list = Yii::$app->request->post('pl_white_list');
            $model->pl_black_list = Yii::$app->request->post('pl_black_list');
            $model->pl_caller_id_block = Yii::$app->request->post('pl_caller_id_block');
            $model->pl_universal_forward = Yii::$app->request->post('pl_universal_forward');
            $model->pl_no_ans_forward = Yii::$app->request->post('pl_no_ans_forward');
            $model->pl_busy_forward = Yii::$app->request->post('pl_busy_forward');
            $model->pl_timebase_forward = Yii::$app->request->post('pl_timebase_forward');
            $model->pl_selective_forward = Yii::$app->request->post('pl_selective_forward');
            $model->pl_shift_forward = Yii::$app->request->post('pl_shift_forward');

            $model->pl_unavailable_forward = Yii::$app->request->post('pl_unavailable_forward');
            $model->pl_redial = Yii::$app->request->post('pl_redial');
            $model->pl_call_return = Yii::$app->request->post('pl_call_return');
            $model->pl_busy_callback = Yii::$app->request->post('pl_busy_callback');

            $model->created_date = date('Y-m-d H:i:s');
            $model->updated_date = date('Y-m-d H:i:s');

            if ($model->validate()) {
                if ($model->save()) {
                    Yii::$app->session->setFlash('success', PlanModule::t('pl', 'created_success'));
                    return $this->redirect(['index']);
                }
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Plan model.
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

            $model->pl_holiday = Yii::$app->request->post('pl_holiday');
            $model->pl_week_off = Yii::$app->request->post('pl_week_off');
            $model->pl_bargain = Yii::$app->request->post('pl_bargain');
            $model->pl_dnd = Yii::$app->request->post('pl_dnd');
            $model->pl_park = Yii::$app->request->post('pl_park');
            $model->pl_transfer = Yii::$app->request->post('pl_transfer');
            $model->pl_call_record = Yii::$app->request->post('pl_call_record');
            $model->pl_white_list = Yii::$app->request->post('pl_white_list');
            $model->pl_black_list = Yii::$app->request->post('pl_black_list');
            $model->pl_caller_id_block = Yii::$app->request->post('pl_caller_id_block');
            $model->pl_universal_forward = Yii::$app->request->post('pl_universal_forward');
            $model->pl_no_ans_forward = Yii::$app->request->post('pl_no_ans_forward');
            $model->pl_busy_forward = Yii::$app->request->post('pl_busy_forward');
            $model->pl_timebase_forward = Yii::$app->request->post('pl_timebase_forward');
            $model->pl_selective_forward = Yii::$app->request->post('pl_selective_forward');
            $model->pl_shift_forward = Yii::$app->request->post('pl_shift_forward');
            $model->pl_unavailable_forward = Yii::$app->request->post('pl_unavailable_forward');
            $model->pl_redial = Yii::$app->request->post('pl_redial');
            $model->pl_call_return = Yii::$app->request->post('pl_call_return');
            $model->pl_busy_callback = Yii::$app->request->post('pl_busy_callback');

            $model->updated_date = date('Y-m-d H:i:s');

            if ($model->validate()) {
                if ($model->save()) {
                    if (Yii::$app->request->post('apply') == 'update') {
                        Yii::$app->session->setFlash('success', PlanModule::t('pl', 'applied_success'));
                        return $this->refresh();
                    } else {
                        Yii::$app->session->setFlash('success', PlanModule::t('pl', 'updated_success'));
                        return $this->redirect(['index']);
                    }
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
     * @throws StaleObjectException
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', PlanModule::t('pl', 'deleted_success'));
        return $this->redirect(['index']);
    }
}
