<?php

namespace app\components;

use Yii;
use yii\mongodb\Connection;

/**
 * Class DynamicMongoDbConnection
 *
 * @package app\components
 */
class DynamicMongoDbConnection extends Connection
{
    public function init()
    {
        $credentials = Yii::$app->commonHelper->getTenantConfig($_SERVER['HTTP_HOST']);
        if ($credentials) {
            $mongodb_host = $credentials['authParams']['mongodb_host'];

            $this->dsn = "mongodb://".$mongodb_host;
            
            // Only set authentication if credentials are provided
            if (!empty($credentials['authParams']['mongodb_username'])) {
                $this->options['username'] = $credentials['authParams']['mongodb_username'];
                $this->options['password'] = $credentials['authParams']['mongodb_password'];
                $this->options['authSource'] = $credentials['authParams']['mongodb_database'];
            }

            $GLOBALS['mongoDBName'] = $credentials['authParams']['mongodb_database'];
            Yii::$app->session->set('tenantID', $credentials['tenant_id']);
            parent::init();
        } else {
            $GLOBALS['mongoDBName'] = 'uctenant';
            echo "Data not Found";
            exit;
        }


    }
}
