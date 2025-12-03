<?php

namespace app\modules\ecosmob\breaks;

use Yii;
use yii\base\Module;

/**
 * breaks module definition class
 */
class BreaksModule extends Module
{

    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\ecosmob\breaks\controllers';

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
        return Yii::t('modules/ecosmob/breaks/' . $category, $message, $params, $language);
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
        Yii::$app->i18n->translations['modules/ecosmob/breaks/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath' => '@app/modules/ecosmob/breaks/messages',
            'fileMap' => [
                'modules/ecosmob/breaks/breaks' => 'app.php',
            ],
        ];
    }
}
