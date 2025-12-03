<?php

namespace app\modules\ecosmob\jobs\assets;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * Class JobsAsset
 *
 * @package app\assets
 */
class JobsAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@app/modules/ecosmob/jobs/web/';
    /**
     * @var array
     */
    public $css
        = [
            'css/jobs.css',
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
