<?php

namespace app\modules\ecosmob\callrecordings\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\ecosmob\callrecordings\models\CallRecordings;

/**
 * CallRecordingsSearch represents the model behind the search form of `app\modules\ecosmob\callrecordings\models\CallRecordings`.
 */
class CallRecordingsSearch extends CallRecordings
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cr_id'], 'integer'],
            [['cr_name', 'cr_files', 'cr_date'], 'safe'],
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
        $query = CallRecordings::find();

        $primaryKey = CallRecordings::primaryKey()[0];

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
            'cr_id' => $this->cr_id,
            'cr_date' => $this->cr_date,
        ]);

        $query->andFilterWhere(['like', 'cr_name', $this->cr_name])
            ->andFilterWhere(['like', 'cr_files', $this->cr_files]);

        return $dataProvider;
    }
}
