<?php

namespace app\modules\ecosmob\supervisoragentcdr;

use Yii;

/**
 * cdr module definition class
 */
class SupervisorAgentCdrModule extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\ecosmob\supervisoragentcdr\controllers';

    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/ecosmob/supervisoragentcdr/' . $category, $message, $params, $language);
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
        Yii::$app->i18n->translations['modules/ecosmob/supervisoragentcdr/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath' => '@app/modules/ecosmob/supervisoragentcdr/messages',
            'fileMap' => [
                'modules/ecosmob/supervisoragentcdr/cdr' => 'app.php',
            ],
        ];
    }
}
