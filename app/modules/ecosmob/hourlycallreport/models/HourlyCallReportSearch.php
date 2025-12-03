<?php

namespace app\modules\ecosmob\hourlycallreport\models;

use app\modules\ecosmob\extension\models\Extension;
use app\modules\ecosmob\timezone\models\Timezone;
use yii\data\ActiveDataProvider;
use app\components\CommonHelper;
use Yii;
use yii\base\Model;
use yii\db\Expression;


class HourlyCallReportSearch extends HourlyCallReport
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
            [['caller_id_num', 'dial_number', 'extension_number', 'call_status', 'start_time', 'ans_time', 'end_time', 'call_id', 'camp_name', 'call_disposion_start_time', 'call_disposion_name', 'call_disposion_decription', 'campaign', 'from', 'to', 'hours', 'queue'], 'safe'],
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

        $query = HourlyCallReport::find()
            ->select([
                "hour(CONVERT_TZ(cc.start_time, @@session.time_zone, '+5:30')) as hours",
                //'hour(cc.start_time) as hours',
                'AVG(TIMESTAMPDIFF(SECOND, cc.start_time, cc.end_time)) call_duration',
                'count(cc.id) as total_call',
                'answered' => new Expression('SUM(CASE WHEN ans_time IS NOT NULL OR ans_time != "0000-00-00 00:00:00" THEN 1 ELSE 0 END)'),
                'abandoned' => new Expression('SUM(CASE WHEN ans_time = "" OR ans_time IS NULL OR ans_time = "0000-00-00 00:00:00" THEN 1 ELSE 0 END)'),
            ])
            ->from('camp_cdr cc')
            ->leftJoin('ct_call_campaign ccc', 'ccc.cmp_id = cc.camp_name')
            //->leftJoin('ct_call_campaign ccc', (new Expression('FIND_IN_SET(ccc.cmp_id, cc.camp_name)')))
            ->andWhere(['ccc.cmp_id' => $campaignList])
            ->andWhere(['ccc.cmp_status' => 'Active'])
            ->groupBy(["hour(CONVERT_TZ(cc.start_time, @@session.time_zone, '+5:30'))"]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ["hours" => SORT_ASC],
                'attributes' => [
                    'hours' => [
                        'asc' => ['hours' => SORT_ASC],
                        'desc' => ['hours' => SORT_DESC],
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
