<?php

namespace app\modules\ecosmob\breaks\models;

use app\modules\ecosmob\breaks\BreaksModule;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "ct_breaks".
 *
 * @property int $br_id
 * @property string $br_reason
 * @property string $br_description
 */
class Breaks extends ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ct_breaks';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['br_reason'], 'required'],
            [['br_reason'], 'string', 'max' => 50],
            [['br_description'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'br_id' => BreaksModule::t('breaks', 'id'),
            'br_reason' => BreaksModule::t('breaks', 'reason'),
            'br_description' => BreaksModule::t('breaks', 'description'),
        ];
    }
}
