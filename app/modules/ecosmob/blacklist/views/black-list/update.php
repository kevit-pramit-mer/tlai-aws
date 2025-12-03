<?php

use app\modules\ecosmob\blacklist\BlackListModule;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\blacklist\models\BlackList */

$this->title = BlackListModule::t('bl', 'update_black_list') . $model->bl_number;
$this->params['breadcrumbs'][] = ['label' => BlackListModule::t('bl', 'black_lists'), 'url' => ['index']];
$this->params['breadcrumbs'][] = BlackListModule::t('bl', 'update');
$this->params['pageHead'] = $this->title;
?>

<div class="col-md-12 profile-contain">
    <div class="row">
        <div class="col-xl-9 col-md-7 col-xs-12">
            <div class="content">
                <div class="black-list-update">

                    <?= $this->render('_form', [
                        'model' => $model,
                    ]) ?>

                </div>
            </div>
        </div>
    </div>
</div>