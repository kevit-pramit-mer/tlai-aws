<?php

namespace app\modules\ecosmob\agentswisereport\models;

use app\modules\ecosmob\agentswisereport\AgentsWiseReportModule;
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
class AgentsCallReport extends ActiveRecord
{
    public $call_waiting;
    public $call_duration;
    public $agent_name;
    public $campaign_name;
    public $agent_duration;
    public $date;
    public $disposition_comment;
    public $wrap_up_time;
    public $customer_name;
    public $cmp_type;

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
            [['caller_id_num', 'dial_number', 'extension_number', 'call_status', 'call_id', 'camp_name', 'call_disposion_name'], 'required'],
            [['caller_id_num', 'dial_number', 'extension_number', 'call_status', 'call_id', 'camp_name', 'call_disposion_name', 'recording_file', 'wrap_up_time', 'customer_name', 'cmp_type'], 'safe'],
            [['start_time', 'ans_time', 'end_time', 'call_disposion_start_time'], 'safe'],
            [['caller_id_num', 'dial_number', 'extension_number', 'call_status', 'call_id', 'camp_name', 'call_disposion_name'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => AgentsWiseReportModule::t('agentswisereport', 'id'),
            'caller_id_num' => AgentsWiseReportModule::t('agentswisereport', 'caller_id_num'),
            'dial_number' => AgentsWiseReportModule::t('agentswisereport', 'dial_number'),
            'extension_number' => AgentsWiseReportModule::t('agentswisereport', 'extension_number'),
            'call_status' => AgentsWiseReportModule::t('agentswisereport', 'hangup'),
            'start_time' => AgentsWiseReportModule::t('agentswisereport', 'start_time'),
            'ans_time' => AgentsWiseReportModule::t('agentswisereport', 'ans_time'),
            'end_time' => AgentsWiseReportModule::t('agentswisereport', 'end_time'),
            'call_id' => AgentsWiseReportModule::t('agentswisereport', 'call_id'),
            'camp_name' => AgentsWiseReportModule::t('agentswisereport', 'camp_name'),
            'call_disposion_start_time' => AgentsWiseReportModule::t('agentswisereport', 'call_disposion_start_time'),
            'call_disposion_name' => AgentsWiseReportModule::t('agentswisereport', 'call_disposion_name'),
            'call_duration' => AgentsWiseReportModule::t('agentswisereport', 'call_duration'),
            'agent_name' => AgentsWiseReportModule::t('agentswisereport', 'agent_name'),
            'campaign_name' => AgentsWiseReportModule::t('agentswisereport', 'campaign_name'),
            'agent_duration' => AgentsWiseReportModule::t('agentswisereport', 'agent_duration'),
            'date' => AgentsWiseReportModule::t('agentswisereport', 'date'),
            'disposition_comment' => AgentsWiseReportModule::t('agentswisereport', 'disposition_comment'),
            'wrap_up_time' => AgentsWiseReportModule::t('agentswisereport', 'wrap_up_time'),
            'cmp_type' => AgentsWiseReportModule::t('agentswisereport', 'cmp_type'),
        ];
    }
}
