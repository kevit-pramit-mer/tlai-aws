<?php

namespace app\modules\ecosmob\autoattendant\controllers;

use app\modules\ecosmob\autoattendant\AutoAttendantModule;
use app\modules\ecosmob\autoattendant\models\AutoAttendantDetail;
use app\modules\ecosmob\autoattendant\models\AutoAttendantKeys;
use app\modules\ecosmob\autoattendant\models\AutoAttendantMaster;
use app\modules\ecosmob\autoattendant\models\AutoAttendantMasterSearch;
use Throwable;
use Yii;
use yii\base\Action;
use yii\db\Exception;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * AutoattendantController implements the CRUD actions for AutoAttendantMaster model.
 */
class AutoattendantController extends Controller
{

    /**
     * @param Action $action
     *
     * @return bool
     * @throws BadRequestHttpException
     */
    public function beforeAction($action)
    {
        /*$all_services = Customer::getAllAssignService(
            Yii::$app->user->identity->user_id
        );*/
        // if ( $all_services['sip_service_id'] == 0 ) {
        if (0) {
            Yii::$app->getSession()->setFlash('info', Yii::t('app', 'not_authorized'), TRUE);
            $this->goHome();
        } else {
            return parent::beforeAction($action);
        }
    }

    /**
     * @inheritdoc
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
                            'settings',
                            'jstree-data',
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
     * Lists all AutoAttendantMaster models.
     *
     * @return string
     * @throws \yii\base\InvalidParamException
     */
    public function actionIndex()
    {
        Yii::$app->session->set(
            'autoat_redirect_to',
            Yii::$app->request->hostInfo . Url::current()
        );

        $searchModel = new AutoAttendantMasterSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render(
            'index',
            [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]
        );
    }

    /**
     * Creates a new AutoAttendantMaster model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     *
     * @return mixed
     * @throws \Exception
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function actionCreate()
    {
        $model = new AutoAttendantMaster();
        $model->aam_max_failures = 2;
        $model->aam_max_timeout = 100;
        try {
        if (Yii::$app->request->post()) {

                $requestData = Yii::$app->request->post('AutoAttendantMaster');

                $autoResult = $this->saveAutoMasterData(
                    $model,
                    $requestData,
                    'CREATE'
                );
                if ($autoResult->save()) {
                    $model->aam_mapped_id = $model->aam_id;
                    $model->update(FALSE, ['aam_mapped_id']);
                    $this->saveDirectExtensionDialData($model);
                    Yii::$app->session->setFlash(
                        "success",
                        AutoAttendantModule::t('autoattendant', "created_success"));
                    return $this->redirect(['index']);
                }
            }
        $model->aam_timeout = (int)($model->aam_timeout / 1000);
        $model->aam_inter_digit_timeout = (int)($model->aam_inter_digit_timeout / 1000);
}catch(Exception $e){
            print_r($e->getMessage());exit;
    }
        return $this->render(
            'create',
            [
                'model' => $model,
            ]
        );
    }

    /**
     * @param $model
     * @param $requestData
     * @param $save_type
     *
     * @return void
     */
    public function saveAutoMasterData($model, $requestData, $save_type)
    {
        $model->aam_name = $requestData['aam_name'];
        $model->aam_extension = $requestData['aam_extension'];


        if ($requestData['aam_greet_long'] == 'NULL'
            || $requestData['aam_greet_long'] == ''
        ) {
            $model->aam_greet_long = NULL;
        } else {
            $model->aam_greet_long = $requestData['aam_greet_long'];
        }

        if ($requestData['aam_greet_short'] == 'NULL'
            || $requestData['aam_greet_short'] == ''
        ) {
            $model->aam_greet_short = NULL;
        } else {
            $model->aam_greet_short = $requestData['aam_greet_short'];
        }

        if ($requestData['aam_invalid_sound'] == 'NULL'
            || $requestData['aam_invalid_sound'] == ''
        ) {
            $model->aam_invalid_sound = NULL;
        } else {
            $model->aam_invalid_sound = $requestData['aam_invalid_sound'];
        }

        if ($requestData['aam_exit_sound'] == 'NULL'
            || $requestData['aam_exit_sound'] == ''
        ) {
            $model->aam_exit_sound = NULL;
        } else {
            $model->aam_exit_sound = $requestData['aam_exit_sound'];
        }

        if ($requestData['aam_failure_prompt'] == 'NULL'
            || $requestData['aam_failure_prompt'] == ''
        ) {
            $model->aam_failure_prompt = NULL;
        } else {
            $model->aam_failure_prompt = $requestData['aam_failure_prompt'];
        }
        $model->aam_timeout_prompt = '';
//        if ($requestData['aam_timeout_prompt'] == 'NULL'
//            || $requestData['aam_timeout_prompt'] == ''
//        ) {
//            $model->aam_timeout_prompt = NULL;
//        } else {
//            $model->aam_timeout_prompt = $requestData['aam_timeout_prompt'];
//        }
        if ($requestData['aam_direct_dial'] == 'NULL'
            || $requestData['aam_direct_dial'] == ''
        ) {
            $model->aam_direct_dial = NULL;
        } else {
            $model->aam_direct_dial = $requestData['aam_direct_dial'];
        }
        if ($requestData['aam_transfer_on_failure'] == 'NULL'
            || $requestData['aam_transfer_on_failure'] == ''
        ) {
            $model->aam_transfer_on_failure = NULL;
        } else {
            $model->aam_transfer_on_failure = $requestData['aam_transfer_on_failure'];
        }

        if ($requestData['aam_transfer_extension_type'] == 'NULL'
            || $requestData['aam_transfer_extension_type'] == ''
        ) {
            $model->aam_transfer_extension_type = NULL;
        } else {
            $model->aam_transfer_extension_type = $requestData['aam_transfer_extension_type'];
        }


        if (!empty($requestData['aam_transfer_extension'])) {
            $model->aam_transfer_extension = $requestData['aam_transfer_extension'];
        } else {
            $model->aam_transfer_extension = NULL;
        }

        if ($requestData['aam_digit_len'] == 'NULL'
            || $requestData['aam_digit_len'] == ''
        ) {
            $model->aam_digit_len = NULL;
        } else {
            $model->aam_digit_len = $requestData['aam_digit_len'];
        }

        if ($requestData['aam_max_failures'] == 'NULL'
            || $requestData['aam_max_failures'] == ''
        ) {
            //            $model->aam_max_failures = null;
        } else {
            $model->aam_max_failures = $requestData['aam_max_failures'];
        }

        if ($requestData['aam_max_timeout'] == 'NULL'
            || $requestData['aam_max_timeout'] == ''
        ) {
            //            $model->aam_max_timeout = null;
        } else {
            $model->aam_max_timeout = $requestData['aam_max_timeout'];
        }

        $model->aam_timeout = !empty($requestData['aam_timeout'])
            ? (int)($requestData['aam_timeout'] * 1000) : 4000;

        $model->aam_inter_digit_timeout
            = !empty($requestData['aam_inter_digit_timeout'])
            ? (int)($requestData['aam_inter_digit_timeout'] * 1000) : 3000;

        $model->aam_language = $requestData['aam_language'];

        switch ($save_type) {
            // Save type $save_type 'Create'.
            case 'CREATE':
                $model->aam_level = '0';
                $model->aam_status = '1';

                return $model;
                break;
            // Save type $save_type 'UPDATE'.
            case 'UPDATE':
                $model->aam_level = '0';
                $model->aam_status = $requestData['aam_status'];

                return $model;
                break;
            // Save type $save_type 'SETTINGS'.
            case 'SETTINGS':
                $model->aam_status = $requestData['aam_status'];

                return $model;
                break;
        }
    }

    /**
     * This method will save the Regular expression in
     * 'auto_attendant_detail' table if 'aam_direct_ext_call' equal to 1 for attribute 'aad_digit'.
     *
     * @param $model
     */
    public function saveDirectExtensionDialData($model)
    {
        if ($model->aam_direct_dial == 1) {
            $autoAttendantDetailModel = new AutoAttendantDetail();
            $autoAttendantDetailModel->aam_id = $model->aam_id;
            $autoAttendantDetailModel->aad_digit = '/^(\d{2,10})$/';
            $autoAttendantDetailModel->aad_action = 'menu-exec-app';
            $autoAttendantDetailModel->aad_action_desc = 'Direct Extension Dial';
            $autoAttendantDetailModel->aad_param = 'CallTechPBX ivr_dial $1';
            $autoAttendantDetailModel->save();
        }
    }

