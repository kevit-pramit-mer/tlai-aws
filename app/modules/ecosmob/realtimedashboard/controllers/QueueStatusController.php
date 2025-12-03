<?php

namespace app\modules\ecosmob\realtimedashboard\controllers;

use app\models\Channels;
use app\modules\ecosmob\realtimedashboard\models\QueueStatusReport;
use Yii;
use yii\base\InvalidArgumentException;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;

/**
 * Class QueueStatusController
 *
 * @package app\modules\ecosmob\realtimedashboard\controllers
 */
class QueueStatusController extends Controller
{

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
                            'export'
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
        $queueStatusReport = new QueueStatusReport();
        $queueStatusDataProvider = $queueStatusReport->search(Yii::$app->request->queryParams);

        Yii::$app->session->set('queueStatusQuery', $queueStatusDataProvider->query);

        return $this->render('index',
            [
                'searchModel' => $queueStatusReport,
                'dataProvider' => $queueStatusDataProvider
            ]);
    }

    public function actionExport(){
        ini_set("memory_limit", -1);
        $fp = fopen('php://temp', 'w');

        $fileName = "Realtime_Queue_Status" . time() . ".csv";

        $model = new QueueStatusReport();

        $query = Yii::$app->session->get('queueStatusQuery');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);
        $records = $dataProvider->query->all();

        $attr = [
            "queue",
            "total_calls",
            "calls_in_queue",
            "abandoned_calls",
            "logged_in_agents",
            "avg_call_duration",
            "avg_queue_wait_time",
            "longest_queue_wait_time",
            "longest_abandoned_calls_wait_time",
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
                    if ($head == 'queue') {
                        $row[$head] = $record->queue;
                    }
                    if ($head == 'total_calls') {
                        $row[$head] = $record->total_calls;
                    }
                    if ($head == 'calls_in_queue') {
                        $row[$head] = $record->calls_in_queue;
                    }
                    if ($head == 'abandoned_calls') {
                        $row[$head] = $record->abandoned_calls;
                    }
                    if ($head == 'logged_in_agents') {
                        $row[$head] = $record->logged_in_agents;
                    }
                    if ($head == 'avg_call_duration') {
                        $row[$head] = ((!empty($record->avg_call_duration) && $record->avg_call_duration > 0) ? gmdate('H:i:s', $record->avg_call_duration) : '');
                    }
                    if ($head == 'avg_queue_wait_time') {
                        $row[$head] = ((!empty($record->avg_queue_wait_time) && $record->avg_queue_wait_time > 0) ? gmdate('H:i:s', $record->avg_queue_wait_time) : '');
                    }
                    if ($head == 'longest_queue_wait_time') {
                        $row[$head] = ((!empty($record->longest_queue_wait_time) && $record->longest_queue_wait_time > 0) ? gmdate('H:i:s', $record->longest_queue_wait_time) : '');
                    }
                    if ($head == 'longest_abandoned_calls_wait_time') {
                        $row[$head] = ((!empty($record->longest_abandoned_calls_wait_time) && $record->longest_abandoned_calls_wait_time > 0) ? gmdate('H:i:s', $record->longest_abandoned_calls_wait_time) : '');
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
