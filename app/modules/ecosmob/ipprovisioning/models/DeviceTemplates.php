<?php

namespace app\modules\ecosmob\ipprovisioning\models;

use Yii;

/**
 * This is the model class for table "tbl_device_templates".
 *
 * @property string $device_templates_id
 * @property string $template_name
 * @property int $brand_id
 * @property string $supported_models_id
 * @property string $voipservice_key
 * @property string $createdAt
 * @property string $updatedAt
 */
class DeviceTemplates extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_device_templates';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['device_templates_id', 'createdAt', 'updatedAt'], 'required'],
            [['template_name'], 'string'],
            [['brand_id'], 'integer'],
            [['createdAt', 'updatedAt'], 'safe'],
            [['device_templates_id'], 'string', 'max' => 36],
            [['supported_models_id', 'voipservice_key'], 'string', 'max' => 255],
            [['device_templates_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'device_templates_id' => 'Device Templates ID',
            'template_name' => 'Template Name',
            'brand_id' => 'Brand ID',
            'supported_models_id' => 'Supported Models ID',
            'voipservice_key' => 'Voipservice Key',
            'createdAt' => 'Created At',
            'updatedAt' => 'Updated At',
        ];
    }

    public function getPhoneVendor(){
        return $this->hasOne(PhoneVendor::className(), ['pv_id' => 'brand_id']);
    }
}
