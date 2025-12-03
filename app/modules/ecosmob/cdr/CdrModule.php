<?php

namespace app\modules\ecosmob\cdr;

use Yii;
use yii\base\Module;

/**
 * cdr module definition class
 */
class CdrModule extends Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\ecosmob\cdr\controllers';

    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/ecosmob/cdr/' . $category, $message, $params, $language);
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
        $this->registerTranslations();
    }

    public function registerTranslations()
    {
        Yii::$app->i18n->translations['modules/ecosmob/cdr/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath' => '@app/modules/ecosmob/cdr/messages',
            'fileMap' => [
                'modules/ecosmob/cdr/cdr' => 'app.php',
            ],
        ];
    }
}
