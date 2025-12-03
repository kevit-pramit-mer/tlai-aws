<?php

namespace app\modules\ecosmob\extension\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "combined_extensions".
 *
 * @property string $extension
 * @property int $main_id
 * @property string $type
 */
class CombinedExtensions extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'combined_extensions';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['main_id'], 'integer'],
            [['extension'], 'string', 'max' => 100],
            [['type'], 'string', 'max' => 10],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'extension' => 'Extension',
            'main_id' => 'Main ID',
            'type' => 'Type',
        ];
    }
}
