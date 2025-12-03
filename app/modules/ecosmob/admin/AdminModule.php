<?php

namespace app\modules\ecosmob\admin;

use Yii;
use yii\base\Module;

/**
 * admin module definition class
 */
class AdminModule extends Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\ecosmob\admin\controllers';

    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/ecosmob/admin/' . $category, $message, $params, $language);
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->registerTranslations();
        // custom initialization code goes here
    }

    public function registerTranslations()
    {
        Yii::$app->i18n->translations['modules/ecosmob/admin/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath' => '@app/modules/ecosmob/admin/messages',
            'fileMap' => [
                'modules/ecosmob/admin/admin' => 'app.php',
            ],
        ];

        Yii::$app->i18n->translations['modules/ecosmob/extension/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath' => '@app/modules/ecosmob/extension/messages',
            'fileMap' => [
                'modules/ecosmob/extension/extension' => 'app.php',
            ],
        ];

        Yii::$app->i18n->translations['modules/ecosmob/tenantmaster/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath' => '@app/modules/ecosmob/tenantmaster/messages',
            'fileMap' => [
                'modules/ecosmob/tenantmaster/tenantmaster' => 'app.php',
            ],
        ];

    }
}
