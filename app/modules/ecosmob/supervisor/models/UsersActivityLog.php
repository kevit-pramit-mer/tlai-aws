<?php

namespace app\modules\ecosmob\supervisor\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "users_activity_log".
 *
 * @property int $id
 * @property int $user_id
 * @property string $login_time
 * @property string $logout_time
 * @property string $created_at
 */
class UsersActivityLog extends ActiveRecord
{
    public $adm_firstname;
    public $adm_lastname;
    public $date;
    public $break_time;
    public $agent_name;
    public $status;
    public $state;

    // public $login_time;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'users_activity_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id'], 'integer'],
            [['login_time', 'logout_time', 'created_at', 'campaign_name'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'login_time' => Yii::t('app', 'Login Time'),
            'logout_time' => Yii::t('app', 'Logout Time'),
            'created_at' => Yii::t('app', 'Created At'),
        ];
    }
}
