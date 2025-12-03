<?php

namespace app\modules\ecosmob\didmanagement\models;

use app\models\DidTimeBased;
use app\modules\ecosmob\autoattendant\models\AutoAttendantMaster;
use app\modules\ecosmob\campaign\models\Campaign;
use app\modules\ecosmob\conference\models\ConferenceMaster;
use app\modules\ecosmob\didmanagement\DidManagementModule;
use app\modules\ecosmob\extension\models\Extension;
use app\modules\ecosmob\fax\models\Fax;
use app\modules\ecosmob\queue\models\QueueMaster;
use app\modules\ecosmob\ringgroup\models\RingGroup;
use app\modules\ecosmob\services\models\Services;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "ct_did_master".
 *
 * @property int $did_id
 * @property string $did_number
 * @property string $did_description
 * @property string $did_status
 * @property string $action_value
 * @property int $action_id
 * @property string $created_date
 * @property string $updated_date
 * @property string $fax
 * @property string $is_time_based
 */
class DidManagement extends ActiveRecord
{

    /**
     * @var
     */
    public $importFileUpload, $type, $did_range_from, $did_range_to, $holiday, $days;

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
                'did_number' => [
                    'displayName' => 'Number',
                    'sample' => '1212121212',
                ],
                'did_description' => [
                    'displayName' => 'Description',
                    'sample' => 'Sample DID',
                ],
            ],

        ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ct_did_master';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            //[['action_id'], 'required'],
            [['did_number'], 'unique'],
            [['did_number', 'did_range_from', 'did_range_to'], 'integer'],
            [['did_number', 'did_range_from', 'did_range_to'], 'string', 'min' => 3, 'max' => 20],
            [['action_value'], 'string', 'max' => 30],
            [['did_description'], 'string', 'max' => 100],
            [
                ['importFileUpload'],
                'required',
                'on' => 'import',
            ],
            [
                ['type'],
                'required',
                'on' => 'create',
            ],
            [
                ['did_number'],
                'required',
                'on' => 'update',
            ],
            [
                ['did_range_from', 'did_range_to'],
                'required',
                'when' => function ($model) {
                    return $model->type == 'range';
                },
                'whenClient' => "function (attribute, value) {
                return ($('#didmanagement-type').val()=='range');
            }",
                'on' => 'create',
            ],
            [
                ['did_number'],
                'required',
                'when' => function ($model) {
                    return $model->type == 'number';
                },
                'whenClient' => "function (attribute, value) {
                return ($('#didmanagement-type').val()=='number');
            }",
                'on' => 'create',
            ],
            [['did_range_to'], 'compare', 'compareAttribute' => 'did_range_from', 'operator' => '>', 'on' => 'create'],
            [
                ['did_range_to'],
                'checkDiff',
                'when' => function ($model) {
                    return $model->type == 'range';
                },
                'whenClient' => "function (attribute, value) {
                        return ($('#didmanagement-type').val()=='range');
            }",
                'on' => 'create',
            ],
            [
                ['importFileUpload'],
                'file',
                'extensions' => 'csv',
                'checkExtensionByMimeType' => FALSE,
                'maxSize' => 10485760,
                'tooBig' => 'Limit is 10MB',
                'on' => 'import',
            ],
            [['action_id', 'action_value', 'sampleValues', 'importFileUpload', 'type', 'did_range_from', 'did_range_to', 'fax', 'is_time_based', 'holiday', 'from_service', 'days'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'did_id' => DidManagementModule::t('did', 'id'),
            'did_number' => DidManagementModule::t('did', 'number'),
            'did_description' => DidManagementModule::t('did', 'description'),
            'did_status' => DidManagementModule::t('did', 'status'),
            'created_date' => DidManagementModule::t('did', 'c_date'),
            'updated_date' => DidManagementModule::t('did', 'u_date'),
            'action_id' => DidManagementModule::t('did', 'action_id'),
            'action_value' => DidManagementModule::t('did', 'action_value'),
            'importFileUpload' => DidManagementModule::t('did', 'importFileUpload'),
            'type' => DidManagementModule::t('did', 'type'),
            'did_range_from' => DidManagementModule::t('did', 'from'),
            'did_range_to' => DidManagementModule::t('did', 'to'),
            'fax' => DidManagementModule::t('did', 'fax'),
            'is_time_based' => DidManagementModule::t('did', 'is_time_based'),
        ];
    }

    public function checkDiff($attribute)
    {
        $diff = preg_replace('/\D+/', '', $this->did_range_to) - preg_replace('/\D+/', '', $this->did_range_from);
        if ($diff >= 100) {
            $this->addError($attribute, DidManagementModule::t('did', 'invalid_length'));
        }
    }

    public function getTimebased()
    {
        return $this->hasMany(DidTimeBased::className(), ['did_id' => 'did_id'])->asArray();
    }

    public  function getDidAction() {
        return $this->hasOne(Services::className(), ['ser_id' => 'action_id']);
    }

    public static function getDidActionValue($action, $id){

        if(!empty($action) && !empty($id)){
            if($action == 'EXTENSION' || $action == 'VOICEMAIL'){
                $destination = Extension::find()->select(['em_extension_name as name'])->where(['em_id' => $id])->asArray()->one();
            }elseif ($action == 'QUEUE'){
                $destination = QueueMaster::find()->select([new \yii\db\Expression("SUBSTRING_INDEX(qm_name, '_', 1) AS name")])->where(['qm_id' => $id])->asArray()->one();
            }elseif ($action == 'IVR'){
                $destination = AutoAttendantMaster::find()->select(['aam_name AS name'])->where(['aam_id' => $id])->asArray()->one();
            }elseif ($action == 'RING GROUP'){
                $destination = RingGroup::find()->select(['rg_name AS name'])->where(['rg_id' => $id])->asArray()->one();
            } else if ($action == 'CAMPAIGN') {
                $destination = Campaign::find()->select(['cmp_name AS name'])->where(['cmp_id' => $id])->asArray()->one();
            }else if ($action == 'CONFERENCE') {
                $destination = ConferenceMaster::find()->select([new \yii\db\Expression("SUBSTRING_INDEX(cm_name, '_', 1) AS name")])->where(['cm_id' => $id])->asArray()->one();
            }else if ($action == 'FAX') {
                $destination = Fax::find()->select(["fax_name AS name"])->where(['id' => $id])->asArray()->one();
            }else{
                $destination = '';
            }

            if(!empty($destination)){
                return $destination['name'];
            }
        }else{
            return '';
        }
    }

    public function getDid($ext){
        $selectedDid = DidManagement::find()->where(['action_id' => '1', 'action_value' => $ext])->all();

        $did = ArrayHelper::map(DidManagement::find()->select(['did_id', 'did_number'])->where(['!=', 'action_id', '1'])
            ->orWhere(['AND', ['action_id' => '1'], ['action_value' => $ext]])
            ->asArray()->all(), 'did_id', 'did_number');
    }
}
