<?php

namespace app\components;

use Yii;
use yii\base\Component;

/**
 * Class FreeswitchSofiaApi
 *
 * @package app\components
 */
class FreeswitchSofiaApi extends Component
{
    /**
     * @var string
     */
    public $host = "127.0.0.1";

    /**
     * @var string
     */
    public $port = "8021";

    /**
     * @var string
     */
    public $passkey = "ClueCon";

    /**
     * @var string
     */
    public $profile = "";

    /**
     * @return array
     */
    public function methodSofiaRecover()
    {
        /** @var $result */
        $result = [];
        $result['resultCode'] = 000;
        $result['resultStatus'] = 'SUCCESS';

        $fp = $this->createEventSocket();
        $cmd = "api sofia recover";

        $response = trim($this->eventSocketRequest($fp, $cmd), "\n");

        if (strncmp($response, "-ERR", 4) == 0) {
            $result['resultCode'] = 999;
            $result['resultStatus'] = "FAILED";
        }
        fclose($fp);

        return $result;
    }

    /**
     * @return bool|resource
     */
    private function createEventSocket()
    {
        $this->host = Yii::$app->params['FREESWITCH_SOFIA_API_HOST'];
        $this->port = Yii::$app->params['FREESWITCH_SOFIA_API_PORT'];
        $fp = fsockopen($this->host, $this->port, $errorNumber, $errorDescription) or die("Connection to $this->host failed");
        socket_set_blocking($fp, false);
        if ($fp) {
            while (!feof($fp)) {
                $buffer = fgets($fp, 1024);
                usleep(100);
                if (trim($buffer) == "Content-Type: auth/request") {
                    fputs($fp, "auth $this->passkey\n\n");
                    break;
                }
            }

            return $fp;
        } else {
            return false;
        }
    }

    /**
     * @param $fp
     * @param $cmd
     * @return string
     */
    private function eventSocketRequest($fp, $cmd)
    {
        if ($fp) {
            fputs($fp, $cmd . "\n\n");
            usleep(100); //allow time for response

            $response = "";
            $i = 0;
            $contentLength = 0;
            while (!feof($fp)) {
                $buffer = fgets($fp, 4096);
                if ($contentLength > 0) {
                    $response .= $buffer;
                }
                //if content length is already don't process again
                if ($contentLength == 0) {
                    //run only if buffer has content
                    if (strlen(trim($buffer)) > 0) {
                        $temporary = explode(":", trim($buffer));
                        if ($temporary[0] == "Content-Length") {
                            $contentLength = trim($temporary[1]);
                        }
                    }
                }
                usleep(100);
                if ($i > 10000) {
                    break;
                }

                if ($contentLength > 0) {
                    if (strlen($response) >= $contentLength) {
                        break;
                    }
                }
                $i++;
            }

            return $response;
        } else {
            return false;
        }
    }

    /**
     * @param string $profileName
     * @return array
     */
    public function methodSofiacontact($profileName = '')
    {

        /** @var $result */
        $result = [];
        $result['resultCode'] = 000;
        $result['resultStatus'] = 'SUCCESS';

        $profileName = (empty($profileName) ? $this->profile : $profileName);

        if (empty($profileName)) {
            $result['resultCode'] = 998;
            $result['resultStatus'] = 'FAILED';
        } else {
            $fp = $this->createEventSocket();
            $host = Yii::$app->components['fsofiapi']['host'];

            $cmd = sprintf("api sofia_contact %s@%s", $profileName, $host);

            $response = trim($this->eventSocketRequest($fp, $cmd), "\n");

            if (!$response || strncmp($response, '-ERR', 4) == 0) {
                $result['resultCode'] = 999;
                $result['resultStatus'] = 'FAILED';
            } else {
                $result['data'] = $response;
            }
            fclose($fp);
        }
        return $result;
    }

    /**
     * @param $data
     * @param string $af_file
     * @return array
     */
    public function methodSofiaOriginate($data = '', $af_file)
    {

        /** @var $result */
        $result = [];
        $result['resultCode'] = 000;
        $result['resultStatus'] = 'SUCCESS';

        $data = (empty($data) ? $this->profile : $data);

        if (empty($data)) {
            $result['resultCode'] = 998;
            $result['resultStatus'] = 'FAILED';
        } else {
            $fp = $this->createEventSocket();
            $cmd = sprintf("api originate {origination_caller_id_number=record_session}%s %s", $data, $af_file);
//            $cmd = sprintf("api originate {origination_caller_id_number=1000}%s %s", $data, $af_file);

            $response = trim($this->eventSocketRequest($fp, $cmd), "\n");

            if (!$response || strncmp($response, '-ERR', 4) == 0) {
                $result['resultCode'] = 999;
                $result['resultStatus'] = 'FAILED';
            }
            fclose($fp);
        }

        return $result;

    }

