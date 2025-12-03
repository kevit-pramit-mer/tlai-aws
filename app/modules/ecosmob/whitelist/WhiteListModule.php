<?php

namespace app\modules\ecosmob\whitelist;

use Yii;
use yii\base\Module;

/**
 * whitelist module definition class
 */
class WhiteListModule extends Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\ecosmob\whitelist\controllers';

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
        return Yii::t('modules/ecosmob/whitelist/' . $category, $message, $params, $language);
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
        Yii::$app->i18n->translations['modules/ecosmob/whitelist/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath' => '@app/modules/ecosmob/whitelist/messages',
            'fileMap' => [
                'modules/ecosmob/whitelist/wl' => 'app.php',
            ],
        ];
    }
}
