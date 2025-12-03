<?php

namespace app\modules\ecosmob\auth\models;

use app\models\CommonModel;
use Yii;

/**
 * This is the model class for table "ntc_device_token".
 *
 * @property integer $id
 * @property string $user_id
 * @property string $device_id
 * @property string $token
 * @property string $updated_at
 */
class Token extends CommonModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ntc_device_token';
    }

    /**
     * @param null $id
     * @return int
     */
    public static function deleteTokens($id = null)
    {
        if (!$id) {
            /** @var string $id */
            $id = Yii::$app->user->identity->id;
        }

        return static::deleteAll(['user_id' => $id]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'device_id', 'token', 'updated_at'], 'required'],
            [['updated_at'], 'safe'],
            [['user_id'], 'string', 'max' => 255],
            [['device_id'], 'string', 'max' => 124],
            [['token'], 'string', 'max' => 64],
            [['device_id'], 'unique'],
            [['token'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'device_id' => Yii::t('app', 'Device ID'),
            'token' => Yii::t('app', 'Token'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }
}
