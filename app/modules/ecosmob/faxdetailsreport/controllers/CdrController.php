<?php

namespace app\modules\ecosmob\faxdetailsreport\controllers;

use app\components\CommonHelper;
use app\components\Helper;
use app\modules\ecosmob\faxdetailsreport\models\Cdr;
use app\modules\ecosmob\faxdetailsreport\models\CdrSearch;
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
 * @package app\modules\ecosmob\cdr\controllers
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

        Yii::$app->session->set('cdrquery', $dataProvider->query);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'sort' => ['defaultOrder' => ['start_epoch' => SORT_DESC]],
            'dataProvider' => $dataProvider
        ]);
    }

    public function actionExport()
    {
        ini_set("memory_limit", -1);
        $fp = fopen('php://temp', 'w');
        $fileName = "Fax_Details_Report_" . time() . ".csv";
        $model = new Cdr();

        $query = Yii::$app->session->get('cdrquery');
        $query->orderby(['start_epoch' => SORT_DESC])->limit(GlobalConfig::getValueByKey('export_limit'));
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);

        $records = $dataProvider->getModels();

        $attr = [
            "fax_caller",
            "fax_callee",
            "direction",
            "duration",
            "start_epoch",
            "answer_epoch",
            "end_epoch",
            "fax_total",
            "faxstatus",
            "hangup_cause",
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
                    if ($head == 'fax_caller') {
                        $row[$head] = (!empty($record->fax_caller) ? $record->fax_caller : '-');
                    }
                    if ($head == 'fax_callee') {
                        $row[$head] = (!empty($record->fax_callee) ? $record->fax_callee : '-');
                    }
                    if ($head == 'direction') {
                        $row[$head] = (!empty($record->direction) ? $record->direction : '-');
                    }
                    if ($head == 'duration') {
                        $row[$head] = (!empty($record->duration) ? $record->duration : '-');
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
                    if ($head == 'fax_total') {
                        $row[$head] = (!empty($record->fax_total) ? $record->fax_total : '-');
                    }
                    if ($head == 'faxstatus') {
                        $row[$head] = (!empty($record->faxstatus) ? $record->faxstatus : '-');
                    }

                    if ($head == 'hangup_cause') {
                        $row[$head] = (!empty($record->hangup_cause) ? $record->hangup_cause : '-');
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

                    Yii::$app->storageHelper->findAndGivePermissionToFreeswitchRecordingsFolder();

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