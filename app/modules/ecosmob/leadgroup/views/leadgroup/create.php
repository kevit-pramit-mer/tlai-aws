<?php

use app\modules\ecosmob\leadgroup\LeadgroupModule;


/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\leadgroup\models\LeadgroupMaster */

$this->title = LeadgroupModule::t('leadgroup', 'create');
$this->params['breadcrumbs'][] = ['label' => LeadgroupModule::t('leadgroup', 'lead_grp'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['pageHead'] = $this->title;
?>

<div class="col-md-12 profile-contain">
    <div class="row">
        <div class="col-xl-9 col-md-7 col-xs-12">
            <div class="content">
                <div class="leadgroup-master-create">

                    <?= $this->render('_form', [
                        'model' => $model,
                    ]) ?>

                </div>
            </div>
        </div>
    </div>
</div>