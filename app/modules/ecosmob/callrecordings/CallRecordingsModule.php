<?php

namespace app\modules\ecosmob\callrecordings;

use Yii;

/**
 * CallRecordings module definition class
 */
class CallRecordingsModule extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\ecosmob\callrecordings\controllers';

    /**
     * {@inheritdoc}
     */

    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/ecosmob/callrecordings/' . $category, $message, $params, $language);
    }
    public function init()
    {
        parent::init();
        $this->registerTranslations();
        // custom initialization code goes here
    }
    public function registerTranslations()
    {
        Yii::$app->i18n->translations['modules/ecosmob/callrecordings/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath' => '@app/modules/ecosmob/callrecordings/messages',
            'fileMap' => [
                'modules/ecosmob/callrecordings/app' => 'app.php',
            ],
        ];
    }
}
