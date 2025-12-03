<?php

namespace app\modules\ecosmob\leadgroupmember;

use Yii;
use yii\base\Module;

/**
 * LeadGroupMember module definition class
 */
class LeadGroupMemberModule extends Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\ecosmob\leadgroupmember\controllers';

    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/ecosmob/leadgroupmember/' . $category, $message, $params, $language);
    }

    public function init()
    {
        parent::init();
        $this->registerTranslations();
        // custom initialization code goes here
    }

    public function registerTranslations()
    {
        Yii::$app->i18n->translations['modules/ecosmob/leadgroupmember/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath' => '@app/modules/ecosmob/leadgroupmember/messages',
            'fileMap' => [
                'modules/ecosmob/leadgroupmember/lead-group-member' => 'app.php',
            ],
        ];
    }
}
