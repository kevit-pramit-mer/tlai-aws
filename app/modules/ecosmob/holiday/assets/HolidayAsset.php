<?php

namespace app\modules\ecosmob\holiday\assets;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * Class HolidayAsset
 *
 * @package app\assets
 */
class HolidayAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@app/modules/ecosmob/holiday/web/';
    /**
     * @var array
     */
    public $css
        = [
            'css/holiday.css',
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
