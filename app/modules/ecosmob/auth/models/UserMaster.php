<?php

namespace app\modules\ecosmob\auth\models;

use app\models\CommonModel;
use app\modules\ecosmob\extension\models\Extension;
use Yii;
use yii\base\NotSupportedException;
use yii\web\IdentityInterface;
use app\modules\ecosmob\timezone\models\Timezone;

/**
 * This is the model class for table "user".
 *
 * @property string $id
 * @property string $user_type
 * @property integer $user_id
 * @property string $user_name
 * @property string $user_password
 * @property string $user_email_id
 * @property string $user_status
 * @property string $tm_id
 * @property string $superuser
 * @property string $oldPassword
 * @property string $newPassword
 * @property string $confirmPassword
 */
class UserMaster extends CommonModel implements IdentityInterface
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
     * @inheritdoc
     */
    public static function tableName() {
        return 'user';
    }

    /**
     * Finds an identity by the given ID.
     *
     * @param string|int $id the ID to be looked for
     * @return IdentityInterface the identity object that matches the given ID.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentity($id) {
        return static::findOne(['id' => $id]);
    }

    /**
     * Finds an identity by the given token.
     *
     * @param mixed $token the token to be looked for
     * @param mixed $type the type of the token. The value of this parameter depends on the implementation.
     * For example, [[\yii\filters\auth\HttpBearerAuth]] will set this parameter to be `yii\filters\auth\HttpBearerAuth`.
     * @return \yii\web\IdentityInterface the identity object that matches the given token.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     * @throws \yii\base\NotSupportedException
     */
    public static function findIdentityByAccessToken($token, $type = null) {
        throw new NotSupportedException("Find Identity by Access Token not implemented.");
    }

    /**
     * Finds user by username
     *
     * @param string $username
     *
     * @return static|null
     */
    public static function findByUsername($username) {
        return static::findOne(['user_name' => $username, 'user_status' => 'Y']);
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['user_id'], 'integer'],
            [['id', 'user_name', 'user_password', 'user_email_id'], 'string', 'max' => 255],
            [['user_type'], 'string', 'max' => 9],
            [['user_status', 'superuser'], 'string', 'max' => 1],
            [['tm_id'], 'string', 'max' => 20],
            [['oldPassword', 'newPassword', 'confirmPassword'], 'required', 'on' => 'changePassword'],
            [['oldPassword'], 'checkCurrentPassword', 'on' => 'changePassword',],
            [['newPassword', 'confirmPassword'], 'string', 'min' => 8, 'max' => 15, 'on' => 'changePassword'],
//            [['newPassword'], 'isUnCrackPasswordOnly', 'on' => 'changePassword'],
            [
                ['confirmPassword'],
                'compare',
                'compareAttribute' => 'newPassword',
                'message' => "Passwords don't match.",
                'on' => 'changePassword',
            ],

            [['oldPassword', 'newPassword', 'confirmPassword'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_type' => Yii::t('app', 'User Type'),
            'user_id' => Yii::t('app', 'User ID'),
            'user_name' => Yii::t('app', 'User Name'),
            'user_password' => Yii::t('app', 'User Password'),
            'user_email_id' => Yii::t('app', 'User Email ID'),
            'user_status' => Yii::t('app', 'User Status'),
            'tm_id' => Yii::t('app', 'Tenant ID'),
            'superuser' => Yii::t('app', 'Superuser'),
        ];
    }

    /**
     * Returns an ID that can uniquely identify a user identity.
     *
     * @return string|int an ID that uniquely identifies a user identity.
     */
    public function getId() {
        return $this->id;
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
    public function getAuthKey() {
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
    public function validateAuthKey($authKey) {
        return true;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     *
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password) {
        return $this->user_password === md5($password);
    }

    /**
     * Custom validation method
     *
     * Method use to check mobile number must be contained 10 digits numeric value
     *
     * @param $attribute
     */
    public function isUnCrackPasswordOnly($attribute) {
        if (!preg_match('((?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*\W).{6,6})', $this->$attribute)) {
            $this->addError($attribute, 'Password must contain min 6 character, lowercase, uppercase, a number and at least one special character.');
        }
    }

    /**
     * Check Current Password
     *
     * Current Password validation with old Password
     */
    public function checkCurrentPassword() {
        if ($this->adm_password != md5($this->oldPassword)) {
            $this->addError('oldPassword', 'Invalid current password.');
        }
    }

    /**
     * Only use when user is tenant or user
     * @return string
     */
    public function getExtNumSipDomain() {
        return Extension::getNumberById(Yii::$app->user->identity->user_id) . '@' . TenantMaster::findSipDomainName(Yii::$app->user->identity->tm_id);
    }

    /**
     * Only use when user is tenant or user
     * @return string
     */
    public function getSipDomain() {
        return TenantMaster::findSipDomainName(Yii::$app->user->identity->tm_id);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTimezoneRl() {
        return $this->hasOne(Timezone::className(), ['tz_id' => 'timezone']);
    }
}
