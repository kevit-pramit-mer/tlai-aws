<?php

namespace app\modules\ecosmob\realtimedashboard\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\db\Query;

class UserMonitor extends ActiveRecord
{
    public $total_idle_time;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'agent_monitor';
    }

    public function rules()
    {
        return [
            [[
                'id', 'user_id', 'login_time', 'logout_time', 'created_at', 'campaign_name', 'cmp_name',
                'agent', 'extension_number', 'customer_number', 'status', 'campaign', 'queue', 'total_calls',
                'total_talk_time', 'avg_call_duration', 'avg_wait_time', 'login_hour', 'total_break_time',
                'total_breaks', 'total_idle_time'
            ], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        return Model::scenarios();
    }

    public function attributeLabels()
    {
        return [
            'agent' => 'Agent',
            'extension_number' => 'Extension Number',
            'customer_number' => 'Customer',
            'status' => 'Status',
            'cmp_name' => 'Campaign',
            'queue' => 'Queue',
            'total_calls' => 'Total Calls',
            'total_talk_time' => 'Total Talk Time',
            'avg_call_duration' => 'Average Call Duration',
            'total_idle_time' => 'Total Idle Time',
            'avg_wait_time' => 'Average Wait Time',
            'login_hour' => 'Login Hour',
            'total_break_time' => 'Total Break Time',
            'total_breaks' => 'Total Breaks',
            'user_id' => 'Agent Name'
        ];
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $totalCallsSubquery = UserMonitor::find()
            ->select('SUM(total_calls)')
            ->andWhere('user_id = ua.user_id');
        $totalTalkTimeSubquery = UserMonitor::find()
            ->select('SUM(total_talk_time)')
            ->andWhere('user_id = ua.user_id');
        $avgCallDurationSubquery = UserMonitor::find()
            ->select('SUM(avg_call_duration)')
            ->andWhere('user_id = ua.user_id');
        $avgWaitTimeSubquery = UserMonitor::find()
            ->select('SUM(avg_wait_time)')
            ->andWhere('user_id = ua.user_id');

        $query = UserMonitor::find()
            ->select([
                'ua.id',
                'ua.user_id',
                'ua.agent',
                'ua.extension_number',
                'ua.customer_number',
                'ua.status',
                'ua.state',
                '(GROUP_CONCAT(DISTINCT(campaign_name))) as campaign_name',
                '(GROUP_CONCAT(DISTINCT(cmp_name))) as cmp_name',
                '(GROUP_CONCAT(DISTINCT(queue))) as queue',
                'total_calls' => $totalCallsSubquery,
                'total_talk_time' => $totalTalkTimeSubquery,
                'avg_call_duration' => $avgCallDurationSubquery,
                'avg_wait_time' => $avgWaitTimeSubquery,
                'login_hour',
                'total_break_time',
                'total_breaks'
            ])
            ->from('agent_monitor ua')
            ->groupBy('ua.user_id');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => false,
            'pagination' => false,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        if (isset($this->cmp_name) && !empty($this->cmp_name)) {
            $totalCallsSubquery->andWhere(['cmp_name' => $this->cmp_name]);
            $totalTalkTimeSubquery->andWhere(['cmp_name' => $this->cmp_name]);
            $avgCallDurationSubquery->andWhere(['cmp_name' => $this->cmp_name]);
            $avgWaitTimeSubquery->andWhere(['cmp_name' => $this->cmp_name]);
            $query->andFilterWhere(['ua.cmp_name' => $this->cmp_name]);
        }

        if (isset($this->queue) && !empty($this->queue)) {
            $totalCallsSubquery->andWhere(['queue' => $this->queue]);
            $totalTalkTimeSubquery->andWhere(['queue' => $this->queue]);
            $avgCallDurationSubquery->andWhere(['queue' => $this->queue]);
            $avgWaitTimeSubquery->andWhere(['queue' => $this->queue]);
            $query->andFilterWhere(['ua.queue' => $this->queue]);
        }

        if (isset($this->user_id) && !empty($this->user_id)) {
            $query->andFilterWhere(['ua.user_id' => $this->user_id]);
        }

        return $dataProvider;
    }
}
