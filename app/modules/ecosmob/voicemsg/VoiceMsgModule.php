<?php

namespace app\modules\ecosmob\voicemsg;

use Yii;
use yii\base\Module;

/**
 * voicemsg module definition class
 */
class VoiceMsgModule extends Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\ecosmob\voicemsg\controllers';

    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/ecosmob/voicemsg/' . $category, $message, $params, $language);
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
        Yii::$app->i18n->translations['modules/ecosmob/voicemsg/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath' => '@app/modules/ecosmob/voicemsg/messages',
            'fileMap' => [
                'modules/ecosmob/voicemsg/voicemsg' => 'app.php',
            ],
        ];
    }
}
