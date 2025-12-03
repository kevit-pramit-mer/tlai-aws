<?php

namespace app\modules\ecosmob\enterprisePhonebook\assets;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * Class EnterprisePhonebookAsset
 *
 * @package app\assets
 */
class EnterprisePhonebookAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@app/modules/ecosmob/enterprisePhonebook/web/';
    /**
     * @var array
     */
    public $css
        = [
            'css/enterprise-phonebook.css',
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
