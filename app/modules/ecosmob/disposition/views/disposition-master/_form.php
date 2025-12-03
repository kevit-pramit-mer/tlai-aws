<?php

use app\modules\ecosmob\disposition\DispositionModule;
use app\modules\ecosmob\dispositionType\models\DispositionType;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\disposition\models\DispositionMaster */
/* @var $form yii\widgets\ActiveForm */
/* @var $selectedContactedStatus */
/* @var $selectedNoContactedStatus */

?>
<div class="row">
    <div class="col s12 m-p-0">
        <?php $form = ActiveForm::begin([
            'class' => 'row',
            'fieldConfig' => [
                'options' => [
                    'class' => 'input-field',
                ],
            ],
        ]); ?>
        <div id="input-fields" class="card card-tabs">
            <div class="form-card-header">
                <?= $this->title ?>
            </div>
            <div class="card-content">
                <div class="disposition-master-form" id="disposition-master-form">
                    <div class="row">
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($model,
                                    'ds_name', [
                                        'inputOptions' => [
                                            //'autofocus' => 'autofocus',
                                            'class' => 'form-control',
                                        ],
                                    ])
                                    ->textInput(['maxlength' => true])
                                    ->label($model->getAttributeLabel('ds_name')); ?>
                            </div>
                        </div>
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($model,
                                    'ds_description')->textArea(['maxlength' => true, 'class' => 'materialize-textarea'])
                                    ->label($model->getAttributeLabel('ds_description')); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12 m6">
                            <div class="input-field multiSelect">
                                <?= $form->field($model, 'ds_contacted_status[]', ['options' => ['id' => 'select2-contacted','class' => '']])
                                    ->dropDownList(DispositionType::getContactedDispositionStatus($model->ds_id, $selectedNoContactedStatus),
                                        [
                                            'multiple' => 'multiple',
                                            'options' => $selectedContactedStatus
                                        ]
                                    )->label($model->getAttributeLabel('ds_contacted_status')); ?>
                            </div>
                        </div>
                        <div class="col s12 m6">
                            <div class="input-field multiSelect">
                                <?= $form->field($model, 'ds_non_contacted_status[]', ['options' => ['id' => 'select2-nonContacted','class' => '']])
                                    ->dropDownList(DispositionType::getNonContactedDispositionStatus($model->ds_id, $selectedContactedStatus),
                                        [
                                            'multiple' => 'multiple',
                                            'options' => $selectedNoContactedStatus,
                                        ]
                                    )->label($model->getAttributeLabel('ds_non_contacted_status')); ?>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12">
                        <span style="font-size: 12px;"><?= DispositionModule::t('disposition', 'note') ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col s12 pb-3 d-flex align-items-center gap-10">
                <?= Html::a(DispositionModule::t('disposition', 'cancel'),
                    ['index', 'page' => Yii::$app->session->get('page')],
                    ['class' => 'btn waves-effect waves-light bg-gray-200']) ?>
                <?= Html::submitButton($model->isNewRecord ? DispositionModule::t('disposition', 'create') : DispositionModule::t('disposition',
                    'update'), [
                    'class' => $model->isNewRecord ? 'btn waves-effect waves-light amber darken-4' :
                        'btn waves-effect waves-light cyan accent-8'
                ]) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>

