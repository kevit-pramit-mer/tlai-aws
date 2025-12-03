<?php

namespace app\modules\ecosmob\extensionsummaryreport\controllers;

use app\components\CommonHelper;
use app\components\Helper;
use app\modules\ecosmob\extensionsummaryreport\models\Cdr;
use app\modules\ecosmob\extensionsummaryreport\models\CdrSearch;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\Controller;
use ZipArchive;
use app\modules\ecosmob\globalconfig\models\GlobalConfig;

/**
 * Class CdrController
 *
 * @package app\modules\ecosmob\extensionsummaryreport\controllers
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
        $searchModel->start_epoch = date('Y-m-d 00:00:00');
        $searchModel->end_epoch = CommonHelper::tsToDt(date('Y-m-d H:i:s'));
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        Yii::$app->session->set('cdrquery', $dataProvider->allModels);


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
        $query =  array_slice($query, 0, GlobalConfig::getValueByKey('export_limit'));

        $records = $query;

        $attr = [
            "extension",
            "extension_name",
            "total_calls",
            "total_duration",
            "average_call_duration",
            "total_answered_calls",
            "total_abandoned_calls",
            "total_inbound_calls",
            "total_inbound_call_duration",
            "total_outbound_calls",
            "total_outbound_call_duration"
        ];

        $row = [];
        foreach ($attr as $header) {
            $row[] = $model->getAttributeLabel($header);
        }
        fputcsv($fp, $row);
        if (!empty($records)) {
            foreach ($records as $key => $record) {
                $row = [];
                foreach ($attr as $head) {
                    if ($head == 'extension') {
                        $row[$head] = $record['extension'];
                    }
                    if ($head == 'extension_name') {
                        $row[$head] = $record['extension_name'];
                    }
                    if ($head == 'total_calls') {
                        $row[$head] = $record['total_calls'];
                    }
                    if ($head == 'total_duration') {
                        $row[$head] = gmdate('H:i:s', $record['total_duration']);
                    }
                    if ($head == 'average_call_duration') {
                        $row[$head] = gmdate('H:i:s', $record['average_call_duration']);
                    }
                    if ($head == 'total_answered_calls') {
                        $row[$head] = $record['total_answered_calls'];
                    }
                    if ($head == 'total_abandoned_calls') {
                        $row[$head] = $record['total_abandoned_calls'];
                    }
                    if ($head == 'total_inbound_calls') {
                        $row[$head] = $record['total_inbound_calls'];
                    }
                    if ($head == 'total_inbound_call_duration') {
                        $row[$head] = gmdate('H:i:s', $record['total_inbound_call_duration']);
                    }
                    if ($head == 'total_outbound_calls') {
                        $row[$head] = $record['total_outbound_calls'];
                    }
                    if ($head == 'total_outbound_call_duration') {
                        $row[$head] = gmdate('H:i:s', $record['total_outbound_call_duration']);
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
     * @return void
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

        $callProcessDir = $path . "/" . $start . "_temp/"; // create temp directory
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
        if ($_POST['optype'] == 'download') {

            $selection = (array)Yii::$app->request->post('selection');
            $total_record = count($selection);

            $zipFilename = 'cdr-audio-files_' . time() . '.zip';
            $zip_folder = Url::to(Yii::$app->params['tenantStorageFullPath'] . $GLOBALS['tenantID'] . '/cdr-audio-download/');
            $zip_path = $zip_folder . $zipFilename;

            if (!is_dir($zip_folder)) {
                Yii::$app->commonHelper->makeDirAndGivePermission($zip_folder);
            }
            $zip = new ZipArchive;
            $zip->open($zip_path, ZipArchive::CREATE);
            if ($total_record > 0) {
                foreach ($selection as $ids) {

                    $model = Cdr::find()->where(['_id' => $ids])->one();
                    $files = $model->record_filename;

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
                readfile($zip_path);
            }
        }
    }
}