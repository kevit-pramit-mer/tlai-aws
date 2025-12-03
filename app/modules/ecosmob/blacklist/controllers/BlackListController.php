<?php

namespace app\modules\ecosmob\blacklist\controllers;

use app\modules\ecosmob\blacklist\BlackListModule;
use app\modules\ecosmob\blacklist\models\BlackList;
use app\modules\ecosmob\blacklist\models\BlackListSearch;
use Exception;
use Throwable;
use Yii;
use yii\base\InvalidArgumentException;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * BlackListController implements the CRUD actions for BlackList model.
 */
class BlackListController extends Controller
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
                            'update',
                            'delete',
                            'import',
                            'download-sample-file',
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
     * Lists all BlackList models.
     *
     * @return string
     * @throws InvalidArgumentException
     */
    public function actionIndex()
    {
        $searchModel = new BlackListSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $importModel = new BlackList();
        $importModel->setScenario('import');

        return $this->render('index',
            [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'importModel' => $importModel,
            ]);
    }

    /**
     * Displays a single BlackList model.
     *
     * @param integer $id
     *
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     * @throws InvalidArgumentException
     */
    public function actionView($id)
    {
        return $this->render('view',
            [
                'model' => $this->findModel($id),
            ]);
    }

    /**
     * Finds the BlackList model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     *
     * @return BlackList the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BlackList::findOne($id)) !== NULL) {
            return $model;
        }

        throw new NotFoundHttpException(BlackListModule::t('bl', 'not_found'));
    }

    /**
     * Creates a new BlackList model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     *
     * @return Response|string
     * @throws InvalidArgumentException
     */
    public function actionCreate()
    {
        $model = new BlackList();

        if ($model->load(Yii::$app->request->post())) {
            $model->updated_date = date('Y-m-d H:i:s');
            $model->created_date = date('Y-m-d H:i:s');

            if ($model->validate()) {
                if ($model->save()) {
                    Yii::$app->session->setFlash('success', BlackListModule::t('bl', 'created_success'));
                    return $this->redirect(['index']);
                }
            }
        }

        return $this->render('create',
            [
                'model' => $model,
            ]);
    }

    /**
     * Updates an existing BlackList model.
     * If update is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     *
     * @return Response|string
     * @throws NotFoundHttpException if the model cannot be found
     * @throws InvalidArgumentException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $model->updated_date = date('Y-m-d H:i:s');

            if ($model->validate()) {
                if ($model->save()) {
                    Yii::$app->session->setFlash('success', BlackListModule::t('bl', 'updated_success'));
                    return $this->redirect(['index']);
                }
            }
        }
        return $this->render('update',
            [
                'model' => $model,
            ]);
    }

    /**
     * @param $id
     *
     * @return Response
     * @throws NotFoundHttpException
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', BlackListModule::t('bl', 'deleted_success'));

        return $this->redirect(['index']);
    }

    /**
     * @return string|Response
     * @throws InvalidArgumentException
     */
    public function actionImport()
    {
        $searchModel = new BlackListSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        Yii::$app->session->set('queryblacklist', $dataProvider->query);
        $importModel = new BlackList();

        $importModel->setScenario('import');
        if ($importModel->load(Yii::$app->request->post())) {

            $importModel->importFileUpload = UploadedFile::getInstance($importModel, 'importFileUpload');
            if ($importModel->validate(['importFileUpload'])) {
                $this->saveDidCSVData($importModel);
                return $this->redirect(['index']);
            }
        }

        return $this->render('index',
            [
                'importModel' => $importModel,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
    }

    /**
     * @param $importModel
     */
    private function saveDidCSVData($importModel)
    {
        $fileExtension = $importModel->importFileUpload->getExtension();
        $tempName = $importModel->importFileUpload->tempName;
        $errors = $importModel->importFileUpload->error;

        $csvPath = Url::to(Yii::$app->params['tenantStorageFullPath'] . $GLOBALS['tenantID'] . '/csv/whitelist/');

        if (!is_dir($csvPath)) {
            Yii::$app->storageHelper->makeDirAndGivePermission($csvPath);
        }

        $fileName = 'failed_whitelist_' . time() . '.csv';
        $csvPath = $csvPath . $fileName;
        $csvUrl = Yii::$app->homeUrl . 'media/' . $GLOBALS['tenantID'] . '/csv/whitelist/' . $fileName;
        $handle2 = fopen($csvPath, "w");
        $total_uploaded_numbers = 0;
        $total_faulty_numbers = 0;
        if ($errors == 0) {
            if (($fileExtension == "csv") && (!empty($tempName))) {
                $i = 0;
                $delimiter = ',';
                $data = [];
                $headersArray = [];
                if (($handle = fopen($tempName, 'r')) !== FALSE) {
                    while (($row = fgetcsv($handle, 0, $delimiter)) !== FALSE) {
                        if ($i == 0) {
                            $headersArray = $row;

                            $row[] = 'Result';
                            fputcsv($handle2, $row);
                        }
                        $importArrayKeys = array_keys($importModel->import['fields']);

                        if ($i != 0) {
                            if (count($row) == 3) {
                                foreach ($row as $key => $value) {
                                    if (isset($value)) {
                                        if (in_array('Black List Number', $headersArray)
                                            && in_array('Reason', $headersArray)
                                            && in_array('Type', $headersArray)
                                        ) {
                                            $data[$i][$importArrayKeys[$key]] = $value;
                                        }else{
                                            $total_faulty_numbers++;
                                            $failedData[$i] = $row;
                                            $errorData = array_values($row);
                                            $errorData[] = Yii::t('app', 'importColumnNameMismatch');
                                            fputcsv($handle2, $errorData);
                                        }
                                    }
                                }
                            }else{
                                $total_faulty_numbers++;
                                $failedData[$i] = $row;
                                $errorData = array_values($row);
                                $errorData[] = Yii::t('app', 'importRecordsMismatch');
                                fputcsv($handle2, $errorData);
                            }
                        }
                        $i++;
                    }
                    fclose($handle);
                }

                $length = count($data);
                $total_uploaded_numbers = 0;
                $total_faulty_numbers = 0;
                if ($length != 0) {
                    for ($i = 1; $i <= $length; $i++) {

                        $number = $data[$i]['bl_number'];
                        $type = $data[$i]['bl_type'];
                        $reason = $data[$i]['bl_reason'];

                        $model = new BlackList();
                        $model->bl_number = $number;
                        $model->bl_type = $type;
                        $model->bl_reason = $reason;
                        $model->created_date = date('Y-m-d H:i:s');
                        $model->updated_date = date('Y-m-d H:i:s');
                        if ($model->save()) {
                            $total_uploaded_numbers += 1;
                            $dataCsv = array_values($data[$i]);
                            $dataCsv[] = Yii::t('app', 'success');
                            fputcsv($handle2, $dataCsv);
                        } else {
                            $total_faulty_numbers++;
                            $errorData = array_values($data[$i]);
                            $errorData[] = implode(', ', call_user_func_array('array_merge', array_values($model->getErrors())));

                            fputcsv($handle2, $errorData);
                        }
                    }
                }

                $msg = Yii::t('app', 'importSuccess');

                fclose($handle2);

                if ($total_faulty_numbers > 0) {
                    $msg = '<span style="display: inline-block;">' . '<a href="' . $csvUrl . '" style="color:white;">' . Yii::t('app', 'importFail', ['success' => $total_uploaded_numbers, 'fail' => $total_faulty_numbers]) . '</a></span>';
                } else if ($length == 0) {
                    $msg = '<span style="display: inline-block;">' . Yii::t('app', 'Imported Report Is Blank') . '</a></span>';
                }
                if ($length == 0 || $total_faulty_numbers > 0) {
                    Yii::$app->session->setFlash('errorimport', $msg);
                } else {
                    Yii::$app->session->setFlash('successimport', $msg);
                }
            }
        }else {
            Yii::$app->session->setFlash('error', Yii::t('app', 'invalid_format'));
        }
    }

    public function actionDownloadSampleFile()
    {
        /*$model = new BlackList();

        if ($model->import['fields']) {
            foreach ($model->import['fields'] as $k => $field) {

                $model->displayNames[$k] = $field['displayName'];
                $model->sampleValues[] = $field['sample'];
            }
        }

        $file = implode(',', $model->displayNames) . "\n" . implode(',', $model->sampleValues);

        header("Content-type:text/csv");
        header("Content-Disposition:attachment; filename=Sample-blacklist-file.csv");
        echo $file;
        exit;*/

        header("Content-Type: text/csv");
        header("Content-Disposition: attachment; filename=Sample-blacklist-file.csv");

        $output = fopen("php://output", "wb");
        $model = new BlackList();

        if ($model->import['fields']) {
            $header = $value = [];
            foreach ($model->import['fields'] as $k => $field) {
                $header[$k] = $field['displayName'];
                $value[] = $field['sample'];
            }
            fputcsv($output, $header);
            fputcsv($output, $value);
        }
        fclose($output);
        exit;
    }
}
