<?php

namespace app\modules\ecosmob\dispositionType\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\ecosmob\dispositionType\models\DispositionType;

/**
 * DispositionTypeSearch represents the model behind the search form of `app\modules\ecosmob\dispositionType\models\DispositionType`.
 */
class DispositionTypeSearch extends DispositionType
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ds_type_id', 'ds_id'], 'integer'],
            [['ds_type'], 'safe'],
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
        $query = DispositionType::find()->where(['NOT IN', 'is_default', [1, 2]]);;

        $primaryKey = DispositionType::primaryKey()[0];

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
            'ds_type_id' => $this->ds_type_id,
            'ds_id' => $this->ds_id,
        ]);

        $query->andFilterWhere(['like', 'ds_type', $this->ds_type]);

        return $dataProvider;
    }
}
