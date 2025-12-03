<?php

namespace app\modules\ecosmob\extension\assets;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * Class ExtensionAsset
 *
 * @package app\assets
 */
class ExtensionAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@app/modules/ecosmob/extension/web/';
    /**
     * @var array
     */
    public $css
        = [
            'css/extension.css',
        ];
    /**
     * @var array
     */
    public $js
        = [

            'js/jquery.json.min.js',
            'js/easytimer.min.js',
            'js/sip-0.21.2.js',
            'js/sipMain.js',
            'js/mainSIP.js',
            'js/sipEventHandler.js',
            'js/sipHelper.js',
            'js/sw.js'
            //'js/main_new.js',
            //  'js/index.js',
            //'js/custom.js',
        ];


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
