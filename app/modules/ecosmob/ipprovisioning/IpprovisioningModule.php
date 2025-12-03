<?php

namespace app\modules\ecosmob\ipprovisioning;

use Yii;
/**
 * ipprovisioning module definition class
 */
class IpprovisioningModule extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\ecosmob\ipprovisioning\controllers';

    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/ecosmob/ipprovisioning/' . $category, $message, $params, $language);
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
        Yii::$app->i18n->translations['modules/ecosmob/ipprovisioning/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath' => '@app/modules/ecosmob/ipprovisioning/messages',
            'fileMap' => [
                'modules/ecosmob/ipprovisioning/app' => 'app.php',
            ],
        ];
    }
}
