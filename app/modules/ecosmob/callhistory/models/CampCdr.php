<?php

namespace app\modules\ecosmob\callhistory\models;

use app\modules\ecosmob\callhistory\CallHistoryModule;
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
    public $call_waiting;
    public $call_duration;
    public $did;
    public $campaign_name;
    public $agent_duration;
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
            /*[['caller_id_num', 'dial_number', 'extension_number', 'call_status', 'call_id', 'camp_name', 'call_disposion_name'], 'required'],*/
            [['start_time', 'ans_time', 'end_time', 'call_disposion_start_time', 'caller_id_num', 'dial_number', 'extension_number', 'call_status', 'call_id', 'camp_name', 'call_disposion_name', 'call_disposion_decription', 'queue', 'agent_first_name', 'agent_last_name', 'customer_first_name', 'customer_last_name', 'recording_file', 'lead_member_id', 'current_active_camp'], 'safe'],
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
            'extension_number' => Yii::t('app', 'Extension Number'),
            'call_id' => Yii::t('app', 'Call ID'),
            'camp_name' => Yii::t('app', 'Campaign Name'),
            'call_disposion_start_time' => Yii::t('app', 'Call Disposion Start Time'),
            'call_status' => CallHistoryModule::t('callhistory', 'hangup'),
            'call_disposion_name' => CallHistoryModule::t('callhistory', 'call_disposion_name'),
            'call_disposion_decription' => CallHistoryModule::t('callhistory', 'call_disposion_decription'),
            'end_time' => Yii::t('app', 'call_ended'),
            'start_time' => Yii::t('app', 'call_started'),
            'ans_time' => Yii::t('app', 'call_answered'),
            'caller_id_num' => Yii::t('app', 'caller_id'),
            'dial_number' => CallHistoryModule::t('callhistory', 'dial_number'),
            'call_waiting' => CallHistoryModule::t('callhistory', 'call_waiting'),
            'call_duration' => CallHistoryModule::t('callhistory', 'call_duration'),
            'did' => CallHistoryModule::t('callhistory', 'did'),
            'queue' => CallHistoryModule::t('callhistory', 'queue'),
            'campaign_name' => CallHistoryModule::t('callhistory', 'campaign_name'),
            'agent_duration' => CallHistoryModule::t('callhistory', 'agent_duration'),
            'customer_first_name' => CallHistoryModule::t('callhistory', 'customer_first_name'),
            'customer_last_name' => CallHistoryModule::t('callhistory', 'customer_last_name'),
            'agent_first_name' => CallHistoryModule::t('callhistory', 'agent_first_name'),
            'agent_last_name' => CallHistoryModule::t('callhistory', 'agent_last_name'),
            'disposition_comment' => CallHistoryModule::t('callhistory', 'disposition_comment'),
        ];
    }
}
