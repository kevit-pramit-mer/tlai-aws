<?php

namespace app\modules\ecosmob\didmanagement\controllers;

use app\models\DidHoliday;
use app\models\DidTimeBased;
use app\modules\ecosmob\autoattendant\models\AutoAttendantMaster;
use app\modules\ecosmob\campaign\models\Campaign;
use app\modules\ecosmob\conference\models\ConferenceMaster;
use app\modules\ecosmob\didmanagement\DidManagementModule;
use app\modules\ecosmob\didmanagement\models\DidManagement;
use app\modules\ecosmob\didmanagement\models\DidManagementSearch;
use app\modules\ecosmob\extension\models\Extension;
use app\modules\ecosmob\fax\models\Fax;
use app\modules\ecosmob\playback\models\Playback;
use app\modules\ecosmob\queue\models\QueueMaster;
use app\modules\ecosmob\ringgroup\models\RingGroup;
use app\modules\ecosmob\services\models\Services;
use Yii;
use yii\base\InvalidArgumentException;
use yii\db\Exception;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;
use app\components\CommonHelper;

/**
 * DIDManagementController implements the CRUD actions for DidManagement model.
 */
class DidManagementController extends Controller
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
                            'change-action',
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
     * Lists all DidManagement models.
     *
     * @return string
     * @throws InvalidArgumentException
     */
    public function actionIndex()
    {
        $searchModel = new DidManagementSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index',
            [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
    }

    /**
     * Creates a new DidManagement model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     *
     * @return Response|string
     * @throws InvalidArgumentException
     */
    public function actionCreate()
    {
        $licenseData = Yii::$app->commonHelper->getLicenseData($_SERVER['HTTP_HOST']);
        if(!empty($licenseData)) {
            $maxDid = $licenseData['maxDID'];
            $totalDid = DidManagement::find()->where(['from_service' => '0'])->count();
            if ($totalDid >= $maxDid) {
                Yii::$app->session->setFlash('error', Yii::t('app', 'license_limit_exceed'));
                return $this->redirect(['index']);
            }
        }
        $model = new DidManagement();
        $model->setScenario('create');
        /*$faxList=Fax::find()->select(['id', 'fax_name'])->all();*/
        $transaction = Yii::$app->db->beginTransaction();
        $didTimeBasedModel = new DidTimeBased();

        try {
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                /*echo '<pre>';
                print_r($_POST);exit;*/
                $model->is_time_based = Yii::$app->request->post('is_time_based');
                if ($model->type == 'range') {
                    $this->saveDids($model, $_POST['DidTimeBased'], $model->is_time_based, $model->days);
                } else {
                    $model->did_status = 1;
                    if($model->save(FALSE)){
                        if($model->is_time_based == 1) {
                            if (!empty($model->holiday)) {
                                foreach ($model->holiday as $_holiday) {
                                    $didHoliday = new DidHoliday();
                                    $didHoliday->did_id = $model->did_id;
                                    $didHoliday->hd_id = $_holiday;
                                    $didHoliday->save();
                                }
                            }
                            if(!empty($model->days)) {
                                foreach ($model->days as $key => $value) {
                                    //foreach ($_POST['DidTimeBased'] as $_didTimeBasedModel) {
                                    $startTime = CommonHelper::DtTots(date('Y-m-d '.$_POST['DidTimeBased'][$value]['start_time'].':00'), 'H:i');
                                    $endTime = CommonHelper::DtTots(date('Y-m-d '.$_POST['DidTimeBased'][$value]['end_time'].':00'), 'H:i');
                                    $insertArray[] = [$model->did_id, $_POST['DidTimeBased'][$value]['day'], $startTime, $endTime, $_POST['DidTimeBased'][$value]['after_hour_action_id'], $_POST['DidTimeBased'][$value]['after_hour_value']];
                                    //}
                                }
                                Yii::$app->db->createCommand()->batchInsert('ct_did_time_based', ['did_id', 'day', 'start_time', 'end_time', 'after_hour_action_id', 'after_hour_value'],
                                    $insertArray)->execute();
                            }
                        }
                        Yii::$app->session->setFlash('success', DidManagementModule::t('did', 'created_success'));
                    }

                }
                $transaction->commit();

                return $this->redirect(['index']);
            }
        } catch (Exception $e) {
            $transaction->rollBack();
            Yii::$app->session->setFlash('success', DidManagementModule::t('did', 'something_wrong'));

            return $this->redirect(['index']);
        }

        return $this->render('create',
            [
                'model' => $model,
                'didTimeBasedModel' => $didTimeBasedModel
            ]);
    }

    /**
     * @param $model
     */
    private function saveDids($model, $didTimeBasedModel, $isTimeBased, $days)
    {
        $from = trim($model->did_range_from);
        $to = trim($model->did_range_to);
        $actionId = $model->action_id;
        $actionValue = $model->action_value;
        $description = $model->did_description;
        $holiday = $model->holiday;
//        $fax=$model->fax;

        $length = $to - $from;
        $total_uploaded_numbers = 0;
        $total_faulty_numbers = 0;
        $alreadyExist = 0;
        if ($length != 0) {
            for ($i = $from; $i <= $to; $i++) {
                $avail_code = DidManagement::find()
                    ->where(['did_number' => $i])
                    ->count();

                if ($avail_code >= 1) {
                    $alreadyExist++;
                    continue;
                }

                $model = new DidManagement();
                $model->did_number = (string)$i;
                $model->did_description = $description;
                $model->action_id = $actionId;
                $model->action_value = $actionValue;
//                $model->fax=$fax;
                $model->created_date = date('Y-m-d H:i:s');
                $model->updated_date = date('Y-m-d H:i:s');
                $model->is_time_based = $isTimeBased;
                if ($model->save()) {
                    if($model->is_time_based == 1) {
                        $insertArray = $holidayArray = [];

                        if (!empty($holiday)) {
                            foreach ($holiday as $_holiday) {
                                $holidayArray[] = [$model->did_id, $_holiday];
                            }
                            Yii::$app->db->createCommand()->batchInsert('ct_did_holiday', ['did_id', 'hd_id'],
                                $holidayArray)->execute();
                        }

                        if(!empty($days)) {
                            foreach ($days as $k => $v) {
                                //foreach ($didTimeBasedModel as $_didTimeBasedModel) {
                                $startTime = CommonHelper::DtTots(date('Y-m-d '.$didTimeBasedModel[$v]['start_time'].':00'), 'H:i');
                                $endTime = CommonHelper::DtTots(date('Y-m-d '.$didTimeBasedModel[$v]['end_time'].':00'), 'H:i');
                                $insertArray[] = [$model->did_id, $didTimeBasedModel[$v]['day'], $startTime, $endTime, $didTimeBasedModel[$v]['after_hour_action_id'], $didTimeBasedModel[$v]['after_hour_value']];
                                //}
                            }
                            Yii::$app->db->createCommand()->batchInsert('ct_did_time_based', ['did_id', 'day', 'start_time', 'end_time', 'after_hour_action_id', 'after_hour_value'],
                                $insertArray)->execute();
                        }
                    }
                    $total_uploaded_numbers++;
                } else {
                    $total_faulty_numbers++;
                }
            }
        }

        Yii::$app->session->setFlash('success',
            DidManagementModule::t('did', 'total_uploaded_number') . ' : ' . $total_uploaded_numbers . ", " .
            DidManagementModule::t('did', 'already_exist') . ' : ' . $alreadyExist . ", " .
            DidManagementModule::t('did', 'total_faulty_number') . ' : ' . $total_faulty_numbers);
    }

    /**
     * Updates an existing DidManagement model.
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
        $model->setScenario('update');
        $didTimeBasedModel = DidTimeBased::find()->where(['did_id' => $id])->all();
        $holiday = DidHoliday::find()->where(['did_id' => $id])->asArray()->all();
        $model->holiday = array_column($holiday, 'hd_id');
        $model->days = array_column($model->timebased, 'day');
        if ($model->load(Yii::$app->request->post())) {
            $model->did_status = Yii::$app->request->post('did_status');
            $model->is_time_based = Yii::$app->request->post('is_time_based');
            if ($model->save()) {
                DidTimeBased::deleteAll(['did_id' => $model->did_id]);
                if($model->is_time_based == 1) {
                    DidHoliday::deleteAll(['did_id' => $model->did_id]);
                    if (!empty($model->holiday)) {
                        foreach ($model->holiday as $_holiday) {
                            $didHoliday = new DidHoliday();
                            $didHoliday->did_id = $model->did_id;
                            $didHoliday->hd_id = $_holiday;
                            $didHoliday->save();
                        }
                    }
                    if(!empty($model->days)) {
                        foreach ($model->days as $key => $value) {
                            //foreach ($_POST['DidTimeBased'] as $_didTimeBasedModel) {
                            $startTime = CommonHelper::DtTots(date('Y-m-d '.$_POST['DidTimeBased'][$value]['start_time'].':00'), 'H:i');
                            $endTime = CommonHelper::DtTots(date('Y-m-d '.$_POST['DidTimeBased'][$value]['end_time'].':00'), 'H:i');
                            $insertArray[] = [$model->did_id, $_POST['DidTimeBased'][$value]['day'], $startTime, $endTime, $_POST['DidTimeBased'][$value]['after_hour_action_id'], $_POST['DidTimeBased'][$value]['after_hour_value']];
                            //}
                        }
                        Yii::$app->db->createCommand()->batchInsert('ct_did_time_based', ['did_id', 'day', 'start_time', 'end_time', 'after_hour_action_id', 'after_hour_value'],
                            $insertArray)->execute();
                    }
                }
                Yii::$app->session->setFlash('success', DidManagementModule::t('did', 'updated_success'));
                return $this->redirect(['index']);
            }
        }
        return $this->render('update',
            [
                'model' => $model,
                'didTimeBasedModel' => $didTimeBasedModel
            ]);
    }

    /**
     * Finds the DidManagement model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     *
     * @return DidManagement the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DidManagement::findOne($id)) !== NULL) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Deletes an existing DidManagement model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     *
     * @return Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        DidTimeBased::deleteAll(['did_id' => $id]);
        DidHoliday::deleteAll(['did_id' => $id]);
        Yii::$app->session->setFlash('success', DidManagementModule::t('did', 'deleted_success'));

        return $this->redirect(['index']);
    }

    public function actionChangeAction()
    {
        $action_value = "";

        if (isset($_POST['action_id'])) {
            $action_id = $_POST['action_id'];
            $action_value = (isset($_POST['action_value']) ? $_POST['action_value'] : '');
            /** @var Services $data */
            $data = Services::find()->where(['ser_id' => $action_id])->asArray()->one();
            if (sizeof($data)) {
                $ser_name = $data['ser_name'];
                if ($ser_name == 'EXTENSION') {
                    $data = Extension::find()->select(['em_id AS id', 'CONCAT(em_extension_name," - ", em_extension_number)  AS name'])->asArray()->all();
                } else if ($ser_name == 'IVR' || $ser_name == 'AUDIO TEXT') {
                    $data = AutoAttendantMaster::find()->select(['aam_id AS id', 'aam_name AS name'])->asArray()->all();
                } else if ($ser_name == 'QUEUE') {
                    $data = QueueMaster::find()->select(['qm_id AS id', new \yii\db\Expression("SUBSTRING_INDEX(qm_name, '_', 1) AS name")])->asArray()->all();
                } else if ($ser_name == 'VOICEMAIL') {
                    $data = Extension::find()->select(['ct_extension_master.em_id AS id', 'ct_extension_master.em_extension_name AS name'])
                        ->leftJoin('ct_extension_call_setting as ecs', 'ecs.em_id = ct_extension_master.em_id')
                        ->where(['ecs.ecs_voicemail' => '1'])
                        ->asArray()->all();
                } else if ($ser_name == 'RING GROUP') {
                    $data = RingGroup::find()->select(['rg_id AS id', 'rg_name AS name'])->asArray()->all();
                } else if ($ser_name == 'EXTERNAL') {
                    $data = '';
                } else if ($ser_name == 'CONFERENCE') {
                    $data = ConferenceMaster::find()->select(['cm_id AS id', new \yii\db\Expression("SUBSTRING_INDEX(cm_name, '_', 1) AS name")])->asArray()->all();
                } else if ($ser_name == 'PLAYBACK') {
                    $data = Playback::find()->select(['pb_id AS id', 'pb_name AS name'])->asArray()->all();
                } else if ($ser_name == 'FAX') {
                    $data = Fax::find()->select(['id AS id', 'fax_name AS name'])->asArray()->all();
                } else if ($ser_name == 'CAMPAIGN') {
                    $data = Campaign::find()->select(['cmp_id AS id', 'cmp_name AS name'])->where(['=','cmp_type','Inbound'])->asArray()->all();
                } else {
                    $data = '';
                }
            }
        } else {
            $data = '';
        }

        return $this->renderPartial('change-action',
            [
                'action_value' => $action_value,
                'data' => $data,
            ]);
    }

    /**
     * @return string|Response
     * @throws InvalidArgumentException
     */
    public function actionImport()
    {
        $licenseData = Yii::$app->commonHelper->getLicenseData($_SERVER['HTTP_HOST']);
        if(!empty($licenseData)) {
            $maxDid = $licenseData['maxDID'];
            $totalDid = DidManagement::find()->count();
            if ($totalDid >= $maxDid) {
                Yii::$app->session->setFlash('error', Yii::t('app', 'license_limit_exceed'));
                return $this->redirect(['index']);
            }
        }
        $searchModel = new DidManagementSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        Yii::$app->session->set('querydid', $dataProvider->query);

        $importModel = new DidManagement();

        $importModel->setScenario('import');

        $transaction = Yii::$app->db->beginTransaction();

        try {
            if ($importModel->load(Yii::$app->request->post())) {
                $importModel->importFileUpload = UploadedFile::getInstance($importModel, 'importFileUpload');
                if ($importModel->validate(['importFileUpload'])) {
                    $this->saveDidCSVData($importModel);

                    $transaction->commit();

                    return $this->redirect(['index']);
                }
            }
        } catch (Exception $e) {
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', DidManagementModule::t('did', 'something_wrong'));

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
    private function saveDidCSVData($importModel)
    {
        $fileExtension = $importModel->importFileUpload->getExtension();
        $tempName = $importModel->importFileUpload->tempName;
        $errors = $importModel->importFileUpload->error;

        /*$actionId = $importModel->action_id;
        $actionValue = $importModel->action_value;*/
        /*$fax = $importModel->fax;*/

        $csvPath = Url::to(Yii::$app->params['tenantStorageFullPath'] . $GLOBALS['tenantID'] . '/csv/did/');

        if (!is_dir($csvPath)) {
            Yii::$app->storageHelper->makeDirAndGivePermission($csvPath);
        }

        $fileName = 'failed_did_' . time() . '.csv';
        $csvPath = $csvPath . $fileName;
        $csvUrl = Yii::$app->homeUrl . 'media/' . $GLOBALS['tenantID'] . '/csv/did/' . $fileName;
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
                                        if (in_array('Number', $headersArray)
                                            && in_array('Description', $headersArray)
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
                $total_uploaded_numbers = 0;
                $total_faulty_numbers = 0;
                $alreadyExist = 0;
                if ($length != 0) {
                    for ($i = 1; $i <= $length; $i++) {
                        $number = $data[$i]['did_number'];
                        $description = $data[$i]['did_description'];
                        //$fax = $data[ $i ]['fax'];


                        $avail_code = DidManagement::find()
                            ->where(['did_number' => $data[$i]['did_number']])
                            ->count();

                        if ($avail_code >= 1) {
                            $alreadyExist++;
                            $dataCsv = array_values($data[$i]);
                            $dataCsv[] = Yii::t('app', 'alreadyExists');
                            fputcsv($handle2, $dataCsv);
                            continue;
                        }

                        $model = new DidManagement();
                        $model->did_number = $number;
                        $model->did_description = $description;
                        /*$model->action_id = $actionId;*/
                        /*$model->fax             = $fax;*/
                        /*$model->action_value = $actionValue;*/
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
                    $msg = '<span style="display: inline-block;">' . '<a href="' . $csvUrl . '" style="color:white;">' . Yii::t('app', 'importFailDid', ['success' => $total_uploaded_numbers, 'fail' => $total_faulty_numbers, 'alreadyExists' => $alreadyExist]) . '</a></span>';
                } elseif ($alreadyExist > 0){
                    $msg = '<span style="display: inline-block;">' . '<a href="' . $csvUrl . '" style="color:white;">' . Yii::t('app', 'importFailDid', ['success' => $total_uploaded_numbers, 'fail' => $total_faulty_numbers, 'alreadyExists' => $alreadyExist]) . '</a></span>';
                } if ($length == 0) {
                    $msg = '<span style="display: inline-block;">' . Yii::t('app', 'Imported Report Is Blank') . '</a></span>';
                }
                if ($length == 0 || $total_faulty_numbers > 0 || $alreadyExist > 0) {
                    Yii::$app->session->setFlash('errorimport', $msg);
                } else {
                    Yii::$app->session->setFlash('successimport', $msg);
                }
            }
        } else {
            Yii::$app->session->setFlash('error', DidManagementModule::t('did', 'invalid_format'));
        }
    }

    /**
     * @return void
     */
    public function actionDownloadSampleFile()
    {
        $model = new DidManagement();

        if ($model->import['fields']) {
            foreach ($model->import['fields'] as $k => $field) {

                $model->displayNames[$k] = $field['displayName'];
                $model->sampleValues[] = $field['sample'];
            }
        }

        $file = implode(',', $model->displayNames) . "\n" . implode(',', $model->sampleValues);

        header("Content-type:text/csv");
        header("Content-Disposition:attachment; filename=Sample-did-file.csv");
        echo $file;
        exit;
    }
}
