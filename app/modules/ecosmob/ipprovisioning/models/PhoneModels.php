<?php

namespace app\modules\ecosmob\ipprovisioning\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "tbl_phone_models".
 *
 * @property string $p_id
 * @property int $pv_id
 * @property string $p_model
 * @property int $p_lines
 */
class PhoneModels extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tbl_phone_models';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['p_id', 'pv_id', 'p_model', 'p_lines'], 'required'],
            [['pv_id', 'p_lines'], 'integer'],
            [['p_id'], 'string', 'max' => 36],
            [['p_model'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'p_id' => 'P ID',
            'pv_id' => 'Pv ID',
            'p_model' => 'P Model',
            'p_lines' => 'P Lines',
        ];
    }
}
