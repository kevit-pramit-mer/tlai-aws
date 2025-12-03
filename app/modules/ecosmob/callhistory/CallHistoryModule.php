<?php

namespace app\modules\ecosmob\callhistory;

use yii;
use yii\base\Module;

/**
 * callhistory module definition class
 */
class CallHistoryModule extends Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\ecosmob\callhistory\controllers';

    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/ecosmob/callhistory/' . $category, $message, $params, $language);
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
        Yii::$app->i18n->translations['modules/ecosmob/callhistory/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath' => '@app/modules/ecosmob/callhistory/messages',
            'fileMap' => [
                'modules/ecosmob/callhistory/callhistory' => 'app.php',
            ],
        ];
    }
}
