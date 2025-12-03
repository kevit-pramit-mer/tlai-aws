<?php

namespace app\modules\ecosmob\agentperformancereport\models;

use app\modules\ecosmob\supervisor\models\BreakReasonMapping;
use yii\data\ActiveDataProvider;
use app\components\CommonHelper;
use Yii;
use yii\base\Model;
use yii\db\Expression;


class AgentPerformanceReportSearch extends AgentPerformanceReport
{
    public $from;
    public $to;
    public $campaign;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'agent_id'], 'integer'],
            [['caller_id_num', 'dial_number', 'extension_number', 'call_status', 'start_time', 'ans_time', 'end_time', 'call_id', 'camp_name', 'call_disposion_start_time', 'call_disposion_name', 'call_disposion_decription', 'campaign', 'from', 'to', 'agent', 'queue', 'break_time', 'wait_time', 'avg_break_time', 'avg_wait_time', 'avg_call_duration', 'disposion_time', 'avg_disposion_time'], 'safe'],
            [
                'to',
                'compare',
                'compareAttribute' => 'from',
                'operator' => '>=',
                'message' => Yii::t('app', 'from_to_date_cmp')
            ],
            [['to'], 'required', 'when' => function ($model) {
                return $model->from != null;
            }, 'whenClient' => "function (attribute, value) {
                  return $('#agentscallreportsearch-from').val() != '' ;
              }", 'message' => Yii::t('app', 'to_date_req'), 'enableClientValidation' => true],
            [['from'], 'required', 'when' => function ($model) {
                return $model->to != null;
            }, 'whenClient' => "function (attribute, value) {
                  return $('#agentscallreportsearch-to').val() != '' ;
              }", 'message' => Yii::t('app', 'from_date_req'), 'enableClientValidation' => true],
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
        $campaignList = [];
        $camp = \app\modules\ecosmob\campaign\models\Campaign::find()->where(['cmp_status' => 'Active'])->all();
        foreach ($camp as $_camp) {
            $campaignList[] = $_camp['cmp_id'];
        }

        $query = AgentPerformanceReport::find()
            ->select([
                'cc.agent_id',
                'CONCAT(am.adm_firstname, " ", am.adm_lastname) as agent',
                'count(cc.id) as total_call',
                'SUM(TIMESTAMPDIFF(SECOND, cc.start_time, cc.end_time)) call_duration',
                'AVG(TIMESTAMPDIFF(SECOND, cc.start_time, cc.end_time)) avg_call_duration',
                'SUM(TIMESTAMPDIFF(SECOND, cc.start_time, cc.ans_time)) wait_time',
                'AVG(TIMESTAMPDIFF(SECOND, cc.start_time, cc.ans_time)) avg_wait_time',
                'SUM(TIMESTAMPDIFF(SECOND, cc.end_time, cc.call_disposion_start_time)) disposion_time',
                'AVG(TIMESTAMPDIFF(SECOND, cc.end_time, cc.call_disposion_start_time)) avg_disposion_time',
                'answered' => new Expression('SUM(CASE WHEN ans_time IS NOT NULL OR ans_time != "0000-00-00 00:00:00" THEN 1 ELSE 0 END)'),
                'abandoned' => new Expression('SUM(CASE WHEN ans_time = "" OR ans_time IS NULL OR ans_time = "0000-00-00 00:00:00" THEN 1 ELSE 0 END)'),
            ])
            ->from('admin_master am')
            ->leftJoin('camp_cdr cc', 'am.adm_id = cc.agent_id')
            ->leftJoin('ct_call_campaign ccc', 'ccc.cmp_id = cc.camp_name')
            //->leftJoin('ct_call_campaign ccc', (new Expression('FIND_IN_SET(ccc.cmp_id, cc.camp_name)')))
            ->andWhere(['ccc.cmp_id' => $campaignList])
            ->andWhere(['ccc.cmp_status' => 'Active'])
            ->groupBy(['cc.agent_id']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ["agent" => SORT_ASC],
                'attributes' => [
                    'agent' => [
                        'asc' => ['agent' => SORT_ASC],
                        'desc' => ['agent' => SORT_DESC],
                    ],
                    'total_call' => [
                        'asc' => ['count(cc.id)' => SORT_ASC],
                        'desc' => ['count(cc.id)' => SORT_DESC],
                    ],
                    'answered' => [
                        'asc' => ['answered' => SORT_ASC],
                        'desc' => ['answered' => SORT_DESC],
                    ],
                    'abandoned' => [
                        'asc' => ['abandoned' => SORT_ASC],
                        'desc' => ['abandoned' => SORT_DESC],
                    ],
                    'call_duration' => [
                        'asc' => ['call_duration' => SORT_ASC],
                        'desc' => ['call_duration' => SORT_DESC],
                    ],
                    'avg_call_duration' => [
                        'asc' => ['avg_call_duration' => SORT_ASC],
                        'desc' => ['avg_call_duration' => SORT_DESC],
                    ],
                    'wait_time' => [
                        'asc' => ['wait_time' => SORT_ASC],
                        'desc' => ['wait_time' => SORT_DESC],
                    ],
                    'avg_wait_time' => [
                        'asc' => ['avg_wait_time' => SORT_ASC],
                        'desc' => ['avg_wait_time' => SORT_DESC],
                    ],
                   /* 'break_time' => [
                        'asc' => ['break_time' => SORT_ASC],
                        'desc' => ['break_time' => SORT_DESC],
                    ],
                    'avg_break_time' => [
                        'asc' => ['avg_break_time' => SORT_ASC],
                        'desc' => ['avg_break_time' => SORT_DESC],
                    ],*/
                    'disposion_time' => [
                        'asc' => ['disposion_time' => SORT_ASC],
                        'desc' => ['disposion_time' => SORT_DESC],
                    ],
                    'avg_disposion_time' => [
                        'asc' => ['avg_disposion_time' => SORT_ASC],
                        'desc' => ['avg_disposion_time' => SORT_DESC],
                    ],
                ],
            ],
            'pagination' => ['pageSize' => Yii::$app->layoutHelper->get_per_page_record_count()],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        if ($this->from && $this->to) {
            $from = CommonHelper::DtTots($this->from . ' 00:00:01');
            $to = CommonHelper::DtTots($this->to . ' 23:59:59');
            $query->andFilterWhere(['>=', 'start_time', trim($from)]);
            $query->andFilterWhere(['<=', 'start_time', trim($to)]);
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'agent_id' => $this->agent_id,
            'ccc.cmp_id' => $this->camp_name,
            'queue' => $this->queue,
        ]);

        return $dataProvider;
    }

}
