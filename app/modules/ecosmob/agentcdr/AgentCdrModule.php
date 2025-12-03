<?php

namespace app\modules\ecosmob\agentcdr;

use Yii;

/**
 * cdr module definition class
 */
class AgentCdrModule extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\ecosmob\agentcdr\controllers';

    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/ecosmob/agentcdr/' . $category, $message, $params, $language);
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
        Yii::$app->i18n->translations['modules/ecosmob/agentcdr/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath' => '@app/modules/ecosmob/agentcdr/messages',
            'fileMap' => [
                'modules/ecosmob/agentcdr/cdr' => 'app.php',
            ],
        ];
    }
}
