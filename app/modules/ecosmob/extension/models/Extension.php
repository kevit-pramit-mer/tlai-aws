<?php

namespace app\modules\ecosmob\extension\models;

use app\modules\ecosmob\extension\extensionModule;
use app\modules\ecosmob\group\models\Group;
use app\modules\ecosmob\plan\models\Plan;
use app\modules\ecosmob\shift\models\Shift;
use app\modules\ecosmob\timezone\models\Timezone;
use Yii;
use yii\base\NotSupportedException;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "ct_extension_master".
 * @property int $em_plan_id
 * @property int $em_id
 * @property string $em_extension_name
 * @property string $em_password
 * @property string $em_web_password It is used for login to the web portal.
 * @property string $em_extension_number
 * @property string $em_status
 * @property int $em_shift_id
 * @property int $em_group_id
 * @property int $em_language_id
 * @property string $em_email
 * @property int $em_timezone_id
 * @property int $is_phonebook
 */
class Extension extends ActiveRecord implements IdentityInterface
{
    public $importFileUpload;
    public $em_number_type, $em_extension_range_to, $em_extension_range_from, $ecs_multiple_registeration, $ecs_fax2mail;

    public $max_calls, $ring_timeout, $call_timeout, $ob_max_timeout, $auto_recording, $dtmf_type, $video_calling, $bypass_media, $audio_codecs, $video_codecs, $dial_out, $voicemail, $voicemail_password, $feature_code_pin, $forwarding, $sipPassword, $imStatus;

