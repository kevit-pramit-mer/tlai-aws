<?php

namespace app\modules\ecosmob\conference\controllers;

use app\modules\ecosmob\conference\ConferenceModule;
use app\modules\ecosmob\conference\models\ConferenceControls;
use app\modules\ecosmob\conference\models\ConferenceMaster;
use app\modules\ecosmob\conference\models\ConferenceMasterSearch;
use app\modules\ecosmob\globalconfig\models\GlobalConfig;
use Yii;
use yii\base\Model;
use yii\db\Exception;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * ConferenceController implements the CRUD actions for ConferenceMaster model.
 */
class ConferenceController extends Controller
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
                            'configuration',
                            'get-file-path',
                        ],
                        'allow' => TRUE,
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
     * Lists all ConferenceMaster models.
     *
     * @return string
     */
    public function actionIndex()
    {
        Yii::$app->session->set('conf_redirect_to', Yii::$app->request->hostInfo . Url::current());
        $searchModel = new ConferenceMasterSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index',
            [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
    }

    /**
     * Creates a new ConferenceMaster model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     *
     * @return string|Response
     */
    public function actionCreate()
    {

        $model = new ConferenceMaster();
        $model->setScenario('create');

        if ($model->load(Yii::$app->request->post())) {
            if ($model->cm_moh == 'NULL' || $model->cm_moh == '') {
                $globalConfig = GlobalConfig::find()->where(['gwc_key' => 'moh_file'])->one();
                if (!empty($globalConfig)) {
                    $model->cm_moh = $globalConfig->gwc_value;

                } else {
                    $model->cm_moh = NULL;
                }
            }

            $model->cm_status = 1;
            //$model->cm_quick_start = '0';

            $model->cm_quick_start = Yii::$app->request->post('cm_quick_start');
            $model->cm_entry_tone = Yii::$app->request->post('cm_entry_tone');
            $model->cm_exit_tone = Yii::$app->request->post('cm_exit_tone');

            if ($model->validate()) {
                $model->cm_name = $model->cm_name.'_'.$_SERVER['HTTP_HOST'];
                $model->save(FALSE);
                $conferenceControlModel = ConferenceControls::findAll(['cm_id' => 0]);

                foreach ($conferenceControlModel as $value) {

                    $saveConferenceControls = new ConferenceControls();
                    $saveConferenceControls->cc_conf_group = $model->cm_id . '-conference';
                    $saveConferenceControls->cc_action = $value->cc_action;
                    $saveConferenceControls->cc_digits = $value->cc_digits;
                    $saveConferenceControls->cm_id = $model->cm_id;

                    $saveConferenceControls->save();

                    unset($saveConferenceControls);
                }
                Yii::$app->session->setFlash("success", ConferenceModule::t('conference', "created_success"));

                return $this->redirect(['index']);
            }
        }
        //$model->cm_max_participant = 0;
        $model->cm_name = ConferenceMaster::getConferenceName($model->cm_name);

        return $this->render('create',
            [
                'model' => $model,
            ]);
    }

    /**
     * Updates an existing ConferenceMaster model.
     * If update is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     *
     * @return Response|string
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->setScenario('update');

        if ($model->cm_moh == 'NULL') {
            $model->cm_moh = NULL;
        } else if ($model->cm_moh == '') {
            $model->cm_moh = NULL;
        } else {
            $model->cm_moh;
        }

        if ($model->load(Yii::$app->request->post())) {

            if ($model->cm_moh == 'NULL' || $model->cm_moh == '') {
                $globalConfig = GlobalConfig::find()->where(['gwc_key' => 'moh_file'])->one();
                if (!empty($globalConfig)) {
                    $model->cm_moh = $globalConfig->gwc_value;
                } else {
                    $model->cm_moh = NULL;
                }
            }


            $model->cm_quick_start = Yii::$app->request->post('cm_quick_start');
            $model->cm_entry_tone = Yii::$app->request->post('cm_entry_tone');
            $model->cm_exit_tone = Yii::$app->request->post('cm_exit_tone');
            $model->cm_status = Yii::$app->request->post('cm_status');

            if ($model->validate()) {
                $model->cm_name = $model->cm_name.'_'.$_SERVER['HTTP_HOST'];
                $model->save(FALSE);
                Yii::$app->session->setFlash("success", ConferenceModule::t('conference', "updated_success"));
                return $this->redirect(Yii::$app->session->get('conf_redirect_to', Url::to(['index'])));
            }
        }
        $model->cm_name = ConferenceMaster::getConferenceName($model->cm_name);
        return $this->render('update',
            [
                'model' => $model,
            ]);
    }

    /**
     * Finds the ConferenceMaster model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     *
     * @return ConferenceMaster the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ConferenceMaster::findOne([
                'cm_id' => $id,
                //                'user_id' => Yii::$app->user->identity->user_id,
            ])) !== NULL) {
            return $model;
        } else {
            throw new NotFoundHttpException(ConferenceModule::t('conference', 'page_not_exits'));
        }
    }

    /**
     * Configuration of Conference Actions.
     *
     * @param $id
     *
     * @return string|Response
     */
    public function actionConfiguration($id)
    {
        $model = ConferenceMaster::findOne($id);
        $cm_name = ConferenceMaster::getConferenceName($model->cm_name);
        $multiModel = ConferenceControls::find()->where(['cm_id' => $id])->orderBy('cc_digits')->all();
        if (Model::loadMultiple($multiModel, Yii::$app->request->post())) {
            if (Model::validateMultiple($multiModel)) {
                foreach ($multiModel as $value) {
                    $value->save();
                }
                Yii::$app->session->setFlash('success', ConferenceModule::t('conference', 'updated_success'));
                return $this->redirect(Yii::$app->session->get('conf_redirect_to', Url::to(['index'])));
            }
        }

        return $this->render('configuration',
            [
                'multiModel' => $multiModel,
                'cm_name' => $cm_name,
            ]);
    }

    /**
     * Deletes an existing ConferenceMaster model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     *
     * @return Response
     * @throws Exception
     * @throws NotFoundHttpException
     * @throws \Exception
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if ($model) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if ($model->delete()) {
                    ConferenceControls::deleteAll(['cm_id' => $id]);
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', ConferenceModule::t('conference', 'deleted_success'));

                    return $this->redirect(Yii::$app->session->get('conf_redirect_to', Url::to(['index'])));
                }
            } catch (Exception $exception) {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', ConferenceModule::t('conference', 'something_wrong'));

                return $this->redirect(Yii::$app->session->get('conf_redirect_to', Url::to(['index'])));
            }
        }

        return $this->redirect(Yii::$app->session->get('conf_redirect_to', Url::to(['index'])));
    }
}
