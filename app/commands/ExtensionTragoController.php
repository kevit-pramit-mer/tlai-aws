<?php

namespace app\commands;

use app\components\DynamicDbConnection;
use app\modules\ecosmob\extension\models\Extension;
use yii\console\Controller;
use yii\console\controllers\MigrateController;
use yii\db\Connection;
use Yii;
use yii\db\Exception;

/**
 * Class ExtensionTragoController
 *
 * @package app\commands
 */
class ExtensionTragoController extends Controller
{

    /**
     * php yii extension-trago/update-trago-status
     *
     */
    public function actionUpdateTragoStatus()
    {
        $masterDb = Yii::$app->masterdb->createcommand("SELECT organisation_domain FROM uc_tenants WHERE status = '1'")->queryAll();
        if (!empty($masterDb)) {
            foreach ($masterDb as $_masterDb) {
                try {
                    $credentials = Yii::$app->commonHelper->initialGetTenantConfig($_masterDb['organisation_domain']);
                    if (!empty($credentials)) {
                        Yii::$app->db->close();
                        Yii::$app->db->dsn = 'mysql:host=' . $credentials['authParams']['mysql_host'] . ';dbname=' . $credentials['authParams']['mysql_dbname'];
                        Yii::$app->db->username = $credentials['authParams']['mysql_username'];
                        Yii::$app->db->password = $credentials['authParams']['mysql_password'];
                        Yii::$app->db->open();
                        Extension::getTrgofoneStatus($credentials['tragofone_username'], $credentials['tragofone_password']);
                    }
                } catch (Exception $e) {
                    continue;
                }
            }
        }
    }

}
