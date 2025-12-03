<?php

namespace app\modules\ecosmob\autoattendant\models;

use app\models\CommonModel;
use app\modules\ecosmob\autoattendant\AutoAttendantModule;

/**
 * This is the model class for table "auto_attendant_keys".
 *
 * @property integer $aak_id
 * @property string $aak_key_name
 * @property string $aak_key_code
 * @property string $aak_key_param_tpl
 */
class AutoAttendantKeys extends CommonModel
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'auto_attendant_keys';
    }

    /**
     * @param string $keyName
     *
     * @return string
     */
    public static function getIdByDesc($keyName)
    {
        return AutoAttendantKeys::findOne(['aak_key_name' => $keyName])->aak_id;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['aak_key_name', 'aak_key_code', 'aak_key_param_tpl'], 'required'],
            [['aak_key_name'], 'string', 'max' => 50],
            [['aak_key_code'], 'string', 'max' => 20],
            [['aak_key_param_tpl'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'aak_id' => AutoAttendantModule::t('autoattendant', 'aak_id'),
            'aak_key_name' => AutoAttendantModule::t('autoattendant', 'key_name'),
            'aak_key_code' => AutoAttendantModule::t('autoattendant', 'key_code'),
            'aak_key_param_tpl' => AutoAttendantModule::t('autoattendant', 'key_param_template'),
        ];
    }
}
