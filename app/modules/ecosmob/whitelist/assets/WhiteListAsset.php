<?php

namespace app\modules\ecosmob\whitelist\assets;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * Class WhiteListAsset
 *
 * @package app\assets
 */
class WhiteListAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@app/modules/ecosmob/whitelist/web/';
    /**
     * @var array
     */
    public $css
        = [
            'css/white-list.css',
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
