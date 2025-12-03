<?php

namespace app\modules\ecosmob\weekoff\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\base\InvalidArgumentException;

/**
 * WeekOffSearch represents the model behind the search form of `app\modules\ecosmob\weekoff\models\WeekOff`.
 */
class WeekOffSearch extends WeekOff
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['wo_id'], 'integer'],
            [['wo_day', 'wo_start_time', 'wo_end_time', 'created_date', 'updated_date'], 'safe'],
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
     * @throws InvalidArgumentException
     */
    public function search($params)
    {
        $query = WeekOff::find();

        $primaryKey = WeekOff::primaryKey()[0];

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => [$primaryKey => SORT_DESC]],
            'pagination' => ['pageSize' => 10],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'wo_id' => $this->wo_id,
        ]);

        $query->andFilterWhere(['like', 'wo_day', $this->wo_day])
            ->andFilterWhere(['like', 'wo_start_time', $this->wo_start_time])
            ->andFilterWhere(['like', 'wo_end_time', $this->wo_end_time])
            ->andFilterWhere(['like', 'created_date', $this->created_date])
            ->andFilterWhere(['like', 'updated_date', $this->updated_date]);

        return $dataProvider;
    }
}
