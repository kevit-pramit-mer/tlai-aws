<?php

namespace app\modules\ecosmob\callcampaign\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\ecosmob\callcampaign\models\CallCampaign;

/**
 * CallCampaignSearch represents the model behind the search form of `app\modules\ecosmob\callcampaign\models\CallCampaign`.
 */
class CallCampaignSearch extends CallCampaign
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cmp_id', 'cmp_caller_id'], 'integer'],
            [['cmp_name', 'cmp_type', 'cmp_timezone', 'cmp_status', 'cmp_disposition'], 'safe'],
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
        $query = CallCampaign::find();

        $primaryKey = CallCampaign::primaryKey()[0];

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
            'cmp_id' => $this->cmp_id,
            'cmp_caller_id' => $this->cmp_caller_id,
            'cmp_timezone' => $this->cmp_timezone,
        ]);

        $query->andFilterWhere(['like', 'cmp_name', $this->cmp_name])
            ->andFilterWhere(['like', 'cmp_type', $this->cmp_type])
            ->andFilterWhere(['like', 'cmp_status', $this->cmp_status])
            ->andFilterWhere(['like', 'cmp_disposition', $this->cmp_disposition]);

        return $dataProvider;
    }
}
