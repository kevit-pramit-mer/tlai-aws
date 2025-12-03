<?php

namespace app\modules\ecosmob\extensionforwarding;

use yii;
/**
 * extensionforwarding module definition class
 */
class ExtensionForwardingModule extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\ecosmob\extensionforwarding\controllers';

    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/ecosmob/extensionforwarding/' . $category, $message, $params, $language);
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
        Yii::$app->i18n->translations['modules/ecosmob/extensionforwarding/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath' => '@app/modules/ecosmob/extensionforwarding/messages',
            'fileMap' => [
                'modules/ecosmob/extensionforwarding/extensionforwarding' => 'app.php',
            ],
        ];
    }
}
