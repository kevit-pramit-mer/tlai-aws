<?php

namespace app\modules\ecosmob\queuecallback\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "ct_queue_callback".
 *
 * @property int $id
 * @property string $queue_name
 * @property string $phone_number
 * @property string $created_at
 */
class QueueCallback extends ActiveRecord
{
    public $campaign_name;
    public $date;

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
            [['created_at', 'date', 'campaign_name'], 'safe'],
            [['queue_name', 'phone_number'], 'string', 'max' => 100],
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
            'phone_number' => Yii::t('app', 'caller_id'),
            'created_at' => Yii::t('app', 'date'),
            'campaign_name' => Yii::t('app', 'camp_name'),
        ];
    }
}
