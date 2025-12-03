<?php

namespace app\modules\ecosmob\speeddial\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * ExtensionSpeeddialSearch represents the model behind the search form of `app\modules\ecosmob\speeddial\models\ExtensionSpeeddial`.
 */
class ExtensionSpeeddialSearch extends ExtensionSpeeddial
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['es_id'], 'integer'],
            [['es_extension', 'es_0', 'es_1', 'es_2', 'es_3', 'es_4', 'es_5', 'es_6', 'es_7', 'es_8', 'es_9'], 'safe'],
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
        $query = ExtensionSpeeddial::find();

        $primaryKey = ExtensionSpeeddial::primaryKey()[0];

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
            'es_id' => $this->es_id,
        ]);

        $query->andFilterWhere(['like', 'es_extension', $this->es_extension])
            ->andFilterWhere(['like', 'es_0', $this->es_0])
            ->andFilterWhere(['like', 'es_1', $this->es_1])
            ->andFilterWhere(['like', 'es_2', $this->es_2])
            ->andFilterWhere(['like', 'es_3', $this->es_3])
            ->andFilterWhere(['like', 'es_4', $this->es_4])
            ->andFilterWhere(['like', 'es_5', $this->es_5])
            ->andFilterWhere(['like', 'es_6', $this->es_6])
            ->andFilterWhere(['like', 'es_7', $this->es_7])
            ->andFilterWhere(['like', 'es_8', $this->es_8])
            ->andFilterWhere(['like', 'es_9', $this->es_9]);

        return $dataProvider;
    }
}
