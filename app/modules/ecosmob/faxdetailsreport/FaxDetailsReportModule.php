<?php

namespace app\modules\ecosmob\faxdetailsreport;

use Yii;

/**
 * cdr module definition class
 */
class FaxDetailsReportModule extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\ecosmob\faxdetailsreport\controllers';

    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/ecosmob/faxdetailsreport/' . $category, $message, $params, $language);
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
        Yii::$app->i18n->translations['modules/ecosmob/faxdetailsreport/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath' => '@app/modules/ecosmob/faxdetailsreport/messages',
            'fileMap' => [
                'modules/ecosmob/faxdetailsreport/cdr' => 'app.php',
            ],
        ];
    }
}
