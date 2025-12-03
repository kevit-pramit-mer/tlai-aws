<?php

namespace app\modules\ecosmob\campaign\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * CampaignSearch represents the model behind the search form of `app\modules\ecosmob\campaign\models\Campaign`.
 */
class CampaignSearch extends Campaign
{
    public $cmp_disp;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cmp_id', 'cmp_caller_id'], 'integer'],
            [['cmp_name', 'cmp_type', 'cmp_disp', 'cmp_timezone', 'cmp_status'], 'safe'],
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
        $query = Campaign::find();

        $primaryKey = Campaign::primaryKey()[0];

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
            'cmp_id' => $this->cmp_id,
            'cmp_caller_id' => $this->cmp_caller_id,
        ]);

        $query->andFilterWhere(['like', 'cmp_name', $this->cmp_name])
            ->andFilterWhere(['like', 'cmp_type', $this->cmp_type])
            ->andFilterWhere(['like', 'cmp_disposition', $this->cmp_disp])
            ->andFilterWhere(['like', 'cmp_timezone', $this->cmp_timezone])
            ->andFilterWhere(['like', 'cmp_status', $this->cmp_status]);

        return $dataProvider;
    }

}
