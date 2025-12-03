<?php

namespace app\modules\ecosmob\timezone\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "ct_timezone".
 *
 * @property int $tz_id
 * @property string $time
 * @property string $tz_zone
 * @property int $sec
 */
class Timezone extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ct_timezone';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['time', 'tz_zone', 'sec'], 'required'],
            [['sec'], 'integer'],
            [['tz_id'], 'safe'],
            [['time'], 'string', 'max' => 20],
            [['tz_zone'], 'string', 'max' => 250],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'tz_id' => Yii::t('app', 'Tz ID'),
            'time' => Yii::t('app', 'Time'),
            'tz_zone' => Yii::t('app', 'Tz Zone'),
            'sec' => Yii::t('app', 'Sec'),
        ];
    }

    /**
     * @return array
     */
    public static function getTimezone()
    {
        $timezone = Timezone::find()->all();
        $timezoneCollection = ArrayHelper::map($timezone, 'tz_id', 'tz_zone');

        return $timezoneCollection;
    }

    /**
     * @param $id
     *
     * @return mixed
     */
    public static function getTimezoneName($id) {
        /** @var timezone $timezone */
        $timezone = self::findOne($id);

        return $timezone->tz_zone;
    }
}
