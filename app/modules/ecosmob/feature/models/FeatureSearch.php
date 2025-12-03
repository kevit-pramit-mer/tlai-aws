<?php

namespace app\modules\ecosmob\feature\models;

use Yii;
use yii\base\InvalidArgumentException;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * FeatureSearch represents the model behind the search form of `app\modules\ecosmob\feature\models\Feature`.
 */
class FeatureSearch extends Feature
{

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['feature_id'], 'integer'],
            [['feature_name', 'feature_code', 'feature_desc'], 'safe'],
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
        $query = Feature::find()->where(['NOT IN', 'feature_code', ['*93', '*94', '*95', '*96', '*97', '*98', '*99', '*85', '*86']]);;

        $primaryKey = Feature::primaryKey()[0];

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
            'feature_id' => $this->feature_id,
        ]);

        $query->andFilterWhere(['like', 'feature_name', $this->feature_name])
            ->andFilterWhere(['like', 'feature_code', $this->feature_code])
            ->andFilterWhere(['like', 'feature_desc', $this->feature_desc]);

        return $dataProvider;
    }
}
