<?php

namespace app\modules\ecosmob\jobs;

use yii;
use yii\base\Module;

/**
 * jobs module definition class
 */
class JobsModule extends Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\ecosmob\jobs\controllers';

    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/ecosmob/jobs/' . $category, $message, $params, $language);
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
        Yii::$app->i18n->translations['modules/ecosmob/jobs/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath' => '@app/modules/ecosmob/jobs/messages',
            'fileMap' => [
                'modules/ecosmob/jobs/jobs' => 'app.php',
            ],
        ];
    }
}
