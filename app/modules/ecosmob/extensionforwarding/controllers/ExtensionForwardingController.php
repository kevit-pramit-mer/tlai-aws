<?php /** @noinspection ALL */

namespace app\modules\ecosmob\extensionforwarding\controllers;

use app\modules\ecosmob\extension\models\Callsettings;
use app\modules\ecosmob\extension\models\Extension;
use app\modules\ecosmob\extension\models\FindmeFollowme;
use app\modules\ecosmob\holiday\models\Holiday;
use app\modules\ecosmob\shift\models\Shift;
use app\modules\ecosmob\weekoff\models\WeekOff;
use Yii;
use app\modules\ecosmob\extensionforwarding\models\ExtensionForwarding;
use app\modules\ecosmob\extensionforwarding\models\ExtensionForwardingSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use app\modules\ecosmob\extensionforwarding\ExtensionForwardingModule;

/**
 * ExtensionForwardingController implements the CRUD actions for ExtensionForwarding model.
 */
class ExtensionForwardingController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access'=>[
                'class'=>AccessControl::className(),
                'rules'=>[
                    [
                        'actions'=>[
                            'index',
                            'create',
                            'view',
                            'update',
                            'delete',
                            'forwading',
                        ],
                        'allow'=>true,
                        'roles'=>['@'],
                    ],
                ],
            ],
            'verbs'=>[
                'class'=>VerbFilter::className(),
                'actions'=>[
                    'delete'=>['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all ExtensionForwarding models.
     * @return mixed
     */
    public function actionIndex()
    {
//        Yii::$app->user->identity->em_extension_number

        $searchModel=new ExtensionForwardingSearch();
        $dataProvider=$searchModel->search(Yii::$app->request->queryParams);

        return $this->renderPartial('index', [
            'searchModel'=>$searchModel,
            'dataProvider'=>$dataProvider,
        ]);
    }

    /**
     * Displays a single ExtensionForwarding model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->renderPartial('view', [
            'model'=>$this->findModel($id),
        ]);
    }

    /**
     * Finds the ExtensionForwarding model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ExtensionForwarding the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model=ExtensionForwarding::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(ExtensionForwardingModule::t('extensionforwarding', 'page_not_exits'));
    }

    /**
     * Creates a new ExtensionForwarding model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionCreate()
    {
        /** @var ExtensionForwarding $model */
        $model=new ExtensionForwarding();
        $call_setting_model=new Callsettings();

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $call_setting_model->load(Yii::$app->request->post()) && $call_setting_model->validate(['ecs_ring_timeout', 'ecs_call_timeout'])) {
            $voiceType='VOICEMAIL';
            $model->ef_unconditional_num=($model->ef_unconditional_type == $voiceType) ? '' : $model->ef_unconditional_num;
            $model->ef_holiday_num=($model->ef_holiday_type == $voiceType) ? '' : $model->ef_holiday_num;
            $model->ef_weekoff_num=($model->ef_weekoff_type == $voiceType) ? '' : $model->ef_weekoff_num;
            $model->ef_shift_num=($model->ef_shift_type == $voiceType) ? '' : $model->ef_shift_num;
            $model->ef_universal_num=($model->ef_universal_type == $voiceType) ? '' : $model->ef_universal_num;
            $model->ef_no_answer_num=($model->ef_no_answer_type == $voiceType) ? '' : $model->ef_no_answer_num;
            $model->ef_busy_num=($model->ef_busy_type == $voiceType) ? '' : $model->ef_busy_num;
            $model->ef_unavailable_num=($model->ef_unavailable_type == $voiceType) ? '' : $model->ef_unavailable_num;


            $model->save();

            $extensionData=Extension::findOne(['em_extension_number'=>$model->ef_extension]);
            /*   echo "<pre>";
                print_r(Yii::$app->request->post());
                echo "</pre>";
                exit;*/

            $em_id=$extensionData->em_id;

            /** @var Callsettings $callsetting */
            $callsetting=Callsettings::findOne(['em_id'=>$em_id]); // where emid = 10
            $callsetting->ecs_ring_timeout=($call_setting_model->ecs_ring_timeout != '') ? $call_setting_model->ecs_ring_timeout : '60';
            $callsetting->ecs_call_timeout=($call_setting_model->ecs_call_timeout != '') ? $call_setting_model->ecs_call_timeout : '60';
            $callsetting->ecs_voicemail=$call_setting_model->ecs_voicemail; //
            $callsetting->ecs_blacklist=$call_setting_model->ecs_blacklist; //
            $callsetting->ecs_accept_blocked_caller_id=$call_setting_model->ecs_accept_blocked_caller_id; //
            $callsetting->ecs_call_redial=$call_setting_model->ecs_call_redial; //
            $callsetting->ecs_bargein=$call_setting_model->ecs_bargein; //
            $callsetting->ecs_busy_call_back=$call_setting_model->ecs_busy_call_back; //
            $callsetting->ecs_park=$call_setting_model->ecs_park; //
            $callsetting->ecs_do_not_disturb=$call_setting_model->ecs_do_not_disturb; //
            $callsetting->ecs_caller_id_block=$call_setting_model->ecs_caller_id_block; //
            $callsetting->ecs_whitelist=$call_setting_model->ecs_whitelist; //
            $callsetting->ecs_call_recording=$call_setting_model->ecs_call_recording; //
            $callsetting->ecs_call_return=$call_setting_model->ecs_call_return; //
            $callsetting->ecs_transfer=$call_setting_model->ecs_transfer; //
            $callsetting->ecs_call_waiting=$call_setting_model->ecs_call_waiting; //


            $callsetting->save(false); // save

            Yii::$app->session->setFlash('success', ExtensionForwardingModule::t('extensionforwarding', 'created_success'));
            return $this->redirect(['index']);
        }

        /*for Holiday List*/
        $holidaylist=Holiday::find()->all();

        /*For Extension list*/
        $internalList=Extension::find()->select(['em_id', 'em_extension_name', 'em_extension_number'])->all();

        /*For shift list*/
        $shiftList=Shift::find()->select(['sft_id', 'sft_name'])->all();

        /*For week off List*/
        $weekOfList=WeekOff::find()->select(['wo_id', 'wo_day'])->all();

        /*For Call Setting*/


        return $this->renderPartial('create', [
            'model'=>$model,
            'holidaylist'=>$holidaylist,
            'internalList'=>$internalList,
            'shiftList'=>$shiftList,
            'weekOfList'=>$weekOfList,
            'call_setting_model'=>$call_setting_model,
            // 'findme_followme_model'=>$findme_followme_model,
        ]);
    }


    /**
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionForwading()
    {

        $extension_number=Yii::$app->user->identity->em_extension_number;

        $model=ExtensionForwarding::find()->where(['ef_extension'=>$extension_number])->one();


        if (!$model) {
            $model=new ExtensionForwarding();
            $model->ef_extension=$extension_number;
            $model->save(false);
        }

        /**@var FindmeFollowme $findme_followme_model */

        $findme_followme_model=FindmeFollowme::findOne(['ff_extension'=>$extension_number]);

        if (!$findme_followme_model) {
            $findme_followme_model=new FindmeFollowme();
            $findme_followme_model->ff_extension=$extension_number;
            $findme_followme_model->save(false);
        }

        /*$model=$this->findModel($extension_number);*/

        /** @var Extension $extensionData */
        $extensionData=Extension::findOne(['em_extension_number'=>$model->ef_extension]);
        $em_id=$extensionData->em_id;
        /** @var Callsettings $callsetting */
        $call_setting_model=Callsettings::findOne(['em_id'=>$em_id]);
        /*$call_setting_model->load(Yii::$app->request->post());
        $call_setting_model->validate(['ecs_ring_timeout', 'ecs_call_timeout'],['ecs_feature_code_pin','ecs_ob_max_timeout']);
        */
        $validate=0;
        if ($call_setting_model->load(Yii::$app->request->post()) && $call_setting_model->validate(['ecs_forwarding', 'ecs_ring_timeout', 'ecs_call_timeout'], ['ecs_feature_code_pin', 'ecs_ob_max_timeout'])) {
            if ($call_setting_model->ecs_forwarding == '0') { // Disable
                $validate=1;
            } else if ($call_setting_model->ecs_forwarding == '1') { // individual
                if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                    $validate=1;
                }
            } else if ($call_setting_model->ecs_forwarding == '2') { // FMFM
                if ($findme_followme_model->load(Yii::$app->request->post()) && $findme_followme_model->validate()) {
                    $validate=1;
                }
            }
        }

        if ($validate) {

            $voiceType='VOICEMAIL';
            $model->ef_unconditional_num=($model->ef_unconditional_type == $voiceType) ? '' : $model->ef_unconditional_num;
            $model->ef_holiday_num=($model->ef_holiday_type == $voiceType) ? '' : $model->ef_holiday_num;
            $model->ef_weekoff_num=($model->ef_weekoff_type == $voiceType) ? '' : $model->ef_weekoff_num;
            $model->ef_shift_num=($model->ef_shift_type == $voiceType) ? '' : $model->ef_shift_num;
            $model->ef_universal_num=($model->ef_universal_type == $voiceType) ? '' : $model->ef_universal_num;
            $model->ef_no_answer_num=($model->ef_no_answer_type == $voiceType) ? '' : $model->ef_no_answer_num;
            $model->ef_busy_num=($model->ef_busy_type == $voiceType) ? '' : $model->ef_busy_num;
            $model->ef_unavailable_num=($model->ef_unavailable_type == $voiceType) ? '' : $model->ef_unavailable_num;
            $model->save(false);

            /** @var Callsettings $callsetting */
            $callsetting=Callsettings::findOne(['em_id'=>$em_id]); // where emid = 10

            $callsetting->ecs_ring_timeout=($call_setting_model->ecs_ring_timeout != '') ? $call_setting_model->ecs_ring_timeout : '60';
            $callsetting->ecs_call_timeout=($call_setting_model->ecs_call_timeout != '') ? $call_setting_model->ecs_call_timeout : '60';
            $callsetting->ecs_voicemail=$call_setting_model->ecs_voicemail; //
            $callsetting->ecs_blacklist=$call_setting_model->ecs_blacklist; //
            $callsetting->ecs_accept_blocked_caller_id=$call_setting_model->ecs_accept_blocked_caller_id; //
            $callsetting->ecs_call_redial=$call_setting_model->ecs_call_redial; //
            $callsetting->ecs_bargein=$call_setting_model->ecs_bargein; //
            $callsetting->ecs_busy_call_back=$call_setting_model->ecs_busy_call_back; //
            $callsetting->ecs_park=$call_setting_model->ecs_park; //
            $callsetting->ecs_do_not_disturb=$call_setting_model->ecs_do_not_disturb; //
            $callsetting->ecs_caller_id_block=$call_setting_model->ecs_caller_id_block; //
            $callsetting->ecs_whitelist=$call_setting_model->ecs_whitelist; //
            $callsetting->ecs_call_recording=$call_setting_model->ecs_call_recording; //
            $callsetting->ecs_call_return=$call_setting_model->ecs_call_return; //
            $callsetting->ecs_transfer=$call_setting_model->ecs_transfer; //
            $callsetting->ecs_call_waiting=$call_setting_model->ecs_call_waiting; //
            $callsetting->ecs_forwarding=$call_setting_model->ecs_forwarding; //

            $callsetting->save(false); // save

            /** @var FindmeFollowme $findme_followme_model */
            $findme_followme_model->ff_1_extension=(empty($findme_followme_model->ff_1_type) || $findme_followme_model->ff_1_type == 'VOICEMAIL') ? '' : $findme_followme_model->ff_1_extension;
            $findme_followme_model->ff_2_extension=(empty($findme_followme_model->ff_2_type) || $findme_followme_model->ff_2_type == 'VOICEMAIL') ? '' : $findme_followme_model->ff_2_extension;
            $findme_followme_model->ff_3_extension=(empty($findme_followme_model->ff_3_type) || $findme_followme_model->ff_3_type == 'VOICEMAIL') ? '' : $findme_followme_model->ff_3_extension;
            $findme_followme_model->save(false);


            Yii::$app->session->setFlash('success', Yii::t('app', 'updated_success'));
            return $this->redirect(['/extension/extension/dashboard']);

        }


        /*for Holiday List*/
        $holidaylist=Holiday::find()->all();

        /*For Extension list*/
        $internalList=Extension::find()->select(['em_id', 'em_extension_name', 'em_extension_number'])->all();

        /*For shift list*/
        $shiftList=Shift::find()->select(['sft_id', 'sft_name'])->all();

        /*For week off List*/
        $weekOfList=WeekOff::find()->select(['wo_id', 'wo_day'])->all();

        /*For Call Setting*/
