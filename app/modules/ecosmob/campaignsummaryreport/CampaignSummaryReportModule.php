<?php

namespace app\modules\ecosmob\campaignsummaryreport;

use Yii;
use yii\base\Module;

/**
 * campaignsummaryreport module definition class
 */
class CampaignSummaryReportModule extends Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\ecosmob\campaignsummaryreport\controllers';

    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/ecosmob/campaignsummaryreport/' . $category, $message, $params, $language);
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
        Yii::$app->i18n->translations['modules/ecosmob/campaignsummaryreport/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath' => '@app/modules/ecosmob/campaignsummaryreport/messages',
            'fileMap' => [
                'modules/ecosmob/campaignsummaryreport/campaignsummaryreport' => 'app.php',
            ],
        ];
    }
}