    public $displayNames = [];
    public $sampleValues = [];
    public $oldPassword;
    public $newPassword;
    public $confirmPassword;
    public $adm_id;
    public $did;
    public $import
        = [
            'basic_fields' => [
                'em_extension_number' => [
                    'displayName' => 'Extension Number',
                    'sample' => '1212121212',
                ],
                'em_password' => [
                    'displayName' => 'SIP Password',
                    'sample' => 'Abcd@123',
                ],
                'em_extension_name' => [
                    'displayName' => 'Extension Name',
                    'sample' => 'Ext Name',
                ],
                'em_email' => [
                    'displayName' => 'Email',
                    'sample' => 'johndoe@mailinator.com',
                ],
                'em_language_id' => [
                    'displayName' => 'Language',
                    'sample' => 'English',
                ],
                'em_status' => [
                    'displayName' => 'Status',
                    'sample' => 'Active',
                ],
                'em_web_password' => [
                    'displayName' => 'Web Password',
                    'sample' => 'Test@123',
                ],
                'is_phonebook' => [
                    'displayName' => 'Is Phonebook',
                    'sample' => 'Active',
                ],
                'em_timezone_id' => [
                    'displayName' => 'Timezone',
                    'sample' => 'America/Argentina/Jujuy',
                ]
            ],
            'advanced_fields' => [
                'em_extension_number' => [
                    'displayName' => 'Extension Number',
                    'sample' => '1212121212',
                ],
                'em_password' => [
                    'displayName' => 'SIP Password',
                    'sample' => 'Abcd@123',
                ],
                'em_extension_name' => [
                    'displayName' => 'Extension Name',
                    'sample' => 'ExtName',
                ],
                'em_email' => [
                    'displayName' => 'Email',
                    'sample' => 'johndoe@mailinator.com',
                ],
                'em_language_id' => [
                    'displayName' => 'Language',
                    'sample' => 'English',
                ],
                'em_status' => [
                    'displayName' => 'Status',
                    'sample' => 'Active',
                ],
                'em_web_password' => [
                    'displayName' => 'Web Password',
                    'sample' => 'Test@123',
                ],
                'is_phonebook' => [
                    'displayName' => 'Is Phonebook',
                    'sample' => 'Active',
                ],
                'em_timezone_id' => [
                    'displayName' => 'Timezone',
                    'sample' => 'America/Argentina/Jujuy',
                ],
                'ecs_bypass_media' => [
                    'displayName' => 'Bypass Media',
                    'sample' => 'No',
                ],
                'external_caller_id' => [
                    'displayName' => 'External CallerID',
                    'sample' => '355656',
                ],
                'ecs_max_calls' => [
                    'displayName' => 'Simultaneous External Call',
                    'sample' => '1',
                ],
                'ecs_forwarding' => [
                    'displayName' => 'Forwarding',
                    'sample' => 'Disable',
                ],
                'ecs_ring_timeout' => [
                    'displayName' => 'Ring Timeout(sec.)',
                    'sample' => '60',
                ],
                'ecs_call_timeout' => [
                    'displayName' => 'Dial Timeout(sec.)',
                    'sample' => '60',
                ],
                'ecs_ob_max_timeout' => [
                    'displayName' => 'Max Timeout(sec.)',
                    'sample' => '3600',
                ],
                'ecs_video_calling' => [
                    'displayName' => 'Video Calling',
                    'sample' => 'Inactive',
                ],
                'ecs_auto_recording' => [
                    'displayName' => 'Extension Auto Recording',
                    'sample' => 'Disable',
                ],
                'ecs_dtmf_type' => [
                    'displayName' => 'DTMF Type',
                    'sample' => 'IN BAND',
                ],
                'ecs_multiple_registeration' => [
                    'displayName' => 'Multiple Registration',
                    'sample' => 'Active',
                ],
                'ecs_dial_out' => [
                    'displayName' => 'Dial Out',
                    'sample' => 'Active',
                ],
                'ecs_voicemail' => [
                    'displayName' => 'Voice Mail',
                    'sample' => 'Active',
                ],
                'ecs_voicemail_password' => [
                    'displayName' => 'VM Password',
                    'sample' => '123456',
                ],
                'ecs_fax2mail' => [
                    'displayName' => 'Fax',
                    'sample' => 'Active',
                ],
                'ecs_audio_codecs' => [
                    'displayName' => 'Active Audio Codecs',
                    'sample' => 'PCMA',
                ],
                'ecs_video_codecs' => [
                    'displayName' => 'Active Video Codecs',
                    'sample' => 'VP8',
                ],
                'did' => [
                    'displayName' => 'DID',
                    'sample' => '121212121212',
                ],
            ],
            'trago_advanced_fields' => [
                'em_extension_number' => [
                    'displayName' => 'Extension Number',
                    'sample' => '1212121212',
                ],
                'em_password' => [
                    'displayName' => 'SIP Password',
                    'sample' => 'Abcd@123',
                ],
                'em_extension_name' => [
                    'displayName' => 'Extension Name',
                    'sample' => 'ExtName',
                ],
                'em_email' => [
                    'displayName' => 'Email',
                    'sample' => 'johndoe@mailinator.com',
                ],
                'em_language_id' => [
                    'displayName' => 'Language',
                    'sample' => 'English',
                ],
                'em_status' => [
                    'displayName' => 'Status',
                    'sample' => 'Active',
                ],
                'em_web_password' => [
                    'displayName' => 'Web Password',
                    'sample' => 'Test@123',
                ],
                'is_phonebook' => [
                    'displayName' => 'Is Phonebook',
                    'sample' => 'Active',
                ],
                'em_timezone_id' => [
                    'displayName' => 'Timezone',
                    'sample' => 'America/Argentina/Jujuy',
                ],
                'ecs_bypass_media' => [
                    'displayName' => 'Bypass Media',
                    'sample' => 'No',
                ],
                'external_caller_id' => [
                    'displayName' => 'External CallerID',
                    'sample' => '355656',
                ],
                'ecs_max_calls' => [
                    'displayName' => 'Simultaneous External Call',
                    'sample' => '1',
                ],
                'ecs_forwarding' => [
                    'displayName' => 'Forwarding',
                    'sample' => 'Disable',
                ],
                'ecs_ring_timeout' => [
                    'displayName' => 'Ring Timeout(sec.)',
                    'sample' => '60',
                ],
                'ecs_call_timeout' => [
                    'displayName' => 'Dial Timeout(sec.)',
                    'sample' => '60',
                ],
                'ecs_ob_max_timeout' => [
                    'displayName' => 'Max Timeout(sec.)',
                    'sample' => '3600',
                ],
                'ecs_video_calling' => [
                    'displayName' => 'Video Calling',
                    'sample' => 'Inactive',
                ],
                'ecs_auto_recording' => [
                    'displayName' => 'Extension Auto Recording',
                    'sample' => 'Disable',
                ],
                'ecs_dtmf_type' => [
                    'displayName' => 'DTMF Type',
                    'sample' => 'IN BAND',
                ],
                'ecs_multiple_registeration' => [
                    'displayName' => 'Multiple Registration',
                    'sample' => 'Active',
                ],
                'ecs_dial_out' => [
                    'displayName' => 'Dial Out',
                    'sample' => 'Active',
                ],
                'ecs_im_status' => [
                    'displayName' => 'Instant Messaging',
                    'sample' => 'Inactive',
                ],
                'ecs_voicemail' => [
                    'displayName' => 'Voice Mail',
                    'sample' => 'Active',
                ],
                'ecs_voicemail_password' => [
                    'displayName' => 'VM Password',
                    'sample' => '123456',
                ],
                'ecs_fax2mail' => [
                    'displayName' => 'Fax',
                    'sample' => 'Active',
                ],
                'ecs_audio_codecs' => [
                    'displayName' => 'Active Audio Codecs',
                    'sample' => 'PCMA',
                ],
                'ecs_video_codecs' => [
                    'displayName' => 'Active Video Codecs',
                    'sample' => 'VP8',
                ],
                'did' => [
                    'displayName' => 'DID',
                    'sample' => '121212121212',
                ],
            ],
        ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ct_extension_master';
    }

