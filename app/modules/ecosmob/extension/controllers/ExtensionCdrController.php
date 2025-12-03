<?php

namespace app\modules\ecosmob\extension\controllers;

use app\models\CodecMaster;
use app\models\SipPresence;
use app\models\SipRegistrations;
use app\modules\ecosmob\agents\models\AdminMaster;
use app\modules\ecosmob\blf\models\ExtensionBlf;
use app\modules\ecosmob\callhistory\models\CampCdr;
use app\modules\ecosmob\cdr\models\Cdr;
use app\modules\ecosmob\didmanagement\models\DidManagement;
use app\modules\ecosmob\enterprisePhonebook\models\EnterprisePhonebook;
use app\modules\ecosmob\extension\extensionModule;
use app\modules\ecosmob\extension\models\Callsettings;
use app\modules\ecosmob\extension\models\Extension;
use app\modules\ecosmob\extension\models\ExtensionCallLog;
use app\modules\ecosmob\extension\models\ExtensionCdr;
use app\modules\ecosmob\extension\models\ExtensionSearch;
use app\modules\ecosmob\extensionforwarding\models\ExtensionForwarding;
use app\modules\ecosmob\timezone\models\Timezone;
use app\modules\ecosmob\voicemsg\models\VoicemailMsgs;
use Throwable;
use Yii;
use yii\base\InvalidArgumentException;
use yii\data\ActiveDataProvider;
use yii\db\Exception;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;
use app\modules\ecosmob\phonebook\models\Phonebook;

/**
 * ExtensionCdrController implements the CRUD actions for Extension model.
 */
class ExtensionCdrController extends Controller
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
                            'extension-cdr',
                            'update-call-end-time',
                            'update-call-ans-time',
                            'update-cdr',
                            'remove-sip'
                        ],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function beforeAction($action)
    {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    public function actionExtensionCdr(){
        $model = ExtensionCdr::find()->where(['call_id' => Yii::$app->request->post()['callId'], 'to_number' => Yii::$app->request->post()['toNumber']])->one();
        if(empty($model)){
            $model = new ExtensionCdr();
        }
        $current_date = date('Y-m-d H:i:s');
        if (Yii::$app->request->post()) {
            $model->from_number = Yii::$app->request->post()['fromNumber'];
            $model->to_number = Yii::$app->request->post()['toNumber'];
            $model->call_id = Yii::$app->request->post()['callId'];
            $model->direction = Yii::$app->request->post()['direction'];
            $model->start_time = $current_date;
            $model->call_type = (Yii::$app->request->post()['callType'] == 1 ? 'audio' : 'video');
            $model->save();
        }
    }

    public function actionUpdateCallEndTime(){
        $current_date = date('Y-m-d H:i:s');
        $callId = Yii::$app->request->post()['callId'];
        $model = ExtensionCdr::find()->where(['call_id' => $callId, 'to_number' => Yii::$app->user->identity->em_extension_number])->one();
        if(!empty($model)){
            Yii::$app->db->createCommand()
                ->update('extension_cdr', (['end_time' => $current_date]), ['call_id' => $callId, 'id' => $model->id])
                ->execute();
        }else {
            Yii::$app->db->createCommand()
                ->update('extension_cdr', (['end_time' => $current_date]), ['call_id' => $callId])
                ->execute();
        }
    }
    public function actionUpdateCallAnsTime()
    {
        $current_date = date('Y-m-d H:i:s');
        $callId = Yii::$app->request->post()['callId'];
        $model = ExtensionCdr::find()->where(['call_id' => $callId, 'to_number' => Yii::$app->user->identity->em_extension_number])->one();
        if(!empty($model)){
            Yii::$app->db->createCommand()
                ->update('extension_cdr', (['ans_time' => $current_date]), ['call_id' => $callId, 'id' => $model->id])
                ->execute();
        }else{
            Yii::$app->db->createCommand()
                ->update('extension_cdr', (['ans_time' => $current_date]), ['call_id' => $callId])
                ->execute();
        }

    }

    public function actionUpdateCdr(){
        $current_date = date('Y-m-d H:i:s');
        $callId = Yii::$app->request->post()['callId'];

        Yii::$app->db->createCommand()
            ->update('camp_cdr', (['end_time' => $current_date]), ['call_id' => $callId])
            ->execute();

        SipRegistrations::deleteAll(['AND', ['sip_user' => Yii::$app->user->identity->em_extension_number], ['sip_host' => $_SERVER['HTTP_HOST']], ['LIKE', 'user_agent', 'SIP.js/0.21.2%', false]]);

        $model = ExtensionCdr::find()->where(['call_id' => $callId, 'to_number' => Yii::$app->user->identity->em_extension_number])->one();
        if(!empty($model)){
            Yii::$app->db->createCommand()
                ->update('extension_cdr', (['end_time' => $current_date]), ['call_id' => $callId, 'id' => $model->id])
                ->execute();
        }else {
            Yii::$app->db->createCommand()
                ->update('extension_cdr', (['end_time' => $current_date]), ['call_id' => $callId])
                ->execute();
        }
    }

    public function actionRemoveSip(){
        SipRegistrations::deleteAll(['AND', ['sip_user' => Yii::$app->user->identity->em_extension_number], ['sip_host' => $_SERVER['HTTP_HOST']], ['LIKE', 'user_agent', 'SIP.js/0.21.2%', false]]);
    }
}
