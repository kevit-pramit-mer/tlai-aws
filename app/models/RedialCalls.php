<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ct_redial_calls".
 *
 * @property int $id
 * @property int $ld_id
 * @property int $lgm_id
 * @property int $rd_status
 * @property int $ds_type_id
 * @property int $ds_category_id
 */
class RedialCalls extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ct_redial_calls';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ld_id', 'lgm_id'], 'required'],
            [['ld_id', 'lgm_id', 'rd_status', 'ds_type_id', 'ds_category_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ld_id' => 'Ld ID',
            'lgm_id' => 'Lgm ID',
            'rd_status' => 'Rd Status',
            'ds_type_id' => 'Ds Type ID',
            'ds_category_id' => 'Ds Category ID',
        ];
    }
}
