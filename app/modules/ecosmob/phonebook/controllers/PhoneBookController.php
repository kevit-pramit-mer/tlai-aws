<?php

namespace app\modules\ecosmob\phonebook\controllers;

use app\modules\ecosmob\phonebook\models\Phonebook;
use app\modules\ecosmob\phonebook\models\PhoneBookSearch;
use app\modules\ecosmob\phonebook\PhoneBookModule;
use Yii;
use yii\base\InvalidArgumentException;
use yii\data\ActiveDataProvider;
use yii\db\Exception;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * PhoneBookController implements the CRUD actions for Phonebook model.
 */
class PhoneBookController extends Controller
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
                /*'actions' => [
                    'delete' => ['POST'],
                ],*/
            ],
        ];
    }

    /**
     * Lists all Phonebook models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PhoneBookSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        Yii::$app->session->set('phonebook', $dataProvider->query);

        return $this->renderPartial('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Export records shown in Index page
     */
    public function actionExport()
    {
        ini_set("memory_limit", -1);
        $fp = fopen('php://temp', 'w');
        $fileName = "Phonebook-" . time() . ".csv";
        $model = new Phonebook();
        $query = Yii::$app->session->get('phonebook');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
            'sort' => ['defaultOrder' => ['ph_id' => SORT_DESC]],
        ]);

        $records = $dataProvider->getModels();

        $headers = [
            'ph_first_name',
            'ph_last_name',
            'ph_display_name',
            'ph_extension',
            'ph_phone_number',
            //'ph_cell_number',
            'ph_email_id',
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
                    if ($head == 'ph_first_name') {
                        $row[$head] = (!empty($record->ph_first_name) ? $record->ph_first_name : '');
                    }
                    if ($head == 'ph_last_name') {
                        $row[$head] = (!empty($record->ph_last_name) ? $record->ph_last_name : '');
                    }
                    if ($head == 'ph_display_name') {
                        $row[$head] = (!empty($record->ph_display_name) ? $record->ph_display_name : '');
                    }
                    if ($head == 'ph_extension') {
                        $row[$head] = (!empty($record->ph_extension) ? $record->ph_extension : '');
                    }
                    if ($head == 'ph_phone_number') {
                        $row[$head] = (!empty($record->ph_phone_number) ? $record->ph_phone_number : '');
                    }
                   /* if ($head == 'ph_cell_number') {
                        $row[$head]=(!empty($record->ph_cell_number) ? $record->ph_cell_number : '');
                    }*/
                    if ($head == 'ph_email_id') {
                        $row[$head] = (!empty($record->ph_email_id) ? $record->ph_email_id : '');
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


    /**
     * @return string|Response
     * @throws InvalidArgumentException
     */
    public function actionImport()
    {
        /** @var PhoneBookSearch $searchModel */
        $searchModel = new PhoneBookSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        Yii::$app->session->set('phonebook', $dataProvider->query);

        $importModel = new Phonebook();
        $importModel->setScenario('import');

        $transaction = Yii::$app->db->beginTransaction();

        try {
            if ($importModel->load(Yii::$app->request->post())) {
                $importModel->importFileUpload = UploadedFile::getInstance($importModel, 'importFileUpload');

                if ($importModel->validate(['importFileUpload'])) {
                    $this->savePhoneBookData($importModel);

                    $transaction->commit();

                    $searchModel = new PhoneBookSearch();
                    $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
                    Yii::$app->session->set('phonebook', $dataProvider->query);

                    return $this->renderPartial('index', [
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider,
                    ]);
                }
            }
        } catch (Exception $e) {
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', PhoneBookModule::t('app', 'something_wrong'));

            $searchModel = new PhoneBookSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            Yii::$app->session->set('phonebook', $dataProvider->query);

            return $this->renderPartial('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }

        return $this->renderPartial('import',
            [
                'importModel' => $importModel,
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
    }

    /**
     * @param $importModel
     */
    private function savePhoneBookData($importModel)
    {

        $fileExtension = $importModel->importFileUpload->getExtension();
        $tempName = $importModel->importFileUpload->tempName;
        $errors = $importModel->importFileUpload->error;

        $ph_first_name=$importModel->ph_first_name;
        $ph_last_name=$importModel->ph_last_name;
        $ph_display_name=$importModel->ph_display_name;
        $ph_extension=$importModel->ph_extension;
        $ph_phone_number=$importModel->ph_phone_number;
        //$ph_cell_number=$importModel->ph_cell_number;
        $ph_email_id=$importModel->ph_email_id;


        if ($errors == 0) {
            if (($fileExtension == "csv") && (!empty($tempName))) {
                $i = 0;
                $delimiter = ',';
                $data = [];
                $headersArray = [];
                if (($handle = fopen($tempName, 'r')) !== false) {
                    while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
                        if ($i == 0) {
                            $headersArray = $row;
                        }
                        if (empty($headersArray[0]) || empty($row)) {
                            Yii::$app->session->setFlash('error', PhoneBookModule::t('app', 'invalid_format'));
                            $searchModel = new PhoneBookSearch();
                            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
                            Yii::$app->session->set('phonebook', $dataProvider->query);

                            return $this->renderPartial('index', [
                                'searchModel' => $searchModel,
                                'dataProvider' => $dataProvider,
                            ]);
                        }

                        $importArrayKeys = array_keys($importModel->import['fields']);
                        if ($i != 0) {


                            if (count($row) >= 6) {

                                foreach ($row as $key => $value) {

                                    if (isset($value)) {

                                        if (in_array('First_Name', $headersArray)
                                            && in_array('Last_Name', $headersArray)
                                            && in_array('Display_Name', $headersArray)
                                            && in_array('Extension', $headersArray)
                                            && in_array('Phone_Number', $headersArray)
                                            //&& in_array('Cell Number', $headersArray)
                                            && in_array('Email_ID', $headersArray)
                                        ) {
                                            $data[$i][$importArrayKeys[$key]] = $value;
                                        }
                                    }
                                }
                            }
                        }
                        $i++;
                    }
                    fclose($handle);
                    if (empty($importArrayKeys)) {
                        Yii::$app->session->setFlash('error', PhoneBookModule::t('app', 'invalid_format'));
                        $searchModel = new PhoneBookSearch();
                        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
                        Yii::$app->session->set('phonebook', $dataProvider->query);

                        return $this->renderPartial('index', [
                            'searchModel' => $searchModel,
                            'dataProvider' => $dataProvider,
                        ]);
                    }
                }


                $length = count($data);
                $total_uploaded_numbers = 0;
                $total_faulty_numbers = 0;
                $alreadyExist = 0;
                $fileNotValid = 0;

                /* header('Content-Type: text/csv; charset=utf-8');
                header('Content-Disposition: attachment; filename=result-data.csv');*/

                $csvPath = Url::to(Yii::$app->params['tenantStorageFullPath'] . $GLOBALS['tenantID'] . '/csv/');

                if (!is_dir($csvPath)) {
                    Yii::$app->storageHelper->makeDirAndGivePermission($csvPath);
                }
                $filename = 'ext_' . time() . '.csv';
                $csvPath = $csvPath . $filename;
                $csvUrl = Yii::$app->homeUrl . 'media/' . $GLOBALS['tenantID'] . '/csv/' . $filename;
                $handle2 = fopen($csvPath, "w");

                /*$handle2=fopen("php://output", "w");*/
                $ExportArrayKeys = $importArrayKeys;
                $ExportArrayKeys[] = PhoneBookModule::t('app', 'result');
                fputcsv($handle2, $ExportArrayKeys);

                if ($length != 0) {
                    for ($i = 1; $i <= $length; $i++) {
                        try {
                            $ph_first_name=$data[$i]['ph_first_name'];
                            $ph_last_name=$data[$i]['ph_last_name'];
                            $ph_display_name=$data[$i]['ph_display_name'];
                            $ph_extension=$data[$i]['ph_extension'];
                            $ph_phone_number=$data[$i]['ph_phone_number'];
                            //$ph_cell_number=$data[$i]['ph_cell_number'];
                            $ph_email_id=$data[$i]['ph_email_id'];
                        } catch (\Exception $e) {
                            $fileNotValid = 1;
                        }
                        $model = new Phonebook();
                        $model->ph_first_name = $ph_first_name;
                        $model->ph_last_name = $ph_last_name;
                        $model->ph_display_name = $ph_display_name;
                        /*$model->ph_extension=Yii::$app->user->identity->em_extension_number;*/
                        $model->ph_extension = $ph_extension;
                        $model->ph_phone_number=$ph_phone_number;
                        $model->ph_cell_number=$ph_phone_number;
                        $model->ph_email_id=$ph_email_id;
                        $model->em_extension = Yii::$app->user->identity->em_extension_number;

                        if ($model->save()) {
                            $total_uploaded_numbers += 1;

                            $errorData = array_values($data[$i]);
                            $errorData[] = Yii::t('app', 'success');
                            fputcsv($handle2, $errorData);

                        } else {

                            $errorData = array_values($data[$i]);
                            $errorData[] = json_encode(array_values($model->getErrors()), TRUE);
                            fputcsv($handle2, $errorData);

                            $total_faulty_numbers++;
                        }
                    }
                }

                fclose($handle2);

                if ($total_uploaded_numbers == 0) {
                    Yii::$app->session->setFlash('error', '<span style="display: inline-block;">'.'<a href="'.$csvUrl.'" style="color: white">'.PhoneBookModule::t('app', 'unable_import').'</a></span>');
                    $searchModel = new PhoneBookSearch();
                    $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
                    Yii::$app->session->set('phonebook', $dataProvider->query);

                    return $this->renderPartial('index', [
                        'searchModel' => $searchModel,
                        'dataProvider' => $dataProvider,
                    ]);
                }
                ($fileNotValid == 1)
                    ? Yii::$app->session->setFlash('error',
                    PhoneBookModule::t('app', 'invalid_structure'))
                    :
                    Yii::$app->session->setFlash('success',
                        '<span style="display: inline-block;">'.'<a href="'.$csvUrl.'" style="color: white">'.PhoneBookModule::t('app','download_result_file').'</a></span>'
                    );
                /*Yii::$app->session->setFlash('success',
                    PhoneBookModule::t('app',
                        'total_uploaded_number') . ' : ' . $total_uploaded_numbers . ", " .
                    PhoneBookModule::t('app', 'already_exist') . ' : ' . $alreadyExist . ", " .
                    PhoneBookModule::t('app',
                        'total_faulty_number') . ' : ' . $total_faulty_numbers);*/

            } else {
                Yii::$app->session->setFlash('error', PhoneBookModule::t('app', 'invalid_format'));
            }
        }
    }


    /**
     *
     */
    public function actionDownloadSampleFile()
    {
        $model = new Phonebook();
        if ($model->import['fields']) {
            foreach ($model->import['fields'] as $k => $field) {

                $model->displayNames[$k] = $field['displayName'];
                $model->sampleValues[] = $field['sample'];
            }
        }

        $file = implode(',', $model->displayNames) . "\n" . implode(',', $model->sampleValues);

        header("Content-type:text/csv");
        header("Content-Disposition:attachment; filename=Sample-phonebook-file.csv");
        echo $file;
        exit;
    }


    /**
     * Creates a new Phonebook model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Phonebook();
        $model->em_extension = Yii::$app->user->identity->em_extension_number;


        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->ph_last_name = trim($model->ph_last_name);
            $model->ph_display_name = trim($model->ph_display_name);
            $model->ph_cell_number = $model->ph_phone_number;
            $model -> save(false);
            Yii::$app->session->setFlash('success', PhoneBookModule::t('app', 'created_success'));
            $searchModel = new PhoneBookSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            Yii::$app->session->set('phonebook', $dataProvider->query);

            return $this->renderPartial('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }

        return $this->renderPartial('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Phonebook model.
     * If update is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->ph_last_name = trim($model->ph_last_name);
            $model->ph_display_name = trim($model->ph_display_name);
            $model->ph_cell_number = $model->ph_phone_number;
            $model->save();
            Yii::$app->session->setFlash('success', PhoneBookModule::t('app', 'updated_success'));
            $searchModel = new PhoneBookSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            Yii::$app->session->set('phonebook', $dataProvider->query);

            return $this->renderPartial('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }

        return $this->renderPartial('update', [
            'model' => $model,
        ]);
    }

    /**
     * Finds the Phonebook model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Phonebook the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Phonebook::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(PhoneBookModule::t('app', 'page_not_exits'));
    }

    /**
     * Deletes an existing Phonebook model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', PhoneBookModule::t('app', 'deleted_success'));
        $searchModel = new PhoneBookSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        Yii::$app->session->set('phonebook', $dataProvider->query);

        return $this->renderPartial('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
}
