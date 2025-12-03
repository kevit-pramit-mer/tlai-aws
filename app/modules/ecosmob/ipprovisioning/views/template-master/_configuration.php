<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\ecosmob\ipprovisioning\IpprovisioningModule;
use app\modules\ecosmob\ipprovisioning\models\TemplateDetails;
use app\modules\ecosmob\ipprovisioning\models\TemplateMaster;
use app\components\ConstantHelper;

/* @var $this yii\web\View */
/* @var $model TemplateMaster */
/* @var $form yii\widgets\ActiveForm */
/* @var $templateDetails TemplateDetails */

$this->title = IpprovisioningModule::t('app', 'template_configuration');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'template'), 'url' => ['index'],];
$this->params['breadcrumbs'][] = IpprovisioningModule::t('app', 'template_configuration');
$this->params['pageHead'] = $this->title;

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
                        <div class="col s12">
                            <div class="card table-structure">
                                <div class="card-content">
                                    <div class="card-header d-flex align-items-center justify-content-between w-100">
                                        <div class="header-title pl-1">
                                            <?= IpprovisioningModule::t('app', 'template_configuration') ?>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="col s6 mb-2 p-0">
                                            <table class="account-details-table">
                                                <tr>
                                                    <td><?= $model->getAttributeLabel('template_name') ?> :</td>
                                                    <td><?= $model->template_name ?></td>
                                                </tr>
                                                <tr>
                                                    <td><?= $model->getAttributeLabel('device_template_id') ?> :</td>
                                                    <td><?= $model->deviceTemplate->template_name ?></td>
                                                </tr>
                                                <tr>
                                                    <td><?= $model->getAttributeLabel('supported_models_id') ?> :</td>
                                                    <td><?= $model->phoneModels ?></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col s12">
                            <?php $form = ActiveForm::begin([
                                'class' => 'row',
                                'fieldConfig' => [
                                    'options' => [
                                        'class' => 'input-field',
                                    ],
                                ],
                            ]); ?>
                            <div id="input-fields" class="card card-tabs">
                                <div class="card-content">
                                    <div class="card-header d-flex align-items-center justify-content-between w-100">
                                        <div class="header-title pl-1">
                                            <?= IpprovisioningModule::t('app', 'template_parameters') ?>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="col s12 mb-2 template-config">
                                            <table>
                                                <thead>
                                                <tr>
                                                    <th class="action-width">Action</th>
                                                    <th class="parameter-width">Parameter</th>
                                                    <th class="source-width">Source</th>
                                                    <th class="value-width">Default Value</th>
                                                    <th class="line-width">Line Configuration</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php foreach ($templateDetails as $_templateDetails): ?>
                                                    <tr>
                                                        <td class="action-width"><?= Html::checkbox($_templateDetails->id.'[is_checked]', $_templateDetails->is_checked == 1, ['value' => 1, 'uncheck' => 0]) ?></td>
                                                        <td class="parameter-width"><?= Html::encode($_templateDetails->parameter_name) ?></td>
                                                        <td class="source-width">
                                                            <?= Html::dropDownList($_templateDetails->id .'[value_source]', $_templateDetails->value_source, ['Device Specific' => 'Device Specific', 'Global' => 'Global', 'Variable' => 'Variable'], ['class' => 'value-source', 'data-id' => $_templateDetails->id, 'prompt' => 'Select', 'disabled' => $_templateDetails->is_writable == '0' ? true : false]) ?>
                                                        </td>
                                                        <td class="value-width">
                                                            <div class="variable-div-<?= $_templateDetails->id ?> d-none">
                                                                <?= Html::dropDownList($_templateDetails->id .'[variable_source]', $_templateDetails->variable_source, ConstantHelper::getSourceVariable(), ['prompt' => 'Select', 'disabled' => $_templateDetails->is_writable == '0' ? true : false]) ?>
                                                            </div>
                                                            <div class="parameter-value-div-<?= $_templateDetails->id ?>">
                                                            <?= $_templateDetails->input_type === 'checkbox'
                                                                ? Html::checkbox($_templateDetails->id .'[parameter_value]', $_templateDetails->parameter_value == 'true', [
                                                                    'value' => 'true',
                                                                    'uncheck' => 'false',
                                                                    'disabled' => $_templateDetails->is_writable == '0' ? true : false])
                                                                : Html::textInput($_templateDetails->id .'[parameter_value]', $_templateDetails->parameter_value, ['class' => 'form-control', 'disabled' => $_templateDetails->is_writable == '0' ? true : false])
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
</div>

<script>
    $(document).ready(function(){
        $.each($('.value-source'), function (index, value) {
            var id = $(this).attr('data-id');
            if($(this).val() == 'Variable'){
                $('.variable-div-'+id).removeClass('d-none');
                $('.parameter-value-div-'+id).addClass('d-none');
            }else{
                $('.variable-div-'+id).addClass('d-none');
                $('.parameter-value-div-'+id).removeClass('d-none');
            }
    });
        $('.value-source').change(function(){
            var id = $(this).attr('data-id');
            if($(this).val() == 'Variable'){
                $('.variable-div-'+id).removeClass('d-none');
                $('.parameter-value-div-'+id).addClass('d-none');
            }else{
                $('.variable-div-'+id).addClass('d-none');
                $('.parameter-value-div-'+id).removeClass('d-none');
            }
        });
    });
</script>