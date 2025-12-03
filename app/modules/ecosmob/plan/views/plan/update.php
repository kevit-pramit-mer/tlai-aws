<?php

use yii\helpers\Html;
use app\modules\ecosmob\plan\PlanModule;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\plan\models\Plan */

$this->title = PlanModule::t('pl', 'update_plan'). $model->pl_name;
$this->params['breadcrumbs'][] = ['label' => PlanModule::t('pl', 'plan'), 'url' => ['index']];
$this->params['breadcrumbs'][] = PlanModule::t('pl', 'update');
$this->params['pageHead'] = $this->title;
?>


<div class="col-md-12 profile-contain">
    <div class="row">
        <div class="col-xl-9 col-md-7 col-xs-12">
            <div class="content">
                <div class="plan-update">

                    <?= $this->render('_form', [
                        'model' => $model,
                    ]) ?>

                </div>
            </div>
        </div>
    </div>
</div>