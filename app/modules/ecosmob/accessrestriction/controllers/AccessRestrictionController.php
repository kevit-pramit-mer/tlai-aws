<?php

namespace app\modules\ecosmob\accessrestriction\controllers;

use app\modules\ecosmob\accessrestriction\AccessRestrictionModule;
use app\modules\ecosmob\accessrestriction\models\AccessRestriction;
use app\modules\ecosmob\accessrestriction\models\AccessRestrictionSearch;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * AccessRestrictionController implements the CRUD actions for AccessRestriction model.
 */
class AccessRestrictionController extends Controller
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
     * Lists all AccessRestriction models.
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new AccessRestrictionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single AccessRestriction model.
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
     * Finds the AccessRestriction model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return AccessRestriction the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AccessRestriction::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Creates a new AccessRestriction model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return string|Response
     */
    public function actionCreate()
    {
        $model = new AccessRestriction();
        $model->ar_status = '1';

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            AccessRestriction::callRabbitmq();
            Yii::$app->session->setFlash('success', AccessRestrictionModule::t('accessrestriction', 'created_success'));
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing AccessRestriction model.
     * If update is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return Response|string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            AccessRestriction::callRabbitmq();
            if (Yii::$app->request->post('apply') == 'update') {
                Yii::$app->session->setFlash('success', AccessRestrictionModule::t('accessrestriction', 'applied_success'));
                return $this->refresh();
            } else {
                Yii::$app->session->setFlash('success', AccessRestrictionModule::t('accessrestriction', 'updated_success'));
                return $this->redirect(['index']);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing AccessRestriction model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        AccessRestriction::callRabbitmq();
        Yii::$app->session->setFlash('success', AccessRestrictionModule::t('accessrestriction', 'deleted_success'));
        return $this->redirect(['index']);
    }
}
