<?php

use app\modules\ecosmob\manageagent\ManageAgentModule;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\manageagent\models\ManageAgent */

$this->title = ManageAgentModule::t('manageagent', 'update_manage_agent') . $model->adm_id;
$this->params['breadcrumbs'][] = ['label' => ManageAgentModule::t('manageagent', 'manage_agent'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->adm_id, 'url' => ['index']];
$this->params['breadcrumbs'][] = ManageAgentModule::t('manageagent', 'update');
$this->params['pageHead'] = $this->title;
?>

<div class="col-md-12 profile-contain">
    <div class="row">
        <div class="col-xl-9 col-md-7 col-xs-12">
            <div class="content">
                <div class="manage-agent-update">

                    <?= $this->render('_form', [
                        'model' => $model,
                    ]) ?>

                </div>
            </div>
        </div>
    </div>
</div>