<?php

namespace app\modules\ecosmob\dispositionreport;

use Yii;
use yii\base\Module;

/**
 * DispositionReport module definition class
 */
class DispositionReportModule extends Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\ecosmob\dispositionreport\controllers';

    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/ecosmob/dispositionreport/' . $category, $message, $params, $language);
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $this->registerTranslations();
    }

    public function registerTranslations()
    {
        Yii::$app->i18n->translations['modules/ecosmob/dispositionreport/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath' => '@app/modules/ecosmob/dispositionreport/messages',
            'fileMap' => [
                'modules/ecosmob/dispositionreport/dispositionreport' => 'app.php',
            ],
        ];
    }
}
