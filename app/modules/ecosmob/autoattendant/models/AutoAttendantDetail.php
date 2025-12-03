<?php

namespace app\modules\ecosmob\autoattendant\models;

use app\models\CommonModel;
use app\modules\ecosmob\autoattendant\AutoAttendantModule;

/**
 * This is the model class for table "auto_attendant_detail".
 *
 * @property integer $aad_id
 * @property integer $aam_id
 * @property string $aad_digit
 * @property string $aad_action
 * @property string $aad_action_desc
 * @property string $aad_param
 */
class AutoAttendantDetail extends CommonModel
{

    /**
     * @var array
     */
    public $actionsAttr
        = [
            'Playfile',
            'External Number',
            'Deposit to user personal voicemail box',
            'Deposit to Common Voicemail box',
            'Sub Menu',
            'Dial by name within User Group',
            'Transfer to extension',
            'Queues',
            'Ring Groups',
            'Copy Sub Menu',
            'Conference',
            'IVR',
            'Voicemail'
        ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'auto_attendant_detail';
    }

    /**
     * Get All SubMenu Data by $id
     *
     * @param $id
     *
     * @return static[]
     */
    public static function getAutoSubMenuData($id)
    {

        return AutoAttendantDetail::findAll(['aam_id' => $id]);
    }

    /**
     * GetSub Menu Entry by its 'aad_param'
     *
     * @param $name
     *
     * @return static
     * @internal param $id
     */
    public static function getSubMenuEntry($name)
    {

        return AutoAttendantDetail::findOne(['aad_param' => $name]);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['aam_id', 'aad_digit', 'aad_action'], 'required'],
            [['aam_id'], 'integer'],
            [['aad_digit'], 'string', 'max' => 25],
            [['aad_action', 'aad_action_desc'], 'string', 'max' => 50],
            [['aad_param'], 'string', 'max' => 255],

            [
                ['aad_param'],
                'required',
                'when' => function ($model) {
                    return in_array($model->aad_action_desc, $this->actionsAttr) ? TRUE : FALSE;
                },
                'message' => $this->aad_action_desc . "'s " . AutoAttendantModule::t('autoattendant', 'associate_value_cannot_be_blank'),
            ],


        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'aad_id' => AutoAttendantModule::t('autoattendant', 'aad_id'),
            'aam_id' => AutoAttendantModule::t('autoattendant', 'aam_id'),
            'aad_digit' => AutoAttendantModule::t('autoattendant', 'aad_digit'),
            'aad_action' => AutoAttendantModule::t('autoattendant', 'aad_action'),
            'aad_action_desc' => AutoAttendantModule::t('autoattendant', 'aad_action_desc'),
            'aad_param' => AutoAttendantModule::t('autoattendant', 'aad_param'),
        ];
    }
}
