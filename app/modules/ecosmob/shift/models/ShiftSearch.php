<?php

namespace app\modules\ecosmob\shift\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * ShiftSearch represents the model behind the search form of `app\modules\ecosmob\shift\models\Shift`.
 */
class ShiftSearch extends Shift
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sft_id'], 'integer'],
            [['sft_name', 'sft_start_time', 'sft_end_time', 'created_date', 'updated_date'], 'safe'],
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
        $query = Shift::find();

        $primaryKey = Shift::primaryKey()[0];

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => [$primaryKey => SORT_DESC]],
            'pagination' => ['pageSize' => Yii::$app->layoutHelper->get_per_page_record_count()],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'sft_id' => $this->sft_id,
        ]);

        $query->andFilterWhere(['like', 'sft_name', $this->sft_name])
            ->andFilterWhere(['like', 'sft_start_time', $this->sft_start_time])
            ->andFilterWhere(['like', 'sft_end_time', $this->sft_end_time])
            ->andFilterWhere(['like', 'created_date', $this->created_date])
            ->andFilterWhere(['like', 'updated_date', $this->updated_date]);

        return $dataProvider;
    }
}
