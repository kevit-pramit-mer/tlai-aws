<?php

namespace app\modules\ecosmob\disposition\models;

use app\modules\ecosmob\campaign\models\Campaign;
use app\modules\ecosmob\disposition\DispositionModule;
use app\modules\ecosmob\dispositionType\models\DispositionType;
use yii\db\ActiveRecord;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "ct_disposition_master".
 *
 * @property int $ds_id disposition auto increment id
 * @property string $ds_name disposition name
 * @property string $ds_description disposition description
 * @property string $ds_type
 */
class DispositionMaster extends ActiveRecord
{
    public $ds_type;
    public $ds_contacted_status;
    public $ds_non_contacted_status;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ct_disposition_master';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ds_name', 'ds_description'], 'required'],
            [['ds_name'], 'unique'],
            ['ds_name', 'match', 'pattern' => '/^[A-Za-z ]+$/', 'message' => DispositionModule::t('disposition', 'invalid_charcter_in_dispostion_name')],
            [['ds_description'], 'string', 'max' => 150],
            [['ds_name'], 'string', 'max' => 50],
            [
                ['ds_contacted_status'],
                'required',
                'when' => function ($model) {
                    return ($model->ds_non_contacted_status == '');
                }, 'whenClient' => "function (attribute, value) {
                        return ($('#dispositionmaster-ds_non_contacted_status').val() == '');
                    }"
            ],

            [
                ['ds_non_contacted_status'],
                'required',
                'when' => function ($model) {
                    return ($model->ds_contacted_status == '');
                }, 'whenClient' => "function (attribute, value) {
                        return ($('#dispositionmaster-ds_contacted_status').val() == '');
                    }"
            ],
            [['is_default'], 'safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ds_id' => DispositionModule::t('disposition', 'ds_id'),
            'ds_name' => DispositionModule::t('disposition', 'ds_name'),
            'ds_description' => DispositionModule::t('disposition', 'ds_description'),
            'ds_type' => DispositionModule::t('disposition', 'ds_status'),
            'ds_contacted_status' => 'Contacted',
            'ds_non_contacted_status' => 'No Contacted',
        ];
    }

    public function canDelete($id)
    {
        /** @var Campaign $campaignCount */
        $campaignCount = Campaign::find()->where(['cmp_disposition' => $id])->count();

        if ($campaignCount == 0) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    /**
     * @return ActiveQuery
     */
    public function getType()
    {
        return $this->hasMany(DispositionType::className(), ['ds_id' => 'ds_id'])->asArray();
    }
    public function getDispositionGroupStatus($id){
        $disStatus = DispositionGroupStatusMapping::find()->where(['ds_group_id' => $id])->all();
        $statusArr = [];

        foreach ($disStatus as $status) {
            $data = DispositionType::find()->where(['ds_type_id' => $status->ds_status_id])->one();
            if($data){
                $statusArr[] = $data->ds_type;
            }
        }
        return implode(',',$statusArr);
    }
}
