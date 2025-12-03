<?php

namespace app\modules\ecosmob\agent\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\ecosmob\agent\models\Agent;

/**
 * AgentSearch represents the model behind the search form of `app\modules\ecosmob\agent\models\Agent`.
 */
class AgentSearch extends Agent
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'system', 'uuid', 'type', 'contact', 'status', 'state'], 'safe'],
            [['max_no_answer', 'wrap_up_time', 'reject_delay_time', 'busy_delay_time', 'no_answer_delay_time', 'last_bridge_start', 'last_bridge_end', 'last_offered_call', 'last_status_change', 'no_answer_count', 'calls_answered', 'talk_time', 'ready_time', 'reject_call_count'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
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
        $query = Agent::find();

        $primaryKey = Agent::primaryKey()[0];

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => [$primaryKey => SORT_DESC]],
            'pagination' => ['pageSize' => Yii::$app->layoutHelper->get_per_page_record_count()],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'max_no_answer' => $this->max_no_answer,
            'wrap_up_time' => $this->wrap_up_time,
            'reject_delay_time' => $this->reject_delay_time,
            'busy_delay_time' => $this->busy_delay_time,
            'no_answer_delay_time' => $this->no_answer_delay_time,
            'last_bridge_start' => $this->last_bridge_start,
            'last_bridge_end' => $this->last_bridge_end,
            'last_offered_call' => $this->last_offered_call,
            'last_status_change' => $this->last_status_change,
            'no_answer_count' => $this->no_answer_count,
            'calls_answered' => $this->calls_answered,
            'talk_time' => $this->talk_time,
            'ready_time' => $this->ready_time,
            'reject_call_count' => $this->reject_call_count,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'system', $this->system])
            ->andFilterWhere(['like', 'uuid', $this->uuid])
            ->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'contact', $this->contact])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'state', $this->state]);

        return $dataProvider;
    }

}
