<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\ecosmob\crm\CrmModule;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\crm\models\LeadGroupMember */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
    <div class="col s12">
        <div id="input-fields" class="card card-tabs">
            <div class="card-content">
                <div class="lead-group-member-form"
                     id="lead-group-member-form">
                    <div class="row">
                        <div class="col s12">
                                <p>
                                <h5><?= CrmModule::t('crm', 'not_found'); ?></h5>
                                </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

