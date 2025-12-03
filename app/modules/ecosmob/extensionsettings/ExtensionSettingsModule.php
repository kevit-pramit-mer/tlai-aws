<?php

namespace app\modules\ecosmob\extensionsettings;

use yii;

/**
 * extensionsettings module definition class
 */
class ExtensionSettingsModule extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\ecosmob\extensionsettings\controllers';

    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/ecosmob/extensionsettings/' . $category, $message, $params, $language);
    }
    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        $this->registerTranslations();

        // custom initialization code goes here
    }

    public function registerTranslations()
    {
        Yii::$app->i18n->translations['modules/ecosmob/extensionsettings/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath' => '@app/modules/ecosmob/extensionsettings/messages',
            'fileMap' => [
                'modules/ecosmob/extensionsettings/extensionsettings' => 'app.php',
            ],
        ];
    }
}
