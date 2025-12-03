<?php

namespace app\modules\ecosmob\accessrestriction\models;

use Yii;
use yii\db\ActiveRecord;
use app\modules\ecosmob\accessrestriction\AccessRestrictionModule;

/**
 * This is the model class for table "ct_access_restriction".
 *
 * @property int $ar_id
 * @property string $ar_ipaddress
 * @property int $ar_maskbit
 * @property string $ar_description
 * @property string $ar_status
 */
class AccessRestriction extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ct_access_restriction';
    }

    /**
     * @return array
     */
    public static function status_list()
    {
        return [
            '1' => Yii::t('app', 'active'),
            '0' => Yii::t('app', 'inactive'),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ar_ipaddress', 'ar_maskbit', 'ar_status'], 'required'],
            [['ar_maskbit'], 'integer', 'min' => 0],
            [['ar_maskbit'], 'checkMaskRange'],
            [['ar_description', 'ar_status'], 'string'],
            [['ar_ipaddress'], 'string', 'max' => 100],
            [['ar_description'], 'string', 'max' => 100],
            ['ar_ipaddress', 'checkIPVersion'],
            [['ar_ipaddress', 'ar_description', 'ar_status'], 'safe'],
            [['ar_ipaddress'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ar_id' => AccessRestrictionModule::t('accessrestriction', 'ar_id'),
            'ar_ipaddress' => AccessRestrictionModule::t('accessrestriction', 'ar_ipaddress'),
            'ar_maskbit' => AccessRestrictionModule::t('accessrestriction', 'ar_maskbit'),
            'ar_description' => AccessRestrictionModule::t('accessrestriction', 'ar_description'),
            'ar_status' => AccessRestrictionModule::t('accessrestriction', 'ar_status'),
        ];
    }

    /**
     * @param $attribute
     * @return string
     */
    public function checkIPVersion($attribute)
    {
        if (filter_var($this->ar_ipaddress, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            if (empty($this->ar_maskbit)) {
                return $this->ar_maskbit = '32';
            } else {
                return $this->ar_maskbit;
            }
        } elseif (filter_var($this->ar_ipaddress, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            if (empty($this->ar_maskbit)) {
                return $this->ar_maskbit = '64';
            } else {
                return $this->ar_maskbit;
            }
        } else {
            return $this->addError($attribute, AccessRestrictionModule::t('accessrestriction', 'invalid_ip'));
        }
    }

    /**
     * @param $attribute
     * @return bool|void
     */
    public function checkMaskRange($attribute)
    {

        if (filter_var($this->ar_ipaddress, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            if ($this->ar_maskbit <= 64) {
                return true;
            } else {
                return $this->addError($attribute, AccessRestrictionModule::t('accessrestriction', 'invalid_mask'));
            }
        }
    }

    public static function callRabbitmq($queue = 'opensips', $exchange = 'e_opensips_msg', $msg = "opensips -x 'mi address_reload'"){
        Yii::$app->amqp->declareExchange($exchange);
        Yii::$app->amqp->declareQueue($queue);
        Yii::$app->amqp->bindQueueExchanger($queue, $exchange);
        Yii::$app->amqp->publish_message($msg, $exchange);
    }
}
