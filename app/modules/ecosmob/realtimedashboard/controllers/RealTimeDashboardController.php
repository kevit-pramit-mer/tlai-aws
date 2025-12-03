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
 * Class RealTimeDashboardController
 *
 * Admin Management : Admin Dashboard Activity, Profile Updating, Change Password etc.
 *
 * @model   AdminMaster require
 * @package app\modules\ecosmob\realtimedashboard\controllers
 */
class RealTimeDashboardController extends Controller
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
                            'real-time-dashboard',
                            'active-calls-export',
                            'sip-reg-export',
                            'get-data'
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
    public function actionRealTimeDashboard()
    {
        /** SIP Extension Registration Status */
        $totalExtension = Extension::find()->where(['em_status' => '1'])->count();
        $registerExtension = SipRegistrations::find()->where(['sip_host' => $_SERVER['HTTP_HOST']])->count();
        $notRegisterExtension = $totalExtension-$registerExtension;
        $inCall = SipPresence::find()->where(['sip_host' => $_SERVER['HTTP_HOST'], 'status' => 'Online'])->count();
        $available = SipPresence::find()->where(['sip_host' => $_SERVER['HTTP_HOST'], 'status' => 'Active'])->count();
        $dnd = SipPresence::find()->where(['sip_host' => $_SERVER['HTTP_HOST'], 'status' => 'DND'])->count();
        $away = SipPresence::find()->where(['sip_host' => $_SERVER['HTTP_HOST'], 'status' => 'Away'])->count();
        $ringing = SipPresence::find()->where(['sip_host' => $_SERVER['HTTP_HOST'], 'status' => 'Ringing'])->count();

        $sipRegSearchModel = new SipPresence();
        $sipRegDataProvider = $sipRegSearchModel->search(Yii::$app->request->queryParams);
        Yii::$app->session->set('sipRegQuery', $sipRegDataProvider->query);

        /** Active Calls */
        $activeCallsSearchModel = new Channels();
        $activeCallsDataProvider = $activeCallsSearchModel->search(Yii::$app->request->queryParams);
        Yii::$app->session->set('activeCallsQuery', $activeCallsDataProvider->query);

        return $this->render('realTimeDashboard',
            [
                'activeCallsSearchModel' => $activeCallsSearchModel,
                'activeCallsDataProvider' => $activeCallsDataProvider,
                'totalExtension' => $totalExtension,
                'registerExtension' => $registerExtension,
                'notRegisterExtension' => $notRegisterExtension,
                'inCall' => $inCall,
                'available' => $available,
                'dnd' => $dnd,
                'away' => $away,
                'ringing' => $ringing,
                'sipRegSearchModel' => $sipRegSearchModel,
                'sipRegDataProvider' => $sipRegDataProvider
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
            "initial_ip_addr",
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
                    if ($head == 'initial_ip_addr') {
                        $row[$head] = $record->initial_ip_addr;
                    }
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

    public function actionSipRegExport(){
        ini_set("memory_limit", -1);
        $fp = fopen('php://temp', 'w');

        $fileName = "SIP_Extension_Registration_Status" . time() . ".csv";

        $model = new SipPresence();

        $query = Yii::$app->session->get('sipRegQuery');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);
        $records = $dataProvider->query->all();

        $attr = [
            "sip_user",
            "network_ip",
            "network_port",
            "expires",
            "status"
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
                    if ($head == 'sip_user') {
                        $row[$head] = $record->sip_user;
                    }
                    if ($head == 'network_ip') {
                        $row[$head] = $record->network_ip;
                    }
                    if ($head == 'network_port') {
                        $row[$head] = $record->network_port;
                    }
                    if ($head == 'expires') {
                        $row[$head] = date('D M d h:i:s', strtotime(\app\components\CommonHelper::tsToDt(date('Y-m-d H:i:s', $record->expires))));
                    }
                    if ($head == 'status') {
                        $row[$head] = $record->status;
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

    public function actionGetData(){
        $data = [];

        /** SIP Extension Registration Status */
        $data['totalExtension'] = Extension::find()->where(['em_status' => '1'])->count();
        $data['registerExtension'] = SipRegistrations::find()->where(['sip_host' => $_SERVER['HTTP_HOST']])->count();
        $data['notRegisterExtension'] = $data['totalExtension']-$data['registerExtension'];
        $data['inCall'] = SipPresence::find()->where(['sip_host' => $_SERVER['HTTP_HOST'], 'status' => 'Online'])->count();
        $data['available'] = SipPresence::find()->where(['sip_host' => $_SERVER['HTTP_HOST'], 'status' => 'Active'])->count();
        $data['dnd'] = SipPresence::find()->where(['sip_host' => $_SERVER['HTTP_HOST'], 'status' => 'DND'])->count();
        $data['away'] = SipPresence::find()->where(['sip_host' => $_SERVER['HTTP_HOST'], 'status' => 'Away'])->count();
        $data['ringing'] = SipPresence::find()->where(['sip_host' => $_SERVER['HTTP_HOST'], 'status' => 'Ringing'])->count();

        return json_encode($data);
    }
}
