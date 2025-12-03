<?php

namespace app\modules\ecosmob\shift;

use Yii;
use yii\base\Module;

/**
 * shift module definition class
 */
class ShiftModule extends Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\ecosmob\shift\controllers';

    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/ecosmob/shift/' . $category, $message, $params, $language);
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
        Yii::$app->i18n->translations['modules/ecosmob/shift/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath' => '@app/modules/ecosmob/shift/messages',
            'fileMap' => [
                'modules/ecosmob/shift/sft' => 'app.php',
            ],
        ];
    }
}
