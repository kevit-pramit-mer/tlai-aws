<?php

namespace app\modules\ecosmob\realtimedashboard\models;

use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;

class QueueStatusReport extends ActiveRecord
{

    /**
     * {@inheritdoc}
     */

    public static function tableName()
    {
        return 'queue_status_report';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['queue', 'total_calls', 'calls_in_queue', 'abandoned_calls', 'logged_in_agents', 'avg_call_duration', 'avg_queue_wait_time', 'longest_queue_wait_time', 'longest_abandoned_calls_wait_time'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'queue' => 'Queue Name',
            'total_calls' => 'Total Calls',
            'calls_in_queue' => 'Calls In Queue',
            'abandoned_calls' => 'Abandoned Calls',
            'logged_in_agents' => 'Logged-in Agents',
            'avg_call_duration' => 'Avg Call Duration',
            'avg_queue_wait_time' => 'Avg Wait Time',
            'longest_queue_wait_time' => 'Longest Wait Time',
            'longest_abandoned_calls_wait_time' => 'Avg Abandoned Call Wait Time'
        ];
    }

    public function search($params)
    {

        $query = self::find()->where(['IS NOT', 'queue', null]);

        /*$query = self::find()
            ->select([
                'qm.qm_name as queue',
                'count(cc.id) as total_calls',
                'calls_in_queue' => new Expression('SUM(CASE WHEN (start_time IS NULL OR start_time = "0000-00-00 00:00:00") AND (queue_join_time IS NOT NULL OR queue_join_time != "0000-00-00 00:00:00") THEN 1 ELSE 0 END)'),
                'abandoned_calls' => new Expression('SUM(CASE WHEN (queue_join_time IS NOT NULL OR queue_join_time != "0000-00-00 00:00:00") AND (ans_time = "" OR ans_time IS NULL OR ans_time = "0000-00-00 00:00:00") AND (end_time IS NOT NULL OR end_time != "0000-00-00 00:00:00") THEN 1 ELSE 0 END)'),
                'logged_in_agents' => UsersActivityLog::find()
                    ->select(['count(id)'])
                    ->andWhere('agent_id = cc.agent_id')
                    ->andWhere('DATE(cc.queue_join_time) >=  DATE(login_time)'),
                'avg_call_duration' => new Expression('AVG(CASE WHEN (queue_join_time IS NOT NULL OR queue_join_time != "0000-00-00 00:00:00") AND (ans_time IS NOT NULL OR ans_time != "0000-00-00 00:00:00") THEN TIMESTAMPDIFF(SECOND, ans_time, end_time) ELSE 0 END)'),
                'avg_queue_wait_time' => new Expression('AVG(CASE WHEN (queue_join_time IS NOT NULL OR queue_join_time != "0000-00-00 00:00:00") AND (ans_time IS NOT NULL OR ans_time != "0000-00-00 00:00:00") THEN TIMESTAMPDIFF(SECOND, queue_join_time, ans_time) ELSE 0 END)'),
                'longest_queue_wait_time' => new Expression('MAX(CASE WHEN (queue_join_time IS NOT NULL OR queue_join_time != "0000-00-00 00:00:00") AND (ans_time IS NOT NULL OR ans_time != "0000-00-00 00:00:00") THEN TIMESTAMPDIFF(SECOND, queue_join_time, ans_time) ELSE 0 END)'),
                'longest_abandoned_calls_wait_time' => new Expression('MAX(CASE WHEN (queue_join_time IS NOT NULL OR queue_join_time != "0000-00-00 00:00:00") AND (ans_time = "" OR ans_time IS NULL OR ans_time = "0000-00-00 00:00:00") AND (end_time IS NOT NULL OR end_time != "0000-00-00 00:00:00") THEN TIMESTAMPDIFF(SECOND, queue_join_time, ans_time) ELSE 0 END)'),

//                'call_duration' =>self::find()
//                    ->select(['TIMESTAMPDIFF(SECOND, ans_time, end_time)'])
//                    ->where(['or',
//                        ['<>', 'ans_time', NULL],
//                        ['<>', 'ans_time', '0000-00-00 00:00:00'],
//                    ])
//                    ->andWhere('DATE_FORMAT(cc.start_time, "%Y-%m-%d")=DATE_FORMAT(start_time, "%Y-%m-%d")')
//                    ->andWhere('cc.queue = queue'),
//                'abandoned_time' =>self::find()
//                    ->select(['TIMESTAMPDIFF(SECOND, start_time, end_time)'])
//                    ->where(['or',
//                        ['=', 'ans_time', NULL],
//                        ['=', 'ans_time', '0000-00-00 00:00:00'],
//                    ])
//                    ->andWhere('DATE_FORMAT(cc.start_time, "%Y-%m-%d")=DATE_FORMAT(start_time, "%Y-%m-%d")')
//                    ->andWhere('cc.queue = queue'),
//                'queue_wait_time' =>self::find()
//                    ->select(['TIMESTAMPDIFF(SECOND, queue_join_time, ans_time)'])
//                    ->where(['or',
//                        ['<>', 'ans_time', NULL],
//                        ['<>', 'ans_time', '0000-00-00 00:00:00'],
//                    ])
//                    ->andWhere('DATE_FORMAT(cc.start_time, "%Y-%m-%d")=DATE_FORMAT(start_time, "%Y-%m-%d")')
//                    ->andWhere('cc.queue = queue'),
//                'answered' => self::find()
//                    ->select(['count(id)'])
//                    ->where(['or',
//                        ['<>', 'ans_time', NULL],
//                        ['<>', 'ans_time', '0000-00-00 00:00:00'],
//                    ])
//                    ->andWhere('DATE_FORMAT(cc.start_time, "%Y-%m-%d")=DATE_FORMAT(start_time, "%Y-%m-%d")')
//                    ->andWhere('cc.queue = queue'),
//                'abandoned' => (new Query())
//                    ->select(['count(id)'])
//                    ->from('ct_queue_abandoned_calls')
//                    ->where('DATE_FORMAT(cc.start_time, "%Y-%m-%d")=from_unixtime(start_time, "%Y-%m-%d")')
//                    ->andWhere('cc.queue = queue_name'),
            ])
            ->from('camp_cdr cc')
            ->leftjoin('ct_queue_master qm', 'qm.qm_name = cc.queue')
            ->andWhere(['<>', 'queue', ' '])
            ->andWhere(['or',
                ['<>', 'queue_join_time', NULL],
                ['<>', 'queue_join_time', '0000-00-00 00:00:00'],
            ])
            ->groupBy('cc.queue');*/

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            /*'sort' => [
                'defaultOrder' => ["queue" => SORT_DESC],
                'attributes' => [
                    'queue' => [
                        'asc' => ['queue' => SORT_ASC],
                        'desc' => ['queue' => SORT_DESC],
                    ],
                    'total_calls' => [
                        'asc' => ['total_calls' => SORT_ASC],
                        'desc' => ['total_calls' => SORT_DESC],
                    ],
                    'calls_in_queue' => [
                        'asc' => ['calls_in_queue' => SORT_ASC],
                        'desc' => ['calls_in_queue' => SORT_DESC],
                    ],
                    'abandoned_calls' => [
                        'asc' => ['abandoned_calls' => SORT_ASC],
                        'desc' => ['abandoned_calls' => SORT_DESC],
                    ],
                    'logged_in_agents' => [
                        'asc' => ['logged_in_agents' => SORT_ASC],
                        'desc' => ['logged_in_agents' => SORT_DESC]
                    ],
                    'avg_call_duration' => [
                        'asc' => ['avg_call_duration' => SORT_ASC],
                        'desc' => ['avg_call_duration' => SORT_DESC],
                    ],
                    'avg_queue_wait_time' => [
                        'asc' => ['avg_queue_wait_time' => SORT_ASC],
                        'desc' => ['avg_queue_wait_time' => SORT_DESC],
                    ],
                    'longest_queue_wait_time' => [
                        'asc' => ['longest_queue_wait_time' => SORT_ASC],
                        'desc' => ['longest_queue_wait_time' => SORT_DESC],
                    ],
                    'longest_abandoned_calls_wait_time' => [
                        'asc' => ['longest_abandoned_calls_wait_time' => SORT_ASC],
                        'desc' => ['longest_abandoned_calls_wait_time' => SORT_DESC],
                    ],
                ],
            ],*/
            'pagination' => false,
            'sort' => false
        ]);

        $this->load($params);


        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'queue' => $this->queue,
        ]);

        return $dataProvider;
    }
}
