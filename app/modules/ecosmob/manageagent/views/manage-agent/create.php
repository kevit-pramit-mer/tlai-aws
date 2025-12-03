<?php

use app\modules\ecosmob\manageagent\ManageAgentModule;


/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\manageagent\models\ManageAgent */

$this->title = ManageAgentModule::t('manageagent', 'create_manage_agent');
$this->params['breadcrumbs'][] = ['label' => ManageAgentModule::t('manageagent', 'manage_agent'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['pageHead'] = $this->title;
?>

<div class="col-md-12 profile-contain">
    <div class="row">
        <div class="col-xl-9 col-md-7 col-xs-12">
            <div class="content">
                <div class="manage-agent-create">

                    <?= $this->render('_form', [
                        'model' => $model,
                    ]) ?>

                </div>
            </div>
        </div>
    </div>
</div>