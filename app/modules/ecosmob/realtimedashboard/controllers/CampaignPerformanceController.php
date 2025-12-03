<?php

namespace app\modules\ecosmob\realtimedashboard\controllers;

use app\modules\ecosmob\realtimedashboard\models\CampaignPerformance;
use app\modules\ecosmob\realtimedashboard\models\QueueStatusReport;
use Yii;
use yii\base\InvalidArgumentException;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;

/**
 * Class CampaignPerformanceController
 *
 * @package app\modules\ecosmob\realtimedashboard\controllers
 */
class CampaignPerformanceController extends Controller
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
        $searchModel = new CampaignPerformance();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        Yii::$app->session->set('campaignPerformanceQuery', $dataProvider->query);

        return $this->render('index',
            [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider
            ]);
    }

    public function actionExport(){
        ini_set("memory_limit", -1);
        $fp = fopen('php://temp', 'w');

        $fileName = "Realtime_Campaign_Performance_" . time() . ".csv";

        $model = new CampaignPerformance();

        $query = Yii::$app->session->get('campaignPerformanceQuery');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);
        $records = $dataProvider->query->all();

        $attr = [
            'cmp_name',
            'total_agent_login',
            'total_calls',
            'live_calls',
            'answered',
            'abandoned',
            /*'total_leads',
            'dial_leads',
            'rechurn_leads',
            'contacted_leads',
            'noncontacted_leads',*/
            'avg_call_duration',
            'avg_wrap_up_time',
            'avg_wait_time',
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
                    if ($head == 'cmp_name') {
                        $row[$head] = $record->cmp_name;
                    }
                    if ($head == 'total_agent_login') {
                        $row[$head] = $record->total_agent_login;
                    }
                    if ($head == 'total_calls') {
                        $row[$head] = $record->total_calls;
                    }
                    if ($head == 'live_calls') {
                        $row[$head] = $record->live_calls;
                    }
                    if ($head == 'answered') {
                        $row[$head] = $record->answered;
                    }
                    if ($head == 'abandoned') {
                        $row[$head] = $record->abandoned;
                    }
                    /*if ($head == 'total_leads') {
                        $row[$head] = $record->total_leads;
                    }
                    if ($head == 'dial_leads') {
                        $row[$head] = $record->dial_leads;
                    }
                    if ($head == 'rechurn_leads') {
                        $row[$head] = $record->rechurn_leads;
                    }
                    if ($head == 'contacted_leads') {
                        $row[$head] = $record->contacted_leads;
                    }
                    if ($head == 'noncontacted_leads') {
                        $row[$head] = $record->noncontacted_leads;
                    }*/
                    if ($head == 'avg_call_duration') {
                        $row[$head] = (!empty($record->avg_call_duration) ? gmdate("H:i:s", $record->avg_call_duration) : '-');;
                    }
                    if ($head == 'avg_wrap_up_time') {
                        $row[$head] = (!empty($record->avg_wrap_up_time) ? gmdate("H:i:s", $record->avg_wrap_up_time) : '-');;
                    }
                    if ($head == 'avg_wait_time') {
                        $row[$head] = (!empty($record->avg_wait_time) ? gmdate("H:i:s", $record->avg_wait_time) : '-');;
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
