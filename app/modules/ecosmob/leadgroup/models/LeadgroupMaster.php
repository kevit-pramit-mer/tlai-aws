<?php

namespace app\modules\ecosmob\leadgroup\models;

use app\modules\ecosmob\campaign\models\Campaign;
use app\modules\ecosmob\leadgroup\LeadgroupModule;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "ct_leadgroup_master".
 *
 * @property int $ld_id lead group auto increment id
 * @property string $ld_group_name lead group name
 */
class LeadgroupMaster extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ct_leadgroup_master';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ld_group_name'], 'required'],
            [['ld_group_name'], 'unique'],
            ['ld_group_name', 'match', 'pattern' => '/^[A-Za-z0-9 ]+$/', 'message' => LeadgroupModule::t('leadgroup', 'invalid_charcter_in_lead_grp_name')],
            [['ld_group_name'], 'string', 'max' => 50, 'min' => 3],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ld_id' => LeadgroupModule::t('leadgroup', 'ld_id'),
            'ld_group_name' => LeadgroupModule::t('leadgroup', 'ld_group_name'),
        ];
    }

    /**
     * @param $id
     * @return bool
     */
    public function canDelete($id)
    {

        /** @var Campaign $campaignCount */
        $campaignCount = Campaign::find()->where(['cmp_lead_group' => $id])->count();

        if ($campaignCount == 0) {
            return FALSE;
        } else {
            return TRUE;
        }
    }
}
