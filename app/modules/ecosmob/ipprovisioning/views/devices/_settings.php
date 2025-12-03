<?php

use app\components\ConstantHelper;
use app\models\ExtensionView;
use app\modules\ecosmob\ipprovisioning\IpprovisioningModule;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use app\modules\ecosmob\ipprovisioning\models\DeviceLineParameter;

/* @var $this yii\web\View */
/* @var $model \app\modules\ecosmob\ipprovisioning\models\Devices */
/* @var $form yii\widgets\ActiveForm */
/* @var $deviceFields */
/* @var $dbLineFields */
/* @var $otherLineFields */
/* @var $lineCount */

$this->title = IpprovisioningModule::t('app', 'settings');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'devices'), 'url' => ['index'],];
$this->params['breadcrumbs'][] = IpprovisioningModule::t('app', 'settings');
$this->params['pageHead'] = $this->title;

$ext = ArrayHelper::map(ExtensionView::find()->select(['em_id', "em_extension_number"])->asArray()->all(), 'em_id', 'em_extension_number');
$sourceVariable = ConstantHelper::getSourceVariable();

?>
<div class="col-md-12 profile-contain">
    <div class="row">
        <div class="col-xl-9 col-md-7 col-xs-12">
            <div class="content">
                <div class="devise-settings">
                    <div class="row">
                        <div class="col s12">
                            <div class="card table-structure">
                                <div class="card-content">
                                    <div class="card-header d-flex align-items-center justify-content-between w-100">
                                        <div class="header-title pl-1">
                                            <?= IpprovisioningModule::t('app', 'device_configuration') ?>
                                        </div>
                                        <div class="card-header-btns">
                                            <?= Html::a(IpprovisioningModule::t('app', 'apply_configuration'),
                                                ['provisioning', 'id' => $_GET['id']],
                                                [
                                                    'id' => 'hov',
                                                    'data-pjax' => 0,
                                                    'class' => 'btn waves-effect waves-light darken-1 breadcrumbs-btn right',
                                                ]) ?>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="col s12 m6 mb-2 p-0">
                                            <table class="account-details-table">
                                                <tr>
                                                    <td><?= IpprovisioningModule::t('app', 'device_name') ?> :</td>
                                                    <td><?= $model->device_name ?></td>
                                                </tr>
                                                <tr>
                                                    <td><?= IpprovisioningModule::t('app', 'mac_address') ?> :</td>
                                                    <td><?= $model->mac_address ?></td>
                                                </tr>
                                                <tr>
                                                    <td><?= IpprovisioningModule::t('app', 'brand') ?> :</td>
                                                    <td><?= $model->brand->pv_name ?></td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col s12 m6 mb-2 p-0">
                                            <table class="account-details-table">
                                                <tr>
                                                    <td><?= IpprovisioningModule::t('app', 'template') ?> :</td>
                                                    <td><?= $model->template->template_name ?></td>
                                                </tr>
                                                <tr>
                                                    <td><?= IpprovisioningModule::t('app', 'model') ?> :</td>
                                                    <td><?= $model->phoneModel->p_model ?></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col s12">
                            <div id="input-fields" class="card card-tabs">
                                <div class="form-card-header">
                                    <?= $this->title ?>
                                </div>
                                <div class="card-content">
                                    <div class="row">
                                        <div class="col s3 sett"
                                             style="border-right: 1px solid #ddd; padding-right: 20px;">
                                            <!--<ul><a href="#" class="device-tab">Device</a></ul>
                                            <?php /*for ($k = 1; $k <= $lineCount; $k++) { */ ?>
                                            <ul><a href="#" class="line-tab-<?php /*= $k */ ?>">Line <?php /*= $k */ ?></a></ul>
                                            --><?php /*} */ ?>

                                           <!-- <ul>
                                                <a href="#" class="device-tab" data-target="device-tab"><?php /*= IpprovisioningModule::t('app', 'general_settings') */?></a></ul>
                                            <?php /*for ($k = 1; $k <= $lineCount; $k++) { */?>
                                                <ul><a href="#" class="line-tab"
                                                       data-target="line-tab-<?php /*= $k */?>"><?php /*= IpprovisioningModule::t('app', 'line') .' '. $k */?></a></ul>
                                            --><?php /*} */?>
                                            <ul>
                                                <li><a href="#" class="device-tab" data-target="device-tab"><?= IpprovisioningModule::t('app', 'general_settings') ?></a></li>
                                                <li>
                                                    <a href="#" class="line-settings-tab" data-target="line-settings-tab">
                                                        <?= IpprovisioningModule::t('app', 'line_settings') ?>
                                                        <span class="caret">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="currentColor" class="bi bi-chevron-down" viewBox="0 0 16 16">
                                                                <path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708"/>
                                                            </svg>
                                                        </span>
                                                    </a>
                                                    <ul class="submenu">
                                                        <?php for ($k = 1; $k <= $lineCount; $k++) { ?>
                                                            <li><a href="#" class="line-tab" data-target="line-tab-<?= $k ?>"><?= IpprovisioningModule::t('app', 'line') .' '. $k ?></a></li>
                                                        <?php } ?>
                                                    </ul>
                                                </li>
                                            </ul>


                                        </div>
                                        <div class="col s9">
                                            <div id="device-tab" class="tab-content">
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
                                                        <?= IpprovisioningModule::t('app', 'general_settings') ?>
                                                    </div>
                                                    <div class="card-content">
                                                <?php
                                                $i = 0;
                                                $rowOpen = false;

                                                foreach ($deviceFields as $_deviceFields):
                                                    if ($i == 0) {
                                                        if ($rowOpen) {
                                                            echo '</div>';
                                                        }
                                                        echo '<div class="row">';
                                                        $rowOpen = true;
                                                    }
                                                    ?>
                                                    <div class="col s6">
                                                        <div class="input-field">
                                                            <?= Html::label($_deviceFields->parameter_label, $_deviceFields->parameter_name) ?>
                                                            <?php
                                                            if ($_deviceFields->input_type === 'checkbox') {
                                                                $checked = $_deviceFields->parameter_value == 'true';
                                                                echo Html::checkbox("device[".$_deviceFields->id."]", $checked, [
                                                                    'value' => 'true',
                                                                    'uncheck' => 'false',
                                                                    'id' => $_deviceFields->parameter_name,
                                                                    'disabled' => $_deviceFields->is_writable == '0' ? true : false
                                                                ]);
                                                            } else {
                                                                echo Html::input('text', "device[".$_deviceFields->id."]", ($_deviceFields->variable_source == 'domain' ? $_SERVER['HTTP_HOST'] : $_deviceFields->parameter_value), [
                                                                    'id' => $_deviceFields->parameter_name,
                                                                    'class' => 'form-control',
                                                                    'disabled' => $_deviceFields->is_writable == '0' ? true : false
                                                                ]);
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>
                                                    <?php
                                                    $i++;
                                                    if ($i == 2) {
                                                        $i = 0;
                                                        echo '</div>';
                                                        $rowOpen = false;
                                                    }
                                                endforeach;

                                                if ($rowOpen) {
                                                    echo '</div>';
                                                }
                                                ?>
                                                    </div>
                                                </div>
                                                <div class="row mt-2">
                                                    <div class="col s12 pb-3 d-flex align-items-center gap-10">
                                                        <?= Html::a(IpprovisioningModule::t('app', 'cancel'),
                                                            ['index', 'page' => Yii::$app->session->get('page')],
                                                            ['class' => 'btn waves-effect waves-light bg-gray-200']) ?>
                                                        <?= Html::submitButton(IpprovisioningModule::t('app', 'save'),
                                                            [
                                                                'class' => 'btn waves-effect waves-light amber darken-4'
                                                            ]) ?>
                                                    </div>
                                                </div>
                                                <?php ActiveForm::end(); ?>
                                            </div>
                                            <?php for ($k = 1; $k <= $lineCount; $k++) { ?>
                                                <div id="line-tab-<?= $k ?>" class="tab-content" style="display:none;">
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
                                                            <?= IpprovisioningModule::t('app', 'line') .' '. $k ?>
                                                        </div>
                                                        <div class="card-content">
                                                            <?php

                                                            $lineData = DeviceLineParameter::find()->andWhere(['profile_number' => $k, 'device_id' => $_GET['id']])->andWhere(['IS', 'codec', null])->all();

                                                            $preferredOrder = [
                                                                'em_extension_number',
                                                                'em_extension_name',
                                                                'em_password',
                                                                'ecs_max_calls',
                                                                'ecs_ring_timeout',
                                                                'directoryNumber',
                                                                'sip_uri',
                                                                'domain',
                                                            ];

                                                            $orderedFields = [];
                                                            $otherFields = [];

                                                            foreach ($lineData as $_otherLineFields) {
                                                                $source = $_otherLineFields->variable_source;
                                                                if (in_array($source, $preferredOrder)) {
                                                                    if (!isset($orderedFields[$source])) {
                                                                        $orderedFields[$source] = [];
                                                                    }
                                                                    $orderedFields[$source][] = $_otherLineFields;
                                                                } else {
                                                                    $otherFields[] = $_otherLineFields;
                                                                }
                                                            }

                                                            $combinedFields = [];
                                                            foreach ($preferredOrder as $source) {
                                                                if (isset($orderedFields[$source])) {
                                                                    $combinedFields = array_merge($combinedFields, $orderedFields[$source]);
                                                                }
                                                            }

                                                            $combinedFields = array_merge($combinedFields, $otherFields);

                                                            $i = 0;
                                                            $rowOpen = false;

                                                            foreach ($combinedFields as $_otherLineFields):
                                                                if ($i == 0) {
                                                                    if ($rowOpen) {
                                                                        echo '</div>';
                                                                    }
                                                                    echo '<div class="row">';
                                                                    $rowOpen = true;
                                                                }
                                                                ?>
                                                                <div class="col s6">
                                                                    <div class="input-field">

                                                                        <?= Html::label($_otherLineFields->parameter_label, $_otherLineFields->parameter_name) ?>
                                                                        <?php
                                                                        if ($_otherLineFields->value_source == 'Variable' && $_otherLineFields->variable_source == 'em_extension_number') {
                                                                            echo Html::dropDownList("line[".$k . "][" . $_otherLineFields->parameter_name . "]", $_otherLineFields->value, $ext,
                                                                                ['class' => 'sip_authusername', 'id' => $_otherLineFields->variable_source . "_" . $k, 'data-id' => $k, 'prompt' => 'Select']);
                                                                        } else {
                                                                            if ($_otherLineFields->input_type === 'checkbox') {
                                                                                $checked = '';
                                                                                echo Html::checkbox("line[".$k . "][" . $_otherLineFields->parameter_name . "]", $_otherLineFields->value, [
                                                                                    'value' => 'true',
                                                                                    'uncheck' => 'false',
                                                                                    'id' => $_otherLineFields->variable_source . "_" . $k,
                                                                                    'readonly' => array_key_exists($_otherLineFields->variable_source, $sourceVariable)
                                                                                ]);
                                                                            } else {
                                                                                if($_otherLineFields->variable_source == 'em_password') {
                                                                                    echo Html::input( 'password', "line[" . $k . "][" . $_otherLineFields->parameter_name . "]", $_otherLineFields->value, [
                                                                                        'id' => $_otherLineFields->variable_source . "_" . $k,
                                                                                        'class' => 'form-control',
                                                                                        'readonly' => array_key_exists($_otherLineFields->variable_source, $sourceVariable)
                                                                                    ]);
                                                                                }else {
                                                                                    echo Html::input('text', "line[" . $k . "][" . $_otherLineFields->parameter_name . "]", ($_otherLineFields->variable_source == 'domain' ? $_SERVER['HTTP_HOST'] : $_otherLineFields->value), [
                                                                                        'id' => $_otherLineFields->variable_source . "_" . $k,
                                                                                        'class' => 'form-control',
                                                                                        'readonly' => array_key_exists($_otherLineFields->variable_source, $sourceVariable)
                                                                                    ]);
                                                                                }
                                                                            }
                                                                        }
                                                                        ?>
                                                                    </div>
                                                                </div>
                                                                <?php
                                                                $i++;
                                                                if ($i == 2) {
                                                                    $i = 0;
                                                                    echo '</div>';
                                                                    $rowOpen = false;
                                                                }
                                                            endforeach;

                                                            if ($rowOpen) {
                                                                echo '</div>';
                                                            }

                                                            $codecs = ArrayHelper::map(DeviceLineParameter::find()->andWhere(['profile_number' => $k, 'device_id' => $_GET['id']])->andWhere(['IS NOT', 'codec', null])->asArray()->all(), 'parameter_key', 'codec');
                                                            ksort($codecs);
                                                            $acodec = ArrayHelper::map(DeviceLineParameter::find()->andWhere(['profile_number' => $k, 'device_id' => $_GET['id']])->andWhere(['IS NOT', 'codec', null])->andWhere(['IS NOT', 'value', null])->orderBy('value')->asArray()->all(), 'parameter_key', 'codec');
                                                            ?>

                                                            <div class="section"></div>
                                                            <div class="row mt-3">
                                                                <div class="form-group col s12 p-0">

                                                                    <div class="form-group field-groups-group_status selection-codes">
                                                                        <div class=" col s4">
                                                                            <div class="col no-padding">
                                                                                <label class="control-label"
                                                                                       for="groups-group_status"><b><?= IpprovisioningModule::t('app', 'codecs') ?></b></label>

                                                                            </div>
                                                                            <select name="line[<?= $k ?>][all_codec][]" id="multiselect_to_0_<?= $k ?>"
                                                                                    class="multiselect form-control"
                                                                                    size="<?= count($codecs) ?>"
                                                                                    multiple="multiple"
                                                                                    data-right="#multiselect_to_<?= $k ?>"
                                                                                    data-sort="false"
                                                                                    data-right-all="#right_All_<?= $k ?>"
                                                                                    data-right-selected="#right_Selected_<?= $k ?>"
                                                                                    data-left-all="#left_All_<?= $k ?>"
                                                                                    data-left-selected="#left_Selected_<?= $k ?>">
                                                                                <?php foreach ($codecs as $key => $val) {
                                                                                    echo "<option value='" . $key . "'>" . $val . "</option>";
                                                                                } ?>
                                                                            </select>
                                                                        </div>

                                                                        <div class="multiselect-btn-pad with-btn col s2 mt-3">
                                                                            <button type="button" id="right_All_<?= $k ?>"
                                                                                    class="btn-margin-bottom btn btn-block multiselect_btn">
                                                                                <i class="material-icons">fast_forward</i>
                                                                            </button>
                                                                            <button type="button" id="right_Selected_<?= $k ?>"
                                                                                    class="btn-margin-bottom btn btn-block multiselect_btn">
                                                                                <i class="material-icons">keyboard_arrow_right</i>
                                                                            </button>
                                                                            <button type="button" id="left_Selected_<?= $k ?>"
                                                                                    class="btn-margin-bottom btn btn-block multiselect_btn">
                                                                                <i class="material-icons">keyboard_arrow_left</i>
                                                                            </button>
                                                                            <button type="button" id="left_All_<?= $k ?>"
                                                                                    class="btn btn-block multiselect_btn">
                                                                                <i class="material-icons">fast_rewind</i>
                                                                            </button>
                                                                        </div>

                                                                        <div class=" col s4">
                                                                            <div class="col no-padding">
                                                                                <label class="control-label"
                                                                                       for="groups-group_status"><b><?= IpprovisioningModule::t('app', 'assigned_codec') ?></b></label>
                                                                            </div>
                                                                            <select name="line[<?= $k ?>][assign_codec][]" id="multiselect_to_<?= $k ?>"
                                                                                    class="multiselect form-control"
                                                                                    size="<?= count($codecs) ?>" multiple="multiple">
                                                                                <?php foreach ($acodec as $akey => $aval) {
                                                                                    echo "<option value='" . $akey . "'>" . $aval . "</option>";
                                                                                } ?>
                                                                            </select>
                                                                        </div>
                                                                        <br>
                                                                        <div class="with-btn col s2 setM-pad">
                                                                            <button type="button" value="Up"
                                                                                    class="btn-margin-bottom btn btn-block line_priority_btns" data-id="<?= $k ?>">
                                                                                <i class="material-icons">arrow_upward</i>
                                                                            </button>

                                                                            <button type="button" value="Down"
                                                                                    class="btn btn-block line_priority_btns" data-id="<?= $k ?>">
                                                                                <i class="material-icons">arrow_downward</i>
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>

                                                    <div class="row mt-2">
                                                        <div class="col s12 pb-3 d-flex align-items-center gap-10">
                                                            <?= Html::a(IpprovisioningModule::t('app', 'cancel'),
                                                                ['index', 'page' => Yii::$app->session->get('page')],
                                                                ['class' => 'btn waves-effect waves-light bg-gray-200']) ?>
                                                            <?= Html::submitButton(IpprovisioningModule::t('app', 'save'),
                                                                [
                                                                    'class' => 'btn waves-effect waves-light amber darken-4'
                                                                ]) ?>
                                                        </div>
                                                    </div>
                                                    <?php ActiveForm::end(); ?>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
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

        document.querySelectorAll('.submenu').forEach(function (submenu) {
            submenu.style.display = 'none';
        });

        // Handle clicks on main tabs and line tabs
        document.querySelectorAll('.device-tab, .line-settings-tab, .line-tab').forEach(function (tab) {
            tab.addEventListener('click', function (e) {
                e.preventDefault(); // Prevent default link behavior

                if (this.classList.contains('line-settings-tab')) {
                    // Toggle the visibility of the submenu
                    var submenu = this.nextElementSibling; // Assuming the submenu is the next sibling
                    if (submenu.style.display === 'block') {
                        submenu.style.display = 'none';
                        this.classList.remove('active');
                    } else {
                        submenu.style.display = 'block';
                        this.classList.add('active');
                    }

                    // Hide all other submenus
                    document.querySelectorAll('.submenu').forEach(function (sub) {
                        if (sub !== submenu) {
                            sub.style.display = 'none';
                            sub.previousElementSibling.classList.remove('active');
                        }
                    });
                } else if (this.classList.contains('line-tab')) {
                    // Handle clicks on line tabs
                    var target = this.getAttribute('data-target');

                    // Hide all tab contents
                    document.querySelectorAll('.tab-content').forEach(function (content) {
                        content.style.display = 'none';
                    });

                    // Show the selected tab content
                    var contentToShow = document.getElementById(target);
                    if (contentToShow) {
                        contentToShow.style.display = 'block';
                    }

                    // Remove 'active' class from all line tabs and general settings
                    document.querySelectorAll('.line-tab').forEach(function (lineTab) {
                        lineTab.classList.remove('active');
                    });
                    document.querySelectorAll('.device-tab').forEach(function (deviceTab) {
                        deviceTab.classList.remove('active');
                    });

                    // Add 'active' class to the clicked line tab
                    this.classList.add('active');
                } else {
                    // Handle clicks on other main tabs (e.g., General Settings)
                    var target = this.getAttribute('data-target');

                    // Hide all tab contents
                    document.querySelectorAll('.tab-content').forEach(function (content) {
                        content.style.display = 'none';
                    });

                    // Show the selected tab content
                    var contentToShow = document.getElementById(target);
                    if (contentToShow) {
                        contentToShow.style.display = 'block';
                    }

                    // Remove 'active' class from all tabs and add it to the clicked tab
                    document.querySelectorAll('.device-tab, .line-settings-tab').forEach(function (tab) {
                        tab.classList.remove('active');
                    });
                    this.classList.add('active');

                    // Hide the 'Line Settings' submenu if clicking on any other main tab
                    document.querySelectorAll('.submenu').forEach(function (submenu) {
                        submenu.style.display = 'none';
                        submenu.previousElementSibling.classList.remove('active');
                    });

                    // Remove active class from all line tabs
                    document.querySelectorAll('.line-tab').forEach(function (lineTab) {
                        lineTab.classList.remove('active');
                    });
                }
            });
        });

        // Optionally, show the first tab content by default
        document.querySelector('.device-tab').click();


        $('.sip_authusername').change(function(){
            var dataId = $(this).attr('data-id');
            if($(this).val()){
                $.ajax({
                    url: "<?= Url::to(['get-ext-data']) ?>",
                    data: {id: $(this).val()},
                    type: "POST",
                    success: function (data) {
                        if(data) {
                            var parsed_array = JSON.parse(data);
                            $("#em_extension_name_" + dataId).val(parsed_array.em_extension_name);
                            $("#em_password_" + dataId).val(parsed_array.em_password);
                            $("#ecs_max_calls_" + dataId).val(parsed_array.ecs_max_calls);
                            $("#ecs_ring_timeout_" + dataId).val(parsed_array.ecs_ring_timeout);
                            $("#directoryNumber_" + dataId).val(parsed_array.em_extension_number);
                            $("#sip_uri_" + dataId).val(parsed_array.sip_uri);
                        }
                    }
                });
            }
        });

        $('.multiselect').multiselect({
            sort: false,
            fireSearch: function (value) {
                return value.length > 2;
            }
        });

        $(document).on('click', '.line_priority_btns', function () {
            var id = $(this).attr('data-id');
            var op = $('#multiselect_to_'+id+' option:selected');

            if (op.length) {
                ($(this).val() == 'Up') ?
                    op.first().prev().before(op) :
                    op.last().next().after(op);
            }
        });

    });
</script>
