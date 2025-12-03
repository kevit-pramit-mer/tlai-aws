<?php

namespace app\modules\ecosmob\blacklist\models;

use Yii;
use yii\base\InvalidArgumentException;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * BlackListSearch represents the model behind the search form of `app\modules\ecosmob\blacklist\models\BlackList`.
 */
class BlackListSearch extends BlackList
{

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['bl_id'], 'integer'],
            [['bl_number', 'bl_type', 'bl_reason', 'updated_date', 'created_date'], 'safe'],
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
        $query = BlackList::find();

        $primaryKey = BlackList::primaryKey()[0];

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
            'bl_id' => $this->bl_id,
        ]);

        $query->andFilterWhere(['like', 'bl_number', $this->bl_number])
            ->andFilterWhere(['like', 'bl_type', $this->bl_type])
            ->andFilterWhere(['like', 'bl_reason', $this->bl_reason])
            ->andFilterWhere(['like', 'updated_date', $this->updated_date])
            ->andFilterWhere(['like', 'created_date', $this->created_date]);

        return $dataProvider;
    }
}
