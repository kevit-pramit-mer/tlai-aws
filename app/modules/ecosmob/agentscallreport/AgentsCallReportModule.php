<?php

namespace app\modules\ecosmob\agentscallreport;

use Yii;
use yii\base\Module;

/**
 * AgentsCallReport module definition class
 */
class AgentsCallReportModule extends Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\ecosmob\agentscallreport\controllers';

    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/ecosmob/agentscallreport/' . $category, $message, $params, $language);
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
        Yii::$app->i18n->translations['modules/ecosmob/agentscallreport/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath' => '@app/modules/ecosmob/agentscallreport/messages',
            'fileMap' => [
                'modules/ecosmob/agentscallreport/agentscallreport' => 'app.php',
            ],
        ];
    }
}
