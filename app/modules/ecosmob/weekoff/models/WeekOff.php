<?php

namespace app\modules\ecosmob\weekoff\models;

use app\modules\ecosmob\weekoff\WeekOffModule;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "ct_week_off".
 *
 * @property int $wo_id
 * @property string $wo_day
 * @property string $wo_start_time
 * @property string $wo_end_time
 * @property string $created_date
 * @property string $updated_date
 */
class WeekOff extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ct_week_off';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['wo_day', 'wo_start_time', 'wo_end_time'], 'required'],
            ['wo_day', 'unique'],
            ['wo_start_time', 'match', 'pattern' => '/^(?:2[0-4]|[01][1-9]|10|00):([0-5][0-9]):([0-5][0-9])$/', 'message' => WeekOffModule::t('wo', 'entered_time_is_not_valid')],
            ['wo_end_time', 'match', 'pattern' => '/^(?:2[0-4]|[01][1-9]|10|00):([0-5][0-9]):([0-5][0-9])$/', 'message' => WeekOffModule::t('wo', 'entered_time_is_not_valid')],
            [['wo_day'], 'string'],
            ['wo_end_time', 'compare', 'compareAttribute' => 'wo_start_time', 'operator' => '>', 'message' => WeekOffModule::t('wo', 'end_time_must_be_greater_than_start_time')],
            [['wo_start_time', 'wo_end_time'], 'string', 'max' => 20],
            [['created_date', 'updated_date'], 'string', 'max' => 40],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'wo_id' => WeekOffModule::t('wo', 'wo_id'),
            'wo_day' => WeekOffModule::t('wo', 'wo_day'),
            'wo_start_time' => WeekOffModule::t('wo', 'wo_start_time'),
            'wo_end_time' => WeekOffModule::t('wo', 'wo_end_time'),
            'created_date' => WeekOffModule::t('wo', 'created_date'),
            'updated_date' => WeekOffModule::t('wo', 'updated_date'),
        ];
    }
}
