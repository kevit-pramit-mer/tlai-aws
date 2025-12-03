<?php

namespace app\modules\ecosmob\autoattendant\models;

use app\models\CommonModel;
use app\models\CommonModelValidationTrait;
use app\modules\ecosmob\audiomanagement\models\AudioManagement;
use app\modules\ecosmob\autoattendant\AutoAttendantModule;
use Yii;
use yii\helpers\ArrayHelper;
use Exception;
use Throwable;
use yii\db\StaleObjectException;

/**
 * This is the model class for table "tbl_auto_attendant_master".
 *
 * @property integer $aam_id
 * @property string $aam_name
 * @property string $aam_extension
 * @property string $aam_greet_long
 * @property string $aam_greet_short
 * @property string $aam_invalid_sound
 * @property string $aam_exit_sound
 * @property string $aam_failure_prompt
 * @property string $aam_timeout_prompt
 * @property string $aam_timeout
 * @property string $aam_inter_digit_timeout
 * @property string $aam_max_failures
 * @property string $aam_max_timeout
 * @property string $aam_language
 * @property integer $sp_id
 * @property integer $aam_mapped_id
 * @property integer $aam_level
 * @property string $aam_status
 * @property string $aam_direct_dial
 * @property string $aam_transfer_on_failure
 * @property integer $aam_digit_len
 */
class AutoAttendantMaster extends CommonModel
{

