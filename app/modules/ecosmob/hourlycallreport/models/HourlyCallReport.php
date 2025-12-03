<?php

namespace app\modules\ecosmob\hourlycallreport\models;

use app\modules\ecosmob\campaignsummaryreport\CampaignSummaryReportModule;
use yii\db\ActiveRecord;
use app\modules\ecosmob\hourlycallreport\HourlyCallReportModule;

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
class HourlyCallReport extends ActiveRecord
{
    public $date;
    public $total_call;
    public $answered;
    public $abandoned;
    public $call_duration;
    public $hours;

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
            [['start_time', 'ans_time', 'end_time', 'call_disposion_start_time', 'hours'], 'safe'],
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
            'hours' => HourlyCallReportModule::t('hourlycallreport', 'hour'),
            'camp_name' => HourlyCallReportModule::t('hourlycallreport', 'camp_name'),
            'call_duration' => HourlyCallReportModule::t('hourlycallreport', 'call_duration'),
            'total_call' => HourlyCallReportModule::t('hourlycallreport', 'total_call'),
            'answered' => HourlyCallReportModule::t('hourlycallreport', 'answered'),
            'abandoned' => HourlyCallReportModule::t('hourlycallreport', 'abandoned'),
        ];
    }
}
