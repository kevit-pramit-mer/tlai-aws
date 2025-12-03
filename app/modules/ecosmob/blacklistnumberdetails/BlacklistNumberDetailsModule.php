<?php

namespace app\modules\ecosmob\blacklistnumberdetails;

use Yii;
use yii\base\Module;

/**
 * cdr module definition class
 */
class BlacklistNumberDetailsModule extends Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\ecosmob\blacklistnumberdetails\controllers';

    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/ecosmob/blacklistnumberdetails/' . $category, $message, $params, $language);
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
        Yii::$app->i18n->translations['modules/ecosmob/blacklistnumberdetails/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath' => '@app/modules/ecosmob/blacklistnumberdetails/messages',
            'fileMap' => [
                'modules/ecosmob/blacklistnumberdetails/cdr' => 'app.php',
            ],
        ];
    }
}