    /**
     * Updates an existing AutoAttendantMaster model.
     * If update is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     *
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if (Yii::$app->request->post()) {

            $requestData = Yii::$app->request->post('AutoAttendantMaster');

            $autoResult = $this->saveAutoMasterData(
                $model,
                $requestData,
                'UPDATE'
            );


            if ($autoResult->save()) {

                AutoAttendantDetail::deleteAll(
                    [
                        'aam_id' => $model->aam_id,
                        'aad_action_desc' => 'Direct Extension Dial',
                    ]
                );

                $this->saveDirectExtensionDialData($model);

                Yii::$app->session->setFlash(
                    "success",
                    AutoAttendantModule::t(
                        'autoattendant',
                        "updated_success"
                    ));

                return $this->redirect(
                    Yii::$app->session->get(
                        'autoat_redirect_to',
                        Url::to(['index'])
                    )
                );

            }
        }
        $model->aam_timeout = (int)($model->aam_timeout / 1000);
        $model->aam_inter_digit_timeout = (int)($model->aam_inter_digit_timeout
            / 1000);

        return $this->render(
            'update',
            [
                'model' => $model,
            ]
        );
    }

    /**
     * Finds the AutoAttendantMaster model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param integer $id
     *
     * @return AutoAttendantMaster the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AutoAttendantMaster::findOne(
                [
                    'aam_id' => $id,
                ]
            )) !== NULL
        ) {
            return $model;
        } else {
            throw new NotFoundHttpException(
                AutoAttendantModule::t('autoattendant', 'something_wrong')
            );
        }
    }

    /**
     * Updates an existing AutoAttendantMaster model.
     * If update is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     *
     * @return string|Response
     * @throws Exception
     * @throws NotFoundHttpException
     * @throws \Exception
     */
    public function actionSettings($id)
    {

        $modelCustom = $this->findModel($id);
        $model = $this->findModel($id);

        $subDetailModel = AutoAttendantDetail::findOne(
            ['aad_param' => $model->aam_name]
        );

        $jsTreeData = $this->jstreeData($id);
        $autoAttendantKeys = AutoAttendantKeys::find()->where(['!=', 'aak_key_name', 'Sub Menu'])->all();

        $allAutoAttendantDetails = AutoAttendantDetail::find()->where(
            ['aam_id' => $id]
        )->all();

        $autoDetail = ArrayHelper::map(
            $allAutoAttendantDetails,
            'aad_digit',
            'aad_action_desc'
        );

        if (!empty($allAutoAttendantDetails)) {
            $resultData = array_map([$this, 'allData'], $allAutoAttendantDetails);

            $allData = [];
            foreach ($resultData as $sub) {
                if(!empty($sub)) {
                    $allData = $allData + $sub;
                }
            }

        } else {
            $allData = '';
        }

        $detailModelError = [];

        if (Yii::$app->request->post()) {
            $requestData = Yii::$app->request->post('AutoAttendantMaster');

            $autoResult = $this->saveAutoMasterData(
                $model,
                $requestData,
                'SETTINGS'
            );

            if ($autoResult->save()) {
                if (isset($modelCustom->aam_name) && Yii::$app->request->post('AutoAttendantMaster')['aam_name']) {
                    if ($modelCustom->aam_name != Yii::$app->request->post('AutoAttendantMaster')['aam_name']) {
                        $temp_all_data = AutoAttendantDetail::find()->where(['aad_param' => $modelCustom->aam_name])->asArray()->all();
                        foreach ($temp_all_data as $temp_all_data_single) {
                            $temp_all_data_single_object = AutoAttendantDetail::findOne(['aad_id' => $temp_all_data_single['aad_id']]);
                            $temp_all_data_single_object->aad_param = trim(Yii::$app->request->post('AutoAttendantMaster')['aam_name']);
                            $temp_all_data_single_object->save();
                        }
                    }
                }


                if ($subDetailModel) {
                    $subDetailModel->aad_param = $model->aam_name;
                    $subDetailModel->update(TRUE, ['aad_param']);
                }
                AutoAttendantDetail::deleteAll(
                    [
                        'aam_id' => $model->aam_id,
                        'aad_action_desc' => 'Direct Extension Dial',
                    ]
                );

                //If Direct Extension Call is 1 then one entry will be store in AutoAttendantDetail Table for attribute 'aad_digit'.
                $this->saveDirectExtensionDialData($model);

                if (Yii::$app->request->post('actions')) {

                    $requestData = Yii::$app->request->post();
                    $result = $this->saveAutoAttendantDetails(
                        $requestData,
                        $model,
                        TRUE
                    );

                    $detailModelValidated = $result['result'];
                    $detailModelError = $result['error'];

                    if ($detailModelValidated) {

                        $transaction = Yii::$app->db->beginTransaction();

                        try {

                            // Delete All Auto Attendant Details Data except Sub menu because it is stored in master table and its dependancy
                            AutoAttendantDetail::deleteAll(
                                'aad_action_desc != \'Direct Extension Dial\' AND aad_action!=\'menu-sub\' AND aam_id = '
                                . $model->aam_id
                            );

                            $this->deleteSubMenuData($requestData);

                            $result = $this->saveAutoAttendantDetails(
                                $requestData,
                                $model,
                                FALSE
                            );

                            $autoAttendantResult = $result['result'];

                            $subMenuError = $result['error'];

                            if ($autoAttendantResult) {

                                $transaction->commit();

                                Yii::$app->session->setFlash(
                                    "success",
                                    AutoAttendantModule::t(
                                        'autoattendant',
                                        "updated_success"
                                    )
                                );

                                return $this->redirect(
                                    Yii::$app->session->get(
                                        'autoat_redirect_to',
                                        Url::to(['index'])
                                    )
                                );

                            } else {
                                $allAutoAttendantDetails
                                    = static::getAllAutoAttendantDetails(
                                    $requestData
                                );

                                if ($subMenuError) {
                                    Yii::$app->session->setFlash(
                                        "error",
                                        AutoAttendantModule::t(
                                            'autoattendant',
                                            'invalid_name'
                                        )
                                    );
                                }

                                // Transaction Rollback
                                $transaction->rollBack();
                                $model->aam_timeout = (int)($model->aam_timeout
                                    / 1000);
                                $model->aam_inter_digit_timeout
                                    = (int)($model->aam_inter_digit_timeout
                                    / 1000);

                                return $this->render(
                                    'settings',
                                    [
                                        'model' => $model,
                                        'autoAttendantKeys' => $autoAttendantKeys,
                                        'detailModelError' => $detailModelError,
                                        'allData' => $allData,
                                        'autoDetail' => $autoDetail,
                                        'allAutoAttendantDetails' => $allAutoAttendantDetails,
                                        'jsTreeData' => $jsTreeData,
                                    ]
                                );
                            }
                        } catch (Exception $exception) {

                            $allAutoAttendantDetails
                                = static::getAllAutoAttendantDetails(
                                $requestData
                            );

                            Yii::$app->session->setFlash(
                                "error",
                                AutoAttendantModule::t(
                                    'autoattendant',
                                    'something_wrong'
                                )
                            );

                            $transaction->rollBack();
                        }

                        $allAutoAttendantDetails
                            = static::getAllAutoAttendantDetails($requestData);
                        $model->aam_timeout = (int)($model->aam_timeout / 1000);
                        $model->aam_inter_digit_timeout
                            = (int)($model->aam_inter_digit_timeout / 1000);

                        return $this->render(
                            'settings',
                            [
                                'model' => $model,
                                'autoAttendantKeys' => $autoAttendantKeys,
                                'detailModelError' => $detailModelError,
                                'allData' => $allData,
                                'autoDetail' => $autoDetail,
                                'allAutoAttendantDetails' => $allAutoAttendantDetails,
                                'jsTreeData' => $jsTreeData,
                            ]
                        );
                    } else {
                        $errorDetail = implode(array_values($detailModelError)[0]);

                        Yii::$app->session->setFlash(
                            "error",
                            AutoAttendantModule::t('autoattendant', $errorDetail)
                        );
                        return $this->redirect(
                            ['settings', 'id' => $id]
                        );
                    }

                    $errorDetail = implode(array_values($detailModelError)[0]);

                    Yii::$app->session->setFlash(
                        "error",
                        AutoAttendantModule::t('autoattendant', $errorDetail)
                    );

                    $allAutoAttendantDetails
                        = $this->getAllAutoAttendantDetails($requestData);
                }

                $allAutoAttendantDetails = static::getAllAutoAttendantDetails(
                    Yii::$app->request->post()
                );
            }
            $allAutoAttendantDetails = static::getAllAutoAttendantDetails(
                Yii::$app->request->post()
            );
        }
        $model->aam_timeout = (int)($model->aam_timeout / 1000);
        $model->aam_inter_digit_timeout = (int)($model->aam_inter_digit_timeout / 1000);

        return $this->render(
            'settings',
            [
                'model' => $model,
                'autoAttendantKeys' => $autoAttendantKeys,
                'detailModelError' => $detailModelError,
                'allData' => $allData,
                'autoDetail' => $autoDetail,
                'allAutoAttendantDetails' => $allAutoAttendantDetails,
                'jsTreeData' => $jsTreeData,
            ]
        );

    }

