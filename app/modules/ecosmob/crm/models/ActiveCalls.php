<?php

namespace app\modules\ecosmob\crm\models;

use Yii;

/**
 * This is the model class for table "active_calls".
 *
 * @property int $active_id
 * @property int $caller_id
 * @property int $did
 * @property int $destination_number
 * @property int $uuid
 * @property string $status
 * @property string $queue
 * @property int $agent
 * @property string $call_queue_time
 * @property string $call_start_time
 * @property string $call_agent_time
 * @property int $campaign_id
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
            /*[['caller_id', 'did', 'destination_number', 'uuid', 'status', 'queue', 'agent', 'call_queue_time', 'call_start_time', 'call_agent_time'], 'required'],*/
            [['caller_id', 'did', 'destination_number', 'uuid', 'agent'], 'integer'],
            [['call_queue_time', 'call_start_time', 'call_agent_time','campaign_id','whisper_uuid'], 'safe'],
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
