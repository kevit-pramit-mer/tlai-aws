<?php

namespace app\modules\ecosmob\callhistory\models;

use app\components\CommonHelper;
use app\modules\ecosmob\campaign\models\CampaignMappingUser;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * CampCdrSearch represents the model behind the search form of `app\modules\ecosmob\callhistory\models\CampCdr`.
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
    public $calller_id;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['caller_id_num', 'dial_number', 'extension_number', 'call_status', 'start_time', 'ans_time', 'end_time', 'call_id', 'camp_name', 'call_disposion_start_time', 'call_disposion_name', 'from', 'to', 'campaign_name', 'agent_firstname', 'agent_lastname', 'customer_firstname', 'customer_lastname', 'calller_id', 'recording_file', 'disposition_comment'], 'safe'],
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
                    'camp_cdr.id', 'caller_id_num', 'dial_number', 'extension_number', 'call_status', 'start_time', 'ans_time', 'end_time', 'call_id', 'camp_name', 'call_disposion_start_time', 'call_disposion_name', 'recording_file', 'camp_cdr.lead_member_id', 'camp_cdr.current_active_camp',
                    'qm.qm_name as queue',
                    'camp_cdr.camp_name as camp_name',
                    'ccc.cmp_name as campaign_name',
                    'am.adm_firstname as agent_first_name',
                    'am.adm_lastname as agent_last_name',
                    'lgm.lg_first_name as customer_first_name',
                    'lgm.lg_last_name as customer_last_name',
                    '(TIMESTAMPDIFF(SECOND, start_time, ans_time)) call_waiting',
                    '(TIMESTAMPDIFF(SECOND, start_time, end_time)) call_duration',
                    '(TIMESTAMPDIFF(SECOND, ans_time, end_time)) agent_duration',
                    'adm.comment as disposition_comment'

                ])
                ->from('camp_cdr')
                ->innerjoin('admin_master am', 'am.adm_id = camp_cdr.agent_id')
                ->innerjoin('ct_call_campaign ccc', 'ccc.cmp_id = camp_cdr.camp_name')
                ->leftJoin('ct_lead_group_member lgm', 'lgm.ld_id = ccc.cmp_lead_group')
                ->leftjoin('ct_queue_master qm', 'qm.qm_id = camp_cdr.queue')
                ->leftJoin('ct_disposition_type dt', 'dt.ds_type = camp_cdr.call_disposion_name')
                ->leftJoin('agent_disposition_mapping adm', 'adm.agent_id = camp_cdr.agent_id and adm.campaign_id = camp_cdr.camp_name and adm.disposition = dt.ds_type_id and adm.create_at = camp_cdr.call_disposion_start_time')
                ->where(['am.adm_is_admin' => 'agent'])
                // ->andWhere('lgm.ld_id = ccc.cmp_lead_group')
                ->andWhere(['ccc.cmp_id' => explode(',', $agentCamp)])
                ->andWhere(['ccc.cmp_status' => 'Active'])
                //->andWhere('lgm.lg_contact_number = camp_cdr.caller_id_num OR lgm.lg_contact_number = camp_cdr.dial_number')
                ->andWhere(['am.adm_id' => Yii::$app->user->identity->adm_id])
                ->groupBy('camp_cdr.id');

        } else {

            $query = CampCdr::find()
                ->select([
                    'camp_cdr.id', 'camp_cdr.dial_number', 'camp_cdr.caller_id_num', 'camp_cdr.dial_number',
                    'camp_cdr.start_time', 'camp_cdr.ans_time', 'camp_cdr.end_time', 'camp_cdr.call_disposion_name',
                    'camp_cdr.call_status', 'camp_cdr.lead_member_id', 'camp_cdr.current_active_camp',
                    'qm.qm_name as queue',
                    'camp_cdr.camp_name as camp_name',
                    'ccc.cmp_name as campaign_name',
                    'am.adm_firstname as agent_first_name',
                    'am.adm_lastname as agent_last_name',
                    'lgm.lg_first_name as customer_first_name',
                    'lgm.lg_last_name as customer_last_name',
                    '(TIMESTAMPDIFF(SECOND, start_time, ans_time)) call_waiting',
                    '(TIMESTAMPDIFF(SECOND, start_time, end_time)) call_duration',
                    '(TIMESTAMPDIFF(SECOND, ans_time, end_time)) agent_duration',
                    'adm.comment as disposition_comment'
                ])
                ->from('camp_cdr')
                ->innerjoin('ct_call_campaign ccc', 'ccc.cmp_id = camp_cdr.camp_name')
                ->innerjoin('admin_master am', 'am.adm_id = camp_cdr.agent_id')
                ->innerjoin('ct_lead_group_member lgm', 'lgm.ld_id = ccc.cmp_lead_group AND (lgm.lg_contact_number = camp_cdr.caller_id_num OR lgm.lg_contact_number = camp_cdr.dial_number)')
                ->leftjoin('ct_queue_master qm', 'qm.qm_id = camp_cdr.queue')
                //->andWhere('lgm.lg_contact_number = camp_cdr.caller_id_num')
                //->orWhere('lgm.lg_contact_number = camp_cdr.dial_number')
                //->andWhere('lgm.lg_contact_number = camp_cdr.caller_id_num OR lgm.lg_contact_number = camp_cdr.dial_number')
                ->leftJoin('ct_disposition_type dt', 'dt.ds_type = camp_cdr.call_disposion_name')
                ->leftJoin('agent_disposition_mapping adm', 'adm.agent_id = camp_cdr.agent_id and adm.campaign_id = camp_cdr.camp_name and adm.disposition = dt.ds_type_id and adm.create_at = camp_cdr.call_disposion_start_time')
                ->andWhere(['ccc.cmp_status' => 'Active'])
                ->andWhere(['ccc.cmp_id' => $campaignList])
                ->groupBy('camp_cdr.id');
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
                    'caller_id_num' => [
                        'asc' => ["SUBSTRING_INDEX(caller_id_num, '+', -1)" => SORT_ASC],
                        'desc' => ["SUBSTRING_INDEX(caller_id_num, '+', -1)" => SORT_DESC],
                    ],
                    'did' => [
                        'asc' => ['dial_number' => SORT_ASC],
                        'desc' => ['dial_number' => SORT_DESC],
                    ],
                    'queue' => [
                        'asc' => ['queue' => SORT_ASC],
                        'desc' => ['queue' => SORT_DESC],
                    ],
                    'campaign_name' => [
                        'asc' => ['campaign_name' => SORT_ASC],
                        'desc' => ['campaign_name' => SORT_DESC],
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
                    'start_time' => [
                        'asc' => ['start_time' => SORT_ASC],
                        'desc' => ['start_time' => SORT_DESC],
                    ],
                    'ans_time' => [
                        'asc' => ['ans_time' => SORT_ASC],
                        'desc' => ['ans_time' => SORT_DESC],
                    ],
                    'end_time' => [
                        'asc' => ['end_time' => SORT_ASC],
                        'desc' => ['end_time' => SORT_DESC],
                    ],
                    'call_waiting' => [
                        'asc' => ['(TIMESTAMPDIFF(SECOND, start_time, ans_time))' => SORT_ASC],
                        'desc' => ['(TIMESTAMPDIFF(SECOND, start_time, ans_time))' => SORT_DESC],
                    ],
                    'call_duration' => [
                        'asc' => ['(TIMESTAMPDIFF(SECOND, start_time, end_time))' => SORT_ASC],
                        'desc' => ['(TIMESTAMPDIFF(SECOND, start_time, end_time))' => SORT_DESC],
                    ],
                    'agent_duration' => [
                        'asc' => ['(TIMESTAMPDIFF(SECOND, ans_time, end_time))' => SORT_ASC],
                        'desc' => ['(TIMESTAMPDIFF(SECOND, ans_time, end_time))' => SORT_DESC],
                    ],
                    'call_disposion_name' => [
                        'asc' => ['call_disposion_name' => SORT_ASC],
                        'desc' => ['call_disposion_name' => SORT_DESC],
                    ],
                    'disposition_comment' => [
                        'asc' => ['disposition_comment' => SORT_ASC],
                        'desc' => ['disposition_comment' => SORT_DESC],
                    ],
                    'call_status' => [
                        'asc' => ['call_status' => SORT_ASC],
                        'desc' => ['call_status' => SORT_DESC],
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
            $from = CommonHelper::DtTots($this->from.' 00:00:00');
            $to = CommonHelper::DtTots($this->to.' 23:59:59');
            $query->andFilterWhere(['>=', 'start_time', trim($from)]);
            $query->andFilterWhere(['<=', 'start_time', trim($to)]);
        }

        if ($this->campaign_name) {
            $query->andFilterWhere(['=', '(CASE WHEN current_active_camp IS NULL THEN camp_cdr.camp_name ELSE current_active_camp END)', $this->campaign_name]);
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
