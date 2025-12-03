<?php

namespace app\modules\ecosmob\blacklist\assets;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * Class BlackListAsset
 *
 * @package app\assets
 */
class BlackListAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@app/modules/ecosmob/blacklist/web/';
    /**
     * @var array
     */
    public $css
        = [
            'css/black-list.css',
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
