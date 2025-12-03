<?php

namespace app\modules\ecosmob\phonebook\models;

use app\modules\ecosmob\phonebook\PhoneBookModule;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "ct_phonebook".
 *
 * @property int $ph_id auto increment id
 * @property string $ph_first_name First Name
 * @property string $ph_last_name Last Name
 * @property string $ph_display_name Display Name
 * @property int $ph_extension Extensions
 * @property string $ph_phone_number
 * @property string $ph_cell_number
 * @property string $ph_email_id
 */
class Phonebook extends ActiveRecord
{

    /**
     * @var
     */
    public $importFileUpload;

    /**
     * @var array
     */
    public $displayNames = [];
    /**
     * @var array
     */
    public $sampleValues = [];

    /**
     * @var array
     */
    public $import
        = [
            'fields' => [
                'ph_first_name' => [
                    'displayName' => 'First_Name',
                    'sample' => 'Sample',
                ],
                'ph_last_name' => [
                    'displayName' => 'Last_Name',
                    'sample' => 'Sample',
                ],
                'ph_display_name' => [
                    'displayName' => 'Display_Name',
                    'sample' => '1234567890',
                ],
                'ph_extension' => [
                    'displayName' => 'Extension',
                    'sample' => '1323',
                ],
                'ph_phone_number' => [
                    'displayName' => 'Phone_Number',
                    'sample' => '21233321289',
                ],
               /* 'ph_cell_number' => [
                    'displayName' => 'Cell Number',
                    'sample' => '1234562121212',
                ],*/
                'ph_email_id' => [
                    'displayName' => 'Email_ID',
                    'sample' => 'Sample@ecosmob.com',
                ],

            ],

        ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ct_phonebook';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ph_first_name', 'ph_phone_number'], 'required'],
            /*[['ph_extension'], 'integer'],*/
            [['ph_first_name', 'ph_last_name', 'ph_display_name'], 'string', 'max' => 50],
            [['ph_phone_number', 'ph_cell_number'], 'string', 'max' => 15],
            [['ph_extension'], 'string', 'min' => 3, 'max' => 20],
            [['ph_phone_number', 'ph_cell_number','ph_extension'], 'number'],
            ['ph_phone_number', 'match', 'pattern'=>'/^[+0-9]{5,16}$/', 'message'=>PhoneBookModule::t('app', 'phone_number_should_contain_min_5_max_16_numbers_only')],
            ['ph_cell_number', 'match', 'pattern'=>'/^[+0-9]{5,16}$/', 'message'=>PhoneBookModule::t('app', 'cell_number_should_contain_min_5_max_16_numbers_only')],
            ['ph_extension', 'match', 'pattern'=>'/^[+0-9]{3,15}$/', 'message'=>PhoneBookModule::t('app', 'extension_number_should_contain_min_3_max_15_numbers_only')],
            [['ph_email_id'], 'string', 'max' => 30],
            [['ph_email_id'], 'email'],
            [
                ['ph_first_name'],
                'match',
                'pattern' => '/^[A-Za-z ]+$/',
                'message' => PhoneBookModule::t('app', 'first_name_contains_allowed_character_and_space')
            ],
            [
                ['ph_last_name'],
                'match',
                'pattern' => '/^[A-Za-z ]+$/',
                'message' => PhoneBookModule::t('app', 'last_name_contains_allowed_character_and_space')
            ],
            [
                ['importFileUpload'],
                'file',
                'extensions' => 'csv',
                'checkExtensionByMimeType' => false,
                'maxSize' => 10485760,
                'tooBig' => 'Limit is 10MB',
                'on' => 'import',
            ],
            [
                ['importFileUpload'],
                'required',
                'on' => 'import',
            ],
            [['importFileUpload'], 'safe'],
            [['ph_phone_number'], 'uniqueNumber'],
            [['ph_email_id'], 'uniqueEmail'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ph_id' => PhoneBookModule::t('app', 'ph_id'),
            'ph_first_name' => PhoneBookModule::t('app', 'ph_first_name'),
            'ph_last_name' => PhoneBookModule::t('app', 'ph_last_name'),
            'ph_display_name' => PhoneBookModule::t('app', 'ph_display_name'),
            'ph_extension' => PhoneBookModule::t('app', 'ph_extension'),
            'ph_phone_number' => PhoneBookModule::t('app', 'ph_phone_number'),
            'ph_cell_number' => PhoneBookModule::t('app', 'ph_cell_number'),
            'ph_email_id' => PhoneBookModule::t('app', 'ph_email_id'),
            'importFileUpload' => PhoneBookModule::t('app', 'importFileUpload'),
        ];
    }

    public function uniqueNumber($attribute)
    {
        if (!empty($this->ph_phone_number)) {
            $user_count = 0;
            if (!empty($this->ph_id)) {
                $user_count = Phonebook::find()->where(['ph_phone_number' => $this->ph_phone_number])->andWhere(["<>", "ph_id", $this->ph_id])->andWhere(["em_extension" => Yii::$app->user->identity->em_extension_number])->count();
            } else {
                $user_count = Phonebook::find()->where(['ph_phone_number' => $this->ph_phone_number])->andWhere(["em_extension" => Yii::$app->user->identity->em_extension_number])->count();
            }

            if ($user_count > 0) {
                $this->addError($attribute, PhoneBookModule::t('app', 'phone_number_exist'));
            }
        }
    }

    public function uniqueEmail($attribute)
    {
        if (!empty($this->ph_email_id)) {
            $user_count = 0;
            if (!empty($this->ph_id)) {
                $user_count = Phonebook::find()->where(['ph_email_id' => $this->ph_email_id])->andWhere(["<>", "ph_id", $this->ph_id])->andWhere(["em_extension" => Yii::$app->user->identity->em_extension_number])->count();
            } else {
                $user_count = Phonebook::find()->where(['ph_email_id' => $this->ph_email_id])->andWhere(["em_extension" => Yii::$app->user->identity->em_extension_number])->count();
            }

            if ($user_count > 0) {
                $this->addError($attribute, PhoneBookModule::t('app', 'email_exist'));
            }
        }
    }
}
