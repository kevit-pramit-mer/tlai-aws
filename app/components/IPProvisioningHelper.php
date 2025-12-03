<?php

namespace app\components;

use app\modules\ecosmob\globalconfig\models\GlobalConfig;
use Exception;
use yii\base\Component;

/**
 * Class TragofoneHelper
 *
 * @package app\components
 */
class IPProvisioningHelper extends Component
{
    public $url;

    public function init()
    {
        $this->url = GlobalConfig::getValueByKey('GENIEACS_API_URL');
    }

    public function getDeviceList()
    {
        $url = $this->url . 'admin/login';

        $headers = [
            'Content-Type: application/json'
        ];

        $data = [
            'username' => $this->username,
            'password' => $this->password
        ];

        return $this->executeCurl($url, $headers, $data);
    }

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
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);

            curl_close($ch);

            if ($error) {
                return [
                    'status_code' => null,
                    'response' => "CURL Error: " . $error,
                ];
            }

            return [
                'status_code' => $httpCode,
                'response' => $result,
            ];
        } catch (Exception $e) {
            return [
                'status_code' => null,
                'response' => 'Error: ' . $e->getMessage(),
            ];
        }
    }

    public function updateDeviceConfiguration($deviceId, $data)
    {
        $url = $this->url . 'devices/'.$deviceId.'/tasks?connection_request';
        $headers = [
            'Content-Type: application/json'
        ];

        return $this->executeCurl($url, $headers, $data);
    }

    public function getDeviceByMACAddress($macAddress)
    {
        $url = $this->url . 'devices?query='.urlencode('{"Device.LAN.MACAddress": "'.$macAddress.'"}');

        return $this->executeCurl($url, [], [], false);
    }

    public function rebootDevice($deviceId)
    {
        $url = $this->url . 'devices/'.$deviceId.'/tasks?connection_request';
        $headers = [
            'Content-Type: application/x-www-form-urlencoded'
        ];
        $data = ['name' => 'reboot'];

        return $this->executeCurl($url, $headers, $data);
    }

    public function resetDevice($deviceId)
    {
        $url = $this->url . 'devices/'.$deviceId.'/tasks?connection_request';
        $headers = [
            'Content-Type: application/x-www-form-urlencoded'
        ];
        $data = ['name' => 'factoryReset'];

        return $this->executeCurl($url, $headers, $data);
    }

}
