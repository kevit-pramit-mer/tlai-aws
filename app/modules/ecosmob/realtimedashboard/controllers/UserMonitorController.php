<?php

namespace app\modules\ecosmob\realtimedashboard\controllers;

use app\components\CommonHelper;
use app\components\WebUser;
use app\models\SipPresence;
use app\models\SipRegistrations;
use app\modules\ecosmob\activecalls\models\ActiveCalls;
use app\modules\ecosmob\agent\models\Agent;
use app\modules\ecosmob\agents\models\CampaignMappingAgents;
use app\modules\ecosmob\auth\models\AdminMaster;
use app\modules\ecosmob\callhistory\models\CampCdr;
use app\modules\ecosmob\cdr\models\Cdr;
use app\modules\ecosmob\crm\models\AgentDispositionMapping;
use app\modules\ecosmob\dispositionType\models\DispositionType;
use app\modules\ecosmob\extension\models\Extension;
use app\modules\ecosmob\queue\models\Tiers;
use app\modules\ecosmob\realtimedashboard\models\CampaignPerformance;
use app\modules\ecosmob\realtimedashboard\models\UserMonitor;
use app\modules\ecosmob\supervisor\models\BreakReasonMapping;
use app\modules\ecosmob\supervisor\models\UsersActivityLog;
use Yii;
use yii\base\InvalidArgumentException;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use DateTime;
use app\models\Channels;
use yii\web\User;

/**
 * Class UserMonitorController
 *
 * @package app\modules\ecosmob\realtimedashboard\controllers
 */
class UserMonitorController extends Controller
{

