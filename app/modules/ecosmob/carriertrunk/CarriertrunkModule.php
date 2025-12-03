<?php

namespace app\modules\ecosmob\carriertrunk;

use Yii;
use yii\base\Module;

/**
 * ecosmob module definition class
 */
class CarriertrunkModule extends Module
{

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\ecosmob\carriertrunk\controllers';

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
        return Yii::t('modules/ecosmob/carriertrunk/' . $category, $message, $params, $language);
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
        Yii::$app->i18n->translations['modules/ecosmob/carriertrunk/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath' => '@app/modules/ecosmob/carriertrunk/messages',
            'fileMap' => [
                'modules/ecosmob/carriertrunk/carriertrunk' => 'app.php',
            ],
        ];
    }
}
