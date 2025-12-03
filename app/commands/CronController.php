<?php
namespace app\commands;

use app\models\IpProvisioningLog;
use app\modules\ecosmob\extension\models\Extension;
use app\modules\ecosmob\ipprovisioning\models\DeviceLineParameter;
use app\modules\ecosmob\ipprovisioning\models\Devices;
use app\modules\ecosmob\ipprovisioning\models\DeviceSetting;
use Yii;
use yii\db\Exception;
use yii\console\Controller;

/**
 * Class CronController
 *
 * @package app\commands
 */
class CronController extends Controller
{
    /**
     * php yii cron/idle-logout
     *
     */
    public function actionIdleLogout()
    {
        $masterDb = Yii::$app->masterdb->createcommand("SELECT organisation_domain FROM uc_tenants WHERE status = '1' AND is_deleted = '0'")->queryAll();
        if (!empty($masterDb)) {
            foreach ($masterDb as $_masterDb) {
                try {
                    $credentials = Yii::$app->commonHelper->initialGetTenantConfig($_masterDb['organisation_domain']);
                    if (!empty($credentials)) {
                        Yii::$app->db->close();
                        Yii::$app->db->dsn = 'mysql:host=' . $credentials['authParams']['mysql_host'] . ';dbname=' . $credentials['authParams']['mysql_dbname'];
                        Yii::$app->db->username = $credentials['authParams']['mysql_username'];
                        Yii::$app->db->password = $credentials['authParams']['mysql_password'];
                        Yii::$app->db->open();

                        $globalConfig = Yii::$app->db->createCommand("SELECT gwc_value  FROM `global_web_config` WHERE `gwc_key` = 'idle_session_timeout'")->queryOne();

                        $userLog = Yii::$app->db->createCommand("SELECT *  FROM `users_activity_log` WHERE `logout_time` = '0000-00-00 00:00:00' and last_activity_time != '0000-00-00 00:00:00'")->queryAll();
                        foreach ($userLog as $_userLog) {

                            $admin = Yii::$app->db->createCommand("SELECT * FROM `admin_master` WHERE `adm_id` = " . $_userLog['user_id'])->queryOne();

                            $idleTime = strtotime('now') - strtotime($_userLog['last_activity_time']);

                            if ($idleTime) {

                                $minutes = round(abs($idleTime) / 60);

                                if ($minutes >= $globalConfig['gwc_value']) {

                                    $agent = $_userLog['user_id'] . '_' . $credentials['tenant_id'];

                                    Yii::$app->db->createCommand()
                                        ->update('agents', (['status' => 'Logged Out', 'state' => 'Waiting']), ['name' => $_userLog['user_id'] . '_' . $credentials['tenant_id']])
                                        ->execute();

                                    $extension = Yii::$app->db->createCommand("SELECT `em_extension_number` FROM `ct_extension_master` WHERE `em_id` = " . $admin['adm_mapped_extension'])->queryOne();
                                    Yii::$app->db->createCommand("DELETE FROM fs_core.sip_registrations WHERE sip_user = " . $extension['em_extension_number'] . " AND sip_host = '" . $_masterDb['organisation_domain'] . "' AND user_agent LIKE 'SIP.js/0.21.2%'")->execute();

                                    Yii::$app->db->createCommand("DELETE FROM `tiers` WHERE `agent` = '" . $agent . "'")->execute();

                                    Yii::$app->db->createCommand()
                                        ->update('break_reason_mapping', (['break_status' => 'Out', 'out_time' => date('Y-m-d H:i:s')]), ['user_id' => $_userLog['user_id'], 'out_time' => '0000-00-00 00:00:00'])
                                        ->execute();
                                    Yii::$app->db->createCommand()
                                        ->update('users_activity_log', (['logout_time' => date('Y-m-d H:i:s')]), ['user_id' => $_userLog['user_id'], 'logout_time' => '0000-00-00 00:00:00'])
                                        ->execute();
                                    Yii::$app->db->createCommand()
                                        ->update('admin_master', (['adm_token' => Yii::$app->security->generateRandomString()]), ['adm_id' => $_userLog['user_id']])
                                        ->execute();
                                }
                            }
                        }
                    }
                } catch (Exception $e) {
                    continue;
                }
            }
        }
    }

