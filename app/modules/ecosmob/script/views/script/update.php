<?php

use app\modules\ecosmob\script\ScriptModule;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\script\models\Script */

$this->title = ScriptModule::t('script', 'update_script') . $model->scr_name;
$this->params['breadcrumbs'][] = ['label' => ScriptModule::t('script', 'script'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ScriptModule::t('script', 'update');
$this->params['pageHead'] = $this->title;
?>


<div class="col-md-12 profile-contain">
    <div class="row">
        <div class="col-xl-9 col-md-7 col-xs-12">
            <div class="content">
                <div class="script-update">

                    <?= $this->render('_form', [
                        'model' => $model,
                    ]) ?>

                </div>
            </div>
        </div>
    </div>
</div>