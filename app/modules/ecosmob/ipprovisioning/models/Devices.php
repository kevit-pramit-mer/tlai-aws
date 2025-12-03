<?php

namespace app\modules\ecosmob\ipprovisioning\models;

use app\modules\ecosmob\ipprovisioning\IpprovisioningModule;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "tbl_devices".
 *
 * @property int $id
 * @property int $brand_id
 * @property string $template_master_id
 * @property string $device_name
 * @property string $mac_address
 * @property int $model_id
 * @property int $provisioning_status
 * @property string $created_at
 * @property string $updated_at
 */
class Devices extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_devices';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['device_name', 'mac_address', 'brand_id', 'template_master_id', 'model_id'], 'required'],
            [['brand_id', 'provisioning_status'], 'integer'],
            [['created_at', 'updated_at', 'model_id', 'template_master_id', 'provisioning_status'], 'safe'],
            [['device_name', 'mac_address'], 'string', 'max' => 255],
            [['mac_address'], 'match', 'pattern' => '/^([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})$/','message' => IpprovisioningModule::t('app', 'mac_address_invalid')],
            [['device_name', 'mac_address'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'brand_id' => 'Brand',
            'template_master_id' => 'Template',
            'device_name' => 'Device Name',
            'mac_address' => 'MAC Address',
            'model_id' => 'Model',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'provisioning_status' => 'Provisioning Status',
        ];
    }

    public function getBrand(){
        return $this->hasOne(PhoneVendor::className(), ['pv_id' => 'brand_id']);
    }

    public function getTemplate(){
        return $this->hasOne(TemplateMaster::className(), ['id' => 'template_master_id']);
    }

    public function getPhoneModel(){
        return $this->hasOne(PhoneModels::className(), ['p_id' => 'model_id']);
    }
}
