<?php

namespace app\modules\ecosmob\queuewisereport\models;

use app\modules\ecosmob\queuewisereport\QueueWiseReportModule;
use yii\mongodb\ActiveRecord;

class QueueWiseReport extends ActiveRecord {

    public static function collectionName () {
        return [$GLOBALS['mongoDBName'], 'uctenant.queue.report'];
    }

    public function rules () {
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

    public function attributes () {
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
            'queue' => QueueWiseReportModule::t('queuewisereport', 'queue'),
            'incoming_call' => QueueWiseReportModule::t('queuewisereport', 'incoming_call'),
            'answered_call' => QueueWiseReportModule::t('queuewisereport', 'answered_call'),
            'answered' => QueueWiseReportModule::t('queuewisereport', 'answered_call'),
            'abandoned' => QueueWiseReportModule::t('queuewisereport', 'abandoned'),
            'agent' => QueueWiseReportModule::t('queuewisereport', 'agent'),
            'avg_waiting_time' => QueueWiseReportModule::t('queuewisereport', 'avg_waiting_time'),
            'date' => QueueWiseReportModule::t('queuewisereport', 'date'),
        ];
    }
}
