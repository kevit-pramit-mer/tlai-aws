<?php

namespace app\modules\ecosmob\parkinglot\models;

use app\modules\ecosmob\autoattendant\models\AutoAttendantMaster;
use app\modules\ecosmob\extension\models\Extension;
use app\modules\ecosmob\group\models\Group;
use app\modules\ecosmob\parkinglot\ParkingLotModule;
use app\modules\ecosmob\queue\models\QueueMaster;
use app\modules\ecosmob\ringgroup\models\RingGroup;
use app\modules\ecosmob\services\models\Services;
use Yii;
use yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "parking_lot".
 *
 * @property int $id
 * @property string $name
 * @property string $park_ext
 * @property int $slot_qty
 * @property string $park_pos_start
 * @property string $park_pos_end
 * @property int $grp_id
 * @property int $parking_time Amount of time call should remain on slot - seconds
 * @property string $park_moh Music on Hold IVR Prompt
 * @property int $return_to_origin Return to originator (enable/disable). Determines further action if no one pickup calls within timeout 
 * @property int $call_back_ring_time Ring time for return call to originator - seconds
 * @property string $destination_type
 * @property int $destination_id
 * @property string $status
 * @property string $created_at
 * @property string $updated_at
 */
class ParkingLot extends \yii\db\ActiveRecord
{
    public $des_id_select, $des_id_input;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'parking_lot';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'status', 'park_ext', 'grp_id', 'parking_time'], 'required'],
            [['park_ext', 'grp_id', 'return_to_origin'], 'integer'],

            [['name'], 'string', 'min' => 2, 'max' => 30, 'message'=> ParkingLotModule::t('parkinglot', 'name_max_validation'), 'tooShort' => ParkingLotModule::t('parkinglot', 'name_min_validation')],
            [['name'], 'unique'],
            [['name'], 'match', 'pattern' => '/^[0-9a-zA-Z\s]*$/', 'message' => ParkingLotModule::t('parkinglot', 'name_validation')],

            [['park_ext'], 'unique', 'message' => 'Extension Number {value} already exists.'],
            [['park_ext'], 'checkUnique'],
            [['park_ext'], 'string', 'min' => 3, 'max' => 20, 'message'=> ParkingLotModule::t('parkinglot', 'park_ext_max_validation'), 'tooShort' => ParkingLotModule::t('parkinglot', 'park_ext_min_validation')],

            [['slot_qty'], 'integer', 'min' => 0, 'max' => 100],

            [['parking_time'], 'integer', 'min' => 1, 'max' => 180, 'tooBig' => ParkingLotModule::t('parkinglot', 'parking_time_max_validation'), 'tooSmall' => ParkingLotModule::t('parkinglot', 'parking_time_min_validation')],

            [['call_back_ring_time'], 'required', 'when' => function($model){
                return ($model->return_to_origin == 1);
            },
                'whenClient' => "function (attribute, value) {
                  return ($('#return-to-origin').is(':checked'));
              }", 'enableClientValidation' => true],
            [['call_back_ring_time'], 'integer', 'min' => 1, 'max' => 60, 'tooBig' => ParkingLotModule::t('parkinglot', 'call_back_ring_time_max_validation'), 'tooSmall' => ParkingLotModule::t('parkinglot', 'call_back_ring_time_min_validation')],

            [['des_id_select'], 'required', 'when' => function($model){
                return (!empty($model->destination_type) && $model->destination_type != 6);
            },
                'whenClient' => "function (attribute, value) {
                  return ($('#parkinglot-destination_type').val() != '' && $('#parkinglot-destination_type').val() != 6);
              }", 'enableClientValidation' => true],

            [['des_id_input'], 'required', 'when' => function($model){
                return (!empty($model->destination_type) && $model->destination_type == 6);
            },'whenClient' => "function (attribute, value) {
                  return ($('#parkinglot-destination_type').val() != '' && $('#parkinglot-destination_type').val() == 6);
              }", 'enableClientValidation' => true],

            [['des_id_select', 'des_id_input'], 'string', 'max' => 50],

            [['grp_id', 'park_moh', 'park_pos_start', 'park_pos_end', 'destination_id', 'created_at', 'updated_at', 'destination_type', 'des_id_select', 'des_type_input'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => ParkingLotModule::t('parkinglot', 'name'),
            'status' => ParkingLotModule::t('parkinglot', 'status'),
            'park_ext' => ParkingLotModule::t('parkinglot', 'park_ext'),
            'slot_qty' => ParkingLotModule::t('parkinglot', 'slot_qty'),
            'park_pos_start' => ParkingLotModule::t('parkinglot', 'park_pos_start'),
            'park_pos_end' => 'Park Pos End',
            'grp_id' => ParkingLotModule::t('parkinglot', 'grp_id'),
            'parking_time' => ParkingLotModule::t('parkinglot', 'parking_time'),
            'park_moh' => ParkingLotModule::t('parkinglot', 'park_moh'),
            'return_to_origin' => ParkingLotModule::t('parkinglot', 'return_to_origin'),
            'call_back_ring_time' => ParkingLotModule::t('parkinglot', 'call_back_ring_time'),
            'destination_type' => ParkingLotModule::t('parkinglot', 'destination_type'),
            'destination_id' => ParkingLotModule::t('parkinglot', 'destination_id'),
            'des_id_select' => ParkingLotModule::t('parkinglot', 'destination_id'),
            'des_id_input' => ParkingLotModule::t('parkinglot', 'destination_id'),
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getGroupList()
    {
        $groupModel = Group::find()->asArray()->orderBy(['grp_name' => SORT_ASC])->all();
        return ArrayHelper::map($groupModel, 'grp_id', 'grp_name');
    }

    /**
     * @param $attribute
     */
    public function checkUnique($attribute)
    {
        $result = Yii::$app->commonHelper->checkUniqueExtension($this->park_ext, $this->getOldAttribute('park_ext'), 'parking');

        if ($result) {
            $this->addError($attribute, $result);
        }else{
            $parking = ParkingLot::find()->orWhere(['park_ext' => $this->park_ext])->orWhere(['AND', ['<=', 'park_pos_start', $this->park_ext], ['>=', 'park_pos_end', $this->park_ext]]);
            if(!empty($this->id)){
                $parking = $parking->andWhere(['!=', 'id', $this->id]);
            }
            $parking = $parking->asArray()->all();

            if(!empty($parking)){
                $this->addError($attribute, ParkingLotModule::t('parkinglot', 'extension_number_is_already_used', ['value' => $this->park_ext]));
            }
        }
    }

    public static function getServices() {
        return ArrayHelper::map( Services::find()->andWhere(['NOT IN','ser_name', ['CONFERENCE', 'FAX', 'CAMPAIGN']])->all(), 'ser_id', 'ser_name' );
    }

    /**
     * @return ActiveQuery
     */
    public  function getTimeoutAction() {
        return $this->hasOne(Services::className(), ['ser_id' => 'destination_type']);
    }

    public static function getTimeoutDestination($action, $id){

        if(!empty($action) && !empty($id)){
            if($action == 'EXTENSION' || $action == 'VOICEMAIL'){
                $destination = Extension::find()->select(['em_extension_name as name'])->where(['em_id' => $id])->asArray()->one();
            }elseif ($action == 'QUEUE'){
                $destination = QueueMaster::find()->select([new \yii\db\Expression("SUBSTRING_INDEX(qm_name, '_', 1) AS name")])->where(['qm_id' => $id])->asArray()->one();

            }elseif ($action == 'IVR'){
                $destination = AutoAttendantMaster::find()->select(['aam_name AS name'])->where(['aam_id' => $id])->asArray()->one();
            }elseif ($action == 'RING GROUP'){
                $destination = RingGroup::find()->select(['rg_name AS name'])->where(['rg_id' => $id])->asArray()->one();
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
}
