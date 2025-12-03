<?php

namespace app\modules\ecosmob\fraudcalldetectionreport;

use Yii;
use yii\base\Module;

/**
 * cdr module definition class
 */
class FraudCallDetectionReportModule extends Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\ecosmob\fraudcalldetectionreport\controllers';

    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/ecosmob/fraudcalldetectionreport/' . $category, $message, $params, $language);
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
        $this->registerTranslations();
    }

    public function registerTranslations()
    {
        Yii::$app->i18n->translations['modules/ecosmob/fraudcalldetectionreport/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath' => '@app/modules/ecosmob/fraudcalldetectionreport/messages',
            'fileMap' => [
                'modules/ecosmob/fraudcalldetectionreport/cdr' => 'app.php',
            ],
        ];
    }
}
