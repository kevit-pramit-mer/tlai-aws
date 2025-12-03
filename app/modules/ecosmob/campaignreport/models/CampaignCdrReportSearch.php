<?php

namespace app\modules\ecosmob\campaignreport\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
use app\components\CommonHelper;
use app\modules\ecosmob\campaignreport\models\CampaignCdrReport;
use app\modules\ecosmob\campaign\models\CampaignMappingUser;

/**
 * CampaignCdrReportSearch represents the model behind the search form of `app\modules\ecosmob\campaignreport\models\CampaignCdrReport`.
 */
class CampaignCdrReportSearch extends CampaignCdrReport
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
            [['caller_id_num', 'dial_number', 'extension_number', 'call_status', 'start_time', 'ans_time', 'end_time', 'call_id', 'camp_name', 'call_disposion_start_time', 'call_disposion_name', 'call_disposion_decription', 'campaign', 'from', 'to', 'total_call', 'call_duration', 'answered', 'abandoned', 'cmp_type'], 'safe'],
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
                  return $('#campaigncdrreportsearch-from').val() != '' ;
              }", 'message' => Yii::t('app', 'to_date_req'), 'enableClientValidation' => true],
            [['from'], 'required', 'when' => function ($model) {
                return $model->to != null;
            }, 'whenClient' => "function (attribute, value) {
                  return $('#campaigncdrreportsearch-to').val() != '' ;
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
        $supervisorCamp = CampaignMappingUser::find()
            ->where(['supervisor_id' => Yii::$app->user->id])
            ->all();
        foreach ($supervisorCamp as $supervisorCamps) {
            $campaignList[] = $supervisorCamps['campaign_id'];
        }

        $query = CampaignCdrReport::find()
            ->select([
                'ccc.cmp_name as camp_name',
                'count(cc.id) as total_call',
                'AVG(TIMESTAMPDIFF(SECOND, cc.start_time, cc.end_time)) call_duration',
                'answered' => new Expression('SUM(CASE WHEN ans_time IS NOT NULL OR ans_time != "0000-00-00 00:00:00" THEN 1 ELSE 0 END)'),
                'abandoned' => new Expression('SUM(CASE WHEN ans_time = "" OR ans_time IS NULL OR ans_time = "0000-00-00 00:00:00" THEN 1 ELSE 0 END)'),
                'ccc.cmp_type'
            ])
            ->from('ct_call_campaign ccc')
            ->innerjoin('camp_cdr cc', 'ccc.cmp_id = (CASE WHEN current_active_camp IS NULL THEN cc.camp_name ELSE current_active_camp END)')
            ->andWhere(['ccc.cmp_id' => $campaignList])
            ->andWhere(['ccc.cmp_status' => 'Active'])
            ->groupBy(['ccc.cmp_id']);
        ;

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
            $query->andFilterWhere(['(CASE WHEN current_active_camp IS NULL THEN cc.camp_name ELSE current_active_camp END)' => $this->campaign]);
        }

        return $dataProvider;
    }
}
