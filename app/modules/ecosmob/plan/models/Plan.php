<?php

namespace app\modules\ecosmob\plan\models;

use app\modules\ecosmob\extension\models\Extension;
use app\modules\ecosmob\plan\PlanModule;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "ct_plan".
 *
 * @property int $pl_id
 * @property string $pl_name
 * @property string $pl_holiday
 * @property string $pl_week_off
 * @property string $pl_bargain
 * @property string $pl_dnd
 * @property string $pl_park
 * @property string $pl_transfer
 * @property string $pl_call_record
 * @property string $pl_white_list
 * @property string $pl_black_list
 * @property string $pl_caller_id_block
 * @property string $pl_universal_forward
 * @property string $pl_no_ans_forward
 * @property string $pl_busy_forward
 * @property string $pl_timebase_forward
 * @property string $pl_selective_forward
 * @property string $pl_shift_forward
 * @property string $pl_unavailable_forward
 * @property string $pl_redial
 * @property string $pl_call_return
 * @property string $pl_busy_callback
 * @property string $created_date
 * @property string $updated_date
 */
class Plan extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ct_plan';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pl_holiday', 'pl_week_off', 'pl_bargain', 'pl_dnd', 'pl_park', 'pl_transfer', 'pl_call_record', 'pl_white_list', 'pl_black_list', 'pl_caller_id_block', 'pl_universal_forward', 'pl_no_ans_forward', 'pl_busy_forward', 'pl_timebase_forward', 'pl_selective_forward', 'pl_shift_forward', 'pl_unavailable_forward', 'pl_redial', 'pl_call_return', 'pl_busy_callback'
            ], 'string'],
            ['pl_name', 'required'],
            ['pl_name', 'unique'],
            [['created_date', 'updated_date', 'pl_name'], 'string', 'min' => 2, 'max' => 40],
            [['pl_name'], 'match', 'pattern' => '/^[0-9a-zA-Z\s]*$/', 'message' => PlanModule::t('pl', 'plan_name_contains_alphanumeric_value')],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'pl_id' => PlanModule::t('pl', 'pl_id'),
            'pl_name' => PlanModule::t('pl', 'pl_name'),
            'pl_holiday' => PlanModule::t('pl', 'pl_holiday'),
            'pl_week_off' => PlanModule::t('pl', 'pl_week_off'),
            'pl_bargain' => PlanModule::t('pl', 'pl_bargain'),
            'pl_dnd' => PlanModule::t('pl', 'pl_dnd'),
            'pl_park' => PlanModule::t('pl', 'pl_park'),
            'pl_transfer' => PlanModule::t('pl', 'pl_transfer'),
            'pl_call_record' => PlanModule::t('pl', 'pl_call_record'),
            'pl_white_list' => PlanModule::t('pl', 'pl_white_list'),
            'pl_black_list' => PlanModule::t('pl', 'pl_black_list'),
            'pl_caller_id_block' => PlanModule::t('pl', 'pl_caller_id_block'),
            'pl_universal_forward' => PlanModule::t('pl', 'pl_universal_forward'),
            'pl_no_ans_forward' => PlanModule::t('pl', 'pl_no_ans_forward'),
            'pl_busy_forward' => PlanModule::t('pl', 'pl_busy_forward'),
            'pl_timebase_forward' => PlanModule::t('pl', 'pl_timebase_forward'),
            'pl_selective_forward' => PlanModule::t('pl', 'pl_selective_forward'),
            'pl_shift_forward' => PlanModule::t('pl', 'pl_shift_forward'),
            'pl_unavailable_forward' => PlanModule::t('pl', 'pl_unavailable_forward'),
            'pl_redial' => PlanModule::t('pl', 'pl_redial'),
            'pl_call_return' => PlanModule::t('pl', 'pl_call_return'),
            'pl_busy_callback' => PlanModule::t('pl', 'pl_busy_callback'),
            'created_date' => PlanModule::t('pl', 'created_date'),
            'updated_date' => PlanModule::t('pl', 'updated_date'),
        ];
    }

    public function canDelete($id)
    {
        /** @var Job $jobCount */
        $planCount = Extension::find()->where(['em_plan_id' => $id])->count();

        if ($planCount == 0) {
            return FALSE;
        } else {
            return TRUE;
        }
    }
}
