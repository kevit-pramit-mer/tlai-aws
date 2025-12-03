<?php

namespace app\modules\ecosmob\dialplan\controllers;

use app\modules\ecosmob\dialplan\DialPlanModule;
use app\modules\ecosmob\dialplan\models\OutboundDialPlansDetails;
use app\modules\ecosmob\dialplan\models\OutboundDialPlansDetailsSearch;
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
 * OutbounddialplanController implements the CRUD actions for OutboundDialPlansDetails model.
 */
class OutbounddialplanController extends Controller
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
     * Lists all OutboundDialPlansDetails models.
     *
     * @return string
     * @throws InvalidArgumentException
     */
    public function actionIndex()
    {
        $searchModel = new OutboundDialPlansDetailsSearch();
        $dataProvider = $searchModel->searchAll(Yii::$app->request->queryParams);
        $dataProviderDefault = $searchModel->searchDefault(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'dataProviderDefault' => $dataProviderDefault,
        ]);
    }

    /**
     * Creates a new OutboundDialPlansDetails model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     *
     * @return string|Response
     * @throws InvalidArgumentException
     */
    public function actionCreate()
    {
        $model = new OutboundDialPlansDetails();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', DialPlanModule::t('dp', 'created_success'));
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing OutboundDialPlansDetails model.
     * If update is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     *
     * @return Response|string
     * @throws InvalidArgumentException|NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->getOldAttribute('odpd_prefix_match_string') == ".*") {
                $model->odpd_prefix_match_string = '.*';
            }

            $model->save(false);
            Yii::$app->session->setFlash('success', DialPlanModule::t('dp', 'updated_success'));
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Finds the OutboundDialPlansDetails model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return OutboundDialPlansDetails the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = OutboundDialPlansDetails::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(DialPlanModule::t('dp', 'page_not_found'));
    }

    /**
     * Deletes an existing OutboundDialPlansDetails model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     *
     * @return Response
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if ($model->odpd_prefix_match_string == ".*") {
            Yii::$app->session->setFlash('success', DialPlanModule::t('dp', 'deleted_error'));
        } else {
            $model->delete();
            Yii::$app->session->setFlash('success', DialPlanModule::t('dp', 'deleted_success'));
        }

        return $this->redirect(['index']);
    }
}
