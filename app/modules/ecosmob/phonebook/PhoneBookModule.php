<?php

namespace app\modules\ecosmob\phonebook;

use Yii;
use yii\base\Module;

/**
 * PhoneBook module definition class
 */
class PhoneBookModule extends Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\ecosmob\phonebook\controllers';

    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/ecosmob/phonebook/' . $category, $message, $params, $language);
    }

    public function init()
    {
        parent::init();
        $this->registerTranslations();
    }

    public function registerTranslations()
    {
        Yii::$app->i18n->translations['modules/ecosmob/phonebook/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath' => '@app/modules/ecosmob/phonebook/messages',
            'fileMap' => [
                'modules/ecosmob/phonebook/app' => 'app.php',
            ],
        ];
    }
}
