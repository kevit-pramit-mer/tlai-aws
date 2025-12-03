<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';
Yii::setAlias('@webroot', dirname(__DIR__) . '/web');

$config = [
    'id' => 'basic-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'app\commands',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
                'ipprovoisioning' => [
                    'class' => 'yii\log\FileTarget',
                    'categories' => ['ipprovoisioning'],
                    'logVars' => [],
                    'logFile' => '@webroot/logs/ipprovoisioning/ipprovoisioning-' . date('Y-m-d H:i:s') . '.log',
                    'maxFileSize' => 1024 * 5,
                    'maxLogFiles' => 20
                ],
            ],
        ],
        'session' => [
            'class' => 'yii\web\Session'
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host='.$params['MASTER_MYSQL_HOST'].';dbname='.$params['MASTER_DBNAME'],
            'username' => $params['MASTER_MYSQL_USERNAME'],
            'password' => $params['MASTER_MYSQL_PASSWORD'],
            'charset' => 'utf8',
            'attributes' => [PDO::ATTR_CASE => PDO::CASE_LOWER],
        ],
        'masterdb' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host='.$params['MASTER_MYSQL_HOST'].';dbname='.$params['MASTER_DBNAME'],
            'username' => $params['MASTER_MYSQL_USERNAME'],
            'password' => $params['MASTER_MYSQL_PASSWORD'],
            'charset' => 'utf8',
            'attributes' => [PDO::ATTR_CASE => PDO::CASE_LOWER],
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'defaultRoles' => ['guest', 'user'],
        ],
        'commonHelper' => [
            'class' => 'app\components\CommonHelper',
            'apiUrl' => $params['API_BASE_PATH'],
            'apiUsername' => $params['API_USERNAME'],
            'apiPassword' => $params['API_PASSWORD'],
            'apiSecret' => $params['API_SECRET'],
            'basePath' => $params['PROJECT_PATH'],
        ],
        'tragofoneHelper' => [
            'class' => 'app\components\TragofoneHelper',
        ],
        'ipprovisioningHelper' => [
            'class' => 'app\components\IPProvisioningHelper',
        ],
    ],
    'params' => $params,
    /*
    'controllerMap' => [
        'fixture' => [ // Fixture generation command line.
            'class' => 'yii\faker\FixtureController',
        ],
    ],
    */
];

if (YII_ENV_DEV) {
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
