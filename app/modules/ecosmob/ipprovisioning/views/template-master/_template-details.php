<?php

use yii\helpers\Html;
use app\components\ConstantHelper;
use app\modules\ecosmob\ipprovisioning\IpprovisioningModule;

/* @var $templateDetails */
/* @var $codecs */
/* @var $acodec */
?>
<style>
    table thead tr th, table tbody tr td {
        word-wrap: break-word;
    }

    .line-width {
        min-width: 90px !important;
        max-width: 90px !important;
    }

    .action-width {
        min-width: 50px !important;
        max-width: 50px !important;
    }

    .parameter-width {
        min-width: 170px !important;
        max-width: 170px !important;
    }

    .value-width {
        min-width: 150px !important;
        max-width: 150px !important;
    }

    .source-width {
        min-width: 130px !important;
        max-width: 130px !important;
    }

    .value-source {
        width: 160px !important;
    }

    .variable-source {
        width: 245px !important;
    }
</style>
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
                            <?php foreach ($templateDetails as $key => $_templateDetails): ?>
                                <?= Html::hiddenInput('TemplateDetails[' . $key . '][parameter_name]', $_templateDetails->parameter_name) ?>
                                <?= Html::hiddenInput('TemplateDetails[' . $key . '][is_object]', $_templateDetails->is_object) ?>
                                <?= Html::hiddenInput('TemplateDetails[' . $key . '][is_writable]', $_templateDetails->is_writable) ?>
                                <?= Html::hiddenInput('TemplateDetails[' . $key . '][value_type]', $_templateDetails->value_type) ?>
                                <?= Html::hiddenInput('TemplateDetails[' . $key . '][parameter_label]', $_templateDetails->parameter_label) ?>
                                <?= Html::hiddenInput('TemplateDetails[' . $key . '][input_type]', $_templateDetails->input_type) ?>
                                <?= Html::hiddenInput('TemplateDetails[' . $key . '][is_primary]', $_templateDetails->is_primary) ?>
                                <?= Html::hiddenInput('TemplateDetails[' . $key . '][voice_profile]', $_templateDetails->voice_profile) ?>
                                <?= Html::hiddenInput('TemplateDetails[' . $key . '][parameter_value]', $_templateDetails->parameter_value) ?>
                                <?= Html::hiddenInput('TemplateDetails[' . $key . '][codec]', $_templateDetails->codec) ?>
                                <?= Html::hiddenInput('TemplateDetails[' . $key . '][value_source]', 'Device Specific') ?>
                                <?= Html::hiddenInput('TemplateDetails[' . $key . '][variable_source]', '') ?>
                                <tr>
                                    <td class="action-width"><?= Html::checkbox('TemplateDetails[' . $key . '][is_checked]', 0, ['value' => 1, 'uncheck' => 0]) ?></td>
                                    <td class="parameter-width"><?= Html::encode($_templateDetails->parameter_label) ?></td>
                                    <td class="source-width">
                                        <?= Html::dropDownList('TemplateDetails[' . $key . '][value_source]', 'Device Specific', ['Device Specific' => 'Device Specific', 'Global' => 'Global', 'Variable' => 'Variable'], ['class' => 'value-source', 'prompt' => 'Select', 'disabled' => $_templateDetails->is_writable == '0' ? true : false]) ?>
                                    </td>
                                    <td class="value-width">
                                        <div class="variable-div d-none">
                                            <?= Html::dropDownList('TemplateDetails[' . $key . '][variable_source]', '', ConstantHelper::getSourceVariable(), ['class' => 'variable-source', 'prompt' => 'Select', 'disabled' => $_templateDetails->is_writable == '0' ? true : false]) ?>
                                        </div>
                                        <div class="parameter-value-div">
                                            <?= $_templateDetails->input_type === 'checkbox'
                                                ? Html::checkbox('TemplateDetails[' . $key . '][parameter_value]', $_templateDetails->parameter_value == 'true', [
                                                    'value' => 'true',
                                                    'uncheck' => 'false',
                                                    'disabled' => $_templateDetails->is_writable == '0' ? true : false])
                                                : Html::textInput('TemplateDetails[' . $key . '][parameter_value]', $_templateDetails->parameter_value, ['class' => 'form-control', 'disabled' => $_templateDetails->is_writable == '0' ? true : false])
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

<script>
    $(document).ready(function () {
        $('.value-source').select2();
        $('.variable-source').select2();
        $('.multiselect').multiselect({
            sort: false,
            fireSearch: function (value) {
                return value.length > 2;
            }
        });
        $.each($('.value-source'), function (index, value) {
            var $this = $(this);
            if ($this.val() === 'Variable') {
                $this.closest('tr').find('.variable-div').removeClass('d-none');
                $this.closest('tr').find('.parameter-value-div').addClass('d-none');
            } else {
                $this.closest('tr').find('.variable-div').addClass('d-none');
                $this.closest('tr').find('.parameter-value-div').removeClass('d-none');
            }
        });
        $('.value-source').change(function () {
            var $this = $(this);
            if ($this.val() === 'Variable') {
                $this.closest('tr').find('.variable-div').removeClass('d-none');
                $this.closest('tr').find('.parameter-value-div').addClass('d-none');
            } else {
                $this.closest('tr').find('.variable-div').addClass('d-none');
                $this.closest('tr').find('.parameter-value-div').removeClass('d-none');
            }
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
    document.getElementById('check-all-header').addEventListener('change', function() {
        // Get the state of the header checkbox
        var isChecked = this.checked;

        // Get all checkboxes in the "Action" column
        var checkboxes = document.querySelectorAll('td.action-width input[type="checkbox"]');
        checkboxes.forEach(function(checkbox) {
            checkbox.checked = isChecked;
        });
    });
</script>
