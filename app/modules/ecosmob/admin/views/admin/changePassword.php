<?php

use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use app\modules\ecosmob\admin\AdminModule;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\admin\models\AdminMaster */

$this->title = AdminModule::t('admin', 'change_password');
$this->params['breadcrumbs'][] = AdminModule::t('admin', 'change_password');
$this->params['pageHead'] = AdminModule::t('admin', 'change_password');

if (Yii::$app->session->get('loginAsExtension')) { ?>
<?= Yii::$app->view->renderFile('@app/views/auth/iframe/header.php') ?>
<div id="main" class="extension-main main-full">
    <div class="content-wrapper-before"></div>
    <div class="breadcrumbs-dark col s12 m6 ml-2" id="breadcrumbs-wrapper">
        <h5 class="breadcrumbs-title mt-0 mb-0"><?= (isset($this->params['pageHead']) ? $this->params['pageHead'] : "") ?></h5>
        <?= Breadcrumbs::widget([
            'tag' => 'ol',
            'options' => ['class' => 'breadcrumbs mb-0'/*, 'target' => 'myFrame'*/],
            'itemTemplate' => "<li class='breadcrumb-item'>{link}</li>\n",
            'homeLink' => [
                'label' => Yii::t('yii', 'Home'),
                'url' => Url::to(['/extension/extension/dashboard']),
                //'url' => 'javascript:void(0);',
                'target' => "extensionFrame",
                'encode' => false
            ],
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
    </div>
<?php } ?>
<div class="admin-change-password" id="admin-change-password">
    <?= $this->render('form/_changePassword',
        [
            'model' => $model,
        ]) ?>
</div>
    <?php if (Yii::$app->session->get('loginAsExtension')) { ?>
</div>
<?php } ?>