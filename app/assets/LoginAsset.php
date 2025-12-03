<?php

namespace app\assets;

use yii\web\AssetBundle;

class LoginAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web/theme/assets';

    public $css = [
        'css/login/login.css',
    ];

    public $js = [
    ];


    public $depends = [
        'yii\web\YiiAsset',
    ];
}
