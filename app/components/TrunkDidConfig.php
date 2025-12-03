<?php

namespace app\components;

use app\models\TenantModuleConfig;
use yii\base\Application;
use yii\base\BootstrapInterface;

/**
 * Class LanguageSelector
 *
 * @package app\components
 */
class TrunkDidConfig implements BootstrapInterface
{
    /**
     * Set language into Cookie
     *
     * @param Application $app
     */
    public function bootstrap($app)
    {
        TenantModuleConfig::trunkDidRoutingPermission();
    }
}
