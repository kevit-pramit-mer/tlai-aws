<?php

namespace app\modules\ecosmob\emailtemplate\controllers;

use Yii;
use app\modules\ecosmob\emailtemplate\models\EmailTemplate;
use app\modules\ecosmob\emailtemplate\models\EmailTemplateSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\modules\ecosmob\emailtemplate\EmailTemplateModule;

/**
 * EmailTemplateController implements the CRUD actions for EmailTemplate model.
 */
class EmailTemplateController extends Controller {
    
    /**
     * {@inheritdoc}
     */
    public function behaviors () {
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
                        ],
                        'allow'   => TRUE,
                        'roles'   => [ '@' ],
                    ],
                ],
            ],
            'verbs'  => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'delete' => [ 'POST' ],
                ],
            ],
        ];
    }
    
    /**
     * Lists all EmailTemplate models.
     *
     * @return mixed
     * @throws \yii\base\InvalidArgumentException
     */
    public function actionIndex () {
        $searchModel  = new EmailTemplateSearch();
        $dataProvider = $searchModel->search( Yii::$app->request->queryParams );
        
        return $this->render( 'index',
            [
                'searchModel'  => $searchModel,
                'dataProvider' => $dataProvider,
            ] );
    }
    
    /**
     * Creates a new EmailTemplate model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     *
     * @return mixed
     * @throws \yii\base\InvalidArgumentException
     */
    public function actionCreate () {
        $model = new EmailTemplate();
        
        if ( $model->load( Yii::$app->request->post() ) && $model->save() ) {
            Yii::$app->session->setFlash( 'success', EmailTemplateModule::t( 'emailtemplate', 'created_success' ) );
            
            return $this->redirect( [ 'index' ] );
        }
        
        return $this->render( 'create',
            [
                'model' => $model,
            ] );
    }
    
    /**
     * Updates an existing EmailTemplate model.
     * If update is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     *
     * @return mixed
     * @throws \yii\base\InvalidArgumentException
     * @throws \yii\web\NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate ( $id ) {
        $model = $this->findModel( $id );
        
        if ( $model->load( Yii::$app->request->post() ) && $model->validate() ) {
            $model->key = $model->getOldAttribute( 'key' );
            $model->save( FALSE );
            if ( Yii::$app->request->post( 'apply' ) == 'update' ) {
                Yii::$app->session->setFlash( 'success', EmailTemplateModule::t( 'emailtemplate', 'applied_success' ) );
                
                return $this->refresh();
            } else {
                Yii::$app->session->setFlash( 'success', EmailTemplateModule::t( 'emailtemplate', 'updated_success' ) );
                
                return $this->redirect( [ 'index' ] );
            }
        }
        
        return $this->render( 'update',
            [
                'model' => $model,
            ] );
    }
    
    /**
     * Finds the EmailTemplate model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     *
     * @return EmailTemplate the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel ( $id ) {
        if ( ( $model = EmailTemplate::findOne( $id ) ) !== NULL ) {
            return $model;
        }
        
        throw new NotFoundHttpException( 'page_not_exits' );
    }
}
