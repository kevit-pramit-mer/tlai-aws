<?php

namespace app\modules\ecosmob\services;

use yii;
/**
 * services module definition class
 */
class ServicesModule extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\ecosmob\services\controllers';

    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/ecosmob/services/' . $category, $message, $params, $language);
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
        Yii::$app->i18n->translations['modules/ecosmob/services/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath' => '@app/modules/ecosmob/services/messages',
            'fileMap' => [
                'modules/ecosmob/services/services' => 'app.php',
            ],
        ];
    }
}
