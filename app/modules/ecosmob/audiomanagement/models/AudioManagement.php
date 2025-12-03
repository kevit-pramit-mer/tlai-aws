<?php

namespace app\modules\ecosmob\audiomanagement\models;

use app\modules\ecosmob\audiomanagement\AudioManagementModule;
use app\modules\ecosmob\autoattendant\models\AutoAttendantMaster;
use app\modules\ecosmob\conference\models\ConferenceMaster;
use app\modules\ecosmob\extension\models\Extension;
use app\modules\ecosmob\queue\models\QueueMaster;
use app\modules\ecosmob\ringgroup\models\RingGroup;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "ct_audiofile".
 *
 * @property int $af_id
 * @property int $af_extension
 * @property string $af_name
 * @property string $af_type
 * @property string $af_language
 * @property string $af_description
 * @property string $af_file
 */
class AudioManagement extends ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ct_audiofile';
    }

    /**
     * @return array
     */
    public static function getPromptFiles()
    {
        return ArrayHelper::map(static::find()->where(['af_type' => 'Prompt'])->orderBy(['af_id' => SORT_DESC])->all(), 'af_id', 'af_name');
    }

    /**
     * @return array
     */
    public static function getMohFiles()
    {
        return ArrayHelper::map(static::find()->where(['af_type' => 'MOH'])->orderBy(['af_id' => SORT_DESC])->all(), 'af_file', 'af_name');
    }

    /**
     * @return array
     */
    public static function getRecordingFiles()
    {
        return ArrayHelper::map(static::find()->where(['af_type' => 'Recording'])->orderBy(['af_id' => SORT_DESC])->all(), 'af_id', 'af_name');
    }

    /**
     * Get Audio List
     *
     * @return array
     */
    public static function getAudioList()
    {
        if ($audioModel = AudioManagement::find()->all()) {
            return ArrayHelper::map($audioModel, 'af_file', 'af_name');
        } else {
            return [];
        }
    }

    /**
     * @return array
     */
    public static function getAudioExtList()
    {
        if ($audioModel = AudioManagement::find()->all()) {
            return ArrayHelper::map($audioModel, 'af_extension', 'af_name');
        } else {
            return [];
        }
    }

    /**
     * @param $af_id
     * @return mixed|string
     */
    public static function getPromptName($af_id)
    {

        $dataTimeOut = AudioManagement::find()->where(['af_file' => $af_id])->one();

        if ($dataTimeOut) {
            return $dataTimeOut->af_name;
        } else {
            return 'None';
        }
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['af_name', 'af_type', 'af_language', 'af_description'], 'required'],
            [['af_name'], 'unique'],
            [['af_id'], 'safe'],
            [['af_language'], 'string'],
            [['af_name', 'af_type', 'af_extension'], 'string', 'max' => 30],
            [
                ['af_file'],
                'file',
                'extensions' => 'mp3, wav',
                'checkExtensionByMimeType' => FALSE,
                'maxSize' => 10485760,
                'tooBig' => AudioManagementModule::t('am', 'limit_is_10mb'),
            ],
            [['af_description'], 'string', 'max' => 255],
            [
                'af_file',
                'required',
                'on' => 'create',
                'when' => function ($model) {
                    return $model->af_type != 'Recording';
                },
                'whenClient' => "function (attribute, value) {
                  return $('#audiomanagement-af_type').val() != 'Recording';
              }"
            ], [
                'af_extension',
                'required',
                'on' => 'create',
                'when' => function ($model) {
                    return $model->af_type == 'Recording';
                },
                'whenClient' => "function (attribute, value) {
                  return $('#audiomanagement-af_type').val() == 'Recording';
              }"
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'af_id' => AudioManagementModule::t('am', 'id'),
            'af_name' => AudioManagementModule::t('am', 'name'),
            'af_type' => AudioManagementModule::t('am', 'type'),
            'af_language' => AudioManagementModule::t('am', 'language'),
            'af_description' => AudioManagementModule::t('am', 'description'),
            'af_file' => AudioManagementModule::t('am', 'file'),
            'af_extension' => AudioManagementModule::t('am', 'af_extension'),
        ];
    }

    public function canDelete($id)
    {
        $audio = AudioManagement::findOne($id);
        $filename = $audio->af_file;
        $count = 0;

        /** @var Extension $extension */
        $extension = Extension::find()->where(['em_moh' => $filename])->count();

        if ($extension > 0) {
            $count++;
        }

        /** @var ConferenceMaster $conference */
        $conference = ConferenceMaster::find()->where(['cm_moh' => $filename])->count();

        if ($conference > 0) {
            $count++;
        }

        /** @var RingGroup $ringGroup */
        $ringGroup = RingGroup::find()->where(['rg_moh' => $filename])->count();

        if ($ringGroup > 0) {
            $count++;
        }

        /** @var RingGroup $ringGroup */
        $ringGroup = RingGroup::find()->where(['rg_moh' => $filename])->count();

        if ($ringGroup > 0) {
            $count++;
        }

        /** @var AutoAttendantMaster $autoAttendant */
        $autoAttendant = AutoAttendantMaster::find()
            ->where(['OR',
                ['aam_greet_long' => $filename],
                ['aam_greet_short' => $filename],
                ['aam_invalid_sound' => $filename],
                ['aam_exit_sound' => $filename],
                ['aam_failure_prompt' => $filename]
            ])->count();

        if ($autoAttendant > 0) {
            $count++;
        }

        /** @var QueueMaster $queue */
        $queue = QueueMaster::find()
            ->where(['OR',
                ['qm_moh' => $filename],
                ['qm_info_prompt' => $id]
            ])->count();

        if ($queue > 0) {
            $count++;
        }

        if ($count > 0) {
            return FALSE;
        } else {
            return TRUE;
        }
    }
}
