<?php

namespace app\modules\ecosmob\parkinglot\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * ParkingLotSearch represents the model behind the search form of `app\modules\ecosmob\parkinglot\models`.
 */
class ParkingLotSearch extends ParkingLot
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'slot_qty', 'grp_id', 'parking_time', 'return_to_origin', 'call_back_ring_time', 'destination_id'], 'integer'],
            [['name'], 'string', 'max' => 32],
            [['park_ext'], 'number'],
            [['name', 'park_ext', 'park_pos_start', 'park_pos_end', 'park_moh', 'destination_type', 'status', 'created_at', 'updated_at'], 'safe'],
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
        $query = ParkingLot::find();

        $primaryKey = ParkingLot::primaryKey()[0];

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC],
                'attributes' => [
                    'id' => [
                        'asc' => ['id' => SORT_ASC],
                        'desc' => ['id' => SORT_DESC],
                    ],
                    'name' => [
                        'asc' => ['name' => SORT_ASC],
                        'desc' => ['name' => SORT_DESC],
                    ],
                    'park_ext' => [
                        'asc' => ['cast(park_ext as unsigned)' => SORT_ASC],
                        'desc' => ['cast(park_ext as unsigned)' => SORT_DESC],
                    ],
                    'park_pos_start' => [
                        'asc' => ['cast(park_pos_start as unsigned)' => SORT_ASC],
                        'desc' => ['cast(park_pos_start as unsigned)' => SORT_DESC],
                    ],
                    'return_to_origin' => [
                        'asc' => ['return_to_origin' => SORT_ASC],
                        'desc' => ['return_to_origin' => SORT_DESC]
                    ],
                    'status' => [
                        'asc' => ['status' => SORT_ASC],
                        'desc' => ['status' => SORT_DESC],
                    ],
                ],
            ],
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
            'id' => $this->id,
            'slot_qty' => $this->slot_qty,
            'grp_id' => $this->grp_id,
            'parking_time' => $this->parking_time,
            'return_to_origin' => $this->return_to_origin,
            'call_back_ring_time' => $this->call_back_ring_time,
            'destination_id' => $this->destination_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'park_ext', $this->park_ext])
            ->andFilterWhere(['like', 'park_pos_start', $this->park_pos_start])
            ->andFilterWhere(['like', 'park_pos_end', $this->park_pos_end])
            ->andFilterWhere(['like', 'park_moh', $this->park_moh])
            ->andFilterWhere(['like', 'destination_type', $this->destination_type]);

        return $dataProvider;
    }
}
