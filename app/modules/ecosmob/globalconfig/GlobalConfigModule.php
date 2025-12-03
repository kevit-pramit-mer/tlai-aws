<?php

namespace app\modules\ecosmob\globalconfig;

use Yii;
use yii\base\Module;

/**
 * GlobalConfigModule module definition class
 */
class GlobalConfigModule extends Module
{

    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\ecosmob\globalconfig\controllers';

    /**
     * @param       $category
     * @param       $message
     * @param array $params
     * @param null $language
     *
     * @return string
     */
    public static function t($category, $message, $params = [], $language = NULL)
    {
        return Yii::t('modules/ecosmob/globalconfig/' . $category, $message, $params, $language);
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

    /**
     *
     */
    public function registerTranslations()
    {
        Yii::$app->i18n->translations['modules/ecosmob/globalconfig/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath' => '@app/modules/ecosmob/globalconfig/messages',
            'fileMap' => [
                'modules/ecosmob/globalconfig/gc' => 'app.php',
            ],
        ];
    }
}
