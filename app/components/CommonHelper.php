<?php

namespace app\components;

use app\modules\ecosmob\auth\models\ForgotPassword;
use app\modules\ecosmob\emailtemplate\models\EmailTemplate;
use app\modules\ecosmob\extension\models\CombinedExtensions;
use app\modules\ecosmob\extension\models\Extension;
use app\modules\ecosmob\globalconfig\models\GlobalConfig;
use app\modules\ecosmob\license\models\LicenseTicketManagement;
use app\modules\ecosmob\parkinglot\models\ParkingLot;
use app\modules\ecosmob\timezone\models\Timezone;
use DateTime;
use DateTimeZone;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Swift_SwiftException;
use Yii;
use yii\base\Component;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\helpers\BaseArrayHelper;

/**
 * Class CommonHelper
 *
 * @package app\components
 */
class CommonHelper extends Component
{

    const INDEX = 'index';
    const CREATE = 'create';
    const UPDATE = 'update';
    const DELETE = 'delete';
    public $apiUrl;
    public $apiUsername;
    public $apiPassword;
    public $apiSecret;
    public $basePath;
    public $apiRefreshInterval;

    /**
     * makeDirAndGivePermission 0755
     *
     * @param string $dirPath directory path
     *
     * @return void
     * @throws \yii\base\Exception
     */
    public static function makeDirAndGivePermission($dirPath)
    {
        FileHelper::createDirectory($dirPath);
        chmod($dirPath, 0755);
    }

    /**
     * give Permission to Freeswitch recording folder 0770
     * @return void
     */
    public static function givePermissionToFreeswitchRecordingsFolder()
    {
        exec("chown -R nginx:nginx /usr/local/freeswitch/recordings/");
        exec("chmod -R 0777 /usr/local/freeswitch/recordings/");
    }

    /**
     * find and give Permission to Freeswitch recording folder 0770
     * @return void
     */
    public static function findAndGivePermissionToFreeswitchRecordingsFolder()
    {
        exec("find /usr/local/freeswitch/recordings -type f -exec chmod 0777 {} +");
        exec("chmod -R 0777 /usr/local/freeswitch/recordings");
    }

    /**
     * Give Permission 0770 to folder and file.
     *
     * @param string $dirPath directory path
     *
     * @return void
     */
    public static function givePermission($dirPath)
    {
        chmod($dirPath, 0770);
    }

    /**
     * Give Permission 0777 to folder and file.
     *
     * @param $dirPath
     */
    public static function giveAllPermission($dirPath)
    {
        chmod($dirPath, 0777);
    }

    /**
     * @param $model
     * @param $password
     */
    public static function userSignup($model, $password, $userType)
    {
        $emailTemplate = EmailTemplate::find()->where(['key' => 'USER_SIGNUP'])->asArray()->one();

        if ($userType == 'tenant') {
            $fullName = $model->tm_company_owner;
            $email = $model->tm_email;
        } else if ($userType == 'operator') {
            $fullName = $model->op_first_name;
            $email = $model->op_email;
        }

        $trans = [
            '_URL_' => Yii::$app->urlManager->createAbsoluteUrl(['auth/auth/login']),
            '_FULL_NAME_' => $fullName,
            '_USERNAME_' => $email,
            '_PASSWORD_' => $password,
        ];

        $body = strtr($emailTemplate['content'], $trans);

        //$mailer = Yii::$app->mailer;

        $smtpDetails = GlobalConfig::find()->select(['gwc_key', 'gwc_value'])->asArray()->all();

        if ($smtpDetails) {
            $smtpDetails = ArrayHelper::map($smtpDetails, 'gwc_key', 'gwc_value');
            $config = [
                'class' => 'yii\swiftmailer\Mailer',
                'useFileTransport' => false,
                'transport' => [
                    'class' => 'Swift_SmtpTransport',
                    'host' => $smtpDetails['smtp_host'],
                    'username' => $smtpDetails['smtp_username'],
                    'password' => $smtpDetails['smtp_password'],
                    'port' => $smtpDetails['smtp_port'],
                    'encryption' => $smtpDetails['smtp_secure'],
                ],
            ];

            $sendObj = Yii::createObject($config);
            $sendObj->compose('layouts/html', ['content' => $body])
                ->setFrom($smtpDetails['smtp_username'])
                ->setTo($email)
                ->setSubject($emailTemplate['subject'])
                ->send();
        }
    }

