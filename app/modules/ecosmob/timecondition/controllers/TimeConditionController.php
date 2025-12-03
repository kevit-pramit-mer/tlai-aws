<?php

namespace app\modules\ecosmob\timecondition\controllers;

use app\modules\ecosmob\timecondition\models\TimeCondition;
use app\modules\ecosmob\timecondition\models\TimeConditionSearch;
use app\modules\ecosmob\timecondition\TimeConditionModule;
use Yii;
use yii\base\InvalidArgumentException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class TimeConditionController extends Controller
{
    /**
     * @inheritdoc
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
     * @return string
     * @throws InvalidArgumentException
     */
    public function actionIndex()
    {
        $searchModel = new TimeConditionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @return string|Response
     * @throws InvalidArgumentException
     */
    public function actionCreate()
    {
        $model = new TimeCondition();

        if ($model->load(Yii::$app->request->post())) {
            $model->created_date = date('Y-m-d H:i:s');
            $model->updated_date = date('Y-m-d H:i:s');

            if ($model->validate()) {
                if ($model->save()) {
                    Yii::$app->session->setFlash('success',
                        TimeConditionModule::t('tc', 'created_success')
                    );
                    return $this->redirect(['index']);
                }
            }
        }

        return $this->render('create', ['model' => $model]);
    }


    /**
     * @param $id
     *
     * @return string|Response
     * @throws NotFoundHttpException
     * @throws InvalidArgumentException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $model->updated_date = date('Y-m-d H:i:s');
            if ($model->validate()) {
                if ($model->save()) {
                    if (Yii::$app->request->post('apply') == 'update') {
                        Yii::$app->session->setFlash('success', TimeConditionModule::t('tc', 'applied_success'));
                        return $this->refresh();
                    } else {
                        Yii::$app->session->setFlash('success', TimeConditionModule::t('tc', 'updated_success'));
                        return $this->redirect(['index']);
                    }
                }
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * @param $id
     * @return TimeCondition|null
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = TimeCondition::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(TimeConditionModule::t('tc', 'page_not_exits'));
    }

    /**
     * @param $id
     * @return Response
     * @throws NotFoundHttpException
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', TimeConditionModule::t('tc', 'deleted_success'));
        return $this->redirect(['index']);
    }
}