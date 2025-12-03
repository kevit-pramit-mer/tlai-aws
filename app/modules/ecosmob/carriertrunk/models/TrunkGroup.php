<?php

namespace app\modules\ecosmob\carriertrunk\models;

use app\models\CommonModel;
use app\modules\ecosmob\carriertrunk\CarriertrunkModule;
use app\modules\ecosmob\dialplan\models\OutboundDialPlansDetails;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "ct_trunk_group".
 *
 * @property integer $trunk_grp_id
 * @property string $trunk_grp_name
 * @property string $trunk_grp_desc
 * @property string $trunk_grp_status
 */
class TrunkGroup extends CommonModel
{

    public $trunkmaster;

    public $lstBox3;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ct_trunk_group';
    }

    /**
     * @return array|ActiveRecord[]
     */
    public static function getAllTrunkGroups()
    {
        $model = static::find()->orderBy(['trunk_grp_name' => SORT_ASC])->where(
            ['trunk_grp_status' => '1']
        )->all();

        return ArrayHelper::map($model, 'trunk_grp_id', 'trunk_grp_name');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                ['trunk_grp_name', 'trunk_grp_status'],
                'required',
            ],
            [['trunk_grp_status'], 'string'],
            [['trunk_grp_name'], 'uniqueWithCreate', 'on' => 'create'],
            [['trunk_grp_name'], 'uniqueWithUpdate', 'on' => 'update'],
            [['trunk_grp_name'], 'string', 'max' => 30],
            [['trunk_grp_desc'], 'string', 'max' => 255],
            [['trunk_grp_name'], 'match', 'pattern' => '/^[a-zA-Z0-9-_.\s]+$/'],
            [['lstBox3'], 'required', 'message' => CarriertrunkModule::t('carriertrunk', 'trunk_can_not_be_blank')],
            ['trunk_grp_status', 'checkStatus'],
            [
                [
                    'trunk_grp_name',
                    'trunk_grp_desc',
                    'trunk_grp_status',
                    'lstBox3',
                    'trunkmaster',
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
            'trunk_grp_name' => CarriertrunkModule::t('carriertrunk', 'name'),
            'trunk_grp_status' => CarriertrunkModule::t(
                'carriertrunk',
                'status'
            ),
            'trunk_grp_desc' => CarriertrunkModule::t(
                'carriertrunk',
                'description'
            ),
            'trunkmaster' => CarriertrunkModule::t(
                'carriertrunk',
                'trunk'
            ),

        ];
    }

    /**
     * @param $attribute
     *
     * @return bool
     */
    public function uniqueWithCreate($attribute)
    {

        $trunk_grp_name = $this->trunk_grp_name;

        $count = (int)TrunkGroup::find()
            ->where(['trunk_grp_name' => $trunk_grp_name])
            ->andWhere(['<>', 'trunk_grp_status', 'X'])
            ->count();

        if ($count > 0) {
            $this->addError(
                $attribute,
                CarriertrunkModule::t('carriertrunk', 'name_already_taken')
            );
        }

        return TRUE;
    }

    /**
     * @param $attribute
     *
     * @return bool
     */
    public function uniqueWithUpdate($attribute)
    {

        $trunk_grp_name = $this->trunk_grp_name;

        $count = (int)TrunkGroup::find()
            ->where(['trunk_grp_name' => $trunk_grp_name])
            ->andWhere(['<>', 'trunk_grp_status', 'X'])
            ->andWhere(['<>', 'trunk_grp_id', $this->trunk_grp_id])->count();
        if ($count > 0) {
            $this->addError(
                $attribute,
                CarriertrunkModule::t('carriertrunk', 'name_already_taken')
            );
        }

        return TRUE;
    }

    /**
     * @param $attribute
     */
    public function checkStatus($attribute)
    {
        if (!in_array($this->$attribute, ['1', '0'])) {
            $this->addError($attribute, 'Please select a valid status.');
        }
    }

    /**
     * @param $id
     * @return bool
     */
    public function canDelete($id)
    {
        /** @var OutboundDialPlansDetails $trunkCount */
        $trunkCount = OutboundDialPlansDetails::find()->where(
            ['trunk_grp_id' => $id]
        )->count();

        if ($trunkCount == 0) {
            return FALSE;
        } else {
            return TRUE;
        }

    }
}
