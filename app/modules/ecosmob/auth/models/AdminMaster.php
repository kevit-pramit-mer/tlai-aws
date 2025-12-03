<?php

namespace app\modules\ecosmob\auth\models;

use app\modules\ecosmob\auth\AuthModule;
use Yii;
use yii\base\NotSupportedException;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;


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
 * @property string $adm_alternate_email
 * @property integer $adm_user_id
 */
class AdminMaster extends ActiveRecord implements IdentityInterface
{
    /**
     * @var
     */
    public $oldPassword;

    /**
     * @var
     */
    public $newPassword;
    //public $adm_password_hash;

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
     * Finds an identity by the given ID.
     *
     * @param string|int $id the ID to be looked for
     * @return IdentityInterface the identity object that matches the given ID.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * Finds an identity by the given token.
     *
     * @param mixed $token the token to be looked for
     * @param mixed $type the type of the token. The value of this parameter depends on the implementation.
     *                     For example, [[\yii\filters\auth\HttpBearerAuth]] will set this parameter to be `yii\filters\auth\HttpBearerAuth`.
     *
     * @return void the identity object that matches the given token.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     * @throws NotSupportedException
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException("Find Identity by Access Token not implemented.");
    }


    /**
     * @param $username
     * @return array|ActiveRecord|null
     */
    public static function findByUsername($username)
    {
        return static::find()->where(['adm_username' => $username])->one();
    }

    /**
     * @param $username
     * @param $adm_is_admin
     * @return array|ActiveRecord|null
     */
    public static function findByUsernameAndStatus($username, $adm_is_admin)
    {
        if ($_POST['logintype'] == "supervisor") {
            return static::find()->where(['adm_username' => $username, 'adm_is_admin' => $adm_is_admin])->one();
        } else {
            return static::find()->where(['adm_username' => $username])->andWhere(['<>', 'adm_is_admin', 'supervisor'])->andWhere(['<>', 'adm_is_admin', 'agent'])->one();
        }

    }

    /**
     * @param $tnId
     * @return array|ActiveRecord|null
     */
    public static function getStatus($tnId)
    {
        return static::find()->where(['adm_user_id' => $tnId])->andWhere(['adm_is_admin' => 'tenant_admin'])->one()['adm_status'];
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
            [['adm_firstname', 'adm_lastname', 'adm_email', 'adm_password', 'adm_contact', 'adm_username'], 'required'],
            [['adm_firstname', 'adm_lastname', 'adm_email', 'adm_password'], 'string', 'min' => 3, 'max' => 255],
            [['adm_email'], 'email'],
            [['adm_email'], 'unique'],
            [['adm_contact'], 'string', 'min' => 8, 'max' => 20],
            ['adm_username', 'string', 'max' => 30],
            // ['adm_password', 'match', 'pattern' => '/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[#?!@$%^&*\-_{}()])[a-zA-Z\d#?!@$%^&*\-_{}()]{8,20}$/', 'message' => 'Password must have 8 characters with at least one number, one special character, one capital and one small letter and no space'],
            /* Change Password Scenario Validation */
            [['oldPassword', 'newPassword', 'confirmPassword'], 'required', 'on' => 'changePassword'],
            [['oldPassword'], 'checkCurrentPassword', 'on' => 'changePassword'],
            [['newPassword', 'confirmPassword'], 'string', 'min' => 8, 'max' => 20, 'on' => 'changePassword'],
            [['newPassword'], 'isUnCrackPasswordOnly', 'on' => 'changePassword'],
            /* Reset Password Scenario Validation */
            [['newPassword', 'confirmPassword'], 'required', 'on' => 'resetPassword'],
            [['newPassword', 'confirmPassword'], 'string', 'min' => 8, 'max' => 20, 'on' => 'resetPassword'],
            //[['newPassword'], 'isUnCrackPasswordOnly', 'on'=>'resetPassword'],
            ['newPassword', 'match', 'pattern' => '/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[#?!@$%^&*\-_{}()])[a-zA-Z\d#?!@$%^&*\-_{}()]{8,20}$/', 'message' => AuthModule::t('auth', 'strong_password_validation'), 'on' => 'resetPassword'],

            [
                ['confirmPassword'],
                'compare',
                'compareAttribute' => 'newPassword',
                'message' => AuthModule::t('auth', 'password_does_not_match'),
                'on' => 'resetPassword',
            ],
            [
                ['confirmPassword'],
                'compare',
                'compareAttribute' => 'newPassword',
                'message' => AuthModule::t('auth', 'password_does_not_match'),
                'on' => 'changePassword',
            ],

            /* Safe all attributes of model */
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
                    'adm_alternate_email',
                    'adm_user_id',
                    'adm_password_hash',
                    'adm_token',
                    'force_logout'
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
            'adm_id' => 'ID(#)',
            'adm_firstname' => 'Firstname',
            'adm_lastname' => 'Lastname',
            'adm_email' => 'Email',
            'adm_password' => 'Password',
            'adm_contact' => 'Contact No(#)',
            'adm_is_admin' => 'Is Admin(#)',
            'adm_status' => 'Status',
            'adm_last_login' => 'Last Login',
            /*            'adm_username'=>'Username',*/
            'adm_username' => 'Username / Extension Number',//Yii::t('app', 'username'),
            'newPassword' => Yii::t('app', 'newPassword'),
            'confirmPassword' => Yii::t('app', 'confirmPassword'),
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
     * Returns a key that can be used to check the validity of a given identity ID.
     *
     * The key should be unique for each individual user, and should be persistent
     * so that it can be used to check the validity of the user identity.
     *
     * The space of such keys should be big enough to defeat potential identity attacks.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     *
     * @return string a key that is used to check the validity of a given identity ID.
     * @see validateAuthKey()
     */
    public function getAuthKey()
    {
        return null;
    }

    /**
     * Validates the given auth key.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     *
     * @param string $authKey the given auth key
     * @return bool whether the given auth key is valid.
     * @see getAuthKey()
     */
    public function validateAuthKey($authKey)
    {
        return true;
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
            $this->addError($attribute, AuthModule::t('auth', 'strong_password_validation'));
        }
    }

    /**
     * Check Current Password
     *
     * Current Password validation with old Password
     */
    public function checkCurrentPassword()
    {
        if ($this->adm_password != md5($this->oldPassword)) {
            $this->addError('oldPassword', 'Invalid current password.');
        }
    }
}
