<?php

namespace app\modules\ecosmob\fail2ban\models;

use Yii;
use yii\base\InvalidArgumentException;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * IpTableSearch represents the model behind the search form of `app\modules\ecosmob\iptable\models\IpTable`.
 */
class CdrSearch extends Cdr
{

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['bw_rule_value', 'ports', 'protocol'], 'string', 'max' => 30],
            [['bw_rule_value', 'ports', 'protocol'], 'safe'],
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
        $query = Cdr::find();

        $primaryKey = Cdr::primaryKey()[0];

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

        $query->andFilterWhere(['like', 'remove', '0']);

        $query->andFilterWhere(['like', 'id', $this->id])
            ->andFilterWhere(['like', 'bw_rule_value', $this->bw_rule_value])
            ->andFilterWhere(['like', 'ports', $this->ports])
            ->andFilterWhere(['like', 'protocol', $this->protocol])
            ->andFilterWhere(['like', 'jail', $this->jail])
            ->andFilterWhere(['like', 'hostname', $this->hostname])
            ->andFilterWhere(['like', 'country', $this->country])
            ->andFilterWhere(['like', 'bw_added_by', $this->bw_added_by]);

        return $dataProvider;
    }
}
