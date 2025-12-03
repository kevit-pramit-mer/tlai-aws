<?php

namespace app\modules\ecosmob\leadgroupmember\models;

use app\modules\ecosmob\leadgroupmember\LeadGroupMemberModule;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "ct_lead_group_member".
 *
 * @property int $lg_id Lead group member auto increment id
 * @property string $lg_first_name Lead group member first name
 * @property string $lg_last_name Lead group member last name
 * @property string $lg_contact_number Lead group member contact number
 * @property string $lg_email_id Lead group member email id
 * @property string $lg_address Lead group member contact number Address
 * @property string $lg_alternate_number Lead group member alternate address contact number
 * @property string $lg_pin_code Lead group member contact number  zip code
 * @property string $lg_permanent_address Lead group member contact number permanent Address
 * @property int $ld_id
 * @property string $lg_contact_number_2 Lead group member contact number two
 */
class LeadGroupMember extends ActiveRecord
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
                /*  'ld_id'=>[
                      'displayName'=>'Lead Group Id',
                      'sample'=>'6',
                  ],*/
                'lg_first_name' => [
                    'displayName' => 'First Name',
                    'sample' => 'Sample',
                ],
                'lg_last_name' => [
                    'displayName' => 'Last Name',
                    'sample' => 'Sample',
                ],
                'lg_contact_number' => [
                    'displayName' => 'Contact Number',
                    'sample' => '1234567890',
                ],
                'lg_contact_number_2' => [
                    'displayName' => 'Contact Number Two',
                    'sample' => '1234567891',
                ],
                'lg_email_id' => [
                    'displayName' => 'Email ID',
                    'sample' => 'Sample@ecosmob.com',
                ],
                'lg_address' => [
                    'displayName' => 'Address',
                    'sample' => 'Ahmadabad',
                ],
                'lg_pin_code' => [
                    'displayName' => 'Zipcode',
                    'sample' => '123456',
                ],
                'lg_alternate_number' => [
                    'displayName' => 'Alternate Number',
                    'sample' => '12345678',
                ],
                'lg_permanent_address' => [
                    'displayName' => 'Permanent Address',
                    'sample' => 'test',
                ],

            ],

        ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ct_lead_group_member';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [
                [
                    'lg_first_name',
                    'lg_contact_number',
                    'lg_email_id',
                    'lg_last_name',

                ],
                'required'
            ],
            [['lg_first_name', 'lg_last_name'], 'string', 'max' => 50, 'min' => 3],
            [
                ['lg_first_name'],
                'match',
                'pattern' => '/^[A-Za-z ]+$/',
                'message' => LeadGroupMemberModule::t('lead-group-member', 'first_name_allow_chacter_and_space')
            ],
            [
                ['lg_last_name'],
                'match',
                'pattern' => '/^[A-Za-z ]+$/',
                'message' => LeadGroupMemberModule::t('lead-group-member', 'last_name_allow_chacter_and_space')
            ],
            /* [
                 [
                     'lg_permanent_address'],
                 'match',
                 'pattern' => '/^[a-zA-Z.,-]+(?:\s[a-zA-Z.,-]+)*$/',
                 'message' => 'Invalid characters.'
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
                'checkExtensionByMimeType' => false,
                'maxSize' => 10485760,
                'skipOnEmpty' => false,
                'tooBig' => 'Limit is 10MB',
                'on' => 'import',
            ],


            [['lg_email_id'], 'uniqueEmail'],
            //[['lg_email_id'], 'uniqueEmail'],

            [['lg_contact_number'], 'uniqueContact'],
            //[['lg_contact_number'], 'uniqueContact'],

            /*[['ld_id'], 'unique'],*/
            [['lg_email_id'], 'string', 'max' => 35],
            [['lg_email_id'], 'match', 'pattern' => '/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', 'message' => LeadGroupMemberModule::t('lead-group-member', 'lg_email_validation')],
            [['lg_contact_number_2', 'lg_email_id','lg_redial_status'], 'safe'],
            [['lg_contact_number', 'lg_alternate_number'], 'number'],
            ['lg_contact_number', 'match', 'pattern' => '/^[+0-9]{5,16}$/', 'message' => LeadGroupMemberModule::t('lead-group-member', 'contact_number_should_contain_min_5_max_16_number_only')],
            ['lg_contact_number_2', 'match', 'pattern' => '/^[+0-9]{5,16}$/', 'message' => LeadGroupMemberModule::t('lead-group-member', 'contact_number_two_should_contain_min_5_max_16_number_only')],
            ['lg_alternate_number', 'match', 'pattern' => '/^[+0-9]{5,16}$/', 'message' => LeadGroupMemberModule::t('lead-group-member', 'alternate_number_should_contain_min_5_max_16_number_only')],
            ['lg_pin_code', 'match', 'pattern' => '/^[0-9A-Za-z]{5,15}$/', 'message' => LeadGroupMemberModule::t('lead-group-member', 'zip_code_should_contain_min_5_max_15_alphanumeric_only')],

            [['lg_address', 'lg_permanent_address'], 'string', 'max' => 150],
            [['importFileUpload'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'lg_id'=> Yii::t('app', 'lg_id'),
            'lg_first_name'=>Yii::t('app', 'lg_first_name'),
            'lg_last_name'=>Yii::t('app', 'lg_last_name'),
            'lg_contact_number'=>Yii::t('app', 'lg_contact_number'),
            'lg_contact_number_2'=>Yii::t('app', 'lg_contact_number_2'),
            'lg_email_id'=>Yii::t('app', 'lg_email_id'),
            'lg_address'=>Yii::t('app', 'lg_address'),
            'lg_alternate_number'=>Yii::t('app', 'lg_alternate_number'),
            'lg_pin_code'=>Yii::t('app', 'lg_pin_code'),
            'lg_permanent_address'=>Yii::t('app', 'lg_permanent_address'),
            'importFileUpload'=>Yii::t('app', 'file'),
        ];
    }

    public function uniqueEmail($attribute)
    {
        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $count = LeadGroupMember::find()->where(['lg_email_id' => $this->lg_email_id])->andWhere(['ld_id' => $this->ld_id])->andWhere(['<>', 'lg_id', $this->lg_id])->count();

        } else {
            $count = LeadGroupMember::find()->where(['lg_email_id' => $this->lg_email_id])->andWhere(['ld_id' => $this->ld_id])->count();
        }

        if ($count > 0) {
            $this->addError($attribute, LeadGroupMemberModule::t('lead-group-member', 'email_already_exists'));
        }
    }

    public function uniqueContact($attribute)
    {

        if (isset($_GET['id']) && !empty($_GET['id'])) {
            $count = LeadGroupMember::find()->where(['lg_contact_number' => $this->lg_contact_number])->andWhere(['ld_id' => $this->ld_id])->andWhere(['<>', 'lg_id', $this->lg_id])->count();
        } else {
            $count = LeadGroupMember::find()->where(['lg_contact_number' => $this->lg_contact_number])->andWhere(['ld_id' => $this->ld_id])->count();
        }

        if ($count > 0) {
            $this->addError($attribute, LeadGroupMemberModule::t('lead-group-member', 'contact_number_already_exists'));
        }
    }


}


