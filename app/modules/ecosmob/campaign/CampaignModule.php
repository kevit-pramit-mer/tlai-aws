<?php

namespace app\modules\ecosmob\campaign;

use yii;
use yii\base\Module;

/**
 * campaign module definition class
 */
class CampaignModule extends Module
{
    public $controllerNamespace = 'app\modules\ecosmob\campaign\controllers';

    /**
     * {@inheritdoc}
     */

    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/ecosmob/campaign/' . $category, $message, $params, $language);
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
        Yii::$app->i18n->translations['modules/ecosmob/campaign/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath' => '@app/modules/ecosmob/campaign/messages',
            'fileMap' => [
                'modules/ecosmob/campaign/campaign' => 'app.php',
            ],
        ];
    }
}
