<?php

namespace app\modules\ecosmob\leadgroup\controllers;

use app\modules\ecosmob\leadgroup\LeadgroupModule;
use app\modules\ecosmob\leadgroup\models\LeadgroupMaster;
use app\modules\ecosmob\leadgroup\models\LeadgroupSearch;
use app\modules\ecosmob\leadgroupmember\models\LeadGroupMember;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * LeadgroupController implements the CRUD actions for LeadgroupMaster model.
 */
class LeadgroupController extends Controller
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
     * Lists all LeadgroupMaster models.
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new LeadgroupSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single LeadgroupMaster model.
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
     * Finds the LeadgroupMaster model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return LeadgroupMaster the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = LeadgroupMaster::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException(LeadgroupModule::t('leadgroup', 'page_not_exits'));
    }

    /**
     * Creates a new LeadgroupMaster model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return string|Response
     */
    public function actionCreate()
    {
        $model = new LeadgroupMaster();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', LeadgroupModule::t('leadgroup', 'created_success'));
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing LeadgroupMaster model.
     * If update is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return Response|string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', LeadgroupModule::t('leadgroup', 'updated_success'));
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing LeadgroupMaster model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        //$lead_group = LeadGroupMember::find()->where(['ld_id' => $id])->count();
        LeadGroupMember::deleteAll(['ld_id' => $id]);
       /* if ($lead_group > 0) {
            Yii::$app->session->setFlash('success', LeadgroupModule::t('leadgroup', 'can_not_delete_lead_group_already_in_use'));
            return $this->redirect(['index']);
        } else {*/
            $this->findModel($id)->delete();
            Yii::$app->session->setFlash('success', LeadgroupModule::t('leadgroup', 'deleted_success'));
            return $this->redirect(['index']);
       /* }*/
    }
}
