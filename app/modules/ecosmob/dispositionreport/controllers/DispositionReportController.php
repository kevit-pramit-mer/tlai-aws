<?php

namespace app\modules\ecosmob\dispositionreport\controllers;

use app\modules\ecosmob\dispositionreport\models\DispositionReport;
use app\modules\ecosmob\dispositionreport\models\DispositionReportSearch;
use Yii;
use yii\base\InvalidParamException;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use app\modules\ecosmob\globalconfig\models\GlobalConfig;

/**
 * Class DispositionReportController
 *
 * @package app\modules\ecosmob\dispositionreport\controllers
 */
class DispositionReportController extends Controller
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
        $searchModel = new DispositionReportSearch();
        $searchModel->from = date('Y-m-d');
        $searchModel->to = date('Y-m-d');
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $total = 0;
        foreach ($dataProvider->models as $m) {
            $total += $m->call_count;
        }
        Yii::$app->session->set('dispositionreportquery', $dataProvider->query);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'total' => $total
        ]);
    }

    public function actionExport()
    {
        ini_set("memory_limit", -1);
        $fp = fopen('php://temp', 'w');
        if(Yii::$app->user->identity->adm_is_admin == 'supervisor') {
            $fileName = "Disposition_Summary_Report_" . time() . ".csv";
        }else{
            $fileName = "Disposition_Report_" . time() . ".csv";
        }
        $model = new DispositionReport();

        $query = Yii::$app->session->get('dispositionreportquery');
        $query->limit(GlobalConfig::getValueByKey('export_limit'));

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]],
            'pagination' => false,
        ]);

        $records = $dataProvider->getModels();

        $attr = [
            "call_disposion_name",
            "call_count"
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
                    if ($head == 'call_disposion_name') {
                        $row[$head] = (!empty($record->call_disposion_name) ? $record->call_disposion_name : '-');
                    }
                    if ($head == 'call_count') {
                        $row[$head] = (!empty($record->call_count) ? $record->call_count : '-');
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