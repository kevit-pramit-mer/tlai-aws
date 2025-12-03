<?php

use app\modules\ecosmob\dispositionType\DispositionTypeModule;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\dispositionType\models\DispositionType */

$this->title = DispositionTypeModule::t('dispositionType', 'update') .' '.DispositionTypeModule::t('dispositionType', 'disp_status'). ': ' . $model->ds_type;
$this->params['breadcrumbs'][] = ['label' => DispositionTypeModule::t('dispositionType', 'disp_status'), 'url' => ['index']];
$this->params['breadcrumbs'][] = DispositionTypeModule::t('dispositionType', 'update');
$this->params['pageHead'] = $this->title;
?>


<div class="col-md-12 profile-contain">
    <div class="row">
        <div class="col-xl-9 col-md-7 col-xs-12">
            <div class="content">
                <div class="disposition-type-update">
                    <?= $this->render('_form', [
                        'model' => $model,
                    ]) ?>
                </div>
            </div>
        </div>
    </div>
</div>