<?php
$ds_type = explode(',', $model->ds_type);
$ds_type = json_encode($ds_type, TRUE);
$dispositionStatusList = json_encode(DispositionType::getDispositionStatus($model->ds_id), JSON_HEX_APOS);
?>
<script>

    let statusOptions = '<?=$dispositionStatusList?>';

    $(document).ready(function () {

        $("#dispositionmaster-ds_contacted_status").on("select2:select", function (e) {
            var selectedOption = e.params.data;
            $("#dispositionmaster-ds_non_contacted_status option[value='" + selectedOption.id + "']").remove();
            $("#dispositionmaster-ds_non_contacted_status").trigger("change"); // Update the Select2 widget
        });
        $("#dispositionmaster-ds_contacted_status").on("select2:unselect", function (e) {
            var unselectedOption = e.params.data;
            var optionValue = unselectedOption.id;
            var newOption = new Option(unselectedOption.text, optionValue, false, false);
// Append the new option to the Select2 dropdown
            $('#dispositionmaster-ds_non_contacted_status').append(newOption).trigger('change');
            $('#dispositionmaster-ds_non_contacted_status').trigger("change"); // Update the Select2 widget
        });

        $("#dispositionmaster-ds_non_contacted_status").on("select2:select", function (e) {
            var selectedOption = e.params.data;
            $("#dispositionmaster-ds_contacted_status option[value='" + selectedOption.id + "']").remove();
            $("#dispositionmaster-ds_contacted_status").trigger("change"); // Update the Select2 widget
        });

        $("#dispositionmaster-ds_non_contacted_status").on("select2:unselect", function (e) {
            var unselectedOption = e.params.data;
            var optionValue = unselectedOption.id;
            var newOption = new Option(unselectedOption.text, optionValue, false, false);
            $('#dispositionmaster-ds_contacted_status').append(newOption).trigger('change');
            $('#dispositionmaster-ds_contacted_status').trigger("change");
        });

        let dispositionStatusData = JSON.parse(statusOptions);
        var disStatusArr = {};

        $.each(dispositionStatusData, function (index, value) {
            disStatusArr[value] = null;
        });
        $('.chips').chips();
        $('.chips-autocomplete').chips({
            autocompleteOptions: {
                data: disStatusArr,
                limit: Infinity,
                minLength: 1,
            },
            placeholder: '<?php echo DispositionModule::t('disposition', 'ds_type') ?>',
            secondaryPlaceholder: '<?php echo DispositionModule::t('disposition', '+type') ?>',
            onChipAdd: (event) => {
                const addedChip = event[0].M_Chips.chipsData[event[0].M_Chips.chipsData.length - 1];

                // Check if the added chip's tag is in the autocomplete data
                if (!disStatusArr.hasOwnProperty(addedChip.tag)) {
                    // Remove the chip if it's not a valid option
                    event[0].M_Chips.deleteChip(event[0].M_Chips.chipsData.length - 1);
                } else {
                    // Handle chip addition (e.g., update an input field) for valid chips
                    var data = '';
                    $(event[0].M_Chips.chipsData).each(function (index) {
                        if (index > 0) {
                            data += ',';
                        }
                        data += event[0].M_Chips.chipsData[index].tag;
                    });
                    $('#dispositionmaster-ds_type').val(data);
                }
            },
            onChipDelete: (event) => {
                var data = '';
                $(event[0].M_Chips.chipsData).each(function (index) {
                    if (index > 0) {
                        data += ',';
                    }
                    data += event[0].M_Chips.chipsData[index].tag;
                });
                $('#dispositionmaster-ds_type').val(data);
            },
        });


        //$('.chips').chips();
        //$('.chips-autocomplete').chips({
        //    autocompleteOptions: {
        //        data: statusOptions,
        //        limit: Infinity,
        //        minLength: 1
        //    },
        //    placeholder: '<?php //= DispositionModule::t('disposition', 'ds_type') ?>//',
        //    secondaryPlaceholder: '<?php //= DispositionModule::t('disposition', '+type') ?>//',
        //    onChipAdd: (event) => {
        //        var data = '';
        //        $(event[0].M_Chips.chipsData).each(function (index) {
        //            if (index > 0) {
        //                data += ',';
        //            }
        //            data += event[0].M_Chips.chipsData[index].tag;
        //        });
        //        $('#dispositionmaster-ds_type').val(data);
        //    },
        //    onChipDelete: (event) => {
        //        var data = '';
        //        $(event[0].M_Chips.chipsData).each(function (index) {
        //            if (index > 0) {
        //                data += ',';
        //            }
        //            data += event[0].M_Chips.chipsData[index].tag;
        //        });
        //        $('#dispositionmaster-ds_type').val(data);
        //    },
        //});
        //var newrecord_ = '<?php //echo $model->isNewRecord ?>//';
        //
        //if (!newrecord_) {
        //    var types_ = '<?php //echo $ds_type  ?>//';
        //    var elem = document.getElementById('chips');
        //    var instance = M.Chips.getInstance(elem);
        //    JSON.parse(types_, function (key, value) {
        //        if (typeof value == 'string') {
        //            instance.addChip({
        //                tag: value
        //            });
        //        }
        //    });
        //}

        setInterval(function () {
            if($("#dispositionmaster-ds_contacted_status").val() == '') {
                $('.field-dispositionmaster-ds_contacted_status .select-wrapper .select2-container .selection .select2-selection--multiple ul li .select2-search__field').attr('placeholder', 'Select Contacted');
                $('.field-dispositionmaster-ds_contacted_status .select-wrapper .select2-container .selection .select2-selection--multiple ul li .select2-search__field').css('width', 'auto');
            }
            if($("#dispositionmaster-ds_non_contacted_status").val() == '') {
                $('.field-dispositionmaster-ds_non_contacted_status .select-wrapper .select2-container .selection .select2-selection--multiple ul li .select2-search__field').attr('placeholder', 'Select No Contacted');
                $('.field-dispositionmaster-ds_non_contacted_status .select-wrapper .select2-container .selection .select2-selection--multiple ul li .select2-search__field').css('width', 'auto');
            }
        }, 100);

    })
</script>
