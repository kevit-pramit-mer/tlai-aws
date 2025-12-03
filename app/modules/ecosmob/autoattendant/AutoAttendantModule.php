<?php

namespace app\modules\ecosmob\autoattendant;

use Yii;
use yii\base\Module;

/**
 * autoattendant module definition class
 */
class AutoAttendantModule extends Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\ecosmob\autoattendant\controllers';

    /**
     * @param $category
     * @param $message
     * @param array $params
     * @param null $language
     *
     * @return string
     */
    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/ecosmob/autoattendant/' . $category, $message, $params, $language);
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->registerTranslations();

        // custom initialization code goes here
    }

    /**
     * Register Translation for AutoAttendantModule
     *
     */
    public function registerTranslations()
    {
        Yii::$app->i18n->translations['modules/ecosmob/autoattendant/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath' => '@app/modules/ecosmob/autoattendant/messages',
            'fileMap' => [
                'modules/ecosmob/autoattendant/autoattendant' => 'app.php',
            ],
        ];

        Yii::$app->i18n->translations['modules/ecosmob/extensionmaster/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath' => '@app/modules/ecosmob/extensionmaster/messages',
            'fileMap' => [
                'modules/ecosmob/extensionmaster/extensionmaster' => 'app.php',
            ],
        ];
    }
}
