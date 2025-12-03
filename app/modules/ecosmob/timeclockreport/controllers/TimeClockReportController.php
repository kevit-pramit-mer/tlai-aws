<?php

namespace app\modules\ecosmob\timeclockreport\controllers;

use app\modules\ecosmob\supervisor\models\BreakReasonMapping;
use app\modules\ecosmob\supervisor\models\UsersActivityLog;
use app\modules\ecosmob\timeclockreport\models\TimeClockReport;
use app\modules\ecosmob\timeclockreport\models\TimeClockReportSearch;
use Yii;
use yii\base\InvalidParamException;
use yii\data\ActiveDataProvider;
use yii\db\Expression;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use app\components\CommonHelper;
use app\modules\ecosmob\campaign\models\CampaignMappingUser;
use app\modules\ecosmob\agents\models\CampaignMappingAgents;
/**
 * Class TimeClockReportController
 *
 * @package app\modules\ecosmob\timeclockreport\controllers
 */
class TimeClockReportController extends Controller
{

    /**
     * @return array
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
                            'agent-detail'
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * @return string
     * @throws InvalidParamException
     */
    public function actionIndex()
    {
        $searchModel = new TimeClockReportSearch();
        $searchModel->from = date('Y-m-d');
        $searchModel->to = date('Y-m-d');
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        Yii::$app->session->set('from', $searchModel->from);
        Yii::$app->session->set('to', $searchModel->to);
        Yii::$app->session->set('user_id', $searchModel->user_id);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionExport()
    {
        ini_set("memory_limit", -1);
        $fp = fopen('php://temp', 'w');

        $fileName = "Time_Clock_Report_" . time() . ".csv";
        $model = new TimeClockReportSearch();

        $from = Yii::$app->session->get('from') != "" ? Yii::$app->session->get('from') : date('Y-m-d');

        $to = Yii::$app->session->get('from') != "" ? Yii::$app->session->get('to') : date('Y-m-d');

        $users = [];

        if(Yii::$app->user->identity->adm_is_admin == 'supervisor'){
            $supCamp = CampaignMappingUser::find()->select(['campaign_id'])->where(['supervisor_id' => Yii::$app->user->identity->id])->asArray()->all();
            $superCamp = array_column($supCamp, 'campaign_id');
            $agent = CampaignMappingAgents::find()->select(['agent_id'])->where(['IN', 'campaign_id', $superCamp])->asArray()->all();
            $agents = array_column($agent, 'agent_id');
            $user = UsersActivityLog::find()->select('user_id')->andWhere(['>=', 'DATE(login_time)', $from])
                ->andWhere(['<=', 'DATE(login_time)', $to])->andWhere(['IN', 'user_id', $agents])->groupBy('user_id')->all();
            if (!empty($user)) {
                foreach ($user as $_user) {
                    $users[] = $_user->user_id;
                }
            }
        }else {
            $user = UsersActivityLog::find()->select('user_id')->andWhere(['>=', 'DATE(login_time)', $from])
                ->andWhere(['<=', 'DATE(login_time)', $to])->groupBy('user_id')->all();
            if (!empty($user)) {
                foreach ($user as $_user) {
                    $users[] = $_user->user_id;
                }
            }
        }

        $query  = TimeClockReport::find()
            ->select([
                'ua.user_id',
                'CONCAT(am.adm_firstname, " ", am.adm_lastname) as agent',
                'login_time' => TimeClockReport::find()
                    ->select('login_time')
                    ->andWhere('DATE(login_time) >= DATE(ua.login_time)')
                    ->andWhere('DATE(login_time) <= DATE(ua.login_time)')
                    ->andWhere('user_id = am.adm_id')
                    ->orderBy(['login_time' => SORT_ASC])
                    ->limit(1),
                'logout_time' => TimeClockReport::find()
                    ->select('logout_time')
                    ->andWhere('DATE(login_time) >= DATE(ua.login_time)')
                    ->andWhere('DATE(login_time) <= DATE(ua.login_time)')
                    ->andWhere('user_id = am.adm_id')
                    ->orderBy(['login_time' => SORT_DESC])
                    ->limit(1),
                'total_break_time' => BreakReasonMapping::find()
                    ->select(['SUM(TIMESTAMPDIFF(SECOND, in_time, out_time))'])
                    ->andWhere('DATE(in_time) >= DATE(ua.login_time)')
                    ->andWhere('DATE(out_time) <= DATE(ua.login_time)')
                    ->andWhere('user_id = am.adm_id'),
                'total_breaks' => BreakReasonMapping::find()
                    ->select(['count(id)'])
                    ->andWhere('DATE(in_time) >= DATE(ua.login_time)')
                    ->andWhere('DATE(out_time) <= DATE(ua.login_time)')
                    ->andWhere('user_id = am.adm_id'),
            ])
            ->from('users_activity_log ua')
            ->leftJoin('admin_master am', 'am.adm_id = ua.user_id')
            ->andWhere(['am.adm_status' => '1'])
            ->andWhere(['IN', 'ua.user_id', $users])
            ->andWhere(['IN', 'am.adm_is_admin', ['agent', 'supervisor']])
            ->andWhere(['>=', 'DATE(login_time)', $from])
            ->andWhere(['<=', 'DATE(login_time)', $to])
            ->groupBy(['ua.user_id', 'DATE(login_time)'])
            ->orderBy('login_time DESC');


        if (Yii::$app->session->get('user_id') != "") {
            $query->andFilterWhere(['ua.user_id' => Yii::$app->session->get('user_id')]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [ 'defaultOrder' => ["agent" => SORT_DESC],
                'attributes' => [
                    'agent' => [
                        'asc' => ['agent' => SORT_ASC],
                        'desc' => ['agent' => SORT_DESC],
                    ],
                ]
            ],
            'pagination' => false,
        ]);

        $records = $dataProvider->getModels();

        $attr = [
            "agent",
            "login_time",
            "logout_time",
            'total_log_hours',
            'total_break_time',
            'total_breaks'
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
                    $date = date('Y-m-d', strtotime($record->login_time));
                    $hours = TimeClockReport::getTotalLogHours($date, $date, $record->user_id);

                    if ($head == 'agent') {
                        $row[$head] = (!empty($record->agent) ? $record->agent : '-');
                    }
                    if ($head == 'login_time') {
                        $row[$head] = CommonHelper::tsToDt($record->login_time);
                    }
                    if ($head == 'logout_time') {
                        $row[$head] = $record->logout_time == '0000-00-00 00:00:00' ? '-'/*date('Y-m-d H:i:s')*/ : CommonHelper::tsToDt($record->logout_time);
                    }
                    if ($head == 'total_log_hours') {
                        $row[$head] = gmdate('H:i:s', $hours);
                    }
                    if ($head == 'total_break_time') {
                        $row[$head] = (!empty($record->total_break_time) ? date("H:i:s", $record->total_break_time) : '-');
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

    public function actionAgentDetail()
    {

        $agentLog = TimeClockReport::find()
            ->select([
                'ua.user_id',
                'CONCAT(am.adm_firstname, " ", am.adm_lastname) as agent',
                'login_time' => TimeClockReport::find()
                    ->select('login_time')
                    ->andWhere('DATE(login_time) >= DATE(ua.login_time)')
                    ->andWhere('DATE(login_time) <= DATE(ua.login_time)')
                    ->andWhere('user_id = am.adm_id')
                    ->orderBy(['login_time' => SORT_ASC])
                    ->limit(1),
                'logout_time' => TimeClockReport::find()
                    ->select('logout_time')
                    ->andWhere('DATE(login_time) >= DATE(ua.login_time)')
                    ->andWhere('DATE(login_time) <= DATE(ua.login_time)')
                    ->andWhere('user_id = am.adm_id')
                    ->orderBy(['login_time' => SORT_DESC])
                    ->limit(1),
                'total_break_time' => BreakReasonMapping::find()
                    ->select(['SUM(TIMESTAMPDIFF(SECOND, in_time, out_time))'])
                    ->andWhere('DATE(in_time) >= DATE(ua.login_time)')
                    ->andWhere('DATE(out_time) <= DATE(ua.login_time)')
                    ->andWhere('user_id = am.adm_id'),
                'total_breaks' => BreakReasonMapping::find()
                    ->select(['count(id)'])
                    ->andWhere('DATE(in_time) >= DATE(ua.login_time)')
                    ->andWhere('DATE(out_time) <= DATE(ua.login_time)')
                    ->andWhere('user_id = am.adm_id'),
            ])
            ->from('users_activity_log ua')
            ->leftJoin('admin_master am', 'am.adm_id = ua.user_id')
            ->andWhere(['am.adm_status' => '1'])
            ->andWhere(['ua.user_id' => $_POST['id']])
            ->andWhere(['>=', 'DATE(login_time)', trim($_POST['from'])])
            ->andWhere(['<=', 'DATE(login_time)', trim($_POST['to'])])
            ->groupBy('DATE(login_time)')
            ->orderBy('login_time DESC')
            ->all();

        $rows = '<table class="table table-bordered table-responsive">';
        $rows .= '<thead><tr><th>User</th>';
        $rows .= '<th>Logged In Time</th>';
        $rows .= '<th>Logout Time</th>';
        $rows .= '<th>Total Logged In Hours</th>';
        $rows .= '<th>Total Break Hours</th>';
        $rows .= '<th>Total Breaks</th></tr>';
        $rows .= '<tbody>';
        if (!empty($agentLog)) {
            foreach ($agentLog as $_agentLog) {
                $date = date('Y-m-d', strtotime($_agentLog->login_time));
                $hours = TimeClockReport::getTotalLogHours($date, $date, $_agentLog->user_id);

                    $rows .= '<tr style="text-align:center;">';
                    $rows .= '<td>'.$_agentLog->agent.'</td>';
                    $rows .= '<td>'.CommonHelper::tsToDt($_agentLog->login_time).'</td>';
                    $rows .= '<td>'.($_agentLog->logout_time == '0000-00-00 00:00:00' ? "-"/*date('Y-m-d H:i:s')*/ : CommonHelper::tsToDt($_agentLog->logout_time)).'</td>';
                    $rows .= '<td>'.gmdate('H:i:s', $hours).'</td>';
                    $rows .= '<td>'.(!empty($_agentLog->total_break_time) ? date('H:i:s', $_agentLog->total_break_time) : "-").'</td>';
                    $rows .= '<td>'.$_agentLog->total_breaks.'</td>';
                    $rows .= '</tr>';
            }
        } else {
            $rows .= '<tr><td colspan=7 align="center">'.Yii::t('app', 'record.notFound').'</td></tr>';
        }
        $rows .= '</tbody>';
        $rows .= '</table>';
        echo json_encode($rows);
        exit;
    }
}
