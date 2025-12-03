<?php

namespace app\modules\ecosmob\activecalls\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\ecosmob\activecalls\models\ActiveCalls;

/**
 * ActiveCallsSearch represents the model behind the search form of `app\modules\ecosmob\activecalls\models\ActiveCalls`.
 */
class ActiveCallsSearch extends ActiveCalls
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['active_id', 'agent'], 'integer'],
            [['caller_id', 'did', 'destination_number', 'uuid', 'status', 'queue', 'call_queue_time', 'call_start_time', 'call_agent_time', 'campaign_id'], 'safe'],
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
        $query = ActiveCalls::find();

        $primaryKey = ActiveCalls::primaryKey()[0];

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
        /*$query->andFilterWhere([
            'active_id' => $this->active_id,
            'agent' => $this->agent,
            'call_queue_time' => $this->call_queue_time,
            'call_start_time' => $this->call_start_time,
            'call_agent_time' => $this->call_agent_time,
        ]);*/

       $cmp_data = isset($params['camp_id'])?$params['camp_id']:'';
        if(isset($params['camp_id'])) {
            $query->andFilterWhere(['like', 'caller_id', $this->caller_id])
                ->andFilterWhere(['like', 'did', $this->did])
                ->andFilterWhere(['like', 'destination_number', $this->destination_number])
                ->andFilterWhere(['like', 'uuid', $this->uuid])
                ->andFilterWhere(['like', 'status', $this->status])
                ->andFilterWhere(['like', 'queue', $this->queue])
                ->andFilterWhere(['like', 'campaign_id', $cmp_data]);
        }

        return $dataProvider;
    }
}
