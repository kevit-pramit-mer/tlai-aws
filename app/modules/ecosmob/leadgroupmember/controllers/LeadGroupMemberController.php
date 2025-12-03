<?php

namespace app\modules\ecosmob\leadgroupmember\controllers;

use app\modules\ecosmob\leadgroup\models\LeadgroupMaster;
use app\modules\ecosmob\leadgroupmember\LeadGroupMemberModule;
use app\modules\ecosmob\leadgroupmember\models\LeadGroupMember;
use app\modules\ecosmob\leadgroupmember\models\LeadGroupMemberSearch;
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
 * LeadGroupMemberController implements the CRUD actions for LeadGroupMember model.
 */
class LeadGroupMemberController extends Controller
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
     * Lists all LeadGroupMember models.
     * @param $ld_id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionIndex($ld_id)
    {
        $LeadGroupMember = LeadgroupMaster::findOne($ld_id);
        if (empty($LeadGroupMember)) {
            throw new NotFoundHttpException(LeadGroupMemberModule::t('app', 'The requested page does not exist'));
        }
        $searchModel = new LeadGroupMemberSearch();
        $searchModel->ld_id = $ld_id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        Yii::$app->session->set('leads', $dataProvider->query);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'ld_id' => $ld_id
        ]);
    }

    /**
     * Displays a single LeadGroupMember model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Finds the LeadGroupMember model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return LeadGroupMember the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = LeadGroupMember::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(LeadGroupMemberModule::t('app', 'The requested page does not exist.'));
    }

    /**
     * Creates a new LeadGroupMember model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionCreate($ld_id)
    {
        $model = new LeadGroupMember();
        $model->ld_id = $ld_id;
        //$model->setScenario('creat');

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                if ($model->save()) {
                    Yii::$app->session->setFlash('success', LeadGroupMemberModule::t('lead-group-member', 'created_success'));
                    return $this->redirect([
                        'index',
                        'ld_id' => $ld_id,
                        'page' => Yii::$app->session->get('page')
                    ]);
                }
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing LeadGroupMember model.
     * If update is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        //$model->setScenario('update');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', LeadGroupMemberModule::t('lead-group-member', 'updated_success'));
            return $this->redirect(['index', 'ld_id' => $model->ld_id,]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing LeadGroupMember model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->delete();
        Yii::$app->session->setFlash('success', LeadGroupMemberModule::t('lead-group-member', 'deleted_success'));
        return $this->redirect(['index', 'ld_id' => $model->ld_id]);
    }

    /**
     * Export records shown in Index page
     */
    public function actionExport()
    {
        ini_set("memory_limit", -1);
        $fp = fopen('php://temp', 'w');
        $fileName = "Leads-" . time() . ".csv";
        $model = new LeadGroupMember();
        $query = Yii::$app->session->get('leads');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
            'sort' => ['defaultOrder' => ['lg_id' => SORT_DESC]],
        ]);

        $records = $dataProvider->getModels();

        $headers = [
            'lg_first_name',
            'lg_last_name',
            'lg_contact_number',
            'lg_contact_number_2',
            'lg_email_id',
            'lg_address',
            'lg_alternate_number',
            'lg_pin_code',
            'lg_permanent_address',
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
                    if ($head == 'lg_first_name') {
                        $row[$head] = (!empty($record->lg_first_name) ? $record->lg_first_name : '');
                    }
                    if ($head == 'lg_last_name') {
                        $row[$head] = (!empty($record->lg_last_name) ? $record->lg_last_name : '');
                    }
                    if ($head == 'lg_contact_number') {
                        $row[$head] = (!empty($record->lg_contact_number) ? $record->lg_contact_number : '');
                    }
                    if ($head == 'lg_contact_number_2') {
                        $row[$head] = (!empty($record->lg_contact_number_2) ? $record->lg_contact_number_2 : '');
                    }
                    if ($head == 'lg_email_id') {
                        $row[$head] = (!empty($record->lg_email_id) ? $record->lg_email_id : '');
                    }
                    if ($head == 'lg_address') {
                        $row[$head] = (!empty($record->lg_address) ? $record->lg_address : '');
                    }
                    if ($head == 'lg_alternate_number') {
                        $row[$head] = (!empty($record->lg_alternate_number) ? $record->lg_alternate_number : '');
                    }
                    if ($head == 'ph_cell_number') {
                        $row[$head] = (!empty($record->ph_cell_number) ? $record->ph_cell_number : '');
                    }
                    if ($head == 'lg_permanent_address') {
                        $row[$head] = (!empty($record->lg_permanent_address) ? $record->lg_permanent_address : '');
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
    public function actionImport($ld_id)
    {
        /** @var LeadGroupMemberSearch $searchModel */
        $searchModel = new LeadGroupMemberSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        Yii::$app->session->set('Leads', $dataProvider->query);

        $importModel = new LeadGroupMember();
        $importModel->ld_id = $ld_id;

        $importModel->setScenario('import');

        $transaction = Yii::$app->db->beginTransaction();

        try {
            if ($importModel->load(Yii::$app->request->post())) {

                $importModel->validate('importFileUpload');

                $importModel->importFileUpload = UploadedFile::getInstance($importModel, 'importFileUpload');

                if ($importModel->validate(['importFileUpload'])) {

                    $this->saveLeadMemberCSVData($importModel, $ld_id);

                    $transaction->commit();

                    return $this->redirect(['index', 'ld_id' => $ld_id,]);
                }

            }
        } catch (Exception $e) {
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', LeadGroupMemberModule::t('lead-group-member', 'something_wrong'));

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
     * @param $ld_id
     */
    private function saveLeadMemberCSVData($importModel, $ld_id)
    {
        $total_uploaded_numbers = 0;
        $total_faulty_numbers = 0;

        $fileExtension = $importModel->importFileUpload->getExtension();
        $tempName = $importModel->importFileUpload->tempName;
        $errors = $importModel->importFileUpload->error;

      /*  $lg_first_name = $importModel->lg_first_name;
        $lg_last_name = $importModel->lg_last_name;
        $lg_contact_number = $importModel->lg_contact_number;
        $lg_contact_number_2 = $importModel->lg_contact_number_2;
        $lg_email_id = $importModel->lg_email_id;
        $lg_address = $importModel->lg_address;
        $lg_alternate_number = $importModel->lg_alternate_number;
        $lg_pin_code = $importModel->lg_pin_code;
        $lg_permanent_address = $importModel->lg_permanent_address;*/

        $csvpath = Url::to(Yii::$app->params['tenantStorageFullPath'] . $GLOBALS['tenantID'] . '/csv/');

        if (!is_dir($csvpath)) {
            Yii::$app->storageHelper->makeDirAndGivePermission($csvpath);
        }
        $filename = 'failed_lead_group_members_' . time() . '.csv';
        $csvpath = $csvpath . $filename;
        $csvurl = Yii::$app->homeUrl . 'media/' . $GLOBALS['tenantID'] . '/csv/' . $filename;
        $handle2 = fopen($csvpath, "w");

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
                            if (count($row) == 9) {
                                foreach ($row as $key => $value) {
                                    if (isset($value)) {
                                        if (in_array('First Name', $headersArray)
                                            && in_array('Last Name', $headersArray)
                                            && in_array('Contact Number', $headersArray)
                                            && in_array('Contact Number Two', $headersArray)
                                            && in_array('Email ID', $headersArray)
                                            && in_array('Address', $headersArray)
                                            && in_array('Alternate Number', $headersArray)
                                            && in_array('Permanent Address', $headersArray)
                                        ) {
                                            $data[$i][$importArrayKeys[$key]] = $value;
                                        }else{
                                            $total_faulty_numbers++;
                                            $faileddata[$i] = $row;
                                            $errordata = array_values($row);
                                            $errordata[] = Yii::t('app', 'importColumnNameMismatch');
                                            fputcsv($handle2, $errordata);
                                        }
                                    }
                                }
                            } else {
                                $total_faulty_numbers++;
                                $faileddata[$i] = $row;
                                $errordata = array_values($row);
                                $errordata[] = Yii::t('app', 'importRecordsMismatch');
                                fputcsv($handle2, $errordata);
                            }
                        }
                        $i++;
                    }
                    fclose($handle);
                }
                $checkData = count($data);

                $data = array_values($data);

                $csvpath = Url::to(Yii::$app->params['tenantStorageFullPath'] . $GLOBALS['tenantID'] . '/csv/');

                if (!is_dir($csvpath)) {
                    Yii::$app->storageHelper->makeDirAndGivePermission($csvpath);
                }


                $length = count($data);
                if ($length != 0) {
                    for ($i = 0; $i < $length; $i++) {

                        try {
                            // $ld_id = $data[$i]['ld_id'];
                            $lg_first_name = $data[$i]['lg_first_name'];
                            $lg_last_name = $data[$i]['lg_last_name'];
                            $lg_contact_number = $data[$i]['lg_contact_number'];
                            $lg_contact_number_2 = $data[$i]['lg_contact_number_2'];
                            $lg_email_id = $data[$i]['lg_email_id'];
                            $lg_address = $data[$i]['lg_address'];
                            $lg_alternate_number = $data[$i]['lg_alternate_number'];
                            $lg_pin_code = $data[$i]['lg_pin_code'];
                            $lg_permanent_address = $data[$i]['lg_address'];

                        } catch (\Exception $e) {
                        }

                        $model = new LeadGroupMember();

                        //$model->setScenario('importsave');

                        $model->ld_id = $ld_id;
                        $model->lg_first_name = $lg_first_name;
                        $model->lg_last_name = $lg_last_name;
                        $model->lg_contact_number = $lg_contact_number;
                        $model->lg_contact_number_2 = $lg_contact_number_2;
                        $model->lg_email_id = $lg_email_id;
                        $model->lg_address = $lg_address;
                        $model->lg_alternate_number = $lg_alternate_number;

                        $model->lg_pin_code = $lg_pin_code;
                        $model->lg_permanent_address = $lg_permanent_address;


                        if ($model->validate() && $model->save()) {
                            $total_uploaded_numbers += 1;
                        } else {
                            $errordata = array_values($data[$i]);
                            $errorvalue = array_values($model->getErrors());
                            $errordata[] = implode(', ', call_user_func_array('array_merge', array_values($errorvalue)));
                            fputcsv($handle2, $errordata);
                            $total_faulty_numbers++;
                        }
                    }
                }
                $msg = Yii::t('app', 'importSuccess');
                fclose($handle2);

                /* $msg = LeadGroupMemberModule::t('lead-group-member', 'success');
                 $error = 0;
                 if ($checkData == 0) {
                     $error++;
                     $msg = LeadGroupMemberModule::t('lead-group-member', 'file_is_blank');
                 }
                 if ($total_faulty_numbers > 0) {
                     $error++;
                     $msg = '<span style="display: inline-block;">' . '<a href="' . $csvurl . '">'.Yii::t('app', 'importFail', ['success' => $total_uploaded_numbers, 'fail' => $total_faulty_numbers]).'</a></span>';
                 }
                 if($total_uploaded_numbers) {
                     $msg = LeadGroupMemberModule::t('lead-group-member', 'total').$total_uploaded_numbers. LeadGroupMemberModule::t('lead-group-member', 'imported_record');
                 }
                 if($error == 0) {
                     Yii::$app->session->setFlash('successimport', $msg);
                 } else {
                     Yii::$app->session->setFlash('errorimport', $msg);
                 }*/
                if ($total_faulty_numbers > 0) {
                    $msg = '<span style="display: inline-block;">' . '<a href="' . $csvurl . '" style="color:white;">' . Yii::t('app', 'importFail', ['success' => $total_uploaded_numbers, 'fail' => $total_faulty_numbers]) . '</a></span>';
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
            Yii::$app->session->setFlash('errorimport', LeadGroupMemberModule::t('lead-group-member', 'invalid_format'));
        }
    }

    public function actionDownloadSampleFile()
    {
        $model = new LeadGroupMember();
        if ($model->import['fields']) {
            foreach ($model->import['fields'] as $k => $field) {
                $model->displayNames[$k] = $field['displayName'];
                $model->sampleValues[] = $field['sample'];
            }
        }

        $file = implode(',', $model->displayNames) . "\n" . implode(',', $model->sampleValues);

        header("Content-type:text/csv");
        header("Content-Disposition:attachment; filename=Sample-Leads-file.csv");
        echo $file;
        exit;
    }

}
