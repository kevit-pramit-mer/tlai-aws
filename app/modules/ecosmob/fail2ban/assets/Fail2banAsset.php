<?php

namespace app\modules\ecosmob\fail2ban\assets;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * Class Fail2banAsset
 *
 * @package app\assets
 */
class Fail2banAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@app/modules/ecosmob/fail2ban/web/';
    /**
     * @var array
     */
    public $css
        = [
            'css/fail2ban.css',
        ];
    /**
     * @var array
     */
    public $js = [];

    /**
     * @var array
     */
    public $depends
        = [
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
