<?php

namespace app\modules\ecosmob\queue\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "tiers".
 *
 * @property string $queue
 * @property string $agent
 * @property string $state
 * @property int $level
 * @property int $position
 */
class Tiers extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tiers';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['level', 'position'], 'integer'],
            [['queue', 'agent', 'state'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'queue' => 'Queue',
            'agent' => 'Agent',
            'state' => 'State',
            'level' => 'Level',
            'position' => 'Position',
        ];
    }
}
