<?php

namespace app\modules\ecosmob\abandonedcallreport\models;

use app\components\CommonHelper;
use Yii;
use yii\data\ActiveDataProvider;

/**
 * This is the model class for table "abandoned_call_report".
 *
 * @property string $queue
 * @property string $callee_id
 * @property string $caller_id
 * @property string $call_status
 * @property string $start_time
 * @property string $end_time
 * @property string $hold_time
 */
class AbandonedCallReportView extends \yii\db\ActiveRecord
{
    public $from;
    public $to;
    public $campaign_name;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'abandoned_call_report';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['queue'], 'string', 'max' => 200],
            [['callee_id', 'caller_id', 'call_status', 'hold_time'], 'string', 'max' => 100],
            [['start_time', 'end_time'], 'string', 'max' => 24],
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
                  return $('#abandonedcallreportview-start_time').val() != '' ;
              }", 'message' => Yii::t('app', 'end_time_req'), 'enableClientValidation' => true],
            [['start_time'], 'required', 'when' => function ($model) {
                return $model->end_time != null;
            }, 'whenClient' => "function (attribute, value) {
                  return $('#abandonedcallreportview-end_time').val() != '' ;
              }", 'message' => Yii::t('app', 'start_date_req'), 'enableClientValidation' => true],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'queue' => 'Queue',
            'callee_id' => 'Callee ID',
            'caller_id' => 'Caller ID',
            'call_status' => 'Abandonment Status',
            'start_time' => 'Call Started',
            'end_time' => 'Call Ended',
            'hold_time' => 'Hold Time',
        ];
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
        $query = AbandonedCallReportView::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['start_time' => SORT_DESC]],
            'pagination' => ['pageSize' => Yii::$app->layoutHelper->get_per_page_record_count()],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'SUBSTRING_INDEX(queue, "_", 1)', $this->queue])
            ->andFilterWhere(['like', 'callee_id', $this->callee_id]);

        if ($this->start_time && $this->end_time) {
            $from = CommonHelper::DtTots(date('Y-m-d H:i:s', strtotime($this->start_time)));
            $to = CommonHelper::DtTots(date('Y-m-d H:i:s', strtotime($this->end_time)));
            $query->andFilterWhere(['>=', 'start_time', trim($from)]);
            $query->andFilterWhere(['<=', 'end_time', trim($to)]);
        }

        return $dataProvider;
    }
}
