<?php

namespace app\modules\ecosmob\dialplan\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * OutboundDialPlansDetailsSearch represents the model behind the search form of `app\modules\ecosmob\dialplan\models\OutboundDialPlansDetails`.
 */
class OutboundDialPlansDetailsSearch extends OutboundDialPlansDetails
{

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['odpd_id', 'trunk_grp_id'], 'integer'],
            [['odpd_prefix_match_string', 'odpd_strip_prefix', 'odpd_add_prefix'], 'safe'],
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
    public function searchAll($params)
    {
        return $this->search($params, '');
    }

    private function search($params, $string)
    {
        $query = OutboundDialPlansDetails::find();

        $primaryKey = OutboundDialPlansDetails::primaryKey()[0];

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
            'odpd_id' => $this->odpd_id,
            'trunk_grp_id' => $this->trunk_grp_id,
        ]);

        if ($string == "default") {
            $query->andFilterWhere(['like', 'odpd_prefix_match_string', '.*'])
                ->andFilterWhere(['like', 'odpd_strip_prefix', $this->odpd_strip_prefix])
                ->andFilterWhere(['like', 'odpd_add_prefix', $this->odpd_add_prefix]);
        } else {
            $query->andFilterWhere(['like', 'odpd_prefix_match_string', $this->odpd_prefix_match_string])
                ->andFilterWhere(['like', 'odpd_strip_prefix', $this->odpd_strip_prefix])
                ->andFilterWhere(['like', 'odpd_add_prefix', $this->odpd_add_prefix])
                ->andFilterWhere(['<>', 'odpd_prefix_match_string', '.*']);
        }

        return $dataProvider;
    }

    /**
     * @param $params
     *
     * @return ActiveDataProvider
     */
    public function searchDefault($params)
    {
        return $this->search($params, 'default');
    }
}
