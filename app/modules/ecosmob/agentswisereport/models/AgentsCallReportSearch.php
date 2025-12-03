<?php

namespace app\modules\ecosmob\agentswisereport\models;

use app\components\CommonHelper;
use app\modules\ecosmob\extension\models\Extension;
use app\modules\ecosmob\timezone\models\Timezone;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\Expression;

/**
 * AgentsCallReportSearch represents the model behind the search form of `app\modules\ecosmob\agentswisereport\models\AgentsCallReport`.
 */
class AgentsCallReportSearch extends AgentsCallReport
{
    public $from;
    public $to;
    public $campaign;
    public $agent;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'agent_id'], 'integer'],
            [['caller_id_num', 'dial_number', 'extension_number', 'call_status', 'start_time', 'ans_time', 'end_time', 'call_id', 'camp_name', 'call_disposion_start_time', 'call_disposion_name', 'call_disposion_decription', 'from', 'campaign', 'to', 'agent', 'recording_file', 'disposition_comment', 'wrap_up_time', 'customer_name', 'cmp_type'], 'safe'],
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
        $query = AgentsCallReport::find()
            ->select([
                'camp_cdr.id', 'camp_cdr.agent_id', 'caller_id_num', 'dial_number', 'extension_number', 'call_status', 'start_time', 'ans_time', 'end_time', 'call_id', 'camp_name', 'call_disposion_start_time', 'call_disposion_name', 'recording_file', 'camp_cdr.lead_member_id', 'camp_cdr.current_active_camp',
                'qm.qm_name as queue',
                'CONCAT(am.adm_firstname," ",am.adm_lastname) as agent_name',
                'ccc.cmp_name as campaign_name', 'ccc.cmp_type',
                'CONCAT(lgm.lg_first_name, " ",  lgm.lg_last_name) as customer_name',
                '(TIMESTAMPDIFF(SECOND, ans_time, start_time)) call_waiting',
                '(TIMESTAMPDIFF(SECOND, start_time, end_time)) call_duration',
                '(TIMESTAMPDIFF(SECOND, ans_time, end_time)) agent_duration',
                '(TIMESTAMPDIFF(SECOND, end_time, call_disposion_start_time)) wrap_up_time',
                'adm.comment as disposition_comment'
            ])
            ->from('camp_cdr')
            ->leftJoin('admin_master am', 'am.adm_id = camp_cdr.agent_id')
            ->leftJoin('ct_call_campaign ccc', 'ccc.cmp_id = camp_cdr.camp_name')
            ->leftjoin('ct_lead_group_member lgm', 'lgm.ld_id = ccc.cmp_lead_group AND (lgm.lg_contact_number = camp_cdr.caller_id_num OR lgm.lg_contact_number = camp_cdr.dial_number)')
            ->leftjoin('ct_queue_master qm', 'qm.qm_id = camp_cdr.queue')
            ->leftJoin('ct_disposition_type dt', 'dt.ds_type = camp_cdr.call_disposion_name')
            ->leftJoin('agent_disposition_mapping adm', 'adm.agent_id = camp_cdr.agent_id and adm.campaign_id = camp_cdr.camp_name and adm.disposition = dt.ds_type_id and adm.create_at = camp_cdr.call_disposion_start_time')
            //->leftJoin('ct_call_campaign ccc', (new Expression('FIND_IN_SET(camp_cdr.camp_name, ccc.cmp_id)')))
            ->andWhere(['ccc.cmp_status' => 'Active'])
            ->andWhere(['am.adm_is_admin' => 'agent'])
            ->groupBy(['camp_cdr.id'])
        ;

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ["date" => SORT_DESC, "start_time" => SORT_DESC],
                'attributes' => [
                    'agent_name' => [
                        'asc' => ['CONCAT(am.adm_firstname," ",am.adm_lastname)' => SORT_ASC],
                        'desc' => ['CONCAT(am.adm_firstname," ",am.adm_lastname)' => SORT_DESC],
                    ],
                    'caller_id_num' => [
                        'asc' => ["SUBSTRING_INDEX(caller_id_num, '+', -1)" => SORT_ASC],
                        'desc' => ["SUBSTRING_INDEX(caller_id_num, '+', -1)" => SORT_DESC],
                    ],
                    'dial_number' => [
                        'asc' => ["SUBSTRING_INDEX(dial_number, '+', -1)" => SORT_ASC],
                        'desc' => ["SUBSTRING_INDEX(dial_number, '+', -1)" => SORT_DESC],
                    ],
                    'queue' => [
                        'asc' => ['queue' => SORT_ASC],
                        'desc' => ['queue' => SORT_DESC],
                    ],
                    'campaign_name' => [
                        'asc' => ['ccc.cmp_name' => SORT_ASC],
                        'desc' => ['ccc.cmp_name' => SORT_DESC],
                    ],
                    'cmp_type' => [
                        'asc' => ['ccc.cmp_type' => SORT_ASC],
                        'desc' => ['ccc.cmp_type' => SORT_DESC],
                    ],
                    'customer_name' => [
                        'asc' => ['CONCAT(lgm.lg_first_name, " ",  lgm.lg_last_name)' => SORT_ASC],
                        'desc' => ['CONCAT(lgm.lg_first_name, " ",  lgm.lg_last_name)' => SORT_DESC],
                    ],
                    'date' => [
                        'asc' => ['DATE_FORMAT(start_time, "%Y-%m-%d")' => SORT_ASC],
                        'desc' => ['DATE_FORMAT(start_time, "%Y-%m-%d")' => SORT_DESC],
                    ],
                    'start_time' => [
                        'asc' => ["start_time" => SORT_ASC],
                        'desc' => ["start_time" => SORT_DESC],
                    ],
                    'ans_time' => [
                        'asc' => ["start_time" => SORT_ASC],
                        'desc' => ["start_time" => SORT_DESC],
                    ],
                    'end_time' => [
                        'asc' => ["end_time" => SORT_ASC],
                        'desc' => ["end_time" => SORT_DESC],
                    ],
                    'call_waiting' => [
                        'asc' => ["call_waiting" => SORT_ASC],
                        'desc' => ["call_waiting" => SORT_DESC],
                    ],
                    'call_duration' => [
                        'asc' => ['DATE_FORMAT(call_duration, "%H:%i:%s")' => SORT_ASC],
                        'desc' => ['DATE_FORMAT(call_duration, "%H:%i:%s")' => SORT_DESC],
                    ],
                    'agent_duration' => [
                        'asc' => ['DATE_FORMAT(agent_duration, "%H:%i:%s")' => SORT_ASC],
                        'desc' => ['DATE_FORMAT(agent_duration, "%H:%i:%s")' => SORT_DESC],
                    ],
                    'call_disposion_name' => [
                        'asc' => ['call_disposion_name' => SORT_ASC],
                        'desc' => ['call_disposion_name' => SORT_DESC],
                    ],
                    'wrap_up_time' => [
                        'asc' => ['wrap_up_time' => SORT_ASC],
                        'desc' => ['wrap_up_time' => SORT_DESC],
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
            $from = CommonHelper::DtTots(date('Y-m-d H:i:s', strtotime($this->from)));
            $to = CommonHelper::DtTots(date('Y-m-d H:i:s', strtotime($this->to)));
            $query->andFilterWhere(['>=', 'start_time', trim($from)]);
            $query->andFilterWhere(['<=', 'start_time', trim($to)]);
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'camp_cdr.agent_id' => $this->agent,
            'ccc.cmp_id' => $this->campaign
        ]);
        return $dataProvider;
    }
}
