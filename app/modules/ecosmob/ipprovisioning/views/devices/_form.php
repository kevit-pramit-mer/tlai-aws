<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use app\models\PhoneVendor;
use app\modules\ecosmob\ipprovisioning\IpprovisioningModule;

/* @var $this yii\web\View */
/* @var $model \app\modules\ecosmob\ipprovisioning\models\Devices */
/* @var $form yii\widgets\ActiveForm */

$brand = ArrayHelper::map(PhoneVendor::find()->all(), 'pv_id', 'pv_name');
?>

<div class="row">
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
            <div class="form-card-header">
                <?= $this->title ?>
            </div>
            <div class="card-content">
                <div class="group-form" id="group-form">
                    <div class="row">
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($model, 'device_name', [
                                    'inputOptions' => [
                                        'class' => 'form-control',
                                    ],
                                ])->textInput(['maxlength' => TRUE, 'placeholder' => ($model->getAttributeLabel('device_name'))]); ?>

                            </div>
                        </div>
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($model, 'mac_address', [
                                    'inputOptions' => [
                                        'class' => 'form-control',
                                    ],
                                ])->textInput(['maxlength' => TRUE, 'placeholder' => ($model->getAttributeLabel('mac_address'))]); ?>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($model, 'brand_id', ['options' => ['class' => '']])
                                    ->dropDownList($brand,
                                        ['prompt' => IpprovisioningModule::t('app', 'select')])
                                    ->label($model->getAttributeLabel('brand_id')); ?>
                            </div>
                        </div>
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($model, 'template_master_id', ['options' => ['class' => '']])
                                    ->dropDownList([],
                                        ['prompt' => IpprovisioningModule::t('app', 'select')])
                                    ->label($model->getAttributeLabel('template_master_id')); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($model, 'model_id', ['options' => ['class' => '']])
                                    ->dropDownList([],
                                        ['prompt' => IpprovisioningModule::t('app', 'select')])
                                    ->label($model->getAttributeLabel('model_id')); ?>
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
                <?= Html::submitButton($model->isNewRecord ? IpprovisioningModule::t('app', 'create') : IpprovisioningModule::t('app', 'update'),
                    [
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
    var tempateId = '<?= $model->template_master_id ?>';
    var modelId = '<?= $model->model_id ?>';
    $(document).ready(function(){
        if(tempateId) {
            $('#devices-brand_id').change();
        }
    });
    $(document).on('change', '#devices-brand_id', function(){
        var csrfToken = $('meta[name="csrf-token"]').attr("content");
        $.ajax({
            type: "POST",
            url: '<?= Url::to(['get-data']); ?>',
            data: {_csrf : csrfToken, 'brand_id': $(this).val()},
            success: function (data) {
                if(data){
                    var result = JSON.parse(data);
                    $('#devices-template_master_id').html(result.templateOption);
                    if(tempateId){
                        $('#devices-template_master_id').val(tempateId).trigger('change');
                    }
                    $('#devices-model_id').html(result.modelOption);
                }
            }
        });
    });

    $(document).on('change', '#devices-template_master_id', function(){
        var csrfToken = $('meta[name="csrf-token"]').attr("content");
        $.ajax({
            type: "POST",
            url: '<?= Url::to(['get-data']); ?>',
            data: {_csrf : csrfToken, 'brand_id': $('#devices-brand_id').val(), 'template_id': $(this).val()},
            success: function (data) {
                if(data){
                    var result = JSON.parse(data);
                    $('#devices-model_id').html(result.modelOption);
                    if(modelId){
                        $('#devices-model_id').val(modelId).trigger('change');
                    }
                }
            }
        });
    })
</script>
