<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_phone_vendor".
 *
 * @property string $pv_id
 * @property string $pv_name
 * @property string $pv_firmware_check
 */
class PhoneVendor extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_phone_vendor';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('masterdb');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pv_id', 'pv_name', 'pv_firmware_check'], 'required'],
            [['pv_firmware_check'], 'string'],
            [['pv_id'], 'string', 'max' => 36],
            [['pv_name'], 'string', 'max' => 255],
            [['pv_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'pv_id' => 'Pv ID',
            'pv_name' => 'Pv Name',
            'pv_firmware_check' => 'Pv Firmware Check',
        ];
    }
}
