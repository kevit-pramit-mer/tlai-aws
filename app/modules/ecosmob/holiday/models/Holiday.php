<?php

namespace app\modules\ecosmob\holiday\models;

use app\modules\ecosmob\holiday\HolidayModule;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "ct_holiday".
 *
 * @property int $hd_id
 * @property string $hd_holiday
 * @property string $hd_date
 * @property string $hd_end_date
 * @property string $created_date
 * @property string $updated_date
 */
class Holiday extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ct_holiday';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['hd_holiday', 'hd_date', 'hd_end_date'], 'required'],
            [['hd_holiday', 'hd_date'], 'unique'],
            [['hd_holiday'], 'string', 'max' => 50],
            [['hd_holiday'], 'match', 'pattern' => '/^[a-zA-Z\s]*$/', 'message' => HolidayModule::t('hd', 'holiday_contains_character')],
            [['hd_date', 'created_date', 'updated_date'], 'string', 'max' => 40],
            ['hd_end_date', 'compare', 'compareAttribute' => 'hd_date', 'operator' => '>=', 'message' => HolidayModule::t('hd', 'holiday_end_date_greater_than_validation_message')],
            [['hd_date', 'hd_end_date'], 'checkUnique']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'hd_id' => HolidayModule::t('hd', 'hd_id'),
            'hd_holiday' => HolidayModule::t('hd', 'hd_holiday'),
            'hd_date' => HolidayModule::t('hd', 'hd_date'),
            'hd_end_date' => HolidayModule::t('hd', 'hd_end_date'),
            'created_date' => HolidayModule::t('hd', 'created_date'),
            'updated_date' => HolidayModule::t('hd', 'updated_date'),
        ];
    }

    public function checkUnique($attribute)
    {
        if (!empty($this->$attribute)) {
            $date = date('Y-m-d', strtotime($this->$attribute));
            $holiday = Holiday::find()
                ->andWhere(['<=', 'hd_date', $date])
                ->andWhere(['>=', 'hd_end_date', $date]);
            if (!empty($this->hd_id)) {
                $holiday = $holiday->andWhere(['!=', 'hd_id', $this->hd_id]);
            }
            $holiday = $holiday->one();
            if (!empty($holiday)) {
                $this->addError($attribute, 'Holiday already created on this date');
            }
        }
    }
}
