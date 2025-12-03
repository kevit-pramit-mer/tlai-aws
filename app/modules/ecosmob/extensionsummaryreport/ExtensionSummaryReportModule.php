<?php

namespace app\modules\ecosmob\extensionsummaryreport;

use Yii;

/**
 * extensionsummaryreport module definition class
 */
class ExtensionSummaryReportModule extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\ecosmob\extensionsummaryreport\controllers';

    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/ecosmob/extensionsummaryreport/' . $category, $message, $params, $language);
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
        Yii::$app->i18n->translations['modules/ecosmob/extensionsummaryreport/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath' => '@app/modules/ecosmob/extensionsummaryreport/messages',
            'fileMap' => [
                'modules/ecosmob/extensionsummaryreport/extensionsummaryreport' => 'app.php',
            ],
        ];
    }
}
