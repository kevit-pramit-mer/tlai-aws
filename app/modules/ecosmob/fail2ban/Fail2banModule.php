<?php

namespace app\modules\ecosmob\fail2ban;

use Yii;
use yii\base\Module;

/**
 * fail2ban module definition class
 */
class Fail2banModule extends Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\ecosmob\fail2ban\controllers';

    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/ecosmob/fail2ban/' . $category, $message, $params, $language);
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
        Yii::$app->i18n->translations['modules/ecosmob/fail2ban/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath' => '@app/modules/ecosmob/fail2ban/messages',
            'fileMap' => [
                'modules/ecosmob/fail2ban/fail2ban' => 'app.php',
            ],
        ];
    }
}