    /**
     * @param int|string $id
     * @return Extension|IdentityInterface|null
     */
    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    /**
     * @param mixed $token
     * @param null $type
     * @return void|IdentityInterface
     * @throws NotSupportedException
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException("Find Identity by Access Token not implemented.");
    }

    /**
     * @param $username
     * @return Extension|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['em_extension_number' => $username, 'em_status' => 1]);
    }

    /**
     * @param $tnId
     * @return mixed
     */
    public static function getStatus($tnId)
    {
        return static::find()->where(['adm_user_id' => $tnId])->andWhere(['adm_is_admin' => 'tenant_admin'])->one()['adm_status'];
    }

    /**
     * @param $username
     */
    public static function findByUserAuth($username)
    {
        return static::findOne(['em_extension_number' => $username]);
    }

    /**
     * @return array
     */
    public static function getExtensionList()
    {
        if ($dataModel = Extension::find()->select(['CONCAT(em_extension_name," - ", em_extension_number) as em_extension_name', 'em_extension_number'])->all()) {
            return ArrayHelper::map($dataModel, 'em_extension_number', 'em_extension_name');
        } else {
            return [];
        }
    }

    public static function getVoicemailList()
    {
        if ($dataModel = Extension::find()->select(['CONCAT(ct_extension_master.em_extension_name," - ", ct_extension_master.em_extension_number) as em_extension_name', 'ct_extension_master.em_extension_number'])
            ->leftJoin('ct_extension_call_setting as ecs', 'ecs.em_id = ct_extension_master.em_id')
            ->where(['ecs.ecs_voicemail' => '1'])
            ->asArray()->all()) {
            return ArrayHelper::map($dataModel, 'em_extension_number', 'em_extension_name');
        } else {
            return [];
        }
    }

    /**
     * @return array
     */
    public static function getExtensionNumbersList()
    {
        if ($dataModel = Extension::find()->all()) {
            return ArrayHelper::map($dataModel, 'em_extension_number', 'em_extension_number');
        } else {
            return [];
        }
    }

