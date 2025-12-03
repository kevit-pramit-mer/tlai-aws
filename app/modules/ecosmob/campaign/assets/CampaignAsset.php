<?php

namespace app\modules\ecosmob\campaign\assets;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * Class CampaignAsset
 *
 * @package app\assets
 */
class CampaignAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@app/modules/ecosmob/campaign/web/';

    /**
     * @var array
     */
    public $css
        = [
            'css/codec_custom.css',
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