    /**
     * jstreeData method get all data to display it in treeview format. It will return data in Nested Array format.
     *
     * @param $id
     *
     * @return array
     */
    public function jstreeData($id)
    {
        $autoAttendantParent = AutoAttendantMaster::findOne(['aam_id' => $id]);
        $autoAttendantDetails = AutoAttendantDetail::find()->where(
            ['aam_id' => $id]
        )->andWhere(
            [
                '<>',
                'aad_action_desc',
                'Direct Extension Dial',
            ]
        )->orderBy(['aad_digit' => SORT_ASC])->all();

        $nameParent = "<b><i>" . $autoAttendantParent->aam_name . "</i></b>";

        $actionDetails = [];

        foreach ($autoAttendantDetails as $key => $value) {

            switch ($value->aad_action_desc) {

                case 'Playfile':
                    $actionDetails[$key] = $value->aad_digit . ' - '
                        . AutoAttendantModule::t(
                            'autoattendant',
                            'play_file'
                        ) . ' - ' . "<b><i>" . substr(array_reverse(explode('/', $value->aad_param))[0], 0, strrpos(array_reverse(explode('/', $value->aad_param))[0], '.'))
                     . "</i></b>";
                    break;

                case 'External Number':

                    $extenData = $this->outboundExtensionVoicemailData($value);
                    $actionDetails[$key] = $value->aad_digit . ' - '
                        . AutoAttendantModule::t(
                            'autoattendant',
                            'outbound_destination'
                        ) . ' - ' . "<b><i>" . $extenData . "</i></b>";
                    break;

                case 'Repeat Menu':
                    $actionDetails[$key] = $value->aad_digit . ' - '
                        . AutoAttendantModule::t(
                            'autoattendant',
                            'repeat_menu'
                        );
                    break;

                case 'Deposit to user personal voicemail box':

                    $extenData = $this->outboundExtensionVoicemailData($value);
                    $actionDetails[$key] = $value->aad_digit . ' - '
                        . AutoAttendantModule::t(
                            'autoattendant',
                            'personal_voicemail_box'
                        ) . ' - ' . "<b><i>" . $extenData . "</i></b>";
                    break;


                case 'Deposit to Common Voicemail box':

                    $extenData = $this->outboundExtensionVoicemailData($value);
                    $actionDetails[$key] = $value->aad_digit . ' - '
                        . AutoAttendantModule::t(
                            'autoattendant',
                            'common_voicemail_box'
                        ) . ' - ' . '<b><i>' . $extenData . "</i></b>";
                    break;

                case 'Return to Previous Menu':
                    $actionDetails[$key] = $value->aad_digit . ' - '
                        . AutoAttendantModule::t(
                            'autoattendant',
                            'previous_menu'
                        );
                    break;

                case 'Sub Menu':

                    $subMenuData = $this->subMenuData($value);
                    $subMenu = $value->aad_param;
                    $subMenuName = ' ' . $value->aad_digit . ' - '
                        . AutoAttendantModule::t(
                            'autoattendant',
                            'sub_menu'
                        ) . ' - ' . '<b><i>' . $subMenu . "</i></b>";
                    $actionDetails[$key] = array_merge(
                        [$subMenuName],
                        $subMenuData
                    );
                    break;

                case 'Copy Sub Menu':

                    $subMenuData = $this->subMenuData($value);
                    $subMenu = $value->aad_param;
                    $subMenuName = ' ' . $value->aad_digit . ' - '
                        . AutoAttendantModule::t(
                            'autoattendant',
                            'copy_sub_menu'
                        ) . ' - ' . '<b><i>' . $subMenu . "</i></b>";
                    $actionDetails[$key] = array_merge(
                        [$subMenuName],
                        $subMenuData
                    );
                    break;

                case 'Dial by name':
                    $actionDetails[$key] = $value->aad_digit . ' - '
                        . AutoAttendantModule::t(
                            'autoattendant',
                            'dial_by_name'
                        );
                    break;

                case 'No Action':
                    $actionDetails[$key] = $value->aad_digit . ' - '
                        . AutoAttendantModule::t(
                            'autoattendant',
                            'no_action'
                        );
                    break;

                case 'Disconnect':
                    $actionDetails[$key] = $value->aad_digit . ' - '
                        . AutoAttendantModule::t(
                            'autoattendant',
                            'disconnect'
                        );
                    break;

                case 'Login to voicemail':
                    $actionDetails[$key] = $value->aad_digit . ' - '
                        . AutoAttendantModule::t(
                            'autoattendant',
                            'login_to_voicemail_box'
                        );
                    break;

                case 'Transfer to extension':
                    $name = $this->outboundExtensionVoicemailData($value);
                    $actionDetails[$key] = $value->aad_digit . ' - '
                        . AutoAttendantModule::t(
                            'autoattendant',
                            'transafer_to_extension'
                        ) . ' - ' . "<b><i>" . $name . "</i></b>";
                    break;

                case 'Queues':
                    $name = $this->outboundExtensionVoicemailData($value);
                    $actionDetails[$key] = $value->aad_digit . ' - '
                        . AutoAttendantModule::t(
                            'autoattendant',
                            'Queues'
                        ) . ' - ' . "<b><i>" . $name . "</i></b>";
                    break;

                case 'Ring Groups':
                    $name = $this->outboundExtensionVoicemailData($value);
                    $actionDetails[$key] = $value->aad_digit . ' - '
                        . AutoAttendantModule::t(
                            'autoattendant',
                            'ring_groups'
                        ) . ' - ' . "<b><i>" . $name . "</i></b>";
                    break;

                case 'Conference':
                    $name = $this->outboundExtensionVoicemailData($value);
                    $actionDetails[$key] = $value->aad_digit . ' - '
                        . AutoAttendantModule::t(
                            'autoattendant',
                            'conference'
                        ) . ' - ' . "<b><i>" . $name . "</i></b>";
                    break;

                case 'IVR':
                    $name = $this->outboundExtensionVoicemailData($value);
                    $actionDetails[$key] = $value->aad_digit . ' - '
                        . AutoAttendantModule::t(
                            'autoattendant',
                            'audio_text'
                        ) . ' - ' . "<b><i>" . $name . "</i></b>";
                    break;
                case 'Voicemail':
                    $name = $this->outboundExtensionVoicemailData($value);
                    $actionDetails[$key] = $value->aad_digit . ' - '
                        . AutoAttendantModule::t(
                            'autoattendant',
                            'voicemail'
                        ) . ' - ' . "<b><i>" . $name . "</i></b>";
                    break;
            }
        }

        $parentData = array_merge([$nameParent], [$actionDetails]);

        return $parentData;
    }

    /**
     * Get Outbound Destination Value, Extension Value OR Voicemail Value base on $value and return it in string format.
     *
     * @param $value
     *
     * @return string
     */
    public function outboundExtensionVoicemailData($value)
    {

        $arrayData = explode(' ', $value->aad_param);
        $aak_id = AutoAttendantKeys::getIdByDesc($value->aad_action_desc);

        $autoAttendantKeyParam = AutoAttendantKeys::findOne(
            ['aak_id' => $aak_id]
        )->aak_key_param_tpl;

        $arrayOriginal = explode(' ', $autoAttendantKeyParam);

        $arrayDiff = array_diff($arrayData, $arrayOriginal);
        $extenData = implode($arrayDiff);

        return $extenData;
    }

    /**
     * Get Nested SubMenu Data and return it in array Format.
     *
     * @param $value
     *
     * @return array
     */
    private function subMenuData($value)
    {
        $subMenuName = $value->aad_param;
        $submenuId = AutoAttendantMaster::getIdByName($subMenuName);
        $autoDetailData = AutoAttendantDetail::getAutoSubMenuData($submenuId);

        $result = [];
        foreach ($autoDetailData as $value) {
            $result = $this->jstreeData($value->aam_id);
        }

        return $result;
    }

