<?php

namespace app\modules\ecosmob\auth\assets;

use yii\web\AssetBundle;

/**
 * Class AuthAsset
 *
 * Auth Asset Class responsible for Auth Module Assets
 *
 * @package app\assets
 */
class AuthAsset extends AssetBundle
{
    /**
     * Default Source Path for Auth Asset
     *
     * @var string
     */
    public $sourcePath = '@app/modules/ecosmob/auth/web/';

    /**
     * @var array
     */
    public $css = [
        'css/login/css/login_v2.css',
        'css/forgotPassword/css/forgot_password_v2.css',
        'css/reset.css'
    ];

    /**
     * @var array
     */
    public $js = [];

    /**
     * @var array
     */
    public $jsOptions = [];

    /**
     * @var array
     */
    public $cssOptions = [];

    /**
     * @var array
     */
    public $publishOptions = [];

    /**
     * @var array
     */
    public $depends = [
        'app\assets\LoginAsset',
    ];
}
