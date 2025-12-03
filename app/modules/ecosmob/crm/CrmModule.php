<?php

namespace app\modules\ecosmob\crm;

use yii;
/**
 * crm module definition class
 */
class CrmModule extends \yii\base\Module
{

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
        return Yii::t('modules/ecosmob/crm/' . $category, $message, $params, $language);
    }

    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\ecosmob\crm\controllers';

    /**
     * {@inheritdoc}
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
        Yii::$app->i18n->translations['modules/ecosmob/crm/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath' => '@app/modules/ecosmob/crm/messages',
            'fileMap' => [
                'modules/ecosmob/crm/crm' => 'app.php',
            ],
        ];
    }
}
