<?php

namespace app\modules\ecosmob\ringgroup\models;

use app\modules\ecosmob\autoattendant\models\AutoAttendantMaster;
use app\modules\ecosmob\conference\models\ConferenceMaster;
use app\modules\ecosmob\extension\models\Extension;
use app\modules\ecosmob\playback\models\Playback;
use app\modules\ecosmob\queue\models\QueueMaster;
use app\modules\ecosmob\ringgroup\RingGroupModule;
use app\modules\ecosmob\services\models\Services;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "ct_ring_group".
 *
 * @property int $rg_id
 * @property string $rg_name
 * @property string $rg_extension
 * @property string $rg_type
 * @property int $rg_language
 * @property string $rg_info_prompt
 * @property int $rg_timeout_sec
 * @property string $rg_is_recording
 * @property string $rg_is_failed
 * @property int $rg_failed_service_id
 * @property int $rg_failed_action
 * @property string $rg_call_feature
 * @property string $updated_date
 * @property string $created_date
 * @property int $rg_status
 * @property int $rg_call_confirm
 * @property int $rg_callerid_name
 * @property int $rg_moh
 */
class RingGroup extends ActiveRecord
{

    /*
     * {@inheritdoc}
     */
    public $etype, $internal_extention, $external_extention, $extension_list, $number;

    public static function tableName()
    {
        return 'ct_ring_group';
    }

    /**
     * @return array
     */
    public static function getRinggroupList()
    {
        if ($dataModel = RingGroup::find()->all()) {
            return ArrayHelper::map($dataModel, 'rg_extension', 'rg_name');
        } else {
            return [];
        }
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [
                [
                    'rg_name',
                    'rg_extension',
                    'rg_type',
                    'rg_language',
                    'rg_info_prompt',
                    'rg_timeout_sec',
                    'rg_moh',
                ],
                'required',
            ],

            [
                [
                    'etype',
                    'internal_extention',
                    'external_extention',
                    'extension_list',
                    'rg_is_recording',
                    'rg_is_failed',
                    'rg_call_feature',
                    'rg_status',
                    'rg_extension',
//                    'rg_name',
                    'rg_callerid_name',
                    'rg_call_confirm',
                ],
                'safe',
            ],


            [['rg_type', 'rg_language', 'etype'], 'string'],

            [
                [
                    'external_extention',
                    'rg_timeout_sec',
                    'rg_extension',
                    'rg_failed_service_id',
                    'rg_failed_action',
                    'rg_callerid_name',
                    'rg_call_confirm',
                ],
                'integer',
            ],
            [['rg_name'], 'unique'],
            [['rg_extension'], 'checkUnique'],
            [['rg_extension'], 'integer'],
            [['rg_extension'], 'string', 'min' => 3, 'max' => 20],
            [['rg_timeout_sec'], 'integer', 'min' => 0, 'max' => 99],
            [['external_extention'], 'number', 'integerOnly' => 'true'],
            [['external_extention'], 'string', 'min' => 1, 'max' => 15],
            [
                ['rg_failed_action', 'rg_failed_service_id'],
                'required',
                'when' => function ($model) {
                    return $model->rg_is_failed == 1;
                },
                'whenClient' => "function (attribute, value) {
                            return $('#failshow:checked').val() == '1';
                }",
            ],

            /*['rg_extension','unique'],*/
            [['rg_name'], 'string', 'min' => 3, 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'rg_id' => RingGroupModule::t('rg', 'id'),
            'rg_name' => RingGroupModule::t('rg', 'name'),
            'rg_extension' => RingGroupModule::t('rg', 'extension'),
            'number' => RingGroupModule::t('rg', 'extension'),
            'rg_type' => RingGroupModule::t('rg', 'type'),
            'rg_language' => RingGroupModule::t('rg', 'language'),
            'rg_info_prompt' => RingGroupModule::t('rg', 'info_prompt'),
            'rg_timeout_sec' => RingGroupModule::t('rg', 'timeout_sec'),
            'rg_is_recording' => RingGroupModule::t('rg', 'recording'),
            'rg_is_failed' => RingGroupModule::t('rg', 'failed'),
            'rg_failed_service_id' => RingGroupModule::t('rg', 'failed_service_id'),
            'rg_failed_action' => RingGroupModule::t('rg', 'failed_action_id'),
            'rg_call_feature' => RingGroupModule::t('rg', 'call_feature'),
            'updated_date' => RingGroupModule::t('rg', 'updated_date'),
            'created_date' => RingGroupModule::t('rg', 'created_date'),
            'rg_status' => RingGroupModule::t('rg', 'status'),
            'rg_callerid_name' => RingGroupModule::t('rg', 'callerid_name'),
            'rg_call_confirm' => RingGroupModule::t('rg', 'rg_call_confirm'),
            'external_extention' => RingGroupModule::t('rg', 'external_extention'),
            'internal_extention' => RingGroupModule::t('rg', 'internal_extention'),
            'rg_moh' => RingGroupModule::t('rg', 'rg_moh'),
        ];
    }

    public function checkUnique($attribute)
    {
        $result = Yii::$app->commonHelper->checkUniqueExtension($this->rg_extension, $this->getOldAttribute('rg_extension'));

        if ($result) {
            $this->addError($attribute, $result);
        }
    }

    /**
     * @param $attribute
     */
/*    public function checkUnique($attribute)
    {
        $data = $this::find()->where(['rg_extension' => $this->rg_extension])->all();
        if(!empty($combinedExtensions))
        {
            $this->addError($attribute, 'Extension number is already exists ' . $this->rg_extension);
            return false;
        }*/

        /*$combinedExtensions = CombinedExtensions::find()->where(['extension' => $this->rg_extension])->all();
        if(!empty($combinedExtensions))
        {

            $this->addError($attribute, 'Extension number is already used in ' . $combinedExtensions[0]->type);
            return false;
        } */

//         $result = Yii::$app->commonHelper->checkUniqueExtension($this->rg_extension, $this->getOldAttribute('rg_extension'));
//
//         if ($result) {
//             $this->addError($attribute, $result);
//         }
   /* }*/


    /**
     * @param $action_id
     * @param $action_value
     * @return Services|string
     */
    public function getFailedActionValue($action_id, $action_value)
    {

        $data = '';
        /** @var Services $data */
        $services = Services::find()->where(['ser_id' => $action_id])->asArray()->one();

        if (sizeof($services)) {
            $ser_name = $services['ser_name'];

            if ($ser_name == 'EXTENSION') {
                $data = Extension::findOne($action_value)->em_extension_number;

            } else if ($ser_name == 'IVR' || $ser_name == 'AUDIO TEXT') {
                $data = AutoAttendantMaster::findOne($action_value)->aam_name;
            } else if ($ser_name == 'QUEUE') {
                $data = QueueMaster::findOne($action_value)->qm_name;
            } else if ($ser_name == 'VOICEMAIL') {
                $data = '';
            } else if ($ser_name == 'RING GROUP') {
                $data = RingGroup::findOne($action_value)->rg_name;
            } else if ($ser_name == 'EXTERNAL') {
                $data = '';
            } else if ($ser_name == 'CONFERENCE') {
                $data = ConferenceMaster::findOne($action_value)->cm_name;
            } else if ($ser_name == 'PLAYBACK') {
                $data = Playback::findOne($action_value)->pb_name;
            }
        }
        return $data;
    }

}
