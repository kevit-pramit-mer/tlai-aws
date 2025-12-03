<?php

use app\modules\ecosmob\dialplan\DialPlanModule;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\dialplan\models\OutboundDialPlansDetails */

$this->title = DialPlanModule::t('dp', 'create_outbound_dial_plan');
$this->params['breadcrumbs'][] = ['label' => DialPlanModule::t('dp', 'outbound_dialplans'), 'url' => ['index']];
$this->params['breadcrumbs'][] = DialPlanModule::t('dp', 'create');
$this->params['pageHead'] = $this->title;
?>

<div class="col-md-12 profile-contain">
    <div class="row">
        <div class="col-xl-9 col-md-7 col-xs-12">
            <div class="content">
                <div class="outbound-dial-plans-details-create">

                    <?= $this->render('_form', [
                        'model' => $model,
                    ]) ?>

                </div>
            </div>
        </div>
    </div>
</div>