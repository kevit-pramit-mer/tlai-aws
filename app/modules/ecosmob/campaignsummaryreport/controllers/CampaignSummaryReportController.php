<?php

namespace app\modules\ecosmob\campaignsummaryreport\controllers;

use app\modules\ecosmob\campaignsummaryreport\models\Campaign;
use app\modules\ecosmob\campaignsummaryreport\models\CampaignSearch;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use app\modules\ecosmob\globalconfig\models\GlobalConfig;

/**
 * Class CampaignSummaryReportController
 *
 * @package app\modules\ecosmob\campaignsummaryreport\controllers
 */
class CampaignSummaryReportController extends Controller
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
     * @throws \yii\base\InvalidParamException
     */
    public function actionIndex()
    {
        $searchModel = new CampaignSearch();
        $searchModel->from = date('Y-m-d');
         $searchModel->to = date('Y-m-d');
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        Yii::$app->session->set('camsummreportquery', $dataProvider->query);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionExport()
    {
        ini_set("memory_limit", -1);
        $fp = fopen('php://temp', 'w');

        $fileName = "Campaign_Summary_Report_" . time() . ".csv";
        $model = new Campaign();

        $query = Yii::$app->session->get('camsummreportquery');
        $query->limit(GlobalConfig::getValueByKey('export_limit'));

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]],
            'pagination' => false,
        ]);

        $records = $dataProvider->getModels();

        $attr = [
            "camp_name",
            "cmp_type",
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
                    if ($head == 'camp_name') {
                        $row[$head] = (!empty($record->camp_name) ? $record->camp_name : '-');
                    }
                    if ($head == 'cmp_type') {
                        $row[$head] = (!empty($record->cmp_type) ? $record->cmp_type : '-');
                    }
                    if ($head == 'total_call') {
                        $row[$head] = (!empty($record->total_call) ? $record->total_call : '-');
                    }
                    if ($head == 'answered') {
                        $row[$head] = (!empty($record->answered) ? $record->answered : '-');
                    }
                    if ($head == 'abandoned') {
                        $row[$head] = (!empty($record->abandoned) ? $record->abandoned : '-');
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