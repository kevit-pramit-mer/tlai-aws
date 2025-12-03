<?php

namespace app\modules\ecosmob\campaigncdr\controllers;

use app\modules\ecosmob\campaigncdr\models\InboundCdr;
use app\modules\ecosmob\campaigncdr\models\InboundCdrSearch;
use app\modules\ecosmob\customer\models\Customer;
use app\modules\ecosmob\serviceprovider\models\ServiceProvider;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class InboundCdrController extends \yii\web\Controller
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
                        'actions' => ['index', 'export',],
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
     */
    public function actionIndex()
    {
        /** @var InboundCdrSearch $searchModel */
        $searchModel = new InboundCdrSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
//        echo '<pre>';  print_r($dataProvider);die;
        if (Yii::$app->user->identity->user_type == 'admin') {
            $getAllServiceProvider = ServiceProvider::getAllServiceProvider();
        }
        if (Yii::$app->user->identity->user_type == 'service_provider') {
            $getAllServiceProvider = Customer::getAllCustomers();
        }
        Yii::$app->session->set('inboundcdrquery', $dataProvider->query);

        if (Yii::$app->user->identity->user_type == 'customer') {
            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider
            ]);
        }
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'DdData' => $getAllServiceProvider
        ]);
    }

    /**
     * Export to csv
     */
    public function actionExport()
    {
        $fp = fopen('php://temp', 'w');

        $fileName = "Inbound_CDR_Report_" . time() . ".csv";

        $model = new InboundCdr();

        $query = Yii::$app->session->get('inboundcdrquery');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);

        $records = $dataProvider->getModels();

        $attr = [
            "dialed_number",
            "caller_id_number",
            "user_id",
            "sp_id",
            "user_name",
            "sp_name",
            "outpluse_caller_id_number",
            "outpluse_dialed_number",
            "flat_rate",
            "free_min",
            "billed_min",
            "cost",
            "call_type",
            "start_epoch",
            "answer_epoch",
            "end_epoch",
            "callstatus",
            "trunk_name",
            "direction",
            "duration",
            "billsec",
            "hangup"
        ];

        if (Yii::$app->user->identity->user_type == 'customer') {
            unset($attr[3]);
            unset($attr[4]);
        }

        if (Yii::$app->user->identity->user_type == 'service_provider') {
            unset($attr[4]);
        }

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
                    if ($head == "start_epoch" || $head == "answer_epoch" || $head == "end_epoch") {
                        if ($record->$head != '0') {
                            $row[$head] = Yii::$app->helper->tsToDt($record->$head);
                        } else {
                            $row[$head] = '';
                        }
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
