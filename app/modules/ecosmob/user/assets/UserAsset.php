<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\modules\ecosmob\user\assets;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * Class UserAsset
 *
 * @package app\assets
 */
class UserAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@app/modules/ecosmob/user/web/';

    /**
     * @var array
     */
    public $css = [
        'css/user.css',
    ];

    /**
     * @var array
     */
    public $js = [
        //'js/user.js',
    ];

    /**
     * @var array
     */
    public $depends = [
        'app\assets\AuthAsset',
    ];

    /**
     * @var array
     */
    public $jsOptions = [View::POS_END];

    /**
     * @var array
     */
    public $cssOptions = [];

    /**
     * @var array
     */
    public $publishOptions = [];
}
