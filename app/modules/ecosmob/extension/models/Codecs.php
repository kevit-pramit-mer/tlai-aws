<?php

namespace app\modules\ecosmob\extension\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "ct_codecs".
 *
 * @property int $codecs_id
 * @property string $codecs_name
 * @property string $codecs_type
 */
class Codecs extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ct_codecs';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['codecs_name', 'codecs_type'], 'required'],
            [['codecs_type'], 'string'],
            [['codecs_name'], 'string', 'max' => 100],
            [['codecs_name'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'codecs_id' => Yii::t('app', 'Codecs ID'),
            'codecs_name' => Yii::t('app', 'Codecs Name'),
            'codecs_type' => Yii::t('app', 'Codecs Type'),
        ];
    }
}
