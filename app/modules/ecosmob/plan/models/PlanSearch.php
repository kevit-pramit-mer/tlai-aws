<?php

namespace app\modules\ecosmob\plan\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\ecosmob\plan\models\Plan;

/**
 * PlanSearch represents the model behind the search form of `app\modules\ecosmob\plan\models\Plan`.
 */
class PlanSearch extends Plan
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pl_id'], 'integer'],
            [['pl_name', 'pl_holiday', 'pl_week_off', 'pl_bargain', 'pl_dnd', 'pl_park', 'pl_transfer', 'pl_call_record', 'pl_white_list', 'pl_black_list', 'pl_caller_id_block', 'pl_universal_forward', 'pl_no_ans_forward', 'pl_busy_forward', 'pl_timebase_forward', 'pl_selective_forward', 'pl_shift_forward', 'pl_unavailable_forward', 'pl_redial', 'pl_call_return', 'pl_busy_callback', 'created_date', 'updated_date'], 'safe'],
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
        $query = Plan::find();

        $primaryKey = Plan::primaryKey()[0];

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
            'pl_id' => $this->pl_id,
        ]);

        $query->andFilterWhere(['like', 'pl_name', $this->pl_name])
            ->andFilterWhere(['like', 'pl_holiday', $this->pl_holiday])
            ->andFilterWhere(['like', 'pl_week_off', $this->pl_week_off])
            ->andFilterWhere(['like', 'pl_bargain', $this->pl_bargain])
            ->andFilterWhere(['like', 'pl_dnd', $this->pl_dnd])
            ->andFilterWhere(['like', 'pl_park', $this->pl_park])
            ->andFilterWhere(['like', 'pl_transfer', $this->pl_transfer])
            ->andFilterWhere(['like', 'pl_call_record', $this->pl_call_record])
            ->andFilterWhere(['like', 'pl_white_list', $this->pl_white_list])
            ->andFilterWhere(['like', 'pl_black_list', $this->pl_black_list])
            ->andFilterWhere(['like', 'pl_caller_id_block', $this->pl_caller_id_block])
            ->andFilterWhere(['like', 'pl_universal_forward', $this->pl_universal_forward])
            ->andFilterWhere(['like', 'pl_no_ans_forward', $this->pl_no_ans_forward])
            ->andFilterWhere(['like', 'pl_busy_forward', $this->pl_busy_forward])
            ->andFilterWhere(['like', 'pl_timebase_forward', $this->pl_timebase_forward])
            ->andFilterWhere(['like', 'pl_selective_forward', $this->pl_selective_forward])
            ->andFilterWhere(['like', 'pl_shift_forward', $this->pl_shift_forward])
            ->andFilterWhere(['like', 'pl_unavailable_forward', $this->pl_unavailable_forward])
            ->andFilterWhere(['like', 'pl_redial', $this->pl_redial])
            ->andFilterWhere(['like', 'pl_call_return', $this->pl_call_return])
            ->andFilterWhere(['like', 'pl_busy_callback', $this->pl_busy_callback])
            ->andFilterWhere(['like', 'created_date', $this->created_date])
            ->andFilterWhere(['like', 'updated_date', $this->updated_date]);

        return $dataProvider;
    }
}
