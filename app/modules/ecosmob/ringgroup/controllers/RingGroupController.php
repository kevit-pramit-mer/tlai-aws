<?php

namespace app\modules\ecosmob\ringgroup\controllers;

use app\modules\ecosmob\autoattendant\models\AutoAttendantMaster;
use app\modules\ecosmob\conference\models\ConferenceMaster;
use app\modules\ecosmob\extension\models\Extension;
use app\modules\ecosmob\playback\models\Playback;
use app\modules\ecosmob\queue\models\QueueMaster;
use app\modules\ecosmob\ringgroup\models\RingGroup;
use app\modules\ecosmob\ringgroup\models\RingGroupMapping;
use app\modules\ecosmob\ringgroup\models\RingGroupSearch;
use app\modules\ecosmob\ringgroup\RingGroupModule;
use app\modules\ecosmob\services\models\Services;
use Yii;
use yii\base\InvalidArgumentException;
use yii\db\Exception;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * RingGroupController implements the CRUD actions for RingGroup model.
 */
class RingGroupController extends Controller
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
                            'update',
                            'delete',
                            'change-action',
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
     * Lists all RingGroup models.
     *
     * @return string
     * @throws InvalidArgumentException
     */
    public function actionIndex()
    {
        $searchModel = new RingGroupSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index',
            [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
    }

    /**
     * Displays a single RingGroup model.
     *
     * @param integer $id
     *
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     * @throws InvalidArgumentException
     */
    public function actionView($id)
    {
        return $this->render('view',
            [
                'model' => $this->findModel($id),
            ]);
    }

    /**
     * Finds the RingGroup model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     *
     * @return RingGroup the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = RingGroup::findOne($id)) !== NULL) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Creates a new RingGroup model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     *
     * @return Response|string
     * @throws InvalidArgumentException
     */

    public function actionCreate()
    {
        $model = new RingGroup();
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                $model->rg_callerid_name = '0';
                $model->save();
                $this->saveRingGroupMapping($model->extension_list, $model->rg_id);
                $transaction->commit();
                Yii::$app->session->setFlash('success', RingGroupModule::t('rg', 'created_success'));
                return $this->redirect(['index']);
            }
        } catch (Exception $exception) {
            $transaction->rollBack();
            Yii::$app->session->setFlash('success', RingGroupModule::t('rg', 'something_wrong'));
        }

        return $this->render('create',
            [
                'model' => $model,
            ]);
    }


    /**
     * @param $extension_list
     * @param $ringGroupId
     *
     * @return void
     */
    private function saveRingGroupMapping($extension_list, $ringGroupId)
    {
        $extensionList = json_decode($extension_list);

        foreach ($extensionList as $extension) {
            $mappingModel = new RingGroupMapping();
            $mappingModel->rg_id = $ringGroupId;
            $mappingModel->rm_type = $extension->rm_type;

            $mappingModel->rm_number = $extension->rm_number;

            $temp_rm_number = explode("-", $extension->rm_number);
            if (!empty($temp_rm_number)) {
                $mappingModel->rm_number = end($temp_rm_number);
            }

            $mappingModel->rm_priority = $extension->rm_priority;
            $mappingModel->save(FALSE);
        }
    }

    /**
     * Updates an existing RingGroup model.
     * If update is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     *
     * @return Response|string
     * @throws NotFoundHttpException if the model cannot be found
     * @throws InvalidArgumentException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $extensionList = RingGroupMapping::find()
            ->select(['rm_type', 'rm_number', 'rm_priority'])
            ->where(['rg_id' => $id])
            ->asArray()
            ->all();
        $model->extension_list = json_encode($extensionList);

        $transaction = Yii::$app->db->beginTransaction();

        try {
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                if ($model->rg_is_failed == 0) {
                    $model->rg_failed_service_id = NULL;
                    $model->rg_failed_action = NULL;
                }

                if ($model->save(FALSE)) {
                    RingGroupMapping::deleteAll(['rg_id' => $id]);

                    $this->saveRingGroupMapping($model->extension_list, $id);
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', RingGroupModule::t('rg', 'updated_success'));
                    return $this->redirect(['index']);
                }
            }
        } catch (Exception $exception) {
            $transaction->rollBack();
            Yii::$app->session->setFlash('success', RingGroupModule::t('rg', 'something_wrong'));
        }

        return $this->render('update',
            [
                'model' => $model,
            ]);
    }

    /**
     * Deletes an existing RingGroup model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     *
     * @return Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $transaction = Yii::$app->db->beginTransaction();

        try {
            $this->findModel($id)->delete();
            RingGroupMapping::deleteAll(['rg_id' => $id]);

            $transaction->commit();
            Yii::$app->session->setFlash('success', RingGroupModule::t('rg', 'deleted_success'));
        } catch (Exception $exception) {
            $transaction->rollBack();
            Yii::$app->session->setFlash('success', RingGroupModule::t('rg', 'something_wrong'));
        }

        return $this->redirect(['index']);
    }

    /**
     * @return string
     * @throws InvalidArgumentException
     */
    public function actionChangeAction()
    {
        $action_value = "";

        if (isset($_POST['action_id'])) {
            $action_id = $_POST['action_id'];
            $action_value = $_POST['action_value'];
            /** @var Services $data */
            $data = Services::find()->where(['ser_id' => $action_id])->asArray()->one();
            if (count($data)) {
                $ser_name = $data['ser_name'];
                if ($ser_name == 'EXTENSION') {
                    $data = Extension::find()->select(['em_id AS id', 'CONCAT(em_extension_name, " - ", em_extension_number) AS name'])->asArray()->all();
                } else if ($ser_name == 'IVR' || $ser_name == 'AUDIO TEXT') {
                    $data = AutoAttendantMaster::find()->select(['aam_id AS id', 'aam_name AS name'])->asArray()->all();
                } else if ($ser_name == 'QUEUE') {
                    $data = QueueMaster::find()->select(['qm_id AS id', 'qm_name AS name'])->asArray()->all();
                } else if ($ser_name == 'VOICEMAIL') {
                    $data = '';
                } else if ($ser_name == 'RING GROUP') {
                    $data = RingGroup::find()->select(['rg_id AS id', 'rg_name AS name'])->asArray()->all();
                } else if ($ser_name == 'EXTERNAL') {
                    $data = '';
                } else if ($ser_name == 'CONFERENCE') {
                    $data = ConferenceMaster::find()->select(['cm_id AS id', new \yii\db\Expression("SUBSTRING_INDEX(cm_name, '_', 1) AS name")])->asArray()->all();
                } else if ($ser_name == 'PLAYBACK') {
                    $data = Playback::find()->select(['pb_id AS id', 'pb_name AS name'])->asArray()->all();
                } else {
                    $data = '';
                }
            }
        } else {
            $data = '';
        }

        return $this->renderPartial('change-action',
            [
                'action_value' => $action_value,
                'data' => $data,
            ]);
    }


}
