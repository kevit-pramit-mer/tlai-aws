<?php

namespace app\controllers\api;

use app\components\HelperLayouts;
use app\modules\ecosmob\auth\models\AdminMaster;
use Codeception\Util\HttpCode;
use Yii;
use yii\base\Exception;
use yii\db\mssql\PDO;
use yii\rbac\DbManager;
use yii\rest\ActiveController;

class CommonController extends ActiveController
{
    const ARG_SEPERATOR = " ";
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
    public function actionTenantOnBoard()
    {

        Yii::info("================ADD START ".date('Y-m-d H:i:s')."============", 'tenantonboard');

        $base_path = Yii::$app->params['PROJECT_PATH'];
        $server_ip = Yii::$app->params['TENANT_SERVER_IP'];

        header('Access-Control-Allow-Origin: *');
        Yii::$app->language = isset(Yii::$app->request->getHeaders()['X-Language']) ?
            (Yii::$app->request->getHeaders()['X-Language'] == 'es') ? 'es-ES' : 'en-US' : 'en-US';
        $uid = Yii::$app->request->post('tenant_uuid');
        $domain = Yii::$app->request->post('domain');
        $dataArr = [
            'tenant_uuid' => $uid,
            'domain' => $domain,
            'firstname' => Yii::$app->request->post('firstname'),
            'lastname' => Yii::$app->request->post('lastname'),
            'username' => Yii::$app->request->post('username'),
            'email' => Yii::$app->request->post('email'),
            'contact' => Yii::$app->request->post('contact'),
            'password' => Yii::$app->request->post('password'),
            'timezone' => Yii::$app->request->post('timezone'),
            'loginToken' => Yii::$app->request->post('login_token')
        ];
        if (!$uid) {
            return HelperLayouts::apiResponse(HttpCode::BAD_REQUEST, Yii::t('app', 'invalid paramas'), null);
        }

        $sh_file = $base_path . '/tenant_gen.sh';

        try {

            Yii::info("================Domain Name ".date('Y-m-d H:i:s')."============", 'tenantonboard');
            Yii::info($domain, 'tenantonboard');

            $output = shell_exec("sudo sh $sh_file $domain $server_ip");

            Yii::info("================Shell OUTPUT ".date('Y-m-d H:i:s')."============", 'tenantonboard');
            Yii::info($output, 'tenantonboard');

            $output = rtrim($output, "\n");
            $output .= ',tenant_uuid=' . $uid;

            $encoded_output = '';
            if ($output) {
                $encoded_output = base64_encode($output);
            }
            $api_base_url = Yii::$app->params['API_BASE_PATH'];
            $url = $api_base_url . 'config/add';
            $api_data = ['auth_params' => $encoded_output];

            Yii::$app->commonHelper->getAPIAccessToken();
            $token = Yii::$app->session->get('api_access_token');
            $header = [
                "token: $token"
            ];
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $api_data);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);


            $response = curl_exec($curl);
            $resultStatus = curl_getinfo($curl);
            curl_close($curl);
            $data = json_decode($response, true);

            Yii::info("================CONFIG ADD RESPONSE ".date('Y-m-d H:i:s')."============", 'tenantonboard');
            Yii::info(json_encode($data), 'tenantonboard');

