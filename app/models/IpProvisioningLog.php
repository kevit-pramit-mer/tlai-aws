<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "tbl_ip_provisioning_log".
 *
 * @property int $id
 * @property int $device_id
 * @property string $device_info
 * @property string $parameter_key
 * @property string $request
 * @property string $response
 * @property string $response_code
 * @property string $created_at
 */
class IpProvisioningLog extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_ip_provisioning_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['device_id'], 'integer'],
            [['request', 'response'], 'string'],
            [['id', 'created_at', 'response_code'], 'safe'],
            [['device_info', 'parameter_key'], 'string', 'max' => 255],
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
            'device_info' => 'Device Info',
            'parameter_key' => 'Parameter Key',
            'request' => 'Request',
            'response' => 'Response',
            'response_code' => 'Response Code',
            'created_at' => 'Created At',
        ];
    }
}
