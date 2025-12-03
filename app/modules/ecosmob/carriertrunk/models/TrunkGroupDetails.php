<?php

namespace app\modules\ecosmob\carriertrunk\models;

use app\models\CommonModel;
use app\modules\ecosmob\carriertrunk\CarriertrunkModule;
use app\modules\ecosmob\globalconfig\models\GlobalConfig;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "ct_trunk_group_details".
 *
 * @property integer $trunk_grp_detail_id
 * @property integer $trunk_id
 */
class TrunkGroupDetails extends CommonModel
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ct_trunk_group_details';
    }

    /**
     * @param $group_id
     *
     * @return array|ActiveRecord[]
     */
    static function get_Trunkname($group_id)
    {
        return TrunkMaster::find()->select('trunk_id, trunk_display_name')->where(
            [
                'IN',
                'trunk_id',
                $group_id,
            ]
        )->asArray()->all();
    }

    /**
     * @param $trunk_id
     *
     * @return string
     */
    static function getTrunkName($trunk_id)
    {
        $model = TrunkMaster::findOne(['trunk_id' => $trunk_id]);

        return isset($model->trunk_display_name) ? $model->trunk_display_name : '';
    }

    /**
     * @param $trunk_id
     *
     * @return bool
     */
    public static function trunkExistInDetails($trunk_id)
    {
        $trunkExistInDetails = TrunkGroupDetails::find()->where(
            ['trunk_id' => $trunk_id]
        )->count();
        if ($trunkExistInDetails) {
            return TRUE;
        } else {
            $trunkCheckConfig = GlobalConfig::findOne(
                [
                    'config_key' => 'default_fax_gateway',
                    'config_value' => $trunk_id,
                ]
            );
            if ($trunkCheckConfig) {
                return TRUE;
            } else {
                return FALSE;
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                [
                    'trunk_grp_detail_id',
                    'trunk_grp_id',
                    'trunk_id',
                ],
                'safe',
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'trunk_grp_detail_id' => CarriertrunkModule::t(
                'carriertrunk',
                'id'
            ),
            'trunk_grp_id' => CarriertrunkModule::t(
                'carriertrunk',
                'id'
            ),
            'trunk_id' => CarriertrunkModule::t(
                'carriertrunk',
                'trunk'
            ),
        ];
    }
}
