<?php

use app\modules\ecosmob\globalconfig\models\GlobalConfig;
use yii\helpers\ArrayHelper;

$smtpDetails = GlobalConfig::find()->select(['gwc_key', 'gwc_value'])->asArray()->all();

if ($smtpDetails) {
    $smtpDetails = ArrayHelper::map($smtpDetails, 'gwc_key', 'gwc_value');

    return [
        //Yii::$app->mailer->setTransport([
        'class' => 'Swift_SmtpTransport',
        'host' => $smtpDetails['smtp_host'],
        'username' => $smtpDetails['smtp_username'],
        'password' => $smtpDetails['smtp_password'],
        'port' => $smtpDetails['smtp_port'],
        'encryption' => $smtpDetails['smtp_secure'],
        //])
    ];
}