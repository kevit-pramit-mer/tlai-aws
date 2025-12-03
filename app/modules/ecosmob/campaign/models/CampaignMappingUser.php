<?php

namespace app\modules\ecosmob\campaign\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "campaign_mapping_user".
 *
 * @property int $id
 * @property int $campaign_id
 * @property int $supervisor_id
 */
class CampaignMappingUser extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'campaign_mapping_user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['campaign_id', 'supervisor_id'], 'required'],
            [['campaign_id', 'supervisor_id'], 'safe'],
            [['campaign_id', 'supervisor_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'campaign_id' => Yii::t('app', 'Campaign ID'),
            'supervisor_id' => Yii::t('app', 'Supervisor ID'),
        ];
    }
}
