<?php

namespace app\modules\ecosmob\accessrestriction;

use Yii;
use yii\base\Module;

/**
 * AccessRestrictionModule module definition class
 */
class AccessRestrictionModule extends Module
{

    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\ecosmob\accessrestriction\controllers';

    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/ecosmob/accessrestriction/' . $category, $message, $params, $language);
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
        Yii::$app->i18n->translations['modules/ecosmob/accessrestriction/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath' => '@app/modules/ecosmob/accessrestriction/messages',
            'fileMap' => [
                'modules/ecosmob/accessrestriction/accessrestriction' => 'app.php',
            ],
        ];

        Yii::$app->i18n->translations['modules/ecosmob/servicebase/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath' => '@app/modules/ecosmob/servicebase/messages',
            'fileMap' => [
                'modules/ecosmob/servicebase/servicebase' => 'app.php',
            ],
        ];

        Yii::$app->i18n->translations['modules/ecosmob/tenantmaster/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath' => '@app/modules/ecosmob/tenantmaster/messages',
            'fileMap' => [
                'modules/ecosmob/tenantmaster/tenantmaster' => 'app.php',
            ],
        ];

    }
}
