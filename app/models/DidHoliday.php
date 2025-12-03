<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ct_did_holiday".
 *
 * @property int $id
 * @property int $did_id
 * @property int $hd_id
 */
class DidHoliday extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ct_did_holiday';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['did_id', 'hd_id'], 'required'],
            [['did_id', 'hd_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'did_id' => 'Did ID',
            'hd_id' => 'Hd ID',
        ];
    }
}
