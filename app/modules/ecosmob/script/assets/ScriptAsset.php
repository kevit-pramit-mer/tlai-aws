<?php

namespace app\modules\ecosmob\script\assets;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * Class ScriptAsset
 *
 * @package app\assets
 */
class ScriptAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@app/modules/ecosmob/script/web/';
    /**
     * @var array
     */
    public $css
        = [
            'css/script.css',
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
