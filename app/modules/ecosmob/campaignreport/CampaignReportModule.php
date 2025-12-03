<?php

namespace app\modules\ecosmob\campaignreport;

use yii;
use yii\base\Module;

/**
 * CampaignReport module definition class
 */
class CampaignReportModule extends Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\ecosmob\campaignreport\controllers';

    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/ecosmob/campaignreport/' . $category, $message, $params, $language);
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
        Yii::$app->i18n->translations['modules/ecosmob/campaignreport/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath' => '@app/modules/ecosmob/campaignreport/messages',
            'fileMap' => [
                'modules/ecosmob/campaignreport/campaignreport' => 'app.php',
            ],
        ];
    }
}