    public static function getMohFiles()
    {
        return ArrayHelper::map(static::find()->where(['af_type' => 'MOH'])->orderBy(['af_id' => SORT_DESC])->all(), 'af_name', 'af_name');
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[/*'em_plan_id',*/ 'em_extension_number', 'em_web_password', 'em_shift_id', 'em_group_id', 'em_language_id', 'em_timezone_id', 'em_extension_name', 'em_email'], 'required'],
            [[/*'em_plan_id',*/ 'em_shift_id', 'em_group_id', 'em_timezone_id'], 'integer'],
            [
                'em_language_id', 'in',
                'range' => ['1', '2'], 'message' => extensionModule::t('app', 'langError')
            ],
            [
                'em_status', 'in',
                'range' => ['0', '1'], 'message' => extensionModule::t('app', 'statusError')
            ],
            [
                'is_phonebook', 'in',
                'range' => ['0', '1'], 'message' => extensionModule::t('app', 'phoneBookError')
            ],
            [['em_web_password', 'em_password'], 'string'],
            [['em_number_type'], 'string'],
            [['em_extension_number'], 'number'],

            [['em_moh', 'trago_username', 'is_tragofone', 'did'], 'safe'],
            [['em_extension_number'], 'string', 'min' => 3, 'max' => 20, 'tooLong'=> extensionModule::t('app', 'ext_num_max_validation'), 'tooShort' => extensionModule::t('app', 'ext_num_min_validation')],
            [['em_extension_range_to', 'em_extension_range_from'], 'integer'],
            [['em_extension_name'], 'string', 'max' => 20],
            //['em_web_password', 'match', 'pattern' => '/^(?=.*\d)(?=.*[$@$!%*#?&])(?=.*[$@$!%*#?&])(?=\S*[A-Z])[A-Za-z\d$@$!%*#?&]{8,}$/', 'message' => 'Password must have 8 characters with at least one number, one special character and one capital letter.'],
            ['em_web_password', 'match', 'pattern' => '/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[#?!@.$%^&*\-_{}()])[a-zA-Z\d#?!@.$%^&*\-_{}()]{8,20}$/', 'message' => extensionModule::t('app', 'web_strong_password_validation')],
            [['em_extension_name'], 'match', 'pattern' => '/^[0-9a-zA-Z\s]*$/', 'message' => extensionModule::t('app', 'extension_name_validation')],
            /*[['em_web_password'],
                'match',
                'pattern'=>'/^(?=.*\d)(?=.*[$@{$(!}%)*#?&])[A-Za-z\d$@{$(!}%)*#?&]{8,50}$/'
                ,
                'message'=>Yii::t('app',
                    'Password must contain min 8 character, lowercase, uppercase, a number and at least one special character.'),

            ],*/
            /* [['em_password'],
                 'match',
                 'pattern'=>'/^(?=.*\d)(?=.*[$@$!%*#?&])(?=.*[$@$!%*#?&])(?=\S*[A-Z])[A-Za-z\d$@$!%*#?&]{8,}$/'
                 ,
                 'message'=>Yii::t('app',
                     'Password must contain min 8 character, lowercase, uppercase and a number.'),

             ],*/
            ['em_password', 'match', 'pattern' => '/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[#?!@.$%^&*\-_{}()])[a-zA-Z\d#?!@.$%^&*\-_{}()]{8,20}$/', 'message' => extensionModule::t('app', 'em_strong_password_validation')],

