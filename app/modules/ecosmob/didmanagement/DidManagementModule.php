<?php

namespace app\modules\ecosmob\didmanagement;

use Yii;
use yii\base\Module;


/**
 * didmanagement module definition class
 */
class DidManagementModule extends Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\ecosmob\didmanagement\controllers';

    /**
     * {@inheritdoc}
     */

    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/ecosmob/didmanagement/' . $category, $message, $params, $language);
    }

    public function init()
    {
        parent::init();
        $this->registerTranslations();
    }

    public function registerTranslations()
    {
        Yii::$app->i18n->translations['modules/ecosmob/didmanagement/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath' => '@app/modules/ecosmob/didmanagement/messages',
            'fileMap' => [
                'modules/ecosmob/didmanagement/did' => 'app.php',
            ],
        ];
    }
}
