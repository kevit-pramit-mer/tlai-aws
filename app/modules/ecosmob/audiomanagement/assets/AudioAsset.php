<?php

namespace app\modules\ecosmob\audiomanagement\assets;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * Class AudioAsset
 *
 * @package app\assets
 */
class AudioAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@app/modules/ecosmob/audiomanagement/web/';
    /**
     * @var array
     */
    public $css
        = [
            'css/audio.css',
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
