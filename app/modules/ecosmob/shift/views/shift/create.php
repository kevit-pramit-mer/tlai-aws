<?php

use app\modules\ecosmob\shift\ShiftModule;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\shift\models\Shift */

$this->title = ShiftModule::t('sft', 'create');
$this->params['breadcrumbs'][] = ['label' => ShiftModule::t('sft', 'sft'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['pageHead'] = $this->title;
?>

<div class="col-md-12 profile-contain">
    <div class="row">
        <div class="col-xl-9 col-md-7 col-xs-12">
            <div class="content">
                <div class="shift-create">

                    <?= $this->render('_form', [
                        'model' => $model,
                    ]) ?>

                </div>
            </div>
        </div>
    </div>
</div>