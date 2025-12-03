<?php

namespace app\modules\ecosmob\holiday;

use Yii;
use yii\base\Module;

/**
 * holiday module definition class
 */
class HolidayModule extends Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\ecosmob\holiday\controllers';

    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/ecosmob/holiday/' . $category, $message, $params, $language);
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
        Yii::$app->i18n->translations['modules/ecosmob/holiday/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath' => '@app/modules/ecosmob/holiday/messages',
            'fileMap' => [
                'modules/ecosmob/holiday/hd' => 'app.php',
            ],
        ];
    }
}
