<?php

namespace app\modules\ecosmob\hourlycallreport;

use Yii;
use yii\base\Module;

/**
 * HourlyCallReport module definition class
 */
class HourlyCallReportModule extends Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\ecosmob\hourlycallreport\controllers';

    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/ecosmob/hourlycallreport/' . $category, $message, $params, $language);
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
        Yii::$app->i18n->translations['modules/ecosmob/hourlycallreport/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath' => '@app/modules/ecosmob/hourlycallreport/messages',
            'fileMap' => [
                'modules/ecosmob/hourlycallreport/hourlycallreport' => 'app.php',
            ],
        ];
    }
}
