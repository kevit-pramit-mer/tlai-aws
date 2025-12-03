<?php

namespace app\components;

use Redis;
use Yii;
use yii\base\Component;

/**
 * Class SipProxyServerApi
 *
 * @package app\components
 */
class SipProxyServerApi extends Component
{
    /**
     * @var string
     */
    public $server = '127.0.0.1';

    /**
     * @var string
     */
    public $port = '80';

    /**
     * dispatcher_reload will send Json data to kamailio proxy server
     */
    public function methodDispatcherReloadKamailio()
    {
        /** @var array $dispatcherDataArray */
        $dispatcherDataArray = [
            'jsonrpc' => '2.0',
            'method' => 'dispatcher.reload',
        ];
        /** @var string $dispatcherData */
        $dispatcherData = json_encode($dispatcherDataArray);
        $this->sendKamailioApi($dispatcherData);
    }

    /**
     * @param string $data
     */
    private function sendKamailioApi($data)
    {

        $host = "http://" . $this->server . ":" . $this->port . "/RPC2";

        $ch = curl_init($host);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);
        curl_close($ch);
    }

    /**
     * permission_address_reload will send Json data to kamailio proxy server
     *
     */
    public function methodPermissionAddressReloadKamailio()
    {
        /** @var array $permissionAddressDataArray */
        $permissionAddressDataArray = [
            'jsonrpc' => '2.0',
            'method' => 'permissions.addressReload',
        ];
        /** @var string $permissionAddressData */
        $permissionAddressData = json_encode($permissionAddressDataArray);
        $this->sendKamailioApi($permissionAddressData);
    }

    /**
     * dispatcher_reload will send Json data to kamailio proxy server
     */
    public function methodDispatcherReloadOpenSips()
    {
        $host = "http://" . $this->server . ":" . $this->port . "/json/ds_reload";

        $ch = curl_init($host);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);
        curl_close($ch);
    }

    /**
     * dispatcher_reload will send Json data to kamailio proxy server
     */
    public function methodPermissionAddressReloadOpenSips()
    {
        $host = "http://" . $this->server . ":" . $this->port . "/json/address_reload";

        $ch = curl_init($host);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_exec($ch);
        curl_close($ch);
    }

    /**
     * @param $key
     * @param $val
     */
    public function setRedisValue($key, $val)
    {
        $hostname = Yii::$app->components['redis']['hostname'];
        $port = Yii::$app->components['redis']['port'];

        $redis = new Redis();
        $redis->connect($hostname, $port);
        $redis->set($key, $val);
    }

    /**
     * @param $key
     */
    public function deleteRedisValue($key)
    {
        $hostname = Yii::$app->components['redis']['hostname'];
        $port = Yii::$app->components['redis']['port'];

        $redis = new Redis();
        $redis->connect($hostname, $port);
        $redis->delete($key);
    }


    /**
     * @param $key
     * @return bool|string
     */
    public function getRedisValue($key)
    {
        $hostname = Yii::$app->components['redis']['hostname'];
        $port = Yii::$app->components['redis']['port'];

        $redis = new Redis();
        $redis->connect($hostname, $port);
        return $redis->get($key);
    }

}