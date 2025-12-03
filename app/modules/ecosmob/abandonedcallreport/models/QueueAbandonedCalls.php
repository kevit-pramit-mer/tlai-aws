<?php

namespace app\modules\ecosmob\abandonedcallreport\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "ct_queue_abandoned_calls".
 *
 * @property int $id
 * @property string $queue_name
 * @property string $queue_number
 * @property string $caller_id_number
 * @property string $call_status
 * @property string $start_time
 * @property string $end_time
 * @property string $hold_time
 * @property string $max_wait_reached
 * @property string $breakaway_digit_dialed
 * @property string $abandoned_time
 * @property string $abandoned_wait_time
 * @property string $break_away_wait_time
 */
class QueueAbandonedCalls extends ActiveRecord
{
    public $from;
    public $to;
    public $campaign_name;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ct_queue_abandoned_calls';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['queue_name', 'queue_number', 'caller_id_number', 'call_status', 'hold_time', 'max_wait_reached', 'breakaway_digit_dialed', 'abandoned_time', 'abandoned_wait_time', 'break_away_wait_time'], 'string', 'max' => 100],
            [['start_time', 'end_time'], 'string', 'max' => 255],
            [['queue_number', 'queue_name', 'start_time', 'end_time', 'from', 'to', 'campaign_name'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'queue_name' => Yii::t('app', 'queue_name'),
            'queue_number' => Yii::t('app', 'callee_id'),
            'caller_id_number' => Yii::t('app', 'caller_id'),
            'call_status' => Yii::t('app', 'call_status'),
            'start_time' => Yii::t('app', 'call_started'),
            'end_time' => Yii::t('app', 'call_ended'),
            'hold_time' => Yii::t('app', 'hold_time'),
            'max_wait_reached' => Yii::t('app', 'max_wait_reached'),
            'breakaway_digit_dialed' => Yii::t('app', 'breakaway_digit_dialed'),
            'abandoned_time' => Yii::t('app', 'abandoned_time'),
            'abandoned_wait_time' => Yii::t('app', 'abandoned_wait_time'),
            'break_away_wait_time' => Yii::t('app', 'break_away_wait_time'),
            'campaign_name' => Yii::t('app', 'camp_name'),
        ];
    }
}
