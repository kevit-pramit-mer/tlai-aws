<?php

namespace app\modules\ecosmob\manageagent\models;

use app\modules\ecosmob\agents\models\CampaignMappingAgents;
use app\modules\ecosmob\campaign\models\CampaignMappingUser;
use app\modules\ecosmob\manageagent\ManageAgentModule;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "admin_master".
 *
 * @property int $adm_id
 * @property string $adm_firstname
 * @property string $adm_lastname
 * @property string $adm_username
 * @property string $adm_email
 * @property string $adm_password
 * @property string $adm_password_hash
 * @property string $adm_contact
 * @property string $adm_is_admin
 * @property string $adm_status '0' - Inactive ,'1' - Active, '2' - Trash
 * @property int $adm_timezone_id
 * @property string $adm_last_login
 */
class ManageAgent extends ActiveRecord
{
    public $campaign, $cmp_name, $recent_login;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'admin_master';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['adm_firstname', 'adm_lastname', 'adm_username', 'adm_email', 'adm_password', 'adm_password_hash', 'adm_contact', 'adm_is_admin', 'adm_status', 'adm_timezone_id'], 'required'],
            [['adm_status'], 'string'],
            [['adm_timezone_id'], 'integer'],
            [['adm_last_login', 'campaign', 'recent_login'], 'safe'],
            [['adm_firstname', 'adm_lastname', 'adm_username', 'adm_email', 'adm_password', 'adm_is_admin'], 'string', 'max' => 255],
            [['adm_password_hash'], 'string', 'max' => 250],
            [['adm_contact'], 'string', 'max' => 15],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'adm_username' => ManageAgentModule::t('manageagent', 'adm_username'),
            'adm_firstname' => ManageAgentModule::t('manageagent', 'adm_firstname'),
            'adm_lastname' => ManageAgentModule::t('manageagent', 'adm_lastname'),
            'adm_email' => ManageAgentModule::t('manageagent', 'adm_email'),
            'adm_status' => ManageAgentModule::t('manageagent', 'adm_status'),
        ];
    }

    public function getCampaignMapUser()
    {
        return $this->hasMany(CampaignMappingUser::className(), ['supervisor_id' => 'adm_id']);
    }

    public function getCampaignMapAgent()
    {
        return $this->hasMany(CampaignMappingAgents::className(), ['agent_id' => 'adm_id']);
    }

}
