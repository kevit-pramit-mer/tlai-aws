<?php

namespace app\modules\ecosmob\supervisorcdr;

use Yii;

/**
 * cdr module definition class
 */
class SupervisorCdrModule extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\ecosmob\supervisorcdr\controllers';

    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/ecosmob/supervisorcdr/' . $category, $message, $params, $language);
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
        Yii::$app->i18n->translations['modules/ecosmob/supervisorcdr/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath' => '@app/modules/ecosmob/supervisorcdr/messages',
            'fileMap' => [
                'modules/ecosmob/supervisorcdr/cdr' => 'app.php',
            ],
        ];
    }
}
