<?php

namespace app\modules\ecosmob\agent;

use Yii;

/**
 * agent module definition class
 */
class AgentModule extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\ecosmob\agent\controllers';
    
    /**
     * @param       $category
     * @param       $message
     * @param array $params
     * @param null  $language
     *
     * @return mixed
     */
    public static function t ( $category, $message, $params = [], $language = NULL ) {
        return Yii::t( 'modules/ecosmob/agent/' . $category, $message, $params, $language );
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
    
    /**
     *
     */
    public function registerTranslations () {
        Yii::$app->i18n->translations['modules/ecosmob/agent/*'] = [
            'class'          => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath'       => '@app/modules/ecosmob/agent/messages',
            'fileMap'        => [
                'modules/ecosmob/agent/agent' => 'app.php',
            ],
        ];
    }
}
