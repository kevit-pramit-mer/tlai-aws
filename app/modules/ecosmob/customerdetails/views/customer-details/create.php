<?php

use app\modules\ecosmob\customerdetails\CustomerDetailsModule;


/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\customerdetails\models\CampaignMappingUser */

$this->title = CustomerDetailsModule::t('customerdetails', 'create_camp_mapp_user');
$this->params['breadcrumbs'][] = ['label' => CustomerDetailsModule::t('customerdetails', 'camp_mapp_user'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['pageHead'] = $this->title;
?>


<div class="col-md-12 profile-contain">
    <div class="row">
        <div class="col-xl-9 col-md-7 col-xs-12">
            <div class="content">
                <div class="campaign-mapping-user-create">

                    <?= $this->render('_form', [
                        'model' => $model,
                    ]) ?>

                </div>
            </div>
        </div>
    </div>
</div>