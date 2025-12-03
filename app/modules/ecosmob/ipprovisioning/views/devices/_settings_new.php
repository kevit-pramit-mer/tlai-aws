<?php

use app\models\ExtensionView;
use app\modules\ecosmob\ipprovisioning\IpprovisioningModule;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\components\ConstantHelper;

/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $deviceFields */
/* @var $dbLineFields */
/* @var $otherLineFields */
/* @var $lineCount */

$this->title = IpprovisioningModule::t('app', 'settings');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'devices'), 'url' => ['index'],];
$this->params['breadcrumbs'][] = IpprovisioningModule::t('app', 'settings');
$this->params['pageHead'] = $this->title;

$ext = ArrayHelper::map(ExtensionView::find()->select(['em_id', "CONCAT(em_extension_name, ' - ', em_extension_number) as em_extension_name"])->asArray()->all(), 'em_id', 'em_extension_name');
$sourceVariable = ConstantHelper::getSourceVariable();
?>
<style>
    input[type=text]:not(.browser-default):disabled {
        background: rgb(236 238 241);
    }
</style>
<div class="col-md-12 profile-contain">
    <div class="row">
        <div class="col-xl-9 col-md-7 col-xs-12">
            <div class="content">
                <div class="devise-settings">
                    <div class="row">
                        <?php $form = ActiveForm::begin([
                            'class' => 'row',
                            'fieldConfig' => [
                                'options' => [
                                    'class' => 'input-field',
                                ],
                            ],
                        ]); ?>
                        <div class="col s12">
                            <div class="card table-structure">
                                <div class="card-content">
                                    <div class="card-header d-flex align-items-center justify-content-between w-100">
                                        <div class="header-title pl-1">
                                            <?= IpprovisioningModule::t('app', 'device_configuration') ?>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="col s4 mb-2 p-0">
                                            <table class="account-details-table">
                                                <tr>
                                                    <td><?= IpprovisioningModule::t('app', 'mac_address') ?> :</td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td><?= IpprovisioningModule::t('app', 'manufacture') ?> :</td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td><?= IpprovisioningModule::t('app', 'product_class') ?> :</td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td><?= IpprovisioningModule::t('app', 'select_line') ?> :</td>
                                                    <td> <?= Html::dropDownList('line_no', [1], $arr = array_combine(range(1, $lineCount), range(1, $lineCount)),
                                                        ['id' => 'line-select', 'class' => 'line_no_arr', 'multiple' => true]); ?></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col s12">
                            <div class="card table-structure">
                                <div class="card-content">
                                    <div class="card-header d-flex align-items-center justify-content-between w-100">
                                        <div class="header-title pl-1">
                                            <?= IpprovisioningModule::t('app', 'line_settings') ?>
                                        </div>
                                    </div>
                                    <div class="card-body line-settings-card">
                                    <?php for ($k = 1; $k <= $lineCount; $k++) { ?>
                                        <div id="line-tab-<?= $k ?>" class="accordion-item">
                                            <ul class="collapsible collapsible-accordion"
                                                data-collapsible="accordion">
                                                <li>
                                                    <div class="collapsible-header">Line <?= $k ?></div>
                                                    <div class="collapsible-body">
                                                        <div class="form-collapse-body">
                                                            <?php
                                                            $i = 0;
                                                            $rowOpen = false;

                                                            foreach ($otherLineFields as $_otherLineFields):
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
                                                                            echo Html::dropDownList($k . "[" . $_otherLineFields->parameter_name . "]", $_otherLineFields->parameter_value, $ext,
                                                                                ['class' => 'sip_authusername', 'id' => $_otherLineFields->parameter_name . "_" . $k,]);
                                                                        } else {
                                                                            if ($_otherLineFields->input_type === 'checkbox') {
                                                                                $checked = '';
                                                                                echo Html::checkbox($k . "[" . $_otherLineFields->parameter_name . "]", $checked, [
                                                                                    'value' => 'true',
                                                                                    'uncheck' => 'false',
                                                                                    'id' => $_otherLineFields->parameter_name . "_" . $k,
                                                                                    'disabled' => array_key_exists($_otherLineFields->variable_source, $sourceVariable)
                                                                                ]);
                                                                            } else {
                                                                                if($_otherLineFields->variable_source == 'em_password') {
                                                                                    echo Html::input('password', $k . "[" . $_otherLineFields->parameter_name . "]", '', [
                                                                                        'id' => $_otherLineFields->parameter_name . "_" . $k,
                                                                                        'class' => 'form-control',
                                                                                        'disabled' => array_key_exists($_otherLineFields->variable_source, $sourceVariable)
                                                                                    ]);
                                                                                }else{
                                                                                    echo Html::input('text', $k . "[" . $_otherLineFields->parameter_name . "]", '', [
                                                                                        'id' => $_otherLineFields->parameter_name . "_" . $k,
                                                                                        'class' => 'form-control',
                                                                                        'disabled' => array_key_exists($_otherLineFields->variable_source, $sourceVariable)
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
                                                            ?>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    <?php } ?>
                                </div>
                                </div>
                            </div>
                        </div>

                        <!--<div class="col s12">
                            <div id="input-fields" class="card card-tabs">
                                <div class="form-card-header">
                                    <?php /*= $this->title */?>
                                </div>
                                <div class="card-content">
                                    <?php /*for ($k = 1; $k <= $lineCount; $k++) { */?>
                                        <div id="line-tab-<?php /*= $k */?>" class="accordion-item">
                                            <ul class="collapsible collapsible-accordion" data-collapsible="accordion">
                                                <li>
                                                    <div class="collapsible-header">Line <?php /*= $k */?></div>
                                                    <div class="collapsible-body">
                                                        <div class="form-collapse-body">
                                                            <?php
/*                                                            $i = 0;
                                                            $rowOpen = false;

                                                            foreach ($otherLineFields as $_otherLineFields):
                                                                if ($i == 0) {
                                                                    if ($rowOpen) {
                                                                        echo '</div>';
                                                                    }
                                                                    echo '<div class="row">';
                                                                    $rowOpen = true;
                                                                }
                                                                */?>
                                                                <div class="col s6">
                                                                    <div class="input-field">
                                                                        <?php /*= Html::label($_otherLineFields->parameter_label, $_otherLineFields->parameter_name) */?>
                                                                        <?php
/*                                                                        if ($_otherLineFields->value_source == 'Variable' && $_otherLineFields->variable_source == 'em_extension_number') {
                                                                            echo Html::dropDownList($k . "[" . $_otherLineFields->parameter_name . "]", $_otherLineFields->parameter_value, $ext,
                                                                                ['class' => 'sip_authusername', 'id' => $_otherLineFields->variable_source . "_" . $k,]);
                                                                        } else {
                                                                            if ($_otherLineFields->input_type === 'checkbox') {
                                                                                $checked = '';
                                                                                echo Html::checkbox($k . "[" . $_otherLineFields->parameter_name . "]", $checked, [
                                                                                    'value' => 'true',
                                                                                    'uncheck' => 'false',
                                                                                    'id' => $_otherLineFields->variable_source . "_" . $k,
                                                                                    'disabled' => array_key_exists($_otherLineFields->variable_source, $sourceVariable)
                                                                                ]);
                                                                            } else {
                                                                                if($_otherLineFields->variable_source == 'em_password') {
                                                                                    echo Html::input('password', $k . "[" . $_otherLineFields->parameter_name . "]", '', [
                                                                                        'id' => $_otherLineFields->variable_source . "_" . $k,
                                                                                        'class' => 'form-control',
                                                                                        'disabled' => array_key_exists($_otherLineFields->variable_source, $sourceVariable)
                                                                                    ]);
                                                                                }else{
                                                                                    echo Html::input('text', $k . "[" . $_otherLineFields->parameter_name . "]", '', [
                                                                                        'id' => $_otherLineFields->variable_source . "_" . $k,
                                                                                        'class' => 'form-control',
                                                                                        'disabled' => array_key_exists($_otherLineFields->variable_source, $sourceVariable)
                                                                                    ]);
                                                                                }
                                                                            }
                                                                        }
                                                                        */?>
                                                                    </div>
                                                                </div>
                                                                <?php
/*                                                                $i++;
                                                                if ($i == 2) {
                                                                    $i = 0;
                                                                    echo '</div>';
                                                                    $rowOpen = false;
                                                                }
                                                            endforeach;

                                                            if ($rowOpen) {
                                                                echo '</div>';
                                                            }
                                                            */?>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    <?php /*} */?>
                                </div>
                            </div>
                        </div>-->

                        <div class="col s12">
                            <div class="card table-structure">
                                <div class="card-content">
                                    <div class="card-header d-flex align-items-center justify-content-between w-100">
                                        <div class="header-title pl-1">
                                            <?= IpprovisioningModule::t('app', 'device_settings') ?>
                                        </div>
                                    </div>
                                    <div class="card-body device-settings-card">

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
                                                        echo Html::checkbox($_deviceFields->id, $checked, [
                                                            'value' => 'true',
                                                            'uncheck' => 'false',
                                                            'id' => $_deviceFields->parameter_name,
                                                            'disabled' => $_deviceFields->is_writable == '0' ? true : false
                                                        ]);
                                                    } else {
                                                        echo Html::input('text', $_deviceFields->id, $_deviceFields->parameter_value, [
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
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col s12 pb-3 d-flex align-items-center gap-10">
                                <?= Html::a(IpprovisioningModule::t('app', 'cancel'),
                                    ['index', 'page' => Yii::$app->session->get('page')],
                                    ['class' => 'btn waves-effect waves-light bg-gray-200']) ?>
                                <?= Html::submitButton(IpprovisioningModule::t('app', 'submit'),
                                    [
                                        'class' => 'btn waves-effect waves-light amber darken-4'
                                    ]) ?>
                            </div>
                        </div>
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {

            const lineSelect = document.getElementById('line-select');
            const accordionItems = document.querySelectorAll('.accordion-item');

            // Initialize Materialize Collapsible (if using Materialize)
            M.Collapsible.init(document.querySelectorAll('.collapsible'), {});

            function updateAccordionVisibility() {
                // Get selected values from the dropdown
                const selectedValues = Array.from(lineSelect.options)
                    .filter(option => option.selected)
                    .map(option => option.value);

                // Show or hide accordion items based on selected values
                accordionItems.forEach(item => {
                    const itemId = item.id.replace('line-tab-', '');
                    const collapsible = M.Collapsible.getInstance(item.querySelector('.collapsible'));

                    if (selectedValues.includes(itemId)) {
                        item.style.display = 'block'; // Show the item
                        /*if (collapsible) {
                            collapsible.open(); // Ensure the accordion is open
                        }*/
                    } else {
                        item.style.display = 'none'; // Hide the item
                        if (collapsible) {
                            collapsible.close(); // Ensure the accordion is closed
                        }
                    }
                });
            }

            // Initialize visibility based on the default selection
            updateAccordionVisibility();

        // Update visibility on dropdown change
        //lineSelect.addEventListener('change', updateAccordionVisibility);
        $('#line-select').change(function(){
            updateAccordionVisibility();
        })
    });
</script>

