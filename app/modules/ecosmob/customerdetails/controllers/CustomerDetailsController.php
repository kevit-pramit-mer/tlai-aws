<?php

namespace app\modules\ecosmob\customerdetails\controllers;

use app\modules\ecosmob\campaign\models\Campaign;
use app\modules\ecosmob\customerdetails\CustomerDetailsModule;
use app\modules\ecosmob\customerdetails\models\CampaignMappingUser;
use app\modules\ecosmob\customerdetails\models\CampaignMappingUserSearch;
use app\modules\ecosmob\extension\models\Extension;
use Yii;
use yii\db\Expression;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * CampaignMappingUserController implements the CRUD actions for CampaignMappingUser model.
 */
class CustomerDetailsController extends Controller
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
     * Lists all CampaignMappingUser models.
     * @return string
     */
    public function actionIndex()
    {

        $searchModel = new CampaignMappingUserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $campaignIds = CampaignMappingUser::find()->select('campaign_id')->where(['supervisor_id' => Yii::$app->user->identity->adm_id])->asArray()->all();
        $ids = implode(",", array_map(function ($a) {
            return implode("~", $a);
        }, $campaignIds));
        $data = Campaign::find()->select(['cmp_id', 'cmp_name'])
            ->andWhere(new Expression('FIND_IN_SET(cmp_id,"' . $ids . '")'))
            ->andWhere(['cmp_status' => 'Active'])
            ->asArray()->all();
        $campaignList = ArrayHelper::map($data, 'cmp_id', 'cmp_name');
        $extensionNumber = $_SESSION['extentationNumber'];

        $extensionInformation = Extension::find()->select(['em_extension_number', 'em_password'])->where(['em_extension_number' => $extensionNumber])->one();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'campaignList' => $campaignList,
            'extensionInformation' => $extensionInformation
        ]);
    }

    /**
     * Displays a single CampaignMappingUser model.
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
     * Finds the CampaignMappingUser model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CampaignMappingUser the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CampaignMappingUser::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(CustomerDetailsModule::t('customerdetails', 'page_not_exits'));
    }

    /**
     * Creates a new CampaignMappingUser model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return string|Response
     */
    public function actionCreate()
    {
        $model = new CampaignMappingUser();

        if ($model->load(Yii::$app->request->post())) {
            $model->save();
            Yii::$app->session->setFlash('success', CustomerDetailsModule::t('customerdetails', 'created_success'));
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing CampaignMappingUser model.
     * If update is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return Response|string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if (Yii::$app->request->post('apply') == 'update') {
                Yii::$app->session->setFlash('success', CustomerDetailsModule::t('customerdetails', 'applied_success'));
                return $this->refresh();
            } else {
                Yii::$app->session->setFlash('success', CustomerDetailsModule::t('customerdetails', 'updated_success'));
                return $this->redirect(['index']);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing CampaignMappingUser model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', CustomerDetailsModule::t('customerdetails', 'deleted_success'));
        return $this->redirect(['index']);
    }
}
