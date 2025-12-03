<?php

namespace app\modules\ecosmob\ipprovisioning\models;

use Yii;

/**
 * This is the model class for table "tbl_device_templates_parameters".
 *
 * @property string $id
 * @property string $device_templates_id
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
 * @property string $createdAt
 * @property string $updatedAt
 */
class DeviceTemplatesParameters extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_device_templates_parameters';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'createdAt', 'updatedAt'], 'required'],
            [['is_primary', 'voice_profile'], 'integer'],
            [['createdAt', 'updatedAt'], 'safe'],
            [['id', 'device_templates_id'], 'string', 'max' => 36],
            [['parameter_name', 'is_object', 'is_writable', 'parameter_value', 'value_type', 'parameter_label', 'input_type', 'value_source', 'codec'], 'string', 'max' => 255],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'device_templates_id' => 'Device Templates ID',
            'parameter_name' => 'Parameter Name',
            'is_object' => 'Is Object',
            'is_writable' => 'Is Writable',
            'parameter_value' => 'Parameter Value',
            'value_type' => 'Value Type',
            'parameter_label' => 'Parameter Label',
            'input_type' => 'Input Type',
            'is_primary' => 'is_primary',
            'voice_profile' => 'Voice Profile',
            'codec' => 'Codec',
            'value_source' => 'Value Source',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
        ];
    }
}
