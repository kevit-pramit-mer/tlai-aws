<?php

namespace app\modules\ecosmob\abandonedcallreport\models;

use app\components\CommonHelper;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * QueueAbandonedCallsSearch represents the model behind the search form of `app\modules\ecosmob\abandonedcallreport\models\QueueAbandonedCalls`.
 */
class QueueAbandonedCallsSearch extends QueueAbandonedCalls
{
    public $from;
    public $to;
    public $campaign_name;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['queue_name', 'queue_number', 'caller_id_number', 'call_status', 'start_time', 'end_time', 'hold_time', 'max_wait_reached', 'breakaway_digit_dialed', 'abandoned_time', 'abandoned_wait_time', 'break_away_wait_time'], 'safe'],
            [['queue_number', 'queue_name', 'start_time', 'end_time', 'from', 'to', 'campaign_name'], 'safe'],
            [
                'end_time',
                'compare',
                'compareAttribute' => 'start_time',
                'operator' => '>=',
                'message' => Yii::t('app', 'start_end_time_cmp')
            ],
            [['end_time'], 'required', 'when' => function ($model) {
                return $model->start_time != null;
            }, 'whenClient' => "function (attribute, value) {
                  return $('#queueabandonedcallssearch-start_time').val() != '' ;
              }", 'message' => Yii::t('app', 'end_time_req'), 'enableClientValidation' => true],
            [['start_time'], 'required', 'when' => function ($model) {
                return $model->end_time != null;
            }, 'whenClient' => "function (attribute, value) {
                  return $('#queueabandonedcallssearch-end_time').val() != '' ;
              }", 'message' => Yii::t('app', 'start_date_req'), 'enableClientValidation' => true],
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
        $query = QueueAbandonedCalls::find()->select(['*',new \yii\db\Expression("SUBSTRING_INDEX(queue_name, '_', 1) AS queue_name")]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['start_time' => SORT_DESC]],
            'pagination' => ['pageSize' => Yii::$app->layoutHelper->get_per_page_record_count()],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        $query->andFilterWhere(['like', 'queue_name', $this->queue_name])
            ->andFilterWhere(['like', 'queue_number', $this->queue_number]);

        if ($this->start_time && $this->end_time) {
            $from = strtotime(CommonHelper::DtTots(date('Y-m-d H:i:s', strtotime($this->start_time))));
            $to = strtotime(CommonHelper::DtTots(date('Y-m-d H:i:s', strtotime($this->end_time))));
            $query->andFilterWhere(['>=', 'start_time', trim($from)]);
            $query->andFilterWhere(['<=', 'end_time', trim($to)]);
        }

        return $dataProvider;
    }
}