    public function actionPhoneProvision($domain, $id, $dateTime)
    {
        Yii::info("\n==== Auto Provisioning Process Start ===", 'ipprovoisioning');
        $credentials = Yii::$app->commonHelper->initialGetTenantConfig($domain);
       /*$credentials = [
            'authParams' => [
                'mysql_username' => 'root',
                'mysql_password' => 'ecosmob',
                'mysql_dbname' => 'ucdemo2',
                'mysql_host' => 'localhost',
                'mongo_dbname' => 'uctenant',
                'mongo_username' => 'ecouc',
                'mongo_password' => 'ecosmob123',
                'mongo_host' => 'localhost',
            ],
            'tenant_id' => 'c8a30252-7046-4e53-88cf-76448f68c0a1',//'29108',
            'tragofone_status' => 0,
            'tragofone_username' => 'isha123',
            'tragofone_password' => 'isha123',
            'tenant_code' => 'T01',
            'enable_sso' => 1,
            'SSO_provider' => 'Google'
        ];*/

        Yii::info("\n DB Credential : " . json_encode($credentials), 'ipprovoisioning');
        if (!empty($credentials)) {

            Yii::$app->db->close();
            Yii::$app->db->dsn = 'mysql:host=' . $credentials['authParams']['mysql_host'] . ';dbname=' . $credentials['authParams']['mysql_dbname'];
            Yii::$app->db->username = $credentials['authParams']['mysql_username'];
            Yii::$app->db->password = $credentials['authParams']['mysql_password'];
            Yii::$app->db->open();

            $device = Devices::findOne($id);

            $result = Yii::$app->ipprovisioningHelper->getDeviceByMACAddress($device->mac_address);

            if ($result['status_code'] === null) {
                Yii::info("\n Get Device Info From MAC Address ERROR : " . $result['response'], 'ipprovoisioning');
            } else {
                $response = json_decode($result['response'], true);

                if (!empty($response)) {

                    Yii::info("\n We Get The Device Info From MAC Address", 'ipprovoisioning');

                    $deviceId = $response[0]['_deviceId']['_OUI'] . '-' . $response[0]['_deviceId']['_ProductClass'] . '-' . $response[0]['_deviceId']['_SerialNumber'];

                    Yii::info("\n Device ID : " . $deviceId, 'ipprovoisioning');

                    $deviceSettings = DeviceSetting::find()->where(['device_id' => $id, 'is_writable' => 1, 'is_primary' => 0, 'voice_profile' => 0, 'is_change' => 1])->all();

                    if (!empty($deviceSettings)) {
                        foreach ($deviceSettings as $_deviceSettings) {

                            $data = [
                                "name" => "setParameterValues",
                                "parameterValues" => [
                                    [
                                        $_deviceSettings->parameter_name,
                                        $_deviceSettings->parameter_value,
                                        (!empty($_deviceSettings->value_type) ? 'xsd:' . $_deviceSettings->value_type : 'xsd:string')
                                    ]
                                ]
                            ];

                            $configuration = Yii::$app->ipprovisioningHelper->updateDeviceConfiguration($deviceId, $data);

                            Yii::info("\n  KEY : " . $_deviceSettings->parameter_name . "\n  API Response : " . json_encode($configuration), 'ipprovoisioning');

                            $ipLog = new IpProvisioningLog();
                            $ipLog->device_id = $id;
                            $ipLog->device_info = $deviceId;
                            $ipLog->parameter_key = $_deviceSettings->parameter_name;
                            $ipLog->request = json_encode($data);
                            $ipLog->response = json_encode($configuration);
                            $ipLog->response_code = $configuration['status_code'];
                            $ipLog->created_at = $dateTime;
                            $ipLog->save();


                        }

                        DeviceSetting::updateAll(['is_change' => 0], ['device_id' => $id, 'is_writable' => 1, 'is_primary' => 0, 'voice_profile' => 0, 'is_change' => 1]);
                    }
                    $deviceLineParameters = DeviceLineParameter::find()->andWhere(['device_id' => $id, 'is_change' => 1])->andWhere(['IS', 'codec', null])->all();
                    if (!empty($deviceLineParameters)) {
                        foreach ($deviceLineParameters as $_deviceLineParameters) {
                            $isAuthUsername = DeviceLineParameter::find()->andWhere(['profile_number' => $_deviceLineParameters->profile_number, 'variable_source' => 'em_extension_number'])->one();
                            if(!empty($isAuthUsername)) {
                                if(!empty($isAuthUsername->value)) {
                                    $value = $_deviceLineParameters->value;
                                    if (!empty($_deviceLineParameters->value_source)) {
                                        if ($_deviceLineParameters->variable_source == 'em_extension_number') {
                                            $ext = Extension::findOne($_deviceLineParameters->value);
                                            if (!empty($ext)) {
                                                $value = $ext->em_extension_number;
                                            } else {
                                                $value = '';
                                            }
                                        }
                                    }
                                    $ldata = [
                                        "name" => "setParameterValues",
                                        "parameterValues" => [
                                            [
                                                $_deviceLineParameters->parameter_key,
                                                $value,
                                                (!empty($_deviceLineParameters->value_type) ? 'xsd:' . $_deviceLineParameters->value_type : 'xsd:string')
                                            ]
                                        ]
                                    ];
                                    $lconfiguration = Yii::$app->ipprovisioningHelper->updateDeviceConfiguration($deviceId, $ldata);

                                    Yii::info("\n  KEY : " . $_deviceLineParameters->parameter_key . "\n  API Response : " . json_encode($lconfiguration), 'ipprovoisioning');

                                    $ipLog = new IpProvisioningLog();
                                    $ipLog->device_id = $id;
                                    $ipLog->device_info = $deviceId;
                                    $ipLog->parameter_key = $_deviceLineParameters->parameter_key;
                                    $ipLog->request = json_encode($ldata);
                                    $ipLog->response = json_encode($lconfiguration);
                                    $ipLog->response_code = $lconfiguration['status_code'];
                                    $ipLog->created_at = $dateTime;
                                    $ipLog->save();
                                }
                            }

                        }

                        DeviceLineParameter::updateAll(['is_change' => 0], ['AND', ['device_id' => $id], ['is_change' => 1], ['IS', 'codec', null]]);
                    }
                    $deviceCodec = DeviceLineParameter::find()->andWhere(['device_id' => $id, 'is_change' => 1])->andWhere(['IS NOT', 'codec', null])->all();
                    if (!empty($deviceCodec)) {
                        foreach ($deviceCodec as $_deviceCodec) {
                            $isCAuthUsername = DeviceLineParameter::find()->andWhere(['profile_number' => $_deviceCodec->profile_number, 'variable_source' => 'em_extension_number'])->one();
                            if (!empty($isCAuthUsername)) {
                                if (!empty($isCAuthUsername->value)) {
                                    $edata = [
                                        "name" => "setParameterValues",
                                        "parameterValues" => [
                                            [
                                                str_replace('Priority', 'Enable', $_deviceCodec->parameter_key),
                                                true,
                                                (!empty($_deviceCodec->value_type) ? 'xsd:' . $_deviceCodec->value_type : 'xsd:string')
                                            ]
                                        ]
                                    ];
                                    $econfiguration = Yii::$app->ipprovisioningHelper->updateDeviceConfiguration($deviceId, $edata);

                                    Yii::info("\n  KEY : " . str_replace('Priority', 'Enable', $_deviceCodec->parameter_key) . "\n  API Response : " . json_encode($econfiguration), 'ipprovoisioning');

                                    $ipLog = new IpProvisioningLog();
                                    $ipLog->device_id = $id;
                                    $ipLog->device_info = $deviceId;
                                    $ipLog->parameter_key = str_replace('Priority', 'Enable', $_deviceCodec->parameter_key);
                                    $ipLog->request = json_encode($edata);
                                    $ipLog->response = json_encode($econfiguration);
                                    $ipLog->response_code = $econfiguration['status_code'];
                                    $ipLog->created_at = $dateTime;
                                    $ipLog->save();

                                    $pdata = [
                                        "name" => "setParameterValues",
                                        "parameterValues" => [
                                            [
                                                $_deviceCodec->parameter_key,
                                                $_deviceCodec->value,
                                                (!empty($_deviceCodec->value_type) ? 'xsd:' . $_deviceCodec->value_type : 'xsd:string')
                                            ]
                                        ]
                                    ];
                                    $pconfiguration = Yii::$app->ipprovisioningHelper->updateDeviceConfiguration($deviceId, $pdata);

                                    Yii::info("\n  KEY : " . $_deviceCodec->parameter_key . "\n  API Response : " . json_encode($pconfiguration), 'ipprovoisioning');

                                    $ipLog = new IpProvisioningLog();
                                    $ipLog->device_id = $id;
                                    $ipLog->device_info = $deviceId;
                                    $ipLog->parameter_key = $_deviceCodec->parameter_key;
                                    $ipLog->request = json_encode($pdata);
                                    $ipLog->response = json_encode($pconfiguration);
                                    $ipLog->response_code = $pconfiguration['status_code'];
                                    $ipLog->created_at = $dateTime;
                                    $ipLog->save();
                                }
                            }
                        }

                        DeviceLineParameter::updateAll(['is_change' => 0], ['AND', ['device_id' => $id], ['is_change' => 1], ['IS NOT', 'codec', null]]);
                    }

                    Devices::updateAll(['provisioning_status' => 2], ['id' => $id]);

                }else{
                    Yii::info("\n API Response Blank", 'ipprovoisioning');
                }
            }

        } else {
            Yii::info("\n DB Credential Not Found", 'ipprovoisioning');
        }
    }
}