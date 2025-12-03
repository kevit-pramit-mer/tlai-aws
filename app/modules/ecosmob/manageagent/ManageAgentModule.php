<?php

namespace app\modules\ecosmob\manageagent;

use yii;
use yii\base\Module;

/**
 * manageagent module definition class
 */
class ManageAgentModule extends Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\ecosmob\manageagent\controllers';

    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/ecosmob/manageagent/' . $category, $message, $params, $language);
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
        Yii::$app->i18n->translations['modules/ecosmob/manageagent/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath' => '@app/modules/ecosmob/manageagent/messages',
            'fileMap' => [
                'modules/ecosmob/manageagent/manageagent' => 'app.php',
            ],
        ];
    }
}
