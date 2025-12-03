<?php

namespace app\modules\ecosmob\leadperformancereport;

use Yii;
use yii\base\Module;

/**
 * ecosmob module definition class
 */
class LeadPerformanceReportModule extends Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\ecosmob\leadperformancereport\controllers';

    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/ecosmob/leadperformancereport/' . $category, $message, $params, $language);
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

    /**
     * @inheritdoc
     */
    public function registerTranslations()
    {
        Yii::$app->i18n->translations['modules/ecosmob/leadperformancereport/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath' => '@app/modules/ecosmob/leadperformancereport/messages',
            'fileMap' => [
                'modules/ecosmob/leadperformancereport/leadperformancereport' => 'app.php',
            ],
        ];
    }
}
