<?php

namespace app\modules\ecosmob\auth;

use Yii;
use yii\base\Module;

/**
 * auth module definition class
 */
class AuthModule extends Module
{

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\ecosmob\auth\controllers';

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
        return Yii::t('modules/ecosmob/auth/' . $category, $message, $params, $language);
    }

    /**
     * @inheritdoc
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
        Yii::$app->i18n->translations['modules/ecosmob/auth/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath' => '@app/modules/ecosmob/auth/messages',
            'fileMap' => [
                'modules/ecosmob/auth/auth' => 'app.php',
            ],
        ];
    }
}
