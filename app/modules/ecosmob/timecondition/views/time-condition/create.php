<?php
use app\modules\ecosmob\timecondition\TimeConditionModule;

/* @var $model app\modules\ecosmob\timecondition\models\TimeCondition */

$this->title = TimeConditionModule::t('tc', 'create_tc');
$this->params['breadcrumbs'][] = [
    'label' => TimeConditionModule::t(
        'tc', 'time_conditions'
    ), 'url' => ['index']
];
$this->params['breadcrumbs'][] = TimeConditionModule::t('tc', 'create');
$this->params['pageHead'] = $this->title;
?>

<div class="col-md-12 profile-contain">
    <div class="row">
        <div class="col-xl-9 col-md-7 col-xs-12">
            <div class="content">
                <div class="time_condition" id="time_condition">

                    <?= $this->render('_form', [
                        'model' => $model,
                    ]) ?>

                </div>
            </div>
        </div>
    </div>
</div>
