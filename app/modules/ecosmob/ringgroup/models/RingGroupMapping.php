<?php

namespace app\modules\ecosmob\ringgroup\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "ct_ring_group_mapping".
 *
 * @property int $rm_id
 * @property int $rg_id
 * @property string $rm_type
 * @property string $rm_number
 * @property int $rm_priority priority number wise
 */
class RingGroupMapping extends ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ct_ring_group_mapping';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['rg_id', 'rm_type', 'rm_number', 'rm_priority'], 'required'],
            [['rg_id', 'rm_priority'], 'integer'],
            [['rm_type'], 'string'],
            [['rm_number'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'rm_id' => 'Rm ID',
            'rg_id' => 'Rg ID',
            'rm_type' => 'Rm Type',
            'rm_number' => 'Rm Number',
            'rm_priority' => 'Rm Priority',
        ];
    }
}
