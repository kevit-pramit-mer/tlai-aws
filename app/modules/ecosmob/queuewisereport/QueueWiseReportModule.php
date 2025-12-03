<?php

namespace app\modules\ecosmob\queuewisereport;

use Yii;
use yii\base\Module;

/**
 * queuewisereport module definition class
 */
class QueueWiseReportModule extends Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\ecosmob\queuewisereport\controllers';

    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/ecosmob/queuewisereport/' . $category, $message, $params, $language);
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
        Yii::$app->i18n->translations['modules/ecosmob/queuewisereport/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath' => '@app/modules/ecosmob/queuewisereport/messages',
            'fileMap' => [
                'modules/ecosmob/queuewisereport/queuewisereport' => 'app.php',
            ],
        ];
    }
}
