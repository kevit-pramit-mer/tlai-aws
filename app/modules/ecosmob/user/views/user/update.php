<?php

use app\modules\ecosmob\user\UserModule;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\user\models\User */
/* @var $roles */

$this->title = UserModule::t('usr', 'update') . $model->adm_firstname . ' ' . $model->adm_lastname;
$this->params['breadcrumbs'][] = ['label' => UserModule::t('usr', 'usr'), 'url' => ['index']];
$this->params['breadcrumbs'][] = UserModule::t('usr', 'update');
$this->params['pageHead'] = $this->title;
?>

<div class="col-md-12 profile-contain">
    <div class="row">
        <div class="col-xl-9 col-md-7 col-xs-12">
            <div class="content">
                <div class="user-update">

                    <?= $this->render('_form', [
                        'model' => $model,
                        'roles' => $roles,
                    ]) ?>

                </div>
            </div>
        </div>
    </div>
</div>