            /*[['em_password', 'em_web_password'], 'isUnCrackPasswordOnly'],*/
            [['em_email'], 'string', 'max' => 256],
            [['em_email'], 'match', 'pattern' => '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', 'message' => extensionModule::t('app', 'em_email_validation')],
            [['em_email'], 'unique'],
           /* [
                ['em_extension_number'],
                'required',
                'when' => function ($model) {
                    return $model->em_number_type == "number";
                },
                'whenClient' => "function (attribute, value) {
                        return ($('#extension-em_number_type').val() == 'number');
            }"
            ],
            [
                ['em_extension_range_to', 'em_extension_range_from'],
                'required',
                'when' => function ($model) {
                    return $model->em_number_type == "range";
                },
                'whenClient' => "function (attribute, value) {
                        return ($('#extension-em_number_type').val()=='range');
            }"
            ],*/
            [['em_extension_range_to', 'em_extension_range_from'], 'string', 'min' => 3, 'max' => 20],
            [['em_extension_number'], 'unique'],
            [['em_extension_number'], 'checkUnique'],
            [['em_extension_range_to'], 'compare', 'compareAttribute' => 'em_extension_range_from', 'operator' => '>='],
            [
                'em_extension_range_to',
                'checkDiff',
                'when' => function ($model) {
                    return $model->em_number_type == "range";
                },
                'whenClient' => "function (attribute, value) {
                        return ($('#extension-em_number_type').val()=='range');
            }"
            ],
            /*[
                'em_extension_range_from',
                'checkUnique',
                'when'=>function ($model) {
                    return $model->em_number_type == "range";
                },
                'whenClient'=>"function (attribute, value) {
                        return ($('#extension-em_number_type').val()=='range');
            }"
            ],*/
            [
                ['importFileUpload'],
                'required',
                'on' => 'import',
            ],
            [
                ['importFileUpload'],
                'file',
                'extensions' => 'csv',
                'checkExtensionByMimeType' => FALSE,
                'maxSize' => 10485760,
                'tooBig' => 'Limit is 10MB',
                'on' => 'import',
                'wrongExtension'=> Yii::t('app', 'wrongImportExtFile'),
            ],
            [['sampleValues', 'importFileUpload', 'em_id', 'em_shift_id'], 'safe'],

            /* Change Password Scenario Validation */
            [['oldPassword', 'newPassword', 'confirmPassword'], 'required', 'on' => 'changePassword'],
            [['oldPassword'], 'checkCurrentPassword', 'on' => 'changePassword'],
            [['oldPassword', 'newPassword', 'confirmPassword'], 'string', 'min' => 8, 'max' => 50, 'on' => 'changePassword'],
            /*[['newPassword'], 'isUnCrackPasswordOnly', 'on'=>'changePassword'],*/
            [
                'newPassword',
                'match',
                'pattern' => '/^(?=.*\d)(?=.*[${(@$!%*#?})&])[A-Za-z\d${(@$!%*#?})&]{8,50}$/',
                'message' => Yii::t('app', Yii::t('app', 'strong_password_validation')),
                'on' => 'changePassword'
            ],
            [
                ['confirmPassword'],
                'compare',
                'compareAttribute' => 'newPassword',
                'message' => Yii::t('app', 'password_does_not_match'),
                'on' => 'changePassword',
            ],
            [['external_caller_id'], 'number'],
            [['external_caller_id'], 'string', 'min' => 3, 'max' => 15, 'tooLong' => extensionModule::t('app', 'external_caller_id_max_validation'), 'tooShort' => extensionModule::t('app', 'external_caller_id_min_validation')],

        ];
    }

    /**
     * Check Current Password
     *
     * Current Password validation with old Password
     *
     */
    public function checkCurrentPassword()
    {
        if ($this->em_web_password != $this->oldPassword) {
            $this->addError('oldPassword', Yii::t('app', 'invalid_current_password'));
        }
    }

    /**
     * @param $attribute
     */
    public function isUnCrackPasswordOnly($attribute)
    {
        if (!preg_match('(/^(?=.*\d)(?=.*[$@{$(!}%)*#?&])[A-Za-z\d$@{$(!}%)*#?&]{8,50}$/)', $this->$attribute)) {
            $this->addError($attribute, Yii::t('app', 'strong_password_validation'));
        }
    }

    public function checkDiff($attribute, $params)
    {
        /*if (preg_replace('/\D+/', '', $this->em_extension_range_to) < 100 || preg_replace('/\D+/', '', $this->em_extension_range_from) < 100)
        {
            $this->addError($attribute, 'Should enter 3 digit extension number in From and To');
        }*/
        $diff = preg_replace('/\D+/', '', $this->em_extension_range_to) - preg_replace('/\D+/', '', $this->em_extension_range_from);
        if ($diff > 100) {
            $this->addError($attribute, extensionModule::t('app', 'range_greater_than_100'));
        } else if ($diff <= 0) {
            $this->addError($attribute, extensionModule::t('app', 'range_greater_than_1'));
        }
    }

    /**
     * @return array
     */
    public function getPlanList()
    {
        $planModel = Plan::find()->asArray()->orderBy(['pl_name' => SORT_ASC])->all();
        return ArrayHelper::map($planModel, 'pl_id', 'pl_name');
    }

    /**
     * @return array
     */
    public function getShiftList()
    {
        $shiftModel = Shift::find()->asArray()->orderBy(['sft_name' => SORT_ASC])->all();
        return ArrayHelper::map($shiftModel, 'sft_id', 'sft_name');
    }

    /**
     * @return array
     */
    public function getGroupList()
    {
        $groupModel = Group::find()->asArray()->orderBy(['grp_name' => SORT_ASC])->all();
        return ArrayHelper::map($groupModel, 'grp_id', 'grp_name');
    }

    /**
     * @return array
     */
    public function getTimezoneList()
    {
        $timezoneModel = Timezone::find()->asArray()->orderBy(['tz_zone' => SORT_ASC])->all();
        return ArrayHelper::map($timezoneModel, 'tz_id', 'tz_zone');
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'em_plan_id' => extensionModule::t('app', 'plan'),
            'em_extension_name' => extensionModule::t('app', 'extension_name'),
            'em_password' => extensionModule::t('app', 'em_password'),
            'max_calls' => extensionModule::t('app', 'max_call'),
            'ring_timeout' => extensionModule::t('app', 'ring_timeout'),
            'call_timeout' => extensionModule::t('app', 'call_timeout'),
            'ob_max_timeout' => extensionModule::t('app', 'ob_max_timeout'),
            'auto_recording' => extensionModule::t('app', 'auto_recording'),
            'dtmf_type' => extensionModule::t('app', 'DTMF_type'),
            'video_calling' => extensionModule::t('app', 'video_calling'),
            'bypass_media' => extensionModule::t('app', 'Bypass Media'),
            'audio_codecs' => extensionModule::t('app', 'Audio Codecs'),
            'video_codecs' => extensionModule::t('app', 'Video Codecs'),
            'dial_out' => extensionModule::t('app', 'dial_out'),
            'voicemail' => extensionModule::t('app', 'voice_mail'),
            'voicemail_password' => extensionModule::t('app', 'voicemail_password'),
            'feature_code_pin' => extensionModule::t('app', 'feature_code_pin'),
            'forwarding' => extensionModule::t('app', 'forwarding'),
            'em_web_password' => extensionModule::t('app', 'web_password'),
            'em_extension_number' => extensionModule::t('app', 'extension_number'),
            'em_extension_range_from' => extensionModule::t('app', 'from'),
            'em_extension_range_to' => extensionModule::t('app', 'to'),
            'em_status' => extensionModule::t('app', 'status'),
            'em_shift_id' => extensionModule::t('app', 'shift'),
            'em_group_id' => extensionModule::t('app', 'group'),
            'em_language_id' => extensionModule::t('app', 'language'),
            'em_email' => extensionModule::t('app', 'email'),
            'em_timezone_id' => extensionModule::t('app', 'timezone'),
            'is_phonebook' => extensionModule::t('app', 'Is Phonebook'),
            'ecs_multiple_registeration' => extensionModule::t('app', 'multiple_registeration'),
            'ecs_fax2mail' => extensionModule::t('app', 'fax'),
            'importFileUpload' => extensionModule::t('app', 'importFileUpload'),
            'oldPassword' => Yii::t('app', 'oldPassword'),
            'newPassword' => Yii::t('app', 'newPassword'),
            'confirmPassword' => Yii::t('app', 'confirmPassword'),
            'external_caller_id' => extensionModule::t('app', 'external_caller_id'),
            'is_tragofone' => extensionModule::t('app', 'is_tragofone'),
            'sipPassword' => extensionModule::t('app', 'sipPassword'),
            'imStatus' => extensionModule::t('app', 'imStatus'),
            'did' => extensionModule::t('app', 'did'),
        ];
    }

    /**
     * @return int|string
     */
    public function getId()
    {
        return $this->em_id;
    }

    /**
     * @return string|null
     */
    public function getAuthKey()
    {
        return null;
    }

    /**
     * @param $password
     * @return bool
     */
    public function validatePassword($password)
    {
        return ($this->em_web_password === ($password));
    }

    /**
     * @return ActiveQuery
     */
    public function getCallsettings()
    {
        return $this->hasOne(Callsettings::className(), ['em_id' => 'em_id']);
    }

    /**
     * Relation with Shift model
     * @return object
     */
    public function getShift()
    {
        return $this->hasOne(Shift::className(), ['sft_id' => 'em_shift_id']);
    }

    /**
     * Relation with Group model
     * @return object
     */
    public function getGroup()
    {
        return $this->hasOne(Group::className(), ['grp_id' => 'em_group_id']);
    }

    /**
     * Relation with TimeZone model
     * @return object
     */
    public function getTimezone()
    {
        return $this->hasOne(Timezone::className(), ['tz_id' => 'em_timezone_id']);
    }

    /**
     * @param string $authKey
     * @return bool
     */
    public function validateAuthKey($authKey)
    {
        return true;
    }

    /**
     * @param $attribute
     */
    public function checkUnique($attribute)
    {
        $result = Yii::$app->commonHelper->checkUniqueExtension($this->em_extension_number, $this->getOldAttribute('em_extension_number'));

        if ($result) {
            $this->addError($attribute, $result);
        }
    }

    public static function getTrgofoneStatus($username, $password)
    {
        $customer = Yii::$app->tragofoneHelper->getCustomerTokenForCron($username, $password);
        if (!empty($customer)) {
            $token = $customer;
            $extension = Extension::find()->where(['IS NOT', 'trago_user_id', null])->all();
            if (!empty($extension)) {
                foreach ($extension as $_extension) {
                    $data = [
                        "usr_id" => $_extension->trago_user_id
                    ];
                    $api = Yii::$app->tragofoneHelper->getUserForCron($data, $token);
                    if (!empty($api)) {
                        $api = json_decode($api, true);
                        if ($api['status'] == 'SUCCESS') {
                            $_extension->is_tragofone = ($api['data'][0]['usr_status'] == 'Active' ? '1' : '0');

                        } elseif ($api['status'] == 'ERROR' && $api['message'] == 'No User Exist') {
                            $_extension->trago_user_id = null;
                            $_extension->is_tragofone = '0';
                        } else {
                            $_extension->is_tragofone = '0';
                        }
                        $_extension->save(false);
                    }
                }
            }
        } else {
            Extension::updateAll(['is_tragofone' => '0'], ['IS NOT', 'trago_user_id', null]);
        }
    }

    public static function updateImStatus($emId, $userId)
    {
        $extCallSetModel = Callsettings::findOne(['em_id' => $emId]);
        if(!empty($extCallSetModel)) {
            $customer = Yii::$app->tragofoneHelper->getCustomerToken();
            if (!empty($customer)) {
                $token = $customer;
                $configData = [
                    "user_id" => $userId
                ];
                $configApi = Yii::$app->tragofoneHelper->getUserConfig($configData, $token);
                if (!empty($configApi)) {
                    $configApi = json_decode($configApi, true);
                    if ($configApi['status'] == 'SUCCESS') {
                        $extCallSetModel->ecs_im_status = ($configApi['data']['configurations']['IM']['im_status'] == 'TRUE' ? '1' : '0');
                        $extCallSetModel->save(false);
                    }
                }
            }
        }
    }
}
