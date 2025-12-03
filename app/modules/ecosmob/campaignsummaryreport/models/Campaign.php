<?php

namespace app\modules\ecosmob\campaignsummaryreport\models;

use app\modules\ecosmob\campaignsummaryreport\CampaignSummaryReportModule;
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
class Campaign extends ActiveRecord
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
            [['start_time', 'ans_time', 'end_time', 'call_disposion_start_time', 'cmp_type'], 'safe'],
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

            'camp_name' => CampaignSummaryReportModule::t('campaignsummaryreport', 'camp_name'),
            'call_duration' => CampaignSummaryReportModule::t('campaignsummaryreport', 'call_duration'),
            'total_call' => CampaignSummaryReportModule::t('campaignsummaryreport', 'total_call'),
            'answered' => CampaignSummaryReportModule::t('campaignsummaryreport', 'answered'),
            'abandoned' => CampaignSummaryReportModule::t('campaignsummaryreport', 'abandoned'),
            'cmp_type' => CampaignSummaryReportModule::t('campaignsummaryreport', 'cmp_type'),
        ];
    }
}
