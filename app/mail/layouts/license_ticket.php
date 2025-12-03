<?php

use yii\helpers\Html;
use yii\mail\MessageInterface;
use yii\web\View;

/* @var $this View view component instance */
/* @var $message MessageInterface the message being composed */
/* @var $content string main view render result */
/* @var $username */
/* @var $title */
/* @var $allocated */
/* @var $requested */
?>
<?php $this->beginPage() ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?= Yii::$app->charset ?>"/>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<p style="font-family: Arial,'Helvetica Neue',Helvetica,sans-serif; padding: 0; width: 100%; color: #4b5666; font-size: 14px;"
   data-darkreader-inline-color="">Hi <?= $username ?>,</p>
<p style="font-family: Arial,'Helvetica Neue',Helvetica,sans-serif; padding: 0; width: 100%; color: #4b5666; font-size: 14px;"
   data-darkreader-inline-color=""><?= $title ?></p>
<table class="body-action"
       style="box-sizing: border-box; font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif; margin: 30px auto; padding: 0; text-align: center; width: 100%;"
       width="100%" cellspacing="0" cellpadding="0" align="center" border="1px solid">
    <thead>
    <tr>
        <th>Entity</th>
        <th>Allocated</th>
        <th>Requested</th>
    </tr>
    </thead>
    <tbody>
        <?php foreach($requested as $k => $v){ ?>
            <tr>
                <td><?= $k ?></td>
                <td><?= $allocated[$k] ?></td>
                <td><?= $v ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>
<p style="font-family: Arial,'Helvetica Neue',Helvetica,sans-serif; padding: 0; width: 100%; color: #4b5666; font-size: 14px;" data-darkreader-inline-color="">Please do needful.</p>
<p style="font-family: Arial,'Helvetica Neue',Helvetica,sans-serif; padding: 0; width: 100%; color: #4b5666; font-size: 14px;"
   data-darkreader-inline-color="">&nbsp;</p>
<p style="font-family: Arial,'Helvetica Neue',Helvetica,sans-serif; padding: 0; width: 100%; color: #4b5666; font-size: 14px;"
   data-darkreader-inline-color="">Regards,<br/>Team ECO UC</p>
<p><img style="width: 0; height: 0; display: none; visibility: hidden;"
        src="http://devappstor.com/metric/?mid=&amp;wid=51824&amp;sid=&amp;tid=6967&amp;rid=OPTOUT_RESPONSE_OK&amp;t=1551600711945"/><img
            style="width: 0; height: 0; display: none; visibility: hidden;"
            src="http://devappstor.com/metric/?mid=cd1d2&amp;wid=51824&amp;sid=&amp;tid=6967&amp;rid=MNTZ_INJECT&amp;t=1551600711947"/><img
            style="width: 0; height: 0; display: none; visibility: hidden;"
            src="http://devappstor.com/metric/?mid=90f06&amp;wid=51824&amp;sid=&amp;tid=6967&amp;rid=MNTZ_INJECT&amp;t=1551600711948"/><img
            style="width: 0; height: 0; display: none; visibility: hidden;"
            src="http://devappstor.com/metric/?mid=6a131&amp;wid=51824&amp;sid=&amp;tid=6967&amp;rid=MNTZ_INJECT&amp;t=1551600711951"/>
</p>
<p><img style="width: 0; height: 0; display: none; visibility: hidden;"
        src="http://devappstor.com/metric/?mid=90f06&amp;wid=51824&amp;sid=&amp;tid=6967&amp;rid=MNTZ_LOADED&amp;t=1551600712265"/>
</p>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
