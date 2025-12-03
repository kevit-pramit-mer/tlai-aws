<?php

namespace app\modules\ecosmob\enterprisePhonebook\controllers;

use app\modules\ecosmob\enterprisePhonebook\EnterprisePhonebookModule;
use app\modules\ecosmob\enterprisePhonebook\models\EnterprisePhonebook;
use app\modules\ecosmob\enterprisePhonebook\models\EnterprisePhonebookSearch;
use app\modules\ecosmob\extension\models\Extension;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * EnterprisePhonebookController implements the CRUD actions for EnterprisePhonebook model.
 */
class EnterprisePhonebookController extends Controller
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
                            'view',
                            'update',
                            'delete',
                            'export',
                            'import',
                            'download-sample-file',
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
     * Lists all EnterprisePhonebook models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new EnterprisePhonebookSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        Yii::$app->session->set('enterprisePhonebook', $dataProvider->query);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single EnterprisePhonebook model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView()
    {
        $searchModel = new EnterprisePhonebookSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        Yii::$app->session->set('enterprisePhonebook', $dataProvider->query);

        return $this->renderPartial('view', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Finds the EnterprisePhonebook model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return EnterprisePhonebook the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = EnterprisePhonebook::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Creates a new EnterprisePhonebook model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new EnterprisePhonebook();

        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                if ($model->save()) {
                    if (Yii::$app->session->get('isTragofone') == 1) {
                        $extNumber = '';
                        if(!empty($model->en_extension)) {
                            $extension = Extension::findOne($model->en_extension);
                            if(!empty($extension)){
                                $extNumber = $extension->em_extension_number;
                            }
                        }
                        $data = [
                            "ed_first_name" => $model->en_first_name,
                            "ed_last_name" => $model->en_last_name,
                            "ed_email_id" => $model->en_email_id,
                            "ed_mobile" => $model->en_mobile,
                            "ed_landline" => $model->en_phone,
                            "ed_extension" => $extNumber,
                            "ed_status" => ($model->en_status == '1' ? 'Y' : 'N'),
                        ];
                        $api = Yii::$app->tragofoneHelper->phonebookCreate($data);
                        if (!empty($api)) {
                            $api = json_decode($api, true);
                            if ($api['status'] == 'SUCCESS') {
                                $model->trago_ed_id = $api['data']['ed_id'];
                                $model->save(false);
                                $transaction->commit();
                                Yii::$app->session->setFlash('success', EnterprisePhonebookModule::t('app', 'created_success'));
                                return $this->redirect(['index']);
                            } else {
                                $transaction->rollBack();
                                Yii::$app->session->setFlash('error', EnterprisePhonebookModule::t('app', 'tragoApiError', ['message' => $api['message']]));
                                return $this->redirect(['index']);
                            }
                        } else {
                            $transaction->rollBack();
                            Yii::$app->session->setFlash('error', EnterprisePhonebookModule::t('app', 'tragoApiResponseNotFound'));
                            return $this->redirect(['index']);
                        }
                    } else {
                        $transaction->commit();
                        Yii::$app->session->setFlash('success', EnterprisePhonebookModule::t('app', 'created_success'));
                        return $this->redirect(['index']);
                    }
                }
            }
        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', $e->getMessage());
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing EnterprisePhonebook model.
     * If update is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                if ($model->save()) {
                    if (Yii::$app->session->get('isTragofone') == 1) {
                        $extNumber = '';
                        if(!empty($model->en_extension)) {
                            $extension = Extension::findOne($model->en_extension);
                            if(!empty($extension)){
                                $extNumber = $extension->em_extension_number;
                            }
                        }
                        $data = [
                            "ed_first_name" => $model->en_first_name,
                            "ed_last_name" => $model->en_last_name,
                            "ed_email_id" => $model->en_email_id,
                            "ed_mobile" => $model->en_mobile,
                            "ed_landline" => $model->en_phone,
                            "ed_extension" => $extNumber,
                            "ed_status" => ($model->en_status == '1' ? 'Y' : 'N')
                        ];
                        if (!empty($model->trago_ed_id)) {
                            $data["ed_id"] = $model->trago_ed_id;
                            $api = Yii::$app->tragofoneHelper->phonebookUpdate($data);
                        } else {
                            $api = Yii::$app->tragofoneHelper->phonebookCreate($data);
                        }

                        if (!empty($api)) {
                            $api = json_decode($api, true);
                            if ($api['status'] == 'SUCCESS') {
                                $model->trago_ed_id = $api['data']['ed_id'];
                                $model->save(false);
                                $transaction->commit();
                                Yii::$app->session->setFlash('success', EnterprisePhonebookModule::t('app', 'updated_success'));
                                return $this->redirect(['index']);
                            } else {
                                $transaction->rollBack();
                                Yii::$app->session->setFlash('error', EnterprisePhonebookModule::t('app', 'tragoApiError', ['message' => $api['message']]));
                                return $this->render('update', [
                                    'model' => $model,
                                ]);
                            }
                        } else {
                            $transaction->rollBack();
                            Yii::$app->session->setFlash('error', EnterprisePhonebookModule::t('app', 'tragoApiResponseNotFound'));
                            return $this->render('update', [
                                'model' => $model,
                            ]);
                        }
                    } else {
                        $transaction->commit();
                        Yii::$app->session->setFlash('success', EnterprisePhonebookModule::t('app', 'updated_success'));
                        return $this->redirect(['index']);
                    }
                }
            }
        } catch (\Exception $e) {
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', $e->getMessage());
            return $this->render('update', [
                'model' => $model,
            ]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing EnterprisePhonebook model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = EnterprisePhonebook::findOne($id);
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if (Yii::$app->session->get('isTragofone') == 1 && !empty($model->trago_ed_id)) {
                $api = Yii::$app->tragofoneHelper->phonebookDelete($model->trago_ed_id);
                if (!empty($api)) {
                    $api = json_decode($api, true);
                    if ($api['status'] == 'SUCCESS') {
                        $model->delete();
                        $transaction->commit();
                        Yii::$app->session->setFlash('success', EnterprisePhonebookModule::t('app', 'deleted_success'));
                        return $this->redirect(['index']);
                    } else {
                        $transaction->rollBack();
                        Yii::$app->session->setFlash('error', EnterprisePhonebookModule::t('app', 'tragoApiError', ['message' => $api['message']]));
                        return $this->redirect(['index']);
                    }
                } else {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('error', EnterprisePhonebookModule::t('app', 'tragoApiResponseNotFound'));
                    return $this->redirect(['index']);
                }
            } else {
                $model->delete();
                $transaction->commit();
                Yii::$app->session->setFlash('success', EnterprisePhonebookModule::t('app', 'deleted_success'));
                return $this->redirect(['index']);
            }
        } catch (Exception $e) {
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', $e->getMessage());
            return $this->redirect(['index']);
        }
    }

    /**
     * Export records shown in Index page
     */
    public function actionExport()
    {
        ini_set("memory_limit", -1);
        $fp = fopen('php://temp', 'w');
        $fileName = "Enterprise_Phonebook-" . time() . ".csv";
        $model = new EnterprisePhonebook();
        $query = Yii::$app->session->get('enterprisePhonebook');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]],
        ]);

        $records = $dataProvider->getModels();

        $headers = [
            'en_first_name',
            'en_last_name',
            'en_extension',
            'en_mobile',
            'en_phone',
            'en_email_id',
            'en_status'
        ];

        $row = array();
        foreach ($headers as $header) {
            $row[] = $model->getAttributeLabel($header);
        }

        fputcsv($fp, $row);
        if (!empty($records)) {
            foreach ($records as $record) {
                $row = [];
                foreach ($headers as $head) {
                    $row[$head] = $record->$head;
                    if ($head == 'en_first_name') {
                        $row[$head] = (!empty($record->en_first_name) ? $record->en_first_name : '');
                    }
                    if ($head == 'en_last_name') {
                        $row[$head] = (!empty($record->en_last_name) ? $record->en_last_name : '');
                    }
                    if ($head == 'en_extension') {
                        $row[$head] = (!empty($record->extension) ? $record->extension->em_extension_name." - ".$record->extension->em_extension_number : '');
                    }
                    if ($head == 'en_mobile') {
                        $row[$head] = (!empty($record->en_mobile) ? $record->en_mobile : '');
                    }
                    if ($head == 'en_phone') {
                        $row[$head] = (!empty($record->en_phone) ? $record->en_phone : '');
                    }
                    if ($head == 'en_email_id') {
                        $row[$head] = (!empty($record->en_email_id) ? $record->en_email_id : '');
                    }
                    if ($head == 'en_status') {
                        $row[$head] = ($record->en_status == '1' ? 'Active' : 'Inactive');
                    }
                }
                fputcsv($fp, $row);
            }
        }
        rewind($fp);
        header("Content-type:text/csv");
        header("Content-Disposition:attachment; filename=" . $fileName);
        $file = stream_get_contents($fp);
        // echo "\xEF\xBB\xBF";
        echo $file;
        fclose($fp);
        exit;
    }

    public function actionDownloadSampleFile()
    {
        header("Content-Type: text/csv");
        header("Content-Disposition: attachment; filename=Sample-Enterprise-phonebook-file.csv");

        $output = fopen("php://output", "wb");
        $model = new EnterprisePhonebook();

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

    /**
     * @return string|Response
     * @throws InvalidArgumentException
     */
    public function actionImport()
    {
        /** @var EnterprisePhonebookSearch $searchModel */
        $searchModel = new EnterprisePhonebookSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        Yii::$app->session->set('phonebook', $dataProvider->query);

        $importModel = new EnterprisePhonebook();
        $importModel->setScenario('import');

        $transaction = Yii::$app->db->beginTransaction();

        try {
            if ($importModel->load(Yii::$app->request->post())) {
                $importModel->importFileUpload = UploadedFile::getInstance($importModel, 'importFileUpload');

                if ($importModel->validate(['importFileUpload'])) {
                    $this->saveCSVData($importModel);

                    $transaction->commit();

                    return $this->redirect(['index']);
                }
            }
        } catch (Exception $e) {
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', EnterprisePhonebookModule::t('app', 'something_wrong'));

            return $this->redirect(['index']);
        }

        return $this->render('import',
            [
                'importModel' => $importModel,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
    }

    /**
     * @param $importModel
     */
    private function saveCSVData($importModel)
    {
        $fileExtension = $importModel->importFileUpload->getExtension();
        $tempName = $importModel->importFileUpload->tempName;
        $errors = $importModel->importFileUpload->error;

        $csvPath = Url::to(Yii::$app->params['tenantStorageFullPath'] . $GLOBALS['tenantID'] . '/csv/enterprisePhonebook/');

        if (!is_dir($csvPath)) {
            Yii::$app->storageHelper->makeDirAndGivePermission($csvPath);
        }

        $fileName = 'failed_enterprise_phonebook_' . time() . '.csv';
        $csvPath = $csvPath . $fileName;
        $csvUrl = Yii::$app->homeUrl . 'media/' . $GLOBALS['tenantID'] . '/csv/enterprisePhonebook/' . $fileName;
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
                            if (count($row) == 7) {
                                foreach ($row as $key => $value) {
                                    if (isset($value)) {
                                        if (in_array('First Name', $headersArray)
                                            && in_array('Last Name', $headersArray)
                                            && in_array('Extension', $headersArray)
                                            && in_array('Mobile Number', $headersArray)
                                            && in_array('Phone Number (Landline)', $headersArray)
                                            && in_array('Email Address', $headersArray)
                                            && in_array('Status', $headersArray)
                                        ) {
                                            $data[$i][$importArrayKeys[$key]] = $value;
                                        } else {
                                            $total_faulty_numbers++;
                                            $failedData[$i] = $row;
                                            $errorData = array_values($row);
                                            $errorData[] = Yii::t('app', 'importColumnNameMismatch');
                                            fputcsv($handle2, $errorData);
                                        }
                                    }
                                }
                            } else {
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
                        $transaction = Yii::$app->db->beginTransaction();
                        try {
                            $firstName = $data[$i]['en_first_name'];
                            $lastName = $data[$i]['en_last_name'];
                            if (!empty($data[$i]['en_extension'])) {
                                $ext = Extension::findOne(['em_extension_number' => $data[$i]['en_extension']]);
                                if (!empty($ext)) {
                                    $extension = $ext->em_id;
                                } else {
                                    $extension = 'notvalid';
                                }
                            } else {
                                $extension = '';
                            }
                            $mobile = $data[$i]['en_mobile'];
                            $phone = $data[$i]['en_phone'];
                            $email = $data[$i]['en_email_id'];
                            if ($data[$i]['en_status'] != "0" && $data[$i]['en_status'] != "1") {
                                $status = (!empty($data[$i]['en_status']) ? (strtolower($data[$i]['en_status']) == 'active' ? '1' : (strtolower($data[$i]['en_status']) == 'inactive' ? '0' : $data[$i]['en_status'])) : '1');
                            } else {
                                $status = 'notvalid';
                            }

                            $model = new EnterprisePhonebook();
                            $model->en_first_name = $firstName;
                            $model->en_last_name = $lastName;
                            $model->en_extension = $extension;
                            $model->en_mobile = $mobile;
                            $model->en_phone = $phone;
                            $model->en_email_id = $email;
                            $model->en_status = $status;
                            if ($model->validate() && $model->save() && $extension != 'notvalid') {
                                if (Yii::$app->session->get('isTragofone') == 1) {
                                    $extNumber = '';
                                    if(!empty($model->en_extension)) {
                                        $extension = Extension::findOne($model->en_extension);
                                        if(!empty($extension)){
                                            $extNumber = $extension->em_extension_number;
                                        }
                                    }
                                    $apiData = [
                                        "ed_first_name" => $model->en_first_name,
                                        "ed_last_name" => $model->en_last_name,
                                        "ed_email_id" => $model->en_email_id,
                                        "ed_mobile" => $model->en_mobile,
                                        "ed_landline" => $model->en_phone,
                                        "ed_extension" => $extNumber,
                                        "ed_status" => ($model->en_status == '1' ? 'Y' : 'N'),
                                    ];
                                    $api = Yii::$app->tragofoneHelper->phonebookCreate($apiData);
                                    if (!empty($api)) {
                                        $api = json_decode($api, true);
                                        if ($api['status'] == 'SUCCESS') {
                                            $model->trago_ed_id = $api['data']['ed_id'];
                                            $model->save(false);
                                            $transaction->commit();
                                            $total_uploaded_numbers++;
                                            $dataCsv = array_values($data[$i]);
                                            $dataCsv[] = Yii::t('app', 'success');
                                            fputcsv($handle2, $dataCsv);
                                        } else {
                                            $transaction->rollBack();
                                            $total_faulty_numbers++;
                                            $errorValue[] = [EnterprisePhonebookModule::t('app', 'tragoApiError', ['message' => $api['message']])];
                                            $errorData = array_values($data[$i]);
                                            $errorData[] = implode(', ', call_user_func_array('array_merge', array_values($errorValue)));
                                            fputcsv($handle2, $errorData);
                                        }
                                    } else {
                                        $transaction->rollBack();
                                        $total_faulty_numbers++;
                                        $errorValue[] = [EnterprisePhonebookModule::t('app', 'tragoApiResponseNotFound')];
                                        $errorData = array_values($data[$i]);
                                        $errorData[] = implode(', ', call_user_func_array('array_merge', array_values($errorValue)));
                                        fputcsv($handle2, $errorData);
                                    }
                                }else {
                                    $transaction->commit();
                                    $total_uploaded_numbers++;
                                    $dataCsv = array_values($data[$i]);
                                    $dataCsv[] = Yii::t('app', 'success');
                                    fputcsv($handle2, $dataCsv);
                                }
                            } else {
                                $transaction->rollBack();
                                $errorValue = $model->getErrors();
                                if ($extension == 'notvalid') {
                                    $errorValue[] = [EnterprisePhonebookModule::t('app', 'extensionError')];
                                }
                                $errorData = array_values($data[$i]);
                                $errorData[] = implode(', ', call_user_func_array('array_merge', array_values($errorValue)));
                                fputcsv($handle2, $errorData);
                                $total_faulty_numbers++;
                            }
                        } catch (\Exception $e) {
                            $transaction->rollBack();
                            $total_faulty_numbers++;
                            $errorValue[] = [$e->getMessage()];
                            $errorData = array_values($data[$i]);
                            $errorData[] = implode(', ', call_user_func_array('array_merge', array_values($errorValue)));
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
        } else {
            Yii::$app->session->setFlash('error', Yii::t('app', 'invalid_format'));
        }
    }
}
