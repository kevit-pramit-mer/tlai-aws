<?php

namespace app\modules\ecosmob\calltimedistributionreport;

use Yii;
use yii\base\Module;

/**
 * CallTimeDistributionReport module definition class
 */
class CallTimeDistributionReportModule extends Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\ecosmob\calltimedistributionreport\controllers';

    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/ecosmob/calltimedistributionreport/' . $category, $message, $params, $language);
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
        Yii::$app->i18n->translations['modules/ecosmob/calltimedistributionreport/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath' => '@app/modules/ecosmob/calltimedistributionreport/messages',
            'fileMap' => [
                'modules/ecosmob/calltimedistributionreport/calltimedistributionreport' => 'app.php',
            ],
        ];
    }
}
