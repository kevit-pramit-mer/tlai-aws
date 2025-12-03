<?php

use app\modules\ecosmob\extension\extensionModule;


/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\extension\models\Extension */
/* @var $call_setting_model */

$this->title = extensionModule::t('app', 'create_extension');
$this->params['breadcrumbs'][] = ['label' => extensionModule::t('app', 'extensions'), 'url' => ['index']];
$this->params['breadcrumbs'][] = Yii::t('app', 'create');
$this->params['pageHead'] = $this->title;
?>
<div class="col-md-12 profile-contain">
    <div class="row">
        <div class="col-xl-9 col-md-7 col-xs-12">
            <div class="content">
                <div class="extension-create">
                    <?= $this->render('_form', [
                        'model' => $model,
                        'call_setting_model' => $call_setting_model
                    ]) ?>

                </div>
            </div>
        </div>
    </div>
</div>
