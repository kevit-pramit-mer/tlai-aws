<?php

use yii\helpers\Html;
use app\modules\ecosmob\crm\CrmModule;


/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\crm\models\LeadGroupMember */

$this->title = CrmModule::t('crm', 'create_ld_grp');
$this->params['breadcrumbs'][] = ['label' => CrmModule::t('crm', 'ld_grp'), 'url' => ['index']];
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