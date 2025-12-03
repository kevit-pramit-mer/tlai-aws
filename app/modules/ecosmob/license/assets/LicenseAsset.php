<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\modules\ecosmob\license\assets;

use yii\web\AssetBundle;

/**
 * Class LicenseAsset
 *
 * @package app\assets
 */
class LicenseAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@app/modules/ecosmob/license/web/';

    /**
     * @var array
     */
    public $css = [];

    /**
     * @var array
     */
    public $js = [];

    /**
     * @var array
     */
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'app\assets\AuthAsset',
    ];

    /**
     * @var array
     */
    public $jsOptions = [];

    /**
     * @var array
     */
    public $cssOptions = [];

    /**
     * @var array
     */
    public $publishOptions = [];
}
