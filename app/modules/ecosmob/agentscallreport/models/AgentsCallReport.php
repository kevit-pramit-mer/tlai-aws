<?php

namespace app\modules\ecosmob\agentscallreport\models;

use app\modules\ecosmob\agentscallreport\AgentsCallReportModule;
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
    public $call_waiting, $call_duration, $agent_name, $campaign_name, $agent_duration, $date, $disposition_comment, $customer_name, $wrap_up_time, $cmp_type;

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
            [['start_time', 'ans_time', 'end_time', 'call_disposion_start_time', 'campaign_name', 'queue', 'customer_name', 'recording_file', 'wrap_up_time', 'cmp_type'], 'safe'],
            [['caller_id_num', 'dial_number', 'extension_number', 'call_status', 'call_id', 'camp_name', 'call_disposion_name'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => AgentsCallReportModule::t('agentscallreport', 'id'),
            'caller_id_num' => AgentsCallReportModule::t('agentscallreport', 'caller_id_num'),
            'dial_number' => AgentsCallReportModule::t('agentscallreport', 'dial_number'),
            'extension_number' => AgentsCallReportModule::t('agentscallreport', 'extension_number'),
            'call_status' => AgentsCallReportModule::t('agentscallreport', 'call_status'),
            'start_time' => AgentsCallReportModule::t('agentscallreport', 'start_time'),
            'ans_time' => AgentsCallReportModule::t('agentscallreport', 'ans_time'),
            'end_time' => AgentsCallReportModule::t('agentscallreport', 'end_time'),
            'call_id' => AgentsCallReportModule::t('agentscallreport', 'call_id'),
            'camp_name' => AgentsCallReportModule::t('agentscallreport', 'camp_name'),
            'call_disposion_start_time' => AgentsCallReportModule::t('agentscallreport', 'call_disposion_start_time'),
            'call_disposion_name' => AgentsCallReportModule::t('agentscallreport', 'call_disposion_name'),
            'call_duration' => AgentsCallReportModule::t('agentscallreport', 'call_duration'),
            'agent_name' => AgentsCallReportModule::t('agentscallreport', 'agent_name'),
            'campaign_name' => AgentsCallReportModule::t('agentscallreport', 'campaign_name'),
            'agent_duration' => AgentsCallReportModule::t('agentscallreport', 'agent_duration'),
            'date' => AgentsCallReportModule::t('agentscallreport', 'date'),
            'disposition_comment' => AgentsCallReportModule::t('agentscallreport', 'disposition_comment'),
            'customer_name' => AgentsCallReportModule::t('agentscallreport', 'customer_name'),
            'wrap_up_time' => AgentsCallReportModule::t('agentscallreport', 'wrap_up_time'),
            'cmp_type' => AgentsCallReportModule::t('agentscallreport', 'cmp_type'),
        ];
    }
}
