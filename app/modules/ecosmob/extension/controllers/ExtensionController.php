<?php

namespace app\modules\ecosmob\extension\controllers;

use app\models\CodecMaster;
use app\models\SipPresence;
use app\models\SipRegistrations;
use app\modules\ecosmob\agents\models\AdminMaster;
use app\modules\ecosmob\blf\models\ExtensionBlf;
use app\modules\ecosmob\callhistory\models\CampCdr;
use app\modules\ecosmob\cdr\models\Cdr;
use app\modules\ecosmob\didmanagement\models\DidManagement;
use app\modules\ecosmob\enterprisePhonebook\models\EnterprisePhonebook;
use app\modules\ecosmob\extension\extensionModule;
use app\modules\ecosmob\extension\models\Callsettings;
use app\modules\ecosmob\extension\models\Extension;
use app\modules\ecosmob\extension\models\ExtensionCallLog;
use app\modules\ecosmob\extension\models\ExtensionSearch;
use app\modules\ecosmob\extensionforwarding\models\ExtensionForwarding;
use app\modules\ecosmob\speeddial\models\ExtensionSpeeddial;
use app\modules\ecosmob\timezone\models\Timezone;
use app\modules\ecosmob\voicemsg\models\VoicemailMsgs;
use Throwable;
use Yii;
use yii\base\InvalidArgumentException;
use yii\data\ActiveDataProvider;
use yii\db\Exception;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;
use app\modules\ecosmob\phonebook\models\Phonebook;

/**
 * ExtensionController implements the CRUD actions for Extension model.
 */
