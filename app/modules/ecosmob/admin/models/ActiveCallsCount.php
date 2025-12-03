<?php

namespace app\modules\ecosmob\admin\models;

use Yii;

/**
 * This is the model class for table "active_calls_count".
 *
 * @property string $date
 * @property string $start_time
 * @property int $count
 * @property string $update_time
 */
class ActiveCallsCount extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'dash_active_calls_count';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['date', 'start_time', 'update_time'], 'safe'],
            [['count'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'date' => Yii::t('app', 'Date'),
            'start_time' => Yii::t('app', 'Start Time'),
            'count' => Yii::t('app', 'Count'),
            'update_time' => Yii::t('app', 'Update Time'),
        ];
    }
}
