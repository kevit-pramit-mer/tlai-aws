<?php

use yii\helpers\Html;
use app\modules\ecosmob\crm\CrmModule;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\crm\models\LeadGroupMember */

$this->title = CrmModule::t('crm', 'update_crm') . $model->lg_first_name;
$this->params['breadcrumbs'][] = ['label' => CrmModule::t('crm', 'CRM'), 'url' => ['index']];
$this->params['breadcrumbs'][] = CrmModule::t('crm', 'update');
$this->params['pageHead'] = $this->title;
?>

<div class="col-md-12 profile-contain">
    <div class="row">
        <div class="col-xl-9 col-md-7 col-xs-12">
            <div class="content">
                <div class="lead-group-member-update">

                    <?= $this->render('_form', [
                        'model' => $model,
                    ]) ?>

                </div>
            </div>
        </div>
    </div>
</div>