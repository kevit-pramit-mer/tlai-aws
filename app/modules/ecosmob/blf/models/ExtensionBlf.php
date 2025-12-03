<?php

namespace app\modules\ecosmob\blf\models;

use Yii;

/**
 * This is the model class for table "ct_extension_blf".
 *
 * @property int $es_id
 * @property int $em_id
 * @property int $digits
 * @property string $extension
 */
class ExtensionBlf extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ct_extension_blf';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['em_id', 'digits'], 'integer'],
            [['extension'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'es_id' => 'Es ID',
            'em_id' => 'Em ID',
            'digits' => 'Digits',
            'extension' => 'Extension',
        ];
    }
}
