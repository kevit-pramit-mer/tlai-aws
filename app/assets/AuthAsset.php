<?php

namespace app\assets;

use yii\web\AssetBundle;

class AuthAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web/theme/assets';

    public $css = [
        'css/select2.min.css',
        'css/data-tables/data-tables.css',
        'css/data-tables/jquery.dataTables.min.css',
        'css/data-tables/responsive.dataTables.min.css',
        'css/data-tables/select.dataTables.min.css',
        /*'css/general/datetimepicker-standalone.min.css'*/
    ];

    public $js = [
        'js/select2.min.js',
        'js/form-mask/form-masks.js',
        'js/form-mask/form-layouts.js',
        'js/form-mask/jquery.formatter.min.js',
        'js/data-tables/data-tables.js',
        'js/data-tables/jquery.dataTables.min.js',
        'js/data-tables/dataTables.select.min.js',
        'js/data-tables/dataTables.responsive.min.js',
        'js/multiselect.min.js',
        'js/custom.js',
        'js/jquery.pjax.js',
        'js/pjax.js'
    ];

    public $depends = [
        'yii\web\YiiAsset',
    ];
}
