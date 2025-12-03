<?php

namespace app\modules\ecosmob\activecalls\models;

use Yii;

/**
 * This is the model class for table "active_calls".
 *
 * @property int $active_id
 * @property string $caller_id
 * @property string $did
 * @property string $destination_number
 * @property string $uuid
 * @property string $status
 * @property string $queue
 * @property int $agent
 * @property string $call_queue_time
 * @property string $call_start_time
 * @property string $call_agent_time
 */
class ActiveCalls extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'active_calls';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['agent'], 'integer'],
            [['call_queue_time', 'call_start_time', 'call_agent_time'], 'safe'],
            [['caller_id', 'did', 'destination_number', 'uuid'], 'string', 'max' => 100],
            [['status', 'queue'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'active_id' => Yii::t('app', 'Active ID'),
            'caller_id' => Yii::t('app', 'Caller ID'),
            'did' => Yii::t('app', 'Did'),
            'destination_number' => Yii::t('app', 'Destination Number'),
            'uuid' => Yii::t('app', 'Uuid'),
            'status' => Yii::t('app', 'Status'),
            'queue' => Yii::t('app', 'Queue'),
            'agent' => Yii::t('app', 'Agent'),
            'call_queue_time' => Yii::t('app', 'Call Queue Time'),
            'call_start_time' => Yii::t('app', 'Call Start Time'),
            'call_agent_time' => Yii::t('app', 'Call Agent Time'),
        ];
    }
}
