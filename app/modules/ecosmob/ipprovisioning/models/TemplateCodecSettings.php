<?php

namespace app\modules\ecosmob\ipprovisioning\models;

use Yii;

/**
 * This is the model class for table "tbl_template_codec_settings".
 *
 * @property int $id
 * @property int $template_id
 * @property string $parameter_key
 * @property string $codec
 * @property int $priority
 */
class TemplateCodecSettings extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_template_codec_settings';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['template_id', 'priority'], 'integer'],
            [['parameter_key', 'codec'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'template_id' => 'Template ID',
            'parameter_key' => 'Parameter Key',
            'codec' => 'Codec',
            'priority' => 'Priority',
        ];
    }
}
