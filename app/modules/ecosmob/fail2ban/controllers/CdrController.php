<?php

namespace app\modules\ecosmob\fail2ban\controllers;

use app\components\Helper;
use app\modules\ecosmob\fail2ban\models\Cdr;
use app\modules\ecosmob\fail2ban\models\CdrSearch;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use DateTime;
use app\components\CommonHelper;
use app\modules\ecosmob\globalconfig\models\GlobalConfig;

/**
 * Class CdrController
 *
 * @package app\modules\ecosmob\fail2ban\controllers
 */
class CdrController extends Controller
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
                            'export',
                            'download-pcap',
                            'bulk-data',
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
        $searchModel = new CdrSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        Yii::$app->session->set('cdrquery', $dataProvider->query);


        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionExport()
    {
        ini_set("memory_limit", -1);
        $fp = fopen('php://temp', 'w');

        $fileName = "Extension_Summary_Report_" . time() . ".csv";

        $model = new Cdr();

        $query = Yii::$app->session->get('cdrquery');
        $query->orderby(['start_epoch' => SORT_DESC])->limit(GlobalConfig::getValueByKey('export_limit'));

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['start_epoch' => SORT_DESC]],
            'pagination' => false,
        ]);

        $records = $dataProvider->getModels();

        $attr = [
            //"uuid",
            // "sip_call_id",
            "caller_id_number",
            "dialed_number",
            //"record_filename",
            /*"user_id",*/
            /*"sp_id",*/
            /*"user_name",*/ //
            /*"sp_name",*/ //
            /*"outpluse_caller_id_number",
            "outpluse_dialed_number",
            "free_min", //
            "billed_min", //
            "sell_cost",
            "sell_rc_id",
            "sell_rc_name", //
            "sell_rate",
            "sell_min_duration",
            "buy_cost",
            "buy_rc_id",
            "buy_rc_name", //
            "buy_rate",
            "buy_min_duration",
            "service",
            "package_id",
            "package_name", //*/
            //"call_type",
            /*"call_region",*/
            "direction",
            "start_epoch",
            "answer_epoch",
            "end_epoch",
            //"callstatus",
            "duration",
            /* "billsec",
             "trunk_id",
             "trunk_name",*/
            /*"forward_to",*/
            "hangup",
            //"isfile",

        ];

        /*     if (Yii::$app->user->identity->user_type == 'customer') {
                 unset($attr[3]);
                 unset($attr[4]);
             }*/

        /*  if (Yii::$app->user->identity->user_type == 'service_provider') {
              unset($attr[4]);
          }*/

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
                    /*if ($head == "start_epoch" || $head == "answer_epoch" || $head == "end_epoch") {
                        if ($record->$head != '0') {
                            $row[$head]=Yii::$app->helper->tsToDt($record->$head);
                        } else {
                            $row[$head]='';
                        }
                    }*/

                    if ($head == 'caller_id_number') {
                        $row[$head] = (!empty($record->caller_id_number) ? $record->caller_id_number : '-');
                    }
                    if ($head == 'dialed_number') {
                        $row[$head] = (!empty($record->dialed_number) ? $record->dialed_number : '-');
                    }
                    if ($head == 'direction') {
                        $row[$head] = (!empty($record->direction) ? $record->direction : '-');
                    }
                    if ($head == 'start_epoch') {
                        $row[$head] = (!empty($record->start_epoch) ? CommonHelper::tsToDt(date("Y-m-d H:i:s", substr($record->start_epoch, 0, 10))) : '-');
                    }
                    if ($head == 'answer_epoch') {
                        $row[$head] = (!empty($record->answer_epoch) ? CommonHelper::tsToDt(date("Y-m-d H:i:s", substr($record->answer_epoch, 0, 10))) : '-');
                    }
                    if ($head == 'end_epoch') {
                        $row[$head] = (!empty($record->end_epoch) ? CommonHelper::tsToDt(date("Y-m-d H:i:s", substr($record->end_epoch, 0, 10))) : '-');
                    }
                    if ($head == 'duration') {
                        $row[$head] = (!empty($record->duration) ? $record->duration : '-');
                    }
                    if ($head == 'hangup') {
                        $row[$head] = (!empty($record->hangup) ? $record->hangup : '-');
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

    /**
     * @param $callId
     * @param $start
     * @param $end
     *
     * @return mixed
     * @throws \yii\base\InvalidParamException
     */
    public function actionDownloadPcap($callId, $start, $end)
    {
        $homerSession = Yii::$app->session->get('homer_session');
        $start = $start * 1000;
        $end = $end * 1000;

        if (!$homerSession) {
            $homerSession = md5(rand());
            Helper::registerHomerSession($homerSession);
        }

        $postData = '{"param":{"trasaction":{},"limit":200,"search":{"callid_aleg":"' . $callId
            . '"},"timezone":{"value":0,"name":"GMT+0 UTC","offset":"+0000"}},"timestamp":{"from":' . $start . ',"to":' . $end . '}}';
        $responseData = Helper::execCURL(HOMER_API_URI_SEARCH_DATA, $homerSession, $postData);
        $response = json_decode($responseData, TRUE);
        foreach ($response['data'] as $data) {
            $callid_aleg[] = $data['callid'];
        }

        /** @var string $path */
        $path = Url::to(PCAP_FILE_FULL_PATH);

        if (!is_dir($path)) {
            mkdir($path);
        }

        $callProcessDir = $path . "/" . $start . "_temp/";
        if (!is_dir($callProcessDir)) {
            mkdir($callProcessDir);
            chmod($callProcessDir, 0777);
        }

        $mainFile = $callProcessDir . $callId . "_main.pcap";
        $fp = fopen($mainFile, "w");
        $outputFile = $callProcessDir . $callId . ".pcap";
        $postData = '{"timestamp":{"from":' . $start . ',"to":' . $end . '},"param":{"search":{"callid":["' . $callId
            . '"]},"transaction":{"call":true,"registration":false,"rest":false}}}';
        $response = Helper::execCURL(HOMER_API_URI_EXPORT, $homerSession, $postData);
        fwrite($fp, $response);
        fclose($fp);

        $regcap_files = $mainFile . " ";

        if (!empty($callid_aleg)) {
            $callid_reg = array_unique($callid_aleg);

            foreach ($callid_reg as $regcallid) {

                $fp = fopen($callProcessDir . $regcallid . ".pcap", "w");
                $postData = '{"timestamp":{"from":' . $start . ',"to":' . $end . '},"param":{"search":{"callid":["' . $regcallid
                    . '"]},"transaction":{"call":true,"registration":false,"rest":false}}}';
                $response = Helper::execCURL(HOMER_API_URI_EXPORT, $homerSession, $postData);
                if (!empty($response)) {
                    $regcap_files = $regcap_files . $callProcessDir . $regcallid . ".pcap" . " ";
                }
                fwrite($fp, $response);
                fclose($fp);
            }
        }

        pclose(popen("/usr/sbin/mergecap -w $outputFile $regcap_files 2>&1", 'w'));

        header('Content-Type: application/octet-stream');
        header("Content-Transfer-Encoding: Binary");
        header("Content-disposition: attachment; filename=" . basename($outputFile));
        readfile($outputFile);
        Helper::deleteDir($callProcessDir);
        exit;
    }

    public function actionBulkData()
    {
        //$selection=(array)Yii::$app->request->post('selection');
        //$total_record=count($selection);

        if ($_POST['optype'] == 'download') {

            $selection = (array)Yii::$app->request->post('selection');
            $total_record = count($selection);

            $zipFilename = 'cdr-audiofiles_' . time() . '.zip';
            //zip file name with path
            $zip_folder = Yii::$app->basePath . DIRECTORY_SEPARATOR . 'web/media/admin/cdr-audio-download' . DIRECTORY_SEPARATOR;
            $zip_path = $zip_folder . $zipFilename;

            if (!is_dir($zip_folder)) {
//                FileHelper::createDirectory($zip_folder);
                mkdir($zip_folder, 0777, TRUE);
                chmod($zip_folder, 0777);
            }
            $zip = new ZipArchive;

            $zip->open($zip_path, ZipArchive::CREATE);

            if ($total_record > 0) {
                foreach ($selection as $ids) {

                    $model = Cdr::find()->where(['_id' => $ids])->one();
                    $files = $model->record_filename;

                    //$files=(isset($model->record_filename) && !empty($model->record_filename)) ? $model->record_filename : '-';
                    //$files = '/var/www/html/calltech/web/media/admin/cdr-audio-download/test.wav';


                    $data = explode('/', $files);
                    $end = rand() . '_' . array_reverse($data)[0];

                    if (file_exists($files)) {
                        $zip->addFile($files, $end);
                    } else {
                        Yii::$app->session->setFlash('error', Yii::t('app', 'No file exists'));
                        return $this->redirect(['index']);
                    }
                }
                $zip->close();

                header("Content-type: application/zip");
                header("Content-Disposition: attachment; filename=$zipFilename");
//                header("Content-length: " . filesize($zipFilename));
//                header("Pragma: no-cache");
//                header("Expires: 0");
                readfile($zip_path);
//                header("Location:$zip_path");
            }
        }
    }


}