    /**
     * Validate and Save AutoAttendant Details.
     *
     * @param array $requestData
     * @param AutoAttendantMaster $model
     * @param bool $only_validate
     *
     * @return array
     * @throws \Exception
     */
    public function saveAutoAttendantDetails(
        $requestData,
        $model,
        $only_validate
    )
    {
        $detailError = [];

        $flag = TRUE;

        if ($only_validate === FALSE) {
            Yii::$app->db->createCommand()->delete('auto_attendant_detail', ['aam_id' => $model->aam_id, 'aad_action' => 'menu-sub'])->execute();
        }
        foreach ($requestData['actions'] as $key => $value) {
            if ($value) {
                if ($only_validate === TRUE) {
                    if ($key == 10) {
                        $digit = '*';
                    } else if ($key == 11) {
                        $digit = '#';
                    } else {
                        $digit = (string)$key;
                    }
                    switch ($value) {

                        case 'Playfile':
                            $audioData = $requestData['audio'];
                            $detailDataModel = $this->saveAutoDetailData(
                                $model,
                                $digit,
                                $value
                            );
                            $detailDataModel->aad_param = $audioData[$key];

                            if (!$detailDataModel->validate()) {
                                $detailError = $detailDataModel->getErrors();
                                if ($flag != FALSE) {
                                    $flag = FALSE;
                                }
                            }
                            break;
                        case 'Transfer to extension':

                            $audioData = $requestData['transfer_extension'];

                            $aak_id = AutoAttendantKeys::getIdByDesc($value);

                            $autoAttendantKeyParam = AutoAttendantKeys::findOne(
                                ['aak_id' => $aak_id]
                            )->aak_key_param_tpl;

                            $audioData[$key] = str_replace("(", "", $audioData[$key]);
                            $audioData[$key] = str_replace(")", "", $audioData[$key]);
                            $audioData[$key] = str_replace("-", "", $audioData[$key]);
                            $audioData[$key] = str_replace(" ", "", $audioData[$key]);


                            $autoParamValue = str_replace(
                                'EXTENSION_NUMBER',
                                $audioData[$key],
                                $autoAttendantKeyParam
                            );

                            $detailDataModel = $this->saveAutoDetailData(
                                $model,
                                $digit,
                                $value
                            );
                            $detailDataModel->aad_param = ($audioData[$key]) ? $autoParamValue : NULL;

                            if (!$detailDataModel->validate()) {
                                $detailError = $detailDataModel->getErrors();
                                if ($flag != FALSE) {
                                    $flag = FALSE;
                                }
                            }
                            break;
                        case 'Queues':
                            $audioData = $requestData['queues'];

                            $aak_id = AutoAttendantKeys::getIdByDesc($value);
                            $autoAttendantKeyParam = AutoAttendantKeys::findOne(
                                ['aak_id' => $aak_id]
                            )->aak_key_param_tpl;

                            $audioData[$key] = str_replace("(", "", $audioData[$key]);
                            $audioData[$key] = str_replace(")", "", $audioData[$key]);
                            $audioData[$key] = str_replace("-", "", $audioData[$key]);
                            $audioData[$key] = str_replace(" ", "", $audioData[$key]);


                            $autoParamValue = str_replace(
                                'EXTENSION_NUMBER',
                                $audioData[$key],
                                $autoAttendantKeyParam
                            );

                            $detailDataModel = $this->saveAutoDetailData(
                                $model,
                                $digit,
                                $value
                            );
                            $detailDataModel->aad_param = ($audioData[$key]) ? $autoParamValue : NULL;

                            if (!$detailDataModel->validate()) {
                                $detailError = $detailDataModel->getErrors();
                                if ($flag != FALSE) {
                                    $flag = FALSE;
                                }
                            }
                            break;
                        case 'Ring Groups':
                            $audioData = $requestData['ring_groups'];

                            $aak_id = AutoAttendantKeys::getIdByDesc($value);

                            $autoAttendantKeyParam = AutoAttendantKeys::findOne(
                                ['aak_id' => $aak_id]
                            )->aak_key_param_tpl;

                            $audioData[$key] = str_replace("(", "", $audioData[$key]);
                            $audioData[$key] = str_replace(")", "", $audioData[$key]);
                            $audioData[$key] = str_replace("-", "", $audioData[$key]);
                            $audioData[$key] = str_replace(" ", "", $audioData[$key]);


                            $autoParamValue = str_replace(
                                'EXTENSION_NUMBER',
                                $audioData[$key],
                                $autoAttendantKeyParam
                            );

                            $detailDataModel = $this->saveAutoDetailData(
                                $model,
                                $digit,
                                $value
                            );
                            $detailDataModel->aad_param = ($audioData[$key]) ? $autoParamValue : NULL;

                            if (!$detailDataModel->validate()) {
                                $detailError = $detailDataModel->getErrors();
                                if ($flag != FALSE) {
                                    $flag = FALSE;
                                }
                            }

                            break;
                        case 'Conference':
                            $audioData = $requestData['conference'];

                            $aak_id = AutoAttendantKeys::getIdByDesc($value);

                            $autoAttendantKeyParam = AutoAttendantKeys::findOne(
                                ['aak_id' => $aak_id]
                            )->aak_key_param_tpl;

                            $audioData[$key] = str_replace("(", "", $audioData[$key]);
                            $audioData[$key] = str_replace(")", "", $audioData[$key]);
                            $audioData[$key] = str_replace("-", "", $audioData[$key]);
                            $audioData[$key] = str_replace(" ", "", $audioData[$key]);


                            $autoParamValue = str_replace(
                                'EXTENSION_NUMBER',
                                $audioData[$key],
                                $autoAttendantKeyParam
                            );

                            $detailDataModel = $this->saveAutoDetailData(
                                $model,
                                $digit,
                                $value
                            );
                            $detailDataModel->aad_param = ($audioData[$key]) ? $autoParamValue : NULL;

                            if (!$detailDataModel->validate()) {
                                $detailError = $detailDataModel->getErrors();
                                if ($flag != FALSE) {
                                    $flag = FALSE;
                                }
                            }

                            break;

                        case 'IVR':
                            $audioData = $requestData['audio_text'];

                            $aak_id = AutoAttendantKeys::getIdByDesc($value);
                            $autoAttendantKeyParam = AutoAttendantKeys::findOne(
                                ['aak_id' => $aak_id]
                            )->aak_key_param_tpl;

                            $audioData[$key] = str_replace("(", "", $audioData[$key]);
                            $audioData[$key] = str_replace(")", "", $audioData[$key]);
                            $audioData[$key] = str_replace("-", "", $audioData[$key]);
                            $audioData[$key] = str_replace(" ", "", $audioData[$key]);


                            $autoParamValue = str_replace(
                                'EXTENSION_NUMBER',
                                $audioData[$key],
                                $autoAttendantKeyParam
                            );

                            $detailDataModel = $this->saveAutoDetailData(
                                $model,
                                $digit,
                                $value
                            );
                            $detailDataModel->aad_param = ($audioData[$key]) ? $autoParamValue : NULL;

                            if (!$detailDataModel->validate()) {
                                $detailError = $detailDataModel->getErrors();
                                if ($flag != FALSE) {
                                    $flag = FALSE;
                                }
                            }

                            break;

                        //AutoAttendant Key : External Number
                        case 'External Number':

                            $outboundData = $requestData['outbound_extension'];

                            $aak_id = AutoAttendantKeys::getIdByDesc($value);

                            $autoAttendantKeyParam = AutoAttendantKeys::findOne(
                                ['aak_id' => $aak_id]
                            )->aak_key_param_tpl;

                            $outboundData[$key] = str_replace("(", "", $outboundData[$key]);
                            $outboundData[$key] = str_replace(")", "", $outboundData[$key]);
                            $outboundData[$key] = str_replace("-", "", $outboundData[$key]);
                            $outboundData[$key] = str_replace(" ", "", $outboundData[$key]);


                            $autoParamValue = str_replace(
                                'EXTENSION_NUMBER',
                                $outboundData[$key],
                                $autoAttendantKeyParam
                            );

                            $detailDataModel = $this->saveAutoDetailData(
                                $model,
                                $digit,
                                $value
                            );
                            $detailDataModel->aad_param = ($outboundData[$key])
                                ? $autoParamValue : NULL;

                            if (!$detailDataModel->validate()) {
                                $detailError = $detailDataModel->getErrors();
                                if ($flag != FALSE) {
                                    $flag = FALSE;
                                }
                            }
                            break;

                        //AutoAttendant Key : Sub Menu
                        case 'Sub Menu':

                            $subMenuData = $requestData['submenu'];

                            $subMenuName = $subMenuData[$key];
                            $detailDataModel = $this->saveAutoDetailData(
                                $model,
                                $digit,
                                $value
                            );
                            $detailDataModel->aad_param = $subMenuData[$key];

                            if (!$detailDataModel->validate()) {
                                $detailError = $detailDataModel->getErrors();
                                if ($flag != FALSE) {
                                    $flag = FALSE;
                                }
                            }

                            if ($subMenuName == $model->aam_name) {
                                $detailDataModel->addError(
                                    'aad_param',
                                    AutoAttendantModule::t(
                                        'autoattendant',
                                        'sub_menu_name_taken'
                                    )
                                );

                                $detailError = $detailDataModel->getErrors();
                                $flag = FALSE;
                            }
                            break;
                        //AutoAttendant Key : Copy Sub Menu
                        case 'Copy Sub Menu':

                            $value = 'Sub Menu';

                            $subMenuData = $requestData['copy_sub_menu'];

                            $autoAttendantModel = AutoAttendantMaster::find()->where(['aam_id' => $subMenuData[$key]])->asArray()->one();

                            if ($autoAttendantModel['aam_name'] != '')
                                $subMenuName = $autoAttendantModel['aam_name'] . '_copy';
                            else
                                $subMenuName = '';

                            $detailDataModel = $this->saveAutoDetailData(
                                $model,
                                $digit,
                                $value
                            );

                            $detailDataModel->aad_param = $subMenuName;

                            if (!$detailDataModel->validate()) {
                                $detailError = $detailDataModel->getErrors();
                                if ($flag != FALSE) {
                                    $flag = FALSE;
                                }
                            }
                            if ($subMenuName == $model->aam_name) {
                                $detailDataModel->addError(
                                    'aad_param',
                                    AutoAttendantModule::t(
                                        'autoattendant',
                                        'sub_menu_name_taken'
                                    )
                                );

                                $detailError = $detailDataModel->getErrors();
                                $flag = FALSE;
                            }
                            break;

                        case 'Voicemail':
                            $audioData = $requestData['voicemail'];

                            $aak_id = AutoAttendantKeys::getIdByDesc($value);
                            $autoAttendantKeyParam = AutoAttendantKeys::findOne(
                                ['aak_id' => $aak_id]
                            )->aak_key_param_tpl;

                            $audioData[$key] = str_replace("(", "", $audioData[$key]);
                            $audioData[$key] = str_replace(")", "", $audioData[$key]);
                            $audioData[$key] = str_replace("-", "", $audioData[$key]);
                            $audioData[$key] = str_replace(" ", "", $audioData[$key]);


                            $autoParamValue = str_replace(
                                'EXTENSION_NUMBER',
                                $audioData[$key],
                                $autoAttendantKeyParam
                            );

                            $detailDataModel = $this->saveAutoDetailData(
                                $model,
                                $digit,
                                $value
                            );
                            $detailDataModel->aad_param = ($audioData[$key]) ? $autoParamValue : NULL;

                            if (!$detailDataModel->validate()) {
                                $detailError = $detailDataModel->getErrors();
                                if ($flag != FALSE) {
                                    $flag = FALSE;
                                }
                            }

                            break;

                    }
                } else {
                    if ($key == 10) {
                        $digit = '*';
                    } else if ($key == 11) {
                        $digit = '#';
                    } else {
                        $digit = (string)$key;
                    }
                    switch ($value) {

                        case 'Playfile':

                            $audioData = $requestData['audio'];
                            $detailDataModel = $this->saveAutoDetailData(
                                $model,
                                $digit,
                                $value
                            );
                            $detailDataModel->aad_param = $audioData[$key];


                            if (!$detailDataModel->save()) {
                                $detailError = $detailDataModel->getErrors();
                                if ($flag != FALSE) {
                                    $flag = FALSE;
                                }
                            }
                            break;

                        case 'Transfer to extension':
                            $audioData = $requestData['transfer_extension'];

                            $aak_id = AutoAttendantKeys::getIdByDesc($value);
                            $autoAttendantKeyParam = AutoAttendantKeys::findOne(
                                ['aak_id' => $aak_id]
                            )->aak_key_param_tpl;

                            $audioData[$key] = str_replace("(", "", $audioData[$key]);
                            $audioData[$key] = str_replace(")", "", $audioData[$key]);
                            $audioData[$key] = str_replace("-", "", $audioData[$key]);
                            $audioData[$key] = str_replace(" ", "", $audioData[$key]);

                            $autoParamValue = str_replace(
                                'EXTENSION_NUMBER',
                                $audioData[$key],
                                $autoAttendantKeyParam
                            );


                            $detailDataModel = $this->saveAutoDetailData(
                                $model,
                                $digit,
                                $value
                            );

                            $detailDataModel->aad_param = ($audioData[$key]) ? $autoParamValue : NULL;

                            if (!$detailDataModel->save()) {
                                $detailError = $detailDataModel->getErrors();
                                if ($flag != FALSE) {
                                    $flag = FALSE;
                                }
                            }
                            break;

                        case 'Queues':
                            $audioData = $requestData['queues'];

                            $aak_id = AutoAttendantKeys::getIdByDesc($value);
                            $autoAttendantKeyParam = AutoAttendantKeys::findOne(
                                ['aak_id' => $aak_id]
                            )->aak_key_param_tpl;

                            $audioData[$key] = str_replace("(", "", $audioData[$key]);
                            $audioData[$key] = str_replace(")", "", $audioData[$key]);
                            $audioData[$key] = str_replace("-", "", $audioData[$key]);
                            $audioData[$key] = str_replace(" ", "", $audioData[$key]);

                            $autoParamValue = str_replace(
                                'EXTENSION_NUMBER',
                                $audioData[$key],
                                $autoAttendantKeyParam
                            );

                            $detailDataModel = $this->saveAutoDetailData(
                                $model,
                                $digit,
                                $value
                            );
                            $detailDataModel->aad_param = ($audioData[$key]) ? $autoParamValue : NULL;

                            if (!$detailDataModel->save()) {
                                $detailError = $detailDataModel->getErrors();
                                if ($flag != FALSE) {
                                    $flag = FALSE;
                                }
                            }
                            break;

                        case 'Ring Groups':
                            $audioData = $requestData['ring_groups'];


                            $aak_id = AutoAttendantKeys::getIdByDesc($value);
                            $autoAttendantKeyParam = AutoAttendantKeys::findOne(
                                ['aak_id' => $aak_id]
                            )->aak_key_param_tpl;

                            $audioData[$key] = str_replace("(", "", $audioData[$key]);
                            $audioData[$key] = str_replace(")", "", $audioData[$key]);
                            $audioData[$key] = str_replace("-", "", $audioData[$key]);
                            $audioData[$key] = str_replace(" ", "", $audioData[$key]);

                            $autoParamValue = str_replace(
                                'EXTENSION_NUMBER',
                                $audioData[$key],
                                $autoAttendantKeyParam
                            );

                            $detailDataModel = $this->saveAutoDetailData(
                                $model,
                                $digit,
                                $value
                            );
                            $detailDataModel->aad_param = ($audioData[$key]) ? $autoParamValue : NULL;


                            if (!$detailDataModel->save()) {
                                $detailError = $detailDataModel->getErrors();
                                if ($flag != FALSE) {
                                    $flag = FALSE;
                                }
                            }
                            break;
                        case 'Conference':
                            $audioData = $requestData['conference'];

                            $aak_id = AutoAttendantKeys::getIdByDesc($value);
                            $autoAttendantKeyParam = AutoAttendantKeys::findOne(
                                ['aak_id' => $aak_id]
                            )->aak_key_param_tpl;

                            $audioData[$key] = str_replace("(", "", $audioData[$key]);
                            $audioData[$key] = str_replace(")", "", $audioData[$key]);
                            $audioData[$key] = str_replace("-", "", $audioData[$key]);
                            $audioData[$key] = str_replace(" ", "", $audioData[$key]);

                            $autoParamValue = str_replace(
                                'EXTENSION_NUMBER',
                                $audioData[$key],
                                $autoAttendantKeyParam
                            );

                            $detailDataModel = $this->saveAutoDetailData(
                                $model,
                                $digit,
                                $value
                            );
                            $detailDataModel->aad_param = ($audioData[$key]) ? $autoParamValue : NULL;

                            if (!$detailDataModel->save()) {
                                $detailError = $detailDataModel->getErrors();
                                if ($flag != FALSE) {
                                    $flag = FALSE;
                                }
                            }
                            break;
                        case 'IVR':
                            $audioData = $requestData['audio_text'];

                            $aak_id = AutoAttendantKeys::getIdByDesc($value);
                            $autoAttendantKeyParam = AutoAttendantKeys::findOne(
                                ['aak_id' => $aak_id]
                            )->aak_key_param_tpl;

                            $audioData[$key] = str_replace("(", "", $audioData[$key]);
                            $audioData[$key] = str_replace(")", "", $audioData[$key]);
                            $audioData[$key] = str_replace("-", "", $audioData[$key]);
                            $audioData[$key] = str_replace(" ", "", $audioData[$key]);

                            $autoParamValue = str_replace(
                                'EXTENSION_NUMBER',
                                $audioData[$key],
                                $autoAttendantKeyParam
                            );


                            $detailDataModel = $this->saveAutoDetailData(
                                $model,
                                $digit,
                                $value
                            );
                            $detailDataModel->aad_param = ($audioData[$key]) ? $autoParamValue : NULL;


                            if (!$detailDataModel->save()) {
                                $detailError = $detailDataModel->getErrors();
                                if ($flag != FALSE) {
                                    $flag = FALSE;
                                }
                            }
                            break;

                        case 'Voicemail':
                            $audioData = $requestData['voicemail'];

                            $aak_id = AutoAttendantKeys::getIdByDesc($value);
                            $autoAttendantKeyParam = AutoAttendantKeys::findOne(
                                ['aak_id' => $aak_id]
                            )->aak_key_param_tpl;

                            $audioData[$key] = str_replace("(", "", $audioData[$key]);
                            $audioData[$key] = str_replace(")", "", $audioData[$key]);
                            $audioData[$key] = str_replace("-", "", $audioData[$key]);
                            $audioData[$key] = str_replace(" ", "", $audioData[$key]);

                            $autoParamValue = str_replace(
                                'EXTENSION_NUMBER',
                                $audioData[$key],
                                $autoAttendantKeyParam
                            );


                            $detailDataModel = $this->saveAutoDetailData(
                                $model,
                                $digit,
                                $value
                            );
                            $detailDataModel->aad_param = ($audioData[$key]) ? $autoParamValue : NULL;


                            if (!$detailDataModel->save()) {
                                $detailError = $detailDataModel->getErrors();
                                if ($flag != FALSE) {
                                    $flag = FALSE;
                                }
                            }
                            break;

                        case 'External Number':
                            $outboundData = $requestData['outbound_extension'];

                            $aak_id = AutoAttendantKeys::getIdByDesc($value);
                            $autoAttendantKeyParam = AutoAttendantKeys::findOne(
                                ['aak_id' => $aak_id]
                            )->aak_key_param_tpl;

                            $outboundData[$key] = str_replace("(", "", $outboundData[$key]);
                            $outboundData[$key] = str_replace(")", "", $outboundData[$key]);
                            $outboundData[$key] = str_replace("-", "", $outboundData[$key]);
                            $outboundData[$key] = str_replace(" ", "", $outboundData[$key]);

                            $autoParamValue = str_replace(
                                'EXTENSION_NUMBER',
                                $outboundData[$key],
                                $autoAttendantKeyParam
                            );

                            $detailDataModel = $this->saveAutoDetailData(
                                $model,
                                $digit,
                                $value
                            );

                            $detailDataModel->aad_param = ($outboundData[$key]) ? $autoParamValue : NULL;

                            if (!$detailDataModel->save()) {
                                $detailError = $detailDataModel->getErrors();
                                if ($flag != FALSE) {
                                    $flag = FALSE;
                                }
                            }

                            break;

                        case 'Repeat Menu':
                        case 'Return to Previous Menu':
                        case 'No Action':

                            $detailDataModel = $this->saveAutoDetailData(
                                $model,
                                $digit,
                                $value
                            );

                            $detailDataModel->aad_param = NULL;

                            if (!$detailDataModel->save()) {
                                $detailError = $detailDataModel->getErrors();
                                if ($flag != FALSE) {
                                    $flag = FALSE;
                                }
                            }

                            break;

                        case 'Sub Menu':

                            $subMenuData = $requestData['submenu'];

                            $subMenuvalue = $subMenuData[$key];

                            $autoDetail = AutoAttendantDetail::find()->where(
                                ['aam_id' => $model->aam_id]
                            )->andWhere(['aad_action' => 'menu-sub'])->andWhere(
                                [
                                    '<>',
                                    'aad_action_desc',
                                    'Direct Extension Dial',
                                ]
                            )->all();

                            $autoDetailDigit = [];
                            $autoDetailName = [];
                            foreach ($autoDetail as $key1 => $value1) {
                                $autoDetailDigit[] = isset($autoDetail)
                                    ? $value1->aad_digit : NULL;
                                $autoDetailName[] = isset($autoDetail)
                                    ? $value1->aad_param : NULL;
                            }

                            $subMenuModel = AutoAttendantMaster::getAllSubMenuData(
                                $subMenuvalue
                            );

                            $subMenuModelName = isset($subMenuModel->aam_name)
                                ? $subMenuModel->aam_name : '';

                            if ($subMenuModelName == $subMenuvalue) {

                                $subMenuModel->aam_name = $subMenuvalue;
                                $modelSaved = $subMenuModel->save();

                                $HdetailDataModelCustom = AutoAttendantDetail::find()->where(['aam_id' => $model->aam_id])->andWhere(['aad_digit' => $digit])->one();
                                if ($HdetailDataModelCustom) {
                                    $HdetailDataModelCustom->aad_action = 'menu-sub';
                                    $HdetailDataModelCustom->aad_action_desc = "Sub Menu";
                                    $HdetailDataModelCustom->aad_param = $subMenuvalue;
                                    if (!$HdetailDataModelCustom->save()) {
                                        $flag = FALSE;
                                        break;
                                    }
                                } else {

                                    Yii::$app->db->createCommand()->delete('auto_attendant_detail', ['aam_id' => $model->aam_id, 'aad_digit' => $digit])->execute();
                                    $HdetailDataModelCustomNew = new AutoAttendantDetail();
                                    $HdetailDataModelCustomNew->aam_id = $model->aam_id;
                                    $HdetailDataModelCustomNew->aad_digit = $digit;
                                    $HdetailDataModelCustomNew->aad_action = 'menu-sub';
                                    $HdetailDataModelCustomNew->aad_action_desc = "Sub Menu";
                                    $HdetailDataModelCustomNew->aad_param = $subMenuvalue;

                                    if (!$HdetailDataModelCustomNew->save()) {
                                        $flag = FALSE;
                                        break;
                                    }
                                }

                            } else {
                                if (!empty($autoDetailDigit)
                                    && in_array(
                                        $digit,
                                        $autoDetailDigit
                                    )
                                ) {

                                    $index = array_search($digit, $autoDetailDigit);
                                    $subMenuUpdateModel
                                        = AutoAttendantMaster::findOne(
                                        ['aam_name' => $autoDetailName[$index]]
                                    );

                                    if (!$subMenuUpdateModel) {
                                        $flag = FALSE;
                                        break;
                                    }

                                    $subMenuUpdateModel->aam_name = $subMenuvalue;
                                    $subMenuUpdateModelValidated
                                        = $subMenuUpdateModel->validate(
                                        ['aam_name']
                                    );
                                    if ($subMenuUpdateModelValidated) {
                                        $subMenuUpdateModel->update(
                                            FALSE,
                                            ['aam_name']
                                        );
                                    } else {
                                        $detailError
                                            = $subMenuUpdateModel->getErrors();
                                        if ($flag != FALSE) {
                                            $flag = FALSE;
                                            break;
                                        }
                                    }

                                    $subMenuDetailUpdateModel
                                        = AutoAttendantDetail::findOne(
                                        [
                                            'aam_id' => $model->aam_id,
                                            'aad_digit' => $digit,
                                        ]
                                    );

                                    $subMenuDetailUpdateModel->aad_param
                                        = $subMenuvalue;
                                    $subMenuDetailUpdateModelValidated
                                        = $subMenuDetailUpdateModel->validate(
                                        ['aad_param']
                                    );

                                    if ($subMenuDetailUpdateModelValidated) {
                                        $subMenuDetailUpdateModel->update(
                                            FALSE,
                                            ['aad_param']
                                        );
                                    } else {
                                        $detailError
                                            = $subMenuDetailUpdateModel->getErrors();
                                        if ($flag != FALSE) {
                                            $flag = FALSE;
                                            break;
                                        }
                                    }
                                } else {

                                    $masterMenuData = AutoAttendantMaster::findOne(
                                        $model->aam_id
                                    );

                                    $autoAttendantSubMenuModel
                                        = new AutoAttendantMaster();
                                    $autoAttendantSubMenuModel->setScenario(
                                        'subMenuCreate'
                                    );

                                    $autoAttendantSubMenuModel->aam_name
                                        = $subMenuvalue;
                                    $autoAttendantSubMenuModel->aam_greet_long
                                        = $model->aam_greet_long;
                                    $autoAttendantSubMenuModel->aam_greet_short
                                        = NULL;
                                    $autoAttendantSubMenuModel->aam_invalid_sound
                                        = NULL;
                                    $autoAttendantSubMenuModel->aam_exit_sound
                                        = NULL;
                                    $autoAttendantSubMenuModel->aam_failure_prompt
                                        = NULL;
                                    $autoAttendantSubMenuModel->aam_timeout_prompt
                                        = NULL;
                                    $autoAttendantSubMenuModel->aam_direct_dial
                                        = NULL;
                                    $autoAttendantSubMenuModel->aam_transfer_on_failure
                                        = NULL;
                                    $autoAttendantSubMenuModel->aam_digit_len
                                        = NULL;
                                    $autoAttendantSubMenuModel->aam_mapped_id
                                        = $model->aam_id;
                                    $autoAttendantSubMenuModel->aam_level
                                        = $masterMenuData->aam_level + 1;
                                    $autoAttendantSubMenuModel->aam_status = '1';

                                    $modelSaved = $autoAttendantSubMenuModel->save();

                                    if ($modelSaved) {


                                        $aak_id = AutoAttendantKeys::getIdByDesc(
                                            $value
                                        );

                                        /** @var AutoAttendantKeys $autoAttendantKeyCode */
                                        $autoAttendantKeyCode
                                            = AutoAttendantKeys::findOne(
                                            ['aak_id' => $aak_id]
                                        )->aak_key_code;

                                        $detailAutoAttendant
                                            = new AutoAttendantDetail();
                                        $detailAutoAttendant->aam_id
                                            = $model->aam_id;
                                        $detailAutoAttendant->aad_digit = $digit;
                                        $detailAutoAttendant->aad_action
                                            = $autoAttendantKeyCode;
                                        $detailAutoAttendant->aad_action_desc
                                            = $value;
                                        $detailAutoAttendant->aad_param
                                            = $subMenuvalue;

                                        if (!$detailAutoAttendant->save()) {
                                            $detailError
                                                = $detailAutoAttendant->getErrors();
                                            if ($flag != FALSE) {
                                                $flag = FALSE;
                                            }
                                        } else {
                                            break;
                                        }
                                    } else {
                                        $autoAttendantSubMenuModel->addError(
                                            'aad_param',
                                            AutoAttendantModule::t(
                                                'autoattendant',
                                                'sub_menu_already_taken'
                                            )
                                        );
                                        $detailError
                                            = $autoAttendantSubMenuModel->getErrors();
                                        if ($flag != FALSE) {
                                            $flag = FALSE;
                                        }
                                    }
                                }
                            }

                            break;

                        case 'Copy Sub Menu':

                            $value = 'Sub Menu';
                            $subMenuData = $requestData['copy_sub_menu'];
                            $originalSubMenu = $subMenuData[$key];

                            $autoAttendantModel = AutoAttendantMaster::find()->where(['aam_id' => $originalSubMenu])->asArray()->one();

                            $subMenuvalue = $autoAttendantModel['aam_name'] . '_copy';

                            $autoAttendantData = 1;
                            while ($autoAttendantData) {
                                $autoAttendantData = AutoAttendantMaster::find()->where(['aam_name' => $subMenuvalue])->count();
                                if ($autoAttendantData) {
                                    $subMenuvalue = $subMenuvalue . '_copy';
                                }
                            }


                            $autoDetail = AutoAttendantDetail::find()->where(
                                ['aam_id' => $model->aam_id]
                            )->andWhere(['aad_action' => 'menu-sub'])->andWhere(
                                [
                                    '<>',
                                    'aad_action_desc',
                                    'Direct Extension Dial',
                                ]
                            )->all();

                            $autoDetailDigit = [];
                            $autoDetailName = [];
                            foreach ($autoDetail as $value1) {
                                $autoDetailDigit[] = isset($autoDetail)
                                    ? $value1->aad_digit : NULL;
                                $autoDetailName[] = isset($autoDetail)
                                    ? $value1->aad_param : NULL;
                            }

                            $subMenuModel = AutoAttendantMaster::getAllSubMenuData(
                                $subMenuvalue
                            );

                            $subMenuModelName = isset($subMenuModel->aam_name)
                                ? $subMenuModel->aam_name : '';

                            if ($subMenuModelName == $subMenuvalue) {

                                $subMenuModel->aam_name = $subMenuvalue;
                                $modelSaved = $subMenuModel->save();
                                $subMenuDetail = AutoAttendantDetail::findOne(
                                    ['aad_param' => $subMenuvalue]
                                );
                                if ($subMenuDetail) {
                                    $subMenuDetail->aad_digit = $digit;
                                    $subMenuDetail->save(FALSE, ['aad_digit']);
                                }
                            } else {
                                if (!empty($autoDetailDigit)
                                    && in_array(
                                        $digit,
                                        $autoDetailDigit
                                    )
                                ) {

                                    $index = array_search($digit, $autoDetailDigit);
                                    $subMenuUpdateModel
                                        = AutoAttendantMaster::findOne(
                                        ['aam_name' => $autoDetailName[$index]]
                                    );

                                    $subMenuUpdateModel->aam_name = $subMenuvalue;
                                    $subMenuUpdateModelValidated
                                        = $subMenuUpdateModel->validate(
                                        ['aam_name']
                                    );
                                    if ($subMenuUpdateModelValidated) {
                                        $subMenuUpdateModel->update(
                                            FALSE,
                                            ['aam_name']
                                        );
                                    } else {
                                        $detailError
                                            = $subMenuUpdateModel->getErrors();
                                        if ($flag != FALSE) {
                                            $flag = FALSE;
                                            break;
                                        }
                                    }

                                    $subMenuDetailUpdateModel
                                        = AutoAttendantDetail::findOne(
                                        [
                                            'aam_id' => $model->aam_id,
                                            'aad_digit' => $digit,
                                        ]
                                    );

                                    $subMenuDetailUpdateModel->aad_param
                                        = $subMenuvalue;
                                    $subMenuDetailUpdateModelValidated
                                        = $subMenuDetailUpdateModel->validate(
                                        ['aad_param']
                                    );

                                    if ($subMenuDetailUpdateModelValidated) {
                                        $subMenuDetailUpdateModel->update(
                                            FALSE,
                                            ['aad_param']
                                        );
                                    } else {
                                        $detailError
                                            = $subMenuDetailUpdateModel->getErrors();
                                        if ($flag != FALSE) {
                                            $flag = FALSE;
                                            break;
                                        }
                                    }
                                } else {

                                    $masterMenuData = AutoAttendantMaster::findOne(
                                        $model->aam_id
                                    );

                                    $autoAttendantSubMenuModel
                                        = new AutoAttendantMaster();
                                    $autoAttendantSubMenuModel->setScenario(
                                        'subMenuCreate'
                                    );

                                    $autoAttendantSubMenuModel->aam_name
                                        = $subMenuvalue;
                                    $autoAttendantSubMenuModel->aam_greet_long
                                        = $model->aam_greet_long;
                                    $autoAttendantSubMenuModel->aam_greet_short
                                        = NULL;
                                    $autoAttendantSubMenuModel->aam_invalid_sound
                                        = NULL;
                                    $autoAttendantSubMenuModel->aam_exit_sound
                                        = NULL;
                                    $autoAttendantSubMenuModel->aam_failure_prompt
                                        = NULL;
                                    $autoAttendantSubMenuModel->aam_timeout_prompt
                                        = NULL;
                                    $autoAttendantSubMenuModel->aam_direct_dial
                                        = NULL;
                                    $autoAttendantSubMenuModel->aam_transfer_on_failure
                                        = NULL;
                                    $autoAttendantSubMenuModel->aam_digit_len
                                        = NULL;
                                    $autoAttendantSubMenuModel->aam_mapped_id
                                        = $model->aam_id;
                                    $autoAttendantSubMenuModel->aam_level
                                        = $masterMenuData->aam_level + 1;
                                    $autoAttendantSubMenuModel->aam_status = '1';

                                    $modelSaved = $autoAttendantSubMenuModel->save();

                                    if ($modelSaved) {

                                        $autoAttendantOriginalModel = AutoAttendantMaster::find()->where(['aam_id' => $originalSubMenu])->one();
                                        $autoAttendantSubModel = AutoAttendantMaster::find()->where(['aam_id' => $autoAttendantSubMenuModel->aam_id])->one();
                                        $autoAttendantSubModel->attributes = $autoAttendantOriginalModel->attributes;
                                        $autoAttendantSubModel->aam_id = $autoAttendantSubMenuModel->aam_id;
                                        $autoAttendantSubModel->aam_name = $autoAttendantSubMenuModel->aam_name;
                                        $autoAttendantSubModel->save(false);


                                        $aak_id = AutoAttendantKeys::getIdByDesc(
                                            $value
                                        );

                                        /** @var AutoAttendantKeys $autoAttendantKeyCode */
                                        $autoAttendantKeyCode
                                            = AutoAttendantKeys::findOne(
                                            ['aak_id' => $aak_id]
                                        )->aak_key_code;

                                        $detailAutoAttendant
                                            = new AutoAttendantDetail();
                                        $detailAutoAttendant->aam_id
                                            = $model->aam_id;
                                        $detailAutoAttendant->aad_digit = $digit;
                                        $detailAutoAttendant->aad_action
                                            = $autoAttendantKeyCode;
                                        $detailAutoAttendant->aad_action_desc
                                            = $value;
                                        $detailAutoAttendant->aad_param
                                            = $subMenuvalue;

                                        if (!$detailAutoAttendant->save()) {
                                            $detailError
                                                = $detailAutoAttendant->getErrors();
                                            if ($flag != FALSE) {
                                                $flag = FALSE;
                                            }
                                        } else {

                                            /** @var AutoAttendantDetail $autoAttendantDetailModel */
                                            $autoAttendantDetailModel = AutoAttendantDetail::find()->where(['aam_id' => $originalSubMenu])->all();
                                            foreach ($autoAttendantDetailModel as $keyA => $valueA) {
                                                $AutoAttendantDetailModal = new AutoAttendantDetail();
                                                $AutoAttendantDetailModal->aam_id = $autoAttendantSubMenuModel->aam_id;
                                                $AutoAttendantDetailModal->aad_digit = $valueA->aad_digit;
                                                $AutoAttendantDetailModal->aad_action = $valueA->aad_action;
                                                $AutoAttendantDetailModal->aad_action_desc = $valueA->aad_action_desc;
                                                $AutoAttendantDetailModal->aad_param = $valueA->aad_param;
                                                $AutoAttendantDetailModal->save(false);
                                            }


                                            break;
                                        }
                                    } else {
                                        $autoAttendantSubMenuModel->addError(
                                            'aad_param',
                                            AutoAttendantModule::t(
                                                'autoattendant',
                                                'sub_menu_already_taken'
                                            )
                                        );
                                        $detailError
                                            = $autoAttendantSubMenuModel->getErrors();
                                        if ($flag != FALSE) {
                                            $flag = FALSE;
                                        }
                                    }
                                }
                            }

                            break;

                        case 'Disconnect':

                            $aak_id = AutoAttendantKeys::getIdByDesc($value);

                            $autoAttendantKeyParam = AutoAttendantKeys::findOne(
                                ['aak_id' => $aak_id]
                            )->aak_key_param_tpl;
                            $detailDataModel = $this->saveAutoDetailData(
                                $model,
                                $digit,
                                $value
                            );
                            $detailDataModel->aad_param = $autoAttendantKeyParam;

                            if (!$detailDataModel->save()) {
                                $detailError = $detailDataModel->getErrors();
                                if ($flag != FALSE) {
                                    $flag = FALSE;
                                }
                            }
                            break;

                        case 'Login to voicemail':

                            $aak_id = AutoAttendantKeys::getIdByDesc($value);

                            $autoAttendantKeyParam = AutoAttendantKeys::findOne(
                                ['aak_id' => $aak_id]
                            )->aak_key_param_tpl;
                            $detailDataModel = $this->saveAutoDetailData(
                                $model,
                                $digit,
                                $value
                            );
                            $detailDataModel->aad_param = $autoAttendantKeyParam;

                            if (!$detailDataModel->save()) {
                                $detailError = $detailDataModel->getErrors();
                                if ($flag != FALSE) {
                                    $flag = FALSE;
                                }
                            }
                            break;
                    }
                }
            } else {
                continue;
            }

            unset($detailAutoAttendant);
        }

        return ['result' => $flag, 'error' => $detailError];
    }

