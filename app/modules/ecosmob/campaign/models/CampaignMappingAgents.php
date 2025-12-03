<?php

namespace app\modules\ecosmob\campaign\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "campaign_mapping_agents".
 *
 * @property int $id
 * @property int $campaign_id
 * @property string $agent_id
 */
class CampaignMappingAgents extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'campaign_mapping_agents';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['campaign_id', 'agent_id'], 'required'],
            [['campaign_id'], 'integer'],
            [['campaign_id', 'agent_id'], 'safe'],
            [['agent_id'], 'string', 'max' => 250],

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
            'agent_id' => Yii::t('app', 'Agent ID'),
        ];
    }
}
