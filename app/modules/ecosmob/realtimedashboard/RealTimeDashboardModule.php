<?php

namespace app\modules\ecosmob\realtimedashboard;

use Yii;
use yii\base\Module;

/**
 * admin module definition class
 */
class RealTimeDashboardModule extends Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\ecosmob\realtimedashboard\controllers';

    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/ecosmob/realtimedashboard/' . $category, $message, $params, $language);
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->registerTranslations();
        // custom initialization code goes here
    }

    public function registerTranslations()
    {
        Yii::$app->i18n->translations['modules/ecosmob/realtimedashboard/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath' => '@app/modules/ecosmob/realtimedashboard/messages',
            'fileMap' => [
                'modules/ecosmob/realtimedashboard/realtimedashboard' => 'app.php',
            ],
        ];
    }
}