    /**
     * @param $af_file
     * @param string $extension
     * @return array
     */
    public function methodSofiaOriginateNew($af_file, $extension = "")
    {

        /** @var $result */
        $result = [];
        $result['resultCode'] = 000;
        $result['resultStatus'] = 'SUCCESS';

        $fp = $this->createEventSocket();
        $cmd = sprintf("api originate {origination_caller_id_number=record_session,call_number=$extension}%s", $af_file);
        $response = trim($this->eventSocketRequest($fp, $cmd), "\n");

        if (!$response || strncmp($response, '-ERR', 4) == 0) {
            $result['resultCode'] = 999;
            $result['resultStatus'] = 'FAILED';
        }
        fclose($fp);

        return $result;

    }

    /**
     * @param string $profileName
     * @return array
     */
    public function methodSofiaProfileStart($profileName = '')
    {

        /** @var $result */
        $result = [];
        $result['resultCode'] = 000;
        $result['resultStatus'] = 'SUCCESS';

        $profileName = (empty($profileName) ? $this->profile : $profileName);

        if (empty($profileName)) {
            $result['resultCode'] = 998;
            $result['resultStatus'] = 'FAILED';
        } else {
            $fp = $this->createEventSocket();
            $cmd = sprintf("api sofia profile %s start", $profileName);

            $response = trim($this->eventSocketRequest($fp, $cmd), "\n");

            if (!$response || strncmp($response, '-ERR', 4) == 0) {
                $result['resultCode'] = 999;
                $result['resultStatus'] = 'FAILED';
            }
            fclose($fp);
        }

        return $result;
    }

    /**
     * @param string $profileName
     * @return array
     */
    public function methodReloadSofiaProfile($profileName = '')
    {
        /** @var $result */
        $result = [];
        $result['resultCode'] = 000;
        $result['resultStatus'] = 'SUCCESS';

        $profileName = (empty($profileName) ? $this->profile : $profileName);

        if (empty($profileName)) {
            $result['resultCode'] = 998;
            $result['resultStatus'] = 'FAILED';
        } else {
            $fp = $this->createEventSocket();
            //$cmd = sprintf("api sofia profile %s rescan reloadxml", $profileName);
            $cmd = "api sofia profile ip rescan";

            $response = trim($this->eventSocketRequest($fp, $cmd), "\n");

            if (strncmp($response, "-ERR", 4) == 0) {
                $result['resultCode'] = 999;
                $result['resultStatus'] = 'FAILED';
            }
            fclose($fp);
        }

        return $result;
    }

    /**
     * @param string $profileName
     * @return array
     */
    public function removeOldSofiaProfile($profileName = '')
    {
        /** @var $result */
        $result = [];
        $result['resultCode'] = 000;
        $result['resultStatus'] = 'SUCCESS';

        $profileName = (empty($profileName) ? $this->profile : $profileName);

        if (empty($profileName)) {
            $result['resultCode'] = 998;
            $result['resultStatus'] = 'FAILED';
        } else {
            $fp = $this->createEventSocket();
            //$cmd = sprintf("api sofia profile %s rescan reloadxml", $profileName);
            //$cmd = "api sofia profile ip rescan";
            $cmd = "api sofia profile ip killgw " . $profileName;

            $response = trim($this->eventSocketRequest($fp, $cmd), "\n");

            if (strncmp($response, "-ERR", 4) == 0) {
                $result['resultCode'] = 999;
                $result['resultStatus'] = 'FAILED';
            }
            fclose($fp);
        }

        return $result;
    }

    /**
     * @return array
     */
    public function methodReloadAcl()
    {
        /** @var $result */
        $result = [];
        $result['resultCode'] = 000;
        $result['resultStatus'] = 'SUCCESS';

        $fp = $this->createEventSocket();
        $cmd = "api reloadacl";

        $response = trim($this->eventSocketRequest($fp, $cmd), "\n");

        if (strncmp($response, "-ERR", 4) == 0) {
            $result['resultCode'] = 999;
            $result['resultStatus'] = 'FAILED';
        }
        fclose($fp);

        return $result;
    }

    /**
     * @param string $profileName
     * @param string $gatewayName
     * @return array
     */
    public function methodUpdateSofiaProfile($profileName = '', $gatewayName)
    {
        /** @var $result */
        $result = [];
        $result['resultCode'] = 000;
        $result['resultStatus'] = 'SUCCESS';

        $profileName = (empty($profileName) ? $this->profile : $profileName);

        if (empty($profileName) || empty($gatewayName)) {
            $result['resultCode'] = 998;
            $result['resultStatus'] = 'FAILED';
        } else {
            $fp = $this->createEventSocket();
            //$cmd = sprintf("api sofia profile %s killgw %s", $profileName, $gatewayName);
            //$cmd = sprintf("api sofia profile %s killgw %s", $profileName, $gatewayName);
            $cmd = "api sofia profile ip rescan";

            $response = trim($this->eventSocketRequest($fp, $cmd), "\n");

            if (strncmp($response, "-ERR", 4) == 0) {
                $result['resultCode'] = 999;
                $result['resultStatus'] = 'FAILED';
            } else {
                $cmd = sprintf("api sofia profile %s rescan", $profileName);
                $response = trim($this->eventSocketRequest($fp, $cmd), "\n");

                if (strncmp($response, "-ERR", 4) == 0) {
                    $result['resultCode'] = 999;
                    $result['resultStatus'] = 'FAILED';
                }
            }
            fclose($fp);
        }

        return $result;
    }

