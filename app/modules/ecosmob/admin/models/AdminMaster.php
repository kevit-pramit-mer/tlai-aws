<?php

namespace app\modules\ecosmob\admin\models;

use app\modules\ecosmob\admin\AdminModule;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "admin_master".
 *
 * @property integer $adm_id
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
 * @property string $oldPassword
 * @property string $newPassword
 * @property string $confirmPassword
 * @property integer $tz_id
 * @property integer $adm_user_id
 * @property string $adm_language
 * @property string $is_auto_login
 */
class AdminMaster extends ActiveRecord
{
    /**
     * @var
     */
    public $oldPassword;

    /**
     * @var
     */
    public $newPassword;

    /**
     * @var
     */
    public $confirmPassword;

    /**
     * Get Table Name
     *
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'admin_master';
    }

    /**
     * Finds user by username
     *
     * @param string $username
     *
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['adm_username' => $username]);
    }

    /**
     * @param $id
     * @return array|ActiveRecord|null
     */
    public static function getOperator($id)
    {
        return static::find()->where(['adm_is_admin' => 'tenant_operator', 'adm_user_id' => $id])->one();
    }

    /**
     * @param $id
     * @return array|ActiveRecord|null
     */
    public static function getTenant($id)
    {
        return static::find()->where(['adm_is_admin' => 'tenant_admin', 'adm_user_id' => $id])->one();
    }

    /**
     * @param $id
     *
     * @return string
     */
    public static function adminName($id)
    {
        $adminName = AdminMaster::findOne(['adm_id' => $id]);

        return $adminName->adm_firstname;
    }

    /**
     * Set Validation Rule From Model
     *
     * @inheritdoc
     */
    public function rules()
    {
        return [

            /* Default Validation */
            [
                [
                    'adm_firstname',
                    'adm_lastname',
                    'adm_email',
                    'adm_password',
                    'adm_status',
                    'adm_username',
                    'adm_timezone_id',
                ],
                'required',
            ],
            [['adm_email', 'adm_password'], 'string', 'min' => 8, 'max' => 255],
            [['adm_username'], 'string', 'min' => 1, 'max' => 30],
            [
                ['adm_firstname', 'adm_lastname'], 'string', 'min' => 3,
                'max' => 15
            ],
            [['adm_email'], 'email'],
//            ['adm_username', 'match', 'pattern' => '~^(?=.*[a-zA-Z])(?!_*\_\_)[\w_]+$~', 'message' => AdminModule::t('admin', 'invalid_username')],
            [['adm_email', 'adm_username'], 'unique'],
            [['adm_contact'], 'string', 'min' => 8, 'max' => 13, 'message'=> AdminModule::t('admin', 'adm_contact_max_validation'), 'tooShort' => AdminModule::t('admin', 'adm_contact_min_validation')],
            [['adm_contact'], 'number'],
            [['adm_status'], 'string'],
            /* Change Password Scenario Validation */
            [['oldPassword', 'newPassword', 'confirmPassword'], 'required', 'on' => 'changePassword'],
            [['oldPassword'], 'checkCurrentPassword', 'on' => 'changePassword'],
            [['oldPassword', 'newPassword', 'confirmPassword'], 'string', 'min' => 8, 'max' => 50, 'on' => 'changePassword'],
            //[['newPassword'], 'isUnCrackPasswordOnly', 'on' => 'changePassword'],
            //['newPassword', 'match', 'pattern' => '/^(?=.*\d)(?=.*[a-zA-Z])(?=.*[#?!@$%^&*\-_{}()])[#?!@$%^&*\-_{}()]{8,20}$/', 'message' => 'Password must have 8 characters with at least one number, one special character and no space','on' => 'changePassword'],
            [['adm_firstname'], 'match', 'pattern' => '/^[a-zA-Z]+$/','message'=>AdminModule::t('admin', 'firstname_alphanumeric_validation')],
            [['adm_lastname'], 'match', 'pattern' => '/^[a-zA-Z]+$/','message'=>AdminModule::t('admin', 'lastname_alphanumeric_validation')],
            ['newPassword', 'match', 'pattern' => '/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[#?!@$%^&*\-_{}()])[a-zA-Z\d#?!@$%^&*\-_{}()]{8,20}$/', 'message' => AdminModule::t('admin', 'strong_password_validation'),'on' => 'changePassword'],
            [
                ['confirmPassword'],
                'compare',
                'compareAttribute' => 'newPassword',
                'message' => AdminModule::t('admin', 'password_does_not_match'),
                'on' => 'changePassword',
            ],
            [
                [
                    'adm_firstname',
                    'adm_lastname',
                    'adm_email',
                    'adm_password',
                    'adm_contact',
                    'adm_is_admin',
                    'adm_status',
                    'adm_last_login',
                    'oldPassword',
                    'newPassword',
                    'confirmPassword',
                    'adm_language',
                    'is_auto_login'
                ],
                'safe',
            ],
        ];
    }

    /**
     * Attributes Label Statement
     *
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'adm_id' => AdminModule::t('admin', 'id'),
            'adm_firstname' => AdminModule::t('admin', 'first_name'),
            'adm_lastname' => AdminModule::t('admin', 'last_name'),
            'adm_email' => AdminModule::t('admin', 'email'),
            'adm_password' => AdminModule::t('admin', 'password'),
            'adm_contact' => AdminModule::t('admin', 'contact_number'),
            'adm_username' => AdminModule::t('admin', 'adm_username'),
            'adm_is_admin' => AdminModule::t('admin', 'is_admin'),
            'adm_status' => AdminModule::t('admin', 'status'),
            'adm_last_login' => AdminModule::t('admin', 'last_login'),
            'adm_timezone_id' => AdminModule::t('admin', 'time_zone'),
            'oldPassword' => AdminModule::t('admin', 'old_password'),
            'newPassword' => AdminModule::t('admin', 'new_password'),
            'confirmPassword' => AdminModule::t('admin', 'confirm_password'),
            'is_auto_login' => AdminModule::t('admin', 'is_auto_login')
        ];
    }

    /**
     * Returns an ID that can uniquely identify a user identity.
     *
     * @return string|int an ID that uniquely identifies a user identity.
     */
    public function getId()
    {
        return $this->adm_id;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     *
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->adm_password === md5($password);
    }

    /**
     * Custom validation method
     *
     * Method use to check mobile number must be contained 10 digits numeric value
     *
     * @param $attribute
     */
    public function isUnCrackPasswordOnly($attribute)
    {
        if (!preg_match('((?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*\W).{8,8})', $this->$attribute)) {
            $this->addError($attribute, AdminModule::t('admin', 'strong_password_validation'));
        }
    }

    /**
     * Check Current Password
     *
     * Current Password validation with old Password
     *
     */
    public function checkCurrentPassword()
    {
        if ($this->adm_password != md5($this->oldPassword)) {
            $this->addError('oldPassword', AdminModule::t('admin', 'invalid_current_password'));
        }
    }
}
