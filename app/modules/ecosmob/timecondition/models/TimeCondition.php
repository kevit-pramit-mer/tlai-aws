<?php

namespace app\modules\ecosmob\timecondition\models;

use app\modules\ecosmob\jobs\models\Job;
use app\modules\ecosmob\timecondition\TimeConditionModule;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "ct_time_condition".
 *
 * @property int $tc_id
 * @property string $tc_name
 * @property string $tc_description
 * @property string $tc_start_time
 * @property string $tc_end_time
 * @property string $tc_start_day
 * @property string $tc_end_day
 * @property string $tc_start_date
 * @property string $tc_end_date
 * @property string $tc_start_month
 * @property string $tc_end_month
 * @property string $created_date
 * @property string $updated_date
 */
class TimeCondition extends ActiveRecord
{
    public $month = [
        'JANUARY' => '1',
        'FEBRUARY' => '2',
        'MARCH' => '3',
        'APRIL' => '4',
        'MAY' => '5',
        'JUNE' => '6',
        'JULY' => '7',
        'AUGUST' => '8',
        'SEPTEMBER' => '9',
        'OCTOBER' => '10',
        'NOVEMBER' => '11',
        'DECEMBER' => '12'
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ct_time_condition';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tc_name', 'tc_start_time', 'tc_end_time', 'tc_start_day', 'tc_end_day', 'tc_start_date', 'tc_end_date', 'tc_start_month', 'tc_end_month'], 'required'],
            ['tc_name', 'unique'],
            ['tc_start_time', 'match', 'pattern' => '/^(?:2[0-4]|[01][0-9]|10):([0-5][0-9]):([0-5][0-9])$/', 'message' => TimeConditionModule::t('tc', 'entered_time_is_not_valid')],
            ['tc_end_time', 'match', 'pattern' => '/^(?:2[0-4]|[01][0-9]|10):([0-5][0-9]):([0-5][0-9])$/', 'message' => TimeConditionModule::t('tc', 'entered_time_is_not_valid')],
            [['tc_start_day', 'tc_end_day', 'tc_start_date', 'tc_end_date', 'tc_start_month', 'tc_end_month'], 'string'],
            [['tc_name'], 'string', 'max' => 30],
            [['tc_description'], 'string', 'max' => 255],
            [['tc_start_time', 'tc_end_time'], 'string', 'max' => 20],
            [['created_date', 'updated_date'], 'string', 'max' => 40],
            [
                [
                    'tc_name',
                    'tc_start_time',
                    'tc_end_time',
                    'tc_start_day',
                    'tc_end_day',
                    'tc_start_date',
                    'tc_end_date',
                    'tc_start_month',
                    'tc_end_month',
                ],
                'required',
            ],
            [
                'tc_start_time',
                'match',
                'pattern' => '/^(?:2[0-4]|[01][0-9]|10):([0-5][0-9]):([0-5][0-9])$/',
                'message' => 'Entered time is not valid.',
            ],
            [
                'tc_end_time',
                'match',
                'pattern' => '/^(?:2[0-4]|[01][0-9]|10):([0-5][0-9]):([0-5][0-9])$/',
                'message' => 'Entered time is not valid.',
            ],
            [['tc_start_day', 'tc_end_day', 'tc_start_date', 'tc_end_date', 'tc_start_month', 'tc_end_month'], 'string'],
            [['tc_name'], 'string', 'max' => 111],
            [['tc_description'], 'string', 'max' => 255],
            [['tc_start_time', 'tc_end_time'], 'string', 'max' => 20],
            [['created_date', 'updated_date'], 'string', 'max' => 40],
            ['tc_end_time', 'compare', 'compareAttribute' => 'tc_start_time', 'operator' => '>', 'message' => TimeConditionModule::t('tc', 'start_time_must_be_greater_than_end_time')],
            [['tc_end_month'], 'checkEndMonth'],
            [['tc_start_date'], 'checkStartDate'],
            [['tc_end_date'], 'checkEndDate'],
            /* [['tc_start_month', 'tc_end_month'], 'number'],
             ['tc_start_month', 'compare', 'compareAttribute' => 'tc_end_month', 'operator' => '<', 'message' => TimeConditionModule::t('tc', 'start_month_must_be_less_than_end_month')],
             ['tc_end_month', 'compare', 'compareAttribute' => 'tc_start_month', 'operator' => '>', 'message' => TimeConditionModule::t('tc', 'end_month_must_be_greater_than_start_month')],*/

        ];
    }

    /**
     * @param $attribute
     */
    public function checkStartDate($attribute)
    {
        if ((strtoupper($this->tc_start_month) == 'FEBRUARY' && $this->tc_start_date > 29)) {
            $this->addError($attribute, 'Invalid Date.');
        }
        if ((strtoupper($this->tc_start_month) == 'APRIL' && $this->tc_start_date > 30)) {
            $this->addError($attribute, 'Invalid Date.');
        }
        if ((strtoupper($this->tc_start_month) == 'JUNE' && $this->tc_start_date > 30)) {
            $this->addError($attribute, 'Invalid Date.');
        }
        if ((strtoupper($this->tc_start_month) == 'SEPTEMBER' && $this->tc_start_date > 30)) {
            $this->addError($attribute, 'Invalid Date.');
        }
        if ((strtoupper($this->tc_start_month) == 'NOVEMBER' && $this->tc_start_date > 30)) {
            $this->addError($attribute, 'Invalid Date.');
        }
    }

    public function checkEndDate($attribute)
    {
        if ((strtoupper($this->tc_end_month) == 'FEBRUARY' && $this->tc_end_date > 29)) {
            $this->addError($attribute, 'Invalid Date.');
        }
        if ((strtoupper($this->tc_end_month) == 'APRIL' && $this->tc_end_date > 30)) {
            $this->addError($attribute, 'Invalid Date.');
        }
        if ((strtoupper($this->tc_end_month) == 'JUNE' && $this->tc_end_date > 30)) {
            $this->addError($attribute, 'Invalid Date.');
        }
        if ((strtoupper($this->tc_end_month) == 'SEPTEMBER' && $this->tc_end_date > 30)) {
            $this->addError($attribute, 'Invalid Date.');
        }
        if ((strtoupper($this->tc_end_month) == 'NOVEMBER' && $this->tc_end_date > 30)) {
            $this->addError($attribute, 'Invalid Date.');
        }
    }

    public function checkEndMonth($attribute)
    {
        if ($this->month[$this->tc_start_month] > $this->month[$this->tc_end_month]) {
            $this->addError($attribute, TimeConditionModule::t('tc', 'end_month_must_be_greater_than_start_month'));
        }
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'tc_id' => TimeConditionModule::t('tc', 'tc_id'),
            'tc_name' => TimeConditionModule::t('tc', 'tc_name'),
            'tc_description' => TimeConditionModule::t('tc', 'tc_description'),
            'tc_start_time' => TimeConditionModule::t('tc', 'tc_start_time'),
            'tc_end_time' => TimeConditionModule::t('tc', 'tc_end_time'),
            'tc_start_day' => TimeConditionModule::t('tc', 'tc_start_day'),
            'tc_end_day' => TimeConditionModule::t('tc', 'tc_end_day'),
            'tc_start_date' => TimeConditionModule::t('tc', 'tc_start_date'),
            'tc_end_date' => TimeConditionModule::t('tc', 'tc_end_date'),
            'tc_start_month' => TimeConditionModule::t('tc', 'tc_start_month'),
            'tc_end_month' => TimeConditionModule::t('tc', 'tc_end_month'),
            'created_date' => TimeConditionModule::t('tc', 'created_date'),
            'updated_date' => TimeConditionModule::t('tc', 'updated_date'),
        ];
    }

    /**
     * @param $id
     * @return bool
     */
    public function canDelete($id)
    {
        $timeConditionCount = Job::find()->where(['time_id' => $id])->count();

        if ($timeConditionCount == 0) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

}
