<?php

namespace app\modules\ecosmob\disposition\controllers;

use app\modules\ecosmob\disposition\DispositionModule;
use app\modules\ecosmob\disposition\models\DispositionGroupStatusMapping;
use app\modules\ecosmob\disposition\models\DispositionMaster;
use app\modules\ecosmob\disposition\models\DispositionMasterSearch;
use app\modules\ecosmob\dispositionType\models\DispositionType;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * DispositionMasterController implements the CRUD actions for DispositionMaster model.
 */
class DispositionMasterController extends Controller
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
     * Lists all DispositionMaster models.
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new DispositionMasterSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DispositionMaster model.
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
     * Finds the DispositionMaster model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return DispositionMaster the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DispositionMaster::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(DispositionModule::t('disposition', 'page_not_exits'));
    }

    /**
     * Creates a new DispositionMaster model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return string|Response
     */
    public function actionCreate()
    {
        $model = new DispositionMaster();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->save()) {
                $dispositionContactedStatus = $model->ds_contacted_status;
                if(!empty($dispositionContactedStatus)) {
                    foreach ($dispositionContactedStatus as $statusDisposition) {
                        $mappingModel = new DispositionGroupStatusMapping();
                        $mappingModel->ds_status_id = $statusDisposition;
                        $mappingModel->ds_group_id = $model->ds_id;
                        $mappingModel->ds_category_id = 1;
                        $mappingModel->save();
                    }
                }
                $dispositionNoContactedStatus = $model->ds_non_contacted_status;
                if(!empty($dispositionNoContactedStatus)) {
                    foreach ($dispositionNoContactedStatus as $statusDisposition) {
                        $mappingModel = new DispositionGroupStatusMapping();
                        $mappingModel->ds_status_id = $statusDisposition;
                        $mappingModel->ds_group_id = $model->ds_id;
                        $mappingModel->ds_category_id = 2;
                        $mappingModel->save();
                    }
                }
            }
            Yii::$app->session->setFlash('success', DispositionModule::t('disposition', 'created_success'));
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
            'selectedContactedStatus' => [],
            'selectedNoContactedStatus' => []
        ]);
    }

    /**
     * Updates an existing DispositionMaster model.
     * If update is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return Response|string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $selectedContactedStatus = DispositionGroupStatusMapping::find()
            ->where(['ds_group_id' => $id])
            ->andWhere(['ds_category_id' => 1])
            ->all();
        $selectedNoContactedStatus = DispositionGroupStatusMapping::find()
            ->where(['ds_group_id' => $id])
            ->andWhere(['ds_category_id' => 2])
            ->all();

        $selectedContactedStatusArr = [];
        if(!empty($selectedContactedStatus)) {
            foreach ($selectedContactedStatus as $status) {
                $selectedContactedStatusArr[$status->ds_status_id] = array("selected" => true);
            }
        }
        $selectedNoContactedStatusArr = [];
        if(!empty($selectedNoContactedStatus)) {
            foreach ($selectedNoContactedStatus as $status) {
                $selectedNoContactedStatusArr[$status->ds_status_id] = array("selected" => true);
            }
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->save()) {
                DispositionGroupStatusMapping::deleteAll(['ds_group_id' => $model->getPrimaryKey()]);
                $dispositionContactedStatus = $model->ds_contacted_status;
                if(!empty($dispositionContactedStatus)) {
                    foreach ($dispositionContactedStatus as $statusDisposition) {
                        $mappingModel = new DispositionGroupStatusMapping();
                        $mappingModel->ds_status_id = $statusDisposition;
                        $mappingModel->ds_group_id = $model->ds_id;
                        $mappingModel->ds_category_id = 1;
                        $mappingModel->save();
                    }
                }
                $dispositionNoContactedStatus = $model->ds_non_contacted_status;
                if(!empty($dispositionNoContactedStatus)) {
                    foreach ($dispositionNoContactedStatus as $statusDisposition) {
                        $mappingModel = new DispositionGroupStatusMapping();
                        $mappingModel->ds_status_id = $statusDisposition;
                        $mappingModel->ds_group_id = $model->ds_id;
                        $mappingModel->ds_category_id = 2;
                        $mappingModel->save();
                    }
                }
            }
            Yii::$app->session->setFlash('success', DispositionModule::t('disposition', 'updated_success'));
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
            'selectedContactedStatus' => $selectedContactedStatusArr,
            'selectedNoContactedStatus' => $selectedNoContactedStatusArr
        ]);
    }

    /**
     * Deletes an existing DispositionMaster model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        DispositionGroupStatusMapping::deleteAll(['ds_group_id' => $id]);
        Yii::$app->session->setFlash('success', DispositionModule::t('disposition', 'deleted_success'));
        return $this->redirect(['index']);
    }
}
