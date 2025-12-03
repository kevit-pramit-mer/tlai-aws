<?php

namespace app\modules\ecosmob\user\controllers;

use app\modules\ecosmob\user\models\User;
use app\modules\ecosmob\user\models\UserSearch;
use app\modules\ecosmob\user\UserModule;
use Yii;
use yii\db\Exception;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\rbac\DbManager;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
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
                            'trashed',
                            'delete-permanent',
                            'restore'
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
     * Lists all User models.
     * @return string
     * @throws Exception
     */
    public function actionIndex()
    {

        Yii::$app->session->set('user_redirect_to', Url::current());

        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $roles = Yii::$app->db->createCommand("SELECT name FROM auth_item WHERE type = 1  AND name NOT IN('super_admin', 'agent', 'supervisor', 'tenant_admin')")->queryAll();
        $roles = ArrayHelper::map($roles, 'name', 'name');

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'roles' => $roles,
        ]);
    }

    /**
     * Displays a single User model.
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
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return Response|string
     * @throws Exception
     */
    public function actionCreate()
    {
        $model = new User();
        $model->scenario = 'create';

        $roles = Yii::$app->db->createCommand("SELECT name FROM auth_item WHERE type = 1 AND name NOT IN('super_admin', 'agent', 'supervisor', 'tenant_admin')")->queryAll();
        $roles = ArrayHelper::map($roles, 'name', 'name');

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->adm_password_hash = base64_encode($model->adm_password);
            $model->adm_password = md5($model->adm_password);

            if ($model->save(false)) {
                /** @var $role */
                $role = (object)['name' => $model->adm_is_admin];
                $role_id = $model->adm_id;

                /** @var DbManager $assignment */
                $assignment = new DbManager();
                $assignment->assign($role, $role_id);

                Yii::$app->session->setFlash('success', UserModule::t('usr', 'created_success'));
                return $this->redirect(['index']);
            }
        }

        return $this->render('create', [
            'model' => $model,
            'roles' => $roles,
        ]);
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return Response|string
     * @throws NotFoundHttpException|Exception if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->scenario = 'update';
        $model->adm_password = "";

        $role_old = (object)['name' => $model->adm_is_admin];
        $roles = Yii::$app->db->createCommand("SELECT name FROM auth_item WHERE type = 1 AND name NOT IN('super_admin', 'agent', 'supervisor', 'tenant_admin')")->queryAll();
        $roles = ArrayHelper::map($roles, 'name', 'name');


        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->adm_password_hash = base64_encode($model->adm_password);
            $model->adm_password = md5($model->adm_password);

            if ($model->adm_is_admin != $role_old) {
                /** @var $role */
                $role = (object)['name' => $model->adm_is_admin];
                $role_id = $model->adm_id;

                /** @var DbManager $assignment */
                $assignment = new DbManager();
                $assignment->revoke($role_old, $role_id);
                $assignment->assign($role, $role_id);
            }
            if ($model->save(false)) {
                Yii::$app->session->setFlash('success', UserModule::t('usr', 'updated_success'));
                return $this->redirect(['index']);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'roles' => $roles,
        ]);
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return Response|void
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->adm_status = '2';
        /** @var $role */
        $role = (object)['name' => $model->adm_is_admin];
        $role_id = $model->adm_id;

        /** @var DbManager $assignment */
        $assignment = new DbManager();
        $assignment->revoke($role, $role_id);
        if ($model->save()) {
            Yii::$app->session->setFlash('success', UserModule::t('usr', 'moved_success'));
            return $this->redirect(['trashed']);
        }
    }

    public function actionTrashed()
    {
        Yii::$app->session->set('user_redirect_to', Url::current());

        $searchModel = new UserSearch();
        $dataProvider = $searchModel->trashedsearch(
            Yii::$app->request->queryParams
        );
        $roles = Yii::$app->db->createCommand("SELECT name FROM auth_item WHERE type = 1")->queryAll();
        $roles = ArrayHelper::map($roles, 'name', 'name');


        return $this->render(
            'trashed',
            [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'roles' => $roles,
            ]
        );

    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDeletePermanent($id)
    {
        $model = $this->findModel($id);
        $model->delete();

        /** @var $role */
        $role = (object)['name' => $model->adm_is_admin];
        $role_id = $model->adm_id;

        /** @var DbManager $assignment */
        $assignment = new DbManager();
        $assignment->revoke($role, $role_id);

        Yii::$app->session->setFlash('success', UserModule::t('usr', 'delete_success'));
        return $this->redirect(['index']);
    }

    /**
     * @param $id
     *
     * @return Response
     * @throws StaleObjectException
     */
    public function actionRestore($id)
    {
        $model = $this->findModel($id);
        $model->adm_status = '1';
        $model->update(FALSE);

        /** @var $role */
        $role = (object)['name' => $model->adm_is_admin];
        $role_id = $model->adm_id;

        /** @var DbManager $assignment */
        $assignment = new DbManager();
        $assignment->assign($role, $role_id);

        Yii::$app->session->setFlash('success', UserModule::t('usr', 'restored_success'));

        return $this->redirect(['index']);
    }
}
