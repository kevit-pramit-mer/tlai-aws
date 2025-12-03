<?php

namespace app\modules\ecosmob\dialplan\models;

use app\modules\ecosmob\dialplan\DialPlanModule;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "ct_outbound_dial_plans_details".
 *
 * @property int $odpd_id
 * @property string $odpd_prefix_match_string Prefix matching rule
 * @property int $trunk_grp_id             ID of trunk group to route call
 * @property string $odpd_strip_prefix        Define prefix to strip from destination number.It will depend on strip prefix rule type. If
 *           DIGITS_COUNT : strip defined number of digits(in case of negative,strip from behind).If PREFIX : Strip exact prefix . If NULL : Do
 *           nothing
 * @property string $odpd_add_prefix          Prefix to add before sending call to destination.If NULL do nothing
 */
class OutboundDialPlansDetails extends ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ct_outbound_dial_plans_details';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['odpd_prefix_match_string', 'trunk_grp_id'/*, 'odpd_strip_prefix', 'odpd_add_prefix'*/], 'required'],
            [['odpd_prefix_match_string'], 'unique'],
            [['trunk_grp_id'], 'integer'],
            [['odpd_prefix_match_string', 'odpd_strip_prefix', 'odpd_add_prefix'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'odpd_id' => DialPlanModule::t('dp', 'id'),
            'odpd_prefix_match_string' => DialPlanModule::t('dp', 'prefix_match_string'),
            'trunk_grp_id' => DialPlanModule::t('dp', 'trunk_grp_id'),
            'odpd_strip_prefix' => DialPlanModule::t('dp', 'strip_prefix'),
            'odpd_add_prefix' => DialPlanModule::t('dp', 'add_prefix'),
        ];
    }
}