//        $call_setting_model=Callsettings::find()->select(['ecs_ring_timeout', 'ecs_call_timeout'])->all();
        /*echo "<pre>";
        print_r($call_setting_model->ecs_forwarding );
        echo "</pre>";
        exit;*/

        return $this->renderPartial('update', [
            'model'=>$model,
            'holidaylist'=>$holidaylist,
            'internalList'=>$internalList,
            'shiftList'=>$shiftList,
            'weekOfList'=>$weekOfList,
            'call_setting_model'=>$call_setting_model,
            'findme_followme_model'=>$findme_followme_model,
        ]);


    }

    /**
     * @param $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id)
    {
        $model=$this->findModel($id);

        /** @var Extension $extensionData */
        $extensionData=Extension::findOne(['em_extension_number'=>$model->ef_extension]);
        $em_id=$extensionData->em_id;

        /** @var Callsettings $callsetting */
        $call_setting_model=Callsettings::findOne(['em_id'=>$em_id]);

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $call_setting_model->load(Yii::$app->request->post()) && $call_setting_model->validate(['ecs_ring_timeout', 'ecs_call_timeout'])) {
            $voiceType='VOICEMAIL';
            $model->ef_unconditional_num=($model->ef_unconditional_type == $voiceType) ? '' : $model->ef_unconditional_num;
            $model->ef_holiday_num=($model->ef_holiday_type == $voiceType) ? '' : $model->ef_holiday_num;
            $model->ef_weekoff_num=($model->ef_weekoff_type == $voiceType) ? '' : $model->ef_weekoff_num;
            $model->ef_shift_num=($model->ef_shift_type == $voiceType) ? '' : $model->ef_shift_num;
            $model->ef_universal_num=($model->ef_universal_type == $voiceType) ? '' : $model->ef_universal_num;
            $model->ef_no_answer_num=($model->ef_no_answer_type == $voiceType) ? '' : $model->ef_no_answer_num;
            $model->ef_busy_num=($model->ef_busy_type == $voiceType) ? '' : $model->ef_busy_num;
            $model->ef_unavailable_num=($model->ef_unavailable_type == $voiceType) ? '' : $model->ef_unavailable_num;
            $model->save(false);

            /** @var Callsettings $callsetting */
            $callsetting=Callsettings::findOne(['em_id'=>$em_id]); // where emid = 10
            $callsetting->ecs_ring_timeout=($call_setting_model->ecs_ring_timeout != '') ? $call_setting_model->ecs_ring_timeout : '60';
            $callsetting->ecs_call_timeout=($call_setting_model->ecs_call_timeout != '') ? $call_setting_model->ecs_call_timeout : '60';
            $callsetting->ecs_voicemail=$call_setting_model->ecs_voicemail; //
            $callsetting->ecs_blacklist=$call_setting_model->ecs_blacklist; //
            $callsetting->ecs_accept_blocked_caller_id=$call_setting_model->ecs_accept_blocked_caller_id; //
            $callsetting->ecs_call_redial=$call_setting_model->ecs_call_redial; //
            $callsetting->ecs_bargein=$call_setting_model->ecs_bargein; //
            $callsetting->ecs_busy_call_back=$call_setting_model->ecs_busy_call_back; //
            $callsetting->ecs_park=$call_setting_model->ecs_park; //
            $callsetting->ecs_do_not_disturb=$call_setting_model->ecs_do_not_disturb; //
            $callsetting->ecs_caller_id_block=$call_setting_model->ecs_caller_id_block; //
            $callsetting->ecs_whitelist=$call_setting_model->ecs_whitelist; //
            $callsetting->ecs_call_recording=$call_setting_model->ecs_call_recording; //
            $callsetting->ecs_call_return=$call_setting_model->ecs_call_return; //
            $callsetting->ecs_transfer=$call_setting_model->ecs_transfer; //
            $callsetting->ecs_call_waiting=$call_setting_model->ecs_call_waiting; //
            $callsetting->ecs_forwarding=$call_setting_model->ecs_forwarding; //

            $callsetting->save(false); // save

            if (Yii::$app->request->post('apply') == 'update') {
                Yii::$app->session->setFlash('success', Yii::t('app', 'applied_success'));
                return $this->refresh();
            } else {
                Yii::$app->session->setFlash('success', Yii::t('app', 'updated_success'));
                return $this->redirect(['index']);
            }
        }

        /*for Holiday List*/
        $holidaylist=Holiday::find()->all();

        /*For Extension list*/
        $internalList=Extension::find()->select(['em_id', 'em_extension_name', 'em_extension_number'])->all();

        /*For shift list*/
        $shiftList=Shift::find()->select(['sft_id', 'sft_name'])->all();

        /*For week off List*/
        $weekOfList=WeekOff::find()->select(['wo_id', 'wo_day'])->all();

        /*For Call Setting*/
//        $call_setting_model=Callsettings::find()->select(['ecs_ring_timeout', 'ecs_call_timeout'])->all();

        return $this->renderPartial('update', [
            'model'=>$model,
            'holidaylist'=>$holidaylist,
            'internalList'=>$internalList,
            'shiftList'=>$shiftList,
            'weekOfList'=>$weekOfList,
            'call_setting_model'=>$call_setting_model,
            'findme_followme_model'=>$findme_followme_model,
        ]);
    }

    /**
     * Deletes an existing ExtensionForwarding model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', Yii::t('app', 'deleted_success'));
        return $this->redirect(['index']);
    }
}
