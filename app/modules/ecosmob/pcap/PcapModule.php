<?php

namespace app\modules\ecosmob\pcap;

use Yii;
use yii\base\Module;

/**
 * pcap module definition class
 */
class PcapModule extends Module
{
    /**
     * {@inheritdoc}
     *
     * public $controllerNamespace = 'app\modules\ecosmob\pcap\controllers';
     *
     * /**
     * @param       $category
     * @param       $message
     * @param array $params
     * @param null $language
     *
     * @return string
     */
    public static function t($category, $message, $params = [], $language = NULL)
    {
        return Yii::t('modules/ecosmob/pcap/' . $category, $message, $params, $language);
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
        Yii::$app->i18n->translations['modules/ecosmob/pcap/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'en',
            'basePath' => '@app/modules/ecosmob/pcap/messages',
            'fileMap' => [
                'modules/ecosmob/pcap/pcap' => 'app.php',
            ],
        ];
    }
}
