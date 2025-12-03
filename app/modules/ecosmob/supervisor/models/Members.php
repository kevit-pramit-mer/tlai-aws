<?php

namespace app\modules\ecosmob\supervisor\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "members".
 *
 * @property string $queue
 * @property string $system
 * @property string $uuid
 * @property string $session_uuid
 * @property string $cid_number
 * @property string $cid_name
 * @property int $system_epoch
 * @property int $joined_epoch
 * @property int $rejoined_epoch
 * @property int $bridge_epoch
 * @property int $abandoned_epoch
 * @property int $base_score
 * @property int $skill_score
 * @property string $serving_agent
 * @property string $serving_system
 * @property string $state
 */
class Members extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'members';
    }
    public static function getDb()
    {
        return Yii::$app->get('masterdb');
    }
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['system_epoch', 'joined_epoch', 'rejoined_epoch', 'bridge_epoch', 'abandoned_epoch', 'base_score', 'skill_score'], 'integer'],
            [['queue', 'system', 'uuid', 'session_uuid', 'cid_number', 'cid_name', 'serving_agent', 'serving_system', 'state'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'queue' => Yii::t('app', 'Queue'),
            'system' => Yii::t('app', 'System'),
            'uuid' => Yii::t('app', 'Uuid'),
            'session_uuid' => Yii::t('app', 'Session Uuid'),
            'cid_number' => Yii::t('app', 'Cid Number'),
            'cid_name' => Yii::t('app', 'Cid Name'),
            'system_epoch' => Yii::t('app', 'System Epoch'),
            'joined_epoch' => Yii::t('app', 'Joined Epoch'),
            'rejoined_epoch' => Yii::t('app', 'Rejoined Epoch'),
            'bridge_epoch' => Yii::t('app', 'Bridge Epoch'),
            'abandoned_epoch' => Yii::t('app', 'Abandoned Epoch'),
            'base_score' => Yii::t('app', 'Base Score'),
            'skill_score' => Yii::t('app', 'Skill Score'),
            'serving_agent' => Yii::t('app', 'Serving Agent'),
            'serving_system' => Yii::t('app', 'Serving System'),
            'state' => Yii::t('app', 'State'),
        ];
    }
}
