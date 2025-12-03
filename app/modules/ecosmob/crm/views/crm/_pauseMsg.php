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
        <div class="lead-group-member-form" id="lead-group-member-form">
            <p>
            <?php echo CrmModule::t('crm', 'pause_msg'); ?>
            </p>
        </div>
    </div>
    <!-- <div class="col s12">
        <div id="input-fields" class="card card-tabs">
            <div class="card-content">
                <div class="lead-group-member-form"
                     id="lead-group-member-form">
                    <div class="row">
                        <div class="col s1"></div>
                        <div class="col s10">
                            <div class="input-field col s12">
                                <p>
                                <center><h6><?php echo CrmModule::t('crm', 'pause_msg'); ?></h6></center>
                                </p>
                            </div>
                        </div>
                        <div class="col s1"></div>
                    </div>
                </div>

            </div>
        </div>
    </div> -->
</div>

