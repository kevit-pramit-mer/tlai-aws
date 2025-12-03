<?php

namespace app\modules\ecosmob\queuecallback\models;

use app\components\CommonHelper;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * QueueCallbackSearch represents the model behind the search form of `app\modules\ecosmob\queuecallback\models\QueueCallback`.
 */
class QueueCallbackSearch extends QueueCallback
{
    public $queue_name;
    public $date;
    public $campaign_name;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['queue_name', 'phone_number', 'created_at', 'date', 'queue_name', 'campaign_name'], 'safe'],
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
        $query = QueueCallback::find();

        $primaryKey = QueueCallback::primaryKey()[0];

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

        $query->andFilterWhere(['like', 'queue_name', $this->queue_name])
            ->andFilterWhere(['like', 'phone_number', $this->phone_number]);

        if ($this->date) {
            $from = CommonHelper::DtTots($this->date . ' 00:00:01');
            $to = CommonHelper::DtTots($this->date . ' 23:59:59');
            $query->andFilterWhere(['>', 'created_at', trim($from)]);
            $query->andFilterWhere(['<', 'created_at', trim($to)]);
        }

        return $dataProvider;
    }
}
