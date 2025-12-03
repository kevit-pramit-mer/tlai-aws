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
            $mongo_host = $credentials['authParams']['mongo_host'];

            $this->dsn = "mongodb://".$mongo_host;
            $this->options['username'] = $credentials['authParams']['mongo_username'];
            $this->options['password'] = $credentials['authParams']['mongo_password'];
            $this->options['authSource'] = $credentials['authParams']['mongo_dbname'];

            $GLOBALS['mongoDBName'] = $credentials['authParams']['mongo_dbname'];
            Yii::$app->session->set('tenantID', $credentials['tenant_id']);
            parent::init();
        } else {
            $GLOBALS['mongoDBName'] = 'uctenant';
            echo "Data not Found";
            exit;
        }


    }
}
