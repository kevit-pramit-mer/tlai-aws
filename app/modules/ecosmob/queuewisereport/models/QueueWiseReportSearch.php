<?php

namespace app\modules\ecosmob\queuewisereport\models;

use app\components\CommonHelper;
use app\modules\ecosmob\queue\models\QueueMaster;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\db\Query;
use yii\helpers\ArrayHelper;


/**
 * QueueWiseReportSearch represents the model behind the search form of `app\modules\ecosmob\queuewisereport\models\QueueWiseReport`.
 */
class QueueWiseReportSearch extends QueueWiseReport
{
    public function rules () {
        return [
            [
                [
                    '_id',
                    'queue_uuid',
                    'queue_number',
                    'caller_id_number',
                    'queue_started',
                    'queue_answered',
                    'queue_ended',
                    'agent_answered_num',
                    'agent_answer_duration',
                    'hold_time',
                    'max_wait_reached',
                    'breakaway_digit_dialed',
                    'abandoned_time',
                    'queue_name',
                    'call_status',
                    'abandoned_wait_time',
                    'break_away_wait_time',
                    'duration',
                    'billsec',
                    'qm_id'
                ],
                'safe',
            ],
            [
                'queue_ended',
                'compare',
                'compareAttribute' => 'queue_started',
                'operator' => '>=',
                'message' => Yii::t('app', 'from_to_date_cmp')
            ],
            [['queue_ended'], 'required', 'when' => function ($model) {
                return $model->queue_started != null;
            }, 'whenClient' => "function (attribute, value) {
                  return $('#queuewisereportsearch-queue_started').val() != '' ;
              }", 'message' => Yii::t('app', 'to_date_req'), 'enableClientValidation' => true],
            [['queue_started'], 'required', 'when' => function ($model) {
                return $model->queue_ended != null;
            }, 'whenClient' => "function (attribute, value) {
                  return $('#queuewisereportsearch-queue_ended').val() != '' ;
              }", 'message' => Yii::t('app', 'from_date_req'), 'enableClientValidation' => true],
        ];
    }

    public function scenarios () {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
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
        $query = QueueWiseReport::find()
            /*->select([
                'queue_name as queue',
                'count(_id) as incoming_call',
                'count(DISTINCT(agent_answered_num)) as agent_id',
                'queue_started as date',
                'AVG(hold_time) avg_waiting_time',
                'answered' => new Expression('SUM(CASE WHEN agent_answered_num != "" OR agent_answer_duration != 0 THEN 1 ELSE 0 END)'),
                'abandoned' => new Expression('SUM(CASE WHEN abandoned_time != "" THEN 1 ELSE 0 END)'),
            ])
            ->groupBy(['date_format(from_unixtime(queue_started), "%Y-%m-%d")', 'queue_name'])*/
        ;

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);

        $this->load($params);

        if ($this->queue_started && $this->queue_ended) {
            $from = strtotime(CommonHelper::DtTots($this->queue_started . ' 00:00:01'));
            $to = strtotime(CommonHelper::DtTots($this->queue_ended . ' 23:59:59'));
            $query->andFilterWhere(['>=', 'queue_started', trim($from)]);
            $query->andFilterWhere(['<=', 'queue_ended', trim($to)]);
        }

        $summaryData = [];
        foreach ($dataProvider->getModels() as $model) {
            $queueDate = '';
            if(isset($model->qm_id)){
                $queueDate = $model->qm_id;
            }else{
                $queue = QueueMaster::findOne(['qm_name' => $model->queue_name]);
                if(!empty($queue)) {
                    $queueDate = $queue->qm_id;
                }
            }
            if(!empty($queueDate)) {
                if (!isset($summaryData[$queueDate])) {
                    $summaryData[$queueDate] = [
                        'queue' => $model->queue_name,
                        'queue_num' => $model->queue_number,
                        'incoming_call' => 0,
                        'total_agent' => [],
                        'total_hold_time' => 0,
                        'total_call_duration' => 0,
                        'answered' => 0,
                        'abandoned' => 0,
                    ];
                }
                $summaryData[$queueDate]['incoming_call']++;
                $summaryData[$queueDate]['total_hold_time'] += intval($model->hold_time);
                if ($model->agent_answered_num != "" && $model->agent_answer_duration != 0) {
                    $summaryData[$queueDate]['answered']++;
                    $summaryData[$queueDate]['total_agent'][] = $model->agent_answered_num;
                    $summaryData[$queueDate]['total_call_duration'] += intval($model->agent_answer_duration);
                }
                if ($model->abandoned_time != "") {
                    $summaryData[$queueDate]['abandoned']++;
                }
            }
        }

