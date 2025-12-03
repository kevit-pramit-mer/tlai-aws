<?php

namespace app\modules\ecosmob\realtimedashboard\controllers;

use app\models\SipPresence;
use app\models\SipRegistrations;
use app\modules\ecosmob\extension\models\Extension;
use Yii;
use yii\base\InvalidArgumentException;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use DateTime;
use app\models\Channels;

/**
 * Class ActiveCallsController
 *
 * @package app\modules\ecosmob\realtimedashboard\controllers
 */
class ActiveCallsController extends Controller
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
                            'active-calls-export'
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
        /** Active Calls */
        $activeCallsSearchModel = new Channels();
        $activeCallsDataProvider = $activeCallsSearchModel->search(Yii::$app->request->queryParams);
        Yii::$app->session->set('activeCallsQuery', $activeCallsDataProvider->query);

        return $this->render('index',
            [
                'activeCallsSearchModel' => $activeCallsSearchModel,
                'activeCallsDataProvider' => $activeCallsDataProvider
            ]);

    }

    public function actionActiveCallsExport(){
        ini_set("memory_limit", -1);
        $fp = fopen('php://temp', 'w');

        $fileName = "Realtime_Active_Calls" . time() . ".csv";

        $model = new Channels();

        $query = Yii::$app->session->get('activeCallsQuery');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);
        $records = $dataProvider->query->all();

        $attr = [
            "cid_num",
            "dest",
            "ip_addr",
            /*"initial_ip_addr",*/
            "created_epoch",
            "callstate",
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
                    if ($head == 'cid_num') {
                        $row[$head] = $record->cid_num;
                    }
                    if ($head == 'dest') {
                        $row[$head] = $record->dest;
                    }
                    if ($head == 'ip_addr') {
                        $row[$head] = $record->ip_addr;
                    }
                  /*  if ($head == 'initial_ip_addr') {
                        $row[$head] = $record->initial_ip_addr;
                    }*/
                    if ($head == 'created_epoch') {
                        $first_date = new DateTime(date('Y-m-d H:i:s'));
                        $second_date = new DateTime(date('Y-m-d H:i:s', $record->created_epoch));
                        $interval = $first_date->diff($second_date);
                        $row[$head] = $interval->format('%H:%I:%S');
                    }
                    if ($head == 'callstate') {
                        $row[$head] = $record->callstate;
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
