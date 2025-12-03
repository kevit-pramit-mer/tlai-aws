<?php

namespace app\modules\ecosmob\user\models;

use app\modules\ecosmob\user\UserModule;
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
 * @property string $adm_status
 * @property string $adm_timezone_id
 * @property string $adm_last_login
 */
class User extends ActiveRecord
{

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
            [
                ['adm_status', 'adm_firstname', 'adm_lastname', 'adm_password', 'adm_username', 'adm_email', 'adm_is_admin', 'adm_timezone_id'],
                'required',
                'on' => ['create', 'update'],
            ],

            [['adm_is_admin', 'adm_status'], 'string', 'on' => ['create', 'update']],
            [['adm_last_login', 'adm_timezone_id', 'adm_is_admin', 'adm_password'], 'safe'],
            [['adm_firstname'], 'required', 'message' => UserModule::t('usr', 'first_name')],
            [['adm_firstname'], 'match', 'pattern' => '/^[a-zA-Z]+$/', 'message' => UserModule::t('usr', 'first_name')],
            [['adm_lastname'], 'match', 'pattern' => '/^[a-zA-Z]+$/', 'message' => UserModule::t('usr', 'last_name')],
            ['adm_username', 'match', 'pattern' => '/^[a-zA-Z0-9_]+$/', 'message' => UserModule::t('usr', 'user_name')],
            [['adm_firstname', 'adm_lastname', 'adm_username'], 'string', 'min' => 2, 'max' => 30, 'on' => ['create', 'update']],
            [['adm_email'], 'string', 'max' => 40, 'on' => ['create', 'update']],
            ['adm_email', 'email', 'on' => ['create', 'update']],
            [['adm_contact'], 'number', 'on' => ['create', 'update']],
            [
                'adm_contact',
                'match',
                'pattern' => '/^[0-9+]{6,15}$/',
                'message' => 'Mobile Number must be 6 to 15 digits.',
                'on' => ['create', 'update'],
            ],
            ['adm_password', 'match', 'pattern' => '/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[#?!@.$%^&*\-_{}()])[a-zA-Z\d#?!@.$%^&*\-_{}()]{8,20}$/', 'message' => UserModule::t('usr', 'password_strength_validation'), 'on' => ['create', 'update']],
            [['adm_email', 'adm_username'], 'unique', 'on' => ['create', 'update']],
            [['adm_username'], 'match', 'pattern' => '/^[0-9a-zA-Z]*$/', 'message' => UserModule::t('usr', 'user_name_alphanumeric_validation'), 'on' => ['create', 'update']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'adm_id' => 'ID',
            'adm_firstname' => UserModule::t('usr', 'adm_firstname'),
            'adm_lastname' => UserModule::t('usr', 'adm_lastname'),
            'adm_username' => UserModule::t('usr', 'adm_username'),
            'adm_email' => UserModule::t('usr', 'adm_email'),
            'adm_password' => UserModule::t('usr', 'adm_password'),
            'adm_contact' => UserModule::t('usr', 'adm_contact'),
            'adm_is_admin' => UserModule::t('usr', 'adm_is_admin'),
            'adm_status' => UserModule::t('usr', 'adm_status'),
            'adm_timezone_id' => UserModule::t('usr', 'adm_timezone_id'),
            'adm_last_login' => UserModule::t('usr', 'adm_last_login'),
        ];
    }
}
