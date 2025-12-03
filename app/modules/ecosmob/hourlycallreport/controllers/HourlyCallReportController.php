<?php

namespace app\modules\ecosmob\hourlycallreport\controllers;

use app\components\ConstantHelper;
use Yii;
use yii\base\InvalidParamException;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use app\modules\ecosmob\hourlycallreport\models\HourlyCallReport;
use app\modules\ecosmob\hourlycallreport\models\HourlyCallReportSearch;
use app\modules\ecosmob\globalconfig\models\GlobalConfig;
/**
 * Class HourlyCallReportController
 *
 * @package app\modules\ecosmob\hourlycallreport\controllers
 */
class HourlyCallReportController extends Controller
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
        $searchModel = new HourlyCallReportSearch();
        $searchModel->from = date('Y-m-d');
        $searchModel->to = date('Y-m-d');
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        Yii::$app->session->set('hourlycallreportquery', $dataProvider->query);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionExport()
    {
        ini_set("memory_limit", -1);
        $fp = fopen('php://temp', 'w');

        $fileName = "Hourly_Call_Report_" . time() . ".csv";
        $model = new HourlyCallReport();

        $query = Yii::$app->session->get('hourlycallreportquery');
        $query->limit(GlobalConfig::getValueByKey('export_limit'));

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [ 'defaultOrder' => ["hours" => SORT_ASC],
                'attributes' => [
                    'hours' => [
                        'asc' => ['hours' => SORT_ASC],
                        'desc' => ['hours' => SORT_DESC],
                    ],
                ]
            ],
            'pagination' => false,
        ]);

        $records = $dataProvider->getModels();

        $attr = [
            "hours",
            "total_call",
            "answered",
            "abandoned",
            "call_duration"
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
                    if ($head == 'hours') {
                        $hour = (!empty($record->hours) ? $record->hours : '0');
                        $row[$head] = ConstantHelper::getReportHours()[$hour];
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
                    if ($head == 'call_duration') {
                        $row[$head] = (!empty($record->call_duration) ? gmdate("H:i:s", $record->call_duration) : '-');
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