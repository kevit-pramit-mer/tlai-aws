<?php

use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use app\modules\ecosmob\extension\extensionModule;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\extension\models\Extension */

$this->title = extensionModule::t('app', 'update_extension') . ':' . $model->em_extension_number;
$this->params['breadcrumbs'][] = extensionModule::t('app', 'update_profile');
$this->params['pageHead'] = $this->title;
?>
<?= Yii::$app->view->renderFile('@app/views/auth/iframe/header.php') ?>
<div id="main" class="extension-main main-full">
    <div class="row">
        <div class="col s12">
            <div class="container">
                <div class="content-wrapper-before"></div>
                <div class="breadcrumbs-dark col s12 m6" id="breadcrumbs-wrapper">
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
                <div class="col-md-12 profile-contain">
                    <div class="row">
                        <div class="col-xl-9 col-md-7 col-xs-12">
                            <div class="content">
                                <div class="extension-update">

                                    <?= $this->render('_updateExtension', [
                                        'model' => $model,
                                    ]) ?>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
