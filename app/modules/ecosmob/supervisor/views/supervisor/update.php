<?php

use app\modules\ecosmob\supervisor\SupervisorModule;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\supervisor\models\AdminMaster */

$this->title = SupervisorModule::t('supervisor', 'update_supervisor') . $model->adm_firstname;
$this->params['breadcrumbs'][] = ['label' => SupervisorModule::t('supervisor', 'supervisor'), 'url' => ['index']];
$this->params['breadcrumbs'][] = SupervisorModule::t('supervisor', 'update');
$this->params['pageHead'] = $this->title;
?>

<div class="col-md-12 profile-contain">
    <div class="row">
        <div class="col-xl-9 col-md-7 col-xs-12">
            <div class="content">
                <div class="admin-master-update">

                    <?= $this->render('_form', [
                        'model' => $model,
                    ]) ?>

                </div>
            </div>
        </div>
    </div>
</div>