<?php

namespace app\modules\ecosmob\jobs\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * JobSearch represents the model behind the search form of `app\modules\ecosmob\jobs\models\Job`.
 */
class JobSearch extends Job
{
    public $camp_id;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['job_id', 'timezone_id', 'camp_id', 'answer_timeout', 'ring_timeout', 'retry_on_no_answer', 'retry_on_busy', 'retry_num', 'time_id'], 'integer'],
            [['job_name', 'job_status', 'activation_status', 'job_dial_status'], 'safe'],
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
        $query = Job::find();

        $primaryKey = Job::primaryKey()[0];

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
            'job_id' => $this->job_id,
            'timezone_id' => $this->timezone_id,
            'campaign_id' => $this->camp_id,
            //'concurrent_calls_limit' => $this->concurrent_calls_limit,
            'answer_timeout' => $this->answer_timeout,
            'ring_timeout' => $this->ring_timeout,
            'retry_on_no_answer' => $this->retry_on_no_answer,
            'retry_on_busy' => $this->retry_on_busy,
            'retry_num' => $this->retry_num,
            'time_id' => $this->time_id,
        ]);

        $query->andFilterWhere(['like', 'job_name', $this->job_name])
            ->andFilterWhere(['like', 'job_status', $this->job_status])
            ->andFilterWhere(['like', 'activation_status', $this->activation_status])
            ->andFilterWhere(['like', 'job_dial_status', $this->job_dial_status]);

        return $dataProvider;
    }
}
