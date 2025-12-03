<?php

namespace app\modules\ecosmob\speeddial;

use Yii;
use yii\base\Module;

/**
 * speeddial module definition class
 */
class SpeeddialModule extends Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\ecosmob\speeddial\controllers';

    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/ecosmob/speeddial/' . $category, $message, $params, $language);
    }

    public function init()
    {
        parent::init();
        $this->registerTranslations();
    }

    public function registerTranslations()
    {
        Yii::$app->i18n->translations['modules/ecosmob/speeddial/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath' => '@app/modules/ecosmob/speeddial/messages',
            'fileMap' => [
                'modules/ecosmob/speeddial/app' => 'app.php',
            ],
        ];
    }
}
