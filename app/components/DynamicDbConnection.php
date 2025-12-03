<?php

namespace app\components;

use Yii;
use yii\db\Connection;
use yii\web\Cookie;
use app\modules\ecosmob\admin\models\AdminMaster;
use app\modules\ecosmob\extension\models\Extension;

/**
 * Class DynamicDBConnection
 *
 * @package app\components
 */
class DynamicDbConnection extends Connection
{
    public function init()
    {
        $credentials = Yii::$app->commonHelper->getTenantConfig($_SERVER['HTTP_HOST']);
        if ($credentials) {
            $mysql_username = $credentials['authParams']['mysql_username'];
            $mysql_password = $credentials['authParams']['mysql_password'];
            $mysql_dbname = $credentials['authParams']['mysql_dbname'];
            $mysql_host = $credentials['authParams']['mysql_host'];

            $this->dsn = 'mysql:host='.$mysql_host.';dbname=' . $mysql_dbname;
            $this->username = $mysql_username;
            $this->password = $mysql_password;
            if(Yii::$app->session->get('refresh-time') == '') {
                Yii::$app->session->set('refresh-time', '00:05');
            }
            Yii::$app->session->set('tenantID', $credentials['tenant_id']);
            Yii::$app->session->set('isMySqlConnected', true);
            Yii::$app->session->set('isTragofone', $credentials['tragofone_status']);
            Yii::$app->session->set('tragofoneUsername', $credentials['tragofone_username']);
            Yii::$app->session->set('tragofonePassword', $credentials['tragofone_password']);
            Yii::$app->session->set('tenant_code', $credentials['tenant_code']);
            $getLogo = Yii::$app->commonHelper->getLogo($_SERVER['HTTP_HOST']);
            Yii::$app->session->set('getLogo', $getLogo);
            Yii::$app->session->set('enable_sso', $credentials['enable_sso']);
            Yii::$app->session->set('SSO_provider', $credentials['SSO_provider']);
        } else {
            echo '<div style="text-align:center; margin-top: 20%"> <h2>Data not Found</h2></div>';
            exit;
        }
        parent::init();
    }
}
