<?php

namespace app\modules\ecosmob\feature\assets;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * Class FeatureAsset
 *
 * @package app\assets
 */
class FeatureAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@app/modules/ecosmob/feature/web/';
    /**
     * @var array
     */
    public $css
        = [
            'css/feature.css',
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
