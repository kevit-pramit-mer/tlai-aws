<?php

use app\modules\ecosmob\group\GroupModule;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\group\models\Group */

$this->title = GroupModule::t('group', 'update_group') . $model->grp_name;
$this->params['breadcrumbs'][] = ['label' => GroupModule::t('group', 'grp'), 'url' => ['index']];
$this->params['breadcrumbs'][] = GroupModule::t('group', 'update');
$this->params['pageHead'] = $this->title;
?>

<div class="col-md-12 profile-contain">
    <div class="row">
        <div class="col-xl-9 col-md-7 col-xs-12">
            <div class="content">
                <div class="group-update">

                    <?= $this->render('_form', [
                        'model' => $model,
                    ]) ?>

                </div>
            </div>
        </div>
    </div>
</div>