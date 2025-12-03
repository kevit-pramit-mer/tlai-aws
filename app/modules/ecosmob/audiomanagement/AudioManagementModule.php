<?php

namespace app\modules\ecosmob\audiomanagement;

use Yii;
use yii\base\Module;

/**
 * audiomanagement module definition class
 */
class AudioManagementModule extends Module
{

    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\ecosmob\audiomanagement\controllers';

    public static function t($category, $message, $params = [], $language = NULL)
    {
        return Yii::t('modules/ecosmob/audiomanagement/' . $category, $message, $params, $language);
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
        Yii::$app->i18n->translations['modules/ecosmob/audiomanagement/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath' => '@app/modules/ecosmob/audiomanagement/messages',
            'fileMap' => [
                'modules/ecosmob/audiomanagement/am' => 'app.php',
            ],
        ];
    }
}
