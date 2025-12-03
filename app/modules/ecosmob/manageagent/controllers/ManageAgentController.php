<?php

namespace app\modules\ecosmob\manageagent\controllers;

use app\modules\ecosmob\campaign\models\Campaign;
use app\modules\ecosmob\customerdetails\models\CampaignMappingUser;
use app\modules\ecosmob\manageagent\ManageAgentModule;
use app\modules\ecosmob\manageagent\models\ManageAgent;
use app\modules\ecosmob\manageagent\models\ManageAgentSearch;
use Yii;
use yii\db\Expression;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * ManageAgentController implements the CRUD actions for ManageAgent model.
 */
class ManageAgentController extends Controller
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
     * Lists all ManageAgent models.
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ManageAgentSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $campaignIds = CampaignMappingUser::find()->select('campaign_id')->where(['supervisor_id' => Yii::$app->user->identity->adm_id])->asArray()->all();
        $ids = implode(",", array_map(function ($a) {
            return implode("~", $a);
        }, $campaignIds));
        $data = Campaign::find()->select(['cmp_id', 'cmp_name'])
            ->andWhere(new Expression('FIND_IN_SET(cmp_id,"' . $ids . '")'))
            ->asArray()->all();
        $campaignList = ArrayHelper::map($data, 'cmp_id', 'cmp_name');

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'campaignList' => $campaignList,
        ]);
    }

    /**
     * Displays a single ManageAgent model.
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
     * Finds the ManageAgent model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ManageAgent the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ManageAgent::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(ManageAgentModule::t('manageagent', 'page_not_exits'));
    }

    /**
     * Creates a new ManageAgent model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return string|Response
     */
    public function actionCreate()
    {
        $model = new ManageAgent();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', ManageAgentModule::t('manageagent', 'created_success'));
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ManageAgent model.
     * If update is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if (Yii::$app->request->post('apply') == 'update') {
                Yii::$app->session->setFlash('success', ManageAgentModule::t('manageagent', 'applied_success'));
                return $this->refresh();
            } else {
                Yii::$app->session->setFlash('success', ManageAgentModule::t('manageagent', 'updated_success'));
                return $this->redirect(['index']);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ManageAgent model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', ManageAgentModule::t('manageagent', 'deleted_success'));
        return $this->redirect(['index']);
    }
}
