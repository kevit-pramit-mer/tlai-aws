<?php

namespace app\modules\ecosmob\blf;

use Yii;
use yii\base\Module;

/**
 * Blf module definition class
 */
class BlfModule extends Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\ecosmob\blf\controllers';

    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/ecosmob/blf/' . $category, $message, $params, $language);
    }

    public function init()
    {
        parent::init();
        $this->registerTranslations();
    }

    public function registerTranslations()
    {
        Yii::$app->i18n->translations['modules/ecosmob/blf/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath' => '@app/modules/ecosmob/blf/messages',
            'fileMap' => [
                'modules/ecosmob/blf/app' => 'app.php',
            ],
        ];
    }
}
