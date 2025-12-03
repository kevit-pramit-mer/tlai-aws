<?php

namespace app\modules\ecosmob\ipprovisioning\controllers;

use app\components\CommonHelper;
use app\components\ConsoleRunner;
use app\models\ExtensionView;
use app\modules\ecosmob\ipprovisioning\IpprovisioningModule;
use app\modules\ecosmob\ipprovisioning\models\DeviceLineParameter;
use app\modules\ecosmob\ipprovisioning\models\Devices;
use app\modules\ecosmob\ipprovisioning\models\DeviceSetting;
use app\modules\ecosmob\ipprovisioning\models\DevicesSearch;
use app\modules\ecosmob\ipprovisioning\models\DeviceTemplatesParameters;
use app\modules\ecosmob\ipprovisioning\models\PhoneModels;
use app\modules\ecosmob\ipprovisioning\models\TemplateCodecSettings;
use app\modules\ecosmob\ipprovisioning\models\TemplateDetails;
use app\modules\ecosmob\ipprovisioning\models\TemplateMaster;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * DevicesController implements the CRUD actions for Devices model.
 */
class DevicesController extends Controller
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
                            'get-data',
                            'settings',
                            'get-ext-data',
                            'provisioning',
                            'reboot',
                            'reset'
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
     * Lists all Devices models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DevicesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Devices model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Finds the Devices model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Devices the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Devices::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Creates a new Devices model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Devices();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->created_at = date('Y-m-d H:i:s');
            $model->updated_at = date('Y-m-d H:i:s');
            if ($model->save()) {
                Yii::$app->db->createCommand('
                    INSERT INTO tbl_device_setting (device_id, parameter_name, is_object, is_writable, parameter_value, value_type, parameter_label, input_type, is_primary, voice_profile, codec, value_source, variable_source) 
                    SELECT ' . $model->id . ' as device_id, parameter_name, is_object, is_writable, parameter_value, value_type, parameter_label, input_type, is_primary, voice_profile, codec, value_source, variable_source FROM tbl_template_details WHERE template_id = "' . $model->template_master_id . '" AND is_checked = 1')->execute();

                $this->addLineParameter($model->id);

                Yii::$app->session->setFlash('success', Yii::t('app', 'Created Successfully'));
                return $this->redirect(['index']);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function addLineParameter($id, $isCodecAdd = 1)
    {
        $model = $this->findModel($id);

        $deviceSetting = DeviceSetting::find()
            ->andWhere(['device_id' => $id, 'is_object' => 0, 'is_writable' => 1, 'voice_profile' => 1])
            ->all();

        $phoneModel = PhoneModels::findOne(['p_id' => $model->model_id]);

        $lineParameter = DeviceLineParameter::find()->andWhere(['device_id' => $id])->andWhere(['IS', 'codec', null])->all();

        $parameterKeysToKeep = [];
        foreach ($deviceSetting as $_deviceSetting) {
            for ($i = 1; $i <= $phoneModel->p_lines; $i++) {
                $parameterKeysToKeep[] = 'Device.Services.VoiceService.1.VoiceProfile.' . $i . '.' . $_deviceSetting->parameter_name;
            }
        }

        $existingParameterMap = [];
        foreach ($lineParameter as $param) {
            $existingParameterMap[$param->parameter_key] = $param;
        }

        $updateArr = [];
        $insertArr = [];

        foreach ($deviceSetting as $_deviceSetting) {
            for ($i = 1; $i <= $phoneModel->p_lines; $i++) {
                $parameterKey = 'Device.Services.VoiceService.1.VoiceProfile.' . $i . '.' . $_deviceSetting->parameter_name;

                if (isset($existingParameterMap[$parameterKey])) {
                    $updateArr[] = [
                        'device_id' => $id,
                        'profile_number' => $i,
                        'parameter_key' => $parameterKey,
                        'value_source' => $_deviceSetting->value_source,
                        'variable_source' => $_deviceSetting->variable_source,
                        'is_writable' => $_deviceSetting->is_writable,
                        'input_type' => $_deviceSetting->input_type,
                        'value_type' => $_deviceSetting->value_type,
                        'parameter_value' => ($_deviceSetting->value_source == 'Variable' ? null : $_deviceSetting->parameter_value)
                    ];
                } else {
                    $insertArr[] = [
                        $id,
                        $i,
                        $_deviceSetting->parameter_label,
                        $_deviceSetting->parameter_name,
                        $parameterKey,
                        $_deviceSetting->value_source,
                        $_deviceSetting->variable_source,
                        $_deviceSetting->is_writable,
                        $_deviceSetting->input_type,
                        $_deviceSetting->value_type,
                        ($_deviceSetting->value_source == 'Variable' ? null : $_deviceSetting->parameter_value)
                    ];
                }
            }
        }

        if (!empty($updateArr)) {
            foreach ($updateArr as $updateData) {
                Yii::$app->db->createCommand()
                    ->update(
                        'tbl_device_line_parameter',
                        [
                            'value_source' => $updateData['value_source'],
                            'variable_source' => $updateData['variable_source'],
                            'is_writable' => $updateData['is_writable'],
                            'input_type' => $updateData['input_type'],
                            'value_type' => $updateData['value_type'],
                            'value' => ($updateData['value_source'] == 'Variable' ? null : $updateData['parameter_value']),
                        ],
                        [
                            'device_id' => $updateData['device_id'],
                            'profile_number' => $updateData['profile_number'],
                            'parameter_key' => $updateData['parameter_key']
                        ]
                    )
                    ->execute();
            }
        }

        Yii::$app->db->createCommand()
            ->delete(
                'tbl_device_line_parameter',
                [
                    'and',
                    ['device_id' => $id],
                    ['not in', 'parameter_key', $parameterKeysToKeep],
                    ['IS', 'codec', null]
                ]
            )
            ->execute();

        if (!empty($insertArr)) {
            Yii::$app->db->createCommand()->batchInsert(
                'tbl_device_line_parameter',
                ['device_id', 'profile_number', 'parameter_label', 'parameter_name', 'parameter_key', 'value_source', 'variable_source', 'is_writable', 'input_type', 'value_type', 'value'],
                $insertArr
            )->execute();
        }

        if ($isCodecAdd == 1) {
            $codecRecords = DeviceTemplatesParameters::find()
                ->andWhere(['OR', ['IS', 'codec', null], ['!=', 'codec', '']])
                ->andWhere(['device_templates_id' => $model->template->device_template_id])
                ->groupBy('codec')
                ->asArray()
                ->all();

            $templateCodec = ArrayHelper::map(TemplateCodecSettings::find()->where(['template_id' => $model->template_master_id])->asArray()->all(), 'parameter_key', 'priority');

            if (!empty($codecRecords)) {
                $parameterNames = array_column($codecRecords, 'parameter_name');
                $parameterNamesString = implode(", ", $parameterNames);

                preg_match_all("/Line\.1\.Codec\.List\.(\d+)\.(Enable)/", $parameterNamesString, $matches);

                for ($j = 1; $j <= $phoneModel->p_lines; $j++) {
                    foreach ($codecRecords as $record) {
                        if (preg_match("/Line\.1\.Codec\.List\.(\d+)\.(Enable)/", $record['parameter_name'], $match)) {
                            $index = (int)$match[1];
                            $codecInsertArr[] = [
                                $id,
                                $j,
                                $record['parameter_label'],
                                $record['parameter_name'],
                                'Device.Services.VoiceService.1.VoiceProfile.' . $j . '.Line.1.Codec.List.' . $index . '.Priority',
                                $record['value_source'],
                                $record['is_writable'],
                                $record['input_type'],
                                $record['codec'],
                                $templateCodec['Line.1.Codec.List.' . $index . '.Priority']
                            ];
                        }
                    }
                }
                Yii::$app->db->createCommand()->batchInsert(
                    'tbl_device_line_parameter',
                    ['device_id', 'profile_number', 'parameter_label', 'parameter_name', 'parameter_key', 'value_source', 'is_writable', 'input_type', 'codec', 'value'],
                    $codecInsertArr
                )->execute();
            }
        }
    }

    /**
     * Updates an existing Devices model.
     * If update is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $oldTemplateId = $model->template_master_id;
        $isTemplateChange = 0;
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->save()) {
                if ($oldTemplateId != $model->template_master_id) {
                    DeviceSetting::deleteAll(['device_id' => $id]);
                    DeviceLineParameter::deleteAll(['device_id' => $id]);
                    $isTemplateChange = 1;
                }

                $deviceSetting = DeviceSetting::find()->where(['device_id' => $id])->all();
                $deviceLineParameter = DeviceLineParameter::find()->where(['device_id' => $id])->all();

                if (empty($deviceSetting)) {
                    Yii::$app->db->createCommand('
                    INSERT INTO tbl_device_setting (device_id, parameter_name, is_object, is_writable, parameter_value, value_type, parameter_label, input_type, is_primary, voice_profile, codec, value_source, variable_source) 
                    SELECT ' . $model->id . ' as device_id, parameter_name, is_object, is_writable, parameter_value, value_type, parameter_label, input_type, is_primary, voice_profile, codec, value_source, variable_source FROM tbl_template_details WHERE template_id = "' . $model->template_master_id . '" AND is_checked = 1')->execute();
                } else {
                    Yii::$app->db->createCommand('
                        UPDATE tbl_device_setting AS d
                        JOIN tbl_template_details AS t
                        ON d.device_id = ' . $model->id . '
                        AND t.template_id = "' . $model->template_master_id . '"
                        AND t.is_checked = 1
                        SET 
                            d.parameter_name = t.parameter_name,
                            d.is_object = t.is_object,
                            d.is_writable = t.is_writable,
                            d.value_type = t.value_type,
                            d.parameter_label = t.parameter_label,
                            d.input_type = t.input_type,
                            d.is_primary = t.is_primary,
                            d.voice_profile = t.voice_profile,
                            d.codec = t.codec,
                            d.value_source = t.value_source,
                            d.variable_source = t.variable_source
                        WHERE d.device_id = ' . $model->id . '
                        AND d.parameter_name = t.parameter_name
                    ')->execute();

                    Yii::$app->db->createCommand('
    INSERT INTO tbl_device_setting (device_id, parameter_name, is_object, is_writable, parameter_value, value_type, parameter_label, input_type, is_primary, voice_profile, codec, value_source, variable_source) 
    SELECT ' . $model->id . ' as device_id, parameter_name, is_object, is_writable, parameter_value, value_type, parameter_label, input_type, is_primary, voice_profile, codec, value_source, variable_source 
    FROM tbl_template_details 
    WHERE template_id = "' . $model->template_master_id . '" 
    AND is_checked = 1
    AND NOT EXISTS (
        SELECT 1 
        FROM tbl_device_setting 
        WHERE device_id = ' . $model->id . ' 
        AND parameter_name = tbl_template_details.parameter_name
    )
')->execute();
                    Yii::$app->db->createCommand('
    DELETE d
    FROM tbl_device_setting d
    LEFT JOIN tbl_template_details t
    ON d.device_id = ' . $model->id . '
    AND t.parameter_name = d.parameter_name
    WHERE t.template_id = "' . $model->template_master_id . '"
    AND (t.is_checked = 0 OR t.parameter_name IS NULL)
')->execute();

                    Yii::$app->db->createCommand('
                        UPDATE tbl_device_setting AS d
                        JOIN tbl_template_details AS t
                        ON d.device_id = ' . $model->id . '
                        AND t.template_id = "' . $model->template_master_id . '"
                        AND t.is_checked = 1
                        SET 
                      d.parameter_value = t.parameter_value
                       WHERE d.device_id = ' . $model->id . '
                        AND d.parameter_name = t.parameter_name
                         AND d.value_source = "Global"
                    ')->execute();
                }
                //if(empty($deviceLineParameter)){
                $this->addLineParameter($model->id, $isTemplateChange);
                //}
                if (Yii::$app->request->post('apply') == 'update') {
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Applied Successfully.'));
                    return $this->refresh();
                } else {
                    Yii::$app->session->setFlash('success', Yii::t('app', 'Updated Successfully.'));
                    return $this->redirect(['index']);
                }
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Devices model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        DeviceSetting::deleteAll(['device_id' => $id]);
        DeviceLineParameter::deleteAll(['device_id' => $id]);
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', Yii::t('app', 'Deleted Successfully.'));
        return $this->redirect(['index']);
    }

    public function actionGetData()
    {
        $templateOption = '<option>Select</option>';
        $modelOption = '<option>Select</option>';
        if (!empty($_POST['brand_id'])) {
            $templateMaster = TemplateMaster::find()->where(['brand_id' => $_POST['brand_id']])->all();
            if (!empty($templateMaster)) {
                foreach ($templateMaster as $_templateMaster) {
                    $templateOption .= '<option value="' . $_templateMaster->id . '">' . $_templateMaster->template_name . '</option>';
                }
            }

            if (!empty($_POST['template_id'])) {
                $template = TemplateMaster::findOne($_POST['template_id']);
                if (!empty($template)) {
                    if (!empty($template->supported_models_id)) {
                        $phoneModel = PhoneModels::find()->andWhere(['pv_id' => $_POST['brand_id']])->andWhere(['IN', 'p_id', explode(',', $template->supported_models_id)])->all();
                        if (!empty($phoneModel)) {
                            foreach ($phoneModel as $_phoneModel) {
                                $modelOption .= '<option value=' . $_phoneModel->p_id . '>' . $_phoneModel->p_model . '</option>';
                            }
                        }

                    }
                }
            }
        }
        return json_encode(['templateOption' => $templateOption, 'modelOption' => $modelOption]);
    }

    public function actionSettings($id)
    {
        $model = $this->findModel($id);
        $phoneModel = PhoneModels::findOne(['p_id' => $model->model_id]);

        $deviceFields = DeviceSetting::find()->where(['device_id' => $id, 'voice_profile' => 0, 'is_object' => 0, 'is_writable' => 1])->all();
        $dbLineFields = DeviceSetting::find()->where(['device_id' => $id, 'voice_profile' => 1, 'is_object' => 0, 'is_writable' => 1, 'value_source' => 'Variable'])->all();
        $otherLineFields = DeviceSetting::find()->andWhere(['device_id' => $id, 'voice_profile' => 1, 'is_object' => 0, 'is_writable' => 1])/*->andWhere(['!=', 'value_source', 'Variable'])*/ ->all();
        if (!empty($_POST)) {
            if (isset($_POST['device'])) {
                foreach ($_POST['device'] as $dKey => $dVal) {
                    $isChangeM = DeviceSetting::findOne(['id' => $dKey, 'device_id' => $id]);
                    if($isChangeM->is_change == 0 && $isChangeM->parameter_value !== $dVal){
                        $isChange = 1;
                    }else{
                       $isChange = $isChangeM->is_change;
                    }
                    DeviceSetting::updateAll(['parameter_value' => $dVal, 'isChange' => $isChange], ['id' => $dKey, 'device_id' => $id]);
                }
            }
            if (isset($_POST['line'])) {
                foreach ($_POST['line'] as $lKey => $lVal) {
                    foreach ($lVal as $ky => $vl) {

                        if($ky != 'all_codec' && $ky != 'assign_codec') {
                            $isChangeM = DeviceLineParameter::findOne(['device_id' => $id, 'profile_number' => $lKey, 'parameter_name' => $ky]);
                            if($isChangeM->is_change == 0 && $isChangeM->value !== $vl){
                                $oldValue = 1;
                            }else{
                                $oldValue = $isChangeM->is_change;
                            }
                            DeviceLineParameter::updateAll(['value' => $vl, 'is_change' => $oldValue], ['device_id' => $id, 'profile_number' => $lKey, 'parameter_name' => $ky]);
                        }
                        if($ky == 'assign_codec'){
                            if(!empty($vl)){
                                foreach ($vl as $cky => $cvl) {
                                    DeviceLineParameter::updateAll(['value' => ($cky + 1), 'is_change' => 1], ['device_id' => $id, 'profile_number' => $lKey, 'parameter_key' => $cvl]);
                                }
                            }
                        }
                        if($ky == 'all_codec'){
                            if(!empty($vl)){
                                foreach ($vl as $aky => $avl) {
                                    DeviceLineParameter::updateAll(['value' => null, 'is_change' => 1], ['device_id' => $id, 'profile_number' => $lKey, 'parameter_key' => $avl]);
                                }
                            }
                        }

                    }

                }
            }

            return $this->redirect(['settings', 'id' => $id]);
        }
        return $this->render('_settings', [
            'model' => $model,
            'deviceFields' => $deviceFields,
            'dbLineFields' => $dbLineFields,
            'otherLineFields' => $otherLineFields,
            'lineCount' => $phoneModel->p_lines
        ]);
    }

    public function actionGetExtData()
    {
        $data = [];
        if (!empty($_POST)) {
            if (!empty($_POST['id'])) {
                $ext = ExtensionView::find()->where(['em_id' => $_POST['id']])->one();
                if (!empty($ext)) {
                    $data = [
                        'em_extension_number' => $ext->em_extension_number,
                        'em_extension_name' => $ext->em_extension_name,
                        'em_password' => $ext->em_password,
                        'ecs_max_calls' => $ext->ecs_max_calls,
                        'ecs_ring_timeout' => $ext->ecs_ring_timeout,
                        'sip_uri' => 'sip:'.$ext->em_extension_number.'@'.$_SERVER['HTTP_HOST']
                    ];
                }
            }
        }
        return json_encode($data);
    }

    public function actionProvisioning($id){
        $model = $this->findModel($id);
        $model->provisioning_status = 1;
        $model->save();
        $consoleExecution = new ConsoleRunner(['file' => '@app/yii']);
        $consoleExecution->runHelper("cron/phone-provision '".$_SERVER['HTTP_HOST']."' '$id' '".CommonHelper::tsToDt(date('Y-m-d H:i:s'))."'");
        Yii::$app->session->setFlash('success', IpprovisioningModule::t('app', 'IP Provisioning is inprogress will complete in sometime.'));
        return $this->redirect(['index']);
    }

    public function actionReboot($id)
    {
        Yii::info("\n==== Reboot Process Start ===", 'ipprovoisioning');
        $device = Devices::findOne($id);

        $result = Yii::$app->ipprovisioningHelper->getDeviceByMACAddress($device->mac_address);

        if ($result['status_code'] === null) {
            Yii::info("\n Get Device Info From MAC Address ERROR : " . $result['response'], 'ipprovoisioning');

            Yii::$app->session->setFlash('error', IpprovisioningModule::t('app', 'Something went wrong from API'));
            return $this->redirect(['index']);
        } else {
            $response = json_decode($result['response'], true);

            if (!empty($response)) {
                Yii::info("\n We Get The Device Info From MAC Address", 'ipprovoisioning');

                $deviceId = $response[0]['_deviceId']['_OUI'] . '-' . $response[0]['_deviceId']['_ProductClass'] . '-' . $response[0]['_deviceId']['_SerialNumber'];

                Yii::info("\n Device ID : " . $deviceId, 'ipprovoisioning');

                $rebootResponse = Yii::$app->ipprovisioningHelper->rebootDevice($deviceId);

                Yii::info("\n Reboot Api Response : " . json_encode($rebootResponse), 'ipprovoisioning');
                if (!empty($rebootResponse)) {
                    if ($rebootResponse['status_code'] == 200) {

                        Yii::$app->session->setFlash('success', IpprovisioningModule::t('app', 'Reboot Successfully'));
                        return $this->redirect(['index']);
                    } else {
                        Yii::info("\n Reboot Device ERROR : " . $rebootResponse['response'], 'ipprovoisioning');

                        Yii::$app->session->setFlash('error', IpprovisioningModule::t('app', 'Something went wrong from API'));
                        return $this->redirect(['index']);
                    }
                } else {
                    Yii::info("\n API Response Blank", 'ipprovoisioning');

                    Yii::$app->session->setFlash('error', IpprovisioningModule::t('app', 'API Response Blank'));
                    return $this->redirect(['index']);
                }
            } else {
                Yii::info("\n API Response Blank", 'ipprovoisioning');

                Yii::$app->session->setFlash('error', IpprovisioningModule::t('app', 'API Response Blank'));
                return $this->redirect(['index']);
            }
        }
    }

    public function actionReset($id)
    {
        Yii::info("\n==== Reset Process Start ===", 'ipprovoisioning');
        $device = Devices::findOne($id);

        $result = Yii::$app->ipprovisioningHelper->getDeviceByMACAddress($device->mac_address);

        if ($result['status_code'] === null) {
            Yii::info("\n Get Device Info From MAC Address ERROR : " . $result['response'], 'ipprovoisioning');

            Yii::$app->session->setFlash('error', IpprovisioningModule::t('app', 'Something went wrong from API'));
            return $this->redirect(['index']);
        } else {
            $response = json_decode($result['response'], true);

            if (!empty($response)) {
                Yii::info("\n We Get The Device Info From MAC Address", 'ipprovoisioning');

                $deviceId = $response[0]['_deviceId']['_OUI'] . '-' . $response[0]['_deviceId']['_ProductClass'] . '-' . $response[0]['_deviceId']['_SerialNumber'];

                Yii::info("\n Device ID : " . $deviceId, 'ipprovoisioning');

                $resetResponse = Yii::$app->ipprovisioningHelper->ResetDevice($deviceId);
                Yii::info("\n Reset Api Response : " . json_encode($resetResponse), 'ipprovoisioning');
                if (!empty($resetResponse)) {
                    if ($resetResponse['status_code'] == 200) {

                        DeviceLineParameter::updateAll(['value' => null, 'is_change' => 0], ['device_id' => $id]);

                        Yii::$app->session->setFlash('success', IpprovisioningModule::t('app', 'Reset Successfully'));
                        return $this->redirect(['index']);
                    } else {
                        Yii::info("\n Reset Device ERROR : " . $resetResponse['response'], 'ipprovoisioning');

                        Yii::$app->session->setFlash('error', IpprovisioningModule::t('app', 'Something went wrong from API'));
                        return $this->redirect(['index']);
                    }
                } else {
                    Yii::info("\n API Response Blank", 'ipprovoisioning');

                    Yii::$app->session->setFlash('error', IpprovisioningModule::t('app', 'API Response Blank'));
                    return $this->redirect(['index']);
                }
            } else {
                Yii::info("\n API Response Blank", 'ipprovoisioning');

                Yii::$app->session->setFlash('error', IpprovisioningModule::t('app', 'API Response Blank'));
                return $this->redirect(['index']);
            }
        }
    }
}
