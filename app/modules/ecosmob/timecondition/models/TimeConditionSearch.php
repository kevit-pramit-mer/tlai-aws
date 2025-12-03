<?php

namespace app\modules\ecosmob\timecondition\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;


class TimeConditionSearch extends TimeCondition
{

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [
                [
                    'tc_name',
                    'tc_description',
                    'tc_start_time',
                    'tc_end_time',
                    'tc_start_day',
                    'tc_end_day',
                    'tc_start_date',
                    'tc_end_date',
                    'tc_start_month',
                    'tc_end_month',
                    'created_date',
                    'updated_date'
                ],
                'safe'
            ],
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
     * @param $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = TimeCondition::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['tc_id' => SORT_DESC]],
            'pagination' => ['pageSize' => Yii::$app->layoutHelper->get_per_page_record_count()],
        ]);

        $this->load($params);

        $query->andFilterWhere(['like', 'tc_name', $this->tc_name]);

        return $dataProvider;
    }
}
