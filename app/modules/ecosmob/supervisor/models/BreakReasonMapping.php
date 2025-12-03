<?php

namespace app\modules\ecosmob\supervisor\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "break_reason_mapping".
 *
 * @property int $id
 * @property int $user_id
 * @property int $camp_id
 * @property string $break_reason
 * @property string $break_status
 * @property string $in_time
 * @property string $out_time
 */
class BreakReasonMapping extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public $campstype;

    public static function tableName()
    {
        return 'break_reason_mapping';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'break_reason', 'break_status', 'in_time'], 'required'],
            [['user_id'], 'integer'],
            [['break_reason', 'break_status', 'camp_id'], 'string'],
            [['id'], 'safe'],
            [['in_time', 'out_time', 'campstype'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'camp_id' => Yii::t('app', 'Camp ID'),
            'break_reason' => Yii::t('app', 'Break Reason'),
            'break_status' => Yii::t('app', 'Break Status'),
            'in_time' => Yii::t('app', 'In Time'),
            'out_time' => Yii::t('app', 'Out Time'),
        ];
    }
}
