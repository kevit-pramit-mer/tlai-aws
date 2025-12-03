<?php

namespace app\modules\ecosmob\fraudcall\models;

use Yii;
use yii\base\InvalidArgumentException;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * FraudCallDetectionSearch represents the model behind the search form of `app\modules\ecosmob\fraudcall\models\FraudCallDetection`.
 */
class FraudCallDetectionSearch extends FraudCallDetection
{

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fcd_id', 'fcd_call_period'], 'integer'],
            [['fcd_rule_name', 'fcd_destination_prefix'], 'string', 'max' => 30],
            [['fcd_rule_name', 'fcd_destination_prefix', 'fcd_call_duration', 'fcd_notify_email', 'blocked_by'], 'safe'],
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
        $query = FraudCallDetection::find();

        $primaryKey = FraudCallDetection::primaryKey()[0];

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
            'fcd_id' => $this->fcd_id,
            'fcd_call_period' => $this->fcd_call_period,
            'blocked_by' => $this->blocked_by,
        ]);

        $query->andFilterWhere(['like', 'fcd_rule_name', $this->fcd_rule_name])
            ->andFilterWhere(['like', 'fcd_destination_prefix', $this->fcd_destination_prefix])
            ->andFilterWhere(['like', 'fcd_call_duration', $this->fcd_call_duration])
            ->andFilterWhere(['like', 'fcd_notify_email', $this->fcd_notify_email]);

        return $dataProvider;
    }
}
