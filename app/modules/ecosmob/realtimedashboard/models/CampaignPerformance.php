<?php

namespace app\modules\ecosmob\realtimedashboard\models;

use app\components\CommonHelper;
use app\modules\ecosmob\campaignreport\models\CampaignCdrReport;
use app\modules\ecosmob\supervisor\models\UsersActivityLog;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use app\modules\ecosmob\leadgroupmember\models\LeadGroupMember;
use yii\db\Expression;

class CampaignPerformance extends ActiveRecord
{
    public $total_agent_login, $total_calls, $live_calls, $answered, $abandoned, $total_leads, $dial_leads, $rechurn_leads;
    public $contacted_leads, $noncontacted_leads, $avg_call_duration, $avg_wrap_up_time, $avg_wait_time;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ct_call_campaign';
    }

    public function rules()
    {
        return [
            [[
                'cmp_id', 'cmp_name', 'caller_id_num', 'dial_number', 'extension_number', 'call_status',
                'start_time', 'ans_time', 'end_time', 'call_id', 'camp_name', 'call_disposion_start_time',
                'call_disposion_name', 'call_disposion_decription', 'total_agent_login', 'total_calls',
                'live_calls', 'answered', 'abandoned', 'total_leads', 'dial_leads', 'rechurn_leads',
                'contacted_leads', 'noncontacted_leads', 'avg_call_duration', 'avg_wrap_up_time', 'avg_wait_time'
            ], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        return Model::scenarios();
    }

    public function attributeLabels()
    {
        return [
            'cmp_name' => 'Campaign Name',
            'total_agent_login' => 'Logged in Agents',
            'total_calls' => 'Total Calls',
            'live_calls' => 'Live Calls',
            'answered' => 'Answered Calls',
            'abandoned' => 'Abandoned Calls',
            'total_leads' => 'Total Leads',
            'dial_leads' => 'Dialed Leads ',
            'rechurn_leads' => 'Rechurn Leads',
            'contacted_leads' => 'Contacted Leads',
            'noncontacted_leads' => 'No Contacted Leads',
            'avg_call_duration' => 'Average Call Duration',
            'avg_wrap_up_time' => 'Average Wrap-up Time',
            'avg_wait_time' => 'Average Wait Time',
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

        $from = CommonHelper::DtTots( date('Y-m-d') . ' 00:00:01');
        $to = CommonHelper::DtTots(date('Y-m-d') . ' 23:59:59');

        $campaignList = [];
        $camp = UsersActivityLog::find()->select(['users_activity_log.campaign_name'])
            ->leftJoin('admin_master', 'admin_master.adm_id = users_activity_log.user_id')
            ->andWhere(['>=', 'users_activity_log.login_time', trim($from)])
            ->andWhere(['<=', 'users_activity_log.logout_time', trim($to)])
            ->andWhere(['admin_master.adm_is_admin' => 'agent'])
            ->asArray()->all();
        if(!empty($camp)){
            $campaignList = explode(',', implode(',',array_column($camp, 'campaign_name')));
        }

        $query = CampaignPerformance::find()
            ->select([
                'ccc.cmp_name',
                'total_agent_login'  => UsersActivityLog::find()->select('count(DISTINCT(user_id))')
                    ->leftJoin('admin_master', 'admin_master.adm_id = users_activity_log.user_id')
                    ->andWhere(['>=', 'login_time', trim($from)])
                    ->andWhere(['<=', 'logout_time', trim($to)])
                    ->andWhere(['admin_master.adm_is_admin' => 'agent'])
                    ->andWhere(new Expression('FIND_IN_SET(ccc.cmp_id, campaign_name)')),
                'total_calls' => CampaignCdrReport::find()->select('count(id)')
                   ->andWhere(['>=', 'start_time', trim($from)])
                   ->andWhere(['<=', 'start_time', trim($to)])
                   ->andWhere('(CASE WHEN current_active_camp IS NULL THEN camp_cdr.camp_name ELSE current_active_camp END) = ccc.cmp_id'),
                'live_calls' => CampaignCdrReport::find()->select('count(id)')
                   ->andWhere(['>=', 'start_time', trim($from)])
                   ->andWhere(['<=', 'start_time', trim($to)])
                   ->andWhere('(CASE WHEN current_active_camp IS NULL THEN camp_cdr.camp_name ELSE current_active_camp END) = ccc.cmp_id')
                   ->andWhere(['OR',['IS NOT', 'ans_time', null], ['!=', 'ans_time', ''], ['!=', 'ans_time', '0000-00-00 00:00:00']])
                   ->andWhere(['OR',['IS', 'end_time', null], ['=', 'end_time', ''], ['=', 'end_time', '0000-00-00 00:00:00']]),
                'answered' => CampaignCdrReport::find()->select('count(id)')
                    ->andWhere(['>=', 'start_time', trim($from)])
                    ->andWhere(['<=', 'start_time', trim($to)])
                    ->andWhere('(CASE WHEN current_active_camp IS NULL THEN camp_cdr.camp_name ELSE current_active_camp END) = ccc.cmp_id')
                    ->andWhere(['OR',['IS NOT', 'ans_time', null], ['!=', 'ans_time', ''], ['!=', 'ans_time', '0000-00-00 00:00:00']]),
                'abandoned' => CampaignCdrReport::find()->select('count(id)')
                   ->andWhere(['>=', 'start_time', trim($from)])
                   ->andWhere(['<=', 'start_time', trim($to)])
                   ->andWhere('(CASE WHEN current_active_camp IS NULL THEN camp_cdr.camp_name ELSE current_active_camp END) = ccc.cmp_id')
                   ->andWhere(['OR',['IS', 'ans_time', null], ['=', 'ans_time', ''], ['=', 'ans_time', '0000-00-00 00:00:00']]),
                   //->andWhere(['OR',['IS NOT', 'end_time', null], ['!=', 'end_time', ''], ['!=', 'end_time', '0000-00-00 00:00:00']]),
                /*'total_leads' => LeadGroupMember::find()->select('count(lg_id)')
                    ->andWhere('ct_lead_group_member.ld_id = ccc.cmp_lead_group'),
                'dial_leads' => LeadGroupMember::find()->select('count(lg_id)')
                    ->leftJoin('camp_cdr cc', 'ct_lead_group_member.lg_contact_number = cc.dial_number')
                    ->andWhere(['>=', 'cc.start_time', trim($from)])
                    ->andWhere(['<=', 'cc.end_time', trim($to)])
                    ->andWhere('ct_lead_group_member.ld_id = ccc.cmp_lead_group'),
                "(SELECT COUNT(*)
                            FROM ct_redial_calls AS rc
                            WHERE rc.ld_id = ccc.cmp_lead_group
                            AND updated_date >= '".$from."' AND updated_date <= '".$to."'
                            AND rc.rd_status = 1
                    ) AS rechurn_leads",
                'contacted_leads' => CampaignCdrReport::find()->select('count(id)')
                    ->andWhere(['>=', 'start_time', trim($from)])
                    ->andWhere(['<=', 'end_time', trim($to)])
                    ->andWhere('(CASE WHEN current_active_camp IS NULL THEN camp_cdr.camp_name ELSE current_active_camp END) = ccc.cmp_id')
                    ->andWhere(['call_disposition_category' => 1]),
                'noncontacted_leads' => CampaignCdrReport::find()->select('count(id)')
                    ->andWhere(['>=', 'start_time', trim($from)])
                    ->andWhere(['<=', 'end_time', trim($to)])
                    ->andWhere('(CASE WHEN current_active_camp IS NULL THEN camp_cdr.camp_name ELSE current_active_camp END) = ccc.cmp_id')
                    ->andWhere(['call_disposition_category' => 2]),*/
                'avg_call_duration' => CampaignCdrReport::find()->select(new Expression('AVG(TIMESTAMPDIFF(SECOND, start_time, end_time))'))
                    ->andWhere(['>=', 'start_time', trim($from)])
                    ->andWhere(['<=', 'end_time', trim($to)])
                    ->andWhere('(CASE WHEN current_active_camp IS NULL THEN camp_cdr.camp_name ELSE current_active_camp END) = ccc.cmp_id'),
                'avg_wrap_up_time' => CampaignCdrReport::find()->select(new Expression('AVG(TIMESTAMPDIFF(SECOND, end_time, call_disposion_start_time))'))
                    ->andWhere(['>=', 'start_time', trim($from)])
                    ->andWhere(['<=', 'end_time', trim($to)])
                    ->andWhere('(CASE WHEN current_active_camp IS NULL THEN camp_cdr.camp_name ELSE current_active_camp END) = ccc.cmp_id'),
                'avg_wait_time' => CampaignCdrReport::find()->select(new Expression('AVG(TIMESTAMPDIFF(SECOND, start_time, ans_time))'))
                    ->andWhere(['>=', 'start_time', trim($from)])
                    ->andWhere(['<=', 'end_time', trim($to)])
                    ->andWhere('(CASE WHEN current_active_camp IS NULL THEN camp_cdr.camp_name ELSE current_active_camp END) = ccc.cmp_id'),

            ])
            ->from('ct_call_campaign ccc')
            ->andWhere(['IN', 'ccc.cmp_id', $campaignList])
            ->andWhere(['ccc.cmp_status' => 'Active'])
            ->groupBy(['ccc.cmp_id']);
//print_r($query->createCommand()->getRawSql());exit;
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => false,
            'pagination' => false,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        if (isset($this->cmp_id) && !empty($this->cmp_id)) {
            $query->andFilterWhere(['cmp_id' => $this->cmp_id]);
        }

        return $dataProvider;
    }
}
