<?php

namespace app\modules\ecosmob\calltimedistributionreport\controllers;


use app\modules\ecosmob\queue\models\QueueMaster;
use Yii;
use yii\base\InvalidParamException;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use app\modules\ecosmob\calltimedistributionreport\models\CallTimeDistributionReport;
use app\modules\ecosmob\calltimedistributionreport\models\CallTimeDistributionReportSearch;
use app\components\CommonHelper;
use app\modules\ecosmob\globalconfig\models\GlobalConfig;

/**
 * Class CallTimeDistributionReportController
 *
 * @package app\modules\ecosmob\calltimedistributionreport\controllers
 */
class CallTimeDistributionReportController extends Controller
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
        $searchModel = new CallTimeDistributionReportSearch();
        $searchModel->queue_started = date('Y-m-d 00:00:00');
        $searchModel->queue_ended = CommonHelper::tsToDt(date('Y-m-d H:i:s'));
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        Yii::$app->session->set('calltimedistributionreportquery', $dataProvider->allModels);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionExport()
    {
        ini_set("memory_limit", -1);
        $fp = fopen('php://temp', 'w');

        $fileName = "Call_Time_Distribution_" . time() . ".csv";
        $model = new CallTimeDistributionReport();

        $query = Yii::$app->session->get('calltimedistributionreportquery');
        $query =  array_slice($query, 0, GlobalConfig::getValueByKey('export_limit'));

        $records = $query;


        $attr = [
            'queue',
            'total_calls',
            'avg_waiting_time',
            'answer_call_30',
            'drop_call_30',
            'answer_call_60',
            'drop_call_60',
            'answer_call_3',
            'drop_call_3',
            'answer_call_5',
            'drop_call_5',
            'answer_call_5_plush',
            'drop_call_5_plush',
        ];

        $row = [];
        foreach ($attr as $header) {
            $row[] = $model->getAttributeLabel($header);
        }
        fputcsv($fp, $row);
        if (!empty($records)) {
            foreach ($records as $key => $record) {
                $row = [];
                foreach ($attr as $head) {

                    if ($head == 'queue') {
                        $row[$head] = (!empty($record['queue']) ? QueueMaster::getQueueName($record['queue']) : '-');
                    }
                    if ($head == 'total_calls') {
                        $row[$head] = $record['total_calls'];
                    }
                    if ($head == 'avg_waiting_time') {
                        $row[$head] = (!empty($record['avg_waiting_time']) ? gmdate("H:i:s", $record['avg_waiting_time']) : '-');
                    }
                    if ($head == 'answer_call_30') {
                        $row[$head] = (!empty($record['answer_call_30']) ? $record['answer_call_30'] : '0');
                    }
                    if ($head == 'drop_call_30') {
                        $row[$head] = (!empty($record['drop_call_30']) ? $record['drop_call_30'] : '0');
                    }
                    if ($head == 'answer_call_60') {
                        $row[$head] = (!empty($record['answer_call_60']) ? $record['answer_call_60'] : '0');
                    }
                    if ($head == 'drop_call_60') {
                        $row[$head] = (!empty($record['drop_call_60']) ? $record['drop_call_60'] : '0');
                    }
                    if ($head == 'answer_call_3') {
                        $row[$head] = (!empty($record['answer_call_3']) ? $record['answer_call_3'] : '0');
                    }
                    if ($head == 'drop_call_3') {
                        $row[$head] = (!empty($record['drop_call_3']) ? $record['drop_call_3'] : '0');
                    }
                    if ($head == 'answer_call_5') {
                        $row[$head] = (!empty($record['answer_call_5']) ? $record['answer_call_5'] : '0');
                    }
                    if ($head == 'drop_call_5') {
                        $row[$head] = (!empty($record['drop_call_5']) ? $record['drop_call_5'] : '0');
                    }
                    if ($head == 'answer_call_5_plush') {
                        $row[$head] = (!empty($record['answer_call_5_plush']) ? $record['answer_call_5_plush'] : '0');
                    }
                    if ($head == 'drop_call_5_plush') {
                        $row[$head] = (!empty($record['drop_call_5_plush']) ? $record['drop_call_5_plush'] : '0');
                    }
                }
                fputcsv($fp, $row);
            }
        }
        $total = [
            "Total",
            CallTimeDistributionReport::getTotal($query, 'total_calls'),
            gmdate("H:i:s", CallTimeDistributionReport::getTotal($query, 'avg_waiting_time')),
            CallTimeDistributionReport::getTotal($query, 'answer_call_30'),
            CallTimeDistributionReport::getTotal($query, 'drop_call_30'),
            CallTimeDistributionReport::getTotal($query, 'answer_call_60'),
            CallTimeDistributionReport::getTotal($query, 'drop_call_60'),
            CallTimeDistributionReport::getTotal($query, 'answer_call_3'),
            CallTimeDistributionReport::getTotal($query, 'drop_call_3'),
            CallTimeDistributionReport::getTotal($query, 'answer_call_5'),
            CallTimeDistributionReport::getTotal($query, 'drop_call_5'),
            CallTimeDistributionReport::getTotal($query, 'answer_call_5_plush'),
            CallTimeDistributionReport::getTotal($query, 'drop_call_5_plush')
        ];
        $row = [];
        foreach ($total as $_total) {
            $row[] = $_total;
        }
        fputcsv($fp, $row);
        rewind($fp);
        header('Content-type: application/csv');
        header('Content-Disposition: attachment; filename=' . $fileName);
        $file = stream_get_contents($fp);
        echo $file;
        fclose($fp);
        exit;
    }
}