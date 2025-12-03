<?php
/**
 * @link      http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license   http://www.yiiframework.com/license/
 */

namespace app\modules\ecosmob\crm\assets;

use yii\web\AssetBundle;

/**
 * Class CrmAsset
 *
 * @package app\assets
 */
class CrmAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@app/modules/ecosmob/crm/web/';

    /**
     * @var array
     */
    public $js
        = [
            'js/jquery.json.min.js',
            'js/jquery.verto.js',
//            'js/jquery.FSRTC.js',
//            'js/jquery.jsonrpcclient.js',
            'js/easytimer.min.js',
            'js/sip-0.21.2.js',
            'js/sipMain.js',
            'js/mainSIP.js',
            'js/sipEventHandler.js',
            'js/sipHelper.js',
            //'js/main_new.js',
          //  'js/index.js',
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
    public $jsOptions = [\yii\web\View::POS_END];

    /**
     * @var array
     */
    public $cssOptions = [];

    /**
     * @var array
     */
    public $publishOptions = [];
}
