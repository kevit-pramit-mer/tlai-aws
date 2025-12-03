<?php

namespace app\modules\ecosmob\globalconfig\models;

use app\components\ConstantHelper;
use app\modules\ecosmob\globalconfig\GlobalConfigModule;
use yii\db\ActiveRecord;

/**
 * This is the model class for collection "global.config".
 *
 * @property integer $id
 * @property mixed $gwc_key
 * @property mixed $gwc_value
 * @property mixed $gwc_type
 * @property mixed $gwc_description
 */
class GlobalConfig extends ActiveRecord
{

    /**
     * Return Table name
     *
     * @return string
     */
    public static function tableName()
    {
        return 'global_web_config';
    }

    /**
     * @return mixed
     */
    public static function getAuthTimeOut()
    {
        $authTimeoutDynamicMin = GlobalConfig::find()
            ->select('gwc_value')
            ->where(['gwc_key' => 'session_timeout'])
            ->one();

        return $authTimeoutDynamicMin['gwc_value'];
    }

    /**
     * Get gc Key Value
     *
     * @param string $key - correspond to gwc_value column of global_web_config table
     *
     * @return string|int
     */
    public static function fetchValue($key)
    {
        return static::find()->select(['gwc_value'])->where(
            ['gwc_value' => $key]
        )->one();
    }

    /**
     * @return mixed
     */
    public static function getWebDomain()
    {
        return GlobalConfig::findOne(
            ['gwc_key' => 'wildcard_web_domain']
        )->gwc_value;
    }

    /**
     * @return string
     */
    public static function getSipDomain()
    {
        return GlobalConfig::findOne(
            ['gwc_key' => 'wildcard_sip_domain']
        )->gwc_value;
    }

    /**
     * @return mixed
     */
    public static function getProfitPercent()
    {
        return GlobalConfig::findOne(
            ['gwc_key' => 'profit_percent_for_did']
        )->gwc_value;
    }

    /**
     * @inheritdoc
     */
    public function attributes()
    {
        return [
            'gwc_id',
            'gwc_key',
            'gwc_value',
            'gwc_type',
            'gwc_description',
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {

        return [
            [
                ['gwc_value'],
                'required',
                'when' => function ($model) {
                    return ($model->gwc_key != 'moh_file') ? TRUE
                        : FALSE;
                },
                'whenClient' => "function (attribute, value) {
                return ($('#globalconfig-gwc_key').val()!='moh_file');
            }",
            ],
            [
                'gwc_value',
                'trim',
                'when' => function ($model) {
                    return in_array(
                        $model->gwc_value,
                        ['sip_reg_auto_refresh', 'session_timeout']
                    );
                },
            ],
            [
                'gwc_value',
                'trimZeros',
                'when' => function ($model) {
                    return in_array(
                        $model->gwc_key,
                        ['sip_reg_auto_refresh', 'session_timeout']
                    );
                },
            ],
            [
                'gwc_value',
                'integer',
                'min' => 1,
                'max' => 60,
                'when' => function ($model) {
                    return $model->gwc_key == 'session_timeout';
                },
                'whenClient' => "function (attribute, value) {
                return ($('#globalconfig-gwc_key').val()=='session_timeout');
            }"
            ],
            [
                'gwc_value',
                'email',
                'when' => function ($model) {
                    return $model->gwc_key == 'mail_send_from';
                },
                'whenClient' => "function (attribute, value) {
                return ($('#globalconfig-gwc_key').val()=='mail_send_from');
            }"
            ],
            [
                ['gwc_value'],
                'checkPositiveValues',
                'when' => function ($model) {
                    return (($model->gwc_key == 'default_credit_limit' || $model->gwc_key == 'profit_percent_for_did')) ? TRUE
                        : FALSE;
                },
            ],
            [
                ['gwc_value'],
                'file',
                'extensions' => 'mp3, wav',
                'checkExtensionByMimeType' => FALSE,
                'when' => function ($model) {
                    return ($model->gwc_type == 'FILE') ? TRUE : FALSE;
                },
                'whenClient' => "function (attribute, value) {
                return ($('#globalconfig-gwc_value').attr('type')=='file'); }",
            ],

            ['gwc_description', 'string', 'max' => 255],
            ['gwc_value', 'string'],
            [
                [
                    'gwc_id',
                    'gwc_key',
                    'gwc_value',
                    'gwc_type',
                    'gwc_description',
                ],
                'safe',
            ],
        ];
    }

    /**
     * @param $attribute
     *
     * @return bool
     */
    public function checkPositiveValues($attribute)
    {
        if (!is_numeric($this->gwc_value)) {
            $this->addError($attribute, 'Config value must be numeric.');
        }

        if ($this->gwc_value < 0) {
            $this->addError($attribute, 'Value must be greater than 0.');
        }
        if ($this->gwc_key == 'profit_percent_for_did' && $this->gwc_value >= 100) {
            $this->addError($attribute, 'Value must not be greater or equal to 100.');
        }

        return TRUE;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'gwc_id' => GlobalConfigModule::t('gc', 'id'),
            'gwc_key' => GlobalConfigModule::t('gc', 'key'),
            'gwc_value' => GlobalConfigModule::t('gc', 'value'),
            'gwc_type' => GlobalConfigModule::t('gc', 'type'),
            'gwc_description' => GlobalConfigModule::t('gc', 'description'),
        ];
    }

    /**
     * Trim leading zeros from global config sip registration value
     *
     *
     * @return string
     */
    public function trimZeros()
    {
        $this->gwc_value = ltrim($this->gwc_value, '0');

        return $this->gwc_value;
    }

    public static function getValueByKey($key){
        $value = '';
        $globalConfig = self::find()->select('gwc_value')->where(['gwc_key' => $key])->one();
        if(!empty($globalConfig)){
            if(!empty($globalConfig->gwc_value)) {
                $value =  $globalConfig->gwc_value;
            }
        }
        if(empty($value)){
            if($key == 'export_limit'){
                $value = ConstantHelper::EXPORT_LIMIT;
            }elseif ($key == 'PCAP_REMOVE_DAYS'){
                $value = ConstantHelper::PCAP_REMOVE_DAYS;
            }elseif ($key == 'realtime_dashboard_refresh_time'){
                $value = ConstantHelper::REALTIME_DASHBOARD_DEFAULT_REFRESH_TIME;
            }
        }

        return $value;
    }
}
