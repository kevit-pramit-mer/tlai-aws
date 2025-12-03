<?php

namespace app\controllers\api;

use app\components\HelperLayouts;
use app\modules\ecosmob\auth\models\AdminMaster;
use app\modules\ecosmob\device\models\DeviceToken;
use app\modules\ecosmob\extension\models\Callsettings;
use app\modules\ecosmob\extension\models\Extension;
use app\modules\ecosmob\globalconfig\models\GlobalConfig;
use app\modules\ecosmob\license\models\LicenseTicketManagement;
use Codeception\Util\HttpCode;
use Throwable;
use Yii;
use yii\base\Exception;
use yii\db\StaleObjectException;
use yii\rest\ActiveController;

class AuthController extends ActiveController
{
    public $modelClass = AdminMaster::class;

    public function actions()
    {
        $actions = parent::actions();

        unset(
            $actions['index'],
            $actions['view'],
            $actions['create'],
            $actions['update'],
            $actions['delete'],
            // $actions['queue-call'],
            $actions['options']
        );

        return $actions;
    }

    /**
     * @return array
     * @throws Exception
     * @throws Throwable
     */
    public function actionLogin()
    {
        $uid = Yii::$app->request->post('uid');
        $password = Yii::$app->request->post('password');
        $deviceId = Yii::$app->request->post('device_id');
        $forceLogin = Yii::$app->request->post('force_login');
        $platform = isset(Yii::$app->request->getHeaders()['X-platform']) ?
            Yii::$app->request->getHeaders()['X-platform'] :
            '';
        $os = isset(Yii::$app->request->getHeaders()['X-OSVersion']) ?
            Yii::$app->request->getHeaders()['X-OSVersion'] :
            '';
        $device = isset(Yii::$app->request->getHeaders()['X-device']) ?
            Yii::$app->request->getHeaders()['X-device'] :
            '';
        Yii::$app->language = isset(Yii::$app->request->getHeaders()['X-Language']) ?
            (Yii::$app->request->getHeaders()['X-Language'] == 'es') ? 'es-ES' : 'en-US'
            :
            'en-US';


        $user = Extension::find()->where(['em_extension_number' => $uid])
            ->andWhere(['em_password' => $password])
            ->one();

        if (!$user) {

            return HelperLayouts::apiResponse(HttpCode::NOT_FOUND, Yii::t('app', 'invalid_credentials'), null);
        }

        $userExists = DeviceToken::findUser($uid);

        if ($userExists) {
            if ($forceLogin == 0) {
                return HelperLayouts::apiResponse(HttpCode::ALREADY_REPORTED, Yii::t('app', 'ask_for_forcelogin'), null);
            } else {
                $userDevice = DeviceToken::findDevices($uid, $deviceId);
                if ($userDevice) {
                    foreach ($userDevice as $v) {
                        if ($v['device_type'] == '0') {
                            Yii::$app->commonHelper->android($v['device_id'], 'userlogin');
                        } else {
                            Yii::$app->commonHelper->ios($v['device_id'], 'userlogin');
                        }
                    }
                }
                $userExists->delete();
            }
        }

        $apiToken = DeviceToken::getFreshToken(32);

        $saveToken = DeviceToken::saveNewToken($uid, $deviceId, $apiToken, $platform, $os, $device);

        if ($saveToken) {
            $callSettingData = Callsettings::find()->where(['em_id' => $user->em_id])->one();
            $domainObj = GlobalConfig::find()
                ->select('gwc_value')
                ->where(['gwc_key' => 'domain'])
                ->one();
            $sipDomainObj = GlobalConfig::find()
                ->select('gwc_value')
                ->where(['gwc_key' => 'sip_domain'])
                ->one();


            $response = [
                'em_extension_number' => $user->em_extension_number,
                'em_password' => $user->em_password,
                'token' => $apiToken,
                'em_timezone_id' => $user->em_timezone_id,
                'ecs_audio_codecs' => $callSettingData->ecs_audio_codecs,
                'ecs_video_codecs' => $callSettingData->ecs_video_codecs,
                'domain' => $domainObj['gwc_value'],
                'sip_domain' => $sipDomainObj['gwc_value'],
                'about_us_link' => "https://" . $domainObj['gwc_value'] . "/web/index.php?r=site/about",
                'about_us_spanish_link' => "https://" . $domainObj['gwc_value'] . "/web/index.php?r=site/about-spanish",
            ];
            return HelperLayouts::apiResponse(HttpCode::OK, Yii::t('app', 'request_success'), $response);
        } else {
            return HelperLayouts::apiResponse(HttpCode::NOT_FOUND, Yii::t('app', 'something_went_wrong'), null);
        }
    }

    /**
     * @return array
     * @throws Exception
     * @throws Throwable
     */
    public function actionNotify()
    {
        Yii::$app->language = isset(Yii::$app->request->getHeaders()['X-Language']) ?
            (Yii::$app->request->getHeaders()['X-Language'] == 'es') ? 'es-ES' : 'en-US'
            :
            'en-US';
        $uid = Yii::$app->request->post('uid');
        $user = Extension::find()->where(['em_extension_number' => $uid])->one();
        if (!$user) {
            return HelperLayouts::apiResponse(HttpCode::NOT_FOUND, Yii::t('app', 'invalid_credentials'), null);
        }
        $userExists = DeviceToken::findUser($uid);
        if ($userExists) {
            $userDevice = DeviceToken::findAllDevices($uid);
            if ($userDevice) {
                foreach ($userDevice as $v) {
                    if ($v['device_type'] == '0') {
                        Yii::$app->commonHelper->android($v['device_id'], 'whenwaked');
                    } else {
                        Yii::$app->commonHelper->ios($v['voip_token_id'], 'whenwaked');
                    }
                }
            }
        }

        $response = [
        ];
        return HelperLayouts::apiResponse(HttpCode::OK, Yii::t('app', 'request_success'), $response);

    }

