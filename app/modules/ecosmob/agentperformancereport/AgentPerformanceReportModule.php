<?php

namespace app\modules\ecosmob\agentperformancereport;

use Yii;
use yii\base\Module;

/**
 * HourlyCallReport module definition class
 */
class AgentPerformanceReportModule extends Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\ecosmob\agentperformancereport\controllers';

    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/ecosmob/agentperformancereport/' . $category, $message, $params, $language);
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->registerTranslations();
    }

    public function registerTranslations()
    {
        Yii::$app->i18n->translations['modules/ecosmob/agentperformancereport/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath' => '@app/modules/ecosmob/agentperformancereport/messages',
            'fileMap' => [
                'modules/ecosmob/agentperformancereport/agentperformancereport' => 'app.php',
            ],
        ];
    }
}