class ExtensionController extends Controller
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
                            'dashboard',
                            'index',
                            'create',
                            'view',
                            'update',
                            'delete',
                            'import',
                            'export',
                            'download-basic-file',
                            'download-advanced-file',
                            'update-extension',
                            'dashboard',
                            'tragofone',
                            'get-data',
                            'get-contacts',
                            'get-blf-list',
                            'get-fwd-contacts',
                            'change-password',
                            'get-speed-dial'
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionDashboard()
    {
        $extension_number = Yii::$app->user->identity->em_extension_number;
        $em_extension_name = Yii::$app->user->identity->em_extension_name;
        $em_language_id = Yii::$app->user->identity->em_language_id;
        $em_id = Yii::$app->user->identity->em_id;
        $callSetting_data = Callsettings::findOne(['em_id' => $em_id]);
        $forwarding_data = ExtensionForwarding::findOne(['ef_extension' => $extension_number]);
        $voiceMsg = VoicemailMsgs::find()->where(['username' => $extension_number])->count();
        $callRecords = Cdr::find()->andWhere(['or', ['caller_id_number' => $extension_number], ['dialed_number' => $extension_number]])->andWhere(['like', 'record_filename', 'recordings'])->andWhere(['!=', "caller_id_number", '*99'])->count();

        $is_online = false;
        $systemLoad = Yii::$app->fscoredb->createCommand("SELECT count(*) as total FROM fs_core.sip_registrations WHERE sip_user = '" . $extension_number . "'")->queryAll();
        if (!empty($systemLoad)) {
            if ($systemLoad[0]['total'] > 0) {
                $is_online = true;
            }
        }

        $last_callee = '-';
        $cdr = Cdr::find()->where(['caller_id_number' => Yii::$app->user->identity->em_extension_number])->orderby(['start_epoch' => SORT_DESC])->limit(1)->asArray()->all();
        if (!empty($cdr)) {
            $last_callee = $cdr[0]['dialed_number'];
        }

        $last_caller_details = '-';
        $cdr = Cdr::find()->where(['dialed_number' => Yii::$app->user->identity->em_extension_number])->orderby(['start_epoch' => SORT_DESC])->limit(1)->asArray()->all();
        if (!empty($cdr)) {
            $last_caller_details = $cdr[0]['caller_id_number'];
        }

        return $this->renderPartial('dashboard',
            [
                'extension_number' => $extension_number,
                'em_extension_name' => $em_extension_name,
                'em_language_id' => $em_language_id,
                'callSetting_data' => $callSetting_data,
                'forwarding_data' => $forwarding_data,
                'voiceMsg' => $voiceMsg,
                'callRecords' => $callRecords,
                'is_online' => $is_online,
                'last_callee' => $last_callee,
                'last_caller_details' => $last_caller_details,
            ]);
    }

    /**
     * @return string
     */
    public function actionIndex()
    {
        if (Yii::$app->request->get('per-page')) {
            Yii::$app->session->set('per-page', (int)Yii::$app->request->get('per-page'));
        }

        if (Yii::$app->request->get('page')) {
            Yii::$app->session->set('page', (int)Yii::$app->request->get('page'));
        } else {
            Yii::$app->session->set('page', 1);
        }
        $searchModel = new ExtensionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        Yii::$app->session->set('extension', $dataProvider->query);

        return $this->render('index',
            [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
    }

    /**
     * Creates a new Extension model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $licenseData = Yii::$app->commonHelper->getLicenseData($_SERVER['HTTP_HOST']);
        if(!empty($licenseData)) {
            $maxExtension = $licenseData['maxExtensions'];
            $totalExtension = Extension::find()->count();
            if ($totalExtension >= $maxExtension) {
                Yii::$app->session->setFlash('error', Yii::t('app', 'license_limit_exceed'));
                return $this->redirect(['index']);
            }
        }
        $model = new Extension();
        $call_setting_model = new Callsettings();
        //        $call_setting_model->loadDefaultValues();
        $call_setting_model->all_audio_codec = CodecMaster::getAllAudioCodec();
        $call_setting_model->all_video_codec = CodecMaster::getAllVideoCodec();
        $call_setting_model->orig_codec = [];
        $call_setting_model->orig_video_codec = [];
        $call_setting_model->ecs_video_calling = 0;
        $call_setting_model->ecs_ring_timeout = 60;
        $call_setting_model->ecs_call_timeout = 60;
        $call_setting_model->ecs_ob_max_timeout = 3600;
        $model->em_number_type = 'number';
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($model->load(Yii::$app->request->post())) {
                $is_name_exist = (($model->em_extension_name != '') ? true : false);
                if ($model->em_number_type == 'number') {
                    $model->em_extension_name = $is_name_exist ? $model->em_extension_name : $model->em_extension_number;
                    $model->trago_username = Yii::$app->session->get('tenant_code').$model->em_extension_number;
                    if ($call_setting_model->load(Yii::$app->request->post())) {
                        if (Yii::$app->request->post('orig_codec')) {
                            foreach (Yii::$app->request->post('orig_codec') as $val) {
                                if (!in_array($val, CodecMaster::getAllAudioCodec())) {
                                    Yii::$app->session->setFlash('error', Yii::t('app', 'Invalid Audio Codec'));
                                    return $this->render('create',
                                        [
                                            'model' => $model,
                                            'call_setting_model' => $call_setting_model,
                                        ]);
                                }
                            }
                            $call_setting_model->ecs_audio_codecs = implode(',', Yii::$app->request->post('orig_codec'));
                        } else {
                            $call_setting_model->ecs_audio_codecs = "PCMA";
                           /* $call_setting_model->orig_codec = [];
                            Yii::$app->session->setFlash('error', Yii::t('app', 'Audio Codec cannot be blank.'));

                            return $this->render('create',
                                [
                                    'model' => $model,
                                    'call_setting_model' => $call_setting_model,
                                ]);*/
                        }
                        if (Yii::$app->request->post('orig_video_codec')) {
                            foreach (Yii::$app->request->post('orig_video_codec') as $val) {
                                if (!in_array($val, CodecMaster::getAllVideoCodec())) {
                                    Yii::$app->session->setFlash('error',
                                        Yii::t('app', 'Invalid Video Codec'));
                                    return $this->render('create',
                                        [
                                            'model' => $model,
                                            'call_setting_model' => $call_setting_model,
                                        ]);
                                }
                            }
                            $call_setting_model->ecs_video_codecs = implode(',',
                                Yii::$app->request->post('orig_video_codec'));
                        } else {
                            $call_setting_model->ecs_video_codecs = "";
                        }

                        if ($model->save()) {
                            if(!empty($_POST['Extension']['did'])){
                                Yii::$app->db->createCommand()
                                    ->update('ct_did_master', (['action_id' => '1', 'action_value' => $model->em_id]), ['IN', 'did_id', $_POST['Extension']['did']])
                                    ->execute();
                            }
                            $call_setting_model->em_id = $model->em_id;
                            $call_setting_model->ecs_ring_timeout = empty($call_setting_model->ecs_ring_timeout) ? '60' : $call_setting_model->ecs_ring_timeout;
                            $call_setting_model->ecs_call_timeout = empty($call_setting_model->ecs_call_timeout) ? '60' : $call_setting_model->ecs_call_timeout;
                            $call_setting_model->ecs_ob_max_timeout = empty($call_setting_model->ecs_ob_max_timeout) ? '3600' : $call_setting_model->ecs_ob_max_timeout;
                            if ($call_setting_model->save()) {
                                if (Yii::$app->session->get('isTragofone') == 1) {
                                    $tragofoneApi = $this->callTragofoneApi('create', $model, $call_setting_model);
                                    if ($tragofoneApi['status'] == true) {
                                        $model->trago_user_id = $tragofoneApi['user_id'];
                                        $model->is_tragofone = 1;
                                        $model->save();
                                        $transaction->commit();
                                        Yii::$app->session->setFlash('success',
                                            extensionModule::t('app', 'Created Successfully'));

                                        return $this->redirect(['index']);
                                    } else {
                                        $transaction->rollBack();
                                        //Yii::$app->session->setFlash('error', extensionModule::t('app', 'something_wrong'));
                                        Yii::$app->session->setFlash('error', $tragofoneApi['msg']);
                                        return $this->redirect(['index']);
                                        /*return $this->render('create',
                                            [
                                                'model' => $model,
                                                'call_setting_model' => $call_setting_model,
                                            ]);*/
                                    }
                                } else {
                                    $transaction->commit();
                                    Yii::$app->session->setFlash('success',
                                        extensionModule::t('app', 'Created Successfully'));

                                    return $this->redirect(['index']);
                                }
                            }
                        }
                    }
                } else {
                    $unsaved_values = [];
                    if ($model->validate([
                            'em_extension_range_to',
                            'em_extension_range_from',
                        ]
                    )
                    ) {
                        for ($i = $model->em_extension_range_from; $i <= $model->em_extension_range_to; $i++) {
                            $model->setIsNewRecord(true);
                            $call_setting_model->setIsNewRecord(true);

                            $model->em_id = null;
                            $call_setting_model->ecs_id = null;

                            $model->em_extension_number = (string)$i;
                            $model->trago_username = Yii::$app->session->get('tenant_code').$i;

                            if (!$is_name_exist) {
                                $model->em_extension_name = $model->em_extension_number;
                            }
                            if (!$model->save()) {

                                $unsaved_values[] = $model->em_extension_number;
                            } else {
                                if ($call_setting_model->load(Yii::$app->request->post())) {

                                    if (Yii::$app->request->post('orig_codec')) {
                                        foreach (Yii::$app->request->post('orig_codec') as $val) {
                                            if (!in_array($val, CodecMaster::getAllAudioCodec())) {
                                                Yii::$app->session->setFlash('danger', Yii::t('app', 'Invalid Audio Codec'));

                                                return $this->render('create',
                                                    [
                                                        'model' => $model,
                                                        'call_setting_model' => $call_setting_model,
                                                    ]);
                                            }
                                        }
                                        $call_setting_model->ecs_audio_codecs = implode(',',
                                            Yii::$app->request->post('orig_codec'));
                                    } else {
                                        $call_setting_model->ecs_audio_codecs = "PCMA";
                                       /* $call_setting_model->orig_codec = [];
                                        Yii::$app->session->setFlash('error', Yii::t('app', 'Audio Codec cannot be blank.'));

                                        return $this->render('create',
                                            [
                                                'model' => $model,
                                                'call_setting_model' => $call_setting_model,
                                            ]);*/
                                    }

                                    $call_setting_model->ecs_video_codecs = "H263";
                                    if (Yii::$app->request->post('orig_video_codec')) {
                                        foreach (Yii::$app->request->post('orig_video_codec') as $val) {
                                            if (!in_array($val, CodecMaster::getAllVideoCodec())) {
                                                Yii::$app->session->setFlash('error',
                                                    Yii::t('app', 'Invalid Video Codec'));

                                                return $this->render('create',
                                                    [
                                                        'model' => $model,
                                                        'call_setting_model' => $call_setting_model,
                                                    ]);
                                            }
                                        }
                                        $call_setting_model->ecs_video_codecs = implode(',',
                                            Yii::$app->request->post('orig_video_codec'));
                                    } else {
                                        $call_setting_model->ecs_video_codecs = "";
                                    }

                                    $call_setting_model->em_id = $model->em_id;
                                    $call_setting_model->ecs_ring_timeout = empty($call_setting_model->ecs_ring_timeout) ? '60' : $call_setting_model->ecs_ring_timeout;
                                    $call_setting_model->ecs_call_timeout = empty($call_setting_model->ecs_call_timeout) ? '60' : $call_setting_model->ecs_call_timeout;
                                    $call_setting_model->ecs_ob_max_timeout = empty($call_setting_model->ecs_ob_max_timeout) ? '3600' : $call_setting_model->ecs_ob_max_timeout;
                                    if ($call_setting_model->save()) {
                                        if (Yii::$app->session->get('isTragofone') == 1) {

                                            $tragofoneApi = $this->callTragofoneApi('create', $model, $call_setting_model);

                                            if ($tragofoneApi['status'] == true) {
                                                $model->trago_user_id = $tragofoneApi['user_id'];
                                                $model->is_tragofone = 1;
                                                $model->save();
                                            }else{
                                                $transaction->rollBack();

                                                //Yii::$app->session->setFlash('error', extensionModule::t('app', 'something_wrong'));
                                                Yii::$app->session->setFlash('error', $tragofoneApi['msg']);
                                                return $this->redirect(['create']);
                                               /* return $this->render('create',
                                                    [
                                                        'model' => $model,
                                                        'call_setting_model' => $call_setting_model,
                                                    ]);*/
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        $message = "";
                        if (count($unsaved_values) > 0) {

                            $message = extensionModule::t('app',
                                    "List of numbers that already exist") . " : " . implode(",",
                                    $unsaved_values);
                        }
                        $transaction->commit();
                        Yii::$app->session->setFlash('extMessage',
                            extensionModule::t('app', 'Created successfully') . ' ' . $message);

                        return $this->redirect(['index']);
                    }
                }
            }
        } catch (Exception $e) {
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', $e->getMessage());
            return $this->redirect(['index']);
            /*return $this->render('create',
                [
                    'model' => $model,
                    'call_setting_model' => $call_setting_model,
                ]);*/
        }

        return $this->render('create',
            [
                'model' => $model,
                'call_setting_model' => $call_setting_model,
            ]);
    }

    /**
     * Updates an existing Extension model.
     * If update is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     *
     * @throws NotFoundHttpException if the model cannot be found
     * @throws InvalidArgumentException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $oldTragoUsername = $model->trago_username;
        $isAssignedExtension = AdminMaster::findOne(['adm_mapped_extension' => $id]);
        $did = DidManagement::find()->where(['action_id' => '1', 'action_value' => $id])->all();
        $selectedDid = [];
        if(!empty($did)) {
            foreach ($did as $_did) {
                $selectedDid[$_did->did_id] = array("selected" => true);
            }
        }
        if ($model->load(Yii::$app->request->post()) && $model->em_status == 0 && $isAssignedExtension) {
            Yii::$app->session->setFlash('error', extensionModule::t('app', 'already_assigned_not_inactive'));
            return $this->refresh();
        }

        $call_setting_model = Callsettings::find()->where(['em_id' => $id])->one();
        $oldEcsForwarding = $call_setting_model->ecs_forwarding;
        if($call_setting_model->ecs_forwarding != 0) {
            $call_setting_model->ecs_forwarding = 3;
        }
        $call_setting_model->all_audio_codec = CodecMaster::getAllAudioCodec();
        $call_setting_model->all_video_codec = CodecMaster::getAllVideoCodec();
        $call_setting_model->orig_codec = $call_setting_model->ecs_audio_codecs != "" ? explode(',',
            $call_setting_model->ecs_audio_codecs)
            : [];
        $call_setting_model->orig_video_codec = $call_setting_model->ecs_video_codecs != "" ? explode(',',
            $call_setting_model->ecs_video_codecs)
            : [];
        if ($call_setting_model->ecs_video_calling == 0 || $call_setting_model->ecs_video_calling == '') {
            $call_setting_model->ecs_video_calling = 0;
        }

        if (empty($call_setting_model)) {
            $call_setting_model = new Callsettings();
        }

        if(Yii::$app->session->get('isTragofone') == 1 && !empty($model->trago_user_id)) {
            Extension::updateImStatus($id, $model->trago_user_id);
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($model->load(Yii::$app->request->post())) {
                //echo '<pre>'; print_r($_POST);exit;
                $model->em_web_password = trim($model->em_web_password);
                $model->em_extension_name = ($model->em_extension_name != '') ? $model->em_extension_name : $model->em_extension_number;
                $model->trago_username = Yii::$app->session->get('tenant_code').$model->em_extension_number;

                Yii::$app->db->createCommand()
                    ->update('ct_did_master', (['action_id' => '', 'action_value' => '']), ['action_id' => '1', 'action_value' => $id])
                    ->execute();
                if(!empty($_POST['Extension']['did'])){
                    Yii::$app->db->createCommand()
                        ->update('ct_did_master', (['action_id' => '1', 'action_value' => $id]), ['IN', 'did_id', $_POST['Extension']['did']])
                        ->execute();
                }

                if ($call_setting_model->load(Yii::$app->request->post())) {
                    if ($model->save()) {
                        if (Yii::$app->request->post('orig_codec')) {
                            foreach (Yii::$app->request->post('orig_codec') as $val) {
                                if (!in_array($val, CodecMaster::getAllAudioCodec())) {
                                    Yii::$app->session->setFlash('error', Yii::t('app', 'Invalid Audio Codec'));

                                    return $this->render('update',
                                        [
                                            'model' => $model,
                                            'call_setting_model' => $call_setting_model,
                                            'selectedDid' => $selectedDid
                                        ]);
                                }
                            }
                            $call_setting_model->ecs_audio_codecs = implode(',', Yii::$app->request->post('orig_codec'));
                        } else {
                            $call_setting_model->ecs_audio_codecs = "PCMA";
                           /* $call_setting_model->orig_codec = [];
                            Yii::$app->session->setFlash('error', Yii::t('app', 'Audio Codec cannot be blank.'));

                            return $this->render('update',
                                [
                                    'model' => $model,
                                    'call_setting_model' => $call_setting_model,
                                ]);*/
                        }
                        if (Yii::$app->request->post('orig_video_codec') && $call_setting_model->ecs_video_calling == 1) {
                            foreach (Yii::$app->request->post('orig_video_codec') as $val) {
                                if (!in_array($val, CodecMaster::getAllVideoCodec())) {
                                    Yii::$app->session->setFlash('error',
                                        Yii::t('app', 'Invalid Video Codec'));

                                    return $this->render('update',
                                        [
                                            'model' => $model,
                                            'call_setting_model' => $call_setting_model,
                                            'selectedDid' => $selectedDid
                                        ]);
                                }
                            }
                            $call_setting_model->ecs_video_codecs = implode(',',
                                Yii::$app->request->post('orig_video_codec'));
                        } else {
                            $call_setting_model->ecs_video_codecs = "";
                        }

                        $call_setting_model->em_id = $model->primaryKey;
                        $call_setting_model->ecs_ring_timeout = empty($call_setting_model->ecs_ring_timeout) ? '60' : $call_setting_model->ecs_ring_timeout;
                        $call_setting_model->ecs_call_timeout = empty($call_setting_model->ecs_call_timeout) ? '60' : $call_setting_model->ecs_call_timeout;
                        $call_setting_model->ecs_ob_max_timeout = empty($call_setting_model->ecs_ob_max_timeout) ? '3600' : $call_setting_model->ecs_ob_max_timeout;
                        if($call_setting_model->ecs_forwarding == 3 && $oldEcsForwarding != 0){
                            $call_setting_model->ecs_forwarding = $oldEcsForwarding;
                        }
                        if ($call_setting_model->save()) {

                            if (Yii::$app->session->get('isTragofone') == 1) {
                                if (empty($model->trago_user_id)) {
                                    $tragofoneApi = $this->callTragofoneApi('create', $model, $call_setting_model);
                                    if ($tragofoneApi['status'] == true) {
                                        $model->is_tragofone = '1';
                                        $model->trago_user_id = $tragofoneApi['user_id'];
                                        $model->save();
                                    }else {
                                        $transaction->rollBack();
                                        Yii::$app->session->setFlash('error', $tragofoneApi['msg']);
                                        return $this->render('update',
                                            [
                                                'model' => $model,
                                                'call_setting_model' => $call_setting_model,
                                                'selectedDid' => $selectedDid
                                            ]);
                                    }
                                } else {

                                    if (!empty($oldTragoUsername) && $model->trago_username != $oldTragoUsername) {

                                        $tragofoneApi = $this->callTragofoneApi('delete', $model->trago_user_id);

                                        if ($tragofoneApi['status'] == true) {
                                            $createTragoUserApi = $this->callTragofoneApi('create', $model, $call_setting_model);

                                            if ($createTragoUserApi['status'] == true) {
                                                $model->is_tragofone = '1';
                                                $model->trago_user_id = $createTragoUserApi['user_id'];
                                                $model->save(false);
                                            } else {
                                                $transaction->rollBack();
                                                Yii::$app->session->setFlash('error', $createTragoUserApi['msg']);
                                                return $this->render('update',
                                                    [
                                                        'model' => $model,
                                                        'call_setting_model' => $call_setting_model,
                                                        'selectedDid' => $selectedDid
                                                    ]);
                                            }
                                        } else {
                                            $transaction->rollBack();
                                            //Yii::$app->session->setFlash('error', extensionModule::t('app', 'something_wrong'));
                                            Yii::$app->session->setFlash('error', $tragofoneApi['msg']);
                                            return $this->render('update',
                                                [
                                                    'model' => $model,
                                                    'call_setting_model' => $call_setting_model,
                                                    'selectedDid' => $selectedDid
                                                ]);
                                        }
                                    }
                                }

                                $tragofoneApi = $this->callTragofoneApi('update', $model, $call_setting_model, $model->trago_user_id, ($model->is_tragofone == '1' ? 'Y' : 'N'));
                                if ($tragofoneApi['status'] == true) {
                                    $transaction->commit();
                                    if (Yii::$app->request->post('apply') == 'update') {
                                        Yii::$app->session->setFlash('success', extensionModule::t('app', 'applied_successfully'));

                                        return $this->refresh();
                                    } else {
                                        Yii::$app->session->setFlash('success', extensionModule::t('app', 'updated_successfully'));

                                        return $this->redirect(['index', 'page' => Yii::$app->session->get('page')]);
                                    }

                                } else {
                                    $transaction->rollBack();
                                    //Yii::$app->session->setFlash('error', extensionModule::t('app', 'something_wrong'));
                                    Yii::$app->session->setFlash('error', $tragofoneApi['msg']);
                                    return $this->render('update',
                                        [
                                            'model' => $model,
                                            'call_setting_model' => $call_setting_model,
                                            'selectedDid' => $selectedDid
                                        ]);
                                }

                            } else {
                                $transaction->commit();
                                Yii::$app->session->setFlash('success', extensionModule::t('app', 'updated_successfully'));
                                return $this->redirect(['index', 'page' => Yii::$app->session->get('page')]);
                            }
                        }
                    }
                }
            }
        } catch (Exception $e) {
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', $e->getMessage());

            return $this->render('update',
                [
                    'model' => $model,
                    'call_setting_model' => $call_setting_model,
                    'selectedDid' => $selectedDid
                ]);
        }

        return $this->render('update',
            [
                'model' => $model,
                'call_setting_model' => $call_setting_model,
                'selectedDid' => $selectedDid
            ]);
    }

    /**
     * Finds the Extension model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     *
     * @return Extension the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = extension::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }

    /**
     * Export records shown in Index page
     */
    public function actionExport()
    {
        ini_set("memory_limit", -1);
        $fp = fopen('php://temp', 'w');
        $fileName = "Extension-" . time() . ".csv";
        $model = new Extension();
        $query = Yii::$app->session->get('extension');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
            'sort' => ['defaultOrder' => ['em_plan_id' => SORT_DESC]],
        ]);

        $records = $dataProvider->getModels();


        $headers = [
            'em_extension_number',
            'sipPassword',
            'em_extension_name',
            'em_email',
            'em_language_id',
            'em_status',
            'em_web_password',
            'em_group_id',
            'is_phonebook',
            'em_timezone_id',
            'bypass_media',
            'external_caller_id',
            'did',
            'max_calls',
            'forwarding',
            'ring_timeout',
            'call_timeout',
            'ob_max_timeout',
            'video_calling',
            'auto_recording',
            'dtmf_type',
            'ecs_multiple_registeration',
            'dial_out',
            'voicemail',
            'voicemail_password',
            'ecs_fax2mail',
            'audio_codecs',
            'video_codecs'
        ];
        if(Yii::$app->session->get('isTragofone') == 1){
            $headers[] = 'imStatus';
            $headers[] = 'is_tragofone';
            $headers[] = 'trago_username';
        }

        $row = array();
        foreach ($headers as $header) {
            $row[] = $model->getAttributeLabel($header);
        }

        fputcsv($fp, $row);
        if (!empty($records)) {
            foreach ($records as $record) {
                $row = array();
                foreach ($headers as $head) {
                    $row[$head] = $record->$head;
                    if ($head == 'sipPassword') {
                        $row[$head] = $record->em_password;
                    }
                    if ($head == 'did') {
                        $did = DidManagement::find()->where(['action_id' => '1', 'action_value' => $record->em_id])->all();
                        $selectedDid = [];
                        if(!empty($did)) {
                            foreach ($did as $_did) {
                                $selectedDid[] = $_did->did_number;
                            }
                        }
                        $row[$head] = (!empty($selectedDid)) ? implode(',', $selectedDid) : '';
                    }
                    if ($head == 'ecs_multiple_registeration') {
                        $row[$head] = ((isset($record->callsettings->ecs_multiple_registeration) && $record->callsettings->ecs_multiple_registeration != "" && $record->callsettings->ecs_multiple_registeration == 1) ? 'Enabled' : 'Disabled');
                    }
                    if ($head == 'is_tragofone') {
                        $row[$head] = (($record->is_tragofone == "1") ? "Active" : "Inactive");
                    }
                    if ($head == 'trago_username') {
                        $row[$head] = $record->trago_username;
                    }
                    if ($head == 'imStatus') {
                        $row[$head] = (($record->callsettings->ecs_im_status == "1") ? "Active" : "Inactive");
                    }
                    if ($head == 'ecs_fax2mail') {
                        $row[$head] = ((isset($record->callsettings->ecs_fax2mail) && $record->callsettings->ecs_fax2mail != "" && $record->callsettings->ecs_fax2mail == 1) ? 'Enabled' : 'Disabled');
                    }
                    if ($head == 'em_status') {
                        $row[$head] = (($record->em_status == "1") ? "Active" : "Inactive");
                    }
                    if ($head == 'em_group_id') {
                        $row[$head] = $record->group->grp_name;
                    }
                    if ($head == 'em_language_id') {
                        $row[$head] = (($record->em_language_id == "1") ? "English" : "Spanish");
                    }
                    if ($head == 'em_timezone_id') {
                        $row[$head] = $record->timezone->tz_zone;
                    }
                    if ($head == 'is_phonebook') {
                        $row[$head] = (($record->is_phonebook == "1") ? "Yes" : "No");
                    }
                    if ($head == 'max_calls') {
                        $row[$head] = (!empty($record->callsettings->ecs_max_calls) ? $record->callsettings->ecs_max_calls : '-');
                    }
                    if ($head == 'ring_timeout') {
                        $row[$head] = (!empty($record->callsettings->ecs_ring_timeout) ? $record->callsettings->ecs_ring_timeout : '-');
                    }
                    if ($head == 'call_timeout') {
                        $row[$head] = (!empty($record->callsettings->ecs_call_timeout) ? $record->callsettings->ecs_call_timeout : '-');
                    }
                    if ($head == 'ob_max_timeout') {
                        $row[$head] = (!empty($record->callsettings->ecs_ob_max_timeout) ? $record->callsettings->ecs_ob_max_timeout : '-');
                    }
                    if ($head == 'video_calling') {
                        $row[$head] = ((isset($record->callsettings->ecs_video_calling) && $record->callsettings->ecs_video_calling != "" && $record->callsettings->ecs_video_calling == "1") ? "Active" : "Inactive");
                    }
                    if ($head == 'auto_recording') {
                        $row[$head] = (
                        (isset($record->callsettings->ecs_auto_recording) && $record->callsettings->ecs_auto_recording != "" && $record->callsettings->ecs_auto_recording == '0') ? "Disabled" :
                            ((isset($record->callsettings->ecs_auto_recording) && $record->callsettings->ecs_auto_recording != "" && $record->callsettings->ecs_auto_recording == '1') ? "All" :
                                ((isset($record->callsettings->ecs_auto_recording) && $record->callsettings->ecs_auto_recording != "" && $record->callsettings->ecs_auto_recording == '2') ? "Internal" : "out of borders")));
                    }
                    if ($head == 'bypass_media') {
                        $row[$head] = (
                        (isset($record->callsettings->ecs_bypass_media) && $record->callsettings->ecs_bypass_media != "" && $record->callsettings->ecs_bypass_media == '0') ? "No" :
                            ((isset($record->callsettings->ecs_bypass_media) && $record->callsettings->ecs_bypass_media != "" && $record->callsettings->ecs_bypass_media == '1') ? "Bypass" :
                                ((isset($record->callsettings->ecs_bypass_media) && $record->callsettings->ecs_bypass_media != "" && $record->callsettings->ecs_bypass_media == '2') ? "Bypass After Bridge" : "Proxy Media")));
                    }
                    if ($head == 'audio_codecs') {
                        $row[$head] = (isset($record->callsettings->ecs_audio_codecs) && $record->callsettings->ecs_audio_codecs != "") ? $record->callsettings->ecs_audio_codecs : "";
                    }
                    if ($head == 'video_codecs') {

                        $row[$head] = (isset($record->callsettings->ecs_video_codecs) && $record->callsettings->ecs_video_codecs != "") ? $record->callsettings->ecs_video_codecs : "";
                    }
                    if ($head == 'dial_out') {
                        $row[$head] = ((isset($record->callsettings->ecs_dial_out) && $record->callsettings->ecs_dial_out != "" && $record->callsettings->ecs_dial_out == "1") ? "Active" : "Inactive");
                    }
                    if ($head == 'voicemail') {
                        $row[$head] = ((isset($record->callsettings->ecs_voicemail) && $record->callsettings->ecs_voicemail != "" && $record->callsettings->ecs_voicemail == "1") ? "Active" : "Inactive");
                    }
                    if ($head == 'dtmf_type') {
                        $row[$head] = (isset($record->callsettings->ecs_dtmf_type) && $record->callsettings->ecs_dtmf_type != "") ? $record->callsettings->ecs_dtmf_type : "";
                    }
                    if ($head == 'voicemail_password') {
                        $row[$head] = (isset($record->callsettings->ecs_voicemail_password) && $record->callsettings->ecs_voicemail_password != "") ? $record->callsettings->ecs_dtmf_type : "";
                    }
                    if ($head == 'feature_code_pin') {
                        $row[$head] = (isset($record->callsettings->ecs_feature_code_pin) && $record->callsettings->ecs_feature_code_pin != "") ? $record->callsettings->ecs_feature_code_pin : "";
                    }
                    if ($head == 'forwarding') {

                        $row[$head] = (
                        (isset($record->callsettings->ecs_forwarding) && $record->callsettings->ecs_forwarding != "" && $record->callsettings->ecs_forwarding == '0') ? "DISABLE" :
                            ((isset($record->callsettings->ecs_forwarding) && $record->callsettings->ecs_forwarding != "" && $record->callsettings->ecs_forwarding == '1') ? "INDIVIDUAL FORWARDING" :
                                ((isset($record->callsettings->ecs_forwarding) && $record->callsettings->ecs_forwarding != "" && $record->callsettings->ecs_forwarding == '2') ? "FIND ME FOLLOW ME FORWARDING" : "ENABLE")));
                    }


                }
                fputcsv($fp, $row);
            }
        }

        rewind($fp);
        header("Content-type:text/csv");
        header("Content-Disposition:attachment; filename=" . $fileName);
        $file = stream_get_contents($fp);
        echo "\xEF\xBB\xBF";
        echo $file;
        fclose($fp);
        exit;
    }

    /**
     * Deletes an existing Extension model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     *
     * @return Response
     * @throws Throwable
     * @throws StaleObjectException
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $isAssignedExtension = AdminMaster::findOne(['adm_mapped_extension' => $id]);
        if ($isAssignedExtension) {
            Yii::$app->session->setFlash('error', extensionModule::t('app', 'already_assigned'));
        } else {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                $model = $this->findModel($id);
                $trago_user_id = $model->trago_user_id;
                $this->findModel($id)->delete();
                Callsettings::deleteAll('em_id = :id', [':id' => $id]);

                if (Yii::$app->session->get('isTragofone') == 1 && $model->is_tragofone == '1' && !empty($model->trago_user_id)) {

                    $tragofoneApi = $this->callTragofoneApi('delete', $trago_user_id);

                    if ($tragofoneApi['status'] == true) {

                        $transaction->commit();
                        Yii::$app->session->setFlash('success', extensionModule::t('app', 'deleted_successfully'));
                    } else {

                        $transaction->rollBack();
                        //Yii::$app->session->setFlash('error', extensionModule::t('app', 'something_wrong'));
                        Yii::$app->session->setFlash('error', $tragofoneApi['msg']);
                        return $this->redirect(['index', 'page' => Yii::$app->session->get('page')]);
                    }
                } else {
                    $transaction->commit();
                    Yii::$app->session->setFlash('success', extensionModule::t('app', 'deleted_successfully'));
                }
            } catch (Exception $e) {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', $e->getMessage());
                return $this->redirect(['index', 'page' => Yii::$app->session->get('page')]);
            }
        }

        return $this->redirect(['index', 'page' => Yii::$app->session->get('page')]);
    }

    /**
     * @return string|Response
     * @throws InvalidArgumentException
     */
    public function actionImport()
    {
         $licenseData = Yii::$app->commonHelper->getLicenseData($_SERVER['HTTP_HOST']);
        if(!empty($licenseData)) {
            $maxExtension = $licenseData['maxExtensions'];
            $totalExtension = Extension::find()->count();
            if ($totalExtension >= $maxExtension) {
                Yii::$app->session->setFlash('error', Yii::t('app', 'license_limit_exceed'));
                return $this->redirect(['index']);
            }
        }
        $searchModel = new ExtensionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        Yii::$app->session->set('queryDid', $dataProvider->query);

        $importModel = new Extension();
        $importModel->setScenario('import');
        $transaction = Yii::$app->db->beginTransaction();

        try {
            if ($importModel->load(Yii::$app->request->post())) {
                $importModel->importFileUpload = UploadedFile::getInstance($importModel, 'importFileUpload');
                if ($importModel->validate(['importFileUpload'])) {
                    $this->saveCSVData($importModel);

                    $transaction->commit();

                    return $this->redirect(['index']);
                }
            }
        } catch (Exception $e) {
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', $e->getMessage());

            return $this->redirect(['index']);
        }

        return $this->render('import',
            [
                'importModel' => $importModel,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
    }

    /**
     * @param $importModel
     */
    private function saveCSVData($importModel)
    {
        $total_uploaded_numbers = 0;
        $total_faulty_numbers = 0;

        $fileExtension = $importModel->importFileUpload->getExtension();
        $tempName = $importModel->importFileUpload->tempName;
        $errors = $importModel->importFileUpload->error;

        $em_plan_id = (!empty($importModel->em_plan_id) ? $importModel->em_plan_id : 0);
        $em_shift_id = $importModel->em_shift_id;
        $em_group_id = $importModel->em_group_id;
        $em_extension_name = $importModel->em_extension_name;
        $em_extension_number = $importModel->em_extension_number;
        $em_password = $importModel->em_password;
        $em_web_password = $importModel->em_web_password;
        $em_status = $importModel->em_status;
        $em_language_id = $importModel->em_language_id;
        $em_email = $importModel->em_email;
        $em_timezone_id = $importModel->em_timezone_id;
        $is_phonebook = $importModel->is_phonebook;

        $totalLength = 0;

        $csvPath = Url::to(Yii::$app->params['tenantStorageFullPath'] . $GLOBALS['tenantID'] . '/csv/extension/');

        if (!is_dir($csvPath)) {
            Yii::$app->storageHelper->makeDirAndGivePermission($csvPath);
        }

        $fileName = 'failed_ext_' . time() . '.csv';
        $csvPath = $csvPath . $fileName;
        $csvUrl = Yii::$app->homeUrl . 'media/' . $GLOBALS['tenantID'] . '/csv/extension/' . $fileName;
        $handle2 = fopen($csvPath, "w");

        if ($errors == 0) {
            if (($fileExtension == "csv") && (!empty($tempName))) {
                $i = 0;
                $delimiter = ',';
                $data = [];
                $failedData = [];
                $headersArray = [];
                if (($handle = fopen($tempName, 'r')) !== false) {

                    while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
                        if ($i == 0) {
                            $headersArray = $row;

                            $row[] = 'Result';
                            fputcsv($handle2, $row);
                        }
                        if ($i != 0) {
                            $totalLength = count($row);
                            if (sizeof($row) == 9) {
                                $importArrayKeys = array_keys($importModel->import['basic_fields']);

                                foreach ($row as $key => $value) {
                                    if (isset($value)) {
                                        if (in_array('Extension Number', $headersArray)
                                            && in_array('SIP Password', $headersArray)
                                            && in_array('Extension Name', $headersArray)
                                            && in_array('Email', $headersArray)
                                            && in_array('Language', $headersArray)
                                            && in_array('Status', $headersArray)
                                            && in_array('Web Password', $headersArray)
                                            && in_array('Is Phonebook', $headersArray)
                                            && in_array('Timezone', $headersArray)
                                        ) {
                                            $data[$i][$importArrayKeys[$key]] = $value;

                                        }
                                    }
                                }
                            } else if (Yii::$app->session->get('isTragofone') == 0 && count($row) == 27) {
                                $importArrayKeys = array_keys($importModel->import['advanced_fields']);
                                foreach ($row as $key => $value) {
                                    if (isset($value)) {
                                        if (in_array('Extension Number', $headersArray)
                                            && in_array('SIP Password', $headersArray)
                                            && in_array('Extension Name', $headersArray)
                                            && in_array('Email', $headersArray)
                                            && in_array('Language', $headersArray)
                                            && in_array('Status', $headersArray)
                                            && in_array('Web Password', $headersArray)
                                            && in_array('Is Phonebook', $headersArray)
                                            && in_array('Timezone', $headersArray)
                                            && in_array('Bypass Media', $headersArray)
                                            && in_array('External CallerID', $headersArray)
                                            && in_array('Simultaneous External Call', $headersArray)
                                            && in_array('Forwarding', $headersArray)
                                            && in_array('Ring Timeout(sec.)', $headersArray)
                                            && in_array('Dial Timeout(sec.)', $headersArray)
                                            && in_array('Max Timeout(sec.)', $headersArray)
                                            && in_array('Video Calling', $headersArray)
                                            && in_array('Extension Auto Recording', $headersArray)
                                            && in_array('DTMF Type', $headersArray)
                                            && in_array('Multiple Registration', $headersArray)
                                            && in_array('Dial Out', $headersArray)
                                            && in_array('Voice Mail', $headersArray)
                                            && in_array('VM Password', $headersArray)
                                            && in_array('Fax', $headersArray)
                                            && in_array('Active Audio Codecs', $headersArray)
                                            && in_array('Active Video Codecs', $headersArray)
                                            && in_array('DID', $headersArray)
                                        ) {
                                            $data[$i][$importArrayKeys[$key]] = $value;
                                        }
                                    }
                                }
                            } else if (Yii::$app->session->get('isTragofone') == 1 && count($row) == 28) {
                                $importArrayKeys = array_keys($importModel->import['trago_advanced_fields']);
                                foreach ($row as $key => $value) {
                                    if (isset($value)) {
                                        if (in_array('Extension Number', $headersArray)
                                            && in_array('SIP Password', $headersArray)
                                            && in_array('Extension Name', $headersArray)
                                            && in_array('Email', $headersArray)
                                            && in_array('Language', $headersArray)
                                            && in_array('Status', $headersArray)
                                            && in_array('Web Password', $headersArray)
                                            && in_array('Is Phonebook', $headersArray)
                                            && in_array('Timezone', $headersArray)
                                            && in_array('Bypass Media', $headersArray)
                                            && in_array('External CallerID', $headersArray)
                                            && in_array('Simultaneous External Call', $headersArray)
                                            && in_array('Forwarding', $headersArray)
                                            && in_array('Ring Timeout(sec.)', $headersArray)
                                            && in_array('Dial Timeout(sec.)', $headersArray)
                                            && in_array('Max Timeout(sec.)', $headersArray)
                                            && in_array('Video Calling', $headersArray)
                                            && in_array('Extension Auto Recording', $headersArray)
                                            && in_array('DTMF Type', $headersArray)
                                            && in_array('Multiple Registration', $headersArray)
                                            && in_array('Dial Out', $headersArray)
                                            && in_array('Instant Messaging', $headersArray)
                                            && in_array('Voice Mail', $headersArray)
                                            && in_array('VM Password', $headersArray)
                                            && in_array('Fax', $headersArray)
                                            && in_array('Active Audio Codecs', $headersArray)
                                            && in_array('Active Video Codecs', $headersArray)
                                            && in_array('DID', $headersArray)
                                        ) {
                                            $data[$i][$importArrayKeys[$key]] = $value;
                                        }
                                    }
                                }
                            }else {
                                $total_faulty_numbers++;
                                $failedData[$i] = $row;
                                $errorData = array_values($row);
                                $errorData[] = 'Failed..Records Mismatch';
                                fputcsv($handle2, $errorData);
                            }
                        }
                        $i++;
                    }

                    $data = array_values($data);
                    $csvPath = Url::to(Yii::$app->params['tenantStorageFullPath'] . $GLOBALS['tenantID'] . '/csv/');

                    if (!is_dir($csvPath)) {
                        Yii::$app->storageHelper->makeDirAndGivePermission($csvPath);
                    }

                    $failedData = array_values($failedData);
                    $lengthFail = count($failedData);
                    $total_uploaded_numbers = 0;
                    $total_faulty_numbers = $lengthFail;
                }

                $length = count($data);


                if ($length != 0) {
                    for ($i = 0; $i < $length; $i++) {
                        if ($totalLength == 9) {
                            $transaction = Yii::$app->db->beginTransaction();
                            try {
                                $em_extension_number = $data[$i]['em_extension_number'];
                                $em_password = $data[$i]['em_password']; // sip password
                                $em_extension_name = $data[$i]['em_extension_name'];
                                $em_email = $data[$i]['em_email'];
                                $em_language_id = (strtolower($data[$i]['em_language_id']) == 'spanish' ? '2' : (strtolower($data[$i]['em_language_id']) == 'english' ? '1' : $data[$i]['em_language_id']));

                                if($data[$i]['em_status'] != "0" && $data[$i]['em_status'] != "1") {
                                    $em_status = (!empty($data[$i]['em_status']) ? (strtolower($data[$i]['em_status']) == 'active' ? '1' : (strtolower($data[$i]['em_status']) == 'inactive' ? '0' : $data[$i]['em_status'])) : '1');
                                }else{
                                    $em_status = 'notvalid';
                                }
                                $em_web_password = $data[$i]['em_web_password'];
                                if($data[$i]['is_phonebook'] != "0" && $data[$i]['is_phonebook'] != "1") {
                                    $is_phonebook = (!empty($data[$i]['is_phonebook']) ? (strtolower($data[$i]['is_phonebook']) == 'active' ? '1' : (strtolower($data[$i]['is_phonebook']) == 'inactive' ? '0' : $data[$i]['is_phonebook'])) : '1');
                                }else{
                                    $is_phonebook = 'notvalid';
                                }
                                $em_timezone = $data[$i]['em_timezone_id'];
                                /** @var Timezone $timezoneModel */
                                if($em_timezone != "") {
                                    $timezoneModel = Timezone::find()->where(['tz_zone' => $em_timezone])->asArray()->one();
                                    if ($timezoneModel) {
                                        $em_timezone_id = $timezoneModel['tz_id'];
                                    } else {
                                        $em_timezone_id = 1000;
                                    }
                                }else{
                                    $em_timezone_id = '';
                                }

                                $model = new Extension();
                                $model->em_extension_name = $em_extension_name;
                                $model->em_extension_number = $em_extension_number;
                                $model->em_password = $em_password;
                                $model->em_plan_id = $em_plan_id;
                                $model->em_web_password = $em_web_password;
                                $model->em_status = $em_status;
                                $model->em_shift_id = $em_shift_id;
                                $model->em_group_id = $em_group_id;
                                $model->em_language_id = $em_language_id;
                                $model->em_email = $em_email;
                                $model->em_timezone_id = $em_timezone_id;
                                $model->is_phonebook = $is_phonebook;
                                if (Yii::$app->session->get('isTragofone') == 1) {
                                    $model->trago_username = Yii::$app->session->get('tenant_code') . $model->em_extension_number;
                                }
                                $model->em_moh = '';

                                if ($model->validate() && $model->save() && $em_timezone_id != 1000) {

                                    $callSettings = new Callsettings();
                                    $callSettings->em_id = $model->em_id;
                                    $callSettings->ecs_max_calls = '1';
                                    $callSettings->ecs_feature_code_pin = '12345';
                                    $callSettings->ecs_multiple_registeration = '1';
                                    $callSettings->ecs_fax2mail = '1';
                                    $callSettings->ecs_forwarding = '0';
                                    $callSettings->ecs_call_timeout = '60';
                                    $callSettings->ecs_audio_codecs = 'PCMA';
                                    $callSettings->ecs_im_status = '0';
                                    $callSettings->ecs_bypass_media = '0';
                                    $callSettings->ecs_ring_timeout = '60';
                                    $callSettings->ecs_ob_max_timeout = '3600';
                                    $callSettings->ecs_video_calling = '0';
                                    $callSettings->ecs_auto_recording = '0';
                                    $callSettings->ecs_dtmf_type = 'none';
                                    $callSettings->ecs_dial_out = '1';
                                    $callSettings->ecs_voicemail = '1';
                                    if ($callSettings->validate() && $callSettings->save()) {
                                        if (Yii::$app->session->get('isTragofone') == 1) {
                                            $tragofoneApi = $this->callTragofoneApi('create', $model, $callSettings);
                                            if ($tragofoneApi['status'] == true) {
                                                $model->trago_user_id = $tragofoneApi['user_id'];
                                                $model->is_tragofone = 1;
                                                $model->save();
                                                $transaction->commit();

                                                $total_uploaded_numbers++;
                                                $dataCsv = array_values($data[$i]);
                                                $dataCsv[] = Yii::t('app', 'success');
                                                fputcsv($handle2, $dataCsv);

                                            } else {
                                                $transaction->rollBack();
                                                $errorData = array_values($data[$i]);
                                                $errorData[] = $tragofoneApi['msg'];

                                                fputcsv($handle2, $errorData);

                                                $total_faulty_numbers++;
                                            }
                                        } else {
                                            $transaction->commit();

                                            $total_uploaded_numbers++;
                                            $dataCsv = array_values($data[$i]);
                                            $dataCsv[] = Yii::t('app', 'success');
                                            fputcsv($handle2, $dataCsv);
                                        }
                                    } else {
                                        $transaction->rollBack();
                                        $errorData = array_values($data[$i]);
                                        $errorData[] = implode(', ', call_user_func_array('array_merge', array_values($callSettings->getErrors())));

                                        fputcsv($handle2, $errorData);

                                        $total_faulty_numbers++;
                                    }

                                } else {
                                    $transaction->rollBack();
                                    $errorValue = $model->getErrors();
                                    if($em_timezone_id == 1000){
                                        $errorValue[] =  ['Timezone is not valid'];
                                    }
                                    $errorData = array_values($data[$i]);
                                    $errorData[] = implode(', ', call_user_func_array('array_merge', array_values($errorValue)));


                                    fputcsv($handle2, $errorData);

                                    $total_faulty_numbers++;
                                }
                            } catch (\Exception $e) {
                                $transaction->rollBack();
                                $total_faulty_numbers++;
                                $errorValue[] = [$e->getMessage()];
                                $errorData = array_values($data[$i]);
                                $errorData[] = implode(', ', call_user_func_array('array_merge', array_values($errorValue)));
                                fputcsv($handle2, $errorData);
                            }

                        } elseif (Yii::$app->session->get('isTragofone') == 0 && $totalLength == 27) {
                            $transaction = Yii::$app->db->beginTransaction();
                            try {
                                $em_extension_number = $data[$i]['em_extension_number'];
                                $em_password = $data[$i]['em_password']; // sip password
                                $em_extension_name = $data[$i]['em_extension_name'];
                                $em_email = $data[$i]['em_email'];
                                $em_language_id = (strtolower($data[$i]['em_language_id']) == 'spanish' ? '2' : (strtolower($data[$i]['em_language_id']) == 'english' ? '1' : $data[$i]['em_language_id']));
                                if($data[$i]['em_status'] != "0" && $data[$i]['em_status'] != "1") {
                                    $em_status = (!empty($data[$i]['em_status']) ? (strtolower($data[$i]['em_status']) == 'active' ? '1' : (strtolower($data[$i]['em_status']) == 'inactive' ? '0' : $data[$i]['em_status'])) : '1');
                                }else{
                                    $em_status = 'notvalid';
                                }
                                $em_web_password = $data[$i]['em_web_password'];
                                if($data[$i]['is_phonebook'] != "0" && $data[$i]['is_phonebook'] != "1") {
                                    $is_phonebook = (!empty($data[$i]['is_phonebook']) ? (strtolower($data[$i]['is_phonebook']) == 'active' ? '1' : (strtolower($data[$i]['is_phonebook']) == 'inactive' ? '0' : $data[$i]['is_phonebook'])) : '1');
                                }else{
                                    $is_phonebook = 'notvalid';
                                }
                                $em_timezone = $data[$i]['em_timezone_id'];
                                /** @var Timezone $timezoneModel */
                                if($em_timezone != "") {
                                    $timezoneModel = Timezone::find()->where(['tz_zone' => $em_timezone])->asArray()->one();
                                    if ($timezoneModel) {
                                        $em_timezone_id = $timezoneModel['tz_id'];
                                    } else {
                                        $em_timezone_id = 1000;
                                    }
                                }else{
                                    $em_timezone_id = '';
                                }
                                $external_caller_id = $data[$i]['external_caller_id'];
                                $didErrorCount = 0;
                                $didError = '';
                                if(!empty($data[$i]['did'])) {
                                    $didNumber = explode(',', $data[$i]['did']);
                                    foreach($didNumber as $_didNumber) {
                                        $did = DidManagement::find()->where(['did_number' => $_didNumber])->all();
                                        if (empty($did)) {
                                            $didErrorCount++;
                                            $didError = 'didNumberNotExists';
                                        } else {
                                            $did = DidManagement::find()
                                                ->where(['action_id' => '1', 'did_number' => $_didNumber])
                                                ->all();
                                            if (!empty($did)) {
                                                $didErrorCount++;
                                                $didError = 'didAlreadyAssignToOtherExtension';
                                            }
                                        }
                                    }
                                }

                                if($data[$i]['ecs_bypass_media'] != "0" && $data[$i]['ecs_bypass_media'] != "1" && $data[$i]['ecs_bypass_media'] != "2" && $data[$i]['ecs_bypass_media'] != "3") {
                                    $ecs_bypass_media = (strtolower($data[$i]['ecs_bypass_media']) == 'no' ? '0' : (strtolower($data[$i]['ecs_bypass_media']) == 'bypass' ? '1' : (strtolower($data[$i]['ecs_bypass_media']) == 'bypass after bridge' ? '2' : (strtolower($data[$i]['ecs_bypass_media']) == 'proxy media' ? '3' : $data[$i]['ecs_bypass_media']))));
                                }else{
                                    $ecs_bypass_media = 'notvalid';
                                }
                                $ecs_max_calls = $data[$i]['ecs_max_calls'];
                                if(strtolower($data[$i]['ecs_forwarding']) == "disable" || strtolower($data[$i]['ecs_forwarding']) == "enable" || (empty($data[$i]['ecs_forwarding']) && $data[$i]['ecs_forwarding'] != "0")) {
                                    $ecs_forwarding = (strtolower($data[$i]['ecs_forwarding']) == 'disable' ? '0' : (strtolower($data[$i]['ecs_forwarding']) == 'enable' ? '3' : $data[$i]['ecs_forwarding']));
                                }else{
                                    $ecs_forwarding = 'notvalid';
                                }
                                $ecs_ring_timeout = empty($data[$i]['ecs_ring_timeout']) ? '60' : $data[$i]['ecs_ring_timeout'];
                                $ecs_call_timeout = $data[$i]['ecs_call_timeout'];
                                $ecs_ob_max_timeout = empty($data[$i]['ecs_ob_max_timeout']) ? '3600' : $data[$i]['ecs_ob_max_timeout'];
                                if($data[$i]['ecs_video_calling'] != "0" && $data[$i]['ecs_video_calling'] != "1") {
                                    $ecs_video_calling = (!empty($data[$i]['ecs_video_calling']) ? (strtolower($data[$i]['ecs_video_calling']) == 'active' ? '1' : (strtolower($data[$i]['ecs_video_calling']) == 'inactive' ? '0' : $data[$i]['ecs_video_calling'])) : '0');
                                }else{
                                    $ecs_video_calling = 'notvalid';
                                }
                                if($data[$i]['ecs_auto_recording'] != "0" && $data[$i]['ecs_auto_recording'] != "1" && $data[$i]['ecs_auto_recording'] != "2" && $data[$i]['ecs_auto_recording'] != "3") {
                                    $ecs_auto_recording = (!empty($data[$i]['ecs_auto_recording']) ? (strtolower($data[$i]['ecs_auto_recording']) == 'disable' ? '0' : (strtolower($data[$i]['ecs_auto_recording']) == 'all' ? '1' : (strtolower($data[$i]['ecs_auto_recording']) == 'internal' ? '2' : (strtolower($data[$i]['ecs_auto_recording']) == 'external' ? '3' : $data[$i]['ecs_auto_recording'])))) : '0');
                                }else{
                                    $ecs_auto_recording = 'notvalid';
                                }
                                $ecs_dtmf_type = (!empty($data[$i]['ecs_dtmf_type']) ? (strtolower($data[$i]['ecs_dtmf_type']) == 'in band' ? 'none' : (strtolower($data[$i]['ecs_dtmf_type']) == 'rfc2833' ? 'rfc2833' : (strtolower($data[$i]['ecs_dtmf_type']) == 'sip info' ? 'info' : $data[$i]['ecs_dtmf_type']))) : 'none');
                                if($data[$i]['ecs_multiple_registeration'] != "0" && $data[$i]['ecs_multiple_registeration'] != "1") {
                                    $ecs_multiple_registration = (!empty($data[$i]['ecs_multiple_registeration']) ? (strtolower($data[$i]['ecs_multiple_registeration']) == 'active' ? '1' : (strtolower($data[$i]['ecs_multiple_registeration']) == 'inactive' ? '0' : $data[$i]['ecs_multiple_registeration'])) : '1');
                                }else{
                                    $ecs_multiple_registration = 'notvalid';
                                }
                                if($data[$i]['ecs_dial_out'] != "0" && $data[$i]['ecs_dial_out'] != "1") {
                                    $ecs_dial_out = (!empty($data[$i]['ecs_dial_out']) ? (strtolower($data[$i]['ecs_dial_out']) == 'active' ? '1' : (strtolower($data[$i]['ecs_dial_out']) == 'inactive' ? '0' : $data[$i]['ecs_dial_out'])) : '1');
                                }else{
                                    $ecs_dial_out = 'notvalid';
                                }
                                if($data[$i]['ecs_voicemail'] != "0" && $data[$i]['ecs_voicemail'] != "1") {
                                    $ecs_voicemail = (!empty($data[$i]['ecs_voicemail']) ? (strtolower($data[$i]['ecs_voicemail']) == 'active' ? '1' : (strtolower($data[$i]['ecs_voicemail']) == 'inactive' ? '0' : $data[$i]['ecs_voicemail'])) : '1');
                                }else{
                                    $ecs_voicemail = 'notvalid';
                                }
                                $ecs_voicemail_password = $data[$i]['ecs_voicemail_password'];
                                if($data[$i]['ecs_fax2mail'] != "0" && $data[$i]['ecs_fax2mail'] != "1") {
                                    $ecs_fax2mail = (!empty($data[$i]['ecs_fax2mail']) ? (strtolower($data[$i]['ecs_fax2mail']) == 'active' ? '1' : (strtolower($data[$i]['ecs_fax2mail']) == 'inactive' ? '0' : $data[$i]['ecs_fax2mail'])) : '1');
                                }else{
                                    $ecs_fax2mail = 'notvalid';
                                }
                                $ecs_audio_codecs = $data[$i]['ecs_audio_codecs'];
                                $ecs_video_codecs = $data[$i]['ecs_video_codecs'];

                                $model = new Extension();
                                $model->em_extension_name = $em_extension_name;
                                $model->em_extension_number = $em_extension_number;
                                $model->em_password = $em_password;
                                $model->em_plan_id = $em_plan_id;
                                $model->em_web_password = $em_web_password;
                                $model->em_status = $em_status;
                                $model->em_shift_id = $em_shift_id;
                                $model->em_group_id = $em_group_id;
                                $model->em_language_id = $em_language_id;
                                $model->em_email = $em_email;
                                $model->em_timezone_id = $em_timezone_id;
                                $model->is_phonebook = $is_phonebook;
                                $model->em_moh = '';
                                $model->external_caller_id = $external_caller_id;

                                if ($model->validate() && $model->save() && $em_timezone_id != 1000 && $didErrorCount == 0) {

                                    Yii::$app->db->createCommand()
                                        ->update('ct_did_master', (['action_id' => '1', 'action_value' => $model->em_id]), ['IN', 'did_number', explode(',', $data[$i]['did'])])
                                        ->execute();

                                    $audioError = $vidioError = 0;
                                    if(!empty($ecs_audio_codecs)) {
                                        $audioArray = ['PCMA', 'PCMU', 'G722', 'GSM', 'G729'];
                                        $audioData = explode(',', $data[$i]['ecs_audio_codecs']);
                                        if (!empty($audioData)) {
                                            foreach($audioData as $_audioData) {
                                                if (!in_array($_audioData, $audioArray)){
                                                    $audioError++;
                                                }
                                            }
                                        }
                                    }

                                    if(!empty($ecs_video_codecs)) {
                                        $videoArray = ['VP8', 'H264'];
                                        $videoData = explode(',', $data[$i]['ecs_video_codecs']);
                                        if (!empty($videoData)) {
                                            foreach($videoData as $_videoData) {
                                                if (!in_array($_videoData, $videoArray)){
                                                    $vidioError++;
                                                }
                                            }
                                        }
                                    }

                                    $callSettings = new Callsettings();
                                    $callSettings->em_id = $model->em_id;
                                    $callSettings->ecs_feature_code_pin = '12345';
                                    $callSettings->ecs_max_calls = $ecs_max_calls;
                                    $callSettings->ecs_ring_timeout = $ecs_ring_timeout;
                                    $callSettings->ecs_call_timeout = $ecs_call_timeout;
                                    $callSettings->ecs_ob_max_timeout = $ecs_ob_max_timeout;
                                    $callSettings->ecs_auto_recording = $ecs_auto_recording;
                                    $callSettings->ecs_dtmf_type = $ecs_dtmf_type;
                                    $callSettings->ecs_video_calling = $ecs_video_calling;
                                    $callSettings->ecs_bypass_media = $ecs_bypass_media;
                                    $callSettings->ecs_audio_codecs = (!empty($ecs_audio_codecs) ? $ecs_audio_codecs : '');
                                    $callSettings->ecs_video_codecs = (!empty($ecs_video_codecs) ? $ecs_video_codecs : '');
                                    $callSettings->ecs_dial_out = $ecs_dial_out;
                                    $callSettings->ecs_forwarding = $ecs_forwarding;
                                    $callSettings->ecs_voicemail = $ecs_voicemail;
                                    $callSettings->ecs_voicemail_password = (!empty($ecs_voicemail_password) ? $ecs_voicemail_password : '');
                                    $callSettings->ecs_fax2mail = $ecs_fax2mail;
                                    $callSettings->ecs_multiple_registeration = $ecs_multiple_registration;
                                    $callSettings->ecs_moh = "";
                                    $callSettings->ecs_im_status = '0';

                                    if ($callSettings->validate() && $callSettings->save() && $audioError == 0 && $vidioError == 0 && $ecs_forwarding != 'notvalid') {
                                        $transaction->commit();
                                        $total_uploaded_numbers++;

                                        $dataCsv = array_values($data[$i]);
                                        $dataCsv[] = Yii::t('app', 'success');
                                        fputcsv($handle2, $dataCsv);

                                    } else {
                                        $transaction->rollBack();
                                        $errorValue = $callSettings->getErrors();
                                        if($audioError > 0){
                                            $errorValue[] =  [Yii::t('app', 'extAudioCodecError')];
                                        }
                                        if($vidioError > 0){
                                            $errorValue[] =  [Yii::t('app', 'extVideoCodecError')];
                                        }
                                        if($ecs_forwarding == 'notvalid'){
                                            $errorValue[] =  [Yii::t('app', 'extFwdError')];
                                        }
                                        $errorData = array_values($data[$i]);
                                        $errorData[] = implode(', ', call_user_func_array('array_merge', array_values($errorValue)));

                                        fputcsv($handle2, $errorData);

                                        $total_faulty_numbers++;
                                    }

                                } else {
                                    $transaction->rollBack();
                                    $errorValue = $model->getErrors();
                                    if($em_timezone_id == 1000){
                                        $errorValue[] =  [Yii::t('app', 'extTimezoneError')];
                                    }
                                    if($didErrorCount > 0){
                                        $errorValue[] =  [Yii::t('app', $didError)];
                                    }
                                    $errorData = array_values($data[$i]);
                                    $errorData[] = implode(', ', call_user_func_array('array_merge', array_values($errorValue)));

                                    fputcsv($handle2, $errorData);

                                    $total_faulty_numbers++;
                                }

                            } catch (\Exception $e) {
                                $transaction->rollBack();
                                $total_faulty_numbers++;
                                $errorValue[] = [$e->getMessage()];
                                $errorData = array_values($data[$i]);
                                $errorData[] = implode(', ', call_user_func_array('array_merge', array_values($errorValue)));
                                fputcsv($handle2, $errorData);
                            }
                        }elseif (Yii::$app->session->get('isTragofone') == 1 && $totalLength == 28) {
                            $transaction = Yii::$app->db->beginTransaction();
                            try {
                                $em_extension_number = $data[$i]['em_extension_number'];
                                $em_password = $data[$i]['em_password']; // sip password
                                $em_extension_name = $data[$i]['em_extension_name'];
                                $em_email = $data[$i]['em_email'];
                                $em_language_id = (strtolower($data[$i]['em_language_id']) == 'spanish' ? '2' : (strtolower($data[$i]['em_language_id']) == 'english' ? '1' : $data[$i]['em_language_id']));
                                if($data[$i]['em_status'] != "0" && $data[$i]['em_status'] != "1") {
                                    $em_status = (!empty($data[$i]['em_status']) ? (strtolower($data[$i]['em_status']) == 'active' ? '1' : (strtolower($data[$i]['em_status']) == 'inactive' ? '0' : $data[$i]['em_status'])) : '1');
                                }else{
                                    $em_status = 'notvalid';
                                }
                                $em_web_password = $data[$i]['em_web_password'];
                                if($data[$i]['is_phonebook'] != "0" && $data[$i]['is_phonebook'] != "1") {
                                    $is_phonebook = (!empty($data[$i]['is_phonebook']) ? (strtolower($data[$i]['is_phonebook']) == 'active' ? '1' : (strtolower($data[$i]['is_phonebook']) == 'inactive' ? '0' : $data[$i]['is_phonebook'])) : '1');
                                }else{
                                    $is_phonebook = 'notvalid';
                                }
                                $em_timezone = $data[$i]['em_timezone_id'];
                                /** @var Timezone $timezoneModel */
                                if($em_timezone != "") {
                                    $timezoneModel = Timezone::find()->where(['tz_zone' => $em_timezone])->asArray()->one();
                                    if ($timezoneModel) {
                                        $em_timezone_id = $timezoneModel['tz_id'];
                                    } else {
                                        $em_timezone_id = 1000;
                                    }
                                }else{
                                    $em_timezone_id = '';
                                }
                                $external_caller_id = $data[$i]['external_caller_id'];

                                $didErrorCount = 0;
                                $didError = '';
                                if(!empty($data[$i]['did'])) {
                                    $didNumber = explode(',', $data[$i]['did']);
                                    foreach($didNumber as $_didNumber) {
                                        $did = DidManagement::find()->where(['did_number' => $_didNumber])->all();
                                        if (empty($did)) {
                                            $didErrorCount++;
                                            $didError = 'didNumberNotExists';
                                        } else {
                                            $did = DidManagement::find()
                                                ->where(['action_id' => '1', 'did_number' => $_didNumber])
                                                ->all();
                                            if (!empty($did)) {
                                                $didErrorCount++;
                                                $didError = 'didAlreadyAssignToOtherExtension';
                                            }
                                        }
                                    }
                                }

                                if($data[$i]['ecs_bypass_media'] != "0" && $data[$i]['ecs_bypass_media'] != "1" && $data[$i]['ecs_bypass_media'] != "2" && $data[$i]['ecs_bypass_media'] != "3") {
                                    $ecs_bypass_media = (strtolower($data[$i]['ecs_bypass_media']) == 'no' ? '0' : (strtolower($data[$i]['ecs_bypass_media']) == 'bypass' ? '1' : (strtolower($data[$i]['ecs_bypass_media']) == 'bypass after bridge' ? '2' : (strtolower($data[$i]['ecs_bypass_media']) == 'proxy media' ? '3' : $data[$i]['ecs_bypass_media']))));
                                }else{
                                    $ecs_bypass_media = 'notvalid';
                                }
                                $ecs_max_calls = $data[$i]['ecs_max_calls'];
                                if(strtolower($data[$i]['ecs_forwarding']) == "disable" || strtolower($data[$i]['ecs_forwarding']) == "enable" || (empty($data[$i]['ecs_forwarding']) && $data[$i]['ecs_forwarding'] != "0")) {
                                    $ecs_forwarding = (strtolower($data[$i]['ecs_forwarding']) == 'disable' ? '0' : (strtolower($data[$i]['ecs_forwarding']) == 'enable' ? '3' : $data[$i]['ecs_forwarding']));
                                }else{
                                    $ecs_forwarding = 'notvalid';
                                }
                                $ecs_ring_timeout = empty($data[$i]['ecs_ring_timeout']) ? '60' : $data[$i]['ecs_ring_timeout'];
                                $ecs_call_timeout = $data[$i]['ecs_call_timeout'];
                                $ecs_ob_max_timeout = empty($data[$i]['ecs_ob_max_timeout']) ? '3600' : $data[$i]['ecs_ob_max_timeout'];
                                if($data[$i]['ecs_video_calling'] != "0" && $data[$i]['ecs_video_calling'] != "1") {
                                    $ecs_video_calling = (!empty($data[$i]['ecs_video_calling']) ? (strtolower($data[$i]['ecs_video_calling']) == 'active' ? '1' : (strtolower($data[$i]['ecs_video_calling']) == 'inactive' ? '0' : $data[$i]['ecs_video_calling'])) : '0');
                                }else{
                                    $ecs_video_calling = 'notvalid';
                                }
                                if($data[$i]['ecs_auto_recording'] != "0" && $data[$i]['ecs_auto_recording'] != "1" && $data[$i]['ecs_auto_recording'] != "2" && $data[$i]['ecs_auto_recording'] != "3") {
                                    $ecs_auto_recording = (!empty($data[$i]['ecs_auto_recording']) ? (strtolower($data[$i]['ecs_auto_recording']) == 'disable' ? '0' : (strtolower($data[$i]['ecs_auto_recording']) == 'all' ? '1' : (strtolower($data[$i]['ecs_auto_recording']) == 'internal' ? '2' : (strtolower($data[$i]['ecs_auto_recording']) == 'external' ? '3' : $data[$i]['ecs_auto_recording'])))) : '0');
                                }else{
                                    $ecs_auto_recording = 'notvalid';
                                }
                                $ecs_dtmf_type = (!empty($data[$i]['ecs_dtmf_type']) ? (strtolower($data[$i]['ecs_dtmf_type']) == 'in band' ? 'none' : (strtolower($data[$i]['ecs_dtmf_type']) == 'rfc2833' ? 'rfc2833' : (strtolower($data[$i]['ecs_dtmf_type']) == 'sip info' ? 'info' : $data[$i]['ecs_dtmf_type']))) : 'none');
                                if($data[$i]['ecs_multiple_registeration'] != "0" && $data[$i]['ecs_multiple_registeration'] != "1") {
                                    $ecs_multiple_registration = (!empty($data[$i]['ecs_multiple_registeration']) ? (strtolower($data[$i]['ecs_multiple_registeration']) == 'active' ? '1' : (strtolower($data[$i]['ecs_multiple_registeration']) == 'inactive' ? '0' : $data[$i]['ecs_multiple_registeration'])) : '1');
                                }else{
                                    $ecs_multiple_registration = 'notvalid';
                                }
                                if($data[$i]['ecs_dial_out'] != "0" && $data[$i]['ecs_dial_out'] != "1") {
                                    $ecs_dial_out = (!empty($data[$i]['ecs_dial_out']) ? (strtolower($data[$i]['ecs_dial_out']) == 'active' ? '1' : (strtolower($data[$i]['ecs_dial_out']) == 'inactive' ? '0' : $data[$i]['ecs_dial_out'])) : '1');
                                }else{
                                    $ecs_dial_out = 'notvalid';
                                }
                                if($data[$i]['ecs_voicemail'] != "0" && $data[$i]['ecs_voicemail'] != "1") {
                                    $ecs_voicemail = (!empty($data[$i]['ecs_voicemail']) ? (strtolower($data[$i]['ecs_voicemail']) == 'active' ? '1' : (strtolower($data[$i]['ecs_voicemail']) == 'inactive' ? '0' : $data[$i]['ecs_voicemail'])) : '1');
                                }else{
                                    $ecs_voicemail = 'notvalid';
                                }
                                $ecs_voicemail_password = $data[$i]['ecs_voicemail_password'];
                                if($data[$i]['ecs_fax2mail'] != "0" && $data[$i]['ecs_fax2mail'] != "1") {
                                    $ecs_fax2mail = (!empty($data[$i]['ecs_fax2mail']) ? (strtolower($data[$i]['ecs_fax2mail']) == 'active' ? '1' : (strtolower($data[$i]['ecs_fax2mail']) == 'inactive' ? '0' : $data[$i]['ecs_fax2mail'])) : '1');
                                }else{
                                    $ecs_fax2mail = 'notvalid';
                                }
                                $ecs_audio_codecs = $data[$i]['ecs_audio_codecs'];
                                $ecs_video_codecs = $data[$i]['ecs_video_codecs'];
                                if($data[$i]['ecs_im_status'] != "0" && $data[$i]['ecs_im_status'] != "1") {
                                    $ecs_im_status = (!empty($data[$i]['ecs_im_status']) ? (strtolower($data[$i]['ecs_im_status']) == 'active' ? '1' : (strtolower($data[$i]['ecs_im_status']) == 'inactive' ? '0' : $data[$i]['ecs_im_status'])) : '0');
                                }else{
                                    $ecs_im_status = 'notvalid';
                                }

                                $model = new Extension();
                                $model->em_extension_name = $em_extension_name;
                                $model->em_extension_number = $em_extension_number;
                                $model->em_password = $em_password;
                                $model->em_plan_id = $em_plan_id;
                                $model->em_web_password = $em_web_password;
                                $model->em_status = $em_status;
                                $model->em_shift_id = $em_shift_id;
                                $model->em_group_id = $em_group_id;
                                $model->em_language_id = $em_language_id;
                                $model->em_email = $em_email;
                                $model->em_timezone_id = $em_timezone_id;
                                $model->is_phonebook = $is_phonebook;
                                $model->trago_username = Yii::$app->session->get('tenant_code') . $model->em_extension_number;
                                $model->em_moh = '';
                                $model->external_caller_id = $external_caller_id;

                                if ($model->validate() && $model->save() && $em_timezone_id != 1000 && $didErrorCount == 0) {

                                    Yii::$app->db->createCommand()
                                        ->update('ct_did_master', (['action_id' => '1', 'action_value' => $model->em_id]), ['IN', 'did_number', explode(',', $data[$i]['did'])])
                                        ->execute();

                                    $audioError = $vidioError = 0;
                                    if(!empty($ecs_audio_codecs)) {
                                        $audioArray = ['PCMA', 'PCMU', 'G722', 'GSM', 'G729'];
                                        $audioData = explode(',', $data[$i]['ecs_audio_codecs']);
                                        if (!empty($audioData)) {
                                            foreach($audioData as $_audioData) {
                                                if (!in_array($_audioData, $audioArray)){
                                                    $audioError++;
                                                }
                                            }
                                        }
                                    }

                                    if(!empty($ecs_video_codecs)) {
                                        $videoArray = ['VP8', 'H264'];
                                        $videoData = explode(',', $data[$i]['ecs_video_codecs']);
                                        if (!empty($videoData)) {
                                            foreach($videoData as $_videoData) {
                                                if (!in_array($_videoData, $videoArray)){
                                                    $vidioError++;
                                                }
                                            }
                                        }
                                    }

                                    $callSettings = new Callsettings();
                                    $callSettings->em_id = $model->em_id;
                                    $callSettings->ecs_feature_code_pin = '12345';
                                    $callSettings->ecs_max_calls = $ecs_max_calls;
                                    $callSettings->ecs_ring_timeout = $ecs_ring_timeout;
                                    $callSettings->ecs_call_timeout = $ecs_call_timeout;
                                    $callSettings->ecs_ob_max_timeout = $ecs_ob_max_timeout;
                                    $callSettings->ecs_auto_recording = $ecs_auto_recording;
                                    $callSettings->ecs_dtmf_type = $ecs_dtmf_type;
                                    $callSettings->ecs_video_calling = $ecs_video_calling;
                                    $callSettings->ecs_bypass_media = $ecs_bypass_media;
                                    $callSettings->ecs_audio_codecs = (!empty($ecs_audio_codecs) ? $ecs_audio_codecs : 'PCMA');
                                    $callSettings->ecs_video_codecs = (!empty($ecs_video_codecs) ? $ecs_video_codecs : '');
                                    $callSettings->ecs_dial_out = $ecs_dial_out;
                                    $callSettings->ecs_forwarding = $ecs_forwarding;
                                    $callSettings->ecs_voicemail = $ecs_voicemail;
                                    $callSettings->ecs_voicemail_password = (!empty($ecs_voicemail_password) ? $ecs_voicemail_password : '');
                                    $callSettings->ecs_fax2mail = $ecs_fax2mail;
                                    $callSettings->ecs_multiple_registeration = $ecs_multiple_registration;
                                    $callSettings->ecs_moh = "";
                                    $callSettings->ecs_im_status = $ecs_im_status;

                                    if ($callSettings->validate() && $callSettings->save() && $audioError == 0 && $vidioError == 0 && $ecs_forwarding != 'notvalid') {
                                        if (Yii::$app->session->get('isTragofone') == 1) {
                                            $tragofoneApi = $this->callTragofoneApi('create', $model, $callSettings);
                                            if ($tragofoneApi['status'] == true) {
                                                $model->trago_user_id = $tragofoneApi['user_id'];
                                                $model->is_tragofone = 1;
                                                $model->save();
                                                $transaction->commit();

                                                $total_uploaded_numbers++;
                                                $dataCsv = array_values($data[$i]);
                                                $dataCsv[] = Yii::t('app', 'success');
                                                fputcsv($handle2, $dataCsv);

                                            } else {
                                                $transaction->rollBack();
                                                $errorData = array_values($data[$i]);
                                                $errorData[] = $tragofoneApi['msg'];

                                                fputcsv($handle2, $errorData);

                                                $total_faulty_numbers++;
                                            }
                                        } else {
                                            $transaction->commit();

                                            $total_uploaded_numbers++;
                                            $dataCsv = array_values($data[$i]);
                                            $dataCsv[] = Yii::t('app', 'success');
                                            fputcsv($handle2, $dataCsv);
                                        }

                                    } else {
                                        $transaction->rollBack();
                                        $errorValue = $callSettings->getErrors();
                                        if($audioError > 0){
                                            $errorValue[] =  [Yii::t('app', 'extAudioCodecError')];
                                        }
                                        if($vidioError > 0){
                                            $errorValue[] =  [Yii::t('app', 'extVideoCodecError')];
                                        }
                                        if($ecs_forwarding == 'notvalid'){
                                            $errorValue[] =  [Yii::t('app', 'extFwdError')];
                                        }
                                        $errorData = array_values($data[$i]);
                                        $errorData[] = implode(', ', call_user_func_array('array_merge', array_values($errorValue)));

                                        fputcsv($handle2, $errorData);

                                        $total_faulty_numbers++;
                                    }

                                } else {
                                    $transaction->rollBack();
                                    $errorValue = $model->getErrors();
                                    if($em_timezone_id == 1000){
                                        $errorValue[] =  [Yii::t('app', 'extTimezoneError')];
                                    }
                                    if($didErrorCount > 0){
                                        $errorValue[] =  [Yii::t('app', $didError)];
                                    }
                                    $errorData = array_values($data[$i]);
                                    $errorData[] = implode(', ', call_user_func_array('array_merge', array_values($errorValue)));

                                    fputcsv($handle2, $errorData);

                                    $total_faulty_numbers++;
                                }

                            } catch (\Exception $e) {
                                $transaction->rollBack();
                                $total_faulty_numbers++;
                                $errorValue[] = [$e->getMessage()];
                                $errorData = array_values($data[$i]);
                                $errorData[] = implode(', ', call_user_func_array('array_merge', array_values($errorValue)));
                                fputcsv($handle2, $errorData);
                            }
                        }
                    }
                }

                $msg = Yii::t('app', 'importSuccess');

                fclose($handle2);

                if ($total_faulty_numbers > 0) {
                    $msg = '<span style="display: inline-block;">' . '<a href="' . $csvUrl . '" style="color:#fff;">' . Yii::t('app', 'importFail', ['success' => $total_uploaded_numbers, 'fail' => $total_faulty_numbers]) . '</a></span>';
                } else if ($length == 0) {
                    $msg = '<span style="display: inline-block;">' . Yii::t('app', 'Imported Report Is Blank') . '</a></span>';
                }
                if ($length == 0 || $total_faulty_numbers > 0) {
                    Yii::$app->session->setFlash('errorimport', $msg);
                } else {
                    Yii::$app->session->setFlash('successimport', $msg);
                }
            }
        } else {
            Yii::$app->session->setFlash('errorimport', Yii::t('app', 'Invalid Format'));
        }
    }

    /**
     * @return void
     */
    public function actionDownloadBasicFile()
    {
       /* $model = new Extension();

        if ($model->import['basic_fields']) {
            foreach ($model->import['basic_fields'] as $k => $field) {

                $model->displayNames[$k] = $field['displayName'];
                $model->sampleValues[] = $field['sample'];
            }
        }

        $file = implode(',', $model->displayNames) . "\n" . implode(',', $model->sampleValues);

        header("Content-type:text/csv");
        header("Content-Disposition:attachment; filename=Sample-basic-extension-file.csv");
        echo $file;
        exit;*/
        header("Content-Type: text/csv");
        header("Content-Disposition: attachment; filename=Sample-basic-extension-file.csv");

        $output = fopen("php://output", "wb");
        $model = new Extension();

        if ($model->import['basic_fields']) {
            $header = $value = [];
            foreach ($model->import['basic_fields'] as $k => $field) {
                $header[$k] = $field['displayName'];
                $value[] = $field['sample'];
            }
            fputcsv($output, $header);
            fputcsv($output, $value);
        }
        fclose($output);
        exit;
    }

    /**
     * @return void
     */
    public function actionDownloadAdvancedFile()
    {
       /* $model = new Extension();
        if(Yii::$app->session->get('isTragofone') == 1){
            if ($model->import['trago_advanced_fields']) {
                foreach ($model->import['trago_advanced_fields'] as $k => $field) {

                    $model->displayNames[$k] = $field['displayName'];
                    $model->sampleValues[] = $field['sample'];
                }
            }
        }else {
            if ($model->import['advanced_fields']) {
                foreach ($model->import['advanced_fields'] as $k => $field) {

                    $model->displayNames[$k] = $field['displayName'];
                    $model->sampleValues[] = $field['sample'];
                }
            }
        }

        $file = implode(',', $model->displayNames) . "\n" . implode(',', $model->sampleValues);

        header("Content-type:text/csv");
        header("Content-Disposition:attachment; filename=Sample-advanced-extension-file.csv");
        echo $file;
        exit;*/

        header("Content-Type: text/csv");
        header("Content-Disposition: attachment; filename=Sample-advanced-extension-file.csv");

        $output = fopen("php://output", "wb");
        $model = new Extension();
        $header = $value = [];
        if(Yii::$app->session->get('isTragofone') == 1){
            if ($model->import['trago_advanced_fields']) {
                foreach ($model->import['trago_advanced_fields'] as $k => $field) {
                    $header[$k] = $field['displayName'];
                    $value[] = $field['sample'];
                }
            }
        }else {
            if ($model->import['advanced_fields']) {
                foreach ($model->import['advanced_fields'] as $k => $field) {
                    $header[$k] = $field['displayName'];
                    $value[] = $field['sample'];
                }
            }
        }
        fputcsv($output, $header);
        fputcsv($output, $value);
        fclose($output);
        exit;
    }

    /**
     * @return string|Response
     * @throws NotFoundHttpException
     */
    public function actionUpdateExtension()
    {

        $model = $this->findModelByExtensionNumber(Yii::$app->user->identity->em_extension_number);
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {

                if ($model->save(FALSE)) {
                    $call_setting_model = Callsettings::find()->where(['em_id' => $model->em_id])->one();

                    if (Yii::$app->session->get('isTragofone') == 1 && !empty($model->trago_user_id) && $model->is_tragofone == '1') {

                        $tragofoneApi = $this->callTragofoneApi('update', $model, $call_setting_model, $model->trago_user_id);

                        if ($tragofoneApi['status'] == true) {

                            $transaction->commit();
                            Yii::$app->session->setFlash('success', extensionModule::t('app', 'updated_successfully'));
                            return $this->redirect(['dashboard']);

                        } else {
                            $transaction->rollBack();
                            //Yii::$app->session->setFlash('error', extensionModule::t('app', 'something_wrong'));
                            Yii::$app->session->setFlash('error', $tragofoneApi['msg']);
                            return $this->render('updateExtension', ['model' => $model]);
                        }
                    } else {
                        $transaction->commit();
                        Yii::$app->session->setFlash('success', extensionModule::t('app', 'updated_successfully'));
                        return $this->redirect(['dashboard']);

                    }
                }
            }
        } catch (Exception $e) {
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', $e->getMessage());

            return $this->render('updateExtension', ['model' => $model]);
        }
        return $this->renderPartial('updateExtension', ['model' => $model]);
    }

    /**
     * @throws NotFoundHttpException
     */
    protected function findModelByExtensionNumber($extNumber)
    {
        if (($model = Extension::findOne(['em_extension_number' => $extNumber])) !== NULL) {
            return $model;
        } else {
            throw new NotFoundHttpException(extensionModule::t('extension', 'page_not_exit'));
        }
    }

    public function callTragofoneApi($method, $model, $call_setting_model = '', $user_id = '', $user_Status = 'Y')
    {
        $api = '';
        $return = ['status' => false, 'user_id' => '', 'msg' => ''];

        if ($method == 'create') {

            $data = [
                "usr_username" => $model->trago_username,
                "usr_password" => $model->em_web_password,
                "usr_email" => $model->em_email,
                "usr_account_name" => $model->em_extension_number,
                "sip_configurations" => [
                    "sip_auth_username" => $model->em_extension_number,
                    "sip_auth_password" => $model->em_password,
                    "sip_auth_sipServer" => $_SERVER['HTTP_HOST'],
                    "sip_auth_sipPort" => Yii::$app->params['TRAGO_SIP_PORT']
                ],
            ];

            $api = Yii::$app->tragofoneHelper->create($data);

        } elseif ($method == 'update') {
            $data = [
                "user_id" => $user_id,
                "usr_password" => $model->em_web_password,
                "usr_email" => $model->em_email,
                "usr_account_name" => $model->em_extension_number,
                "usr_status" => $user_Status,
            ];

            $api = Yii::$app->tragofoneHelper->update($data);

        } elseif ($method == 'delete') {
            $data = [
                "user_id" => $model
            ];
            $api = Yii::$app->tragofoneHelper->delete($data);
        }

        if (!empty($api)) {
            $api = json_decode($api, true);

            if ($api['status'] == 'SUCCESS') {

                $return['status'] = true;
                if ($method != 'delete') {
                    $return['user_id'] = $api['data']['usr_id'];

                    $configData = [
                        "user_id" => $api['data']['usr_id'],
                        "configurations" => [
                            "sip_auth_username" => $model->em_extension_number,
                            "sip_auth_password" => $model->em_password,
                            "sip_auth_sipServer" => $_SERVER['HTTP_HOST'],
                            "sip-auth-sipPort" => Yii::$app->params['TRAGO_SIP_PORT'],
                            "audio_audioCodecs" => (!empty($call_setting_model->ecs_audio_codecs) ? $call_setting_model->ecs_audio_codecs : 'PCMA'),
                            "video_videoCodecs" => ($call_setting_model->ecs_video_calling == '1' && empty($call_setting_model->ecs_video_codecs) ? ['VP8'] : explode(',', $call_setting_model->ecs_video_codecs)),
                            "video_enableVideo" => ($call_setting_model->ecs_video_calling == '1' ? "TRUE" : "FALSE"),
                            "call_noAnswerTimeout" => $call_setting_model->ecs_call_timeout,
                            "voicemail_status" =>  ($call_setting_model->ecs_voicemail == '1' ? "TRUE" : "FALSE"),
                            "im_status" => ($call_setting_model->ecs_im_status == '1' ? "TRUE" : "FALSE")
                        ],
                    ];

                    $configApi = Yii::$app->tragofoneHelper->updateConfig($configData);
                    $configApi = json_decode($configApi, true);
                    if ($configApi['status'] == 'SUCCESS') {
                        $return['status'] = true;
                    }else {
                        $return['status'] = false;
                        $return['msg'] = 'Tragofone API Message : ' . (isset($configApi['message']) ? $configApi['message'] : '');
                    }
                }
            }else{
                $return['msg'] = 'Tragofone API Message : '.(isset($api['message']) ? $api['message'] : '');
            }
        }

        return $return;
    }

    public function actionTragofone($id){
        $model = $this->findModel($id);
        $call_setting_model = Callsettings::find()->where(['em_id' => $id])->one();
        $isTragofone = ($model->is_tragofone == 1 ? 'N' : 'Y');
        if(Yii::$app->session->get('isTragofone') == 1) {
            if (empty($model->trago_user_id)) {
                $model->trago_username = Yii::$app->session->get('tenant_code').$model->em_extension_number;
                $tragofoneApi = $this->callTragofoneApi('create', $model, $call_setting_model);
                if ($tragofoneApi['status'] == true) {
                    $model->is_tragofone = ($isTragofone == 'N' ? '0' : '1');
                    $model->trago_user_id = $tragofoneApi['user_id'];
                    $model->save();
                    Yii::$app->session->setFlash('success', extensionModule::t('app', 'Tragofone Enabled Successfully'));
                } else {

                    //Yii::$app->session->setFlash('error', extensionModule::t('app', 'something_wrong'));
                    Yii::$app->session->setFlash('error', $tragofoneApi['msg']);
                }
            } else {

                $tragofoneApi = $this->callTragofoneApi('update', $model, $call_setting_model, $model->trago_user_id, $isTragofone);

                if ($tragofoneApi['status'] == true) {

                    $model->is_tragofone = ($isTragofone == 'N' ? '0' : '1');
                    $model->save();
                    if ($isTragofone == 'Y') {
                        Yii::$app->session->setFlash('success', extensionModule::t('app', 'Tragofone Enabled Successfully'));
                    } else {
                        Yii::$app->session->setFlash('success', extensionModule::t('app', 'Tragofone Disabled Successfully'));
                    }
                } else {

                    //Yii::$app->session->setFlash('error', extensionModule::t('app', 'something_wrong'));
                    Yii::$app->session->setFlash('error', $tragofoneApi['msg']);
                }

            }
        }else{
            Yii::$app->session->setFlash('error', 'Tragofone Status not enabled from service portal.');
        }

        return $this->redirect(['index', 'page' => Yii::$app->session->get('page')]);
    }

    public function actionGetData($id){
        $data = '';
        if($id == 1){
            $data = ExtensionCallLog::getAllCall();
        }elseif($id == 2){
            $data = ExtensionCallLog::getMissCall();
        }elseif($id == 3){
            $data = ExtensionCallLog::getIncomingCall();
        }elseif($id == 4){
            $data = ExtensionCallLog::getOutgoingCall();
        }
        return json_encode($data);
    }

    public function actionGetContacts()
    {
        $em_id = Yii::$app->user->identity->em_id;
        $callSetting_data = Callsettings::findOne(['em_id' => $em_id]);

        $videoCall = $callSetting_data->ecs_video_calling;
        // $videoCall = 0;

        $html = '';
        $phoneBook = EnterprisePhonebook::find()->all();

        if (!empty($phoneBook)) {
            foreach ($phoneBook as $_phoneBook) {
                $extensionNumber = '';
                $extension = Extension::findOne($_phoneBook->en_extension);

                if (!empty($_phoneBook->en_extension) && !empty($extension)) {
                    $phoneNumber = $extension->em_extension_number;
                    $extensionNumber = $extension->em_extension_number;
                } elseif ($_phoneBook->en_mobile) {
                    $phoneNumber = $_phoneBook->en_mobile;
                } else {
                    $phoneNumber = $_phoneBook->en_phone;
                }
                $html .= '<li>
                    <div class="collapsible-header">
                        <div class="call-lists">
                            <div class="user-initial">' . strtoupper(substr($_phoneBook->en_first_name, 0, 1)) . '</div>
                            <div class="caller-detail">
                                <div>
                                    <p class="caller-name">' . $_phoneBook->en_first_name . ' ' . $_phoneBook->en_last_name . '</p>
                                    <p class="contact-number">' . $phoneNumber . '</p>
                                </div>
                                <!--<div class="ml-auto">' . $phoneNumber . '</div>-->
                            </div>
                        </div>
                    </div>
                    <div class="collapsible-body">
                     <div class="call-opiton">';
                if (!empty($extensionNumber)) {
                    $html .= '
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="user-call-data">
                                <p class="mb-0">Extension</p>
                                <p>' . $extensionNumber . '</p>
                            </div>
                            <div class="user-call-icons" >
                                <i class="material-icons" id = "audioCall" data-number = "' . $extensionNumber . '" > local_phone</i >';
                    if ($videoCall) {
                        $html .= '<i class="material-icons dial-pad-open" id = "videoCall" data - number = "' . $extensionNumber . '" > videocam</i >';
                    } else {
                        $html .= '<i class="material-icons cursor-auto">videocam_off</i>';
                    }
                    $html .= '</div>
                        </div>';
                }
                if (!empty($_phoneBook->en_phone)) {
                    $html .= '
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="user-call-data">
                                <p class="mb-0">Phone Number</p>
                                <p>' . $_phoneBook->en_phone . '</p>
                            </div>
                        </div>';
                }
                if (!empty($_phoneBook->en_mobile)) {
                    $html .= '
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="user-call-data">
                                <p class="mb-0">Mobile Number</p>
                                <p>' . $_phoneBook->en_mobile . '</p>
                            </div>
                        </div>';
                }
                $html .= ' </div>
                    </div>
                </li>';
            }
        }
        return json_encode($html);
    }

    public function actionGetBlfList()
    {
        $blfExt = ExtensionBlf::find()->where(['em_id' => Yii::$app->user->id])->all();
        $html = '';
        if (!empty($blfExt)) {
            foreach ($blfExt as $_blfExt) {
                $status = '';
                if (!empty($_blfExt->extension)) {
                    $sipReg = SipRegistrations::find()->where(['sip_host' => $_SERVER['HTTP_HOST'], 'sip_user' => $_blfExt->extension])->one();
                    $sipPresence = SipPresence::find()->where(['sip_host' => $_SERVER['HTTP_HOST'], 'sip_user' => $_blfExt->extension])->one();
                    if (!empty($sipReg) && !empty($sipPresence)) {
                        $status = strtolower(str_replace(' ', '-', $sipPresence->status));
                    } elseif (!empty($sipReg) && empty($sipPresence)) {
                        $status = 'available';
                    } elseif (empty($sipReg) && empty($sipPresence)) {
                        $status = 'not-register';
                    }

                    $extension = Extension::find()
                        ->select(['CONCAT(em_extension_name," - ",em_extension_number) as name'])
                        ->where(['em_extension_number' => $_blfExt->extension])
                        ->asArray()->one();
                    if (!empty($extension)) {
                        $html .= '<li class="blf-user">
                        <a href="#" title="'.strtoupper($status).'">
                            <div class="' . $status . '"></div>'
                            . $extension["name"] .
                            '</a>
                    </li>';
                    } else {
                        $html .= '<li class="blf-user">
                        <a href="#">
                            <div class="inactive"></div>
                        </a>
                    </li>';
                    }
                } else {
                    $html .= '<li class="blf-user">
                        <a href="#">
                            <div class="inactive"></div>
                        </a>
                    </li>';
                }
            }
        }
        return json_encode($html);
    }

    public function actionGetFwdContacts(){
        $html = '';
       // $phoneBook = EnterprisePhonebook::find()->where(['em_extension' => Yii::$app->user->identity->em_extension_number])->all();

        $phoneBook = EnterprisePhonebook::find()->all();
        if (!empty($phoneBook)) {
            foreach ($phoneBook as $_phoneBook) {
                $extensionNumber = '';
                $extension = Extension::findOne($_phoneBook->en_extension);

                if (!empty($_phoneBook->en_extension) && !empty($extension)) {
                    $phoneNumber = $extension->em_extension_number;
                    $extensionNumber = $extension->em_extension_number;
                } elseif ($_phoneBook->en_mobile) {
                    $phoneNumber = $_phoneBook->en_mobile;
                } else {
                    $phoneNumber = $_phoneBook->en_phone;
                }

                $html .= '<div class="call-lists fwd-contact">
                    <div class="user-initial">'.strtoupper(substr($_phoneBook->en_first_name, 0, 1)).'</div>
                    <div class="caller-detail">
                        <div>
                            <p class="caller-name">'.$_phoneBook->en_first_name.'</p>
                            <p class="contact-number">'.$phoneNumber.'</p>
                        </div>
                        <div class="ml-auto">'.$extensionNumber.'</div>
                    </div>
                </div>';
            }

        }

        return json_encode($html);
    }


    public function actionChangePassword()
    {
        $model = Extension::findOne(['em_extension_number' => Yii::$app->user->identity->em_extension_number]);
        $oldPassword = $model->em_web_password;
        $model->setScenario('changePassword');
        if ($model->load(Yii::$app->request->post()) && $model->validate(['oldPassword', 'newPassword', 'confirmPassword'])) {
            $enc_pass = $model->em_web_password = $model->newPassword;
            if ($oldPassword == $enc_pass) {
                Yii::$app->getSession()->setFlash('error', extensionModule::t('app', 'same_password'), TRUE);
                return $this->renderPartial('_changePassword', ['model' => $model]);
            }
            $status = $model->update(FALSE, ['em_web_password']);
            if ($status) {
                Yii::$app->getSession()->setFlash('success', extensionModule::t('app', 'changed_success'), TRUE);
                return $this->redirect(['dashboard']);
            } else {
                Yii::$app->getSession()->setFlash('error', extensionModule::t('app', 'something_wrong'), TRUE);
                return $this->renderPartial('_changePassword', ['model' => $model]);
            }
        }
        return $this->renderPartial('_changePassword', ['model' => $model]);
    }

    public function actionGetSpeedDial(){
        $dialNum = [];
        $speedDial = ExtensionSpeeddial::find()->where(['es_extension' => Yii::$app->user->identity->em_extension_number])->one();
        if(!empty($speedDial)){
            for($i=0; $i <=9; $i++){
                $es = "es_".$i;
                $dialNum['*'.$i] = $speedDial->$es;
            }
        }
        return json_encode($dialNum);
    }
}
