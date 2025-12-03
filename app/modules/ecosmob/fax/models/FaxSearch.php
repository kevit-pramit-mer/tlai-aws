<?php

namespace app\modules\ecosmob\fax\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * FaxSearch represents the model behind the search form of `app\modules\ecosmob\fax\models\Fax`.
 */
class FaxSearch extends Fax
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['fax_name', 'fax_destination', 'fax_failure', 'fax_extension'], 'safe'],
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
        $query = Fax::find();

        $primaryKey = Fax::primaryKey()[0];

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
            'id' => $this->id,
        ]);

        $query->andFilterWhere(['like', 'fax_name', $this->fax_name])
            ->andFilterWhere(['like', 'fax_destination', $this->fax_destination])
            ->andFilterWhere(['like', 'fax_failure', $this->fax_failure]);

        return $dataProvider;
    }
}
