<?php

namespace app\modules\ecosmob\crm\models;

use Yii;

/**
 * This is the model class for table "ct_lead_group_member".
 *
 * @property int $lg_id Lead group member auto increment id
 * @property int $ld_id Lead group id reference id from lead group table
 * @property string $lg_first_name Lead group member first name
 * @property string $lg_last_name Lead group member last name
 * @property string $lg_contact_number Lead group member contact number 
 * @property string $lg_contact_number_2
 * @property string $lg_email_id Lead group member email id
 * @property string $lg_address Lead group member contact number Addrss
 * @property string $lg_alternate_number Lead group member alternate address contact number 
 * @property string $lg_pin_code Lead group member contact number  pin code
 * @property string $lg_permanent_address Lead group member contact number permanent Address
 * @property string $lg_dial_status
 */
class LeadGroupMember extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ct_lead_group_member';
    }
    public $comments;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['lg_first_name', 'lg_last_name', 'lg_contact_number', 'lg_email_id'], 'required'],
            [[ 'ld_id','lg_contact_number','lg_contact_number_2','lg_alternate_number'], 'integer'],
            [['lg_email_id'], 'email'],
            [['lg_first_name', 'lg_last_name'], 'string', 'max' => 122],
            [['lg_contact_number', 'lg_pin_code'], 'string', 'max' => 15],
            [['lg_contact_number_2'], 'string', 'max' => 20],
            [['lg_email_id', 'lg_address','lg_permanent_address'], 'string', 'max' => 125],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'lg_id' => Yii::t('app', 'ID'),
            'ld_id' => Yii::t('app', 'Ld ID'),
            'lg_first_name' => Yii::t('app', 'lg_first_name'),
            'lg_last_name' => Yii::t('app', 'lg_last_name'),
            'lg_contact_number' => Yii::t('app', 'lg_contact_number'),
            'lg_contact_number_2' => Yii::t('app', 'lg_contact_number_2'),
            'lg_email_id' => Yii::t('app', 'lg_email_id'),
            'lg_address' => Yii::t('app', 'lg_address'),
            'lg_alternate_number' => Yii::t('app', 'lg_alternate_number'),
            'lg_pin_code' => Yii::t('app', 'lg_pin_codes'),
            'lg_permanent_address' => Yii::t('app', 'lg_permanent_address'),
            'lg_dial_status' => Yii::t('app', 'Dial Status'),
        ];
    }
}
