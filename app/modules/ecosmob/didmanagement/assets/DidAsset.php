<?php

namespace app\modules\ecosmob\didmanagement\assets;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * Class DidAsset
 *
 * @package app\assets
 */
class DidAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@app/modules/ecosmob/didmanagement/web/';
    /**
     * @var array
     */
    public $css
        = [
            'css/did.css',
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
