<?php
namespace app\modules\ecosmob\dbbackup;

use Yii;

/**
 * cdr module definition class
 */
class DbBackupModule extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\ecosmob\dbbackup\controllers';

    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('modules/ecosmob/dbbackup/' . $category, $message, $params, $language);
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
        Yii::$app->i18n->translations['modules/ecosmob/dbbackup/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath' => '@app/modules/ecosmob/dbbackup/messages',
            'fileMap' => [
                'modules/ecosmob/dbbackup/app' => 'app.php',
            ],
        ];
    }
}

