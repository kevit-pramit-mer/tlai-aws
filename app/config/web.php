<?php

use yii\web\View;

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';
$apiRules = require __DIR__ . '/apiRule.php';
$mongoDb = require __DIR__ . '/mongo.php';
$saml = require __DIR__ . '/saml.php';
$spBaseUrl = 'https://' . $_SERVER['HTTP_HOST'] . '/index.php?r=auth/auth/';

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'app\bootstrap\BootstrapSetting', 'app\components\LanguageSelector', 'app\components\ForceLogout', 'app\components\TrunkDidConfig'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'defaultRoute' => '/admin/admin/',
    'on beforeRequest' => function () {
        Yii::$app->layout = Yii::$app->user->isGuest ? '@app/views/noauth/main.php' : '@app/views/auth/main.php';
        \yii\base\Event::on(\yii\db\ActiveRecord::class, \yii\db\ActiveRecord::EVENT_BEFORE_VALIDATE, function ($event) {
            $model = $event->sender;
            foreach ($model->attributes() as $attribute) {
                if (is_string($model->$attribute)) {
                    $model->$attribute = trim($model->$attribute);
                }
            }
        });

        \yii\base\Event::on(\yii\mongodb\ActiveRecord::class, \yii\mongodb\ActiveRecord::EVENT_BEFORE_VALIDATE, function ($event) {
            $model = $event->sender;
            foreach ($model->attributes() as $attribute) {
                if (is_string($model->$attribute)) {
                    $model->$attribute = trim($model->$attribute);
                }
            }
        });
    },
    'modules' => [
        'gridview' => ['class' => 'kartik\grid\Module'],
        'auth' => [
            'class' => app\modules\ecosmob\auth\AuthModule::class,
        ],
        'admin' => [
            'class' => app\modules\ecosmob\admin\AdminModule::class,
        ],
        'timecondition' => [
            'class' => app\modules\ecosmob\timecondition\TimeConditionModule::class,
        ],
        'shift' => [
            'class' => app\modules\ecosmob\shift\ShiftModule::class,
        ],
        'weekoff' => [
            'class' => app\modules\ecosmob\weekoff\WeekOffModule::class,
        ],
        'holiday' => [
            'class' => app\modules\ecosmob\holiday\HolidayModule::class,
        ],
        'plan' => [
            'class' => app\modules\ecosmob\plan\PlanModule::class,
        ],
        'blacklist' => [
            'class' => app\modules\ecosmob\blacklist\BlackListModule::class,
        ],
        'whitelist' => [
            'class' => app\modules\ecosmob\whitelist\WhiteListModule::class,
        ],
        'globalconfig' => [
            'class' => app\modules\ecosmob\globalconfig\GlobalConfigModule::class,
        ],
        'ringgroup' => [
            'class' => app\modules\ecosmob\ringgroup\RingGroupModule::class,
        ],
        'services' => [
            'class' => app\modules\ecosmob\services\ServicesModule::class,
        ],
        'didmanagement' => [
            'class' => app\modules\ecosmob\didmanagement\DidManagementModule::class,
        ],
        'playback' => [
            'class' => app\modules\ecosmob\playback\PlaybackModule::class,
        ],
        'audiomanagement' => [
            'class' => app\modules\ecosmob\audiomanagement\AudioManagementModule::class,
        ],
        'autoattendant' => [
            'class' => app\modules\ecosmob\autoattendant\AutoAttendantModule::class,
        ],
        'user' => [
            'class' => app\modules\ecosmob\user\UserModule::class,
        ],
        'extension' => [
            'class' => app\modules\ecosmob\extension\extensionModule::class,
        ],
        'carriertrunk' => [
            'class' => app\modules\ecosmob\carriertrunk\CarriertrunkModule::class,
        ],
        'conference' => [
            'class' => app\modules\ecosmob\conference\ConferenceModule::class,
        ],
        'group' => [
            'class' => app\modules\ecosmob\group\GroupModule::class,
        ],
        'queue' => [
            'class' => app\modules\ecosmob\queue\QueueModule::class,
        ],
        'agent' => [
            'class' => app\modules\ecosmob\agent\AgentModule::class,
        ],
        'dialplan' => [
            'class' => app\modules\ecosmob\dialplan\DialPlanModule::class,
        ],
        'feature' => [
            'class' => app\modules\ecosmob\feature\FeatureModule::class,
        ],
        'accessrestriction' => [
            'class' => app\modules\ecosmob\accessrestriction\AccessRestrictionModule::class,
        ],
        'extensionforwarding' => [
            'class' => app\modules\ecosmob\extensionforwarding\ExtensionForwardingModule::class,
        ],
        'leadgroup' => [
            'class' => app\modules\ecosmob\leadgroup\LeadgroupModule::class,
        ],
        'leadgroupmember' => [
            'class' => app\modules\ecosmob\leadgroupmember\LeadGroupMemberModule::class,
        ],
        'phonebook' => [
            'class' => app\modules\ecosmob\phonebook\PhoneBookModule::class,
        ],
        'disposition' => [
            'class' => app\modules\ecosmob\disposition\DispositionModule::class,
        ],
        'disposition-type' => [
            'class' => app\modules\ecosmob\dispositionType\DispositionTypeModule::class,
        ],
        'leadGroupMember' => [
            'class' => app\modules\ecosmob\leadgroupmember\LeadGroupMemberModule::class,
        ],
        'call-campaign' => [
            'class' => app\modules\ecosmob\callcampaign\CallCampaignModule::class,
        ],
        'call-recordings' => [
            'class' => app\modules\ecosmob\callrecordings\CallRecordingsModule::class,
        ],
        'phone-book' => [
            'class' => app\modules\ecosmob\phonebook\PhoneBookModule::class,
        ],
        'speeddial' => [
            'class' => app\modules\ecosmob\speeddial\SpeeddialModule::class,
        ],
        'campaign' => [
            'class' => app\modules\ecosmob\campaign\CampaignModule::class,
        ],
        'script' => [
            'class' => app\modules\ecosmob\script\ScriptModule::class,
        ],
        'jobs' => [
            'class' => app\modules\ecosmob\jobs\JobsModule::class,
        ],
        'cdr' => [
            'class' => app\modules\ecosmob\cdr\CdrModule::class,
        ],

        'voicemsg' => [
            'class' => app\modules\ecosmob\voicemsg\VoiceMsgModule::class,
        ],
        'crm' => [
            'class' => app\modules\ecosmob\crm\CrmModule::class,
        ],
        'supervisor' => [
            'class' => app\modules\ecosmob\supervisor\SupervisorModule::class,
        ],
        'customerdetails' => [
            'class' => app\modules\ecosmob\customerdetails\CustomerDetailsModule::class,
        ],
        'agents' => [
            'class' => 'app\modules\ecosmob\agents\AgentsModule',
        ],
        'supervisorcdr' => [
            'class' => app\modules\ecosmob\supervisorcdr\SupervisorCdrModule::class,
        ],
        'campaigncdr' => [
            'class' => app\modules\ecosmob\campaigncdr\CampaignCdrModule::class,
        ],
        'agentcdr' => [
            'class' => app\modules\ecosmob\agentcdr\AgentCdrModule::class,
        ],
        'supervisoragentcdr' => [
            'class' => app\modules\ecosmob\supervisoragentcdr\SupervisorAgentCdrModule::class,
        ],
        'manageagent' => [
            'class' => app\modules\ecosmob\manageagent\ManageAgentModule::class,
        ],
        'activecalls' => [
            'class' => app\modules\ecosmob\activecalls\ActiveCallsModule::class,
        ],
        'clienthistory' => [
            'class' => app\modules\ecosmob\clienthistory\ClientHistoryModule::class,
        ],
        'callhistory' => [
            'class' => app\modules\ecosmob\callhistory\CallHistoryModule::class,
        ],
        'systemcode' => [
            'class' => app\modules\ecosmob\systemcode\SystemCodeModule::class,
        ],
        'supervisorsummary' => [
            'class' => app\modules\ecosmob\supervisorsummary\SupervisorSummaryModule::class,
        ],
        'agentscallreport' => [
            'class' => app\modules\ecosmob\agentscallreport\AgentsCallReportModule::class,
        ],
        'campaignreport' => [
            'class' => app\modules\ecosmob\campaignreport\CampaignReportModule::class,
        ],
        'logviewer' => [
            'class' => app\modules\ecosmob\logviewer\LogViewerModule::class,
        ],
        'iptable' => [
            'class' => app\modules\ecosmob\iptable\IpTableModule::class,
        ],
        'pcap' => [
            'class' => app\modules\ecosmob\pcap\PcapModule::class,
        ],
        'fraudcall' => [
            'class' => app\modules\ecosmob\fraudcall\FraudCallModule::class,
        ],
        'breaks' => [
            'class' => app\modules\ecosmob\breaks\BreaksModule::class,
        ],
        'reports' => [
            'class' => app\modules\ecosmob\reports\ReportsModule::class,
        ],
        'redialcall' => [
            'class' => app\modules\ecosmob\redialcall\ReDialCallModule::class,
        ],
        'queuewisereport' => [
            'class' => app\modules\ecosmob\queuewisereport\QueueWiseReportModule::class,
        ],
        'extensionsummaryreport' => [
            'class' => app\modules\ecosmob\extensionsummaryreport\ExtensionSummaryReportModule::class,
        ],
        'fail2ban' => [
            'class' => app\modules\ecosmob\fail2ban\Fail2banModule::class,
        ],
        'agentswisereport' => [
            'class' => app\modules\ecosmob\agentswisereport\AgentsWiseReportModule::class,
        ],
        'fraudcalldetectionreport' => [
            'class' => app\modules\ecosmob\fraudcalldetectionreport\FraudCallDetectionReportModule::class,
        ],
        'faxdetailsreport' => [
            'class' => app\modules\ecosmob\faxdetailsreport\FaxDetailsReportModule::class,
        ],
        'queuecallback' => [
            'class' => app\modules\ecosmob\queuecallback\QueueCallBackModule::class,
        ],
        'supervisorqueuecallback' => [
            'class' => app\modules\ecosmob\supervisorqueuecallback\SupervisorQueueCallBackModule::class,
        ],

        'abandonedcallreport' => [
            'class' => app\modules\ecosmob\abandonedcallreport\AbandonedCallReportModule::class,
        ],
        'supervisorabandonedcallreport' => [
            'class' => app\modules\ecosmob\supervisorabandonedcallreport\SupervisorAbandonedCallReportModule::class,
        ],
        'blacklistnumberdetails' => [
            'class' => app\modules\ecosmob\blacklistnumberdetails\BlacklistNumberDetailsModule::class,
        ],
        'campaignsummaryreport' => [
            'class' => app\modules\ecosmob\campaignsummaryreport\CampaignSummaryReportModule::class,
        ],
        'dispositionreport' => [
            'class' => app\modules\ecosmob\dispositionreport\DispositionReportModule::class,
        ],
        'hourlycallreport' => [
            'class' => app\modules\ecosmob\hourlycallreport\HourlyCallReportModule::class,
        ],
        'leadperformancereport' => [
            'class' => app\modules\ecosmob\leadperformancereport\LeadPerformanceReportModule::class,
        ],
        'agentperformancereport' => [
            'class' => app\modules\ecosmob\agentperformancereport\AgentPerformanceReportModule::class,
        ],
        'calltimedistributionreport' => [
            'class' => app\modules\ecosmob\calltimedistributionreport\CallTimeDistributionReportModule::class,
        ],
        'realtimedashboard' => [
            'class' => app\modules\ecosmob\realtimedashboard\RealTimeDashboardModule::class,
        ],
        'timeclockreport' => [
            'class' => app\modules\ecosmob\timeclockreport\TimeClockReportModule::class,
        ],

        'fax' => [
            'class' => app\modules\ecosmob\fax\FaxModule::class,
        ],
        'parkinglot' => [
            'class' => app\modules\ecosmob\parkinglot\ParkingLotModule::class,
        ],
        'blf' => [
            'class' => app\modules\ecosmob\blf\BlfModule::class,
        ],
        'enterprisePhonebook' => [
            'class' => app\modules\ecosmob\enterprisePhonebook\EnterprisePhonebookModule::class,
        ],
        'license' => [
            'class' => \app\modules\ecosmob\license\LicenseModule::class,
        ],
        'ipprovisioning' => [
            'class' => 'app\modules\ecosmob\ipprovisioning\IpprovisioningModule',
        ],
        'dbbackup' => [
            'class' => app\modules\ecosmob\dbbackup\DbBackupModule::class,
        ],
        'rbac' => [
            'class' => yii2mod\rbac\Module::class,
            'viewPath' => '@app/views/rbac',
            'controllerMap' => [
                'assignment' => [
                    'class' => yii2mod\rbac\controllers\AssignmentController::class,
                    'usernameField' => 'adm_id',
                ],
                'role' => [
                    'class' => 'app\controllers\RoleController',
                ]
            ],
            'layout' => (isset(Yii::$app->user->isGuest) && Yii::$app->user->isGuest === true) ? '@app/views/noauth/main.php' : '@app/views/auth/main.php',
        ],
    ],

    'components' => [
        'language' => ['en', 'ru'],
        'assetManager' => [
            'bundles' => [
                'yii\web\JqueryAsset' => [
                    'class' => 'yii\web\JqueryAsset',
                    'sourcePath' => null,
                    'basePath' => '@webroot',
                    'baseUrl' => '@web/theme/assets/',
                    'js' => [
                        // 'js/materialize.min.js',
                        'js/jquery.min.js',
                        'js/plugins.min.js',
                        'js/vendors.min.js',
                        //'js/jquery-ui.min.js',
                    ],
                    'css' => [
                        'css/general/materialize.css',
                        'css/general/style.css',
                        'css/general/newvendors.css',
                    ],
                    'jsOptions' => ['position' => View::POS_BEGIN],
                ],
            ],
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'wg9aL0UfF9XbQCSPBi_tx_sdO1RK6g-T',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'class' => app\components\WebUser::class,
            'identityClass' => app\modules\ecosmob\auth\models\AdminMaster::class,
            'enableAutoLogin' => true,
            'loginUrl' => ['/auth/auth/login'],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'i18n' => [
            'translations' => [
                'app*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'sourceLanguage' => 'en',
                    'basePath' => '@app/messages',
                    'fileMap' => [
                        'app' => 'app.php',
                    ],
                ],
                'yii2mod.rbac' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@yii2mod/rbac/messages',
                ],
            ],
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
                'tenantonboard' => [
                    'class' => 'yii\log\FileTarget',
                    'categories' => ['tenantonboard'],
                    'logVars' => [],
                    'logFile' => '@webroot/logs/tenantonboard/tenantonboard-' . date('Y-m-d') . '.log',
                    'maxFileSize' => 1024 * 5,
                    'maxLogFiles' => 20
                ],
                'licensedata' => [
                    'class' => 'yii\log\FileTarget',
                    'categories' => ['licensedata'],
                    'logVars' => [],
                    'logFile' => '@webroot/logs/licensedata/licensedata-' . date('Y-m-d') . '.log',
                    'maxFileSize' => 1024 * 5,
                    'maxLogFiles' => 20
                ],
                'ipprovoisioning' => [
                    'class' => 'yii\log\FileTarget',
                    'categories' => ['ipprovoisioning'],
                    'logVars' => [],
                    'logFile' => '@webroot/logs/ipprovoisioning/ipprovoisioning-' . date('Y-m-d-H:i:s') . '.log',
                    'maxFileSize' => 1024 * 5,
                    'maxLogFiles' => 20
                ],
            ],
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'defaultRoles' => ['guest', 'user'],
        ],
        'sofia' => [
            'class' => 'ecosmob\gatewaysofiapi\FreeSwitchSofiaApi',
        ],
        'fsofiapi' => [
            'class' => 'app\components\FreeswitchSofiaApi',
            'host' => '127.0.0.1',
            'port' => '8021',
            'passkey' => 'ClueCon',
            'profile' => 'ip',
        ],
        'db' => $db,
        'mongodb' => $mongoDb,
        'fscoredb' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=' . $params['MYSQL_HOST'] . ';dbname=fs_core',
            'username' => $params['MYSQL_USERNAME'],
            'password' => $params['MYSQL_PASSWORD'],
            'charset' => 'utf8',
            'attributes' => [PDO::ATTR_CASE => PDO::CASE_LOWER],
        ],
        'masterdb' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=' . $params['MASTER_MYSQL_HOST'] . ';dbname=' . $params['MASTER_DBNAME'],
            'username' => $params['MASTER_MYSQL_USERNAME'],
            'password' => $params['MASTER_MYSQL_PASSWORD'],
            'charset' => 'utf8',
            'attributes' => [PDO::ATTR_CASE => PDO::CASE_LOWER],
        ],
        'urlManager' => [
            'enablePrettyUrl' => false,
            'showScriptName' => false,
            'rules' => $apiRules,
        ],
        'layoutHelper' => [
            'class' => 'app\components\HelperLayouts',
        ],
        'commonHelper' => [
            'class' => 'app\components\CommonHelper',
            'apiUrl' => $params['API_BASE_PATH'],
            'apiUsername' => $params['API_USERNAME'],
            'apiPassword' => $params['API_PASSWORD'],
            'apiSecret' => $params['API_SECRET'],
            'basePath' => $params['PROJECT_PATH'],
            'apiRefreshInterval' => $params['MASTER_API_REFRESH_INTERVAL'],
        ],
        'storageHelper' => [
            'class' => 'app\components\StorageHelper',
        ],
        'tragofoneHelper' => [
            'class' => 'app\components\TragofoneHelper',
        ],
        'constantHelper' => [
            'class' => 'app\components\ConstantHelper',
        ],
        'ipprovisioningHelper' => [
            'class' => 'app\components\IPProvisioningHelper',
        ],
        'amqp' => [
            'class' => 'devmustafa\amqp\components\Amqp',
            'host' => $params['RABBITMQ_HOST'],
            'port' => $params['RABBITMQ_PORT'],
            'user' => $params['RABBITMQ_USER'],
            'password' => $params['RABBITMQ_PASSWORD'],
            'vhost' => '/',
        ],
        'saml' => $saml,
        'saml_google' => [
            'class' => 'asasmoyo\yii2saml\Saml',
            'config' => [
                'debug' => true,
                'sp' => [
                    'entityId' => $spBaseUrl . 'metadata',
                    'assertionConsumerService' => [
                        'url' => $spBaseUrl . 'acs',
                    ],
                    'singleLogoutService' => [
                        'url' => $spBaseUrl . 'sls',
                    ],
                    //                'NameIDFormat' => 'urn:oasis:names:tc:SAML:2.0:nameid-format:unspecified',
                ],
                'idp' => [
                    'entityId' => 'https://accounts.google.com/o/saml2?idpid=C01tsbdju',
                    'singleSignOnService' => [
                        'url' => 'https://accounts.google.com/o/saml2/idp?idpid=C01tsbdju',
                    ],
                    /* 'singleLogoutService' => [
                         'url' => 'https://accounts.google.com/o/saml2?idpid=C01tsbdju',
                     ],*/
                    'x509cert' => '-----BEGIN CERTIFICATE-----
MIIDdDCCAlygAwIBAgIGAY0/H294MA0GCSqGSIb3DQEBCwUAMHsxFDASBgNVBAoTC0dvb2dsZSBJ
bmMuMRYwFAYDVQQHEw1Nb3VudGFpbiBWaWV3MQ8wDQYDVQQDEwZHb29nbGUxGDAWBgNVBAsTD0dv
b2dsZSBGb3IgV29yazELMAkGA1UEBhMCVVMxEzARBgNVBAgTCkNhbGlmb3JuaWEwHhcNMjQwMTI1
MDUzNzIxWhcNMjkwMTIzMDUzNzIxWjB7MRQwEgYDVQQKEwtHb29nbGUgSW5jLjEWMBQGA1UEBxMN
TW91bnRhaW4gVmlldzEPMA0GA1UEAxMGR29vZ2xlMRgwFgYDVQQLEw9Hb29nbGUgRm9yIFdvcmsx
CzAJBgNVBAYTAlVTMRMwEQYDVQQIEwpDYWxpZm9ybmlhMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8A
MIIBCgKCAQEA2n90TzxnyxFh12vzI4VaSshwFXNBGmduzThGmNVHoZbkMUAoeu+j4XCXKXy/ocA1
eidf/ELTojwCFidNvdr3NE4EaKaGNZhZB799ow3W7DMHVj33hcqRS4lujVkSCxOB8ilMVWUCo9P+
eEFWw5MX1MSsFAwVdLljOlG1UW1Gm3KYKN7BM4RDlNFiXvY5yhV865c6weEfTv+iWKe2WrnhM8w3
gVOWGJtFLja9zJxABAyFA6TtUelApEmn+woabh0nn9PLCn8QORdzqETFF1Z3QBAQ7VGTz8vivm6E
DVl5RVb2xyTieypem4OYk3m+BWw/QM1OWUUKUbKU+DRXG0b3RQIDAQABMA0GCSqGSIb3DQEBCwUA
A4IBAQCzxMzN19Hx97RaplEMRkPR0lXGhi5hDDUCsceItiC2iDoLRBcx7IsQhYKCCcg1uvDff5S0
90OKNku6mbmlxrVejQyg3+QaYyAtLEheu9RzVvQfvr7k7hgtEbp24azXSNsmaNqyFuaq6zRGpXgg
SjVqCRZmXCH137XH/phsuCHP4boCgWo8EbW+8GhszIC0fytuitut+bUzW0sBf1pwETyJDp5zjrur
/uvXU5om/qZsmE29KneKZjbmVwgpmNdJvLYEREccJimV2doA+E6lU+Hu9tJXGXBpDoyS83Pgdf4r
YrrE3L+ybQcxRamrKnkZYUIQ0ozVTMy5pnTl+yRcYt1k
-----END CERTIFICATE-----',
                    /*                  'attributes' => [
                                            'Email' => 'username',
                                            'Departments' => 'role'
                                            // Add more attribute mappings as needed
                    ],*/
                ],
            ],
        ],
        'saml_azure' => [
            'class' => 'asasmoyo\yii2saml\Saml',
            'config' => [
                'debug' => true,
                'sp' => [
                    'entityId' => $spBaseUrl . 'metadata',
                    'assertionConsumerService' => [
                        'url' => $spBaseUrl . 'azure-acs',
                    ],
                    'singleLogoutService' => [
                        'url' => $spBaseUrl . 'sls',
                    ],
                    //          'NameIDFormat' => 'urn:oasis:names:tc:SAML:1.1:nameid-format:emailAddress',
                ],
                'idp' => [
                    'entityId' => 'https://sts.windows.net/43533798-009e-4e9e-8ceb-6fb1f53976fc/',
                    'singleSignOnService' => [
                        'url' => 'https://login.microsoftonline.com/43533798-009e-4e9e-8ceb-6fb1f53976fc/saml2',
                    ],
                    /* 'singleLogoutService' => [
                         'url' => 'https://accounts.google.com/o/saml2?idpid=C01tsbdju',
                     ],*/
                    'x509cert' => '-----BEGIN CERTIFICATE-----
MIIC8DCCAdigAwIBAgIQPIFZfQ//051EvbGXRSBuATANBgkqhkiG9w0BAQsFADA0MTIwMAYDVQQD
EylNaWNyb3NvZnQgQXp1cmUgRmVkZXJhdGVkIFNTTyBDZXJ0aWZpY2F0ZTAeFw0yNDA2MjQxMTA2
MzFaFw0yNzA2MjQxMTA2MzFaMDQxMjAwBgNVBAMTKU1pY3Jvc29mdCBBenVyZSBGZWRlcmF0ZWQg
U1NPIENlcnRpZmljYXRlMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA6Ywv0wmv+OQ3
tumzfoY5tVzPCOvIceyWJYbfPwBCVXMRqFnj0Ma0uczhdoPvK/Bu5bp/4IHn0TY/YEtHJlQRqEp7
qpOowtVGvgdfyR7DQd3sSdUh6/zaPNmNzhjFpw/c5L5Cq8ppEom1aPSvLG8r4FGUcUL8o/+RgaAK
HxYejHILVYR+KP9ynbSX3NiHZZquN3XYd9oOClILixh/spkCVQEhXyDaXAkOd47SFwzTO0koEa3W
SmxMjQ0zGYZjbC+5h2ekR2/xPSfNv+0250aSsIqJVDgyl/4O5IW9kr8TPZgAGZWsvrl7ZXPdN/h9
2qf8fAm5xlchZuET9AkIPQD7OQIDAQABMA0GCSqGSIb3DQEBCwUAA4IBAQBwWpqEwza6UTkzqh0z
ggTN1tMK1utx0iBwT7fXnQTNQz28QXc4mEGQpmms9IicFedkJDQgl0mFS3vhnNw6zsA0DnmCE2WY
k9pyqnCbVsoQOmF4v/kmvGoZ57vmkGPb91oltavzu6IMBWBMWBqAv+pbR2I0NLN9DkJz4wN00lPS
XMT07TqQ3AwH0+a+rWNa54ofBOSbxLA1h1bFYp2DRGDjioVICIFlTqeuLAgJ1mL7n6NwcK93owsr
HZnGgtgtf5QUEIRe2O28P6SS4YdBuglkxi6Wf/W8qMOoyksOUuAYOcw1J7y4VYjRbekMj7VE3Hqh
+34x68eHSxnVZhRPJdIc
-----END CERTIFICATE-----',
                    /*              'attributes' => [
                                      'Email' => 'username',
                                      'Departments' => 'role'
                                      // Add more attribute mappings as needed
                    ],*/
                ],
            ],
        ],
    ],
    'as access' => [
        'class' => yii2mod\rbac\filters\AccessControl::class,
        'allowActions' => [
            'auth/*',
            'admin/*',
            'debug/*',
            'extension/extension/dashboard',
            'extension/extension/update-extension',
            'extension/extension/get-data',
            'extension/extension/get-contacts',
            'extension/extension/get-blf-list',
            'extension/extension/get-fwd-contacts',
            'extension/extension/change-password',
            'extension/extension/get-speed-dial',
            'extension/extension-cdr/*',
            'site/change-language',
            'phonebook/*',
            'cdr/*',
            'extensionforwarding/*',
            //'voicemsg/voicemail-msgs/*',
            'voicemsg/voicemail-msgs/index',
            'voicemsg/voicemail-msgs/bulk-data',
            'voicemsg/voicemail-msgs/delete',
            'speeddial/*',
            'blf/*',
            'enterprisePhonebook/enterprise-phonebook/view',
            'enterprisePhonebook/enterprise-phonebook/export',
            'supervisor/supervisor/remove-sip',
            'ipprovisioning/*',
            'api/*',
            'site/about',
            'site/about-spanish',
            'site/cron',
            '/media/recordings/*',
            '/media/voicemail/*',
            '/gii/*',
            'gii/*',
            'gii',
        ],
    ],

    'params' => $params,
];
if (YII_ENV_DEV) {

    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'generators' => [
            'controller' => [
                'class' => 'app\views\template\generators\controller\BackendGenerator', // Generator class
                'templates' => [
                    'backendController' => '@app/views/template/generators/controller/default',
                ],
            ],
            'crud' => [
                'class' => 'app\views\template\generators\crud\BackendGenerator', // Generator class
                'templates' => [
                    'backendCrud' => '@app/views/template/generators/crud/default',
                ],
            ],
        ],
        // uncomment the following to add your IP if you are not connecting from localhost.
        'allowedIPs' => ['127.0.0.1', '::1', '143.110.224.9', 'localhost', '*'],
    ];
}

return $config;