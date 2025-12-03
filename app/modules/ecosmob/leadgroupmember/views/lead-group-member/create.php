<?php

use app\modules\ecosmob\leadgroupmember\LeadGroupMemberModule;


/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\leadgroupmember\models\LeadGroupMember */

$this->title = LeadGroupMemberModule::t('lead-group-member', 'create');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'lead_group'), 'url' => ['/leadgroup/leadgroup/index']];
$this->params['breadcrumbs'][] = ['label' => LeadGroupMemberModule::t('lead-group-member', 'lead_grp_member'), 'url' => ['index', 'ld_id' => $model->ld_id]];
$this->params['breadcrumbs'][] = $this->title;
$this->params['pageHead'] = $this->title;
?>

<div class="col-md-12 profile-contain">
    <div class="row">
        <div class="col-xl-9 col-md-7 col-xs-12">
            <div class="content">
                <div class="lead-group-member-create">

                    <?= $this->render('_form', [
                        'model' => $model,
                    ]) ?>

                </div>
            </div>
        </div>
    </div>
</div>