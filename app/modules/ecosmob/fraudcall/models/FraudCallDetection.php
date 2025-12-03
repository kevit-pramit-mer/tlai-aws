<?php

namespace app\modules\ecosmob\fraudcall\models;

use app\modules\ecosmob\fraudcall\FraudCallModule;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "ct_fraud_call_detection".
 *
 * @property int $fcd_id
 * @property string $fcd_rule_name
 * @property string $fcd_destination_prefix
 * @property string $fcd_call_duration
 * @property int $fcd_call_period
 * @property string $fcd_notify_email
 */
class FraudCallDetection extends ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ct_fraud_call_detection';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fcd_rule_name', 'fcd_call_period', 'fcd_destination_prefix', 'fcd_call_duration', 'fcd_notify_email', 'blocked_by'], 'required'],
            [['fcd_rule_name'], 'unique'],
            [['fcd_call_period'], 'integer', 'min' => 1, 'max' => 10000000000],
            [['fcd_rule_name'], 'string', 'max' => 30],
            ['fcd_call_duration', 'integer', 'min' => 1, 'max' => 10000000000],
            [['fcd_destination_prefix'], 'string', 'max' => 30],
            [['fcd_call_duration', 'fcd_call_period'], 'string', 'max' => 10],
            [['fcd_notify_email'], 'email'],
            [['fcd_notify_email'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'fcd_id' => FraudCallModule::t('fcd', 'id'),
            'fcd_rule_name' => FraudCallModule::t('fcd', 'rule_name'),
            'fcd_destination_prefix' => FraudCallModule::t('fcd', 'prefix'),
            'fcd_call_duration' => FraudCallModule::t('fcd', 'call_duration'),
            'fcd_call_period' => FraudCallModule::t('fcd', 'call_period'),
            'fcd_notify_email' => FraudCallModule::t('fcd', 'email'),
            'blocked_by' => FraudCallModule::t('fcd', 'blocked_by'),
        ];
    }
}
