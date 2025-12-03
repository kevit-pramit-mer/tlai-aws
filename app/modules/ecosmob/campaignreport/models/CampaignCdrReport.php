<?php

namespace app\modules\ecosmob\campaignreport\models;

use app\modules\ecosmob\campaignreport\CampaignReportModule;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "camp_cdr".
 *
 * @property int $id
 * @property int $agent_id
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
 * @property string $call_disposion_decription
 */
class CampaignCdrReport extends ActiveRecord
{
    public $date;
    public $total_call;
    public $answered;
    public $abandoned;
    public $call_duration;
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
            [['agent_id'], 'integer'],
            [['caller_id_num', 'dial_number', 'extension_number', 'call_status', 'call_id', 'camp_name'], 'required'],
            [['start_time', 'ans_time', 'end_time', 'call_disposion_start_time', 'total_call', 'call_duration', 'answered', 'abandoned', 'cmp_type'], 'safe'],
            [['caller_id_num', 'dial_number', 'extension_number', 'call_status', 'call_id', 'camp_name', 'call_disposion_name'], 'string', 'max' => 100],
            [['call_disposion_decription'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => CampaignReportModule::t('campaignreport', 'ID'),
            'agent_id' => CampaignReportModule::t('campaignreport', 'Agent ID'),
            'caller_id_num' => CampaignReportModule::t('campaignreport', 'Caller Id Num'),
            'dial_number' => CampaignReportModule::t('campaignreport', 'Dial Number'),
            'extension_number' => CampaignReportModule::t('campaignreport', 'Extension Number'),
            'call_status' => CampaignReportModule::t('campaignreport', 'Call Status'),
            'start_time' => CampaignReportModule::t('campaignreport', 'Start Time'),
            'end_time' => CampaignReportModule::t('campaignreport', 'End Time'),
            'call_id' => CampaignReportModule::t('campaignreport', 'Call ID'),
            'call_disposion_start_time' => CampaignReportModule::t('campaignreport', 'Call Disposion Start Time'),
            'call_disposion_name' => CampaignReportModule::t('campaignreport', 'Call Disposition Name'),
            'call_disposion_decription' => CampaignReportModule::t('campaignreport', 'Call Disposition Description'),
            'ans_time' => CampaignReportModule::t('campaignreport', 'ans_time'),
            'camp_name' => CampaignReportModule::t('campaignreport', 'camp_name'),
            'date' => CampaignReportModule::t('campaignreport', 'date'),
            'total_call' => CampaignReportModule::t('campaignreport', 'total_call'),
            'answered' => CampaignReportModule::t('campaignreport', 'answered'),
            'abandoned' => CampaignReportModule::t('campaignreport', 'abandoned'),
            'call_duration' => CampaignReportModule::t('campaignreport', 'call_duration'),
            'cmp_type' => CampaignReportModule::t('campaignreport', 'cmp_type'),
        ];
    }
}
