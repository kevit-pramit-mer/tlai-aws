<?php

namespace app\modules\ecosmob\queue\assets;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * Class QueueAsset
 *
 * @package app\assets
 */
class QueueAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@app/modules/ecosmob/queue/web/';

    /**
     * @var array
     */
    public $css
        = [
            'css/codec_custom.css',
            'css/queue.css',
        ];

    /**
     * @var array
     */
    public $js
        = [
            'js/jquery.ui.widget.js',
            'js/assign_codec.js',
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