    /**
     * @return array
     */
    public function actionUpdateDeviceToken()
    {
        Yii::$app->language = isset(Yii::$app->request->getHeaders()['X-Language']) ?
            (Yii::$app->request->getHeaders()['X-Language'] == 'es') ? 'es-ES' : 'en-US'
            :
            'en-US';
        $uid = Yii::$app->request->post('uid');
        $device_id = Yii::$app->request->post('device_id');

        $user = Extension::find()->where(['em_extension_number' => $uid])->one();
        if (!$user) {
            return HelperLayouts::apiResponse(HttpCode::NOT_FOUND, Yii::t('app', 'invalid_credentials'), null);
        }
        $userExists = DeviceToken::findUser($uid);
        if ($userExists) {
            DeviceToken::saveToken($uid, $device_id);
        }

        $response = [
        ];
        return HelperLayouts::apiResponse(HttpCode::OK, Yii::t('app', 'device_token_updated'), $response);

    }

    /**
     * @return array
     */
    public function actionUpdateVoipDeviceToken()
    {
        Yii::$app->language = isset(Yii::$app->request->getHeaders()['X-Language']) ?
            (Yii::$app->request->getHeaders()['X-Language'] == 'es') ? 'es-ES' : 'en-US'
            :
            'en-US';
        $uid = Yii::$app->request->post('uid');
        $device_id = Yii::$app->request->post('voip_token_id');

        $user = Extension::find()->where(['em_extension_number' => $uid])->one();
        if (!$user) {
            return HelperLayouts::apiResponse(HttpCode::NOT_FOUND, Yii::t('app', 'invalid_credentials'), null);
        }
        $userExists = DeviceToken::findUser($uid);
        if ($userExists) {
            DeviceToken::saveVoipToken($uid, $device_id);
        }

        $response = [
        ];
        return HelperLayouts::apiResponse(HttpCode::OK, Yii::t('app', 'device_token_updated'), $response);

    }

    /**
     * @return mixed
     * @throws Throwable
     * @throws StaleObjectException
     */
    public function actionLogout()
    {
        Yii::$app->language = isset(Yii::$app->request->getHeaders()['X-Language']) ?
            (Yii::$app->request->getHeaders()['X-Language'] == 'es') ? 'es-ES' : 'en-US'
            :
            'en-US';
        $authToken = isset(Yii::$app->request->getHeaders()['X-authorization']) ?
            Yii::$app->request->getHeaders()['X-authorization'] :
            '';

        $logout = DeviceToken::deleteUserToken($authToken);

        if ($logout) {
            return HelperLayouts::apiResponse(HttpCode::OK, Yii::t('app', 'request_success'), null);
        } else {
            return HelperLayouts::apiResponse(HttpCode::NOT_FOUND, Yii::t('app', 'invalid_credentials'), null);
        }
    }

    public function actionQueueCall()
    {
        $data = Yii::$app->request->post();
        //$headers = Yii::$app->request->headers;
        // $authKey = $headers->get('X-authorization');
        //$type = $headers->get('X-type');
        if (!empty($data)) {
            Yii::$app->db->createCommand()
                ->insert('ct_queue_callback', [
                    'queue_name' => $data['queue_name'],
                    'phone_number' => $data['phone_number'],
                    'created_at' => date('Y-m-d H:i:s'),
                ])->execute();
            //http_response_code(204);
            $response = [
                'status' => '200',
                'message' => 'Success',
                // 'data' => (object)array(),
            ];

        } else {
            $response = [
                'status' => '204',
                'message' => 'Blank data given',
                //'data' => (object)array(),
            ];
        }
        /** @var $response */
        echo json_encode($response, true);
        die;
    }

    public function actionUpdateTicket()
    {
        $data = Yii::$app->request->post();
        if (!empty($data)) {
            if(!empty($data['ticketId']) && !empty($data['status'])) {
                $ticket = LicenseTicketManagement::findOne($data['ticketId']);
                if(!empty($ticket)){
                    Yii::$app->db->createCommand()
                        ->update('license_ticket_management', (['status' => $data['status']]), ['id' => $data['ticketId']])
                        ->execute();
                    return HelperLayouts::apiResponse(HttpCode::OK, 'Ticket Status Updated Successfully', null);
                }else{
                    return HelperLayouts::apiResponse(HttpCode::NOT_FOUND, 'Ticket ID not found', null);
                }
            }else{
                return HelperLayouts::apiResponse(HttpCode::NO_CONTENT, 'Ticket ID and Status cannot be blank.', null);
            }
        } else {
            return HelperLayouts::apiResponse(HttpCode::NO_CONTENT, 'Blank data given', null);
        }
    }

    protected function verbs()
    {
        return [
            'login' => ['POST'],
            'logout' => ['POST'],
            'queue-call' => ['POST'],
            // 'queue-call' => ['POST']
            'update-ticket' => ['POST']
        ];
    }
}
