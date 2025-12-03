<?php

use app\modules\ecosmob\fraudcall\FraudCallModule;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\fraudcall\models\FraudCallDetection */

$this->title = FraudCallModule::t('fcd',
        'update_fcd') . $model->fcd_rule_name;
$this->params['breadcrumbs'][] = ['label' => FraudCallModule::t('fcd', 'fcds'), 'url' => ['index']];
$this->params['breadcrumbs'][] = FraudCallModule::t('fcd', 'update');
$this->params['pageHead'] = $this->title;
?>

<div class="col-md-12 profile-contain">
    <div class="row">
        <div class="col-xl-9 col-md-7 col-xs-12">
            <div class="content">
                <div class="fraud-call-detection-update">

                    <?= $this->render('_form',
                        [
                            'model' => $model,
                        ]) ?>

                </div>
            </div>
        </div>
    </div>
</div>