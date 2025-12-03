<?php

namespace app\modules\ecosmob\ipprovisioning\models;

use Yii;

/**
 * This is the model class for table "tbl_device_line_parameter".
 *
 * @property int $id
 * @property int $device_id
 * @property string $parameter_label
 * @property string $parameter_name
 * @property int $profile_number
 * @property string $parameter_key
 * @property string $value
 * @property string $value_source
 * @property string $variable_source
 * @property string $is_writable
 * @property string $input_type
 * @property int $is_change
 */
class DeviceLineParameter extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_device_line_parameter';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['device_id', 'profile_number', 'is_change'], 'integer'],
            [['parameter_label', 'parameter_name', 'parameter_key', 'value', 'value_source', 'variable_source', 'is_writable', 'input_type', 'codec', 'value_type'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'device_id' => 'Device ID',
            'profile_number' => 'Profile Number',
            'parameter_name' => 'Parameter Name',
            'parameter_key' => 'Parameter Key',
            'value' => 'Value',
            'is_change' => 'Is Change',
        ];
    }
}
