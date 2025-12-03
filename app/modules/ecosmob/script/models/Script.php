<?php

namespace app\modules\ecosmob\script\models;

use app\modules\ecosmob\campaign\models\Campaign;
use app\modules\ecosmob\script\ScriptModule;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "ct_script".
 *
 * @property int $scr_id
 * @property string $scr_name
 * @property string $scr_description
 * @property string $scr_status
 */
class Script extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ct_script';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['scr_name', 'scr_description', 'scr_status'], 'required'],
            [['scr_description', 'scr_status'], 'string'],
            [['scr_name'], 'string', 'max' => 50],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'scr_name' => ScriptModule::t('script', 'scr_name'),
            'scr_description' => ScriptModule::t('script', 'scr_description'),
            'scr_status' => ScriptModule::t('script', 'scr_status'),
        ];
    }

    public function canDelete($id)
    {
        /** @var Campaign $campaignCount */
        $campaignCount = Campaign::find()->where(['cmp_script' => $id])->count();

        if ($campaignCount == 0) {
            return FALSE;
        } else {
            return TRUE;
        }
    }
}
