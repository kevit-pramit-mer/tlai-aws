<?php

namespace app\modules\ecosmob\supervisorabandonedcallreport\models;

use app\components\CommonHelper;
use app\modules\ecosmob\campaign\models\CampaignMappingUser;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * QueueAbandonedCallsSearch represents the model behind the search form of `app\modules\ecosmob\abandonedcallreport\models\QueueAbandonedCalls`.
 */
class QueueAbandonedCallsSearch extends QueueAbandonedCalls
{
    public $from;
    public $to;
    public $campaign_name;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['queue_name', 'queue_number', 'caller_id_number', 'call_status', 'start_time', 'end_time', 'hold_time', 'max_wait_reached', 'breakaway_digit_dialed', 'abandoned_time', 'abandoned_wait_time', 'break_away_wait_time'], 'safe'],
            [['queue_number', 'queue_name', 'start_time', 'end_time', 'from', 'to', 'campaign_name'], 'safe'],
            [
                'end_time',
                'compare',
                'compareAttribute' => 'start_time',
                'operator' => '>=',
                'message' => Yii::t('app', 'start_end_time_cmp')
            ],
            [['end_time'], 'required', 'when' => function ($model) {
                return $model->start_time != null;
            }, 'whenClient' => "function (attribute, value) {
                  return $('#queueabandonedcallssearch-start_time').val() != '' ;
              }", 'message' => Yii::t('app', 'end_time_req'), 'enableClientValidation' => true],
            [['start_time'], 'required', 'when' => function ($model) {
                return $model->end_time != null;
            }, 'whenClient' => "function (attribute, value) {
                  return $('#queueabandonedcallssearch-end_time').val() != '' ;
              }", 'message' => Yii::t('app', 'start_date_req'), 'enableClientValidation' => true],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
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
        $campaignList = [];
        $supervisorCamp = CampaignMappingUser::find()
            ->where(['supervisor_id' => Yii::$app->user->id])
            ->all();
        foreach ($supervisorCamp as $supervisorCamps) {
            $campaignList[] = $supervisorCamps['campaign_id'];
        }
        //if (isset($campaignList) && !empty($campaignList)) {
        $query = QueueAbandonedCalls::find()
            ->select([
                'ct_queue_abandoned_calls.*',
                'ccc.cmp_name as campaign_name',
            ])
            ->leftjoin('ct_queue_master qm', 'qm.qm_name = ct_queue_abandoned_calls.queue_name')
            ->leftjoin('ct_call_campaign ccc', 'qm.qm_id = ccc.cmp_queue_id')
            ->andWhere(['ccc.cmp_status' => 'Active'])
            ->andWhere(['ccc.cmp_id' => $campaignList])
            ->groupBy(['ct_queue_abandoned_calls.id', 'ccc.cmp_queue_id']);
        // }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['start_time' => SORT_DESC],
                'attributes' => [
                    'queue_name' => [
                        'asc' => ['queue_name' => SORT_ASC],
                        'desc' => ['queue_name' => SORT_DESC],
                    ],
                    'queue_number' => [
                        'asc' => ['queue_number' => SORT_ASC],
                        'desc' => ['queue_number' => SORT_DESC],
                    ],
                    'caller_id_number' => [
                        'asc' => ["SUBSTRING_INDEX(caller_id_number, '+', -1)" => SORT_ASC],
                        'desc' => ["SUBSTRING_INDEX(caller_id_number, '+', -1)" => SORT_DESC],
                    ],
                    'call_status' => [
                        'asc' => ['call_status' => SORT_ASC],
                        'desc' => ['call_status' => SORT_DESC],
                    ],
                    'start_time' => [
                        'asc' => ['start_time' => SORT_ASC],
                        'desc' => ['start_time' => SORT_DESC],
                    ],
                    'end_time' => [
                        'asc' => ['end_time' => SORT_ASC],
                        'desc' => ['end_time' => SORT_DESC],
                    ],
                    'hold_time' => [
                        'asc' => ['cast(hold_time as unsigned)' => SORT_ASC],
                        'desc' => ['cast(hold_time as unsigned)' => SORT_DESC],
                    ],
                ],
            ],
            'pagination' => ['pageSize' => Yii::$app->layoutHelper->get_per_page_record_count()],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        $query->andFilterWhere(['like', 'queue_name', $this->queue_name])
            ->andFilterWhere(['like', 'queue_number', $this->queue_number]);

        if ($this->start_time && $this->end_time) {
            $from = strtotime(CommonHelper::DtTots($this->start_time));
            $to = strtotime(CommonHelper::DtTots($this->end_time));
            $query->andFilterWhere(['>=', 'start_time', trim($from)]);
            $query->andFilterWhere(['<=', 'start_time', trim($to)]);
        }

        return $dataProvider;
    }
}
