<?php

namespace app\modules\ecosmob\leadperformancereport\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "camp_cdr".
 *
 * @property int $id
 * @property int $contacted_count
 * @property int $noncontacted_count
 * @property int $dialed_count
 * @property int $remaining_count
 */
class LeadPerformanceReport extends ActiveRecord
{
    public $ld_group_name;
    public $contacted_count;
    public $noncontacted_count;
    public $dialed_count;
    public $remaining_count;
    public $redial_contacted_count;
    public $redial_noncontacted_count;
    public $redial_count;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'camp_cdr';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ld_group_name' => 'Lead Group',
            'contacted_count' => 'Contacted Count',
            'noncontacted_count' => 'No Contacted Count',
            'dialed_count' => 'Dialed Count',
            'remaining_count' => 'Remaining Count',
            'redial_contacted_count' => 'Redial Contacted Count',
            'redial_noncontacted_count' => 'Redial No Contacted Count',
        ];
    }
}
