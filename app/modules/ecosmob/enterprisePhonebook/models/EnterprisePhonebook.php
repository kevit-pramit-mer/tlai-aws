<?php

namespace app\modules\ecosmob\enterprisePhonebook\models;

use Yii;
use app\modules\ecosmob\enterprisePhonebook\EnterprisePhonebookModule;
use app\modules\ecosmob\extension\models\Extension;

/**
 * This is the model class for table "tbl_enterprise_phonebook".
 *
 * @property int $id auto increment id
 * @property int $en_extension Extension
 * @property string $en_first_name First Name
 * @property string $en_last_name Last Name
 * @property string $en_mobile
 * @property string $en_phone
 * @property string $en_email_id
 * @property string $en_status
 * @property int $trago_ed_id
 */
class EnterprisePhonebook extends \yii\db\ActiveRecord
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
                'en_first_name' => [
                    'displayName' => 'First Name',
                    'sample' => 'Sample',
                ],
                'en_last_name' => [
                    'displayName' => 'Last Name',
                    'sample' => 'Sample',
                ],
                'en_extension' => [
                    'displayName' => 'Extension',
                    'sample' => '1323',
                ],
                'en_mobile' => [
                    'displayName' => 'Mobile Number',
                    'sample' => '21233321289',
                ],
                 'en_phone' => [
                     'displayName' => 'Phone Number (Landline)',
                     'sample' => '1234562121212',
                 ],
                'en_email_id' => [
                    'displayName' => 'Email Address',
                    'sample' => 'Sample@ecosmob.com',
                ],
                'en_status' => [
                    'displayName' => 'Status',
                    'sample' => 'Active',
                ],
            ],
        ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_enterprise_phonebook';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['en_first_name', 'en_last_name'], 'required'],
            [['trago_ed_id'], 'integer'],
            [['en_mobile', 'en_phone'], 'number'],
            [['en_first_name', 'en_last_name'], 'string', 'max' => 50],
            [['en_mobile'], 'string', 'min' => 10, 'max' => 15, 'tooLong'=> EnterprisePhonebookModule::t('app', 'mobile_max_validation'), 'tooShort' => EnterprisePhonebookModule::t('app', 'mobile_min_validation')],
            [['en_phone'], 'string', 'min' => 5, 'max' => 15, 'tooLong'=> EnterprisePhonebookModule::t('app', 'phone_max_validation'), 'tooShort' => EnterprisePhonebookModule::t('app', 'phone_min_validation')],
            [['en_email_id'], 'string', 'max' => 30],
            ['en_mobile', 'match', 'pattern'=>'/^\+?\d{10,15}$/', 'message'=> EnterprisePhonebookModule::t('app', 'en_mobile_should_contain_min_10_max_15_numbers_only')],
            ['en_phone', 'match', 'pattern'=>'/^\+?\d{5,15}$/', 'message'=> EnterprisePhonebookModule::t('app', 'en_phone_should_contain_min_5_max_15_numbers_only')],
            [['en_email_id'], 'email'],
            [
                'en_status', 'in',
                'range' => ['0', '1'], 'message' => EnterprisePhonebookModule::t('app', 'statusError')
            ],
            [
                ['en_first_name'],
                'match',
                'pattern' => '/^[A-Za-z ]+$/',
                'message' => EnterprisePhonebookModule::t('app', 'first_name_contains_allowed_character_and_space')
            ],
            [
                ['en_last_name'],
                'match',
                'pattern' => '/^[A-Za-z ]+$/',
                'message' => EnterprisePhonebookModule::t('app', 'last_name_contains_allowed_character_and_space')
            ],
            [
                ['en_mobile'],
                'required',
                'when' => function ($model) {
                    return ($model->en_phone == '');
                }, 'whenClient' => "function (attribute, value) {
                        return ($('#enterprisephonebook-en_phone').val() == '');
                    }"
            ],
            [
                ['en_phone'],
                'required',
                'when' => function ($model) {
                    return ($model->en_mobile == '');
                }, 'whenClient' => "function (attribute, value) {
                        return ($('#enterprisephonebook-en_mobile').val() == '');
                    }"
            ],
            [['en_mobile', 'en_phone'], 'mobilePhoneUnique'],
            //[['en_mobile', 'en_phone'], 'unique'],
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
            [['id', 'sampleValues', 'importFileUpload', 'en_first_name', 'en_last_name', 'en_extension', 'en_mobile', 'en_phone', 'en_email_id', 'en_status'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'en_extension' => EnterprisePhonebookModule::t('app', 'en_extension'),
            'en_first_name' => EnterprisePhonebookModule::t('app', 'en_first_name'),
            'en_last_name' => EnterprisePhonebookModule::t('app', 'en_last_name'),
            'en_mobile' => EnterprisePhonebookModule::t('app', 'en_mobile'),
            'en_phone' => EnterprisePhonebookModule::t('app', 'en_phone'),
            'en_email_id' => EnterprisePhonebookModule::t('app', 'en_email_id'),
            'trago_ed_id' => 'Trago Ed ID',
            'en_status' => EnterprisePhonebookModule::t('app', 'en_status'),
            'importFileUpload' => EnterprisePhonebookModule::t('app', 'importFileUpload'),
        ];
    }

    public function getExtension()
    {
        return $this->hasOne(Extension::className(), ['em_id' => 'en_extension']);

    }

    public function mobilePhoneUnique($attribute){

        if(!empty($this->attributes)){
            if($attribute == 'en_mobile'){
                $mobile = str_replace('+', '', $this->en_mobile);
                $enPhonebook = EnterprisePhonebook::find()->andWhere(['REPLACE(en_mobile, "+", "")' => $mobile]);
                if(!empty($this->id)){
                    $enPhonebook = $enPhonebook->andWhere(['!=', 'id', $this->id]);
                }
                $enPhonebook = $enPhonebook->asArray()->one();
                if(!empty($enPhonebook)){
                    return $this->addError($attribute, EnterprisePhonebookModule::t('app', 'mobileUnique', ['mobile' => $this->en_mobile]));
                }
            }
            if($attribute == 'en_phone'){
                $phone = str_replace('+', '', $this->en_phone);
                $enPhonebook = EnterprisePhonebook::find()->andWhere(['REPLACE(en_phone, "+", "")' => $phone]);
                if(!empty($this->id)){
                    $enPhonebook = $enPhonebook->andWhere(['!=', 'id', $this->id]);
                }
                $enPhonebook = $enPhonebook->asArray()->one();
                if(!empty($enPhonebook)){
                    return $this->addError($attribute, EnterprisePhonebookModule::t('app', 'phoneUnique', ['phone' => $this->en_phone]));
                }
            }
        }

    }
}
