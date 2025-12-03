<?php

namespace app\modules\ecosmob\fax\assets;

use yii\web\AssetBundle;

/**
 * Class FaxAsset
 * @package app\assets
 */
class FaxAsset extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public $sourcePath = '@app/modules/ecosmob/fax/web/';
    /**
     * @inheritdoc
     */
    public $js = [];

    public $css = [
        'css/fax.css',
    ];

    /**
     * @inheritdoc
     */
    public $depends = [
        'yii\web\YiiAsset',
    ];
}