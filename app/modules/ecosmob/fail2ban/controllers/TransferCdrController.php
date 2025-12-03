<?php

namespace app\modules\ecosmob\extensionsummaryreport\controllers;

use app\modules\ecosmob\customer\models\Customer;
use app\modules\ecosmob\extensionsummaryreport\models\TransferCdr;
use app\modules\ecosmob\extensionsummaryreport\models\TransferCdrSearch;
use app\modules\ecosmob\serviceprovider\models\ServiceProvider;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

/**
 * Class TransferCdrController
 *
 * @package app\modules\ecosmob\cdr\controllers
 */
class TransferCdrController extends Controller
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
        $searchModel = new TransferCdrSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        if (Yii::$app->user->identity->user_type == 'admin') {
            $getAllServiceProvider = ServiceProvider::getAllServiceProvider();
        }
        if (Yii::$app->user->identity->user_type == 'service_provider') {
            $getAllServiceProvider = Customer::getAllCustomers();
        }
        Yii::$app->session->set('transfercdrquery', $dataProvider->query);

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
     *
     */
    public function actionExport()
    {
        $fp = fopen('php://temp', 'w');
        $fileName = "Transfer_CDR_Report_" . time() . ".csv";
        $model = new TransferCdr();
        $query = Yii::$app->session->get('transfercdrquery');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);

        $records = $dataProvider->getModels();
        $attr = [
            "dialed_number",
            "caller_id_number",
            "user_name", //
            "sp_name", //
            "outpluse_caller_id_number",
            "outpluse_dialed_number",
            "free_min", //
            "billed_min", //
            "sell_cost",
            "sell_rc_name", //
            "sell_rate",
            "sell_min_duration",
            "buy_cost",
            "buy_rc_name", //
            "buy_rate",
            "buy_min_duration",
            "service",
            "package_name", //
            "call_type",
            "call_region",
            "start_epoch",
            "answer_epoch",
            "end_epoch",
            "callstatus",
            //"direction",
            "duration",
            "billsec",
            "trunk_id",
            "forward_to",
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
