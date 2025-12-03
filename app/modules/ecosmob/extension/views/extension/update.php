<?php

use app\modules\ecosmob\extension\extensionModule;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\extension\models\Extension */
/* @var $call_setting_model */
/* @var $selectedDid */

$this->title = extensionModule::t('app', 'update_extension') . ' : ' . $model->em_extension_name;
$this->params['breadcrumbs'][] = ['label' => extensionModule::t('app', 'extensions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = extensionModule::t('app', 'update');
$this->params['pageHead'] = $this->title;
?>
<!-- <div id="main" class="extension-main main-full pl-0">
    <div class="row">
        <div class="col s12">
            <div class="container p-0">
                <div class="content-wrapper-before"></div> -->

<div class="col-md-12 profile-contain">
    <div class="row">
        <div class="col-xl-9 col-md-7 col-xs-12">
            <div class="content">
                <div class="extension-create">
                    <?= $this->render('_form', [
                        'model' => $model,
                        'call_setting_model' => $call_setting_model,
                        'selectedDid' => $selectedDid
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- </div>
</div>
</div>
</div> -->
