<?php

namespace app\modules\ecosmob\agentswisereport;

use Yii;
use yii\base\Module;

/**
 * agentswisereport module definition class
 */
class AgentsWiseReportModule extends Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\ecosmob\agentswisereport\controllers';

    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/ecosmob/agentswisereport/' . $category, $message, $params, $language);
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
        Yii::$app->i18n->translations['modules/ecosmob/agentswisereport/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath' => '@app/modules/ecosmob/agentswisereport/messages',
            'fileMap' => [
                'modules/ecosmob/agentswisereport/agentswisereport' => 'app.php',
            ],
        ];
    }

}
