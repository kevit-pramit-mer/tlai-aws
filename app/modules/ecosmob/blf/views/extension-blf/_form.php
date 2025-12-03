<?php

use app\modules\ecosmob\blf\BlfModule;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $extension */
/* @var $digits */

?>

<div class="row">
    <div class="col s12 blf-form-data">
        <?php $form = ActiveForm::begin([
            'class' => 'row',
            'fieldConfig' => [
                'options' => [
                    'class' => 'input-field'
                ],
            ],
        ]); ?>
        <div id="input-fields" class="card card-tabs">
            <div class="form-card-header">
                <?= $this->title ?>
            </div>
            <div class="card-content pt-0">
                <div class="extension-blf-form" id="extension-blf-form">
                    <div class="col s12 m6 p-0 margin-top-15">
                        <table>
                            <thead>
                            <tr>
                                <th><?= BlfModule::t('app', 'blf_key') ?></th>
                                <th><?= BlfModule::t('app', 'extension') ?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php for ($i = 1; $i <= Yii::$app->params['BLF_DIGITS_LIMIT']; $i++) { ?>
                                <tr>
                                    <td><?= $i ?></td>
                                    <td class=""><?= HTML::dropDownList("digit_$i", (isset($digits[$i]) ? $digits[$i] : ''), ArrayHelper::map($extension[$i], 'em_extension_number', 'name'), ['class' => 'blf-ext', 'id' => "digit_$i", 'prompt' => 'Select Extension']); ?></td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col s12 pb-3 d-flex align-items-center gap-10">
                <?= Html::a(BlfModule::t('app', 'cancel'),
                    ['/extension/extension/dashboard'],
                    ['class' => 'btn waves-effect waves-light bg-gray-200']) ?>
                <?= Html::submitButton(BlfModule::t('app', 'apply'), [
                    'class' => 'btn waves-effect waves-light amber darken-4',
                    'name' => 'apply',
                    'value' => 'update'
                ]) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
<script>
    /*var digitLimit = "<?= Yii::$app->params['BLF_DIGITS_LIMIT'] ?>";
    for(var i=1; i<=digitLimit; i++) {
        var sel = $("#digit_" + i);
        sel.data("prev", sel.val());
        sel.data("text", $("#digit_" + i +" option:selected").text());
        sel.change(function (data) {
            var preVal = $(this).data('prev');
            var preText = $(this).data('text');
            var curId = $(this).attr('id');
            var curVal = $(this).val();
            $('.blf-ext').each(function () {
                var tempId = $(this).attr('id');
                if (tempId != curId) {
                    if(curVal != '') {
                        $("#" + tempId + " option[value='" + curVal + "']").remove();
                    }
                    if(preVal != ''){
                        $("#" + tempId).append($('<option></option>').val(preVal).html(preText));
                    }
                }
            });
        });
    }*/
    (function () {

        $(".blf-ext").each(function() {
            var curId = $(this).attr('id');
            var preVal;
            var preText;

            var sel = $("#"+curId);
            preVal = sel.val();
            preText = $("#" + curId +" option:selected").text();
            sel.change(function (data) {

                var curVal = $(this).val();
                console.log('p--'+preVal);
                console.log('t--'+preText);
                $('.blf-ext').each(function () {
                    var tempId = $(this).attr('id');
                    if (tempId != curId) {
                        if(curVal != '') {
                            $("#" + tempId + " option[value='" + curVal + "']").remove();
                        }
                        if(preVal != ''){

                            $("#" + tempId).append($('<option></option>').val(preVal).html(preText));
                        }
                    }
                });

                // Make sure the previous value is updated
                preVal = this.value;
                preText = $(this).find("option:selected").text();

            });
        });
    })();
</script>
