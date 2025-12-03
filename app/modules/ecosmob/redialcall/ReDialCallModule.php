<?php

namespace app\modules\ecosmob\redialcall;

use Yii;
use yii\base\Module;

/**
 * redialcall module definition class
 */
class ReDialCallModule extends Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\ecosmob\redialcall\controllers';

    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/ecosmob/redialcall/' . $category, $message, $params, $language);
    }

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        $this->registerTranslations();
    }

    public function registerTranslations()
    {
        Yii::$app->i18n->translations['modules/ecosmob/redialcall/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath' => '@app/modules/ecosmob/redialcall/messages',
            'fileMap' => [
                'modules/ecosmob/redialcall/redialcall' => 'app.php',
            ],
        ];
    }
}
