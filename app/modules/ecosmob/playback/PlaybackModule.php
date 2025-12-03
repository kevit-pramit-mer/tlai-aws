<?php

namespace app\modules\ecosmob\playback;

use Yii;

/**
 * playback module definition class
 */
class PlaybackModule extends \yii\base\Module {
    
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\ecosmob\playback\controllers';
    
    /**
     * @param       $category
     * @param       $message
     * @param array $params
     * @param null  $language
     *
     * @return string
     */
    public static function t ( $category, $message, $params = [], $language = NULL ) {
        return Yii::t( 'modules/ecosmob/playback/' . $category, $message, $params, $language );
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
        Yii::$app->i18n->translations['modules/ecosmob/playback/*'] = [
            'class'          => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath'       => '@app/modules/ecosmob/playback/messages',
            'fileMap'        => [
                'modules/ecosmob/playback/pb' => 'app.php',
            ],
        ];
    }
}
