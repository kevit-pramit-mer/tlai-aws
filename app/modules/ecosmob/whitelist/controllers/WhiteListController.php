<?php

namespace app\modules\ecosmob\whitelist\controllers;

use app\modules\ecosmob\whitelist\models\WhiteList;
use app\modules\ecosmob\whitelist\models\WhiteListSearch;
use app\modules\ecosmob\whitelist\WhiteListModule;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use Exception;
use Throwable;
use yii\base\InvalidArgumentException;
use yii\db\StaleObjectException;
use yii\web\Response;

/**
 * WhiteListController implements the CRUD actions for WhiteList model.
 */
class WhiteListController extends Controller
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
     * Lists all WhiteList models.
     *
     * @return mixed
     * @throws \yii\base\InvalidParamException
     */
    public function actionIndex()
    {
        $searchModel = new WhiteListSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $importModel = new WhiteList();
        $importModel->setScenario('import');

        return $this->render('index',
            [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
                'importModel' => $importModel,
            ]);
    }

    /**
     * @return string|Response
     * @throws InvalidArgumentException
     */
    public function actionImport()
    {
        /** @var WhiteList $searchModel */
        $searchModel = new WhiteListSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        Yii::$app->session->set('querywhitelist', $dataProvider->query);

        $importModel = new WhiteList();

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
                            if (count($row) == 2) {
                                foreach ($row as $key => $value) {
                                    if (isset($value)) {
                                        if (in_array('White List Number', $headersArray)
                                            && in_array('Description', $headersArray)
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

                if ($length != 0) {
                    for ($i = 1; $i <= $length; $i++) {

                        $number = $data[$i]['wl_number'];
                        $description = $data[$i]['wl_description'];

                        $model = new WhiteList();
                        $model->wl_number = $number;
                        $model->wl_description = $description;
                        $model->created_date = date('Y-m-d H:i:s');
                        $model->updated_date = date('Y-m-d H:i:s');
                        if ($model->validate() && $model->save()) {
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

    /**
     * Displays a single WhiteList model.
     *
     * @param integer $id
     *
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws \yii\base\InvalidParamException
     */
    public function actionView($id)
    {
        return $this->render('view',
            [
                'model' => $this->findModel($id),
            ]);
    }

    /**
     * Finds the WhiteList model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     *
     * @return WhiteList the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = WhiteList::findOne($id)) !== NULL) {
            return $model;
        }

        throw new NotFoundHttpException(WhiteListModule::t('wl', 'not_found'));
    }

    /**
     * Creates a new WhiteList model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     *
     * @return mixed
     * @throws InvalidArgumentException
     */
    public function actionCreate()
    {
        $model = new WhiteList();

        if ($model->load(Yii::$app->request->post())) {
            $model->updated_date = date('Y-m-d H:i:s');
            $model->created_date = date('Y-m-d H:i:s');

            if ($model->validate()) {
                if ($model->save()) {
                    Yii::$app->session->setFlash('success', WhiteListModule::t('wl', 'created_success'));

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
     * Updates an existing WhiteList model.
     * If update is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     *
     * @return mixed
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
                    Yii::$app->session->setFlash('success', WhiteListModule::t('wl', 'updated_success'));
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
        Yii::$app->session->setFlash('success', WhiteListModule::t('wl', 'deleted_success'));

        return $this->redirect(['index']);
    }

    /**
     *
     */
    public function actionDownloadSampleFile()
    {
     /*   $model = new WhiteList();

        if ($model->import['fields']) {
            foreach ($model->import['fields'] as $k => $field) {

                $model->displayNames[$k] = $field['displayName'];
                $model->sampleValues[] = $field['sample'];
            }
        }

        $file = implode(',', $model->displayNames) . "\n" . implode(',', $model->sampleValues);

        header("Content-type:text/csv");
        header("Content-Disposition:attachment; filename=Sample-whitelist-file.csv");
        echo $file;
        exit;*/

        header("Content-Type: text/csv");
        header("Content-Disposition: attachment; filename=Sample-whitelist-file.csv");

        $output = fopen("php://output", "wb");
        $model = new WhiteList();

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
