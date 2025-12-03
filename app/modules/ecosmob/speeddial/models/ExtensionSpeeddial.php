<?php

namespace app\modules\ecosmob\speeddial\models;

use app\modules\ecosmob\speeddial\SpeeddialModule;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "ct_extension_speeddial".
 *
 * @property int $es_id
 * @property string $es_extension
 * @property string $es_*0
 */
class ExtensionSpeeddial extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ct_extension_speeddial';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['es_extension', 'es_0', 'es_1', 'es_2', 'es_3', 'es_4', 'es_5', 'es_6', 'es_7', 'es_8', 'es_9'], 'string', 'max' => 30],
            [['es_extension'], 'required'],
            [['es_extension', 'es_0', 'es_1', 'es_2', 'es_3', 'es_4', 'es_5', 'es_6', 'es_7', 'es_8', 'es_9'], 'number'],
            [['es_extension'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'es_id' => SpeeddialModule::t('app', 'ID'),
            'es_extension' => SpeeddialModule::t('app', 'Extension'),
            'es_0' => SpeeddialModule::t('app', 'es_*0'),
            'es_1' => SpeeddialModule::t('app', 'es_*1'),
            'es_2' => SpeeddialModule::t('app', 'es_*2'),
            'es_3' => SpeeddialModule::t('app', 'es_*3'),
            'es_4' => SpeeddialModule::t('app', 'es_*4'),
            'es_5' => SpeeddialModule::t('app', 'es_*5'),
            'es_6' => SpeeddialModule::t('app', 'es_*6'),
            'es_7' => SpeeddialModule::t('app', 'es_*7'),
            'es_8' => SpeeddialModule::t('app', 'es_*8'),
            'es_9' => SpeeddialModule::t('app', 'es_*9'),
            'all_attributes' => SpeeddialModule::t('app', 'Value'),
        ];
    }
}
