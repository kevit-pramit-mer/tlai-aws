<?php

namespace app\modules\ecosmob\extension;

use Yii;
use yii\base\Module;

/**
 * extension module definition class
 */
class extensionModule extends Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\ecosmob\extension\controllers';

    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/ecosmob/extension/' . $category, $message, $params, $language);
    }

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        $this->registerTranslations();
    }

    public function registerTranslations()
    {
        Yii::$app->i18n->translations['modules/ecosmob/extension/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath' => '@app/modules/ecosmob/extension/messages',
            'fileMap' => [
                'modules/ecosmob/extension/app' => 'app.php',
            ],
        ];
    }
}
