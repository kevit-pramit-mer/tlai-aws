<?php

namespace app\modules\ecosmob\campaignsummaryreport\models;

use app\components\CommonHelper;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Expression;

class CampaignSearch extends Campaign
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
            [['caller_id_num', 'dial_number', 'extension_number', 'call_status', 'start_time', 'ans_time', 'end_time', 'call_id', 'camp_name', 'call_disposion_start_time', 'call_disposion_name', 'call_disposion_decription', 'campaign', 'from', 'to', 'cmp_type'], 'safe'],
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

        $query = Campaign::find()
            ->select([
                'ccc.cmp_name as camp_name',
                'count(cc.id) as total_call',
                'AVG(TIMESTAMPDIFF(SECOND, cc.start_time, cc.end_time)) call_duration',
                'answered' => new Expression('SUM(CASE WHEN ans_time IS NOT NULL OR ans_time != "0000-00-00 00:00:00" THEN 1 ELSE 0 END)'),
                'abandoned' => new Expression('SUM(CASE WHEN ans_time = "" OR ans_time IS NULL OR ans_time = "0000-00-00 00:00:00" THEN 1 ELSE 0 END)'),
                'ccc.cmp_type'
            ])
            ->from('ct_call_campaign ccc')
            //->innerjoin('camp_cdr cc', 'ccc.cmp_id = cc.camp_name')
            ->innerjoin('camp_cdr cc', 'ccc.cmp_id = (CASE WHEN current_active_camp IS NULL THEN cc.camp_name ELSE current_active_camp END)')
            //->leftJoin('camp_cdr cc', (new Expression('FIND_IN_SET(cc.camp_name, ccc.cmp_id)')))
            ->andWhere(['ccc.cmp_id' => $campaignList])
            ->andWhere(['ccc.cmp_status' => 'Active'])
            ->groupBy(['ccc.cmp_id']);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => [
                    'camp_name' => [
                        'asc' => ['ccc.cmp_name' => SORT_ASC],
                        'desc' => ['ccc.cmp_name' => SORT_DESC],
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
                    'cmp_type' => [
                        'asc' => ['cmp_type' => SORT_ASC],
                        'desc' => ['cmp_type' => SORT_DESC],
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

        if (isset($this->campaign) && !empty($this->campaign)) {
            $query->andFilterWhere(['=', 'ccc.cmp_id', $this->campaign]);
            //$query->andFilterWhere(['(CASE WHEN current_active_camp IS NULL THEN cc.camp_name ELSE current_active_camp END)' => $this->campaign]);
        }

        return $dataProvider;
    }
}
