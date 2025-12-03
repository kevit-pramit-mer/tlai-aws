<?php

namespace app\modules\ecosmob\feature;

use Yii;
use yii\base\Module;

/**
 * feature module definition class
 */
class FeatureModule extends Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\ecosmob\feature\controllers';

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
        return Yii::t('modules/ecosmob/feature/' . $category, $message, $params, $language);
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
        Yii::$app->i18n->translations['modules/ecosmob/feature/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath' => '@app/modules/ecosmob/feature/messages',
            'fileMap' => [
                'modules/ecosmob/feature/feature' => 'app.php',
            ],
        ];
    }
}
