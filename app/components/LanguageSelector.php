<?php

namespace app\components;

use yii\base\Application;
use yii\base\BootstrapInterface;
use Yii;
use yii\web\Cookie;

/**
 * Class LanguageSelector
 *
 * @package app\components
 */
class LanguageSelector implements BootstrapInterface
{
    /**
     * Set language into Cookie
     *
     * @param Application $app
     */
    public function bootstrap($app)
    {
        if(isset(Yii::$app->user->identity->adm_id)){
            $language_cookie = new Cookie([
                'name' => 'app_language',
                'value' => Yii::$app->user->identity->adm_language,
                'expire' => time() + 60 * 60 * 24 * 30 // 30 days
            ]);

            Yii::$app->response->cookies->add($language_cookie);
        }
        if (isset($app->request->cookies['app_language'])) {
            $users_language = (string)$app->request->cookies['app_language'];
        } else {
            $users_language = $app->request->getPreferredLanguage($app->params['list_of_languages']);
        }

        $app->language = $users_language;
    }
}
