<?php

namespace app\modules\ecosmob\clienthistory\models;

use app\modules\ecosmob\clienthistory\ClientHistoryModule;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "camp_cdr".
 *
 * @property int $id
 * @property string $caller_id_num
 * @property string $dial_number
 * @property string $extension_number
 * @property string $call_status
 * @property string $start_time
 * @property string $ans_time
 * @property string $end_time
 * @property string $call_id
 * @property string $camp_name
 * @property string $call_disposion_start_time
 * @property string $call_disposion_name
 */
class CampCdr extends ActiveRecord
{
    public $agent_name;
    public $campaign_name;
    // public $call_duration;
    public $agent_first_name;
    public $agent_last_name;
    public $customer_first_name;
    public $customer_last_name;
    public $disposition_comment;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'camp_cdr';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['caller_id_num', 'dial_number', 'extension_number', 'call_status', 'call_id', 'camp_name', 'call_disposion_name', 'agent_first_name', 'agent_last_name', 'customer_first_name', 'customer_last_name', 'lead_member_id', 'current_active_camp'], 'required'],
            [['start_time', 'ans_time', 'end_time', 'call_disposion_start_time', 'queue', 'campaign_name'], 'safe'],
            [['caller_id_num', 'dial_number', 'extension_number', 'call_status', 'call_id', 'camp_name', 'call_disposion_name', 'call_disposion_decription'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'caller_id_num' => Yii::t('app', 'Phone Number'),
            'extension_number' => Yii::t('app', 'Extension Number'),
            'call_status' => Yii::t('app', 'Call Status'),
            'ans_time' => Yii::t('app', 'Ans Time'),
            'end_time' => Yii::t('app', 'End Time'),
            'call_id' => Yii::t('app', 'Call ID'),
            'camp_name' => Yii::t('app', 'Camp Name'),


            'start_time' => ClientHistoryModule::t('clienthistory', 'start_time'),
            'call_disposion_start_time' => ClientHistoryModule::t('clienthistory', 'call_disposion_start_time'),
            'dial_number' => ClientHistoryModule::t('clienthistory', 'dial_number'),
            'call_disposion_name' => ClientHistoryModule::t('clienthistory', 'call_disposion_name'),
            'call_disposion_decription' => ClientHistoryModule::t('clienthistory', 'call_disposion_decription'),
            'agent_name' => ClientHistoryModule::t('clienthistory', 'agent_name'),
            'campaign_name' => ClientHistoryModule::t('clienthistory', 'campaign_name'),
            'customer_first_name' => ClientHistoryModule::t('clienthistory', 'customer_first_name'),
            'customer_last_name' => ClientHistoryModule::t('clienthistory', 'customer_last_name'),
            'agent_first_name' => ClientHistoryModule::t('clienthistory', 'agent_first_name'),
            'agent_last_name' => ClientHistoryModule::t('clienthistory', 'agent_last_name'),
            'disposition_comment' => ClientHistoryModule::t('clienthistory', 'disposition_comment'),

        ];
    }
}
