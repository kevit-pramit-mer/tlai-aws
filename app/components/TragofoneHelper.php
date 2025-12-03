<?php

namespace app\components;

use app\modules\ecosmob\globalconfig\models\GlobalConfig;
use Yii;
use yii\base\Component;

/**
 * Class TragofoneHelper
 *
 * @package app\components
 */
class TragofoneHelper extends Component
{
    public $username;

    public $password;

    public $token;

    public $url;
    
    public function init(){
        $this->username = GlobalConfig::getValueByKey('TRAGOFONE_USERNAME');
        $this->password = GlobalConfig::getValueByKey('TRAGOFONE_PASSWORD');
        $this->token = '';
        $this->url = GlobalConfig::getValueByKey('TRAGOFONE_API_URL');
    }

    public function adminAuthentication()
    {
        $url = $this->url.'admin/login';

        $headers = [
            'Content-Type: application/json'
        ];

        $data = [
            'username' => $this->username,
            'password' => $this->password
        ];

        return $this->executeCurl($url, $headers, $data);
    }

    public function customerAuthentication()
    {
        $admin = $this->adminAuthentication();

        if(!empty($admin)) {

            $admin = json_decode($admin, true);

            if (!empty($admin['access_token'])) {
                $url = $this->url.'customer/login';

                $headers = [
                    'Authorization: Bearer ' . $admin['access_token'],
                    'Content-Type: application/json'
                ];

                $data = [
                    'username' =>  Yii::$app->session->get('tragofoneUsername'),
                    'password' => Yii::$app->session->get('tragofonePassword'),
                    'device_type' => 'web'
                ];

                return $this->executeCurl($url, $headers, $data);
            }
        }
        return '';
    }

    public function getCustomerToken(){
        $token = $this->customerAuthentication();

        if(!empty($token)) {

            $token = json_decode($token, true);

            if (!empty($token['access_token'])) {
                return $token['access_token'];
            }
        }
        return '';
    }


