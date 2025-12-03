<?php

namespace app\modules\ecosmob\calltimedistributionreport\models;

use app\modules\ecosmob\queue\models\QueueMaster;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use app\components\CommonHelper;


class CallTimeDistributionReportSearch extends CallTimeDistributionReport
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
                  return $('#calltimedistributionsearch-queue_started').val() != '' ;
              }", 'message' => Yii::t('app', 'to_date_req'), 'enableClientValidation' => true],
            [['queue_started'], 'required', 'when' => function ($model) {
                return $model->queue_ended != null;
            }, 'whenClient' => "function (attribute, value) {
                  return $('#calltimedistributionsearch-queue_ended').val() != '' ;
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
        $query = CallTimeDistributionReport::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);

        $this->load($params);

        if ($this->queue_started && $this->queue_ended) {
            $from = strtotime(CommonHelper::DtTots($this->queue_started));
            $to = strtotime(CommonHelper::DtTots($this->queue_ended));
            $query->andFilterWhere(['>=', 'queue_started', trim($from)]);
            $query->andFilterWhere(['<=', 'queue_ended', trim($to)]);
        }

        $summaryData = [];
        foreach ($dataProvider->getModels() as $model) {
            $queue = '';
            if(isset($model->qm_id)){
                $queue = $model->qm_id;
            }else{
                $queueMaster = QueueMaster::findOne(['qm_name' => $model->queue_name]);
                if(!empty($queueMaster)) {
                    $queue = $queueMaster->qm_id;
                }
            }
            if(!empty($queue)) {
                if (!isset($summaryData[$queue])) {
                    $summaryData[$queue] = [
                        'queue' => $model->queue_name,
                        'total_calls' => 0,
                        'total_hold_time' => 0,
                        'answer_call_30' => 0,
                        'drop_call_30' => 0,
                        'answer_call_60' => 0,
                        'drop_call_60' => 0,
                        'answer_call_3' => 0,
                        'drop_call_3' => 0,
                        'answer_call_5' => 0,
                        'drop_call_5' => 0,
                        'answer_call_5_plush' => 0,
                        'drop_call_5_plush' => 0,
                    ];
                }
                $summaryData[$queue]['total_calls']++;
                $summaryData[$queue]['total_hold_time'] += intval($model->hold_time);
                if ($model->agent_answer_duration != 0 && $model->hold_time > 0 && $model->hold_time <= 30) {
                    $summaryData[$queue]['answer_call_30']++;
                }
                if (!empty($model->abandoned_time) && $model->hold_time > 0 && $model->hold_time <= 30) {
                    $summaryData[$queue]['drop_call_30']++;
                }
                if ($model->agent_answer_duration != 0 && $model->hold_time > 30 && $model->hold_time <= 60) {
                    $summaryData[$queue]['answer_call_60']++;
                }
                if (!empty($model->abandoned_time) && $model->hold_time > 30 && $model->hold_time <= 60) {
                    $summaryData[$queue]['drop_call_60']++;
                }
                if ($model->agent_answer_duration != 0 && $model->hold_time > 60 && $model->hold_time <= 180) {
                    $summaryData[$queue]['answer_call_3']++;
                }
                if (!empty($model->abandoned_time) && $model->hold_time > 60 && $model->hold_time <= 180) {
                    $summaryData[$queue]['drop_call_3']++;
                }
                if ($model->agent_answer_duration != 0 && $model->hold_time > 180 && $model->hold_time <= 300) {
                    $summaryData[$queue]['answer_call_5']++;
                }
                if (!empty($model->abandoned_time) && $model->hold_time > 180 && $model->hold_time <= 300) {
                    $summaryData[$queue]['drop_call_5']++;
                }
                if ($model->agent_answer_duration != 0 && $model->hold_time > 300) {
                    $summaryData[$queue]['answer_call_5_plush']++;
                }
                if (!empty($model->abandoned_time) && $model->hold_time > 300) {
                    $summaryData[$queue]['drop_call_5_plush']++;
                }
            }
        }

        $summaryData = $this->qm_id ? array_filter($summaryData, fn($key) => in_array($key, $this->qm_id), ARRAY_FILTER_USE_KEY) : $summaryData;

        foreach ($summaryData as &$summaryItem) {
            $summaryItem['avg_waiting_time'] = (($summaryItem['total_calls'] > 0 && $summaryItem['total_hold_time'] > 0) ? $summaryItem['total_hold_time'] / $summaryItem['total_calls'] : 0);
        }

        $summaryDataProvider = new ArrayDataProvider([
            'allModels' => array_values($summaryData),
            'sort' => [
                'defaultOrder' => ["queue" => SORT_ASC],
                'attributes' => [
                    'queue' => [
                        'asc' => ['queue' => SORT_ASC],
                        'desc' => ['queue' => SORT_DESC],
                    ],
                    'total_calls' => [
                        'asc' => ['total_calls' => SORT_ASC],
                        'desc' => ['total_calls' => SORT_DESC],
                    ],
                    'avg_waiting_time' => [
                        'asc' => ['avg_waiting_time' => SORT_ASC],
                        'desc' => ['avg_waiting_time' => SORT_DESC],
                    ],
                    'answer_call_30' => [
                        'asc' => ['answer_call_30' => SORT_ASC],
                        'desc' => ['answer_call_30' => SORT_DESC],
                    ],
                    'drop_call_30' => [
                        'asc' => ['drop_call_30' => SORT_ASC],
                        'desc' => ['drop_call_30' => SORT_DESC],
                    ],
                    'answer_call_60' => [
                        'asc' => ['answer_call_60' => SORT_ASC],
                        'desc' => ['answer_call_60' => SORT_DESC],
                    ],
                    'drop_call_60' => [
                        'asc' => ['drop_call_60' => SORT_ASC],
                        'desc' => ['drop_call_60' => SORT_DESC],
                    ],
                    'answer_call_3' => [
                        'asc' => ['answer_call_3' => SORT_ASC],
                        'desc' => ['answer_call_3' => SORT_DESC],
                    ],
                    'drop_call_3' => [
                        'asc' => ['drop_call_3' => SORT_ASC],
                        'desc' => ['drop_call_3' => SORT_DESC],
                    ],
                    'answer_call_5' => [
                        'asc' => ['answer_call_5' => SORT_ASC],
                        'desc' => ['answer_call_5' => SORT_DESC],
                    ],
                    'drop_call_5' => [
                        'asc' => ['drop_call_5' => SORT_ASC],
                        'desc' => ['drop_call_5' => SORT_DESC],
                    ],
                    'answer_call_5_plush' => [
                        'asc' => ['answer_call_5_plush' => SORT_ASC],
                        'desc' => ['answer_call_5_plush' => SORT_DESC],
                    ],
                    'drop_call_5_plush' => [
                        'asc' => ['drop_call_5_plush' => SORT_ASC],
                        'desc' => ['drop_call_5_plush' => SORT_DESC],
                    ],
                ],
            ],
            'pagination' => ['pageSize' => Yii::$app->layoutHelper->get_per_page_record_count()],
        ]);

        return $summaryDataProvider;
    }

}
