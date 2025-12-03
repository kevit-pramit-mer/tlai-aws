<?php

namespace app\modules\ecosmob\agents;

use Yii;
use yii\base\Module;

/**
 * agents module definition class
 */
class AgentsModule extends Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\ecosmob\agents\controllers';

    public static function t($category, $message, $params = [], $language = NULL)
    {
        return Yii::t('modules/ecosmob/agents/' . $category, $message, $params, $language);
    }

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        $this->registerTranslations();

        // custom initialization code goes here
    }

    public function registerTranslations()
    {
        Yii::$app->i18n->translations['modules/ecosmob/agents/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath' => '@app/modules/ecosmob/agents/messages',
            'fileMap' => [
                'modules/ecosmob/agents/agents' => 'app.php',
            ],
        ];
    }
}
