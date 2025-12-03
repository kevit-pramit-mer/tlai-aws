<?php

namespace app\modules\ecosmob\leadperformancereport\models;

use app\components\CommonHelper;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
use app\modules\ecosmob\campaign\models\CampaignMappingUser;

/**
 * LeadPerformanceSearchReport represents the model behind the search form of `app\modules\ecosmob\leadperformancereport\models\LeadPerformanceReport`.
 */
class LeadPerformanceSearchReport extends LeadPerformanceReport
{
    public $from;
    public $to;
    public $campaign;
    public $leadgroup;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['campaign', 'leadgroup', 'from', 'to'], 'safe'],
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
                  return $('#leadperformancesearchreport-from').val() != '' ;
              }", 'message' => Yii::t('app', 'to_date_req'), 'enableClientValidation' => true],
            [['from'], 'required', 'when' => function ($model) {
                return $model->to != null;
            }, 'whenClient' => "function (attribute, value) {
                  return $('#leadperformancesearchreport-to').val() != '' ;
              }", 'message' => Yii::t('app', 'from_date_req'), 'enableClientValidation' => true],
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
       /* SUM(CASE WHEN call_disposition_category = 1 THEN 1 ELSE 0 END) AS contacted_count,
                  SUM(CASE WHEN call_disposition_category = 2 THEN 1 ELSE 0 END) AS noncontacted_count,*/
        $campaignList = [];
        if(Yii::$app->user->identity->adm_is_admin == 'supervisor') {
            $supervisorCamp = CampaignMappingUser::find()
                ->where(['supervisor_id' => Yii::$app->user->id])
                ->all();
            foreach ($supervisorCamp as $supervisorCamps) {
                $campaignList[] = $supervisorCamps['campaign_id'];
            }
        }
        $query = LeadPerformanceReport::find()
            ->select(['ct_leadgroup_master.ld_group_name,
                 
                  count(*) as dialed_count,
                  SUM(CASE WHEN call_disposition_category = 1 THEN 1 ELSE 0 END) AS contacted_count,
                  SUM(CASE WHEN call_disposition_category = 2 THEN 1 ELSE 0 END) AS noncontacted_count,
                    (
                    SELECT COUNT(*)
                            FROM ct_lead_group_member AS lgm
                            WHERE lgm.ld_id = ct_call_campaign.cmp_lead_group
                        AND lgm.lg_contact_number NOT IN (camp_cdr.dial_number)
                    ) AS remaining_count,
                    (
                    SELECT COUNT(*)
                            FROM ct_redial_calls AS rc
                            WHERE rc.ld_id = ct_call_campaign.cmp_lead_group
                            AND DATE(updated_date) >= DATE(start_time) AND DATE(updated_date) <= DATE(end_time)
                            AND rc.rd_status = 1
                    ) AS redial_count,
                     (
                     SELECT COUNT(*)
                            FROM ct_redial_calls AS rc
                            WHERE rc.ld_id = ct_call_campaign.cmp_lead_group
                            AND DATE(updated_date) >= DATE(start_time) AND DATE(updated_date) <= DATE(end_time)
                            AND rc.rd_status = 1 AND rc.ds_category_id = 1 
                    ) AS redial_contacted_count,
                     (
                       SELECT COUNT(*)
                            FROM ct_redial_calls AS rc
                            WHERE rc.ld_id = ct_call_campaign.cmp_lead_group
                            AND DATE(updated_date) >= DATE(start_time) AND DATE(updated_date) <= DATE(end_time)
                            AND rc.rd_status = 1 AND rc.ds_category_id = 2
                    ) AS redial_noncontacted_count'
            ])
            ->from('ct_leadgroup_master')

            ->leftJoin('ct_call_campaign', 'ct_leadgroup_master.ld_id = ct_call_campaign.cmp_lead_group')
            ->leftJoin('camp_cdr', 'ct_call_campaign.cmp_id = (CASE WHEN current_active_camp IS NULL THEN camp_cdr.camp_name ELSE current_active_camp END)')
            //->leftJoin('camp_cdr', 'camp_cdr.camp_name = ct_call_campaign.cmp_id')
            //->leftJoin('camp_cdr', (new Expression('FIND_IN_SET(ct_call_campaign.cmp_id, camp_cdr.camp_name)')))
            ->leftJoin('ct_lead_group_member lgm', 'lgm.ld_id = ct_call_campaign.cmp_lead_group AND (lgm.lg_contact_number = camp_cdr.dial_number)');
        if(Yii::$app->user->identity->adm_is_admin == 'supervisor') {
            $query->where(['ct_call_campaign.cmp_id' => $campaignList]);
        }

        $query->groupBy('ct_leadgroup_master.ld_id');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['ld_group_name' => SORT_DESC],
                'attributes' => [
                    'ld_group_name' => [
                        'asc' => ['ld_group_name' => SORT_ASC],
                        'desc' => ['ld_group_name' => SORT_DESC],
                    ],
                    'contacted_count' => [
                        'asc' => ['contacted_count' => SORT_ASC],
                        'desc' => ['contacted_count' => SORT_DESC],
                    ],
                    'noncontacted_count' => [
                        'asc' => ['noncontacted_count' => SORT_ASC],
                        'desc' => ['noncontacted_count' => SORT_DESC],
                    ],
                    'dialed_count' => [
                        'asc' => ['dialed_count' => SORT_ASC],
                        'desc' => ['dialed_count' => SORT_DESC],
                    ],
                    'remaining_count' => [
                        'asc' => ['remaining_count' => SORT_ASC],
                        'desc' => ['remaining_count' => SORT_DESC],
                    ],
                    'redial_count' => [
                        'asc' => ['redial_count' => SORT_ASC],
                        'desc' => ['redial_count' => SORT_DESC],
                    ],
                    'redial_contacted_count' => [
                        'asc' => ['redial_contacted_count' => SORT_ASC],
                        'desc' => ['redial_contacted_count' => SORT_DESC],
                    ],
                    'redial_noncontacted_count' => [
                        'asc' => ['redial_noncontacted_count' => SORT_ASC],
                        'desc' => ['redial_noncontacted_count' => SORT_DESC],
                    ],
                ],
            ],
            'pagination' => ['pageSize' => Yii::$app->layoutHelper->get_per_page_record_count()],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if ($this->from && $this->to) {
            $from = CommonHelper::DtTots($this->from . ' 00:00:01');
            $to = CommonHelper::DtTots($this->to . ' 23:59:59');
            $query->andFilterWhere(['>=', 'start_time', trim($from)]);
            $query->andFilterWhere(['<=', 'start_time', trim($to)]);
        }


        $query->andFilterWhere(['=', 'ct_call_campaign.cmp_id', $this->campaign]);
        if ($this->leadgroup) {
            $query->andFilterWhere(['like', 'ct_call_campaign.cmp_lead_group', $this->leadgroup]);
        }

        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        return $dataProvider;
    }
}