    /**
     * This Method will store values AutoAttendantDetail Model
     * for each case and return Model to save further.
     *
     * @param $model
     * @param $digit
     * @param $value
     *
     * @return AutoAttendantDetail
     */
    public function saveAutoDetailData($model, $digit, $value)
    {

        $aak_id = AutoAttendantKeys::getIdByDesc($value);

        $autoAttendantKeyCode = AutoAttendantKeys::findOne(
            ['aak_id' => $aak_id]
        )->aak_key_code;

        $detailAutoAttendant = new AutoAttendantDetail();
        $detailAutoAttendant->aam_id = $model->aam_id;
        $detailAutoAttendant->aad_digit = $digit;
        $detailAutoAttendant->aad_action = $autoAttendantKeyCode;
        $detailAutoAttendant->aad_action_desc = $value;

        return $detailAutoAttendant;
    }

    /**
     * Delete Sub Menu Data
     *
     * @param $requestData
     */
    public function deleteSubMenuData($requestData)
    {
        $subMenuActions = $requestData['actions'];

        foreach ($subMenuActions as $key => $value) {
            $subMenus = $requestData['submenu'][$key];

            switch ($value) {
                case 'Sub Menu':
                    break;
                default :
                    AutoAttendantDetail::deleteAll(['aad_param' => $subMenus]);
                    break;
            }
        }
    }

