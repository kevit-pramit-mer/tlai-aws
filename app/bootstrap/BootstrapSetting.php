<?php

namespace app\bootstrap;

use app\models\TenantModuleConfig;
use Yii;
use yii\base\Application;
use yii\base\BootstrapInterface;
use yii\rbac\DbManager;

/**
 * Class BootstrapSetting
 *
 * @package app\bootstrap
 */
class BootstrapSetting implements BootstrapInterface
{

    /**
     * Bootstraping implementation forsetting homeUrl bsed on User type
     * Bootstrap method to be called during application bootstrap stage.
     * @param Application $app the application currently running
     */
    public function bootstrap($app)
    {
        $GLOBALS['flash'] = 'HEADER';
        $GLOBALS['brand-image'] = "/theme/assets/images/flat-bg.jpg";
        $GLOBALS['image-path'] = "/theme/assets/images";
        // TODO :: Fetch Permission according to User,
        // TODO Here We are fetching User permissions based on User Identity on its primary key, Change it accordingly
        /* homeUrl based on user type  breadcrumb */

        /*echo "<pre>";
        print_r(Yii::$app->user->identity);
        echo "</pre>";
        exit;*/
        $GLOBALS['permissions'] = array();
        $GLOBALS['tenantID'] = Yii::$app->session->get('tenantID');
        //$GLOBALS['mongoDBName'] = 'uc_tKWz13eYZ';
        $GLOBALS['tragoSipServer'] = $_SERVER['HTTP_HOST'];
        $GLOBALS['tragoSipPort'] = '5060';

        Yii::$app->commonHelper->storeLicenseData($_SERVER['HTTP_HOST']);
        if (!Yii::$app->user->isGuest && isset(Yii::$app->user->identity->adm_id)) {

            /** @var DbManager $dbManager */
            $dbManager = new DbManager();
            /** @var array $permissions */
            $permissions = array_keys($dbManager->getPermissionsByUser(Yii::$app->user->identity->adm_id));
            $GLOBALS['permissions'] = $permissions;
        }

    }
}
