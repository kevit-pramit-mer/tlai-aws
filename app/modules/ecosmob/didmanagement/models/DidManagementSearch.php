<?php

namespace app\modules\ecosmob\didmanagement\models;

use Yii;
use yii\base\InvalidArgumentException;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * DidManagementSearch represents the model behind the search form of `app\modules\ecosmob\didmanagement\models\DidManagement`.
 */
class DidManagementSearch extends DidManagement
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['did_id'], 'integer'],
            [['did_number', 'did_description', 'did_status', 'created_date', 'updated_date'], 'safe'],
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
        $query = DidManagement::find();

        $primaryKey = DidManagement::primaryKey()[0];

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['did_id' => SORT_DESC],
                'attributes' => [
                    'did_id' => [
                        'asc' => ['did_id' => SORT_ASC],
                        'desc' => ['did_id' => SORT_DESC],
                    ],
                    'did_number' => [
                        'asc' => ['did_number' => SORT_ASC],
                        'desc' => ['did_number' => SORT_DESC],
                    ],
                    'did_status' => [
                        'asc' => ['did_status' => SORT_ASC],
                        'desc' => ['did_status' => SORT_DESC],
                    ],
                ],
            ],
            'pagination' => ['pageSize' => Yii::$app->layoutHelper->get_per_page_record_count()],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'did_id' => $this->did_id,
        ]);

        $query->andFilterWhere(['like', 'did_number', $this->did_number])
            ->andFilterWhere(['like', 'did_description', $this->did_description])
            ->andFilterWhere(['like', 'did_status', $this->did_status])
            ->andFilterWhere(['like', 'created_date', $this->created_date])
            ->andFilterWhere(['like', 'updated_date', $this->updated_date]);

        return $dataProvider;
    }
}
