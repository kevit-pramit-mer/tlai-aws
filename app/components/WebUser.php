<?php

namespace app\components;

use Yii;
use yii\base\InvalidConfigException;
use yii\db\Query;
use yii\web\User;

/**
 * Class WebUser
 * @package app\components
 */
class WebUser extends User
{

    /**
     * To set dynamic session timeout value
     *
     * Here, We have extends web user class
     *
     * @throws InvalidConfigException
     */
    public function init()
    {
        parent::init();
        $authTimeout = (new Query())
            ->select('gwc_value')
            ->from('global_web_config')
            ->where('gwc_key = :gwc_key', ['gwc_key' => 'session_timeout'])
            ->scalar();

        if ($authTimeout) {
            $this->authTimeout = (abs($authTimeout) * 60) - 5;
        } else {
            $this->authTimeout = AUTH_TIMEOUT_DYNAMIC;
        }
        if (Yii::$app->session->get('loginAsExtension')) {
            $this->identityClass = 'app\modules\ecosmob\extension\models\Extension';
        }

    }
}