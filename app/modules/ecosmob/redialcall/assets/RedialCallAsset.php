<?php

namespace app\modules\ecosmob\redialcall\assets;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * Class RedialCallAsset
 *
 * @package app\assets
 */
class RedialCallAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@app/modules/ecosmob/redialcall/web/';
    /**
     * @var array
     */
    public $css
        = [
            'css/redial-call.css',
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
