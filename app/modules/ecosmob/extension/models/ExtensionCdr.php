<?php

namespace app\modules\ecosmob\extension\models;

use Yii;

/**
 * This is the model class for table "extension_cdr".
 *
 * @property int $id
 * @property string $from_number
 * @property string $to_number
 * @property string $call_id
 * @property string $start_time
 * @property string $ans_time
 * @property string $end_time
 * @property string $direction
 * @property string $call_type
 */
class ExtensionCdr extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'extension_cdr';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['start_time', 'ans_time', 'end_time', 'call_type'], 'safe'],
            [['from_number', 'to_number', 'call_id', 'direction'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'from_number' => 'From Number',
            'to_number' => 'To Number',
            'call_id' => 'Call ID',
            'start_time' => 'Start Time',
            'ans_time' => 'Ans Time',
            'end_time' => 'End Time',
            'direction' => 'Direction',
            'call_type' => 'Call Type',
        ];
    }
}
