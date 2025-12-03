<?php

namespace app\modules\ecosmob\clienthistory\models;

use app\components\CommonHelper;
use app\modules\ecosmob\campaign\models\CampaignMappingUser;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * CampCdrSearch represents the model behind the search form of `app\modules\ecosmob\clienthistory\models\CampCdr`.
 */
class CampCdrSearch extends CampCdr
{
    public $from;
    public $to;
    public $campaign_name;
    public $agent_first_name;
    public $agent_last_name;
    public $customer_first_name;
    public $customer_last_name;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['caller_id_num', 'dial_number', 'extension_number', 'call_status', 'start_time', 'ans_time', 'end_time', 'call_id', 'camp_name', 'call_disposion_start_time', 'call_disposion_name', 'from', 'to', 'campaign_name', 'agent_first_name', 'agent_last_name', 'customer_first_name', 'customer_last_name', 'disposition_comment'], 'safe'],
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
                  return $('#campcdrsearch-from').val() != '' ;
              }", 'message' => Yii::t('app', 'to_date_req'), 'enableClientValidation' => true],
            [['from'], 'required', 'when' => function ($model) {
                return $model->to != null;
            }, 'whenClient' => "function (attribute, value) {
                  return $('#campcdrsearch-to').val() != '' ;
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
        $session = yii::$app->session;
        $agentCamp = $session->get('selectedCampaign');

        $campaignList = [];
        $supervisorCamp = CampaignMappingUser::find()
            ->where(['supervisor_id' => Yii::$app->user->id])
            ->all();
        foreach ($supervisorCamp as $supervisorCamps) {
            $campaignList[] = $supervisorCamps['campaign_id'];
        }

        if (!empty($agentCamp)) {
            $query = CampCdr::find()
                ->select([
                    'cc.id', 'cc.dial_number', 'cc.caller_id_num', 'cc.dial_number',
                    'cc.start_time', 'cc.ans_time', 'cc.end_time', 'cc.call_disposion_name',
                    'cc.call_status', 'cc.lead_member_id', 'cc.current_active_camp',
                    'cc.camp_name as camp_name',
                    'ccc.cmp_name as campaign_name',
                    'am.adm_firstname as agent_first_name',
                    'am.adm_lastname as agent_last_name',
                    'lgm.lg_first_name as customer_first_name',
                    'lgm.lg_last_name as customer_last_name',
                    'adm.comment as disposition_comment',
                ])
                ->from('camp_cdr cc')
                ->innerjoin('admin_master am', 'am.adm_id = cc.agent_id')
                ->innerjoin('ct_call_campaign ccc', 'ccc.cmp_id = cc.camp_name')
                ->innerjoin('ct_lead_group_member lgm', 'lgm.ld_id = ccc.cmp_lead_group') // AND (lgm.lg_contact_number = cc.caller_id_num OR lgm.lg_contact_number = cc.dial_number)
                ->leftJoin('ct_disposition_type dt', 'dt.ds_type = cc.call_disposion_name')
                ->leftJoin('agent_disposition_mapping adm', 'adm.agent_id = cc.agent_id and adm.campaign_id = cc.camp_name and adm.disposition = dt.ds_type_id and adm.create_at = cc.call_disposion_start_time')
                ->where(['am.adm_is_admin' => 'agent'])
                ->andWhere(['ccc.cmp_id' => $agentCamp])
                ->andWhere(['ccc.cmp_status' => 'Active'])
                //->andWhere('lgm.lg_contact_number = cc.caller_id_num')
                //->orWhere('lgm.lg_contact_number = cc.dial_number')
                // ->andWhere('lgm.lg_contact_number = cc.caller_id_num OR lgm.lg_contact_number = cc.dial_number')
                ->andWhere(['am.adm_id' => Yii::$app->user->identity->adm_id])
                ->groupBy('cc.id');
        } else {
            $query = CampCdr::find()
                ->select([
                    'cc.id', 'cc.dial_number', 'cc.caller_id_num', 'cc.dial_number',
                    'cc.start_time', 'cc.ans_time', 'cc.end_time', 'cc.call_disposion_name',
                    'cc.call_status', 'cc.lead_member_id', 'cc.current_active_camp',
                    'cc.camp_name as camp_name',
                    'ccc.cmp_name as campaign_name',
                    'am.adm_firstname as agent_first_name',
                    'am.adm_lastname as agent_last_name',
                    'lgm.lg_first_name as customer_first_name',
                    'lgm.lg_last_name as customer_last_name',
                    'adm.comment as disposition_comment'
                ])
                ->from('camp_cdr cc')
                ->innerjoin('admin_master am', 'am.adm_id = cc.agent_id')
                ->innerjoin('ct_call_campaign ccc', 'ccc.cmp_id = cc.camp_name')
                ->innerjoin('ct_lead_group_member lgm', 'lgm.ld_id = ccc.cmp_lead_group AND (lgm.lg_contact_number = cc.caller_id_num OR lgm.lg_contact_number = cc.dial_number)')
                ->leftJoin('ct_disposition_type dt', 'dt.ds_type = cc.call_disposion_name')
                ->leftJoin('agent_disposition_mapping adm', 'adm.agent_id = cc.agent_id and adm.campaign_id = cc.camp_name and adm.disposition = dt.ds_type_id and adm.create_at = cc.call_disposion_start_time')
                ->andWhere(['ccc.cmp_status' => 'Active'])
                //->andWhere('lgm.lg_contact_number = cc.caller_id_num')
                //->orWhere('lgm.lg_contact_number = cc.dial_number')
                // ->andWhere('lgm.lg_contact_number = cc.caller_id_num OR lgm.lg_contact_number = cc.dial_number')
                ->andWhere(['ccc.cmp_id' => $campaignList])
                ->groupBy('cc.id');
            //->andWhere(['am.adm_id' => Yii::$app->user->identity->adm_id]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['start_time' => SORT_DESC],
                'attributes' => [
                    'dial_number' => [
                        'asc' => ["SUBSTRING_INDEX(dial_number, '+', -1)" => SORT_ASC],
                        'desc' => ["SUBSTRING_INDEX(dial_number, '+', -1)" => SORT_DESC],
                    ],
                    'call_disposion_name' => [
                        'asc' => ['call_disposion_name' => SORT_ASC],
                        'desc' => ['call_disposion_name' => SORT_DESC],
                    ],
                    'disposition_comment' => [
                        'asc' => ['disposition_comment' => SORT_ASC],
                        'desc' => ['disposition_comment' => SORT_DESC],
                    ],
                    'call_disposion_decription' => [
                        'asc' => ['call_disposion_decription' => SORT_ASC],
                        'desc' => ['call_disposion_decription' => SORT_DESC],
                    ],
                    'agent_first_name' => [
                        'asc' => ['agent_first_name' => SORT_ASC],
                        'desc' => ['agent_first_name' => SORT_DESC],
                    ],
                    'agent_last_name' => [
                        'asc' => ['agent_last_name' => SORT_ASC],
                        'desc' => ['agent_last_name' => SORT_DESC],
                    ],
                    'customer_first_name' => [
                        'asc' => ['customer_first_name' => SORT_ASC],
                        'desc' => ['customer_first_name' => SORT_DESC],
                    ],
                    'customer_last_name' => [
                        'asc' => ['customer_last_name' => SORT_ASC],
                        'desc' => ['customer_last_name' => SORT_DESC],
                    ],
                    'call_disposion_start_time' => [
                        'asc' => ['call_disposion_start_time' => SORT_ASC],
                        'desc' => ['call_disposion_start_time' => SORT_DESC],
                    ],
                    'start_time' => [
                        'asc' => ['start_time' => SORT_ASC],
                        'desc' => ['start_time' => SORT_DESC],
                    ],
                    'campaign_name' => [
                        'asc' => ['campaign_name' => SORT_ASC],
                        'desc' => ['campaign_name' => SORT_DESC],
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
            $from = CommonHelper::DtTots($this->from);
            $to = CommonHelper::DtTots($this->to);
            $query->andFilterWhere(['>=', 'start_time', trim($from)]);
            $query->andFilterWhere(['<=', 'start_time', trim($to)]);
        } else {
            $currentDate = date('Y-m-d');
            $query->andWhere(['>=', 'DATE_FORMAT(start_time, "%Y-%m-%d")', $currentDate]);
            $query->andWhere(['<=', 'DATE_FORMAT(start_time, "%Y-%m-%d")', $currentDate]);
        }

        if ($this->campaign_name) {
            $query->andFilterWhere(['=', 'ccc.cmp_id', $this->campaign_name]);
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'ans_time' => $this->ans_time,
            'end_time' => $this->end_time,
            'call_disposion_start_time' => $this->call_disposion_start_time,
        ]);

        $query->andFilterWhere(['like', 'caller_id_num', $this->caller_id_num])
            ->andFilterWhere(['like', 'dial_number', $this->dial_number])
            ->andFilterWhere(['like', 'extension_number', $this->extension_number])
            ->andFilterWhere(['like', 'call_status', $this->call_status])
            ->andFilterWhere(['like', 'call_id', $this->call_id])
            ->andFilterWhere(['like', 'call_disposion_name', $this->call_disposion_name]);

        return $dataProvider;
    }
}
