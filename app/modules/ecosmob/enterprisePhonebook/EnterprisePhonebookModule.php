<?php

namespace app\modules\ecosmob\enterprisePhonebook;

use Yii;
/**
 * EnterprisePhonebookModule module definition class
 */
class EnterprisePhonebookModule extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\ecosmob\enterprisePhonebook\controllers';

    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/ecosmob/enterprisePhonebook/' . $category, $message, $params, $language);
    }

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        $this->registerTranslations();
    }

    public function registerTranslations()
    {
        Yii::$app->i18n->translations['modules/ecosmob/enterprisePhonebook/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath' => '@app/modules/ecosmob/enterprisePhonebook/messages',
            'fileMap' => [
                'modules/ecosmob/enterprisePhonebook/app' => 'app.php',
            ],
        ];
    }
}
