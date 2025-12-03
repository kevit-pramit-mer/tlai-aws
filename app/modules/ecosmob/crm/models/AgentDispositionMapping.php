<?php

namespace app\modules\ecosmob\crm\models;

use Yii;

/**
 * This is the model class for table "agent_disposition_mapping".
 *
 * @property int $adm_id
 * @property int $agent_id
 * @property int $lead_id
 * @property int $dispostion
 * @property string $comment
 * @property int $camp_id
 * @property int $is_redialed
 * @property string $create_at
 */
class AgentDispositionMapping extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'agent_disposition_mapping';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['disposition'], 'required'],
            [['disposition'], 'integer', 'message' => 'Disposition cannot be blank.'],
            [['agent_id', 'lead_id', 'campaign_id'], 'integer'],
            [['comment'], 'string'],
            [['create_at','disposition','campaign_id','comment', 'is_redialed'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'agent_id' => Yii::t('app', 'Agent ID'),
            'lead_id' => Yii::t('app', 'Lead ID'),
            'disposition' => Yii::t('app', 'disposition'),
            'comment' => Yii::t('app', 'Comment'),
            'camp_id' => Yii::t('app', 'Camp ID'),
            'is_redialed' => Yii::t('app', 'Is Redialed'),
            'create_at' => Yii::t('app', 'Create At'),
        ];
    }
}
