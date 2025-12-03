<?php

namespace app\modules\ecosmob\weekoff;

use Yii;
use yii\base\Module;

/**
 * weekoff module definition class
 */
class WeekOffModule extends Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\ecosmob\weekoff\controllers';

    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/ecosmob/weekoff/' . $category, $message, $params, $language);
    }


    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        $this->registerTranslations();
    }

    /**
     *
     */
    public function registerTranslations()
    {
        Yii::$app->i18n->translations['modules/ecosmob/weekoff/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath' => '@app/modules/ecosmob/weekoff/messages',
            'fileMap' => [
                'modules/ecosmob/weekoff/wo' => 'app.php',
            ],
        ];
    }
}
