<?php

namespace app\modules\ecosmob\timezone;

use Yii;

/**
 * timezone module definition class
 */
class timezone extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\ecosmob\timezone\controllers';

    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/ecosmob/timezone/' . $category, $message, $params, $language);
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
        Yii::$app->i18n->translations['modules/ecosmob/timezone/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath' => '@app/modules/ecosmob/timezone/messages',
            'fileMap' => [
                'modules/ecosmob/timezone/timezone' => 'app.php',
            ],
        ];
    }
}
