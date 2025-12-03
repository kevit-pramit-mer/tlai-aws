<?php

namespace app\modules\ecosmob\conference\models;

use app\models\CommonModel;
use app\modules\ecosmob\conference\ConferenceModule;

/**
 * This is the model class for table "tbl_conference_controls".
 *
 * @property integer $cc_id
 * @property string $cc_conf_group
 * @property string $cc_action
 * @property string $cc_digits
 * @property integer $cm_id
 */
class ConferenceControls extends CommonModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ct_conference_controls';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['cc_conf_group', 'cc_action', 'cc_digits', 'cm_id'], 'required'],
            [['cm_id'], 'integer'],
            [['cc_conf_group', 'cc_action'], 'string', 'max' => 64],
            [['cc_digits'], 'string', 'max' => 16],
            [['cc_digits'], 'match', 'pattern' => '/^([0-9]{1}|\#|\*)$/'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'cc_id' => ConferenceModule::t('conference', 'cc_id'),
            'cc_conf_group' => ConferenceModule::t('conference', 'cc_conf_group'),
            'cc_action' => ConferenceModule::t('conference', 'cc_action'),
            'cc_digits' => ConferenceModule::t('conference', 'cc_digits'),
            'cm_id' => ConferenceModule::t('conference', 'cm_id'),
        ];
    }
}
