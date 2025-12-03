<?php

namespace app\commands;

use app\components\DynamicDbConnection;
use yii\console\Controller;
use yii\console\controllers\MigrateController;
use yii\db\Connection;
use Yii;
use yii\db\Exception;

/**
 * Class ConsoleDynamicDbCommandController
 *
 * @package app\commands
 */
class DynamicDbMigrationController extends Controller
{

    /**
     * php yii dynamic-db-migration/index
     *
     */
    public function actionIndex()
    {
        $masterDb = Yii::$app->masterdb->createcommand("SELECT organisation_domain FROM uc_tenants WHERE status = '1' AND is_deleted = '0'")->queryAll();
        if(!empty($masterDb)) {
            foreach ($masterDb as $_masterDb) {
                try {
                    $credentials = Yii::$app->commonHelper->initialGetTenantConfig($_masterDb['organisation_domain']);
                    if (!empty($credentials)) {
                        Yii::$app->masterdb->close();
                        Yii::$app->masterdb->dsn = 'mysql:host=' . $credentials['authParams']['mysql_host'] . ';dbname=' . $credentials['authParams']['mysql_dbname'];
                        Yii::$app->masterdb->username = $credentials['authParams']['mysql_username'];
                        Yii::$app->masterdb->password = $credentials['authParams']['mysql_password'];
                        Yii::$app->masterdb->open();
                        Yii::$app->runAction('migrate', ['migrationPath' => '@app/migrations/', 'interactive' => 0, 'db' => 'masterdb']);
                    }
                }catch(Exception $e){
                    continue;
                }
            }
        }
    }

}
