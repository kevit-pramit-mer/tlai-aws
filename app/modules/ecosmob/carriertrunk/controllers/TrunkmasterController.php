<?php

namespace app\modules\ecosmob\carriertrunk\controllers;

use app\models\CodecMaster;
use app\modules\ecosmob\carriertrunk\CarriertrunkModule;
use app\modules\ecosmob\carriertrunk\models\TrunkGroupDetails;
use app\modules\ecosmob\carriertrunk\models\TrunkMaster;
use app\modules\ecosmob\carriertrunk\models\TrunkMasterSearch;
use Yii;
use yii\base\Exception;
use yii\base\InvalidArgumentException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * TrunkmasterController implements the CRUD actions for TrunkMaster model.
 */
class TrunkmasterController extends Controller
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
     * Lists all TrunkMaster models.
     *
     * @return string
     */
    public function actionIndex()
    {
        Yii::$app->session->set('tmaster_redirect_to', Url::current());
        $searchModel = new TrunkMasterSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index',
            [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
    }

    /**
     * Creates a new TrunkMaster model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     *
     * @return Response|string
     * @throws \yii\db\Exception
     */
    public function actionCreate()
    {
        $licenseData = Yii::$app->commonHelper->getLicenseData($_SERVER['HTTP_HOST']);
        if(!empty($licenseData)) {
            $maxTrunk = $licenseData['maxSipTrunk'];
            $totalTrunk = TrunkMaster::find()->where(['from_service' => '0'])->count();
            if ($totalTrunk >= $maxTrunk) {
                Yii::$app->session->setFlash('error', Yii::t('app', 'license_limit_exceed'));
                return $this->redirect(['index']);
            }
        }
        $model = new TrunkMaster();
        $availableAudioCodecs = CodecMaster::getAllAudioCodec();
        $availableVideoCodecs = CodecMaster::getAllVideoCodec();
        $model->setScenario('create');

        $transaction = Yii::$app->db->beginTransaction();

        try {
            if ($model->load(Yii::$app->request->post())) {
                $model->trunk_display_name = $model->trunk_name;
                if(empty($model->caller_id)){
                    $model->is_caller_id_override = '0';
                }
                $model->tenant_uuid = $GLOBALS['tenantID'];
                if (empty($model->trunk_proxy_ip)) {
                    $model->trunk_proxy_ip = $model->trunk_ip;
                }

                $model->trunk_status = 'Y';
                if (empty($model->trunk_channels)) {
                    $model->trunk_channels = 1;
                }
                if (empty($model->trunk_cps)) {
                    $model->trunk_cps = 1;
                }
                $model_validated = $model->validate();

                if ($model->audioCodecs == '' && $model->videoCodecs == '') {
                    $model->trunk_absolute_codec = '';
                } else {
                    if ($model->audioCodecs == '') {
                        $model->trunk_absolute_codec = $model->videoCodecs;
                    } else {
                        if ($model->videoCodecs == '') {
                            $model->trunk_absolute_codec = $model->audioCodecs;
                        } else {
                            $codecs = $model->audioCodecs . ',' . $model->videoCodecs;
                            $model->trunk_absolute_codec = $codecs;
                        }
                    }
                }

                if ($model_validated) {
                    $model_saved = $model->save(FALSE);

                    if ($model_saved) {
                        // Reload Freeswitch after create new trunk
                        $transaction->commit();
                        Yii::$app->fsofiapi->methodReloadSofiaProfile();
                        TrunkMaster::callRabbitmq();
                        Yii::$app->session->setFlash("success", CarriertrunkModule::t('carriertrunk', "created_success"));

                        return $this->redirect(['index']);
                    } else {
                        $model->isNewRecord = TRUE;
                        $transaction->rollBack();
                        Yii::$app->session->setFlash('error', CarriertrunkModule::t('carriertrunk', "unable_create_trunk"));
                    }
                } else {

                    $audioCodec = '';
                    $videoCodec = '';

                    $model->audioCodecs = $audioCodec;
                    $model->videoCodecs = $videoCodec;
                    $model->isNewRecord = TRUE;
                    $transaction->rollBack();

                    Yii::$app->session->setFlash('error', CarriertrunkModule::t('carriertrunk', 'unable_create_trunk'));
                }
            }
        } catch (Exception $e) {
            $audioCodec = '';
            $videoCodec = '';

            $model->audioCodecs = $audioCodec;
            $model->videoCodecs = $videoCodec;
            $model->isNewRecord = TRUE;
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', CarriertrunkModule::t('carriertrunk', 'something_wrong'));

            return $this->render('create',
                [
                    'model' => $model,
                    'availableAudioCodecs' => $availableAudioCodecs,
                    'availableVideoCodecs' => $availableVideoCodecs,
                ]);
        }

        return $this->render('create',
            [
                'model' => $model,
                'availableAudioCodecs' => $availableAudioCodecs,
                'availableVideoCodecs' => $availableVideoCodecs,
            ]);
    }

    /**
     * Updates an existing TrunkMaster model.
     * If update is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     *
     * @return mixed
     * @throws InvalidArgumentException
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $old_trunk_name = $model->trunk_name;
        $allAudioCodecsData = CodecMaster::getAllAudioCodec();
        $allVideoCodecsData = CodecMaster::getAllVideoCodec();

        $codecs = $model->trunk_absolute_codec;
        $codecList = explode(',', $codecs);
        $audioCodec = array_intersect($codecList, $allAudioCodecsData);
        $videoCodec = array_intersect($codecList, $allVideoCodecsData);
        $availableAudioCodecUpdate = array_diff($allAudioCodecsData, $audioCodec);
        $availableVideoCodecUpdate = array_diff($allVideoCodecsData, $videoCodec);
        $model->audioCodecs = implode(',', $audioCodec);
        $model->videoCodecs = implode(',', $videoCodec);

        $model->setScenario('update');
        $transaction = Yii::$app->db->beginTransaction();

        try {
            if ($model->load(Yii::$app->request->post())) {
                $model->trunk_display_name = $model->trunk_name;
                if(empty($model->caller_id)){
                    $model->is_caller_id_override = '0';
                }
                $model->tenant_uuid = $GLOBALS['tenantID'];
                if (empty($model->trunk_proxy_ip)) {
                    $model->trunk_proxy_ip = $model->trunk_ip;
                }

                if ($model->audioCodecs == '' && $model->videoCodecs == '') {
                    $model->trunk_absolute_codec = '';
                } else if ($model->audioCodecs == '') {
                    $model->trunk_absolute_codec = $model->videoCodecs;
                } else if ($model->videoCodecs == '') {
                    $model->trunk_absolute_codec = $model->audioCodecs;
                } else {
                    $codecs = $model->audioCodecs . ',' . $model->videoCodecs;
                    $model->trunk_absolute_codec = $codecs;
                }

                $model_validated = $model->validate();
                if ($model_validated) {
                    $model_saved = $model->save(FALSE);
                    if ($model_saved) {
                        $transaction->commit();
                        $gatewayName = $model->tenant_uuid . '_' . $old_trunk_name;
                        Yii::$app->fsofiapi->removeOldSofiaProfile($gatewayName);
                        Yii::$app->fsofiapi->methodReloadSofiaProfile();
                        TrunkMaster::callRabbitmq();
                        Yii::$app->session->setFlash('success', CarriertrunkModule::t('carriertrunk', "updated_success"));
                        return $this->redirect(Yii::$app->session->get('tmaster_redirect_to', 'index'));
                    } else {
                        $transaction->rollBack();
                        $model->audioCodecs = implode(',', $audioCodec);
                        $model->videoCodecs = implode(',', $videoCodec);
                        Yii::$app->session->setFlash('error', CarriertrunkModule::t('carriertrunk', 'unable_update_trunk'));
                    }
                } else {
                    $transaction->rollBack();
                    $model->audioCodecs = implode(',', $audioCodec);
                    $model->videoCodecs = implode(',', $videoCodec);
                    Yii::$app->session->setFlash('error', CarriertrunkModule::t('carriertrunk', 'unable_update_trunk'));
                }
            }
        } catch (Exception $e) {
            $transaction->rollBack();
            $model->audioCodecs = implode(',', $audioCodec);
            $model->videoCodecs = implode(',', $videoCodec);
            Yii::$app->session->setFlash('error', CarriertrunkModule::t('carriertrunk', 'something_wrong'));

            return $this->render('update',
                [
                    'model' => $model,
                    'audioCodec' => $audioCodec,
                    'videoCodec' => $videoCodec,
                    'availableAudioCodecUpdate' => $availableAudioCodecUpdate,
                    'availableVideoCodecUpdate' => $availableVideoCodecUpdate,
                ]);
        }

        return $this->render('update',
            [
                'model' => $model,
                'audioCodec' => $audioCodec,
                'videoCodec' => $videoCodec,
                'availableAudioCodecUpdate' => $availableAudioCodecUpdate,
                'availableVideoCodecUpdate' => $availableVideoCodecUpdate,
            ]);
    }

    /**
     * Finds the TrunkMaster model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     *
     * @return TrunkMaster the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TrunkMaster::findOne($id)) !== NULL) {
            return $model;
        } else {
            throw new NotFoundHttpException(CarriertrunkModule::t('carriertrunk', 'page_not_exits'));
        }
    }

    /**
     * @param $id
     * @return Response
     * @throws NotFoundHttpException
     * @throws \Exception
     */
    public function actionDelete($id)
    {
        $trunkExistInDetails = TrunkGroupDetails::find()->where(['trunk_id' => $id])->count();
        if ($trunkExistInDetails) {
            Yii::$app->session->setFlash("error", CarriertrunkModule::t('carriertrunk', "cannot_delete_assign_togrp"));
        } else {
            if ($model = $this->findModel($id)) {
                $gatewayName = $model->tenant_uuid . '_' . $model->trunk_name;
                $model->delete();
                TrunkGroupDetails::deleteAll(['trunk_id' => $id]);
                Yii::$app->fsofiapi->methodReloadSofiaProfile();
                Yii::$app->fsofiapi->removeOldSofiaProfile($gatewayName);
                TrunkMaster::callRabbitmq();
                Yii::$app->session->setFlash("success", CarriertrunkModule::t('carriertrunk', "deleted_success"));
                return $this->redirect(Yii::$app->session->get('tmaster_redirect_to', 'index'));
            }
        }
        return $this->redirect(Yii::$app->session->get('tmaster_redirect_to', 'index'));
    }
}
