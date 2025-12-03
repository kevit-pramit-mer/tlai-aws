<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\modules\ecosmob\admin\assets;

use yii\web\AssetBundle;

/**
 * Class AdminAsset
 *
 * @package app\assets
 */
class AdminAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@app/modules/ecosmob/admin/web/';

    /**
     * @var array
     */
    public $css = [
        'css/admin.css',
    ];

    /**
     * @var array
     */
    public $js
        = [
            'js/active_tab.js',
            'js/progressbar.min.js',
            'js/graph_data.js',
        ];

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
