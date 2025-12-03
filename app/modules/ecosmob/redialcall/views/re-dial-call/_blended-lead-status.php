<?php

use app\modules\ecosmob\redialcall\ReDialCallModule;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\crm\models\LeadGroupMember */
/* @var $form yii\widgets\ActiveForm */
/* @var $doneBlendedCount */
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
                            <h6><?= ReDialCallModule::t('redialcall', 'Blended Campaign Count') . $doneBlendedCount; ?></h6>
                            <?php if ($doneBlendedCount == 0) { ?>
                                <script>
                                    $('.update-btn').hide();
                                    //$('.update-btn').attr('disabled', 'disabled');
                                </script>
                            <?php } else { ?>
                                <script>
                                    $('.update-btn').show();
                                    $('.update-btn').removeAttr('disabled');
                                </script>
                            <?php } ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

