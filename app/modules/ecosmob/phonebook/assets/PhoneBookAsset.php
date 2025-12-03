<?php

namespace app\modules\ecosmob\phonebook\assets;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * Class PhoneBookAsset
 *
 * @package app\assets
 */
class PhoneBookAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@app/modules/ecosmob/phonebook/web/';
    /**
     * @var array
     */
    public $css
        = [
            'css/phone-book.css',
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
