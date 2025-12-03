<?php

namespace app\modules\ecosmob\campaigncdr;

use Yii;

/**
 * cdr module definition class
 */
class CampaignCdrModule extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\ecosmob\campaigncdr\controllers';

    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/ecosmob/campaigncdr/' . $category, $message, $params, $language);
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
        Yii::$app->i18n->translations['modules/ecosmob/campaigncdr/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath' => '@app/modules/ecosmob/campaigncdr/messages',
            'fileMap' => [
                'modules/ecosmob/campaigncdr/cdr' => 'app.php',
            ],
        ];
    }
}
