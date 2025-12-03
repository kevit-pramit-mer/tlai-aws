<?php

namespace app\modules\ecosmob\customerdetails;

use yii;
use yii\base\Module;

/**
 * customerdetails module definition class
 */
class CustomerDetailsModule extends Module
{
    public $controllerNamespace = 'app\modules\ecosmob\customerdetails\controllers';

    /**
     * {@inheritdoc}
     */
    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/ecosmob/customerdetails/' . $category, $message, $params, $language);
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

    public function registerTranslations()
    {
        Yii::$app->i18n->translations['modules/ecosmob/customerdetails/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath' => '@app/modules/ecosmob/customerdetails/messages',
            'fileMap' => [
                'modules/ecosmob/customerdetails/customerdetails' => 'app.php',
            ],
        ];
    }
}