    /**
     * @param $firstName
     * @param $userEmailId
     * @param $username
     */
    public static function resetPasswordLink($firstName, $userEmailId, $username)
    {

        $emailTemplate = EmailTemplate::find()->where(['key' => 'FORGOT_PASSWORD'])->asArray()->one();

        $forgotPassModel = ForgotPassword::find()->where(['fp_user_id' => $username, 'fp_status' => '1'])->orderby(['fp_id' => SORT_DESC])->one();

        $trans = [
            '_URL_' => $forgotPassModel->fp_reset_url,
            '_USER_NAME_' => $firstName,
        ];

        $body = strtr($emailTemplate['content'], $trans);

        /*$mailer = Yii::$app->mailer;

        $mailer->compose('layouts/html', ['content' => $body])
            ->setFrom('testingecosmob1@gmail.com')
            ->setTo($userEmailId)
            ->setSubject($emailTemplate['subject'])
            ->send();*/

        $smtpDetails = GlobalConfig::find()->select(['gwc_key', 'gwc_value'])->asArray()->all();

        if ($smtpDetails) {
            $smtpDetails = ArrayHelper::map($smtpDetails, 'gwc_key', 'gwc_value');

            try {
                $config = [
                    'class' => 'yii\swiftmailer\Mailer',
                    'useFileTransport' => false,
                    'transport' => [
                        'class' => 'Swift_SmtpTransport',
                        'host' => $smtpDetails['smtp_host'],
                        'username' => $smtpDetails['smtp_username'],
                        'password' => $smtpDetails['smtp_password'],
                        'port' => $smtpDetails['smtp_port'],
                        'encryption' => $smtpDetails['smtp_secure'],
                    ],
                ];

                $sendObj = Yii::createObject($config);
                $status = $sendObj->compose('layouts/html', ['content' => $body])
                    ->setFrom($smtpDetails['smtp_username'])
                    ->setTo($userEmailId)
                    ->setSubject($emailTemplate['subject'])
                    ->send();
                return $status;
            } catch (Swift_SwiftException $e) {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * @param $deviceId
     * @param $sipUserDetail
     * @param $param
     */
    public static function android($deviceId, $param)
    {
//        $deviceId = 'en7wSZRvvTI:APA91bEtOW_yxnQ2G76GoTOdKU_CPa1wysjWG5Ffws3ryEX3fYNSjF2LkLe3JpTj18BOg3ZxjM9esxk8GktcR4z-IUzhLNNZ1RurzokeaFAG4L_R5jgJrphmymL_C80kasRpYXB5wQlA';
        if ($param == 'userlogin') {
            $msg = array
            (
                'body' => 'Another device is used for logged in', //, so you have logged out from the app
                'title' => 'Logout'
            );
        }
        if ($param == 'whenwaked') {
            $msg = array
            (
                'body' => 'Device waked up',
                'title' => 'Wakeup'
            );
        }

        $fields = array
        (
            'to' => $deviceId,
            'data' => $msg,
            'priority' => 'high'
        );

        $headers = array
        (
            'Authorization: key=' . Yii::$app->params['ANDROID_API_KEY'],
            'Content-Type: application/json'
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        curl_close($ch);
    }

    /**
     * @param $deviceId
     * @param $sipUserDetail
     * @param $param
     */
    public static function ios($deviceId, $param)
    {
        $randomString = '';
        $passPhrase = '';

        if ($param == 'whenwaked') {
            $pemFile = Yii::getAlias('@app') . '/UCTenant_VOIP_Dev_Cer.pem';
            $n = 32;
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            for ($i = 0; $i < $n; $i++) {
                $index = rand(0, strlen($characters) - 1);
                $randomString .= $characters[$index];
            }
        } else {
            $pemFile = Yii::getAlias('@app') . '/UCTenant_PushDev_Certificates.pem';
        }

        if (file_exists($pemFile)) {
            $ctx = stream_context_create();
            stream_context_set_option($ctx, 'ssl', 'local_cert', $pemFile);
            stream_context_set_option($ctx, 'ssl', 'passphrase', $passPhrase);
            $fp = stream_socket_client(
                'ssl://gateway.sandbox.push.apple.com:2195', $err,
                //   'ssl://gateway.push.apple.com:2195', $err,
                $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);
            if (!$fp)
                exit("Failed to connect: $err $errstr" . PHP_EOL);

            if ($param == 'userlogin') {
                $message = 'Another device is used for logged in';
                $alert = array(
                    "title" => "Login",
                    "body" => $message
                );
                $body['aps'] = array(
//                    'alert' => $alert,
                    'loc-key' => 'IC_MSG',
                    'type' => 'You are logged out!',
                    'sound' => 'chime.aiff',
                    "content-available" => 1
                );
            } else if ($param == 'whenwaked') {
                $message = 'Device waked';
                $alert = array(
                    "title" => "Device waked up",
                    "body" => $message
                );
                $body['aps'] = array(
//                    'alert' => $alert,
                    'loc-key' => 'IC_MSG',
                    'type' => 'Device waked up',
                    'sound' => 'chime.aiff',
//                    "content-available" => 1,
                    'call-id' => $randomString
                );
            } else {
                $message = 'Another device is used for logged in';
                $alert = array(
                    "title" => "Login",
                    "body" => $message
                );
                $body['aps'] = array(
//                    'alert' => $alert,
                    'loc-key' => 'IC_MSG',
                    'type' => 'You are logged out!',
                    'sound' => 'chime.aiff',
                    "content-available" => 1
                );
            }


            $payload = json_encode($body);

            $msg = chr(0) . pack('n', 32) . pack('H*', $deviceId) . pack('n', strlen($payload)) . $payload;
            try {
                $result = fwrite($fp, $msg, strlen($msg));
            } catch (Exception $ex) {
                $result = fwrite($fp, $msg, strlen($msg));
            }
            $result = fwrite($fp, $msg, strlen($msg));

            fclose($fp);
        }


        /*if (file_exists($pemFile)) {
            $ctx = stream_context_create();
            stream_context_set_option($ctx, 'ssl', 'local_cert', $pemFile);
            stream_context_set_option($ctx, 'ssl', 'passphrase', $passPhrase);
            // Open a connection to the APNS server
            $fp = stream_socket_client(
                'ssl://gateway.sandbox.push.apple.com:2195', $err,
                $errstr, 60, STREAM_CLIENT_CONNECT | STREAM_CLIENT_PERSISTENT, $ctx);
            if (!$fp)
                exit("Failed to connect: $err $errstr" . PHP_EOL);

            if ($param == 'userlogin') {
                $message = 'Another device is used for logged in';
                $alert = array(
                    "title" => "Login",
                    "body" => $message
                );
                $body['aps'] = array(
                    'alert' => $alert,
                    'loc-key' => 'IC_MSG',
                    'type' => 'You are logged out!',
                    'sound' => 'chime.aiff'
                );
            }
            if ($param == 'whenwaked') {
                $message = 'Device waked';
                $alert = array(
                    "title" => "Device waked up",
                    "body" => $message
                );
                $body['aps'] = array(
                    'alert' => $alert,
                    'loc-key' => 'IC_MSG',
                    'type' => 'Device waked up',
                    'sound' => 'chime.aiff'
                );
            }

            $payload = json_encode($body, true);

            //echo $payload;
            // Build the binary notification
            $msg = chr(0) . pack('n', 32) . pack('H*', str_replace(' ', '', sprintf('%u', CRC32($deviceId)))) . pack('n', strlen($payload)) . $payload;
            $result = fwrite($fp, $msg, strlen($msg));
//echo "<pre>"; print_r($result); exit;
            fclose($fp);
        }*/
    }

    /**
     * @param $timestamp
     * @param string $format
     * @return mixed
     */
    public static function tsToDt($timestamp, $format = 'Y-m-d H:i:s')
    {
        //$timeZoneModel = Timezone::findOne(['tz_id' => Yii::$app->user->identity->adm_timezone_id]);
        if (Yii::$app->session->get('loginAsExtension')) {
            $extensionModel = Extension::findOne(['em_extension_number' => Yii::$app->user->identity->em_extension_number]);
            if (!($extensionModel)) {
                return $newDate = date($format, strtotime($timestamp) + 0);
            }
            if (!isset($extensionModel->em_timezone_id) || $extensionModel->em_timezone_id == "") {
                return $newDate = date($format, strtotime($timestamp) + 0);
            }
            $timeZoneModel = Timezone::findOne(['tz_id' => $extensionModel->em_timezone_id]);
        } else {
            $timeZoneModel = Timezone::findOne(['tz_id' => Yii::$app->user->identity->adm_timezone_id]);
        }
        $getTimezoneName = $timeZoneModel->tz_zone;

        $date = new DateTime($timestamp, new DateTimeZone('UTC'));
        //$date = new DateTime($timestamp, new DateTimeZone('CEST'));
        $date->setTimeZone(new DateTimeZone($getTimezoneName));
        return $date->format($format);
    }

    /**
     * @param $timestamp
     * @param string $format
     * @return mixed
     */
    public static function DtTots($timestamp, $format = 'Y-m-d H:i:s')
    {
        //$timeZoneModel = Timezone::findOne(['tz_id' => Yii::$app->user->identity->adm_timezone_id]);
        if (Yii::$app->session->get('loginAsExtension')) {
            $extensionModel = Extension::findOne(['em_extension_number' => Yii::$app->user->identity->em_extension_number]);
            if (!($extensionModel)) {
                return date($format, strtotime($timestamp) + 0);
            }
            if (!isset($extensionModel->em_timezone_id) || $extensionModel->em_timezone_id == "") {
                return date($format, strtotime($timestamp) + 0);
            }
            $timeZoneModel = Timezone::findOne(['tz_id' => $extensionModel->em_timezone_id]);
        } else {
            $timeZoneModel = Timezone::findOne(['tz_id' => Yii::$app->user->identity->adm_timezone_id]);
        }
        $getSecond = $timeZoneModel->sec;

        if ($getSecond > 0) {
            $newDate = date($format, strtotime($timestamp) - $getSecond);
        } else {
            $newDate = date($format, strtotime($timestamp) + $getSecond);
        }
        return $newDate;
    }

    /**
     * This function contain action list of all modules.
     * Listed actions are the actions Except create, update, delete, index and view.
     * If any new action are their in module then add it to here for assign access permission.
     *
     * @param $data
     *
     * @return array
     */
    public function assignAdditionalPermission($data)
    {
        $additionalPermission = [
            'blacklist/black-list' => [
                self::CREATE => ['/blacklist/black-list/import'],
                self::INDEX => ['/blacklist/black-list/download-sample-file'],
            ],
            'whitelist/white-list' => [
                self::CREATE => ['/whitelist/white-list/import'],
                self::INDEX => ['/whitelist/white-list/download-sample-file'],
            ],
            'autoattendant/autoattendant' => [
                self::UPDATE => ['/autoattendant/autoattendant/settings'],
            ],
            'ringgroup/ring-group' => [
                self::CREATE => ['/ringgroup/ring-group/change-action'],
            ],
            'didmanagement/did-management' => [
                self::CREATE => ['/didmanagement/did-management/change-action', '/didmanagement/did-management/import', '/didmanagement/did-management/download-sample-file'],
            ],
            'conference/conference' => [
                self::UPDATE => ['/conference/conference/configuration'],
            ],
            'queue/queue' => [
                self::UPDATE => ['/queue/queue/change-action'],
            ],
            'extension/extension' => [
                self::INDEX => ['/extension/extension/export'],
                self::CREATE => ['/extension/extension/import', '/extension/extension/download-basic-file', '/extension/extension/download-advanced-file'],
            ],
            'leadgroupmember/lead-group-member' => [
                self::INDEX => ['/leadgroupmember/lead-group-member/export'],
                self::CREATE => ['/leadgroupmember/lead-group-member/import', '/leadgroupmember/lead-group-member/download-sample-file'],
            ],
            'jobs/job' => [
                self::UPDATE => ['/jobs/job/change-job-status', '/jobs/job/copy-job', '/jobs/job/get-job'],
            ],
            'user/user' => [
                self::DELETE => ['/user/user/trashed'],
                self::UPDATE => ['/user/user/restore', '/user/user/delete-permanent'],
            ],
            'rbac/role' => [
                self::UPDATE => ['/rbac/role/assign-access'],
            ],
            'redialcall/re-dial-call' => [
                self::UPDATE => ['/redialcall/re-dial-call/lead-status-count', '/redialcall/re-dial-call/disposition-list', '/redialcall/re-dial-call/update-lead-status', '/redialcall/re-dial-call/update-new-lead-status', '/redialcall/re-dial-call/update-blended-new-lead-status', '/redialcall/re-dial-call/leadgroup-list'],
            ],
            'queuewisereport/queue-wise-report' => [
                self::INDEX => ['/queuewisereport/queue-wise-report/export'],
            ],
            'agentswisereport/agents-call-report' => [
                self::INDEX => ['/agentswisereport/agents-call-report/export'],
            ],
            'extensionsummaryreport/cdr' => [
                self::INDEX => ['/extensionsummaryreport/cdr/export'],
            ],
            'fraudcalldetectionreport/cdr' => [
                self::INDEX => ['/fraudcalldetectionreport/cdr/export'],
            ],
            'faxdetailsreport/cdr' => [
                self::INDEX => ['/faxdetailsreport/cdr/export'],
            ],
            'abandonedcallreport/abandoned-call-report' => [
                self::INDEX => ['/abandonedcallreport/abandoned-call-report/export'],
            ],
            'queuecallback/queue-callback' => [
                self::INDEX => ['/queuecallback/queue-callback/export'],
            ],
            'supervisorqueuecallback/queue-callback' => [
                self::INDEX => ['/supervisorqueuecallback/queue-callback/export'],
            ],
            'blacklistnumberdetails/cdr' => [
                self::INDEX => ['/blacklistnumberdetails/cdr/export'],
            ],
            'supervisorabandonedcallreport/abandoned-call-report' => [
                self::INDEX => ['/supervisorabandonedcallreport/abandoned-call-report/export'],
            ],
            'pcap/pcap' => [
                self::INDEX => ['/pcap/pcap/start-capture', '/pcap/pcap/auto-delete-pcap', '/pcap/pcap/pcap-list', '/pcap/pcap/stop-capture'],
            ],
            'dbbackup/db-backup' => [
                self::INDEX => ['/dbbackup/db-backup/download'],
            ],
            'realtimedashboard/sip-extension' => [
                self::INDEX => ['/realtimedashboard/sip-extension/get-data', '/realtimedashboard/sip-extension/sip-reg-export'],
            ],
            'realtimedashboard/queue-status' => [
                self::INDEX => ['/realtimedashboard/queue-status/export'],
            ],
            'realtimedashboard/active-calls' => [
                self::INDEX => ['/realtimedashboard/active-calls/active-calls-export'],
            ],
            'realtimedashboard/user-monitor' => [
                self::INDEX => ['/realtimedashboard/user-monitor/export', '/realtimedashboard/user-monitor/get-data', '/realtimedashboard/user-monitor/force-logout'],
            ],
            'realtimedashboard/campaign-performance' => [
                self::INDEX => ['/realtimedashboard/campaign-performance/export'],
            ],
            'campaign/campaign' => [
                self::INDEX => ['/campaign/campaign/change-action'],
            ],

        ];

        $actions = [];

        if (isset($additionalPermission[$data[2]][$data[1]])) {
            $actions = $additionalPermission[$data[2]][$data[1]];
        }

        return $actions;
    }

    /**
     * @param $newNumber
     * @param $oldNumber
     *
     * @return mixed
     */
    public function checkUniqueExtension($newNumber, $oldNumber, $module = '')
    {
        $combinedExtensions = CombinedExtensions::find()->where(['extension' => $newNumber])->all();

        if (count($combinedExtensions) > 0 && $combinedExtensions[0]->extension != $oldNumber) {
            if ($combinedExtensions[0]->type == 'EXTENSION') {
                return Yii::t('app', 'Extension_number_is_already_used_in') . ' ' . Yii::t('app', 'extension');
            } elseif ($combinedExtensions[0]->type == 'CONFERENCE') {
                return Yii::t('app', 'Extension_number_is_already_used_in') . ' ' . Yii::t('app', 'conference');
            } else {
                return Yii::t('app', 'Extension_number_is_already_used_in') . ' ' . $combinedExtensions[0]->type;
            }

        }else{
            if($module != 'parking') {
                $parking = ParkingLot::find()
                    ->where(['park_ext' => $newNumber])
                    ->orWhere(['AND',
                        ['<=', 'CAST(park_pos_start AS UNSIGNED)', $newNumber],
                        ['>=', 'CAST(park_pos_end AS UNSIGNED)', $newNumber]
                    ])
                    ->all();
                if (!empty($parking)) {
                    return Yii::t('app', 'Extension_number_is_already_used_in') . ' PARKINGLOT';
                }
            }
        }

        return false;
    }

    public function getTenantConfig($domain)
    {

        $tenant_config_expire_time = Yii::$app->session->get('tenant_config_expire_time');
        $currentTime = time();
        if(!isset($tenant_config_expire_time)){
            $tenant_config_expire_time = 0;
        }
        $timeDiff = $currentTime - $tenant_config_expire_time;

        if($timeDiff > $this->apiRefreshInterval){
            self::getAPIAccessToken();
            $token = Yii::$app->session->get('api_access_token');
            $url = $this->apiUrl . 'config/get-tenant-config';
            $api_data = ['domain' => $domain];

            $header = [
                "token: $token"
            ];

            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $api_data);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

            $response = (array)json_decode(curl_exec($curl), true);

            Yii::info("\n==== Response of get Config ===", 'tenantonboard');
            Yii::info("\n==== " . json_encode($response) . "===", 'tenantonboard');

            curl_close($curl);

            if ($response && isset($response['data'])) {
                $authParamData = json_decode(base64_decode(($response['data']['auth_params'])),true);
                Yii::info("\n==== Tenant Config Data===", 'tenantonboard');
                $credentialArr = [
                    'authParams' => $authParamData,
                    'tenant_id' => $response['data']['tenant_id'],
                    'tragofone_status' => $response['data']['tragofone_status'],
                    'tragofone_username' => $response['data']['tragofone_username'],
                    'tragofone_password' => $response['data']['tragofone_password'],
                    'tenant_code' => $response['data']['account_code'],
                    'enable_sso' => $response['data']['enable_sso'],
                    'SSO_provider' => $response['data']['SSO_provider'],
                ];

                Yii::info("\n==== " . json_encode($credentialArr) . "===", 'tenantonboard');

                Yii::$app->session->set('tenant_credentials', $credentialArr);
                Yii::$app->session->set('tenant_config_expire_time', time());

                return $credentialArr;
            }else if($response && !isset($response['data'])){
                echo '<div style="text-align:center; margin-top: 20%"> <h2>'.$response['message'].'</h2></div>';
                exit;
            }else{
                echo '<div style="text-align:center; margin-top: 20%"> <h2>Response Not Found From API</h2></div>';
                exit;
            }
        }else{
            return (Yii::$app->session->get('tenant_credentials'));
        }
    }

    public function getAPIAccessToken()
    {
        $token_expire_time = Yii::$app->session->get('token_expire_time');
        $currentTime = time();
        if ($currentTime >= $token_expire_time) {
            Yii::info("\n==== TOKEN EXPIRED===", 'tenantonboard');
            self::getAccessToken();
        }
    }

    public function getAccessToken()
    {
        $key = $this->apiSecret;
        $url = $this->apiUrl . 'login';
        $api_data = ['email' => $this->apiUsername, 'password' => $this->apiPassword];
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $api_data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);


        $response = json_decode(curl_exec($curl), true);
        if (!empty($response) && isset($response['data'])) {
            try {
                $decoded = JWT::decode($response['data']['token'], new Key($key, 'HS256'));

                Yii::info("\n==== " . json_encode($decoded) . "===", 'tenantonboard');

                Yii::$app->session->set('token_expire_time', $decoded->exp);
                Yii::$app->session->set('api_access_token', $response['data']['token']);
            } catch (Exception $e) {
                Yii::info("\n==== Unable to to decode ===", 'tenantonboard');
                Yii::info("\n==== " . $e->getMessage() . " ===", 'tenantonboard');
            }
        }
    }

    public static function customMap($array, $from, $to, $add, $group = null)
    {
        $result = [];
        foreach ($array as $element) {

            $key = BaseArrayHelper::getValue($element, $from);
            $value = BaseArrayHelper::getValue($element, $to);
            //$addValue =  $element["$add"];
            // $value = $value . " - " . $addValue;
            $dialerType =  $element["cmp_dialer_type"];

            if (!empty($dialerType)) {
                $value = $value . " - " . $dialerType;
            }
            if ($group !== null) {
                $result[BaseArrayHelper::getValue($element, $group)][$key] = $value;
            } else {
                $result[$key] = $value;
            }
        }

        return $result;
    }


    public static function customMapNew($array, $from, $to, $add, $group = null)
    {
        $result = [];
        foreach ($array as $element) {

            $key = BaseArrayHelper::getValue($element, $from);
            $value = BaseArrayHelper::getValue($element, $to);
            //$addValue =  $element["$add"];
            // $value = $value . " - " . $addValue;
            $dialerType =  $element["cmp_dialer_type"];

            if (!empty($dialerType)) {
                $value = $dialerType;
            }else{
                $value = $element["cmp_type"];
            }
            /* if ($group !== null) {
                 $result[BaseArrayHelper::getValue($element, $group)][$key] = $value;
             } else {
                 $result[$key] = $value;
             }*/

            $result[$key]['data-campaignType'] = $value;
        }

        return $result;
    }

    public function initialGetTenantConfig($domain)
    {
        self::getAPIAccessToken();
        $token = Yii::$app->session->get('api_access_token');
        $url = $this->apiUrl . 'config/get-tenant-config';
        $api_data = ['domain' => $domain];

        $header = [
            "token: $token"
        ];

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $api_data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $response = (array)json_decode(curl_exec($curl), true);

        Yii::info("\n==== Response of get Config ===", 'tenantonboard');
        Yii::info("\n==== " . json_encode($response) . "===", 'tenantonboard');

        curl_close($curl);

        if ($response && isset($response['data'])) {
            $authParamData = json_decode(base64_decode(($response['data']['auth_params'])),true);
            $credentialArr = [
                'authParams' => $authParamData,
                'tenant_id' => $response['data']['tenant_id'],
                'tragofone_status' => $response['data']['tragofone_status'],
                'tragofone_username' => $response['data']['tragofone_username'],
                'tragofone_password' => $response['data']['tragofone_password'],
                'tenant_code' => $response['data']['account_code'],
                'enable_sso' => $response['data']['enable_sso'],
                'SSO_provider' => $response['data']['SSO_provider'],
            ];
            Yii::info("\n==== " . json_encode($credentialArr) . "===", 'tenantonboard');

            Yii::$app->session->set('tenant_credentials', $credentialArr);
            Yii::$app->session->set('tenant_config_expire_time', time());

            return $credentialArr;
        }
        return [];
    }

    public function convertToTime($totalSeconds) {
        $hours = floor($totalSeconds / 3600);
        $minutes = floor(($totalSeconds % 3600) / 60);
        $seconds = $totalSeconds % 60;
        $timeFormat = sprintf("%02d:%02d:%02d", $hours, $minutes, $seconds);
        return $timeFormat;
    }

    public function getLogo($domain)
    {
        self::getAPIAccessToken();
        $token = Yii::$app->session->get('api_access_token');
        $url = $this->apiUrl . 'tenant/get-tenant-setting';
        $api_data = ['organisationDomain' => $domain];

        $header = [
            "token: $token"
        ];

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $api_data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $response = (array)json_decode(curl_exec($curl), true);

        Yii::info("\n==== Response of get logo ===", 'tenantonboard');
        Yii::info("\n==== " . json_encode($response) . "===", 'tenantonboard');

        curl_close($curl);

        if ($response && isset($response['data'])) {
            $logoArr = [
                'tenant_id' => $response['data']['tenantId'],
                'logo' => $response['data']['organisationLogo'],
                'favicon_icon' => $response['data']['organisationFavicon'],
            ];
            Yii::info("\n==== " . json_encode($logoArr) . "===", 'tenantonboard');

            return $logoArr;
        }
        return [];
    }

    public function updateTenantProfile($api_data)
    {
        Yii::info("\n==== Update Profile API Start ===", 'tenantonboard');
        Yii::info("\n==== Request Data ".json_encode($api_data)."===", 'tenantonboard');
        self::getAPIAccessToken();
        $token = Yii::$app->session->get('api_access_token');
        $url = $this->apiUrl . 'tenant/update-tenant-profile';

        $header = [
            "token: $token"
        ];

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $api_data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $response = (array)json_decode(curl_exec($curl), true);

        Yii::info("\n==== Response of update profile ===", 'tenantonboard');
        Yii::info("\n==== " . json_encode($response) . "===", 'tenantonboard');

        curl_close($curl);

        if ($response && isset($response['status'])) {
            return $response;
        }
        return [];
    }

    public function getLicenseData($domain)
    {
        self::getAPIAccessToken();
        $token = Yii::$app->session->get('api_access_token');
        $url = $this->apiUrl . 'license/get-licenceData';
        $api_data = ['domain' => $domain];

        $header = [
            "token: $token"
        ];

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $api_data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $response = (array)json_decode(curl_exec($curl), true);

        Yii::info("\n==== Response of get licenceData ===", 'licensedata');
        Yii::info("\n==== " . json_encode($response) . "===", 'licensedata');

        curl_close($curl);

        if ($response && isset($response['data'])) {
            $data = $response['data'];
            Yii::info("\n==== " . json_encode($data) . "===", 'licensedata');
            return $data;
        }
        return [];
    }

    public function addTicket($api_data)
    {
        Yii::info("\n==== Add License Ticket ===", 'licensedata');
        Yii::info("\n==== Request Data ".json_encode($api_data)."===", 'licensedata');
        self::getAPIAccessToken();
        $token = Yii::$app->session->get('api_access_token');
        $url = $this->apiUrl . 'support-ticket/add';

        $header = [
            "token: $token"
        ];

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $api_data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $response = (array)json_decode(curl_exec($curl), true);

        Yii::info("\n==== Response of add ticket ===", 'licensedata');
        Yii::info("\n==== " . json_encode($response) . "===", 'licensedata');

        curl_close($curl);

        if ($response && isset($response['status'])) {
            return $response;
        }
        return [];
    }

    public function sendLicenseMail($ticketId, $status, $allocated, $requested){
        $userEmailId = Yii::$app->params['SERVICE_PROVIDER_EMAIL'];
        $smtpDetails = GlobalConfig::find()->select(['gwc_key', 'gwc_value'])->asArray()->all();

        if ($smtpDetails) {
            $smtpDetails = ArrayHelper::map($smtpDetails, 'gwc_key', 'gwc_value');
            $title = 'Please refer below request for license modification. <br/> Tenant Name : '
                .Yii::$app->user->identity->adm_firstname.' '.Yii::$app->user->identity->adm_lastname.' 
                <br/> Tenant\'s Account  Code : '. Yii::$app->session->get('tenant_code').' 
                <br/> Ticket ID : '. $ticketId .'
                <br/> Ticket Status : '. $status;

            try {
                $config = [
                    'class' => 'yii\swiftmailer\Mailer',
                    'useFileTransport' => false,
                    'transport' => [
                        'class' => 'Swift_SmtpTransport',
                        'host' => $smtpDetails['smtp_host'],
                        'username' => $smtpDetails['smtp_username'],
                        'password' => $smtpDetails['smtp_password'],
                        'port' => $smtpDetails['smtp_port'],
                        'encryption' => $smtpDetails['smtp_secure'],
                    ],
                ];

                $sendObj = Yii::createObject($config);
                $status = $sendObj->compose('layouts/license_ticket', ['username' => 'Admin', 'title' => $title, 'allocated' => $allocated, 'requested' => $requested])
                    ->setFrom($smtpDetails['smtp_username'])
                    ->setTo($userEmailId)
                    ->setSubject('Request for license modification')
                    ->send();
                return $status;
            } catch (Swift_SwiftException $e) {
                return false;
            }
        } else {
            return false;
        }
    }
    public function storeLicenseData($domain)
    {
        $tenant_config_expire_time = Yii::$app->session->get('tenant_config_expire_time');
        $currentTime = time();
        if (!isset($tenant_config_expire_time)) {
            $tenant_config_expire_time = 0;
        }
        $timeDiff = $currentTime - $tenant_config_expire_time;

        //if ($timeDiff > $this->apiRefreshInterval) {
            $licenseData = Yii::$app->commonHelper->getLicenseData($domain);
            Yii::info("\n==== Response of license data ===", 'licensedata');
            Yii::info("\n==== " . json_encode($licenseData) . "===", 'licensedata');

            if(!empty($licenseData)){
                $insertArr = [];
                foreach($licenseData as $k => $v){
                    $insertArr[] = [$k, $v];
                }
                Yii::$app->db->createCommand()->truncateTable('license_master')->execute();
                Yii::$app->db->createCommand()->batchInsert('license_master', ['key', 'value'], $insertArr)->execute();
            }
        //}
    }

    public function updateTicket($api_data)
    {
        Yii::info("\n==== Add License Ticket ===", 'licensedata');
        Yii::info("\n==== Request Data ".json_encode($api_data)."===", 'licensedata');
        self::getAPIAccessToken();
        $token = Yii::$app->session->get('api_access_token');
        $url = $this->apiUrl . 'support-ticket/updated';

        $header = [
            "token: $token"
        ];

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($curl, CURLOPT_POSTFIELDS, $api_data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $response = (array)json_decode(curl_exec($curl), true);

        Yii::info("\n==== Response of add ticket ===", 'licensedata');
        Yii::info("\n==== " . json_encode($response) . "===", 'licensedata');

        curl_close($curl);

        if ($response && isset($response['status'])) {
            return $response;
        }
        return [];
    }

    public function removeOnHoldTicket(){
        $removeDays = GlobalConfig::getValueByKey('TICKET_ON_HOLD_REMOVE_DAYS');
        $date = date('Y-m-d', strtotime('-'.$removeDays.'days'));
        $licence = LicenseTicketManagement::find()->andWhere(['status' => 'On-hold'])->andWhere(['<', 'DATE(created_at)', $date])->all();
    }
}
