<?php

namespace app\modules\ecosmob\agentperformancereport\models;

use app\modules\ecosmob\supervisor\models\BreakReasonMapping;
use yii\db\ActiveRecord;
use app\modules\ecosmob\agentperformancereport\AgentPerformanceReportModule;

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
class AgentPerformanceReport extends ActiveRecord
{
    public $date;
    public $total_call;
    public $answered;
    public $abandoned;
    public $call_duration;
    public $agent;
    public $break_time;
    public $wait_time;
    public $avg_break_time;
    public $avg_wait_time;
    public $avg_call_duration;
    public $disposion_time;
    public $avg_disposion_time;

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
            [['start_time', 'ans_time', 'end_time', 'call_disposion_start_time', 'agent', 'break_time', 'wait_time', 'avg_break_time', 'avg_wait_time', 'avg_call_duration', 'disposion_time', 'avg_disposion_time'], 'safe'],
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
            'agent' => AgentPerformanceReportModule::t('agentperformancereport', 'agent_name'),
            'call_duration' => AgentPerformanceReportModule::t('agentperformancereport', 'call_duration'),
            'total_call' => AgentPerformanceReportModule::t('agentperformancereport', 'total_call'),
            'answered' => AgentPerformanceReportModule::t('agentperformancereport', 'answered'),
            'abandoned' => AgentPerformanceReportModule::t('agentperformancereport', 'abandoned'),
            'avg_call_duration' => AgentPerformanceReportModule::t('agentperformancereport', 'avg_call_duration'),
            'break_time' => AgentPerformanceReportModule::t('agentperformancereport', 'break_time'),
            'avg_break_time' => AgentPerformanceReportModule::t('agentperformancereport', 'avg_break_time'),
            'wait_time' => AgentPerformanceReportModule::t('agentperformancereport', 'wait_time'),
            'avg_wait_time' => AgentPerformanceReportModule::t('agentperformancereport', 'avg_wait_time'),
            'disposion_time' => AgentPerformanceReportModule::t('agentperformancereport', 'disposion_time'),
            'avg_disposion_time' => AgentPerformanceReportModule::t('agentperformancereport', 'avg_disposion_time'),
        ];
    }

    public static function getTotalBreakTime($from, $to, $userId){
        $data = BreakReasonMapping::find()
            ->select(['SUM(TIMESTAMPDIFF(SECOND ,in_time,out_time)) as total_break_time'])
            ->andWhere(['user_id' => $userId])
            ->andWhere(['>=', 'DATE(in_time)', $from])
            ->andWhere(['<=', 'DATE(out_time)', $to])->asArray()->one();
        if(!empty($data)){
            return $data['total_break_time'];
        }else{
            return 0;
        }
    }

    public static function getAvgBreakTime($from, $to, $userId){
        $data = BreakReasonMapping::find()
            ->select(['AVG(TIMESTAMPDIFF(SECOND ,in_time,out_time)) as avg_break_time'])
            ->andWhere(['user_id' => $userId])
            ->andWhere(['>=', 'DATE(in_time)', $from])
            ->andWhere(['<=', 'DATE(out_time)', $to])->asArray()->one();
        if(!empty($data)){
            return $data['avg_break_time'];
        }else{
            return 0;
        }
    }
}
