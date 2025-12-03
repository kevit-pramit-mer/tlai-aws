<?php

namespace app\modules\ecosmob\autoattendant\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * AutoAttendantDetailSearch represents the model behind the search form about `app\modules\ecosmob\autoattendant\models\AutoAttendantDetail`.
 */
class AutoAttendantDetailSearch extends AutoAttendantDetail
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['aad_id', 'aam_id'], 'integer'],
            [['aad_digit', 'aad_action', 'aad_action_desc', 'aad_param'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
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
        $query = AutoAttendantDetail::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => Yii::$app->layoutHelper->get_per_page_record_count()],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'aad_id' => $this->aad_id,
            'aam_id' => $this->aam_id,
        ]);

        $query->andFilterWhere(['like', 'aad_digit', $this->aad_digit])
            ->andFilterWhere(['like', 'aad_action', $this->aad_action])
            ->andFilterWhere(['like', 'aad_action_desc', $this->aad_action_desc])
            ->andFilterWhere(['like', 'aad_param', $this->aad_param]);

        return $dataProvider;
    }
}
