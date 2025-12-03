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
 * Class SipExtensionController
 *
 * Admin Management : Admin Dashboard Activity, Profile Updating, Change Password etc.
 *
 * @model   AdminMaster require
 * @package app\modules\ecosmob\realtimedashboard\controllers
 */
class SipExtensionController extends Controller
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
    public function actionIndex()
    {
        /** SIP Extension Registration Status */
        $totalExtension = Extension::find()->count();
        $registerExtension = SipRegistrations::find()->where(['sip_host' => $_SERVER['HTTP_HOST']])->groupBy('sip_user')->count();
        $notRegisterExtension = $totalExtension-$registerExtension;
        $online = SipRegistrations::find()->where(['sip_presence.sip_host' => $_SERVER['HTTP_HOST'], 'sip_presence.status' => 'Online'])
            ->innerJoin('sip_presence', 'sip_registrations.sip_user = sip_presence.sip_user and sip_registrations.sip_host = sip_presence.sip_host')
            //->groupBy('sip_registrations.sip_user')
            ->count();

        $available = Yii::$app->fscoredb->createCommand("SELECT `sip_registrations`.sip_user as sip_user FROM `sip_registrations`  WHERE `sip_registrations`.`sip_host`='".$_SERVER['HTTP_HOST']."' and sip_user not in (select sip_user from sip_presence where sip_host = sip_registrations.sip_host and sip_user = sip_registrations.sip_user)")->queryAll();
        $available = array_column($available, 'sip_user');
        $available = count($available);

        $dnd = SipRegistrations::find()->where(['sip_presence.sip_host' => $_SERVER['HTTP_HOST'], 'sip_presence.status' => 'DND'])
            ->innerJoin('sip_presence', 'sip_registrations.sip_user = sip_presence.sip_user and sip_registrations.sip_host = sip_presence.sip_host')
            //->groupBy('sip_registrations.sip_user')
            ->count();
        $away = SipRegistrations::find()->where(['sip_presence.sip_host' => $_SERVER['HTTP_HOST'], 'sip_presence.status' => 'Away'])
            ->innerJoin('sip_presence', 'sip_registrations.sip_user = sip_presence.sip_user and sip_registrations.sip_host = sip_presence.sip_host')
            //->groupBy('sip_registrations.sip_user')
            ->count();
        $ringing = SipRegistrations::find()->where(['sip_presence.sip_host' => $_SERVER['HTTP_HOST'], 'sip_presence.status' => 'Ringing'])
            ->innerJoin('sip_presence', 'sip_registrations.sip_user = sip_presence.sip_user and sip_registrations.sip_host = sip_presence.sip_host')
            //->groupBy('sip_registrations.sip_user')
            ->count();
        $onThePhone = SipRegistrations::find()->where(['sip_presence.sip_host' => $_SERVER['HTTP_HOST'], 'sip_presence.status' => 'On The Phone'])
            ->innerJoin('sip_presence', 'sip_registrations.sip_user = sip_presence.sip_user and sip_registrations.sip_host = sip_presence.sip_host')
            //->groupBy('sip_registrations.sip_user')
            ->count();
        $busy = SipRegistrations::find()->where(['sip_presence.sip_host' => $_SERVER['HTTP_HOST'], 'sip_presence.status' => 'Busy'])
            ->innerJoin('sip_presence', 'sip_registrations.sip_user = sip_presence.sip_user and sip_registrations.sip_host = sip_presence.sip_host')
            //->groupBy('sip_registrations.sip_user')
            ->count();

        $sipRegSearchModel = new SipRegistrations();
        $sipRegDataProvider = $sipRegSearchModel->search(Yii::$app->request->queryParams);
        Yii::$app->session->set('sipRegQuery', $sipRegDataProvider->query);

        return $this->render('index',
            [
                'totalExtension' => $totalExtension,
                'registerExtension' => $registerExtension,
                'notRegisterExtension' => $notRegisterExtension,
                'online' => $online,
                'available' => $available,
                'dnd' => $dnd,
                'away' => $away,
                'ringing' => $ringing,
                'sipRegSearchModel' => $sipRegSearchModel,
                'sipRegDataProvider' => $sipRegDataProvider,
                'onThePhone' => $onThePhone,
                'busy' => $busy
            ]);

    }

    public function actionSipRegExport(){
        ini_set("memory_limit", -1);
        $fp = fopen('php://temp', 'w');

        $fileName = "Realtime_SIP_Extension_Registration_Status" . time() . ".csv";

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
            "user_agent",
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
                        $networkIP = $record->network_ip;
                        $userAgent = explode('::', $record->user_agent);
                        if(isset($userAgent[1])){
                            if(str_contains($userAgent[1], 'client_ip')){
                                $ip = explode('=', $userAgent[1]);
                                if(isset($ip[1])){
                                    $networkIP = $ip[1];
                                }
                            }
                        }
                        $row[$head] = $networkIP;
                    }
                    if ($head == 'network_port') {
                        $row[$head] = $record->network_port;
                    }
                    if ($head == 'user_agent') {
                        $userAgent = explode('::', $record->user_agent);
                        if(isset($userAgent[1])) {
                            $str = strtolower($userAgent[0]);
                            $search = strtolower('SIP.js');
                            if (preg_match("~\b" . strtolower($search) . "\b~", strtolower($str))) {
                                $row[$head] = 'WebRTC Client';
                            } else {
                                $row[$head] = $userAgent[0];
                            }
                        }else{
                            $row[$head] = $record->user_agent;
                        }
                    }
                    if ($head == 'expires') {
                        $row[$head] = date('D M d h:i:s', strtotime(\app\components\CommonHelper::tsToDt(date('Y-m-d H:i:s', $record->expires))));
                    }
                    if ($head == 'status') {
                        $getStatus = SipPresence::getStatus($record->sip_host, $record->sip_user);
                        $status = 'Available';
                        if (!empty($getStatus)) {
                            $status = $getStatus->status;
                        }
                        $row[$head] = $status;
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
        $data['totalExtension'] = Extension::find()->count();
        $data['registerExtension'] = SipRegistrations::find()->where(['sip_host' => $_SERVER['HTTP_HOST']])->groupBy('sip_user')->count();
        $data['notRegisterExtension'] = $data['totalExtension']-$data['registerExtension'];
        $data['inCall'] = SipRegistrations::find()->where(['sip_presence.sip_host' => $_SERVER['HTTP_HOST'], 'sip_presence.status' => 'Online'])
            ->innerJoin('sip_presence', 'sip_registrations.sip_user = sip_presence.sip_user and sip_registrations.sip_host = sip_presence.sip_host')
            //->groupBy('sip_registrations.sip_user')
            ->count();

        $available = Yii::$app->fscoredb->createCommand("SELECT `sip_registrations`.sip_user as sip_user FROM `sip_registrations`  WHERE `sip_registrations`.`sip_host`='".$_SERVER['HTTP_HOST']."' and sip_user not in (select sip_user from sip_presence where sip_host = sip_registrations.sip_host and sip_user = sip_registrations.sip_user)")->queryAll();
        $available = array_column($available, 'sip_user');
        $data['available'] = count($available);

        $data['dnd'] = SipRegistrations::find()->where(['sip_presence.sip_host' => $_SERVER['HTTP_HOST'], 'sip_presence.status' => 'DND'])
            ->innerJoin('sip_presence', 'sip_registrations.sip_user = sip_presence.sip_user and sip_registrations.sip_host = sip_presence.sip_host')
            //->groupBy('sip_registrations.sip_user')
            ->count();
        $data['away'] = SipRegistrations::find()->where(['sip_presence.sip_host' => $_SERVER['HTTP_HOST'], 'sip_presence.status' => 'Away'])
            ->innerJoin('sip_presence', 'sip_registrations.sip_user = sip_presence.sip_user and sip_registrations.sip_host = sip_presence.sip_host')
            //->groupBy('sip_registrations.sip_user')
            ->count();
        $data['ringing'] = SipRegistrations::find()->where(['sip_presence.sip_host' => $_SERVER['HTTP_HOST'], 'sip_presence.status' => 'Ringing'])
            ->innerJoin('sip_presence', 'sip_registrations.sip_user = sip_presence.sip_user and sip_registrations.sip_host = sip_presence.sip_host')
            //->groupBy('sip_registrations.sip_user')
            ->count();
        $data['onThePhone'] = SipRegistrations::find()->where(['sip_presence.sip_host' => $_SERVER['HTTP_HOST'], 'sip_presence.status' => 'On The Phone'])
            ->innerJoin('sip_presence', 'sip_registrations.sip_user = sip_presence.sip_user and sip_registrations.sip_host = sip_presence.sip_host')
            //->groupBy('sip_registrations.sip_user')
            ->count();

        $data['busy'] = SipPresence::find()->where(['sip_presence.sip_host' => $_SERVER['HTTP_HOST'], 'sip_presence.status' => 'Busy'])
            ->innerJoin('sip_registrations', 'sip_registrations.sip_user = sip_presence.sip_user and sip_registrations.sip_host = sip_presence.sip_host')
            //->groupBy('sip_registrations.sip_user')
            ->count();

        return json_encode($data);
    }
}
