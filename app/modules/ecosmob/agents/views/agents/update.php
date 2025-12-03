<?php

use app\modules\ecosmob\agents\AgentsModule;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\agents\models\AdminMaster */

$this->title = Yii::t('app', AgentsModule::t('agents', 'update') . ' : ' . $model->adm_firstname, [
    'nameAttribute' => '' . $model->adm_id,
]);
$this->params['breadcrumbs'][] = ['label' => AgentsModule::t('agents', 'Agents'), 'url' => ['index']];
$this->params['breadcrumbs'][] = AgentsModule::t('agents', 'update');
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