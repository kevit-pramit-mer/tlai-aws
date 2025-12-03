<?php

namespace app\modules\ecosmob\systemcode\models;

use app\modules\ecosmob\systemcode\SystemCodeModule;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "ct_feature_master".
 *
 * @property int $feature_id
 * @property string $feature_name
 * @property string $feature_code
 * @property string $feature_desc
 */
class FeatureMaster extends ActiveRecord
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
            [['feature_name'], 'string', 'max' => 30],
            [['feature_code'], 'string', 'max' => 4],
            [['feature_desc'], 'string', 'max' => 500],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'feature_id' => Yii::t('app', 'Feature ID'),
            'feature_name' => SystemCodeModule::t('systemcode', 'feature_name'),
            'feature_code' => SystemCodeModule::t('systemcode', 'feature_code'),
            'feature_desc' => SystemCodeModule::t('systemcode', 'feature_desc'),
        ];
    }
}
