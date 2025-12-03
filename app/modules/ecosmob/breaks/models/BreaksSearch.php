<?php

namespace app\modules\ecosmob\breaks\models;

use Yii;
use yii\base\InvalidArgumentException;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * BreaksSearch represents the model behind the search form of `app\modules\ecosmob\breaks\models\Breaks`.
 */
class BreaksSearch extends Breaks
{

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['br_id'], 'integer'],
            [['br_reason'], 'string', 'max' => 50],
            [['br_reason', 'br_description'], 'safe'],
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
        $query = Breaks::find();

        $primaryKey = Breaks::primaryKey()[0];

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
            'br_id' => $this->br_id,
        ]);

        $query->andFilterWhere(['like', 'br_reason', $this->br_reason])
            ->andFilterWhere(['like', 'br_description', $this->br_description]);

        return $dataProvider;
    }
}
