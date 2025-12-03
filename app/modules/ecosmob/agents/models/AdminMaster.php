<?php

namespace app\modules\ecosmob\agents\models;

use app\modules\ecosmob\agents\AgentsModule;
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
 * @property string $adm_contact
 * @property string $adm_is_admin
 * @property string $adm_status '0' - Inactive ,'1' - Active, '2' - Trash
 * @property int $adm_timezone_id
 * @property string $adm_last_login
 * @property string $adm_mapped_extension
 *
 */
class AdminMaster extends ActiveRecord
{
    public $uname;

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
            [['adm_firstname', 'adm_lastname', 'adm_username', 'adm_password', 'adm_status', 'adm_timezone_id', 'adm_email', 'adm_mapped_extension'], 'required'],
            [['adm_timezone_id'], 'integer'],
            [['adm_firstname', 'adm_lastname', 'adm_username'], 'string', 'min' => 2, 'max' => 30],
            [['adm_email'], 'string', 'max' => 40],
            [['adm_last_login', 'adm_password'], 'safe'],
            [['adm_email', 'adm_username', 'adm_mapped_extension'], 'unique'],
            [['adm_email'], 'email'],
            [['adm_firstname', 'adm_lastname', 'adm_username', 'adm_email', 'adm_is_admin'], 'string', 'max' => 100],
            [['adm_firstname'], 'match', 'pattern' => '/^[a-zA-Z]+$/', 'message' => AgentsModule::t('agents', 'first_name')],
            [['adm_lastname'], 'match', 'pattern' => '/^[a-zA-Z]+$/', 'message' => AgentsModule::t('agents', 'last_name')],
            ['adm_password', 'match', 'pattern' => '/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[#?!@.$%^&*\-_{}()])[a-zA-Z\d#?!@.$%^&*\-_{}()]{8,20}$/', 'message' => AgentsModule::t('agents', 'strong_password_validation')],
            [['adm_contact'], 'string', 'max' => 15],
            [['adm_username'], 'match', 'pattern' => '/^[0-9a-zA-Z]*$/', 'message' => AgentsModule::t('agents', 'user_name_alphanumeric_validation')],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'adm_firstname' => AgentsModule::t('agents', 'firstname'),
            'adm_lastname' => AgentsModule::t('agents', 'lastname'),
            'adm_username' => AgentsModule::t('agents', 'username'),
            'uname' => AgentsModule::t('agents', 'username'),
            'adm_email' => AgentsModule::t('agents', 'email'),
            'adm_password' => AgentsModule::t('agents', 'password'),
            'adm_contact' => AgentsModule::t('agents', 'contact'),
            'adm_status' => AgentsModule::t('agents', 'status'),
            'adm_timezone_id' => AgentsModule::t('agents', 'timezone'),
            'adm_mapped_extension' => AgentsModule::t('agents', 'extension'),
        ];
    }
}
