<?php

namespace app\modules\ecosmob\disposition;

use Yii;
use yii\base\Module;

/**
 * Disposition module definition class
 */
class DispositionModule extends Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\ecosmob\disposition\controllers';

    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/ecosmob/disposition/' . $category, $message, $params, $language);
    }

    public function init()
    {
        parent::init();
        $this->registerTranslations();
    }

    public function registerTranslations()
    {
        Yii::$app->i18n->translations['modules/ecosmob/disposition/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath' => '@app/modules/ecosmob/disposition/messages',
            'fileMap' => [
                'modules/ecosmob/disposition/disposition' => 'app.php',
            ],
        ];
    }
}
