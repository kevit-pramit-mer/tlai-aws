<?php

use app\modules\ecosmob\whitelist\WhiteListModule;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\whitelist\models\WhiteList */

$this->title = WhiteListModule::t('wl', 'create_white_list');
$this->params['breadcrumbs'][] = ['label' => WhiteListModule::t('wl', 'white_list'), 'url' => ['index']];
$this->params['breadcrumbs'][] = WhiteListModule::t('wl', 'create');
$this->params['pageHead'] = $this->title;
?>

<div class="col-md-12 profile-contain">
    <div class="row">
        <div class="col-xl-9 col-md-7 col-xs-12">
            <div class="content">
                <div class="white-list-create">

                    <?= $this->render('_form', [
                        'model' => $model,
                    ]) ?>

                </div>
            </div>
        </div>
    </div>
</div>