<?php

namespace app\modules\ecosmob\conference\assets;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * Class ConferenceAsset
 * @package app\assets
 */
class ConferenceAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@app/modules/ecosmob/conference/web/';

    /**
     * @var array
     */
    public $css = [
        'css/conference.css',
    ];

    /**
     * @var array
     */
    public $js = [
        'js/conference_moh.js',
    ];

    /**
     * @var array
     */
    public $depends = [
        'yii\web\YiiAsset',
    ];

    /**
     * @var array
     */
    public $jsOptions = [View::POS_END];
}
