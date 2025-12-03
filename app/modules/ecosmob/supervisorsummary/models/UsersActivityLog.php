<?php

namespace app\modules\ecosmob\supervisorsummary\models;

use app\modules\ecosmob\supervisorsummary\SupervisorSummaryModule;
use Yii;
use yii\db\ActiveRecord;


/**
 * This is the model class for table "users_activity_log".
 *
 * @property int $id
 * @property int $user_id
 * @property string $login_time
 * @property string $logout_time
 * @property string $campaign_name
 * @property string $created_at
 */
class UsersActivityLog extends ActiveRecord
{
    public $adm_firstname;
    public $adm_lastname;
    public $date;
    public $break_time;
    public $custom_id;
    public $campaign;

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
            [['login_time', 'logout_time', 'created_at', 'campaign', 'campaign_name'], 'safe'],
            [['campaign_name'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => SupervisorSummaryModule::t('supervisorsummary', 'name'),
            'date' => SupervisorSummaryModule::t('supervisorsummary', 'date'),
            'adm_firstname' => SupervisorSummaryModule::t('supervisorsummary', 'name'),
            'login_time' => SupervisorSummaryModule::t('supervisorsummary', 'login_time'),
            'logout_time' => SupervisorSummaryModule::t('supervisorsummary', 'logout_time'),
            'break_time' => SupervisorSummaryModule::t('supervisorsummary', 'brk_time'),
            'campaign_name' => SupervisorSummaryModule::t('supervisorsummary', 'camp'),
            'created_at' => SupervisorSummaryModule::t('supervisorsummary', 'date'),
        ];
    }
}