    use CommonModelValidationTrait;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'auto_attendant_master';
    }

    /**
     * Get All AutoAttendant data where status in Active and Tenant is Current Login Extension.
     *
     * @return static[]
     */
    public static function getAutoData()
    {
        return static::findAll([
            'aam_status' => '1',
            'aam_level' => 0,
        ]);
    }

    /**
     * Get All Records where aam_mapped_id is $id.
     *
     * @param $name
     *
     * @return AutoAttendantMaster
     */
    public static function getAllSubMenuData($name)
    {
        return static::findOne(['aam_name' => $name, 'aam_status' => '1']);
    }

    /**
     * @param $id
     * @param $linkId
     * @return null|static
     */
    public static function getAllSubMenuName($id, $linkId)
    {

        if ($dataModel = static::find()->where(['aam_mapped_id' => $linkId, 'aam_level' => 1])->all()) {
            return ArrayHelper::map($dataModel, 'aam_id', 'aam_name');
        } else {
            return [];
        }

//        $data = static::findOne( [ 'aam_id' => $id, 'aam_status' => '1' ] );
    }

    /**
     * @param $id
     * @param $linkId
     * @return null|static
     */
    public static function getAllAudioText($id, $linkId)
    {

        if ($dataModel = static::find()->where(['aam_level' => 0])
            ->andWhere(['<>', 'aam_mapped_id', $linkId])
            ->all()
        ) {
            return ArrayHelper::map($dataModel, 'aam_id', 'aam_name');
        } else {
            return [];
        }

//        $data = static::findOne( [ 'aam_id' => $id, 'aam_status' => '1' ] );
    }

    public static function getAudioTextExtList()
    {
        if ($audioModel = AutoAttendantMaster::find()->all()) {
            return ArrayHelper::map($audioModel, 'aam_extension', 'aam_name');
        } else {
            return [];
        }
    }


    /**
     * Get Old Submenu Data where aam_id is $id.
     *
     * @param $id
     *
     * @return AutoAttendantMaster
     * @internal param $name
     */
    public static function getOldSubMenuData($id)
    {
        return static::findOne(['aam_id' => $id]);
    }

    /**
     * Get SubMenu id By Its Name
     *
     * @param $name
     *
     * @return mixed
     */
    public static function getIdByName($name)
    {
        /** @var AutoAttendantMaster $subMenuId */
        $subMenuId = static::find()->where([
            'aam_name' => $name,
        ])->one();

        return isset($subMenuId->aam_id) ? $subMenuId->aam_id : '';
    }

    /**
     * Concat Extension Number and Name
     *
     * @return string
     */
    public function getNameNumber()
    {
        return $this->aam_name;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['aam_name', 'aam_digit_len', 'aam_greet_long', 'aam_timeout', 'aam_failure_prompt', 'aam_greet_short', 'aam_invalid_sound', 'aam_exit_sound', 'aam_extension'], 'required',],
            [['aam_name', 'aam_extension'], 'unique'],
            [['aam_mapped_id', 'aam_level'], 'integer'],
            [['aam_name'], 'string', 'max' => 20],
            ['aam_name', 'match', 'pattern' => '/^[a-zA-Z0-9_.]+$/', 'message' => AutoAttendantModule::t('autoattendant', 'name_alphanumeric_validation')],

            ['aam_timeout', 'default', 'value' => 4000],
            ['aam_inter_digit_timeout', 'default', 'value' => 3000],
            [
                'aam_timeout',
                'compare',
                'compareAttribute' => 'aam_inter_digit_timeout',
                'operator' => '>',
                'type' => 'number',
            ],
            [['aam_timeout', 'aam_inter_digit_timeout', 'aam_max_failures', 'aam_max_timeout'], 'integer'],
            [['aam_timeout', 'aam_inter_digit_timeout', 'aam_max_failures', 'aam_max_timeout'], 'number', 'min' => 1],
            [['aam_timeout', 'aam_inter_digit_timeout', 'aam_max_failures', 'aam_max_timeout'], 'match', 'pattern' => '/^[0-9.]+$/'],
            [['aam_language'], 'string', 'max' => 30],
            [['aam_digit_len'], 'number'],
            [['aam_digit_len'], 'match', 'pattern' => '/^(1|[1-9]|1[0-9]|20)$/', 'message' => AutoAttendantModule::t('autoattendant', 'digits_length_have_2_number')],
            [['aam_name'], 'subMenuNameUnique', 'on' => 'subMenuCreate'],
            [['aam_extension'], 'number'],
            [['aam_extension'], 'string', 'min' => 3, 'max' => 20],
            [['aam_extension'], 'checkUnique'],
            [
                [
                    'aam_id',
                    'aam_name',
                    'aam_extension',
                    'aam_greet_long',
                    'aam_greet_short',
                    'aam_invalid_sound',
                    'aam_exit_sound',
                    'aam_failure_prompt',
                    'aam_timeout_prompt',
                    'aam_timeout',
                    'aam_inter_digit_timeout',
                    'sampleValues',
                    'importFileUpload',
                    'aam_language',
                    'aam_direct_ext_call',
                    'aam_transfer_on_failure',
                    'aam_deny_direct_dial_extension',
                    'aam_mapped_id',
                    'aam_level',
                    'aam_status',
                    'aam_max_failures',
                    'aam_max_timeout',
                    'aam_direct_dial',
                    'aam_transfer_on_failure',
                    'aam_digit_len',
                    'aam_transfer_extension_type',
                    'aam_transfer_extension'
                ],
                'safe',
            ],

            [
                'aam_transfer_extension',
                'required',
                'when' => function ($model) {
                    return ($model->aam_transfer_extension_type == 'INTERNAL' || $model->aam_transfer_extension_type == 'EXTERNAL');
                }, 'whenClient' => "function (attribute, value) {
                        return ($('#autoattendantmaster-aam_transfer_extension_type').val() == 'INTERNAL' || $('#autoattendantmaster-aam_transfer_extension_type').val() == 'EXTERNAL');
                    }", 'message' => AutoAttendantModule::t('autoattendant', 'Transfer extension cannot be blank.')
            ],

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'aam_id' => AutoAttendantModule::t('autoattendant', 'aam_id'),
            'aam_name' => AutoAttendantModule::t('autoattendant', 'aam_name'),
            'aam_extension' => AutoAttendantModule::t('autoattendant', 'aam_extension'),
            'aam_greet_long' => AutoAttendantModule::t('autoattendant', 'aam_greet_long'),
            'aam_greet_short' => AutoAttendantModule::t('autoattendant', 'aam_greet_short'),
            'aam_invalid_sound' => AutoAttendantModule::t('autoattendant', 'aam_invalid_sound'),
            'aam_exit_sound' => AutoAttendantModule::t('autoattendant', 'aam_exit_sound'),
            'aam_failure_prompt' => AutoAttendantModule::t('autoattendant', 'aam_failure_prompt'),
            'aam_timeout_prompt' => AutoAttendantModule::t('autoattendant', 'aam_timeout_prompt'),
            'aam_timeout' => AutoAttendantModule::t('autoattendant', 'aam_timeout'),
            'aam_inter_digit_timeout' => AutoAttendantModule::t('autoattendant', 'aam_inter_digit_timeout'),
            'aam_language' => AutoAttendantModule::t('autoattendant', 'language'),
            'aam_direct_ext_call' => AutoAttendantModule::t('autoattendant', 'aam_direct_ext_call'),
            'aam_transfer_on_failure' => AutoAttendantModule::t('autoattendant', 'aam_transfer_on_failure'),
            'aam_deny_direct_dial_extension' => AutoAttendantModule::t('autoattendant',
                'aam_deny_direct_dial_extension'),
            'aam_mapped_id' => AutoAttendantModule::t('autoattendant', 'mapped_id'),
            'aam_level' => AutoAttendantModule::t('autoattendant', 'level'),
            'aam_status' => AutoAttendantModule::t('autoattendant', 'aam_status'),
            'aam_glob_addbook_mem' => AutoAttendantModule::t('autoattendant', 'aam_global_address_book'),
            'aam_outbound_dialplan' => AutoAttendantModule::t('autoattendant', 'aam_outbound_dialplan'),
            'aam_max_failures' => AutoAttendantModule::t('autoattendant', 'aam_max_failures'),
            'aam_max_timeout' => AutoAttendantModule::t('autoattendant', 'aam_max_timeout'),

            'aam_direct_dial' => AutoAttendantModule::t('autoattendant', 'aam_direct_dial'),
            'aam_digit_len' => AutoAttendantModule::t('autoattendant', 'aam_digit_len'),


        ];
    }


    /**
     * Unique Auto Attendant name with tenant while creating.
     *
     * @param $attribute
     *
     * @return bool
     */
   /* public function nameUniqueWithTenantCreate($attribute)
    {*/
        /** @var AutoAttendantMaster $autoAttendantNameCount */
        /*$autoAttendantNameCount = (int)AutoAttendantMaster::find()
            ->where(['aam_name' => $this->aam_name])
            ->andWhere(['user_id' => Yii::$app->user->identity->user_id])
            ->andWhere(['<>', 'aam_status', 'X'])
            ->count();

        if ($autoAttendantNameCount > 0) {
            $this->addError($attribute,
                AutoAttendantModule::t('autoattendant', 'name_taken')
            );
        }

        return true;*/
   /* }*/

    /**
     * Unique AutoAttendant name with tenant while updating.
     *
     * @param $attribute
     *
     * @return bool
     */
   /* public function nameUniqueWithTenantUpdate($attribute)
    {*/
        /** @var AutoAttendantMaster $autoAttendantNameCount */
        /* $autoAttendantNameCount = (int)AutoAttendantMaster::find()
             ->where(['aam_name' => $this->aam_name])
             ->andWhere(['user_id' => Yii::$app->user->identity->user_id])
             ->andWhere(['<>', 'aam_id', $this->aam_id,])
             ->andWhere(['<>', 'aam_status', 'X'])
             ->count();

         if ($autoAttendantNameCount > 0) {
             $this->addError($attribute,
                 AutoAttendantModule::t('autoattendant', 'name_taken')
             );
         }

         return true;*/
   /* }*/

    /**
     * Unique AutoAttendant name with tenant while updating.
     *
     * @param $attribute
     *
     * @return bool
     */
    public function subMenuNameUnique($attribute)
    {
        /** @var AutoAttendantMaster $autoAttendantNameCount */
        $autoAttendantNameCount = (int)AutoAttendantMaster::find()
            ->where(['aam_name' => $this->aam_name])
            ->andWhere(['<>', 'aam_id', $this->aam_id])
            ->andWhere(['<>', 'aam_status', 'X'])
            ->count();

        if ($autoAttendantNameCount > 0) {
            $this->addError($attribute,
                AutoAttendantModule::t('autoattendant', 'name_taken')
            );
        }

        return TRUE;
    }

    /**
     * @throws Exception
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function deletePersonalAutoAttendant()
    {
        AutoAttendantDetail::deleteAll(['aam_id' => $this->aam_id]);
        $this->delete();
    }

    /**
     * @param $attribute
     */
    public function checkUnique($attribute)
    {
        $result = Yii::$app->commonHelper->checkUniqueExtension($this->aam_extension, $this->getOldAttribute('aam_extension'));

        if ($result) {
            $this->addError($attribute, $result);
        }
    }

    public function getPromt()
    {
        return $this->hasOne(AudioManagement::className(), ['af_id' => 'aam_greet_long']);
    }


}
