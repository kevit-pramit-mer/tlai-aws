<?php

namespace app\modules\ecosmob\systemcode;

use yii;
use yii\base\Module;

/**
 * systemcode module definition class
 */
class SystemCodeModule extends Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\ecosmob\systemcode\controllers';

    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/ecosmob/systemcode/' . $category, $message, $params, $language);
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
        Yii::$app->i18n->translations['modules/ecosmob/systemcode/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath' => '@app/modules/ecosmob/systemcode/messages',
            'fileMap' => [
                'modules/ecosmob/systemcode/systemcode' => 'app.php',
            ],
        ];
    }
}
