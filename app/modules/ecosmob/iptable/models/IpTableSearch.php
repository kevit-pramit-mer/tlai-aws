<?php

namespace app\modules\ecosmob\iptable\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\base\InvalidArgumentException;

/**
 * IpTableSearch represents the model behind the search form of `app\modules\ecosmob\iptable\models\IpTable`.
 */
class IpTableSearch extends IpTable
{

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['it_id', 'it_port'], 'integer'],
            [['it_source', 'it_destination', 'it_port', 'it_protocol', 'it_service', 'it_action', 'it_direction'], 'string', 'max' => 30],
            [['it_source', 'it_destination', 'it_port', 'it_protocol', 'it_service', 'it_action', 'it_direction'], 'safe'],
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
        $query = IpTable::find();

        $primaryKey = IpTable::primaryKey()[0];

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
            'it_id' => $this->it_id,
        ]);

        $query->andFilterWhere(['like', 'it_source', $this->it_source])
            ->andFilterWhere(['like', 'it_destination', $this->it_destination])
            ->andFilterWhere(['like', 'it_port', $this->it_port])
            ->andFilterWhere(['like', 'it_protocol', $this->it_protocol])
            ->andFilterWhere(['like', 'it_service', $this->it_service])
            ->andFilterWhere(['like', 'it_action', $this->it_action])
            ->andFilterWhere(['like', 'it_direction', $this->it_direction]);

        return $dataProvider;
    }
}
