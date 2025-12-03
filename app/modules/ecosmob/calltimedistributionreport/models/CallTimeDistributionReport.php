<?php

namespace app\modules\ecosmob\calltimedistributionreport\models;

use yii\mongodb\ActiveRecord;
use app\modules\ecosmob\calltimedistributionreport\CallTimeDistributionReportModule;

class CallTimeDistributionReport extends ActiveRecord
{
    public static function collectionName()
    {
        return [$GLOBALS['mongoDBName'], 'uctenant.queue.report'];
    }

    public function rules()
    {
        return [
            [
                [
                    '_id',
                    'queue_uuid',
                    'queue_number',
                    'caller_id_number',
                    'queue_started',
                    'queue_answered',
                    'queue_ended',
                    'agent_answered_num',
                    'agent_answer_duration',
                    'hold_time',
                    'max_wait_reached',
                    'breakaway_digit_dialed',
                    'abandoned_time',
                    'queue_name',
                    'call_status',
                    'abandoned_wait_time',
                    'break_away_wait_time',
                    'duration',
                    'billsec',
                    'qm_id'
                ],
                'safe',
            ],
        ];
    }

    public function attributes()
    {
        return [
            '_id',
            'queue_uuid',
            'queue_number',
            'caller_id_number',
            'queue_started',
            'queue_answered',
            'queue_ended',
            'agent_answered_num',
            'agent_answer_duration',
            'hold_time',
            'max_wait_reached',
            'breakaway_digit_dialed',
            'abandoned_time',
            'queue_name',
            'call_status',
            'abandoned_wait_time',
            'break_away_wait_time',
            'duration',
            'billsec',
            'qm_id'
        ];
    }
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'queue' => CallTimeDistributionReportModule::t('calltimedistributionreport', 'queue'),
            'total_call' => CallTimeDistributionReportModule::t('calltimedistributionreport', 'total_call'),
            'avg_wait_time' => CallTimeDistributionReportModule::t('calltimedistributionreport', 'avg_wait_time'),
            'answer_call_30' => CallTimeDistributionReportModule::t('calltimedistributionreport', 'answered_thirty'),
            'drop_call_30' => CallTimeDistributionReportModule::t('calltimedistributionreport', 'dropped_thirty'),
            'answer_call_60' => CallTimeDistributionReportModule::t('calltimedistributionreport', 'answered_sixty'),
            'drop_call_60' => CallTimeDistributionReportModule::t('calltimedistributionreport', 'dropped_sixty'),
            'answer_call_3' => CallTimeDistributionReportModule::t('calltimedistributionreport', 'answered_one'),
            'drop_call_3' => CallTimeDistributionReportModule::t('calltimedistributionreport', 'dropped_one'),
            'answer_call_5' => CallTimeDistributionReportModule::t('calltimedistributionreport', 'answered_three'),
            'drop_call_5' => CallTimeDistributionReportModule::t('calltimedistributionreport', 'dropped_three'),
            'answer_call_5_plush' => CallTimeDistributionReportModule::t('calltimedistributionreport', 'answered_five'),
            'drop_call_5_plush' => CallTimeDistributionReportModule::t('calltimedistributionreport', 'dropped_five'),
        ];
    }

    public static function getTotal($provider, $fieldName)
    {
        $total = 0;

        foreach ($provider as $item) {
            $total += $item[$fieldName];
        }

        return $total;
    }
}
