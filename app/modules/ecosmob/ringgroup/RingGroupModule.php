<?php

namespace app\modules\ecosmob\ringgroup;

use Yii;
use yii\base\Module;

/**
 * ringgroup module definition class
 */
class RingGroupModule extends Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\ecosmob\ringgroup\controllers';

    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/ecosmob/ringgroup/' . $category, $message, $params, $language);
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
        Yii::$app->i18n->translations['modules/ecosmob/ringgroup/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath' => '@app/modules/ecosmob/ringgroup/messages',
            'fileMap' => [
                'modules/ecosmob/ringgroup/rg' => 'app.php',
            ],
        ];
    }

}