    CONST LOGOUT_FROM_ADMIN_DISPOSITION = 1;
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [
                            'index',
                            'export',
                            'get-data',
                            'force-logout'
                        ],
                        'allow' => TRUE,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }


    /**
     * @return string
     * @throws InvalidArgumentException
     */
    public function actionIndex()
    {
        $searchModel = new UserMonitor();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        Yii::$app->session->set('userMonitorQuery', $dataProvider->query);

        $userdata = $this->getUserData();

        return $this->render('index',
            [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'loginUser' => $userdata['loginUser'],
                'availableUser' => $userdata['availableUser'],
                'inCallUser' => $userdata['inCallUser'],
            ]);
    }

    public function actionExport(){
        ini_set("memory_limit", -1);
        $fp = fopen('php://temp', 'w');

        $fileName = "Realtime_Agent_Monitor_" . time() . ".csv";

        $model = new UserMonitor();

        $query = Yii::$app->session->get('userMonitorQuery');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);
        $records = $dataProvider->query->all();

        $attr = [
            'agent',
            'extension_number',
            'customer_number',
            'status',
            'cmp_name',
            'queue',
            'total_calls',
            'total_talk_time',
            'avg_call_duration',
            'total_idle_time',
            'avg_wait_time',
            'login_hour',
            'total_break_time',
            'total_breaks',
        ];

        $row = [];
        foreach ($attr as $header) {
            $row[] = $model->getAttributeLabel($header);
        }

        fputcsv($fp, $row);

        if (!empty($records)) {
            foreach ($records as $record) {
                $row = [];
                foreach ($attr as $head) {
                    $row[$head] = $record->$head;
                    if ($head == 'agent') {
                        $row[$head] = $record->agent;
                    }
                    if ($head == 'extension_number') {
                        $row[$head] = $record->extension_number;
                    }
                    if ($head == 'customer_number') {
                        $row[$head] = $record->customer_number;
                    }
                    if ($head == 'status') {
                        $row[$head] = $record->status;
                    }
                    if ($head == 'cmp_name') {
                        $row[$head] = $record->cmp_name;
                    }
                    if ($head == 'queue') {
                        $row[$head] = $record->queue;
                    }
                    if ($head == 'total_calls') {
                        $row[$head] = $record->total_calls;
                    }
                    if ($head == 'total_talk_time') {
                        $row[$head] = (!empty($record->total_talk_time) ? gmdate("H:i:s", $record->total_talk_time) : '-');;
                    }
                    if ($head == 'avg_call_duration') {
                        $row[$head] = (!empty($record->avg_call_duration) ? gmdate("H:i:s", $record->avg_call_duration) : '-');;
                    }
                    if ($head == 'total_idle_time') {
                        $row[$head] = gmdate("H:i:s", $record->login_hour - (!empty($record->total_talk_time) ? $record->total_talk_time : 0));
                    }
                    if ($head == 'avg_wait_time') {
                        $row[$head] = (!empty($record->avg_wait_time) ? gmdate("H:i:s", $record->avg_wait_time) : '-');
                    }
                    if ($head == 'login_hour') {
                        $row[$head] = (!empty($record->login_hour) ? gmdate("H:i:s", $record->login_hour) : '-');
                    }
                    if ($head == 'total_break_time') {
                        $row[$head] = (!empty($record->total_break_time) ? gmdate("H:i:s", $record->total_break_time) : '-');
                    }
                    if ($head == 'total_breaks') {
                        $row[$head] = $record->total_breaks;
                    }

                }
                fputcsv($fp, $row);
            }
        }

        rewind($fp);
        header('Content-type: application/csv');
        header('Content-Disposition: attachment; filename=' . $fileName);
        $file = stream_get_contents($fp);
        echo $file;
        fclose($fp);
        exit;
    }

    public function getUserdata(){

        $loginUser = UsersActivityLog::find()
            ->leftJoin('admin_master am', 'am.adm_id = users_activity_log.user_id')
            ->where(['DATE(users_activity_log.login_time)' => date('Y-m-d'), 'users_activity_log.logout_time' => '0000-00-00 00:00:00', 'am.adm_status' => '1', 'am.adm_is_admin' => 'agent'])
            ->groupBy('users_activity_log.user_id')
            ->count();

        $availableUser = UsersActivityLog::find()
            ->leftJoin('admin_master am', 'am.adm_id = users_activity_log.user_id')
            ->leftJoin('agents', 'SUBSTRING_INDEX(agents.name, \'_\', 1) = users_activity_log.user_id')
            ->where(['DATE(users_activity_log.login_time)' => date('Y-m-d'), 'logout_time' => '0000-00-00 00:00:00', 'am.adm_status' => '1',
                'adm_is_admin' => 'agent', 'status' => 'Available', 'state' => 'Waiting'])
            ->groupBy('users_activity_log.user_id')
            ->count();

        $inCallUser = UsersActivityLog::find()->where([])
            ->leftJoin('admin_master am', 'am.adm_id = users_activity_log.user_id')
            ->leftJoin('agents', 'SUBSTRING_INDEX(agents.name, \'_\', 1) = users_activity_log.user_id')
            ->where(['DATE(users_activity_log.login_time)' => date('Y-m-d'), 'logout_time' => '0000-00-00 00:00:00', 'am.adm_status' => '1',
                'adm_is_admin' => 'agent', 'status' => 'Available', 'state' => 'In a queue call'])
            ->groupBy('users_activity_log.user_id')
            ->count();

        return ['loginUser' => $loginUser, 'availableUser' => $availableUser, 'inCallUser' => $inCallUser];
    }

    public function actionGetData()
    {
        $data = $this->getUserData();
        return json_encode($data);
    }

    public function actionForceLogout($id)
    {
        $count = 0;
        if (!empty($id)) {
            $admin = AdminMaster::findOne($id);
            $agentId = $id . '_' . $GLOBALS['tenantID'];
            $agent = Agent::find()->andWhere(['name' => $agentId])
                ->andWhere(['status' => 'Logged Out', 'state' => 'Waiting'])->asArray()->one();
            if (!empty($admin) && empty($agent)) {
                $activityLog = UsersActivityLog::find()
                    ->andWhere(['user_id' => $id])
                    ->andWhere(['logout_time' => '0000-00-00 00:00:00'])
                    ->asArray()->all();
                if(empty($activityLog)){
                    Yii::$app->db->createCommand()
                        ->update('agents', (['status' => 'Logged Out', 'state' => 'Waiting']), ['name' => $agentId])
                        ->execute();
                    return $this->redirect(['index']);
                }else {
                    $from = CommonHelper::DtTots(date('Y-m-d H:i:s', strtotime(date('Y-m-d 00:00:00'))));
                    $to = CommonHelper::DtTots(date('Y-m-d H:i:s', strtotime(date('Y-m-d 23:59:59'))));
                    $campCdr = CampCdr::find()->andWhere(['agent_id' => $id])
                        ->andWhere(['or',
                            ['>=', 'start_time', trim($from)],
                            ['>=', 'queue_join_time', trim($to)]
                        ])->andWhere(['IS', 'end_time', null])->all();
                    if (!empty($campCdr)) {
                        $dispositionType = DispositionType::find()->where(['is_default' => self::LOGOUT_FROM_ADMIN_DISPOSITION])->one();
                        foreach ($campCdr as $_campCdr) {

                            $leadId = (!empty($_campCdr->lead_member_id) ? $_campCdr->lead_member_id : null);
                            $campId = (!empty($_campCdr->current_active_camp) ? $_campCdr->current_active_camp : $_campCdr->camp_name);
                            $dsId = $dispositionType->ds_type_id;

                            $agentMapping = new AgentDispositionMapping();
                            $agentMapping->agent_id = $id;
                            $agentMapping->lead_id = $leadId;
                            $agentMapping->disposition = $dsId;
                            $agentMapping->campaign_id = $campId;
                            if ($agentMapping->save(false)) {
                                $_campCdr->end_time = date('Y-m-d H:i:s');
                                $_campCdr->call_disposion_start_time = date('Y-m-d H:i:s');
                                $_campCdr->call_disposion_name = $dispositionType->ds_type;
                                $_campCdr->call_disposition_category = 1;
                                if ($_campCdr->save(false)) {
                                    $count++;
                                }
                            }
                        }
                        Yii::$app->db->createCommand()
                            ->delete('active_calls', ['agent' => $id])
                            ->execute();
                    } else {
                        $count++;
                    }

                    if ($count > 0) {
                        Yii::$app->db->createCommand()
                            ->update('agents', (['status' => 'Logged Out', 'state' => 'Waiting']), ['name' => $agentId])
                            ->execute();
                        UserMonitorController::agentLogoutEntry($id);
                        Yii::$app->session->destroySession($id);
                        $admin->adm_token = Yii::$app->security->generateRandomString();
                        $admin->save(false);
                        return $this->redirect(['index']);
                    }
                }
            }else{
                UserMonitorController::agentLogoutEntry($id);
            }
        }

        return $this->redirect(['index']);
    }

    public static function agentLogoutEntry($id){
        $agentName = $id . '_' . $GLOBALS['tenantID'];
        Tiers::deleteAll(['agent' => $agentName]);
        Yii::$app->db->createCommand()
            ->update('break_reason_mapping', (['break_status' => 'Out', 'out_time' => date('Y-m-d H:i:s')]), ['user_id' => $id, 'out_time' => '0000-00-00 00:00:00'])
            ->execute();
        Yii::$app->db->createCommand()
            ->update('users_activity_log', (['logout_time' => date('Y-m-d H:i:s')]), ['user_id' => $id, 'logout_time' => '0000-00-00 00:00:00'])
            ->execute();
    }
}
