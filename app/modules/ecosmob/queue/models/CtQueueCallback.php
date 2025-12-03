<?php

namespace app\modules\ecosmob\queue\models;


use yii\db\ActiveRecord;

/**
 * This is the model class for table "ct_queue_callback".
 *
 * @property int $id
 * @property string $queue_name
 * @property string $phone_number
 * @property string $created_at
 */
//class QueueMaster extends \yii\db\ActiveRecord
class CtQueueCallback extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ct_queue_callback';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created_at', 'phone_number', 'queue_name'], 'safe'],
            [['queue_name', 'phone_number'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'queue_name' => 'Queue Name',
            'phone_number' => 'Phone Number',
            'created_at' => 'Created At',
        ];
    }
}
