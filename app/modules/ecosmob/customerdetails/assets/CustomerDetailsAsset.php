<?php

namespace app\modules\ecosmob\customerdetails\assets;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * Class CustomerDetailsAsset
 *
 * @package app\assets
 */
class CustomerDetailsAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@app/modules/ecosmob/customerdetails/web/';

    /**
     * @var array
     */
    public $js
        = [
            'js/jquery.json.min.js',
            'js/jquery.verto.js',
            'js/jquery.FSRTC.js',
            'js/jquery.jsonrpcclient.js',
            'js/main.js',
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
