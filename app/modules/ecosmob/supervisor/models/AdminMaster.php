<?php

namespace app\modules\ecosmob\supervisor\models;

use app\modules\ecosmob\supervisor\SupervisorModule;
use Yii;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

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
 * @property string $adm_mapped_extension
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
     * @return array
     */
    public static function getAllSupervisors()
    {
        $model = AdminMaster::find()->where(['adm_is_admin' => 'supervisor'])->all();

        return ArrayHelper::map($model, 'adm_id', 'adm_username');
    }

    /**
     * @return array
     */
    public static function getAllAgents()
    {
        $model = AdminMaster::find()->where(['adm_is_admin' => 'agent'])->all();

        return ArrayHelper::map($model, 'adm_id', 'adm_username');
    }

    /**
     * @param $users
     * @return array
     */
    public static function getDetails($users)
    {
        $model = AdminMaster::find()->select(['adm_id', 'adm_username'])
            ->andWhere(new Expression('FIND_IN_SET(adm_id,"' . $users . '")'))
            ->asArray()->all();

        return ArrayHelper::map($model, 'adm_id', 'adm_username');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['adm_firstname', 'adm_lastname', 'adm_username', 'adm_email', 'adm_password', 'adm_status', 'adm_timezone_id', 'adm_mapped_extension'], 'required'],
            [['adm_status'], 'string'],
            [['adm_timezone_id'], 'integer'],
            [['adm_last_login', 'adm_firstname', 'adm_lastname'], 'safe'],
            [['adm_is_admin'], 'string', 'max' => 100],
            [['adm_contact'], 'string', 'max' => 15],
            [['adm_password'], 'string', 'max' => 20],
            [['adm_firstname', 'adm_lastname', 'adm_username'], 'string', 'max' => 30, 'min' => 2],
            [['adm_email'], 'string', 'max' => 40],
            [['adm_email', 'adm_username', 'adm_mapped_extension'], 'unique'],
            [['adm_email'], 'email'],
            [['adm_firstname'], 'match', 'pattern' => '/^[a-zA-Z]+$/', 'message' => SupervisorModule::t('supervisor', 'first_name')],
            [['adm_lastname'], 'match', 'pattern' => '/^[a-zA-Z]+$/', 'message' => SupervisorModule::t('supervisor', 'last_name')],
            ['adm_username', 'match', 'pattern' => '/^[a-zA-Z0-9_]+$/', 'message' => SupervisorModule::t('supervisor', 'user_name')],
            [['adm_firstname', 'adm_lastname'], 'match', 'pattern' => '/^[0-9a-zA-Z\s]*$/', 'message' => 'Field contain character value'],
            ['adm_password', 'match', 'pattern' => '/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[#?!@.$%^&*\-_{}()])[a-zA-Z\d#?!@.$%^&*\-_{}()]{8,20}$/', 'message' => SupervisorModule::t('supervisor', 'password_strong_validation')],
            [['adm_username'], 'match', 'pattern' => '/^[0-9a-zA-Z]*$/', 'message' => SupervisorModule::t('supervisor', 'user_name_alphanumeric_validation')],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'adm_id' => Yii::t('app', 'Adm ID'),
            'adm_firstname' => SupervisorModule::t('supervisor', 'firstname'),
            'adm_lastname' => SupervisorModule::t('supervisor', 'lastname'),
            'adm_username' => SupervisorModule::t('supervisor', 'username'),
            'uname' => SupervisorModule::t('supervisor', 'username'),
            'adm_email' => SupervisorModule::t('supervisor', 'email'),
            'adm_password' => SupervisorModule::t('supervisor', 'password'),
            'adm_is_admin' => SupervisorModule::t('supervisor', 'is_admin'),
            'adm_status' => SupervisorModule::t('supervisor', 'status'),
            'adm_timezone_id' => SupervisorModule::t('supervisor', 'timezone'),
            'adm_mapped_extension' => SupervisorModule::t('supervisor', 'extension'),
        ];
    }
}
