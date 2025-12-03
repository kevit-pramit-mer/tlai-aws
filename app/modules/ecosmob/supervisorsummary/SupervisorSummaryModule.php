<?php

namespace app\modules\ecosmob\supervisorsummary;

use yii;
use yii\base\Module;

/**
 * supervisorsummary module definition class
 */
class SupervisorSummaryModule extends Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\ecosmob\supervisorsummary\controllers';

    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/ecosmob/supervisorsummary/' . $category, $message, $params, $language);
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
        Yii::$app->i18n->translations['modules/ecosmob/supervisorsummary/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath' => '@app/modules/ecosmob/supervisorsummary/messages',
            'fileMap' => [
                'modules/ecosmob/supervisorsummary/supervisorsummary' => 'app.php',
            ],
        ];
    }
}
