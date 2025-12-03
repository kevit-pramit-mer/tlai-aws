<?php

use app\components\ConstantHelper;
use app\models\PhoneVendor;
use app\modules\ecosmob\ipprovisioning\IpprovisioningModule;
use app\modules\ecosmob\ipprovisioning\models\TemplateMaster;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model TemplateMaster */
/* @var $form yii\widgets\ActiveForm */
/* @var $deviceTemplate */
/* @var $templateDetails */
/* @var $codecs */
/* @var $acodec */

$brand = ArrayHelper::map(PhoneVendor::find()->all(), 'pv_id', 'pv_name');
?>

<div class="row">
    <div class="col s12">
        <?php $form = ActiveForm::begin([
            'id' => 'template-master-form',
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
                <div class="group-form" id="group-form">
                    <div class="row">
                        <div class="col s12 m4">
                            <div class="input-field">
                                <?= $form->field($model, 'template_name', [
                                    'inputOptions' => [
                                        'class' => 'form-control',
                                    ],
                                ])->textInput(['maxlength' => TRUE, 'placeholder' => ($model->getAttributeLabel('template_name'))]); ?>
                            </div>
                        </div>
                        <div class="col s12 m4">
                            <div class="input-field">
                                <?= $form->field($model, 'brand_id', ['options' => ['class' => '']])
                                    ->dropDownList($brand,
                                        ['prompt' => IpprovisioningModule::t('app', 'select')])
                                    ->label($model->getAttributeLabel('brand_id')); ?>
                            </div>
                        </div>
                        <div class="col s12 m4">
                            <div class="input-field">
                                <?= $form->field($model, 'device_template_id', ['options' => ['class' => '']])
                                    ->dropDownList([],
                                        ['prompt' => IpprovisioningModule::t('app', 'select')])
                                    ->label($model->getAttributeLabel('device_template_id')); ?>
                            </div>
                        </div>
                    </div>
                    <div id="template-details-section">
                        <?php if (!empty($templateDetails)) { ?>
                        <div class="row">
                            <div class="col s12">

                                    <div id="input-fields" class="card card-tabs">
                                        <div class="card-content p-0">
                                            <div class="card-header d-flex align-items-center justify-content-between w-100">
                                                <div class="header-title pt-2 pb-2">
                                                    <?= IpprovisioningModule::t('app', 'template_parameters') ?>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="col s12 mb-2 template-config">
                                                    <table>
                                                        <thead>
                                                        <tr>
                                                            <th class="action-width">
                                                                <!-- Action -->
                                                                <input type="checkbox" id="check-all-header"/>
                                                            </th>
                                                            <th class="parameter-width">Parameter</th>
                                                            <th class="source-width">Source</th>
                                                            <th class="value-width">Default Value</th>
                                                            <th class="line-width">Line Configuration</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php foreach ($templateDetails as $_templateDetails): ?>
                                                            <tr>
                                                                <td class="action-width"><?= Html::checkbox('TemplateDetails[' . $_templateDetails->id . '][is_checked]', $_templateDetails->is_checked == 1, ['value' => 1, 'uncheck' => 0]) ?></td>
                                                                <td class="parameter-width"><?= Html::encode($_templateDetails->parameter_label) ?></td>
                                                                <td class="source-width">
                                                                    <?= Html::dropDownList('TemplateDetails[' . $_templateDetails->id . '][value_source]', $_templateDetails->value_source, ['Device Specific' => 'Device Specific', 'Global' => 'Global', 'Variable' => 'Variable'], ['class' => 'value-source', 'data-id' => $_templateDetails->id, 'prompt' => 'Select', 'disabled' => $_templateDetails->is_writable == '0' ? true : false]) ?>
                                                                </td>
                                                                <td class="value-width">
                                                                    <div class="variable-div-<?= $_templateDetails->id ?> d-none">
                                                                        <?= Html::dropDownList('TemplateDetails[' . $_templateDetails->id . '][variable_source]', $_templateDetails->variable_source, ConstantHelper::getSourceVariable(), ['prompt' => 'Select', 'disabled' => $_templateDetails->is_writable == '0' ? true : false]) ?>
                                                                    </div>
                                                                    <div class="parameter-value-div-<?= $_templateDetails->id ?>">
                                                                        <?= $_templateDetails->input_type === 'checkbox'
                                                                            ? Html::checkbox('TemplateDetails[' . $_templateDetails->id . '][parameter_value]', $_templateDetails->parameter_value == 'true', [
                                                                                'value' => 'true',
                                                                                'uncheck' => 'false',
                                                                                'disabled' => $_templateDetails->is_writable == '0' ? true : false])
                                                                            : Html::textInput('TemplateDetails[' . $_templateDetails->id . '][parameter_value]', $_templateDetails->parameter_value, ['class' => 'form-control', 'disabled' => $_templateDetails->is_writable == '0' ? true : false])
                                                                        ?>
                                                                    </div>
                                                                </td>
                                                                <td class="line-width"><?= Html::encode($_templateDetails->voice_profile == 1 ? 'Yes' : 'No') ?></td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            </div>
                        </div>
                            <div class="row">
                                <div class="col s12" id="codec-setting-section">
                                    <div id="input-fields" class="card card-tabs">
                                        <div class="card-content p-0">
                                            <div class="card-header d-flex align-items-center justify-content-between w-100">
                                                <div class="header-title pt-2 pb-2">
                                                    <?= IpprovisioningModule::t('app', 'Codec Setting') ?>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="section"></div>
                                                <div class="row mt-3">
                                                    <div class="form-group col s12 p-0">

                                                        <div class="form-group field-groups-group_status selection-codes">
                                                            <div class=" col s4">
                                                                <div class="col no-padding">
                                                                    <label class="control-label"
                                                                           for="groups-group_status"><b><?= IpprovisioningModule::t('app', 'codecs') ?></b></label>

                                                                </div>
                                                                <select name="codec[all_codec][]"
                                                                        id='multiselect_to_0'
                                                                        class="multiselect form-control"
                                                                        size=""
                                                                        multiple="multiple"
                                                                        data-right="#multiselect_to_1"
                                                                        data-sort="false"
                                                                        data-right-all="#right_All_1"
                                                                        data-right-selected="#right_Selected_1"
                                                                        data-left-all="#left_All_1"
                                                                        data-left-selected="#left_Selected_1">
                                                                    <?php foreach ($codecs as $key => $val) {
                                                                        echo "<option value='" . $key . "'>" . $val . "</option>";
                                                                    } ?>
                                                                </select>
                                                            </div>

                                                            <div class="multiselect-btn-pad with-btn col s2 mt-3">
                                                                <button type="button" id="right_All_1"
                                                                        class="btn-margin-bottom btn btn-block multiselect_btn">
                                                                    <i class="material-icons">fast_forward</i>
                                                                </button>
                                                                <button type="button" id="right_Selected_1"
                                                                        class="btn-margin-bottom btn btn-block multiselect_btn">
                                                                    <i class="material-icons">keyboard_arrow_right</i>
                                                                </button>
                                                                <button type="button" id="left_Selected_1"
                                                                        class="btn-margin-bottom btn btn-block multiselect_btn">
                                                                    <i class="material-icons">keyboard_arrow_left</i>
                                                                </button>
                                                                <button type="button" id="left_All_1"
                                                                        class="btn btn-block multiselect_btn">
                                                                    <i class="material-icons">fast_rewind</i>
                                                                </button>
                                                            </div>


                                                            <div class=" col s4">
                                                                <div class="col no-padding">
                                                                    <label class="control-label"
                                                                           for="groups-group_status"><b><?= IpprovisioningModule::t('app', 'assigned_codec') ?></b></label>
                                                                </div>
                                                                <select name="codec[assign_codec][]"
                                                                        id="multiselect_to_1"
                                                                        class="multiselect form-control"
                                                                        size="" multiple="multiple">
                                                                    <?php foreach ($acodec as $akey => $aval) {
                                                                        echo "<option value='" . $akey . "'>" . $aval . "</option>";
                                                                    } ?>
                                                                </select>
                                                            </div>
                                                            <br>
                                                            <div class="with-btn col s2 setM-pad">
                                                                <button type="button" value="Up"
                                                                        class="btn-margin-bottom btn btn-block template_priority_btns">
                                                                    <i class="material-icons">arrow_upward</i>
                                                                </button>

                                                                <button type="button" value="Down"
                                                                        class="btn btn-block template_priority_btns">
                                                                    <i class="material-icons">arrow_downward</i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col s12 pb-3 d-flex align-items-center gap-10">
                <?= Html::a(IpprovisioningModule::t('app', 'cancel'),
                    ['index', 'page' => Yii::$app->session->get('page')],
                    ['class' => 'btn waves-effect waves-light bg-gray-200']) ?>
                <?= Html::submitButton($model->isNewRecord ? IpprovisioningModule::t('app', 'create') : IpprovisioningModule::t('app', 'update'),
                    [
                            'id' => 'btn-submit',
                        'class' => $model->isNewRecord
                            ? 'btn waves-effect waves-light amber darken-4'
                            :
                            'btn waves-effect waves-light cyan accent-8',
                    ]) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>

<script>
    var tempateId = '<?= $model->device_template_id ?>';
    $(document).ready(function () {
        $('.multiselect').multiselect({
            sort: false,
            fireSearch: function (value) {
                return value.length > 2;
            }
        });

        if (tempateId) {
            $('#templatemaster-brand_id').change();
        }

        $.each($('.value-source'), function (index, value) {
            var id = $(this).attr('data-id');
            if ($(this).val() == 'Variable') {
                $('.variable-div-' + id).removeClass('d-none');
                $('.parameter-value-div-' + id).addClass('d-none');
            } else {
                $('.variable-div-' + id).addClass('d-none');
                $('.parameter-value-div-' + id).removeClass('d-none');
            }
        });
        $('.value-source').change(function () {
            var id = $(this).attr('data-id');
            if ($(this).val() == 'Variable') {
                $('.variable-div-' + id).removeClass('d-none');
                $('.parameter-value-div-' + id).addClass('d-none');
            } else {
                $('.variable-div-' + id).addClass('d-none');
                $('.parameter-value-div-' + id).removeClass('d-none');
            }
        });

        $('#btn-submit').on('click', function (e) {
            e.preventDefault();

            var form = $(this);
            var templateName = $('#templatemaster-template_name').val();
            var csrfToken = $('meta[name="csrf-token"]').attr("content");

            $.ajax({
                type: 'POST',
                url: '<?= Url::to(['check-template-name']); ?>',
                data: {
                    _csrf: csrfToken,
                    template_name: templateName,
                    template_id: '<?= (!$model->isNewRecord ? $_GET['id'] : "") ?>'
                },
                success: function (response) {
                    var data = JSON.parse(response);
                    if (data.exists) {
                        $('.field-templatemaster-template_name').find('.help-block').text(data.message).show();
                    } else {
                        var isAnyCheckboxChecked = $('td.action-width input[type="checkbox"]:checked').length > 0;

                        if (!isAnyCheckboxChecked) {
                            alert('At least one checkbox must be checked.');
                        } else {
                            form.submit();
                        }
                    }
                },
                error: function () {
                    alert('An error occurred while checking the template name.');
                }
            });
        });

        $(document).on('click', '.template_priority_btns', function () {
            var op = $('#multiselect_to_1 option:selected');

            if (op.length) {
                ($(this).val() == 'Up') ?
                    op.first().prev().before(op) :
                    op.last().next().after(op);
            }
        });

    });
    $(document).on('change', '#templatemaster-brand_id', function () {
        var csrfToken = $('meta[name="csrf-token"]').attr("content");
        $.ajax({
            type: "POST",
            url: '<?= Url::to(['get-data']); ?>',
            data: {_csrf: csrfToken, 'brand_id': $(this).val()},
            success: function (data) {
                if (data) {
                    var result = JSON.parse(data);
                    $('#templatemaster-device_template_id').html(result.templateOption);
                    if (tempateId) {
                        $('#templatemaster-device_template_id').val(tempateId).trigger('change');
                    }
                }
            }
        });
    });
    $(document).on('change', '#templatemaster-device_template_id', function () {
        if (tempateId != $(this).val()) {
            var csrfToken = $('meta[name="csrf-token"]').attr("content");
            $.ajax({
                type: "POST",
                url: '<?= Url::to(['get-template-details']); ?>',
                data: {
                    _csrf: csrfToken,
                    'device_template_id': $(this).val(),
                    'template_id': '<?= (!$model->isNewRecord ? $_GET['id'] : "") ?>'
                },
                success: function (data) {
                    if (data) {
                        $('#template-details-section').html(data.templateDetailsHtml);
                    }
                }
            });
        }
    });
    $(document).on('change','#check-all-header', function () {
        // Get the state of the header checkbox
        var isChecked = this.checked;

        // Get all checkboxes in the "Action" column
        var checkboxes = document.querySelectorAll('td.action-width input[type="checkbox"]');
        checkboxes.forEach(function(checkbox) {
            checkbox.checked = isChecked;
        });
    });
</script>
