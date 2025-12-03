<?php

namespace app\modules\ecosmob\blacklist;

use Yii;
use yii\base\Module;

/**
 * blacklist module definition class
 */
class BlackListModule extends Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\ecosmob\blacklist\controllers';

    /**
     * @param       $category
     * @param       $message
     * @param array $params
     * @param null $language
     *
     * @return string
     */
    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/ecosmob/blacklist/' . $category, $message, $params, $language);
    }

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        $this->registerTranslations();
    }

    /**
     *
     */
    public function registerTranslations()
    {
        Yii::$app->i18n->translations['modules/ecosmob/blacklist/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath' => '@app/modules/ecosmob/blacklist/messages',
            'fileMap' => [
                'modules/ecosmob/blacklist/bl' => 'app.php',
            ],
        ];
    }
}
