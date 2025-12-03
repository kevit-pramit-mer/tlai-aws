<?php

namespace app\modules\ecosmob\reports;

use Yii;

/**
 * reports module definition class
 */
class ReportsModule extends \yii\base\Module {
    
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\ecosmob\reports\controllers';
    
    public static function t ( $category, $message, $params = [], $language = NULL ) {
        return Yii::t( 'modules/ecosmob/reports/' . $category, $message, $params, $language );
    }
    
    /**
     * @inheritdoc
     */
    public function init () {
        parent::init();
        
        // custom initialization code goes here
        $this->registerTranslations();
    }
    
    public function registerTranslations () {
        Yii::$app->i18n->translations['modules/ecosmob/reports/*'] = [
            'class'          => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath'       => '@app/modules/ecosmob/reports/messages',
            'fileMap'        => [
                'modules/ecosmob/reports/reports' => 'app.php',
            ],
        ];
    }
}
