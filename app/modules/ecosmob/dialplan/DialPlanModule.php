<?php

namespace app\modules\ecosmob\dialplan;

use Yii;
use yii\base\Module;

/**
 * dialplan module definition class
 */
class DialPlanModule extends Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\ecosmob\dialplan\controllers';

    /**
     * @param       $category
     * @param       $message
     * @param array $params
     * @param null $language
     *
     * @return mixed
     */
    public static function t($category, $message, $params = [], $language = NULL)
    {
        return Yii::t('modules/ecosmob/dialplan/' . $category, $message, $params, $language);
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
        Yii::$app->i18n->translations['modules/ecosmob/dialplan/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath' => '@app/modules/ecosmob/dialplan/messages',
            'fileMap' => [
                'modules/ecosmob/dialplan/dp' => 'app.php',
            ],
        ];
    }
}
