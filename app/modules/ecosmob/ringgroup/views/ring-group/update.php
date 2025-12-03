<?php

use app\modules\ecosmob\ringgroup\RingGroupModule;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\ringgroup\models\RingGroup */

$this->title = RingGroupModule::t('rg', 'update_ringgroup') . $model->rg_name;
$this->params['breadcrumbs'][] = ['label' => RingGroupModule::t('rg', 'ringgroup'), 'url' => ['index']];
$this->params['breadcrumbs'][] = RingGroupModule::t('rg', 'update');
$this->params['pageHead'] = $this->title;
?>

<div class="col-md-12 profile-contain">
    <div class="row">
        <div class="col-xl-9 col-md-7 col-xs-12">
            <div class="content">
                <div class="ring-group-update">

                    <?= $this->render('_form', [
                        'model' => $model,
                    ]) ?>

                </div>
            </div>
        </div>
    </div>
</div>