        $summaryData = $this->qm_id ? array_filter($summaryData, fn($key) => ($key == $this->qm_id), ARRAY_FILTER_USE_KEY) : $summaryData;

        foreach ($summaryData as &$summaryItem) {
            $summaryItem['avg_waiting_time'] = (($summaryItem['incoming_call'] > 0 && $summaryItem['total_hold_time'] > 0) ? $summaryItem['total_hold_time'] / $summaryItem['incoming_call'] : 0);
            $summaryItem['agent'] = count(array_unique($summaryItem['total_agent']));
            $summaryItem['avg_call_duration'] = (($summaryItem['incoming_call'] > 0 && $summaryItem['total_call_duration'] > 0) ? $summaryItem['total_call_duration'] / $summaryItem['incoming_call'] : 0);
        }

        $summaryDataProvider = new ArrayDataProvider([
            'allModels' => array_values($summaryData),
            'sort' => [
                'defaultOrder' => ["queue" => SORT_DESC],
                'attributes' => [
                    'queue' => [
                        'asc' => ['queue' => SORT_ASC],
                        'desc' => ['queue' => SORT_DESC],
                    ],
                    'queue_num' => [
                        'asc' => ['queue_num' => SORT_ASC],
                        'desc' => ['queue_num' => SORT_DESC],
                    ],
                    'incoming_call' => [
                        'asc' => ['incoming_call' => SORT_ASC],
                        'desc' => ['incoming_call' => SORT_DESC],
                    ],
                    'total_call_duration' => [
                        'asc' => ['total_call_duration' => SORT_ASC],
                        'desc' => ['total_call_duration' => SORT_DESC],
                    ],
                    'avg_call_duration' => [
                        'asc' => ['avg_call_duration' => SORT_ASC],
                        'desc' => ['avg_call_duration' => SORT_DESC],
                    ],
                    'answered' => [
                        'asc' => ['answered' => SORT_ASC],
                        'desc' => ['answered' => SORT_DESC],
                    ],
                    'abandoned' => [
                        'asc' => ['abandoned' => SORT_ASC],
                        'desc' => ['abandoned' => SORT_DESC],
                    ],
                    'agent' => [
                        'asc' => ['agent' => SORT_ASC],
                        'desc' => ['agent' => SORT_DESC],
                    ],
                    'avg_waiting_time' => [
                        'asc' => ['avg_waiting_time' => SORT_ASC],
                        'desc' => ['avg_waiting_time' => SORT_DESC],
                    ],
                ],
            ],
            'pagination' => ['pageSize' => Yii::$app->layoutHelper->get_per_page_record_count()],
        ]);

        return $summaryDataProvider;

    /*    $query = new Query();
        $query->select([
            'queue' => '$queue_name',
            'incoming_call' => ['$sum' => 1],
            'agent_id' => ['$addToSet' => '$agent_answered_num'],
            'date' => [
                '$dateToString' => [
                    'format' => '%Y-%m-%d',
                    'date' => ['$toDate' => ['$multiply' => ['$queue_started', 1000]]]
                ]
            ],
            'avg_waiting_time' => ['$avg' => '$hold_time'],
            'answered' => ['$sum' => ['$cond' => [
                ['$ne' => ['$agent_answered_num', '']],
                1,
                ['$cond' => ['$eq' => ['$agent_answer_duration', 0], 1, 0]]
            ]]],
            'abandoned' => ['$sum' => ['$cond' => [['$ne' => ['$abandoned_time', '']], 1, 0]]],
        ])
            ->from('QueueWiseReport') // Replace with your MongoDB collection name
            ->groupBy(['date', 'queue']); // Group by date and queue

// Create ActiveDataProvider to handle pagination and sorting
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => Yii::$app->layoutHelper->get_per_page_record_count()],
        ]);

// Load and validate filters
        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

// Apply date range filtering
        if ($this->queue_started && $this->queue_ended) {
            $from = strtotime($this->queue_started . ' 00:00:00');
            $to = strtotime($this->queue_ended . ' 23:59:59');
            $query->andFilterWhere(['>=', 'queue_started', $from]);
            $query->andFilterWhere(['<=', 'queue_started', $to]);
        }

// Return the data provider
        return $dataProvider;*/

    }
}
