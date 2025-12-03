<?php

namespace app\modules\ecosmob\auth\models;

use app\modules\ecosmob\auth\AuthModule;
use app\modules\ecosmob\extension\models\Extension;
use Yii;
use yii\base\Model;
use yii\base\InvalidArgumentException;
use yii\db\ActiveRecord;

/**
 * LoginFormAgent is the model behind the login form.
 *
 * @property AdminMaster|null $user This property is read-only.
 *
 */
class LoginFormAgent extends Model
{

    /**
     * @var
     */
    public $username;

    /**
     * @var
     */
    public $password;

    /**
     * @var bool
     */
    public $rememberMe = true;
    /**
     * @var
     */
    public $loginAsExtension;
    /**
     * @var
     */
    public $loginAsAgent;
    /**
     * @var bool
     */
    public $loginType;
    /**
     * @var
     */
    public $extension;
    /**
     * @var
     */
    public $campaign_type;
    /**
     * @var bool
     */
    private $_user = false;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['username', 'password', 'extension'], 'required'],
            [['username'], 'string', 'max' => 50],
            [['username'], 'string', 'max' => 50],
            ['rememberMe', 'boolean'],
            ['username', 'checkActivationStatus'],
            ['password', 'validatePassword'], //custom validation
            [['loginAsExtension', 'extension', 'loginAsAgent', 'campaign_type'], 'safe'],

            /* [['extension'], 'required', 'when'=>function ($model) {
                 return $model->loginAsAgent == 1 || $model->loginAsAgent;
             }, 'whenClient'=>"function (attribute, value) {
                 return $('#agentlogin').val() == 1;
             }"],*/
            [['campaign_type'], 'required', 'on' => 'agent'],
            /*[['campaign_type'], 'required', 'when'=>function ($model) {
                return $model->loginAsAgent == 1;
            }, 'whenClient'=>"function (attribute, value) {
                return $('#agentlogin').val() == 1;
            }"],*/

        ];
    }


    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     */
    public function validatePassword($attribute)
    {
        if (!$this->hasErrors()) {
            if ($this->loginType == 'admin') {
                $user = $this->getUser();
            } else {
                $user = $this->getSupervisorUser();
            }

            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, AuthModule::t('auth', 'does_not_match'));
            }
        }
    }

    /**
     * @return Extension|array|bool|ActiveRecord|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            if (!$this->loginAsExtension) {
                $this->_user = AdminMaster::findByUsernameAndStatus($this->username, 'super_admin');
            } else {
                $this->_user = Extension::findByUsername($this->username);
            }
        }
        return $this->_user;
    }

    /**
     * @return Extension|array|bool|ActiveRecord|null
     */
    public function getSupervisorUser()
    {
        if ($this->_user === false) {
            $adm_is_admin_status = AdminMaster::find()->select('adm_is_admin')->where(['adm_username' => $this->username])->one();
            if (isset($adm_is_admin_status) && !empty($adm_is_admin_status)) {
                if ($this->loginType == 'supervisor' && $adm_is_admin_status->adm_is_admin == 'agent') {
                    $this->_user = AdminMaster::findByUsernameAndStatus($this->username, 'agent');
                } else {
                    $this->_user = AdminMaster::findByUsernameAndStatus($this->username, 'supervisor');
                }
            }

        }
        return $this->_user;
    }

    public function checkActivationStatus($attribute)
    {
        if (!$this->hasErrors()) {

            if (!$this->loginAsExtension) {
                $user = AdminMaster::findByUsername($this->username);
                if ($user) {
                    if (!$user->adm_status) {
                        $this->addError($attribute, AuthModule::t('auth', 'login_only_if_user_is_active'));
                    }
                }
            }

        }
    }

    /**
     * Logs in a user with direct tenant user.
     *
     * @return bool whether the user is logged in successfully
     */
    public function userLogin()
    {
        // Check data in extension table
        $userData = Extension::findByUsername($this->username);
        $domain = TenantMaster::findWebDomainName($userData->tm_id);

        // create same array to userMaster
        $user = new UserMaster();
        $user->id = $userData->em_number . '@' . $domain;
        $user->user_domain = TenantMaster::findWebDomainName($userData->tm_id);
        $user->user_type = 'extension';
        $user->user_id = $userData->em_id;
        $user->user_name = $userData->em_number . '@' . $domain;
        $user->user_password = $userData->em_password;
        $user->user_token = null;
        $user->user_email_id = $userData->em_email;
        $user->user_status = $userData->em_status;
        $user->timezone = $userData->tz_id;
        $user->tm_id = $userData->tm_id;
        $user->superuser = 0;

        if ($userData) {
            return Yii::$app->user->login($user, $this->rememberMe ? 3600 * 24 * 30 : 0);
        }

        return $this->addError('username', AuthModule::t('auth', 'user_not_exist'));
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     * @throws InvalidArgumentException
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        }
        return false;
    }

    public function attributeLabels()
    {
        return [

            'username' => AuthModule::t('auth', 'username'),
            'password' => AuthModule::t('auth', 'password'),
            'extension' => AuthModule::t('auth', 'extension'),
            'newPassword' => AuthModule::t('auth', 'newPassword'),
            'confirmPassword' => AuthModule::t('auth', 'confirmPassword'),
            'campaign_type' => AuthModule::t('auth', 'campaign_type'),
        ];
    }
}
