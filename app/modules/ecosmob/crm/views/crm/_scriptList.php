<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\crm\models\LeadGroupMember */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
    <div class="col s12">
        <div class="lead-group-member-form" id="lead-group-member-form">
            <p>
                <?php
                echo $script->scr_description;
                ?>
            </p>
        </div>
    </div>
</div>
<style type="text/css">
    .mrg-btn {
        margin-bottom: 1em
    }
</style>
<script type="text/javascript">
    $(document).ready(function () {
        M.updateTextFields();
    });

</script>
