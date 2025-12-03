<?php

namespace app\modules\ecosmob\device\models;

use Yii;
use yii\base\Security;

/**
 * This is the model class for table "ct_device_token".
 *
 * @property int $id pk
 * @property string $user_id ct_extension_master's em_extenion_name
 * @property string $device_id got in request
 * @property string $voip_token_id got in Voip request
 * @property string $token sent to response
 * @property string $device_type 0=ios ,1=android
 * @property string $os_version
 * @property string $device_name
 * @property string $updated_at
 */
class DeviceToken extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ct_device_token';
    }

    /**
     * @param $length
     * @return string
     * @throws \yii\base\Exception
     */
    public static function getFreshToken($length)
    {
        $security = new Security();
        $token = $security->generateRandomString($length);

        if (static::find()->where(['token' => $token])->one()) {
            $func = __FUNCTION__;
            return static::$func();
        }
        return $token;
    }

    /**
     * @param $uid
     * @return array|\yii\db\ActiveRecord|null
     */
    public static function findUser($uid)
    {
        return static::find()->where(['user_id' => $uid])->one();
    }

    /**
     * @param $uid
     * @param $deviceId
     * @param $apiToken
     * @param $platform
     * @param $os
     * @param $device
     * @return bool
     */
    public static function saveNewToken($uid, $deviceId, $apiToken, $platform, $os, $device)
    {
        $model = new static();

        $model->user_id = $uid;
        $model->device_id = $deviceId;
        $model->token = $apiToken;
        $model->device_type = $platform;
        $model->os_version = $os;
        $model->device_name = $device;
        $model->updated_at = date('Y-m-d H:i:s');

        if($model->save()) {
            return true;
        }

        return false;
    }

    /**
     * @param $uid
     * @param $device_id
     * @return bool
     */
    public static function saveToken($uid, $device_id )
    {
        $model = static::find()->where(['user_id' => $uid])->one();
        $model->device_id = $device_id;
        if($model->save(false)) {
            return true;
        }

        return false;
    }

    /**
     * @param $uid
     * @param $device_id
     * @return bool
     */
    public static function saveVoipToken($uid, $device_id )
    {
        $model = static::find()->where(['user_id' => $uid])->one();
        $model->voip_token_id = $device_id;
        if($model->save(false)) {
            return true;
        }

        return false;
    }

    /**
     * @param $authToken
     * @return false|int
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public static function deleteUserToken($authToken)
    {
        $user = static::find()->where(['token' => $authToken])->one();

        if(!$user) {
            return false;
        }
        return $user->delete();
    }

    /**
     * @param $uid
     * @param $deviceId
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function findDevices($uid, $deviceId)
    {
        return static::find()->where(['user_id' => $uid])->andWhere(['not in', 'device_id' ,$deviceId])->all();
    }

    /**
     * @param $uid
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function findAllDevices($uid)
    {
        return static::find()->where(['user_id' => $uid])->all();
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'device_id', 'token', 'updated_at'], 'required'],
            [['device_type'], 'string'],
            [['updated_at'], 'safe'],
            [['user_id', 'device_name'], 'string', 'max' => 255],
            [['device_id'], 'string', 'max' => 512],
            [['token'], 'string', 'max' => 64],
            [['os_version'], 'string', 'max' => 111],
            [['token'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app\modules\ecosmob\device\messages\app.php', 'ID'),
            'user_id' => Yii::t('app\modules\ecosmob\device\messages\app.php', 'User ID'),
            'device_id' => Yii::t('app\modules\ecosmob\device\messages\app.php', 'Device ID'),
            'token' => Yii::t('app\modules\ecosmob\device\messages\app.php', 'Token'),
            'device_type' => Yii::t('app\modules\ecosmob\device\messages\app.php', 'Device Type'),
            'os_version' => Yii::t('app\modules\ecosmob\device\messages\app.php', 'Os Version'),
            'device_name' => Yii::t('app\modules\ecosmob\device\messages\app.php', 'Device Name'),
            'updated_at' => Yii::t('app\modules\ecosmob\device\messages\app.php', 'Updated At'),
        ];
    }
}
