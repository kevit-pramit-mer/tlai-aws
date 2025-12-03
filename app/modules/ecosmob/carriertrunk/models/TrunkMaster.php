<?php

namespace app\modules\ecosmob\carriertrunk\models;

use app\models\CommonModel;
use app\modules\ecosmob\campaign\models\Campaign;
use app\modules\ecosmob\carriertrunk\CarriertrunkModule;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "tbl_trunk_master".
 *
 * @property integer $trunk_id
 * @property string $trunk_name
 * @property string $trunk_ip
 * @property string $trunk_register
 * @property string $trunk_username
 * @property string $trunk_password
 * @property string $trunk_add_prefix
 * @property string $trunk_proxy_ip
 * @property string $trunk_status
 * @property string $trunk_ip_type
 * @property string $trunk_ip_version
 * @property string $trunk_fax_support
 * @property string $trunk_port
 * @property integer $trunk_channels
 * @property integer $trunk_cps
 * @property boolean $overwrite_config
 * @property string $trunk_live_status
 * @property string $tenant_uuid
 * @property string $service_trunk_id
 * @property boolean $from_service
 * @property string $trunk_display_name
 */
class TrunkMaster extends CommonModel
{
    /**
     * @var
     */
    public $audioCodecs;

    /**
     * @var
     */
    public $videoCodecs, $overwrite_config;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ct_trunk_master';
    }

    /**
     * @return array
     */
    public static function getFaxTrunk()
    {
        $trunkFaxListed = TrunkMaster::find()->where(
            ['trunk_fax_support' => '1']
        )->all();
        return ArrayHelper::map(
            $trunkFaxListed,
            'trunk_id',
            'trunk_name'
        );
    }

    /**
     * @param $id
     *
     * @return bool|string
     */
    public static function getTrunkById($id)
    {
        $trunk = static::findOne(['trunk_id' => $id]);
        if ($trunk instanceof TrunkMaster) {
            return $trunk->trunk_name;
        }

        return FALSE;
    }

    /**
     * @return array|ActiveRecord[]
     */
    public static function fetchAllActiveTrunk()
    {
        $query = static::find()
            ->alias('tm_tbl')
            ->where(['tm_tbl.trunk_status' => 'Y']);

        return $query->all();
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {

        return [

            /* Default Validation */
            [
                [
                    'trunk_name',
                    'trunk_ip',
                    'trunk_register',
                    'trunk_status',
                    'trunk_ip_type',
                    'trunk_ip_version',
                ],
                'required',
            ],
            ['trunk_name', 'unique'],
            ['trunk_protocol', 'default', 'value' => 'UDP'],
            ['trunk_port', 'default', 'value' => 5060],
            ['trunk_protocol', 'in', 'range' => ['TCP', 'UDP']],

            [
                ['trunk_ip', 'trunk_port'],
                'unique',
                'targetAttribute' => ['trunk_ip', 'trunk_port'],
                'when' => function () {

                    $trunkModel = TrunkMaster::find()->where(
                        [
                            'trunk_ip' => $this->trunk_ip,
                            'trunk_port' => $this->trunk_port,
                        ]
                    )->andWhere(['<>', 'trunk_status', 'X'])->count();

                    if ($trunkModel > 0) {
                        return TRUE;
                    } else {
                        return FALSE;
                    }
                },
                'on' => 'create',
            ],
            [
                ['trunk_ip', 'trunk_port'],
                'unique',
                'targetAttribute' => ['trunk_ip', 'trunk_port'],
                'when' => function () {
                    $trunkModel = TrunkMaster::find()->where(
                        [
                            'trunk_ip' => $this->trunk_ip,
                            'trunk_port' => $this->trunk_port,
                        ]
                    )->andWhere(['<>', 'trunk_status', 'X'])->andWhere(
                        ['<>', 'trunk_id', $this->trunk_id]
                    )->count();
                    if ($trunkModel > 0) {
                        return TRUE;
                    } else {
                        return FALSE;
                    }
                },
                'on' => 'update',
            ],
            ['trunk_name', 'match', 'pattern' => '/^[a-zA-Z0-9_.]+$/'],

            [
                ['trunk_name', 'trunk_username'],
                'string',
                'min' => 3,
                'max' => 25,
            ],

            [['trunk_password'], 'string', 'min' => 6, 'max' => 25],
            //preg_match prefix for +...  or * only.
            [['trunk_add_prefix'], 'match', 'pattern' => '/(\*|\+)|([0-9])/'],
            [['trunk_add_prefix'], 'string', 'max' => 15],
            [['trunk_ip'], 'string', 'max' => 45],
            [['trunk_proxy_ip'], 'string', 'max' => 45],


            [
                ['trunk_username', 'trunk_password'],
                'required',
                'when' => function ($model) {
                    // Check if trunk_register is '1' or an integer 1
                    return $model->trunk_register == '1';
                },
                'whenClient' => "function(attribute, value) {
                    return $('#trunkmaster-trunk_register').val() == '1';
                }",
            ],
            // Create unique username and trunk_ip for create Scenario
            [
                ['trunk_username'],
                'unique',
                'on' => 'create',
                'when' => function ($model) {
                    $trunkGroupCount = TrunkMaster::find()->where(
                        ['trunk_username' => $model->trunk_username,]
                    )->andWhere(['trunk_ip' => $model->trunk_ip])->andWhere(
                        [
                            '<>',
                            'trunk_status',
                            'X',
                        ]
                    )->count();

                    if ($trunkGroupCount > 0) {
                        return TRUE;
                    } else {
                        return FALSE;
                    }
                },
            ],
            // Update unique username and trunk_ip for Update Scenario
            [
                ['trunk_username'],
                'unique',
                'on' => 'update',
                'when' => function ($model) {
                    $trunkGroupCount = TrunkMaster::find()->where(
                        ['trunk_username' => $model->trunk_username]
                    )->andWhere(['trunk_ip' => $model->trunk_ip])->andWhere(
                        [
                            '<>',
                            'trunk_status',
                            'X',
                        ]
                    )->andWhere(['<>', 'trunk_id', $model->trunk_id])->count();

                    if ($trunkGroupCount > 0) {
                        return TRUE;
                    } else {
                        return FALSE;
                    }
                },
            ],

            // Validate trunk ip ipv4 /ipv6 based on selected  version type start here
            [
                ['trunk_ip'],
                'ip',
                'ipv4' => FALSE,
                'when' => function ($model) {
                    return $model->trunk_ip_version == 'IPv6';
                },
                'whenClient' => "function (attribute, value) {
                return ($('#trunkmaster-trunk_ip_version').val()=='IPv6');
            }",
            ],
            [
                ['trunk_ip'],
                'ip',
                'ipv6' => FALSE,
                'when' => function ($model) {
                    return $model->trunk_ip_version == 'IPv4';
                },
                'whenClient' => "function (attribute, value) {
                    return ($('#trunkmaster-trunk_ip_version').val()=='IPv4');
                }",
            ],
            [
                ['trunk_ip'],
                'match', 'pattern' => "/^([a-z\d](-*[a-z\d])*)(\.([a-z\d](-*[a-z\d])*))*$/i",
                'when' => function ($model) {
                    return $model->trunk_ip_version == 'domain';
                },
                'whenClient' => "function (attribute, value) {
                    return ($('#trunkmaster-trunk_ip_version').val()=='domain');
                }",
                //"message"=>"Domain not valid!"
                "message" => CarriertrunkModule::t(
                    'carriertrunk',
                    'Invalid Domain'),
            ],
            // End here
            // Validate trunk proxy ip ipv4 /ipv6 based on selected  version type start here

            [
                ['trunk_proxy_ip'],
                'ip',
                'ipv4' => FALSE,
                'when' => function ($model) {
                    return $model->trunk_ip_version == 'IPv6';
                },
                'whenClient' => "function (attribute, value) {
                return ($('#trunkmaster-trunk_ip_version').val()=='IPv6');
            }",
            ],
            [
                ['trunk_proxy_ip'],
                'ip',
                'ipv6' => FALSE,
                'when' => function ($model) {
                    return $model->trunk_ip_version == 'IPv4';
                },
                'whenClient' => "function (attribute, value) {
                return ($('#trunkmaster-trunk_ip_version').val()=='IPv4');
            }",
            ],
            // End here

            [['trunk_port'], 'integer', 'integerOnly' => TRUE, 'max' => 65535],
            ['trunk_port', 'default', 'value' => '5060'],

            [['trunk_ip_type'], 'checkIptype'],
            [['trunk_register'], 'checkRegister'],

            ['trunk_status', 'checkStatus'],

            [
                ['trunk_cps', 'trunk_channels'],
                'number',
                'min' => 1,
                'max' => 99999,
            ],
            [['caller_id'], 'string', 'min' => 3, 'max' => 15, 'message' => CarriertrunkModule::t('carriertrunk', 'caller_id_validation')],
            [
                [
                    'trunk_name',
                    'trunk_ip',
                    'trunk_port',
                    'trunk_proxy_ip',
                    'trunk_username',
                    'trunk_password',
                    'trunk_register',
                    'trunk_status',
                    'trunk_ip_type',
                    'trunk_absolute_codec',
                    'audioCodecs',
                    'videoCodecs',
                    'trunk_ip_version',
                    'trunk_fax_support',
                    'trunk_protocol',
                    'trunk_channels',
                    'trunk_cps',
                    'overwrite_config',
                    'is_caller_id_override',
                    'trunk_live_status',
                    'tenant_uuid',
                    'service_trunk_id',
                    'from_service',
                    'trunk_display_name'
                ],
                'safe',
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'trunk_id' => CarriertrunkModule::t(
                'carriertrunk',
                'id'
            ),
            'trunk_ip_version' => CarriertrunkModule::t(
                'carriertrunk',
                'trunk_ip_version'
            ),
            'trunk_name' => CarriertrunkModule::t(
                'carriertrunk',
                'name'
            ),
            'trunk_ip' => CarriertrunkModule::t(
                'carriertrunk',
                'ip'
            ),
            'trunk_port' => CarriertrunkModule::t(
                'carriertrunk',
                'port'
            ),
            'trunk_register' => CarriertrunkModule::t(
                'carriertrunk',
                'register'
            ),
            'trunk_username' => CarriertrunkModule::t(
                'carriertrunk',
                'username'
            ),
            'trunk_password' => CarriertrunkModule::t(
                'carriertrunk',
                'password'
            ),
            'trunk_add_prefix' => CarriertrunkModule::t(
                'carriertrunk',
                'prefix'
            ),
            'trunk_proxy_ip' => CarriertrunkModule::t(
                'carriertrunk',
                'proxy_ip'
            ),
            'rp_id' => CarriertrunkModule::t(
                'carriertrunk',
                'rateplan'
            ),
            'trunk_status' => CarriertrunkModule::t(
                'carriertrunk',
                'status'
            ),
            'audioCodecs' => CarriertrunkModule::t(
                'carriertrunk',
                'audio_codec'
            ),
            'videoCodecs' => CarriertrunkModule::t(
                'carriertrunk',
                'video_codec'
            ),
            'trunk_absolute_codec' => CarriertrunkModule::t(
                'carriertrunk',
                'trunk_codec'
            ),
            'trunk_ip_type' => CarriertrunkModule::t(
                'carriertrunk',
                'trunk_ip_type'
            ),
            'trunk_fax_support' => CarriertrunkModule::t(
                'carriertrunk',
                'trunk_fax_support'
            ),
            'trunk_protocol' => CarriertrunkModule::t(
                'carriertrunk',
                'trunk_protocol_label'
            ),
            'trunk_channels' => CarriertrunkModule::t(
                'carriertrunk',
                'channels_label'
            ),
            'trunk_cps' => CarriertrunkModule::t(
                'carriertrunk',
                'cps_label'
            ),
            'overwrite_config' => CarriertrunkModule::t('carriertrunk', 'overwrite_config'),
            'caller_id' => CarriertrunkModule::t('carriertrunk', 'caller_id'),
            'is_caller_id_override' => CarriertrunkModule::t('carriertrunk', 'is_caller_id_override'),
            'trunk_live_status' => CarriertrunkModule::t('carriertrunk', 'trunk_live_status'),
        ];
    }

    /**
     * @param $attribute
     */
    public function checkIptype($attribute)
    {
        $new_arr = ['PRIVATE', 'PUBLIC'];

        if (!in_array($this->$attribute, $new_arr)) {
            $this->addError($attribute, 'Please select valid IP Scope');
        }
    }

    /**
     * @param $attribute
     */
    public function checkStatus($attribute)
    {
        if (!in_array($this->$attribute, ['Y', 'N'])) {
            $this->addError($attribute, Yii::t('app', 'invalid_option'));
        }
    }

    /**
     * @param $attribute
     */
    public function checkRegister($attribute)
    {
        if (!in_array($this->$attribute, ['0', '1'])) {
            $this->addError($attribute, Yii::t('app', 'invalid_option'));
        }
    }

    /**
     * @param $id
     * @return bool
     */
    public function canDelete($id)
    {
        /** @var TrunkGroupDetails $trunkExistInDetails */
        /** @var Campaign $campaignCount */
        $trunkExistInDetails = TrunkGroupDetails::find()->where(['trunk_id' => $id])->count();
        $campaignCount = Campaign::find()->where(['cmp_trunk' => $id])->count();

        if ($trunkExistInDetails > 0 || $campaignCount > 0) {
            return TRUE;
        } else {
            return FALSE;
        }

    }

    public static function callRabbitmq($queue = 'q_opensips_msg', $exchange = 'e_opensips_msg', $msg = 'opensips-cli -x mi reg_reload'){
        Yii::$app->amqp->declareExchange($exchange);
        Yii::$app->amqp->declareQueue($queue);
        Yii::$app->amqp->bindQueueExchanger($queue, $exchange);
        Yii::$app->amqp->publish_message($msg, $exchange);

    }

    public static function updateTrunkLiveStatus(){
        $trunk = TrunkMaster::find()->all();
        if(!empty($trunk)){
            foreach($trunk as $_trunk){
                $_trunk->tenant_uuid = $GLOBALS['tenantID'];
                $status = Yii::$app->fsofiapi->getTrunkStatus($GLOBALS['tenantID'].'_'.$_trunk->trunk_name);
                $_trunk->trunk_live_status = ($status == '1' ? '1' : '0');
                $_trunk->save(false);
            }
        }
    }
}
