<?php

namespace app\modules\ecosmob\agent\models;

use app\modules\ecosmob\agent\AgentModule;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "agents".
 *
 * @property string $name
 * @property string $system
 * @property string $uuid
 * @property string $type
 * @property string $contact
 * @property string $status
 * @property string $state
 * @property int    $max_no_answer
 * @property int    $wrap_up_time
 * @property int    $reject_delay_time
 * @property int    $busy_delay_time
 * @property int    $no_answer_delay_time
 * @property int    $last_bridge_start
 * @property int    $last_bridge_end
 * @property int    $last_offered_call
 * @property int    $last_status_change
 * @property int    $no_answer_count
 * @property int    $calls_answered
 * @property int    $talk_time
 * @property int    $ready_time
 * @property int    $reject_call_count
 */
class Agent extends \yii\db\ActiveRecord {
    
    /**
     * {@inheritdoc}
     */
    public static function tableName () {
        return 'agents';
    }
    
    /**
     * {@inheritdoc}
     */
    public function rules () {
        return [
            [ [ 'name' ], 'required' ],
            [['name'], 'string', 'min' => 4, 'max' => 30],
            // [['contact'], 'string', 'min' => 8,  'max' => 10],
            // [['contact'], 'integer'],
            [
                [
                    'max_no_answer',
                    'wrap_up_time',
                    'reject_delay_time',
                    'busy_delay_time',
                    'no_answer_delay_time',
                    'last_bridge_start',
                    'last_bridge_end',
                    'last_offered_call',
                    'last_status_change',
                    'no_answer_count',
                    'calls_answered',
                    'talk_time',
                    'ready_time',
                    'reject_call_count',
                ],
                'integer',
            ],
            [ [ 'system', 'uuid', 'type', 'status', 'state' ], 'string', 'max' => 255 ],
            [ [ 'name' ], 'unique' ],
            [ [ 'login_extension' ], 'safe' ],
        ];
    }
    
    /**
     * {@inheritdoc}
     */
    public function attributeLabels () {
        return [
            'name'                 => AgentModule::t( 'agent', 'name' ),
            'system'               => AgentModule::t( 'agent', 'system' ),
            'uuid'                 => AgentModule::t( 'agent', 'uuid' ),
            'type'                 => AgentModule::t( 'agent', 'type' ),
            'contact'              => AgentModule::t( 'agent', 'contact' ),
            'status'               => AgentModule::t( 'agent', 'status' ),
            'state'                => AgentModule::t( 'agent', 'state' ),
            'max_no_answer'        => AgentModule::t( 'agent', 'max_no_answer' ),
            'wrap_up_time'         => AgentModule::t( 'agent', 'wrap_up_time' ),
            'reject_delay_time'    => AgentModule::t( 'agent', 'reject_delay_time' ),
            'busy_delay_time'      => AgentModule::t( 'agent', 'busy_delay_time' ),
            'no_answer_delay_time' => AgentModule::t( 'agent', 'no_answer_delay_time' ),
            'last_bridge_start'    => AgentModule::t( 'agent', 'last_bridge_start' ),
            'last_bridge_end'      => AgentModule::t( 'agent', 'last_bridge_end' ),
            'last_offered_call'    => AgentModule::t( 'agent', 'last_offered_call' ),
            'last_status_change'   => AgentModule::t( 'agent', 'last_status_change' ),
            'no_answer_count'      => AgentModule::t( 'agent', 'no_answer_count' ),
            'calls_answered'       => AgentModule::t( 'agent', 'calls_answered' ),
            'talk_time'            => AgentModule::t( 'agent', 'talk_time' ),
            'ready_time'           => AgentModule::t( 'agent', 'ready_time' ),
            'reject_call_count'    => AgentModule::t( 'agent', 'reject_call_count' ),
        ];
    }
    
    public static function getAllAgents() {
        $model = Agent::find()->all();
        
        return ArrayHelper::map($model, 'name' , 'name');
    }
}
