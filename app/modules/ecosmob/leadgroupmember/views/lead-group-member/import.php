<?php


use app\modules\ecosmob\leadgroupmember\LeadGroupMemberModule;

/* @var $this yii\web\View */
/** @var $searchModel */
/** @var $dataProvider */
/* @var $importModel app\modules\ecosmob\leadgroupmember\models\LeadGroupMember */

$this->title                   = LeadGroupMemberModule::t( 'lead-group-member', 'import_leads' );
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'lead_group'), 'url' => ['/leadgroup/leadgroup/index']];
$this->params['breadcrumbs'][] = [ 'label' => LeadGroupMemberModule::t('lead-group-member', 'lead_grp_member'), 'url' => [ 'index','ld_id' => $_GET['ld_id'] ] ];
$this->params['breadcrumbs'][] = LeadGroupMemberModule::t( 'lead-group-member', 'import' );
$this->params['pageHead']      = $this->title;
?>

<div class="col-md-12 profile-contain">
    <div class="row">
        <div class="col-xl-9 col-md-7 col-xs-12">
            <div class="content">
                <div class="lead-group-member-import">
                    <?= $this->render('_import',
                        [
                            'importModel' => $importModel,
                            'searchModel' => $searchModel,
                            'dataProvider' => $dataProvider,
                        ]) ?>
                </div>
            </div>
        </div>
    </div>
</div>