<?php

namespace app\modules\ecosmob\disposition\models;

use Yii;

/**
 * This is the model class for table "ct_disposition_group_status_mapping".
 *
 * @property int $id
 * @property int $ds_group_id
 * @property int $ds_status_id
 * @property int $ds_category_id
 */
class DispositionGroupStatusMapping extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ct_disposition_group_status_mapping';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ds_group_id', 'ds_status_id','ds_category_id'], 'required'],
            [['ds_group_id', 'ds_status_id','ds_category_id'], 'integer'],
            [['ds_group_id', 'ds_status_id','ds_category_id'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ds_group_id' => 'Ds Group ID',
            'ds_status_id' => 'Ds Status ID',
            'ds_category_id' => 'Disposition Category'
        ];
    }
}
