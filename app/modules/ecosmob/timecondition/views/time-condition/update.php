<?php
/**
 * Created by PhpStorm.
 * User: vivek
 * Date: 2/4/19
 * Time: 10:44 AM
 */

use app\modules\ecosmob\timecondition\TimeConditionModule;

/* @var $model app\modules\ecosmob\timecondition\models\TimeCondition */

$this->title = TimeConditionModule::t('tc', 'update_tc');
$this->params['breadcrumbs'][] = [
    'label' => TimeConditionModule::t(
        'tc', 'time_conditions'
    ), 'url' => ['index']
];
$this->params['breadcrumbs'][] = TimeConditionModule::t('tc', 'update');
$this->params['pageHead'] = $this->title . ' : ' . $model->tc_name;
?>

<div class="col-md-12 profile-contain">
    <div class="row">
        <div class="col-xl-9 col-md-7 col-xs-12">
            <div class="content">
                <div class="time_condition_update" id="time_condition_update">

                    <?= $this->render('_form', [
                        'model' => $model,
                    ]) ?>

                </div>
            </div>
        </div>
    </div>
</div>
