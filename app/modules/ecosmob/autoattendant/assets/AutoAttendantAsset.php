<?php

namespace app\modules\ecosmob\autoattendant\assets;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * Class AutoAttendantAsset
 *
 * @package app\assets
 */
class AutoAttendantAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@app/modules/ecosmob/autoattendant/web/';

    /**
     * @var array
     */
    public $css
        = [
            'css/auto_attendant.css',
        ];

    /**
     * @var array
     */
    public $js = [
        'js/jquery-ui.min.js',
        'js/autoattendant_keys.js',
    ];

    /**
     * @var array
     */
    public $depends = [
        'app\assets\AppAsset',
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
