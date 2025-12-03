<?php

namespace app\modules\ecosmob\shift\models;

use app\modules\ecosmob\extension\models\Extension;
use app\modules\ecosmob\shift\ShiftModule;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "ct_shift".
 *
 * @property int $sft_id
 * @property string $sft_name
 * @property string $sft_start_time
 * @property string $sft_end_time
 * @property string $created_date
 * @property string $updated_date
 */
class Shift extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ct_shift';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sft_name', 'sft_start_time', 'sft_end_time'], 'required'],
            ['sft_name', 'unique'],
            [
                'sft_start_time',
                'match',
                'pattern' => '/^(?:2[0-4]|[01][0-9]|10):([0-5][0-9]):([0-5][0-9])$/',
                'message' => ShiftModule::t('sft', 'entered_time_is_not_valid')
            ],
            [
                'sft_end_time',
                'match',
                'pattern' => '/^(?:2[0-4]|[01][0-9]|10):([0-5][0-9]):([0-5][0-9])$/',
                'message' => ShiftModule::t('sft', 'entered_time_is_not_valid')
            ],
            [['sft_name'], 'string', 'min' => 2, 'max' => 40],
            [['sft_start_time', 'sft_end_time'], 'string', 'max' => 20],
            [['created_date', 'updated_date'], 'string', 'max' => 40],
            [
                'sft_end_time',
                'compare',
                'compareAttribute' => 'sft_start_time',
                'operator' => '>',
                'message' => ShiftModule::t('sft', 'end_time_must_be_greater_then_start_time')
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'sft_id' => ShiftModule::t('sft', 'sft_id'),
            'sft_name' => ShiftModule::t('sft', 'sft_name'),
            'sft_start_time' => ShiftModule::t('sft', 'sft_start_time'),
            'sft_end_time' => ShiftModule::t('sft', 'sft_end_time'),
            'created_date' => ShiftModule::t('sft', 'created_date'),
            'updated_date' => ShiftModule::t('sft', 'updated_date'),
        ];
    }

    public function canDelete($id)
    {
        /** @var Extension $extensionCount */
        $extensionCount = Extension::find()->where(['em_shift_id' => $id])->count();

        if ($extensionCount == 0) {
            return FALSE;
        } else {
            return TRUE;
        }
    }
}