    /**
     * @param $id
     * @param $domainName
     * @param $uuid
     * @return array
     */
    public function methodVoicemailRead($id, $domainName, $uuid)
    {

        /** @var $result */
        $result = [];
        $result['resultCode'] = 000;
        $result['resultStatus'] = 'SUCCESS';

        $fp = $this->createEventSocket();
        // api vm_read <id>@<domain>[/profile] <read|unread> [<uuid>]
        $cmd = 'api vm_read' . ' ' . $id . '@' . $domainName . ' ' . 'read' . ' ' . $uuid;

        $response = trim($this->eventSocketRequest($fp, $cmd), "\n");

        if (strncmp($response, "-ERR", 4) == 0) {
            $result['resultCode'] = 999;
            $result['resultStatus'] = 'FAILED';
        }
        fclose($fp);

        return $result;
    }

    /**
     * @param $id
     * @param $domainName
     * @param $uuid
     * @return array
     */
    public function methodVoicemailDelete($id, $domainName, $uuid)
    {

        /** @var $result */
        $result = [];
        $result['resultCode'] = 000;
        $result['resultStatus'] = 'SUCCESS';

        $fp = $this->createEventSocket();
        // api vm_delete <id>@<domain>[/profile] [<uuid>]
        $cmd = 'api vm_delete' . ' ' . $id . '@' . $domainName . ' ' . $uuid;

        $response = trim($this->eventSocketRequest($fp, $cmd), "\n");

        if (strncmp($response, "-ERR", 4) == 0) {
            $result['resultCode'] = 999;
            $result['resultStatus'] = 'FAILED';
        }
        fclose($fp);

        return $result;
    }

    /**
     * @param $id
     * @param $domainName
     * @return array
     */
    public function methodVoicemailDeleteAll($id, $domainName)
    {

        /** @var $result */
        $result = [];
        $result['resultCode'] = 000;
        $result['resultStatus'] = 'SUCCESS';

        $fp = $this->createEventSocket();
        // api vm_delete <id>@<domain>[/profile]
        $cmd = 'api vm_delete' . ' ' . $id . '@' . $domainName;

        $response = trim($this->eventSocketRequest($fp, $cmd), "\n");

        if (strncmp($response, "-ERR", 4) == 0) {
            $result['resultCode'] = 999;
            $result['resultStatus'] = 'FAILED';
        }
        fclose($fp);

        return $result;
    }

    /**
     * @param string $action
     * @param string $queueName
     * @return array
     */
    public function methodReloadQueue($action = "", $queueName = '')
    {
        /** @var $result */
        $result = [];
        $result['resultCode'] = 000;
        $result['resultStatus'] = 'SUCCESS';

        if (empty($queueName)) {
            $result['resultCode'] = 998;
            $result['resultStatus'] = 'FAILED';
        } else {
            $fp = $this->createEventSocket();
            if ($action == "create")
                $cmd = "api callcenter_config queue load " . $queueName;
            else if ($action == "update")
                $cmd = "api callcenter_config queue reload " . $queueName;
            else
                $cmd = "api callcenter_config queue unload " . $queueName;

            $response = trim($this->eventSocketRequest($fp, $cmd), "\n");

            if (strncmp($response, "-ERR", 4) == 0) {
                $result['resultCode'] = 999;
                $result['resultStatus'] = 'FAILED';
            }
            fclose($fp);
        }

        return $result;
    }

    /**
     * @param string $profileName
     * @return array
     */
    public function getTrunkStatus($profileName = '')
    {
        $trunkStatus = '0';

        $profileName = (empty($profileName) ? $this->profile : $profileName);

        if (!empty($profileName)) {

            $fp = $this->createEventSocket();
            //$cmd = sprintf("api sofia status gateway %s rescan reloadxml", $profileName);
            $cmd = "api sofia status gateway " . $profileName;

            $response = trim($this->eventSocketRequest($fp, $cmd), "\n");

            if (strncmp($response, "-ERR", 4) == 0) {
                $trunkStatus = '0';
            } else {
                $response = explode("\n", $response);
                if(isset($response[20])){
                    if (str_contains($response[20], 'Status')) {
                        if(trim(array_filter(explode(" ", $response[20]))[2]) == 'UP'){
                            $trunkStatus = '1';
                        }
                    }
                }
            }
            fclose($fp);
        }

        return $trunkStatus;
    }
}
