<?php

namespace app\modules\ecosmob\plan;

use Yii;

/**
 * plan module definition class
 */
class PlanModule extends \yii\base\Module {
    
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\ecosmob\plan\controllers';
    
    /**
     * @param       $category
     * @param       $message
     * @param array $params
     * @param null  $language
     *
     * @return string
     */
    public static function t ( $category, $message, $params = [], $language = NULL ) {
        return Yii::t( 'modules/ecosmob/plan/' . $category, $message, $params, $language );
    }
    
    /**
     * {@inheritdoc}
     */
    public function init () {
        parent::init();
        $this->registerTranslations();
    }
    
    /**
     *
     */
    public function registerTranslations () {
        Yii::$app->i18n->translations['modules/ecosmob/plan/*'] = [
            'class'          => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath'       => '@app/modules/ecosmob/plan/messages',
            'fileMap'        => [
                'modules/ecosmob/plan/pl' => 'app.php',
            ],
        ];
    }
}
