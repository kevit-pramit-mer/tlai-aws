<?php

namespace app\models;

use app\modules\ecosmob\didmanagement\DidManagementModule;
use app\modules\ecosmob\didmanagement\models\DidManagement;
use Yii;

/**
 * This is the model class for table "ct_did_time_based".
 *
 * @property int $id
 * @property int $did_id
 * @property string $day
 * @property string $start_time
 * @property string $end_time
 * @property int $after_hour_action_id
 * @property string $after_hour_value
 */
class DidTimeBased extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ct_did_time_based';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['did_id', 'day', 'start_time', 'end_time'/*, 'after_hour_action_id', 'after_hour_value'*/], 'required'],
            /*[['did_id', 'day', 'start_time', 'end_time', 'after_hour_action_id', 'after_hour_value'], 'required',

                'when' => function ($model, $attribute) {

                    return $model->did->is_time_based;

                }],*/
            [['did_id', 'after_hour_action_id'], 'integer'],
            [['day'], 'string'],
            [['start_time', 'end_time'], 'string', 'max' => 20],
            [['after_hour_value'], 'string', 'max' => 100],
           /* [
                'end_time',
                'compare',
                'compareAttribute' => 'start_time',
                'operator' => '>',
                'message' => Yii::t('app', 'end_time_error_msg')
            ],*/
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'did_id' => 'Did ID',
            'day' => 'Day',
            'start_time' => 'Start Time',
            'end_time' => 'End Time',
            'after_hour_action_id' => 'After Hour Action ID',
            'after_hour_value' => 'After Hour Value',
        ];
    }
    public function getDid()
    {
        return $this->hasOne(DidManagement::className(), ['id' => 'did_id']);
    }
}
