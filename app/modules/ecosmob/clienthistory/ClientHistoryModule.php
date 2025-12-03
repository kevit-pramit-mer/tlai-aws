<?php

namespace app\modules\ecosmob\clienthistory;

use yii;
use yii\base\Module;

/**
 * clienthistory module definition class
 */
class ClientHistoryModule extends Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\ecosmob\clienthistory\controllers';

    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/ecosmob/clienthistory/' . $category, $message, $params, $language);
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
        Yii::$app->i18n->translations['modules/ecosmob/clienthistory/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath' => '@app/modules/ecosmob/clienthistory/messages',
            'fileMap' => [
                'modules/ecosmob/clienthistory/clienthistory' => 'app.php',
            ],
        ];
    }
}
