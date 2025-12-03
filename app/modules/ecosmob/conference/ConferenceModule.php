<?php

namespace app\modules\ecosmob\conference;

use Yii;
use yii\base\Module;

/**
 * conference module definition class
 */
class ConferenceModule extends Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\ecosmob\conference\controllers';
    
    /**
     * @param       $category
     * @param       $message
     * @param array $params
     * @param null  $language
     *
     * @return string
     */
    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/ecosmob/conference/' . $category, $message, $params, $language);
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
     *
     */
    public function registerTranslations()
    {
        Yii::$app->i18n->translations['modules/ecosmob/conference/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath' => '@app/modules/ecosmob/conference/messages',
            'fileMap' => [
                'modules/ecosmob/conference/conference' => 'app.php',
            ],
        ];
    }
}
