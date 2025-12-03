<?php

namespace app\modules\ecosmob\dispositionreport\models;

use yii\db\ActiveRecord;
use app\modules\ecosmob\dispositionreport\DispositionReportModule;

/**
 * This is the model class for table "camp_cdr".
 *
 * @property int $id
 * @property string $caller_id_num
 * @property string $dial_number
 * @property string $extension_number
 * @property string $call_status
 * @property string $start_time
 * @property string $ans_time
 * @property string $end_time
 * @property string $call_id
 * @property string $camp_name
 * @property string $call_disposion_start_time
 * @property string $call_disposion_name
 */
class DispositionReport extends ActiveRecord
{
    public $date;
    public $call_count;

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
            [['caller_id_num', 'dial_number', 'extension_number', 'call_status', 'call_id', 'camp_name', 'call_disposion_name'], 'required'],
            [['caller_id_num', 'dial_number', 'extension_number', 'call_status', 'call_id', 'camp_name', 'call_disposion_name', 'recording_file'], 'safe'],
            [['start_time', 'ans_time', 'end_time', 'call_disposion_start_time'], 'safe'],
            [['caller_id_num', 'dial_number', 'extension_number', 'call_status', 'call_id', 'camp_name', 'call_disposion_name'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'call_disposion_name' => DispositionReportModule::t('dispositionreport', 'disposition_type'),
            'call_count' => DispositionReportModule::t('dispositionreport', 'call_count'),
            'total_call' => DispositionReportModule::t('dispositionreport', 'total_call'),
        ];
    }
}
