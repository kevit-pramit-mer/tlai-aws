<?php

namespace app\modules\ecosmob\supervisor\assets;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * Class SupervisorAsset
 *
 * @package app\assets
 */
class SupervisorAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@app/modules/ecosmob/supervisor/web/';

    public $css
        = [
            'css/supervisor.css',
        ];
    /**
     * @var array
     */
    public $js
        = [
 //           'js/jquery.json.min.js',
//            'js/jquery.verto.js',
//            'js/jquery.FSRTC.js',
//            'js/jquery.jsonrpcclient.js',
            'js/sip-0.21.2.js',
            'js/sipMain.js',
            'js/mainSIP.js',
          //  'js/index.js',
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
