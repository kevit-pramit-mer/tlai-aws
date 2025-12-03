<?php

namespace app\modules\ecosmob\leadgroupmember\assets;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * Class LeadGroupMemberAsset
 *
 * @package app\assets
 */
class LeadGroupMemberAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@app/modules/ecosmob/leadgroupmember/web/';
    /**
     * @var array
     */
    public $css
        = [
            'css/lead-group-member.css',
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
