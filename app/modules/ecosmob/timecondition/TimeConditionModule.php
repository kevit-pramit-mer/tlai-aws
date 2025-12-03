<?php

namespace app\modules\ecosmob\timecondition;

use Yii;
use yii\base\Module;

/**
 * TimeConditionModule module definition class
 */
class TimeConditionModule extends Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\ecosmob\timecondition\controllers';

    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/ecosmob/timecondition/' . $category, $message, $params, $language);
    }

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        $this->registerTranslations();
    }

    /**
     *
     */
    public function registerTranslations()
    {
        Yii::$app->i18n->translations['modules/ecosmob/timecondition/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath' => '@app/modules/ecosmob/timecondition/messages',
            'fileMap' => [
                'modules/ecosmob/timecondition/tc' => 'app.php',
            ],
        ];
    }
}
