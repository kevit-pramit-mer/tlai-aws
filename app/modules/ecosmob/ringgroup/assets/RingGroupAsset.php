<?php

namespace app\modules\ecosmob\ringgroup\assets;

use yii\web\AssetBundle;

/**
 * Class RingGroupAsset
 * @package app\assets
 */
class RingGroupAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@app/modules/ecosmob/ringgroup/web/';
    /**
     * @inheritdoc
     */
    public $js = [
        'js/jquery-ui.min.js',
        'js/ringgroup.js',
    ];

    public $css = [
        'css/ringgroup.css',
    ];

    /**
     * @inheritdoc
     */
    public $depends = [
        'yii\web\YiiAsset',
    ];
}