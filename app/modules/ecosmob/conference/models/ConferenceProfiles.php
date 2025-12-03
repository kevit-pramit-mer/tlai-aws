<?php

namespace app\modules\ecosmob\conference\models;

use app\models\CommonModel;
use app\modules\ecosmob\conference\ConferenceModule;

/**
 * This is the model class for table "tbl_conference_profiles".
 *
 * @property integer $id
 * @property string $profile_name
 * @property string $param_name
 * @property string $param_value
 */
class ConferenceProfiles extends CommonModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ct_conference_profiles';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['profile_name', 'param_name', 'param_value'], 'required'],
            [['profile_name', 'param_name', 'param_value'], 'string', 'max' => 64],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => ConferenceModule::t('conference', 'id'),
            'profile_name' => ConferenceModule::t('conference', 'profile_name'),
            'param_name' => ConferenceModule::t('conference', 'param_name'),
            'param_value' => ConferenceModule::t('conference', 'param_value'),
        ];
    }
}
