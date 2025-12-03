<?php

namespace app\modules\ecosmob\ipprovisioning\models;

use Yii;

/**
 * This is the model class for table "tbl_template_master".
 *
 * @property int $id
 * @property string $device_template_id
 * @property string $template_name
 * @property string $created_at
 * @property string $updated_at
 */
class TemplateMaster extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_template_master';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['device_template_id', 'template_name'], 'required'],
            [['created_at', 'updated_at', 'brand_id', 'status'], 'safe'],
            [['device_template_id'], 'string', 'max' => 36],
            [['supported_models_id', 'template_name'], 'string', 'max' => 255],
            [['template_name'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'device_template_id' => 'Device Template',
            'brand_id' => 'Brand ID',
            'supported_models_id' => 'Supported Models',
            'template_name' => 'Template Name',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getDeviceTemplate(){
        return $this->hasOne(DeviceTemplates::className(), ['device_templates_id' => 'device_template_id']);
    }

    public function getPhoneModels()
    {
        $ids = explode(',', $this->supported_models_id);
        $phoneModels = PhoneModels::find()->where(['p_id' => $ids])->asArray()->all();
        if(!empty($phoneModels)){
            return implode(', ', array_column($phoneModels, 'p_model'));
        }else{
            return '-';
        }
    }
}
