<?php

namespace app\modules\ecosmob\pcap\controllers;

use app\modules\ecosmob\pcap\models\Pcap;
use app\modules\ecosmob\pcap\models\PcapSearch;
use app\modules\ecosmob\pcap\PcapModule;
use Throwable;
use Yii;
use yii\base\InvalidArgumentException;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * PcapController implements the CRUD actions for Pcap model.
 */
class PcapController extends Controller
{

    /**
     * {@inheritdoc}
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
                            'create',
                            'delete',
                            'start-capture',
                            'stop-capture',
                            'pcap-list',
                            'auto-delete-pcap',
                            'download-file',
                            'auto-stop-capture',
                        ],
                        'allow' => TRUE,
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
     * Lists all Pcap models.
     *
     * @return string
     * @throws InvalidArgumentException
     */
    public function actionIndex()
    {
        $pcapModel = new Pcap();
        $searchModel = new PcapSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index',
            [
                'model' => $pcapModel,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
    }

    public function actionPcapList()
    {
        $pcapModel = new Pcap();
        $searchModel = new PcapSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index',
            [
                'model' => $pcapModel,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
    }

    public function actionCreate()
    {
        $model = new Pcap();
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }
        if ($model->load(Yii::$app->request->post())) {
            $model->save();

            Yii::$app->session->setFlash('success', PcapModule::t('pcap', 'created_success'));

        } else {
            Yii::$app->session->setFlash('error', PcapModule::t('pcap', 'Something went wrong.'));
        }
        return $this->redirect(['index']);
    }


    public function actionStartCapture($id)
    {
        $data = Pcap::findOne($id);
        if (!empty($data)) {

            $path = Yii::$app->params['PCAP_PATH'] . "pcap/".$GLOBALS['tenantID'] ."/";

            if (!is_dir($path)) {
                Yii::$app->storageHelper->makeDirAndGivePermission($path);
            }
            Yii::$app->commonHelper->giveAllPermission($path);

            //$filename = uniqid() . '.pcap';
            $filename = $data->ct_name . '.pcap';
            $filter = '';
            if($data->filter == 'IPV4'){
                $filter = 'ip';
            }elseif ($data->filter == 'IPV6'){
                $filter = 'ip6';
            }else{
                $filter = 'ip or ip6';
            }

            //$process = shell_exec('sudo /usr/sbin/tcpdump -w ' . $path . $filename . ' > /dev/null 2>&1 & echo $!; ');
            $process = shell_exec('sudo /usr/bin/tcpdump -i any "'.$filter.'" -w ' . $path . $filename . ' -C ' . $data->buffer_size . ' -c ' . $data->packets_limit.' > /dev/null 2>&1 & echo $!;');
//print_r($this->getPId($path . $filename));exit;
//$process1 = exec("sudo ps aux | grep -i " . $path . $filename . " | awk {'print $2'}", $op);

            $data->ct_start = date('Y-m-d H:i:s');
            $data->ct_process = $process;
            $data->ct_status = 'start';
            $data->ct_filename = $filename;
            $data->ct_url = $path . $filename;
            $data->save(false);

            return $process;
        } else {
            return '';
        }
    }

    public function actionStopCapture($id)
    {
        $data = Pcap::find()->where(['ct_status' => 'start', 'ct_id' => $id])->one();
        if ($data) {
            $data->ct_status = 'stop';
            $data->ct_stop = date('Y-m-d H:i:s');
            $data->save(false);

           /* if ($pcapUpdate->ct_process != "") {
                $process1 = shell_exec('sudo kill -9 ' . $pcapUpdate->ct_process);
            }*/
            if ($data->ct_url != "") {
                $process1 = shell_exec('sudo pkill -9 -f ' . $data->ct_url);
                /*$process1 = exec("sudo ps aux | grep -i " . $data->ct_url . " | awk {'print $2'}", $op);
                if(!empty($op)){
                    foreach($op as $_op){
                        shell_exec('sudo kill -9 ' . $_op);
                    }
                }*/
            }
        }
        //$process1 = shell_exec('kill -9 ' . $processData);
        //return 'Stop Capture';
    }

    public function actionAutoDeletePcap()
    {
        $from = date('Y-m-d', strtotime('-7 days'));
        $query = Yii::$app->db->createCommand("SELECT ct_filename FROM ct_pcap WHERE ct_start < '" . $from . "'")->queryAll();
        foreach ($query as $value) {
            if (file_exists(Yii::$app->basePath . '/web/media/pcap/' . $value['ct_filename'])) {
                unlink(Yii::$app->basePath . '/web/media/pcap/' . $value['ct_filename']);
            }
        }
        Yii::$app->db->createCommand("DELETE FROM ct_pcap WHERE ct_start < '" . $from . "'")->execute();
    }

    /**
     * Deletes an existing Pcap model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     *
     * @return Response
     * @throws Throwable
     * @throws StaleObjectException
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if(!empty($model->ct_filename)) {
            if (file_exists(Yii::$app->basePath . '/web/media/pcap/' . $model->ct_filename)) {
                unlink(Yii::$app->basePath . '/web/media/pcap/' . $model->ct_filename);
            }
        }
        $model->delete();

        Yii::$app->session->setFlash('success', Yii::t('app', 'deleted_success'));

        return $this->redirect(['index']);
    }

    /**
     * Finds the Pcap model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     *
     * @return Pcap the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Pcap::findOne($id)) !== NULL) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('pcap', 'The requested page does not exist.'));
    }

    public function actionDownloadFile()
    {
        $id = Yii::$app->request->get('id');
        $model = Pcap::findOne($id);
        if ($model) {
            $filePath = Yii::$app->params['PCAP_PATH'] . "pcap/".$GLOBALS['tenantID']."/";
            $filename = $model->ct_filename;
            if (file_exists($filePath . $filename)) {
                Yii::$app->response->sendFile($filePath, $filename)->send();
            } else {
                throw new NotFoundHttpException('The requested file does not exist.');
            }
        } else {
            throw new NotFoundHttpException('The requested file does not exist.');

        }
    }

    public function actionAutoStopCapture()
    {
        $data = Pcap::find()->where(['ct_status' => 'start'])->all();
        if ($data) {
            foreach ($data as $_data) {
                if ($_data->ct_url != "") {
                    $output = $op = '';
                    $process = exec("sudo ps aux | grep -i " . $_data->ct_url . " | awk {'print $2'}", $output);

                    if ($output) {
                        foreach ($output as $_output) {
                            $p = exec("ps -p " . $_output . " | awk {'print $1'}", $op);
                            unset($op[array_search('PID', $op)]);
                        }
                        if (empty($op)) {
                            $_data->ct_status = 'stop';
                            $_data->ct_stop = date('Y-m-d H:i:s');
                            $_data->save(false);
                        }
                    }
                }
            }
        }
    }

    public function getPId($path){
        $descriptorspec = array(
            0 => array("pipe", "r"),  // stdin is a pipe that the child will read from
            1 => array("pipe", "w"),  // stdout is a pipe that the child will write to
            2 => array("pipe", "w")   // stderr is a pipe that the child will write to
        );

        $process = proc_open('tcpdump -w '.$path, $descriptorspec, $pipes);

        if (is_resource($process)) {
            // Get the PID of the tcpdump process
            $processStatus = proc_get_status($process);
            $pid = $processStatus['pid'];

            // Close the pipes as we don't need them
            fclose($pipes[0]);
            fclose($pipes[1]);
            fclose($pipes[2]);

            // Close the process
            proc_close($process);

            // Output the PID
            return $pid;
        } else {
            return "";
        }
    }

}
