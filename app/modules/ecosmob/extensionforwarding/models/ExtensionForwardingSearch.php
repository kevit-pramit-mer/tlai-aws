<?php

namespace app\modules\ecosmob\extensionforwarding\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\ecosmob\extensionforwarding\models\ExtensionForwarding;

/**
 * ExtensionForwardingSearch represents the model behind the search form of `app\modules\ecosmob\extensionforwarding\models\ExtensionForwarding`.
 */
class ExtensionForwardingSearch extends ExtensionForwarding
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ef_id'], 'integer'],
            [['ef_extension', 'ef_unconditional_type', 'ef_unconditional_num', 'ef_holiday_type', 'ef_holiday', 'ef_holiday_num', 'ef_weekoff_type', 'ef_weekoff', 'ef_weekoff_num', 'ef_shift_type', 'ef_shift', 'ef_shift_num', 'ef_universal_type', 'ef_universal_num', 'ef_no_answer_type', 'ef_no_answer_num', 'ef_busy_type', 'ef_busy_num', 'ef_unavailable_type', 'ef_unavailable_num'], 'safe'],
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
        $query = ExtensionForwarding::find();

        $primaryKey = ExtensionForwarding::primaryKey()[0];

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
            'ef_id' => $this->ef_id,
        ]);

        $query->andFilterWhere(['like', 'ef_extension', $this->ef_extension])
            ->andFilterWhere(['like', 'ef_unconditional_type', $this->ef_unconditional_type])
            ->andFilterWhere(['like', 'ef_unconditional_num', $this->ef_unconditional_num])
            ->andFilterWhere(['like', 'ef_holiday_type', $this->ef_holiday_type])
            ->andFilterWhere(['like', 'ef_holiday', $this->ef_holiday])
            ->andFilterWhere(['like', 'ef_holiday_num', $this->ef_holiday_num])
            ->andFilterWhere(['like', 'ef_weekoff_type', $this->ef_weekoff_type])
            ->andFilterWhere(['like', 'ef_weekoff', $this->ef_weekoff])
            ->andFilterWhere(['like', 'ef_weekoff_num', $this->ef_weekoff_num])
            ->andFilterWhere(['like', 'ef_shift_type', $this->ef_shift_type])
            ->andFilterWhere(['like', 'ef_shift', $this->ef_shift])
            ->andFilterWhere(['like', 'ef_shift_num', $this->ef_shift_num])
            ->andFilterWhere(['like', 'ef_universal_type', $this->ef_universal_type])
            ->andFilterWhere(['like', 'ef_universal_num', $this->ef_universal_num])
            ->andFilterWhere(['like', 'ef_no_answer_type', $this->ef_no_answer_type])
            ->andFilterWhere(['like', 'ef_no_answer_num', $this->ef_no_answer_num])
            ->andFilterWhere(['like', 'ef_busy_type', $this->ef_busy_type])
            ->andFilterWhere(['like', 'ef_busy_num', $this->ef_busy_num])
            ->andFilterWhere(['like', 'ef_unavailable_type', $this->ef_unavailable_type])
            ->andFilterWhere(['like', 'ef_unavailable_num', $this->ef_unavailable_num]);


        return $dataProvider;
    }
}
