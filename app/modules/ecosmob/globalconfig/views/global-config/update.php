<?php

use app\modules\ecosmob\globalconfig\GlobalConfigModule;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\globalconfig\models\GlobalConfig */

$this->title = GlobalConfigModule::t('gc', 'update_global_config') . $model->gwc_key;
$this->params['breadcrumbs'][] = ['label' => GlobalConfigModule::t('gc', 'global_configurations'), 'url' => ['index']];
$this->params['breadcrumbs'][] = GlobalConfigModule::t('gc', 'update');
$this->params['pageHead'] = $this->title;
?>

<div class="col-md-12 profile-contain">
    <div class="row">
        <div class="col-xl-9 col-md-7 col-xs-12">
            <div class="content">
                <div class="global-config-update">

                    <?= $this->render('_form', [
                        'model' => $model,
                    ]) ?>

                </div>
            </div>
        </div>
    </div>
</div>