    /**
     * Return All Post data when error occurred.
     *
     * @param $post
     *
     * @return array
     */
    public static function getAllAutoAttendantDetails($post)
    {
        $return_array = [];

        foreach ($post['actions'] as $key => $value) {
            if ($value) {
                $return_array[$key]['actions'] = $post['actions'][$key];
                $return_array[$key]['audio'] = $post['audio'][$key];
                $return_array[$key]['outbound_extension']
                    = $post['outbound_extension'][$key];
                $return_array[$key]['submenu'] = $post['submenu'][$key];
            } else {
                continue;
            }
        }

        return $return_array;
    }

    /**
     * @param $value
     *
     * @return mixed
     */
    public function userGroupData($value)
    {
        $arrayData = explode('-', $value->aad_param)[1];
        return explode(' ', $arrayData)[0];
    }

    /**
     * Deletes an existing AutoAttendantMaster model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param integer $id
     *
     * @return Response
     * @throws Exception
     * @throws NotFoundHttpException
     * @throws \Exception
     */
    public function actionDelete($id)
    {
        isset($_GET['page']) ? $page = $_GET['page'] : $page = '1';

        if ($model = $this->findModel($id)) {

            $count = AutoAttendantDetail::find()->where(['aad_param' => $model->aam_name, 'aad_action' => 'menu-sub'])->count();
            if ($count > 0) {
                Yii::$app->session->setFlash(
                    'error',
                    $model->aam_name . "" . AutoAttendantModule::t(
                        'autoattendant',
                        'delete_audio_text_mapped'
                    )
                );
                return $this->redirect(['index', 'page' => $page]);
            }
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if ($model->delete()) {
                    AutoAttendantDetail::deleteAll(['aam_id' => $id]);
                    // Transaction Commit
                    $transaction->commit();

                    Yii::$app->session->setFlash(
                        'success',
                        AutoAttendantModule::t(
                            'autoattendant',
                            'deleted_success'
                        )
                    );

                    return $this->redirect(['index', 'page' => $page]);
                } else {
                    //Transaction Rollback
                    $transaction->rollBack();
                    Yii::$app->session->setFlash(
                        'error',
                        AutoAttendantModule::t(
                            'autoattendant',
                            'something_wrong'
                        )
                    );

                    return $this->redirect(['index', 'page' => $page]);
                }
            } catch (Exception $exception) {
                // Transaction Rollback
                $transaction->rollBack();
                Yii::$app->session->setFlash(
                    'error',
                    AutoAttendantModule::t('autoattendant', 'something_wrong')
                );

                return $this->redirect(['index', 'page' => $page]);
            }
        }

        return $this->redirect(['index', 'page' => $page]);
    }

    /**
     * Return alldata in array format to display while update.
     *
     * @param $allAutoAttendantDetails
     *
     * @return array
     */
    public function allData($allAutoAttendantDetails)
    {
        $value = $allAutoAttendantDetails->aad_action_desc;
        $result = [];

        switch ($value) {
            case 'Playfile':
            case 'Sub Menu':

            case 'Copy Sub Menu':
                $result[$allAutoAttendantDetails->aad_digit]
                    = $allAutoAttendantDetails->aad_param;

                return $result;
                break;

            case 'IVR':
            case 'Voicemail':
            case 'Transfer to extension':
            case 'Queues':
            case 'Ring Groups':
            case 'Conference':
            case 'External Number':

                $extenData = $this->outboundExtensionVoicemailData(
                    $allAutoAttendantDetails
                );
                $result[$allAutoAttendantDetails->aad_digit] = $extenData;

                return $result;
                break;

            case 'Repeat Menu':
            case 'Return to Previous Menu':
            case 'Dial by name':
            case 'No Action':
            case 'Direct Extension Dial':
            case 'Disconnect':
            case 'Login to voicemail':
                return [];
                break;
        }
    }
}

