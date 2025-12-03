<?php

namespace app\modules\ecosmob\crm\models;

use Yii;

/**
 * This is the model class for table "lead_comment_mapping".
 *
 * @property int $id
 * @property int $lead_id
 * @property int $agents_id
 * @property int $campaign_id
 * @property string $comment
 * @property string $lead_status
 * @property string $created_at
 */
class LeadCommentMapping extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'lead_comment_mapping';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['agents_id', 'campaign_id'], 'required'],
            [['lead_id', 'agents_id', 'campaign_id' ,'id'], 'integer'],
            [['comment'], 'string'],
            [['created_at','lead_group_id'], 'safe'],
            [['lead_status'], 'string', 'max' => 150],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'lead_id' => Yii::t('app', 'Lead ID'),
            'agents_id' => Yii::t('app', 'Agents ID'),
            'campaign_id' => Yii::t('app', 'Campaign ID'),
            'comment' => Yii::t('app', 'Comment'),
            'lead_status' => Yii::t('app', 'Lead Status'),
            'created_at' => Yii::t('app', 'Created At'),
        ];
    }
}
