<?php

namespace app\modules\ecosmob\dispositionType;

use Yii;

/**
 * DispositionType module definition class
 */
class DispositionTypeModule extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\ecosmob\dispositionType\controllers';

    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/ecosmob/dispositionType/' . $category, $message, $params, $language);
    }

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        $this->registerTranslations();

        // custom initialization code goes here
    }

    public function registerTranslations()
    {
        Yii::$app->i18n->translations['modules/ecosmob/dispositionType/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath' => '@app/modules/ecosmob/dispositionType/messages',
            'fileMap' => [
                'modules/ecosmob/dispositionType/dispositionType' => 'app.php',
            ],
        ];
    }
}