    /**
     * Send Curl request
     *
     * @param $url
     * @param $headers
     * @param array $data
     * @param bool $post
     *
     * @return mixed
     */
    public function executeCurl($url, $headers, $data = [], $post = true)
    {
        try {
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_URL, $url);
            if ($post) {
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            }

            $result = curl_exec($ch);

            curl_close($ch);

            return $result;
        }catch(\Exception $e){

        }
    }

    /**
     * @param $token
     * @param $data
     *
     * @return mixed
     */
    public function create($data)
    {
        $token = $this->getCustomerToken();//'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJjdXN0X2lkIjo2MTQsImN1c3RfZmlyc3RuYW1lIjoiSXNoYSIsImN1c3RfbGFzdG5hbWUiOiJEYWJoaSIsImN1c3RfY21wX25hbWUiOiJlY29zbW9iIiwiY3VzdF9jb250YWN0X2VtYWlsIjoiZGVtb0BnbWFpbC5jb20iLCJkZXZpY2VfdG9rZW4iOiIxLjM4Ljk0LjQ1IiwiZGV2aWNlX3R5cGUiOiJ3ZWIiLCJyYW5kb21fa2V5IjoiNTE4MTY5ODY0ODQyOSJ9.eWAS4msBCob_G3dAUhzyeHFngdUtNt7nPtFNy_4XDo8';

        $url = $this->url.'customer/user/create';

        $headers = array
        (
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json'
        );

        return $this->executeCurl($url, $headers, $data);
    }

    public function update($data)
    {
        $token = $this->getCustomerToken();//'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJjdXN0X2lkIjo2MTQsImN1c3RfZmlyc3RuYW1lIjoiSXNoYSIsImN1c3RfbGFzdG5hbWUiOiJEYWJoaSIsImN1c3RfY21wX25hbWUiOiJlY29zbW9iIiwiY3VzdF9jb250YWN0X2VtYWlsIjoiZGVtb0BnbWFpbC5jb20iLCJkZXZpY2VfdG9rZW4iOiIxLjM4Ljk0LjQ1IiwiZGV2aWNlX3R5cGUiOiJ3ZWIiLCJyYW5kb21fa2V5IjoiNTE4MTY5ODY0ODQyOSJ9.eWAS4msBCob_G3dAUhzyeHFngdUtNt7nPtFNy_4XDo8';

        $url = $this->url.'customer/user/update';

        $headers = array
        (
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json'
        );

        return $this->executeCurl($url, $headers, $data);
    }

    public function delete($data)
    {
        $token = $this->getCustomerToken();//'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJjdXN0X2lkIjo2MTQsImN1c3RfZmlyc3RuYW1lIjoiSXNoYSIsImN1c3RfbGFzdG5hbWUiOiJEYWJoaSIsImN1c3RfY21wX25hbWUiOiJlY29zbW9iIiwiY3VzdF9jb250YWN0X2VtYWlsIjoiZGVtb0BnbWFpbC5jb20iLCJkZXZpY2VfdG9rZW4iOiIxLjM4Ljk0LjQ1IiwiZGV2aWNlX3R5cGUiOiJ3ZWIiLCJyYW5kb21fa2V5IjoiNTE4MTY5ODY0ODQyOSJ9.eWAS4msBCob_G3dAUhzyeHFngdUtNt7nPtFNy_4XDo8';

        $url = $this->url.'customer/user/delete';

        $headers = array
        (
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json'
        );

        return $this->executeCurl($url, $headers, $data);
    }

    public function updateConfig($data)
    {
        $token = $this->getCustomerToken();//'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJjdXN0X2lkIjo2MTQsImN1c3RfZmlyc3RuYW1lIjoiSXNoYSIsImN1c3RfbGFzdG5hbWUiOiJEYWJoaSIsImN1c3RfY21wX25hbWUiOiJlY29zbW9iIiwiY3VzdF9jb250YWN0X2VtYWlsIjoiZGVtb0BnbWFpbC5jb20iLCJkZXZpY2VfdG9rZW4iOiIxLjM4Ljk0LjQ1IiwiZGV2aWNlX3R5cGUiOiJ3ZWIiLCJyYW5kb21fa2V5IjoiNTE4MTY5ODY0ODQyOSJ9.eWAS4msBCob_G3dAUhzyeHFngdUtNt7nPtFNy_4XDo8';

        $url = $this->url.'customer/user/update-configurations';

        $headers = array
        (
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json'
        );

        return $this->executeCurl($url, $headers, $data);
    }

    public function getUser($data, $token)
    {
        //$token = $this->getCustomerToken();//'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJjdXN0X2lkIjo2MTQsImN1c3RfZmlyc3RuYW1lIjoiSXNoYSIsImN1c3RfbGFzdG5hbWUiOiJEYWJoaSIsImN1c3RfY21wX25hbWUiOiJlY29zbW9iIiwiY3VzdF9jb250YWN0X2VtYWlsIjoiZGVtb0BnbWFpbC5jb20iLCJkZXZpY2VfdG9rZW4iOiIxLjM4Ljk0LjQ1IiwiZGV2aWNlX3R5cGUiOiJ3ZWIiLCJyYW5kb21fa2V5IjoiNTE4MTY5ODY0ODQyOSJ9.eWAS4msBCob_G3dAUhzyeHFngdUtNt7nPtFNy_4XDo8';

        $url = $this->url.'customer/user/list';

        $headers = array
        (
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json'
        );

        return $this->executeCurl($url, $headers, $data);
    }

    public function getUserConfig($data, $token)
    {
        $url = $this->url.'customer/user/get-configurations';

        $headers = array
        (
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json'
        );

        return $this->executeCurl($url, $headers, $data);
    }

    /**
     * @param $token
     * @param $data
     *
     * @return mixed
     */
    public function phonebookCreate($data)
    {
        $token = $this->getCustomerToken();

        $url = $this->url.'customer/enterprise/create';

        $headers = array
        (
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json'
        );

        return $this->executeCurl($url, $headers, $data);
    }

    /**
     * @param $token
     * @param $data
     *
     * @return mixed
     */
    public function phonebookUpdate($data)
    {
        $token = $this->getCustomerToken();

        $url = $this->url.'customer/enterprise/update';

        $headers = array
        (
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json'
        );

        return $this->executeCurl($url, $headers, $data);
    }

    /**
     * @param $token
     * @param $data
     *
     * @return mixed
     */
    public function phonebookDelete($edId)
    {
        $token = $this->getCustomerToken();

        $url = $this->url . 'customer/enterprise/delete';

        $headers = array
        (
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json'
        );

        $data = ["ed_id" => $edId];
        try {
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

            $result = curl_exec($ch);

            curl_close($ch);

            return $result;
        } catch (\Exception $e) {

        }

    }

    public function customerAuthenticationForCron($username, $password)
    {
        $admin = $this->adminAuthentication();

        if(!empty($admin)) {

            $admin = json_decode($admin, true);

            if (!empty($admin['access_token'])) {
                $url = $this->url.'customer/login';

                $headers = [
                    'Authorization: Bearer ' . $admin['access_token'],
                    'Content-Type: application/json'
                ];

                $data = [
                    'username' =>  $username,
                    'password' => $password,
                    'device_type' => 'web'
                ];

                return $this->executeCurl($url, $headers, $data);
            }
        }
        return '';
    }

    public function getCustomerTokenForCron($username, $password){
        $token = $this->customerAuthenticationForCron($username, $password);

        if(!empty($token)) {

            $token = json_decode($token, true);

            if (!empty($token['access_token'])) {
                return $token['access_token'];
            }
        }
        return '';
    }

    public function getUserForCron($data, $token)
    {
        $url = $this->url.'customer/user/list';

        $headers = array
        (
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json'
        );

        return $this->executeCurl($url, $headers, $data);
    }

}