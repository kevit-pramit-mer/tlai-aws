<?php

use app\modules\ecosmob\disposition\DispositionModule;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\disposition\models\DispositionMaster */
/* @var $selectedContactedStatus array */
/* @var $selectedNoContactedStatus array */

$this->title = DispositionModule::t('disposition', 'update_disposition') . $model->ds_name;
$this->params['breadcrumbs'][] = ['label' => DispositionModule::t('disposition', 'disposition'), 'url' => ['index']];
$this->params['breadcrumbs'][] = DispositionModule::t('disposition', 'update');
$this->params['pageHead'] = $this->title;
?>

<div class="col-md-12 profile-contain">
    <div class="row">
        <div class="col-xl-9 col-md-7 col-xs-12">
            <div class="content">
                <div class="disposition-master-update">
                    <?= $this->render('_form', [
                        'model' => $model,
                        'selectedContactedStatus' => $selectedContactedStatus,
                        'selectedNoContactedStatus' => $selectedNoContactedStatus
                    ]) ?>

                </div>
            </div>
        </div>
    </div>
</div>
