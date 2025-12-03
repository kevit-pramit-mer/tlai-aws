<?php

namespace app\modules\ecosmob\fail2ban\controllers;

use app\components\Helper;
use app\modules\ecosmob\customer\models\Customer;
use app\modules\ecosmob\fail2ban\models\Cdr;
use app\modules\ecosmob\fail2ban\models\CdrSearch;
use app\modules\ecosmob\serviceprovider\models\ServiceProvider;
use Throwable;
use Yii;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * Class IptableController
 *
 * @package app\modules\ecosmob\fail2ban\controllers
 */
class IptablesController extends Controller
{

    /**
     * @return array
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
     * @return string
     * @throws \yii\base\InvalidParamException
     */
    public function actionIndex()
    {
        $searchModel = new CdrSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        Yii::$app->session->set('cdrquery', $dataProvider->query);


        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Deletes an existing Fail2ban model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     *
     * @return Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->remove = 1;
        $model->save(false);

        Yii::$app->session->setFlash('success', Yii::t('app', 'deleted_success'));
        return $this->redirect(['index']);
    }


    /**
     * Finds the Cdr model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     *
     * @return Cdr the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Cdr::findOne($id)) !== NULL) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
