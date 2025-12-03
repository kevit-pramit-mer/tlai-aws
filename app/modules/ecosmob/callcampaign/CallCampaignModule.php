<?php

namespace app\modules\ecosmob\callcampaign;

use Yii;
use yii\base\Module;

/**
 * CallCampaign module definition class
 */
class CallCampaignModule extends Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\ecosmob\callcampaign\controllers';

    /**
     * {@inheritdoc}
     */

    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/ecosmob/callcampaign/' . $category, $message, $params, $language);
    }
    public function init()
    {
        parent::init();
        $this->registerTranslations();
        // custom initialization code goes here
    }
    public function registerTranslations()
    {
        Yii::$app->i18n->translations['modules/ecosmob/callcampaign/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath' => '@app/modules/ecosmob/callcampaign/messages',
            'fileMap' => [
                'modules/ecosmob/callcampaign/app' => 'app.php',
            ],
        ];
    }
}