            if ($resultStatus['http_code'] == 200) {

                Yii::info("================POST DATA ".date('Y-m-d H:i:s')."============", 'tenantonboard');
                Yii::info(json_encode($dataArr), 'tenantonboard');

                $tenantAdded = $this->addTenant($dataArr);

                Yii::info("================TENANT ADD RESPONSE ".date('Y-m-d H:i:s')."============", 'tenantonboard');
                Yii::info(json_encode($tenantAdded), 'tenantonboard');

                if ($tenantAdded['status'] != 200) {
                    return HelperLayouts::apiResponse($tenantAdded['status'], $tenantAdded['message'], null);
                }
            } else {
                return HelperLayouts::apiResponse($data['status'], $data['message'], null);
            }

        } catch (\Exception $e) {

            Yii::info("================SHELL SCRIPT CATCH ".date('Y-m-d H:i:s')."============", 'tenantonboard');
            Yii::info($e->getMessage(), 'tenantonboard');

            $errCode = $e->getCode();
            return HelperLayouts::apiResponse($errCode, $e->getMessage(), null);
        }
        return HelperLayouts::apiResponse(HttpCode::OK, $data['message'], null);

    }

    public function addTenant($data)
    {
        $base_path = Yii::$app->params['PROJECT_PATH'];

        Yii::info("================POST DATA OF TENANT ADD ".date('Y-m-d H:i:s')."============", 'tenantonboard');
        Yii::info(json_encode($data), 'tenantonboard');

        $uid = $data['tenant_uuid'];
        $domain = $data['domain'];
        $firstname = $data['firstname'];
        $lastname = $data['lastname'];
        $username = $data['username'];
        $email = $data['email'];
        $password = $data['password'];
        $contact = $data['contact'];
        $timezone = $data['timezone'];
        $loginToken = $data['loginToken'];

        $credentials = Yii::$app->commonHelper->initialGetTenantConfig($domain);

        Yii::info("================DB CREDENTIAL GET ".date('Y-m-d H:i:s')."============", 'tenantonboard');
        Yii::info("\ndb=" . json_encode($credentials), 'tenantonboard');

        if ($credentials) {
            $mysql_username = $credentials['authParams']['mysql_username'];
            $mysql_password = $credentials['authParams']['mysql_password'];
            $mysql_dbname = $credentials['authParams']['mysql_dbname'];
            $mysql_host = $credentials['authParams']['mysql_host'];

            Yii::info("================DB CREDENTIAL GET ".date('Y-m-d H:i:s')."============", 'tenantonboard');
            Yii::info(json_encode($credentials['authParams']), 'tenantonboard');

            $newConnection = new yii\db\Connection([
                'dsn' => 'mysql:host='.$mysql_host.';dbname=' . $mysql_dbname,
                'username' => $mysql_username,
                'password' => $mysql_password,
                'attributes' => [PDO::ATTR_CASE => PDO::CASE_LOWER]
            ]);
            try {
                $newConnection->open();
                $newCommand = $newConnection->createCommand();
                $newCommand->insert('admin_master', [
                    'adm_is_admin' => 'super_admin',
                    'adm_firstname' => $firstname,
                    'adm_lastname' => $lastname,
                    'adm_username' => $username,
                    'adm_contact' => $contact,
                    'adm_email' => $email,
                    'adm_password' => md5($password),
                    'adm_password_hash' => base64_encode(md5($password)),
                    'adm_timezone_id' => $timezone,
                    'uuid' => $uid,
                    'auto_login_token' => $loginToken
                ])->execute();

                $tenantID = $newCommand->db->lastInsertID;

                $authManager = new DbManager([
                    'db' => $newConnection,
                ]);
                $role = (object)['name' => 'super_admin'];
                $user_id = $tenantID;

                $authManager->assign($role, $user_id);

                $newCommand->insert('ct_tenant_info', [
                    'domain' => $domain,
                    'tenant_uuid' => $uid
                ])->execute();

                $newConnection->close();

            } catch (\Exception $e) {

                Yii::info("================Tenant Add Error ".date('Y-m-d H:i:s')."============", 'tenantonboard');
                Yii::info($e->getMessage(), 'tenantonboard');

                $errCode = $e->getCode();
                return HelperLayouts::apiResponse($errCode, $e->getMessage(), null);
            }
        }
        return HelperLayouts::apiResponse(HttpCode::OK, 'Tenant added Successfully', null);
    }

    public function actionSendData()
    {
        $string = base64_decode($_POST['auth_params']);
        $parts = explode(',', $string);
        $data = [];
        foreach ($parts as $part) {
            $pair = explode('=', $part);
            if (count($pair) == 2) {
                $key = $pair[0];
                $value = $pair[1];
                $data[$key] = $value;
            }
        }
        return HelperLayouts::apiResponse(HttpCode::OK, Yii::t('app', 'request_success'), $data);
    }

    public function actionUpdateTenant()
    {
        header('Access-Control-Allow-Origin: *');
        $uid = Yii::$app->request->post('tenant_uuid');
        $domain = Yii::$app->request->post('domain');
        $firstname = Yii::$app->request->post('firstname');
        $lastname = Yii::$app->request->post('lastname');
        $username = Yii::$app->request->post('username');
        $email = Yii::$app->request->post('email');
        $contact = Yii::$app->request->post('contact');
        $timezone = Yii::$app->request->post('timezone');
        $loginToken = Yii::$app->request->post('login_token');

        $base_path = Yii::$app->params['PROJECT_PATH'];

        Yii::info("\n==== UPDATE START ".date("Y-m-d H:i:s")."===", 'tenantonboard');

        $credentials = Yii::$app->commonHelper->initialGetTenantConfig($domain);
        if ($credentials) {
            $mysql_username = $credentials['authParams']['mysql_username'];
            $mysql_password = $credentials['authParams']['mysql_password'];
            $mysql_dbname = $credentials['authParams']['mysql_dbname'];
            $mysql_host = $credentials['authParams']['mysql_host'];

            $newConnection = new yii\db\Connection([
                'dsn' => 'mysql:host='.$mysql_host.';dbname=' . $mysql_dbname,
                'username' => $mysql_username,
                'password' => $mysql_password,
                'attributes' => [PDO::ATTR_CASE => PDO::CASE_LOWER]
            ]);
            $dataArr = [
                'adm_firstname' => $firstname,
                'adm_lastname' => $lastname,
                'adm_username' => $username,
                'adm_contact' => $contact,
                'adm_email' => $email,
                'adm_timezone_id' => $timezone,
                'auto_login_token' => $loginToken
            ];

            Yii::info("\n====Update Post data ".date("Y-m-d H:i:s")."===", 'tenantonboard');
            Yii::info("\n====" . json_encode($dataArr) . "===", 'tenantonboard');

            if (Yii::$app->request->post('password') !== null) {
                $dataArr['adm_password'] = md5(Yii::$app->request->post('password'));
                $dataArr['adm_password_hash'] = base64_encode(md5(Yii::$app->request->post('password')));
            }
            try {
                $newConnection->open();
                $newCommand = $newConnection->createCommand();
                $newCommand->update('admin_master', $dataArr, ['uuid' => $uid])->execute();
                $newConnection->close();

            } catch (\Exception $e) {
                $errCode = $e->getCode();

                Yii::info("\n==== UPDATE CATCH BLOCK ".date("Y-m-d H:i:s")."===", 'tenantonboard');
                Yii::info($e->getMessage(), 'tenantonboard');

                return HelperLayouts::apiResponse($errCode, $e->getMessage(), null);
            }
        } else {

            Yii::info("\n==== UPDATE DB ERROR ".date("Y-m-d H:i:s")."===", 'tenantonboard');
            Yii::info("\nNO DB FOUND ".date("Y-m-d H:i:s"), 'tenantonboard');

            return HelperLayouts::apiResponse(HttpCode::BAD_REQUEST, 'No Database Found', null);
        }
        return HelperLayouts::apiResponse(HttpCode::OK, 'Tenant Updated Successfully', null);
    }

    public function actionDeleteTenant()
    {
        header('Access-Control-Allow-Origin: *');
        $domain = Yii::$app->request->post('domain');
        $credentials = Yii::$app->commonHelper->initialGetTenantConfig($domain);

        $deleteArg['mysql_username']= $credentials['authParams']['mysql_username'];
        $deleteArg['mysql_password']= $credentials['authParams']['mysql_password'];
        $deleteArg['mysql_dbname']= $credentials['authParams']['mysql_dbname'];
        $deleteArg['mysql_host']= $credentials['authParams']['mysql_host'];
        $deleteArg['mongo_username']= $credentials['authParams']['mongo_username'];
        $deleteArg['mongo_password']= $credentials['authParams']['mongo_password'];
        $deleteArg['mongo_dbname']= $credentials['authParams']['mongo_dbname'];

        $base_path = Yii::$app->params['PROJECT_PATH'];
        $sh_file = $base_path . '/tenant_del.sh';

        if ($credentials) {
            $dataArr = implode(self::ARG_SEPERATOR, $deleteArg) . self::ARG_SEPERATOR . $credentials['tenant_id'] . self::ARG_SEPERATOR . $domain;
            $dataArr = str_replace(PHP_EOL, "", $dataArr);

            Yii::info("\n==== DELETE START ".date("Y-m-d H:i:s")."===", 'tenantonboard');
            Yii::info($dataArr, 'tenantonboard');

            try {
                shell_exec("sudo sh $sh_file $dataArr");
            } catch (\Exception $e) {

                Yii::info("=========DELETE CATCH BLOCK ".date('Y-m-d H:i:s')."=======", 'tenantonboard');
                Yii::info($e->getMessage(), 'tenantonboard');

                return HelperLayouts::apiResponse($e->getCode(), $e->getMessage(), null);
            }
        } else {

            Yii::info("=========DELETE NO DATABASE FOUNd ".date('Y-m-d H:i:s')."=======", 'tenantonboard');

            return HelperLayouts::apiResponse(HttpCode::BAD_REQUEST, 'No Database Found', null);
        }
        return HelperLayouts::apiResponse(HttpCode::OK, 'Tenant Deleted Successfully', null);
    }

    protected function verbs()
    {
        return [
            'tenant-on-board' => ['POST'],
            'send-data' => ['POST'],
            'update-tenant' => ['POST'],
            'delete-tenant' => ['POST']
        ];
    }
}
