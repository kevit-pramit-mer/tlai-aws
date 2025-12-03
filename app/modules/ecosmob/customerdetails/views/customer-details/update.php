<?php

use app\modules\ecosmob\customerdetails\CustomerDetailsModule;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\customerdetails\models\CampaignMappingUser */

$this->title = CustomerDetailsModule::t('customerdetails', 'update_camp_mapp_user' . $model->id, [
    'nameAttribute' => '' . $model->id,
]);
$this->params['breadcrumbs'][] = ['label' => CustomerDetailsModule::t('customerdetails', 'camp_mapp_user'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['index']];
$this->params['breadcrumbs'][] = CustomerDetailsModule::t('customerdetails', 'update');
$this->params['pageHead'] = $this->title;
?>

<div class="col-md-12 profile-contain">
    <div class="row">
        <div class="col-xl-9 col-md-7 col-xs-12">
            <div class="content">
                <div class="campaign-mapping-user-update">

                    <?= $this->render('_form', [
                        'model' => $model,
                    ]) ?>

                </div>
            </div>
        </div>
    </div>
</div>