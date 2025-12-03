<?php

namespace app\controllers;

use app\models\PageAccess;
use app\models\TenantModuleConfig;
use dosamigos\arrayquery\ArrayQuery;
use Yii;
use yii\base\InvalidArgumentException;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\db\Exception;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\rbac\Item;
use yii\web\NotFoundHttpException;
use yii2mod\rbac\models\AuthItemModel;
use yii2mod\rbac\models\search\AuthItemSearch;

/**
 * Class RoleController
 * @package app\controllers
 */
class RoleController extends \yii2mod\rbac\controllers\RoleController
{


    /**
     * {@inheritdoc}
     */
    public function behaviors(): array
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
                            'role',
                            'assign-access',
                            'delete'
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

    public function actionIndex()
    {
        $searchModel = new AuthItemSearch();
       /* $searchModel->type = $this->type;
        $searchModel->pageSize = Yii::$app->layoutHelper->get_per_page_record_count();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);*/

        $authManager = Yii::$app->getAuthManager();

        if ($this->type == Item::TYPE_ROLE) {
            $items = $authManager->getRoles();
        } else {
            $items = array_filter($authManager->getPermissions(), function ($item) {
                return strpos($item->name, '/') !== 0;
            });
        }

        $query = new ArrayQuery($items);

        $query->addCondition('name', "<>super_admin");
        $query->addCondition('name', "<>supervisor");
        $query->addCondition('name', "<>agent");
        if(Yii::$app->user->identity->adm_is_admin != 'super_admin' && Yii::$app->user->identity->adm_is_admin != 'supervisor' && Yii::$app->user->identity->adm_is_admin != 'agent') {
            $query->addCondition('name', "<>" . Yii::$app->user->identity->adm_is_admin);
        }
        $searchModel->load(Yii::$app->request->queryParams);

        $params = Yii::$app->request->queryParams;


        if ($searchModel->validate() && isset($params['AuthItemSearch'])) {
            $query->addCondition('name', $params['AuthItemSearch']['name'] ? "~{$params['AuthItemSearch']['name']}" : null)
                ->addCondition('description', $params['AuthItemSearch']['description'] ? "~{$params['AuthItemSearch']['description']}" : null);
        }

        $dataProvider =  new ArrayDataProvider([
            'allModels' => $query->find(),
            'sort' => [
                'attributes' => ['name'],
            ],
            'pagination' => [
                'pageSize' => Yii::$app->layoutHelper->get_per_page_record_count()
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Creates a new AuthItem model.
     *
     * If creation is successful, the browser will be redirected to the 'index' page.
     *
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function actionCreate()
    {
        /** @var AuthItemModel $model */
        $model = new AuthItemModel();
        $model->type = $this->type;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', Yii::t('app', 'item_has_been_saved'));

            return $this->redirect(['index']);
        }

        return $this->render('create', ['model' => $model]);
    }

    /**
     * Creates a new AuthItem model.
     *
     * If assign is successful, the browser will be redirected to the 'index' page.
     *
     * @param $id
     *
     * @return mixed
     * @throws Exception
     * @throws NotFoundHttpException
     * @throws InvalidArgumentException
     */
    public function actionAssignAccess($id)
    {
        if ($id == 'super_admin' || $id == 'agent' || $id == 'supervisor') {
            Yii::$app->session->setFlash('error', Yii::t('app', 'sry_you_can_not_assign_permission'));
            return $this->redirect(['index']);
        }


        $model = $this->findModel($id);


       /* $tenantModuleConfig = TenantModuleConfig::find()
            ->leftJoin('ct_tenant_info', 'ct_tenant_info.tenant_uuid = tenant_module_config.tenant_id')
            ->andWhere(['tenant_module_config.module_slug_name' => 'Allow_DID_Trunk_Routing'])
            ->andWhere(['tenant_module_config.status' => 1])
            ->one();
        if (!empty($tenantModuleConfig)) {*/
            $dataProvider = new ActiveDataProvider([
                'query' => PageAccess::find()->where(['NOT IN', 'page_desc', ['Plans', 'Playbacks', 'Prompt Lists', 'DB Backup', 'Queue Call Back', 'CDR Reports', 'Jobs', 'Time Conditions', 'Agent', 'Access Restriction', 'Fail2ban ', 'IP Table', 'Log Viewer']]),
                'pagination' => false,
                'sort' => ['defaultOrder' => ['priority' => SORT_ASC]],
            ]);
        /*}else {
            $dataProvider = new ActiveDataProvider([
                'query' => PageAccess::find()->where(['NOT IN', 'page_desc', ['Plans', 'Playbacks', 'Prompt Lists', 'DB Backup', 'Queue Call Back', 'CDR Reports', 'Jobs', 'Time Conditions', 'Agent', 'Trunks', 'Trunk Groups', 'Outbound Dial Plans', 'Access Restriction', 'Fail2ban ', 'IP Table', 'Log Viewer']]),
                'pagination' => false,
                'sort' => ['defaultOrder' => ['priority' => SORT_ASC]],
            ]);
        }*/
        if (Yii::$app->request->isAjax) {


            $post_data = Yii::$app->getRequest()->post('data');


            $model_item_child = $this->findModel($id);

            if(!empty($_POST)) {
                if (!empty($post_data)):

                    Yii::$app->db->createCommand()->delete('auth_item_child', 'parent = "' . $id . '"')->execute();

                    foreach ($post_data as $data) {
                        $actions = Yii::$app->commonHelper->assignAdditionalPermission($data);
                        // print_r($actions);exit;
                        foreach ($actions as $action) {
                            $model_item_child->addChildren(array($action));
                        }
                        /* if($data[2] == 'realtimedashboard/real-time-dashboard'){
                             $data[1] = 'real-time-dashboard';
                         }*/
                        $item = array("/" . $data[2] . "/" . $data[1]);

                        $model_item_child->addChildren($item);
                    }
                    Yii::$app->session->setFlash('success', Yii::t('app', 'role_permission_assign_successfully'));
                    return $this->redirect(['index']);

                elseif ($_POST['isAssign'] == 'N'):
                        Yii::$app->db->createCommand()->delete('auth_item_child', 'parent = "' . $id . '"')->execute();
                        Yii::$app->session->setFlash('success', Yii::t('app', 'role_permission_assign_successfully'));
                        return $this->redirect(['index']);
                endif;
            }

        }

        return $this->render('assign', ['model' => $model, 'page_model' => $dataProvider]);
    }

    /**
     * Updates an existing AuthItem model.
     *
     * If update is successful, the browser will be redirected to the 'index' page.
     *
     * @param string $id
     *
     * @return mixed
     * @throws InvalidArgumentException
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        if ($id == 'super_admin' || $id == 'agent' || $id == 'supervisor' || $id == 'tenant_admin') {
            Yii::$app->session->setFlash('error', Yii::t('app', 'sry_you_can_not_update_role'));
            return $this->redirect(['index']);
        }
        $model = $this->findModel($id);
        $name = $model->name;
        if ($model->load(Yii::$app->request->post())) {
            $model->name = $name;
            $model->save();
            Yii::$app->session->setFlash('success', Yii::t('app', 'updated_success'));
            return $this->redirect(['index']);
        }

        return $this->render('update', ['model' => $model]);
    }
}
