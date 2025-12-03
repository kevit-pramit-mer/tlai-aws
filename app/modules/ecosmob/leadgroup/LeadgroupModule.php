<?php

namespace app\modules\ecosmob\leadgroup;

use Yii;
use yii\base\Module;

/**
 * Leadgroup module definition class
 */
class LeadgroupModule extends Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\ecosmob\leadgroup\controllers';

    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/ecosmob/leadgroup/' . $category, $message, $params, $language);
    }

    public function init()
    {
        parent::init();
        $this->registerTranslations();
    }

    public function registerTranslations()
    {
        Yii::$app->i18n->translations['modules/ecosmob/leadgroup/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath' => '@app/modules/ecosmob/leadgroup/messages',
            'fileMap' => [
                'modules/ecosmob/leadgroup/leadgroup' => 'app.php',
            ],
        ];
    }

}
