<?php

use app\modules\ecosmob\didmanagement\DidManagementModule;


/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\didmanagement\models\DidManagement */
/* @var $didTimeBasedModel \app\models\DidTimeBased*/

$this->title = DidManagementModule::t('did', 'create_did');
$this->params['breadcrumbs'][] = ['label' => DidManagementModule::t('did', 'did_mang'), 'url' => ['index']];
$this->params['breadcrumbs'][] = DidManagementModule::t('did', 'create');
$this->params['pageHead'] = $this->title;
?>

<div class="col-md-12 profile-contain">
    <div class="row">
        <div class="col-xl-9 col-md-7 col-xs-12">
            <div class="content">
                <div class="did-master-create">

                    <?= $this->render('_formCreate', [
                        'model' => $model,
                        'didTimeBasedModel' => $didTimeBasedModel
                    ]) ?>

                </div>
            </div>
        </div>
    </div>
</div>