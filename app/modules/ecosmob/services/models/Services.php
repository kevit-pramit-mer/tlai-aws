<?php

namespace app\modules\ecosmob\services\models;

use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "ct_services".
 *
 * @property int $ser_id
 * @property string $ser_name
 */
class Services extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ct_services';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ser_name'], 'required'],
            [['ser_name'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ser_id' => 'Ser ID',
            'ser_name' => 'Ser Name',
        ];
    }

    /**
     * @return array
     */
    public static function getServices() {
        return ArrayHelper::map( static::find()->all(), 'ser_id', 'ser_name' );
    }

    /**
     * @return array
     */
    public static function getHCustomServices() {
        return ArrayHelper::map( static::find()->andWhere(['<>','ser_name', 'VOICEMAIL'])->andWhere(['<>','ser_name', 'FAX'])->andWhere(['<>','ser_name', 'CAMPAIGN'])->all(), 'ser_id', 'ser_name' );
    }

    public static function getCampServices() {
        return ArrayHelper::map( static::find()->andWhere(['<>','ser_name', 'CAMPAIGN'])->andWhere(['<>','ser_name', 'FAX'])->all(), 'ser_id', 'ser_name' );
    }

    public static function getQueueOnFailServices() {
        return ArrayHelper::map( static::find()->andWhere(['<>','ser_name', 'FAX'])->all(), 'ser_id', 'ser_name' );
    }
}
