<?php

namespace app\modules\ecosmob\feature\models;

use app\modules\ecosmob\feature\FeatureModule;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "ct_feature_master".
 *
 * @property int $feature_id
 * @property string $feature_name
 * @property string $feature_code
 * @property string $feature_desc
 */
class Feature extends ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ct_feature_master';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['feature_name', 'feature_code'], 'required'],
            [['feature_code'], 'unique'],
            [['feature_name'], 'string', 'max' => 30],
            [['feature_code'], 'string', 'max' => 4],
            [['feature_desc'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'feature_id' => FeatureModule::t('feature', 'id'),
            'feature_name' => FeatureModule::t('feature', 'name'),
            'feature_code' => FeatureModule::t('feature', 'code'),
            'feature_desc' => FeatureModule::t('feature', 'desc'),
        ];
    }
}
