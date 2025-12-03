<?php

use app\modules\ecosmob\weekoff\WeekOffModule;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\weekoff\models\WeekOff */

$this->title = WeekOffModule::t('wo', 'update_weekoff') . $model->wo_day;
$this->params['breadcrumbs'][] = ['label' => WeekOffModule::t('wo', 'wo'), 'url' => ['index']];
$this->params['breadcrumbs'][] = WeekOffModule::t('wo', 'update');
$this->params['pageHead'] = $this->title;
?>

<div class="col-md-12 profile-contain">
    <div class="row">
        <div class="col-xl-9 col-md-7 col-xs-12">
            <div class="content">
                <div class="week-off-update">

                    <?= $this->render('_form', [
                        'model' => $model,
                    ]) ?>

                </div>
            </div>
        </div>
    </div>
</div>