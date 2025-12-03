<?php

namespace app\modules\ecosmob\whitelist\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * WhiteListSearch represents the model behind the search form of `app\modules\ecosmob\whitelist\models\WhiteList`.
 */
class WhiteListSearch extends WhiteList
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['wl_id'], 'integer'],
            [['wl_number', 'wl_description', 'updated_date', 'created_date'], 'safe'],
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
        $query = WhiteList::find();

        $primaryKey = WhiteList::primaryKey()[0];

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
            'wl_id' => $this->wl_id,
        ]);

        $query->andFilterWhere(['like', 'wl_number', $this->wl_number])
            ->andFilterWhere(['like', 'wl_description', $this->wl_description])
            ->andFilterWhere(['like', 'updated_date', $this->updated_date])
            ->andFilterWhere(['like', 'created_date', $this->created_date]);

        return $dataProvider;
    }
}
