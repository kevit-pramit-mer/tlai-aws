<?php

namespace app\modules\ecosmob\agentperformancereport\controllers;

use app\modules\ecosmob\globalconfig\models\GlobalConfig;
use Yii;
use yii\base\InvalidParamException;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use app\modules\ecosmob\agentperformancereport\models\AgentPerformanceReport;
use app\modules\ecosmob\agentperformancereport\models\AgentPerformanceReportSearch;
/**
 * Class AgentPerformanceReportController
 *
 * @package app\modules\ecosmob\agentperformancereport\controllers
 */
class AgentPerformanceReportController extends Controller
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
                            'export'
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
        $searchModel = new AgentPerformanceReportSearch();
        $searchModel->from = date('Y-m-d');
        $searchModel->to = date('Y-m-d');
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $queryParams = Yii::$app->request->queryParams;
        Yii::$app->session->set('agentperformancereportquery', $dataProvider->query);
        if(isset($queryParams['AgentPerformanceReportSearch'])) {
            Yii::$app->session->set('from', $queryParams['AgentPerformanceReportSearch']['from']);
            Yii::$app->session->set('to', $queryParams['AgentPerformanceReportSearch']['to']);
        }else{
            Yii::$app->session->set('from', '');
            Yii::$app->session->set('to', '');
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionExport()
    {
        ini_set("memory_limit", -1);
        $fp = fopen('php://temp', 'w');

        $fileName = "Agent_Performance_Report_" . time() . ".csv";
        $model = new AgentPerformanceReport();

        $from = Yii::$app->session->get('from');
        $to = Yii::$app->session->get('to');

        $query = Yii::$app->session->get('agentperformancereportquery');
        $query->limit(GlobalConfig::getValueByKey('export_limit'));

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [ 'defaultOrder' => ["agent" => SORT_ASC],
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
            "total_call",
            "answered",
            "abandoned",
            "break_time",
            "avg_break_time",
            "wait_time",
            "avg_wait_time",
            "call_duration",
            "avg_call_duration",
            "disposion_time",
            "avg_disposion_time",
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
                        $row[$head] = (!empty($record->agent) ? $record->agent : '-');
                    }
                    if ($head == 'total_call') {
                        $row[$head] = $record->total_call;
                    }
                    if ($head == 'answered') {
                        $row[$head] = $record->answered;
                    }
                    if ($head == 'abandoned') {
                        $row[$head] = $record->abandoned;
                    }
                    if ($head == 'break_time') {
                        $totalBreakTime = 0;
                        if ($from && $to) {
                            $totalBreakTime = AgentPerformanceReport::getTotalBreakTime($from, $to, $record->agent_id);
                        } else {
                            $totalBreakTime = AgentPerformanceReport::getTotalBreakTime(date('Y-m-d'), date('Y-m-d'), $record->agent_id);
                        }
                        $row[$head] =  ($totalBreakTime ? date("H:i:s", $totalBreakTime) : '-');
                        //$row[$head] = (!empty($record->break_time) ? gmdate("H:i:s", $record->break_time) : '-');
                    }
                    if ($head == 'avg_break_time') {
                        $avgBreakTime = 0;
                        if ($from && $to) {
                            $avgBreakTime = AgentPerformanceReport::getAvgBreakTime($from, $to, $record->agent_id);
                        } else {
                            $avgBreakTime = AgentPerformanceReport::getAvgBreakTime(date('Y-m-d'), date('Y-m-d'), $record->agent_id);
                        }
                        $row[$head] = ($avgBreakTime ? date("H:i:s", $avgBreakTime) : '-');
                        //$row[$head] = (!empty($record->avg_break_time) ? gmdate("H:i:s", $record->avg_break_time) : '-');
                    }
                    if ($head == 'wait_time') {
                        $row[$head] = (!empty($record->wait_time) ? gmdate("H:i:s", $record->wait_time) : '-');
                    }
                    if ($head == 'avg_wait_time') {
                        $row[$head] = (!empty($record->avg_wait_time) ? gmdate("H:i:s", $record->avg_wait_time) : '-');
                    }
                    if ($head == 'call_duration') {
                        $row[$head] = (!empty($record->call_duration) ? gmdate("H:i:s", $record->call_duration) : '-');
                    }
                    if ($head == 'avg_call_duration') {
                        $row[$head] = (!empty($record->avg_call_duration) ? gmdate("H:i:s", $record->avg_call_duration) : '-');
                    }
                    if ($head == 'disposion_time') {
                        $row[$head] = (!empty($record->disposion_time) ? gmdate("H:i:s", $record->disposion_time) : '-');
                    }
                    if ($head == 'avg_disposion_time') {
                        $row[$head] = (!empty($record->avg_disposion_time) ? gmdate("H:i:s", $record->avg_disposion_time) : '-');
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
}