<?php

namespace app\modules\ecosmob\auth\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "ct_forgot_password".
 *
 * @property integer $fp_id
 * @property integer $fp_user_id
 * @property string $fp_user_type
 * @property string $fp_token
 * @property string $fp_reset_url
 * @property string $fp_status
 * @property string $fp_update_at
 */
class ForgotPassword extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ct_forgot_password';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fp_user_id', 'fp_user_type', 'fp_token', 'fp_reset_url'], 'required'],
            [['fp_user_type', 'fp_token'], 'string', 'max' => 255],
            [['fp_reset_url', 'fp_status'], 'string'],
            [['fp_user_id'], 'integer'],
            [['fp_token'], 'unique'],
            [['fp_user_id', 'fp_user_type', 'fp_token', 'fp_reset_url', 'fp_status', 'fp_update_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'fp_id' => 'ID(#)',
            'fp_user_id' => 'User ID(#)',
            'fp_user_type' => 'User Type',
            'fp_token' => 'Token',
            'fp_reset_url' => 'Reset Url',
            'fp_status' => 'Status',
            'fp_update_at' => 'Update At',
        ];
    }
}
