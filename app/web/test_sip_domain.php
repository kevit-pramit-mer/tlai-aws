<?php
require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

$config = require(__DIR__ . '/../config/web.php');
(new yii\web\Application($config));

echo "SIP_DOMAIN: " . Yii::$app->params['SIP_DOMAIN'] . "\n";
echo "WSS_PORT: " . Yii::$app->params['WSS_PORT'] . "\n";
