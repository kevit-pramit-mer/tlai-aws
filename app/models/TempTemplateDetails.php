<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "temp_template_details".
 *
 * @property int $id
 * @property string $template_id
 * @property string $parameter_name
 * @property string $is_object
 * @property string $is_writable
 * @property string $parameter_value
 * @property string $value_type
 * @property string $parameter_label
 * @property string $input_type
 * @property int $is_primary
 * @property int $voice_profile
 * @property int $codec
 * @property string $value_source
 * @property string $variable_source
 * @property int $is_checked
 */
class TempTemplateDetails extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'temp_template_details';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['is_primary', 'voice_profile', 'codec', 'is_checked'], 'integer'],
            [['template_id', 'parameter_name', 'is_object', 'is_writable', 'parameter_value', 'value_type', 'parameter_label', 'input_type', 'value_source', 'variable_source'], 'string', 'max' => 255],
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
            'parameter_name' => 'Parameter Name',
            'is_object' => 'Is Object',
            'is_writable' => 'Is Writable',
            'parameter_value' => 'Parameter Value',
            'value_type' => 'Value Type',
            'parameter_label' => 'Parameter Label',
            'input_type' => 'Input Type',
            'is_primary' => 'Is Primary',
            'voice_profile' => 'Voice Profile',
            'codec' => 'Codec',
            'value_source' => 'Value Source',
            'variable_source' => 'Variable Source',
            'is_checked' => 'Is Checked',
        ];
    }
}
