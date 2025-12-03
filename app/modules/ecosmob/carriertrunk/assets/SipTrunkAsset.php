<?php

namespace app\modules\ecosmob\carriertrunk\assets;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * Class SipTrunkAsset
 *
 * @package app\assets
 */
class SipTrunkAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@app/modules/ecosmob/carriertrunk/web/';

    /**
     * @var array
     */
    public $css
        = [
            'css/frm-grp.css',
            'css/codec_custom.css',
        ];

    /**
     * @var array
     */
    public $js
        = [
            'js/jquery.ui.widget.js',
            'js/carriertrunk-register-show.js',
            'js/trunk-grp-listbox.js',
            'js/dynamic_element.js',
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
