<?php

namespace app\modules\ecosmob\timeclockreport;

use Yii;
use yii\base\Module;

/**
 * TimeClockReportModule module definition class
 */
class TimeClockReportModule extends Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\ecosmob\timeclockreport\controllers';

    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/ecosmob/timeclockreport/' . $category, $message, $params, $language);
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
        Yii::$app->i18n->translations['modules/ecosmob/timeclockreport/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath' => '@app/modules/ecosmob/timeclockreport/messages',
            'fileMap' => [
                'modules/ecosmob/timeclockreport/timeclockreport' => 'app.php',
            ],
        ];
    }
}
