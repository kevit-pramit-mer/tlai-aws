<?php

namespace app\modules\ecosmob\conference\models;

use app\models\CommonModel;
use app\models\CommonModelValidationTrait;
use app\modules\ecosmob\audiomanagement\models\AudioManagement;
use app\modules\ecosmob\conference\ConferenceModule;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "tbl_conference_master".
 *
 * @property integer $cm_id
 * @property string $cm_name
 * @property string $cm_status
 * @property string $cm_part_code
 * @property string $cm_mod_code
 * @property string $cm_quick_start
 * @property string $cm_entry_tone
 * @property string $cm_language
 * @property string $cm_exit_tone
 * @property integer $cm_max_participant
 * @property string $cm_moh
 * @property string $cm_extension
 */
class ConferenceMaster extends CommonModel
{

    use CommonModelValidationTrait;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ct_conference_master';
    }

    /**
     * @return static[]
     */
    public static function getConferenceData()
    {
        return ConferenceMaster::findAll(['cm_status' => '1']);
    }

    /**
     * @param $id
     */
    public static function deleteConferenceData($id)
    {
        $conference = static::findAll(['user_id' => $id]);
        foreach ($conference as $value) {
            $value->detachBehavior('audit_trail_log');
            ConferenceControls::deleteAll(['cm_id' => $value->cm_id]);
            $value->delete();
        }
    }

    /**
     * @return array
     */
    public static function getAllConference()
    {
        if ($dataModel = static::find()->select(['cm_extension', new \yii\db\Expression("SUBSTRING_INDEX(cm_name, '_', 1) AS cm_name")])->where(['cm_status' => '1'])->all()) {
            return ArrayHelper::map($dataModel, 'cm_extension', 'cm_name');
        } else {
            return [];
        }
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cm_name', 'cm_extension', 'cm_language', 'cm_max_participant'], 'required'],

            [
                ['cm_part_code'],
                'required',
                'when' => function ($model) {
                    return ($model->cm_mod_code != '');
                }, 'whenClient' => "function (attribute, value) {
                        return ($('#conferencemaster-cm_mod_code').val() != '');
                    }"
            ],

            [
                ['cm_mod_code'],
                'required',
                'when' => function ($model) {
                    return ($model->cm_part_code != '');
                }, 'whenClient' => "function (attribute, value) {
                        return ($('#conferencemaster-cm_part_code').val() != '');
                    }"
            ],

            [['cm_quick_start', 'cm_entry_tone', 'cm_exit_tone'], 'string'],
            [['cm_name'], 'string', 'max' => 32],
            [['cm_name'], 'match', 'pattern' => '/^[0-9a-zA-Z\s]*$/', 'message' => ConferenceModule::t('conference', 'cm_name_validation')],
            [['cm_part_code', 'cm_mod_code', 'cm_max_participant'], 'string', 'max' => 4],
            [['cm_extension'], 'unique'],
            [['cm_extension'], 'checkUnique'],
            [['cm_extension'], 'integer'],
            [['cm_extension'], 'string', 'min' => 3, 'max' => 20],
            [
                ['cm_part_code', 'cm_mod_code', 'cm_max_participant'],
                'match',
                'pattern' => '/^[0-9]*$/',
                'message' => ConferenceModule::t('conference', 'cm_part_code') . ' ' . ConferenceModule::t('conference', 'must_be_a_number'),
            ],
            [['cm_part_code', 'cm_mod_code', 'cm_max_participant'], 'integer'],
            [['cm_max_participant'], 'integer', 'min' => 2, 'max' => 9999],
            [['cm_mod_code'], 'isSame'],
            [['cm_status', 'cm_moh'], 'safe'],

            // Custom Validations
            [['cm_name'], 'nameUniqueWithTenantCreate', 'on' => 'create'],
            [['cm_name'], 'nameUniqueWithTenantUpdate', 'on' => 'update'],
            //            [['cm_moh'], 'checkMoh'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cm_id' => ConferenceModule::t('conference', 'cm_id'),
            'cm_name' => ConferenceModule::t('conference', 'cm_name'),
            'cm_status' => ConferenceModule::t('conference', 'cm_status'),
            'cm_part_code' => ConferenceModule::t('conference', 'cm_part_code'),
            'cm_mod_code' => ConferenceModule::t('conference', 'cm_mod_code'),
            'cm_quick_start' => ConferenceModule::t('conference', 'cm_quick_start'),
            'cm_entry_tone' => ConferenceModule::t('conference', 'cm_entry_tone'),
            'cm_exit_tone' => ConferenceModule::t('conference', 'cm_exit_tone'),
            'cm_max_participant' => ConferenceModule::t('conference', 'cm_max_participant'),
            'cm_moh' => ConferenceModule::t('conference', 'cm_moh'),
            'cm_extension' => ConferenceModule::t('conference', 'cm_extension'),
            'cm_language' => ConferenceModule::t('conference', 'cm_language'),
        ];
    }

    /**
     * @param $attribute
     */
    public function checkMoh($attribute)
    {
        $audios = AudioManagement::find()->select('audiofile_name')->asArray()->all();

        $new_arr = array_column($audios, 'audiofile_name');

        if (!in_array($this->cm_moh, $new_arr) && $this->cm_moh != -1) {
            $this->addError($attribute, ConferenceModule::t('conference', 'please_select_moh_file'));
        }
    }

    /**
     * Unique Ring group name with tenant while creating.
     *
     * @param $attribute
     *
     * @return bool
     */
    public function nameUniqueWithTenantCreate($attribute)
    {
        $ringNameCount = (int)ConferenceMaster::find()
            ->where(['cm_name' => $this->cm_name])
            ->count();
        if ($ringNameCount > 0) {
            $this->addError($attribute, ConferenceModule::t('conference', 'conference_name_taken'));
        }

        return TRUE;
    }

    /**
     * @param $attribute
     *
     * @return bool
     */
    public function nameUniqueWithTenantUpdate($attribute)
    {
        $ringNameCount = (int)ConferenceMaster::find()
            ->where(['cm_name' => $this->cm_name])
            ->andWhere([
                '<>',
                'cm_id',
                $this->cm_id,
            ])
            ->count();

        if ($ringNameCount > 0) {
            $this->addError($attribute, ConferenceModule::t('conference', 'conference_name_taken'));
        }

        return TRUE;
    }

    /**
     * Checks Value of Participant Code and Moderator code.
     * If it is same then it will return error.
     *
     * @return bool
     */
    public function isSame()
    {
        if ($this->cm_part_code == $this->cm_mod_code) {
            $this->addError('cm_mod_code', ConferenceModule::t('conference', 'code_should_be_different'));

            return FALSE;
        }

        return TRUE;
    }

    /**
     * Concat Conference Number and Name
     *
     * @return string
     */
    public function getNameNumber()
    {
        return $this->cm_name;
    }

    /**
     * @param $attribute
     */
    public function checkUnique($attribute)
    {
        $result = Yii::$app->commonHelper->checkUniqueExtension($this->cm_extension, $this->getOldAttribute('cm_extension'));

        if ($result) {
            $this->addError($attribute, $result);
        }
    }
    public static function getConferenceName($conferenceName)
    {
        $conferenceName = explode('_',$conferenceName);
        if(is_array($conferenceName)){
            return $conferenceName[0];
        }
        return $conferenceName;
    }
}
