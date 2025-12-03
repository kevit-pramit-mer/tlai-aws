<?php

namespace app\modules\ecosmob\parkinglot;

use Yii;
use yii\base\Module;

/**
 * ParkingLotModule definition class
 */
class ParkingLotModule extends Module
{

    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\ecosmob\parkinglot\controllers';

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
        return Yii::t('modules/ecosmob/parkinglot/' . $category, $message, $params, $language);
    }

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        $this->registerTranslations();

        // custom initialization code goes here
    }

    /**
     *
     */
    public function registerTranslations()
    {
        Yii::$app->i18n->translations['modules/ecosmob/parkinglot/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath' => '@app/modules/ecosmob/parkinglot/messages',
            'fileMap' => [
                'modules/ecosmob/parkinglot/parkinglot' => 'app.php',
            ],
        ];
    }
}
