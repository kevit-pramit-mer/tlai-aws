<?php

namespace app\modules\ecosmob\emailtemplate;

use Yii;

/**
 * EmailTemplate module definition class
 */
class EmailTemplateModule extends \yii\base\Module {
    
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\ecosmob\emailtemplate\controllers';
    
    /**
     * @param       $category
     * @param       $message
     * @param array $params
     * @param null  $language
     *
     * @return mixed
     */
    public static function t ( $category, $message, $params = [], $language = NULL ) {
        return Yii::t( 'modules/ecosmob/emailtemplate/' . $category, $message, $params, $language );
    }
    
    
    /**
     * {@inheritdoc}
     */
    public function init () {
        parent::init();
        $this->registerTranslations();
        // custom initialization code goes here
    }
    
    /**
     *
     */
    public function registerTranslations () {
        Yii::$app->i18n->translations['modules/ecosmob/emailtemplate/*'] = [
            'class'          => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath'       => '@app/modules/ecosmob/emailtemplate/messages',
            'fileMap'        => [
                'modules/ecosmob/emailtemplate/emailtemplate' => 'app.php',
            ],
        ];
    }
}
