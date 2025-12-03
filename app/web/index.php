<?php

// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', false);
defined('YII_ENV') or define('YII_ENV', 'dev');
defined('AUTH_TIMEOUT_DYNAMIC') or define('AUTH_TIMEOUT_DYNAMIC', '1000');

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../config/web.php';

$app = (new yii\web\Application($config));
$app->on(yii\web\Application::EVENT_BEFORE_REQUEST, function (yii\base\Event $event) {
    $event->sender->response->on(yii\web\Response::EVENT_BEFORE_SEND, function ($e) {
        ob_start("ob_gzhandler");
    });
    $event->sender->response->on(yii\web\Response::EVENT_AFTER_SEND, function ($e) {
        ob_end_flush();
    });
});
$app->run();

