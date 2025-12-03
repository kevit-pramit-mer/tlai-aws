<?php

use app\modules\ecosmob\audiomanagement\models\AudioManagement;
use app\modules\ecosmob\parkinglot\models\ParkingLot;
use app\modules\ecosmob\parkinglot\ParkingLotModule;
use app\modules\ecosmob\services\models\Services;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model ParkingLot */
/* @var $form yii\widgets\ActiveForm */
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
                <div class="parking-lot-form" id="parking-lot-form">
                    <div class="row">
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($model, 'name', ['inputOptions' => ['class' => 'form-control' ],
                                ])->textInput(['maxlength' => TRUE,'placeholder' => ($model->getAttributeLabel('name'))]); ?>
                            </div>
                        </div>
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?php echo $form->field($model, 'status', ['options' => ['class' => '']])
                                    ->dropDownList([1 => Yii::t('app', 'active'), 0 => Yii::t('app', 'inactive')])->label
                                    (ParkingLotModule::t('parkinglot', 'status')); ?>
                            </div>
                        </div>
                        <div class="col s12 m6 clear-both">
                            <div class="input-field">
                                <?= $form->field($model, 'park_ext', ['inputOptions' => ['class' => 'form-control'],
                                ])->textInput(['maxlength' => TRUE, 'onkeypress' => "return isNumberKey(event)", 'onpaste' => "return paste(this)", 'placeholder' => ($model->getAttributeLabel('park_ext'))]); ?>
                            </div>
                        </div>
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($model, 'slot_qty', ['inputOptions' => ['class' => 'form-control'],
                                ])->textInput(['maxlength' => TRUE, 'onkeypress' => "return isNumberKey(event)", 'placeholder' => ($model->getAttributeLabel('slot_qty'))]); ?>
                            </div>
                        </div>
                        <div class="col s12 m6 clear-both">
                            <div class="input-field">
                                <?= $form->field($model, 'grp_id', ['options' => ['class' => '']])->dropDownList(
                                    $model->groupList,
                                    ['prompt' => ParkingLotModule::t('parkinglot', 'select')])
                                    ->label(ParkingLotModule::t('parkinglot', 'grp_id')); ?>
                            </div>
                        </div>
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($model, 'parking_time', ['inputOptions' => ['class' => 'form-control'],
                                ])->textInput(['maxlength' => TRUE, 'onkeypress' => "return isNumberKey(event)", 'value' => ($model->isNewRecord ? 45 : $model->parking_time), 'placeholder' => ($model->getAttributeLabel('parking_time'))]); ?>

                            </div>
                        </div>
                        <div class="col s12 m6 clear-both">
                            <div class="input-field">
                                <?= $form->field($model,
                                    'park_moh', ['options' => ['class' => '']])
                                    ->dropDownList(AudioManagement::getMohFiles(), ['prompt' => ParkingLotModule::t('parkinglot', 'select')])
                                    ->label(ParkingLotModule::t('parkinglot', 'park_moh')); ?>
                            </div>
                        </div>
                        <div class="row m-0">
                            <div class="col s12">
                                <label class="control-label"> <?= $model->getAttributeLabel('return_to_origin') ?>: </label>
                            </div>    
                            <div class="col s12 m6 d-flex align-items-center gap-2 switch-input">
                                <div class="switch">
                                    <label>
                                        Disabled
                                        <?= Html::checkbox('return_to_origin', $model->return_to_origin, ['uncheck' => 0, 'id' => 'return-to-origin']) ?>
                                        <span class="lever"></span>
                                        Enabled
                                    </label>
                                </div>
                            </div>
                            <div class="col s12 m6 clear-both">
                                    <?= $form->field($model, 'call_back_ring_time', ['inputOptions' => ['class' => 'form-control input-field'], 'options' => ['class' => '']
                                    ])->textInput(['maxlength' => TRUE, 'onkeypress' => "return isNumberKey(event)"]); ?>
                            </div>
                        </div>    
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($model, 'destination_type', ['options' => ['class' => 'mb-2']])
                                    ->dropDownList(ParkingLot::getServices(), ['prompt' => ParkingLotModule::t('parkinglot', 'select')])
                                    ->label($model->getAttributeLabel('destination_type')); ?>
                            </div>
                        </div>
                        <div class="col s12 m6 ml-0">
                            <div class="input-field">
                                <?= $form->field($model, 'des_id_select', ['options' => ['class' => 'mb-2']])
                                    ->dropDownList([], ['prompt' => ParkingLotModule::t('parkinglot', 'select')])
                                    ->label($model->getAttributeLabel('destination_id').' <span style="color: red;">*</span>'); ?>
                                <?= $form->field($model, 'des_id_input', ['options' => ['class' => 'mb-2 mt--7']])
                                    ->textInput(['maxlength' => TRUE, 'value' => $model->destination_id])
                                    ->label($model->getAttributeLabel('destination_id').' <span style="color: red;">*</span>'); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col s12 pb-3 d-flex align-items-center gap-10">
            <?= Html::a(ParkingLotModule::t('parkinglot', 'cancel'),
                        ['index', 'page' => Yii::$app->session->get('page')],
                        ['class' => 'btn waves-effect waves-light bg-gray-200']) ?>                        
                    <?= Html::submitButton($model->isNewRecord ? ParkingLotModule::t('parkinglot', 'create') : ParkingLotModule::t('parkinglot', 'update'),
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
    var des_id = '<?= $model->destination_id ?>';

    $(document).ready(function () {
        $('.field-parkinglot-des_id_select').hide();
        $('.field-parkinglot-des_id_input').hide();

        if ($('#parkinglot-destination_type').val() != '') {
            if ($('#parkinglot-destination_type').val() == '6') {
                $('.field-parkinglot-des_id_select').hide();
                $('.field-parkinglot-des_id_input').show();
            } else {
                $('.field-parkinglot-des_id_select').show();
                $('.field-parkinglot-des_id_input').hide();
                changeDes(des_id);
            }
        } else {
            $('.field-parkinglot-des_id_select').hide();
            $('.field-parkinglot-des_id_input').hide();
        }

        if ($('input[name="return_to_origin"]').is(':checked')) {
            $('.field-parkinglot-call_back_ring_time').show();
        } else {
            $('.field-parkinglot-call_back_ring_time').hide();
        }
    });

    $(document).on('change', '#parkinglot-destination_type', function () {
        if ($(this).val() != '') {
            if ($(this).val() == '6') {
                $('#parkinglot-des_id_input').val('');
                $('.field-parkinglot-des_id_select').hide();
                $('.field-parkinglot-des_id_input').show();
            } else {
                $('.field-parkinglot-des_id_select').show();
                $('.field-parkinglot-des_id_input').hide();
                changeDes('');
            }
        } else {
            $('.field-parkinglot-des_id_select').hide();
            $('.field-parkinglot-des_id_input').hide();
        }
    });

    function changeDes(des_id) {
        var action_id = $('#parkinglot-destination_type').val();

        if (action_id != '') {
            var csrfToken = $('meta[name="csrf-token"]').attr("content");
            $.ajax({
                type: "POST",
                url: '<?= Url::to(['change-action']); ?>',
                data: {_csrf: csrfToken, 'action_id': action_id, 'action_value': des_id},
                success: function (data) {
                    $('#parkinglot-des_id_select').html(data);
                }
            });
        }
    }

    $('input[name="return_to_origin"]').on('change', function () {
        if ($(this).is(':checked')) {
            $('#parkinglot-call_back_ring_time').val(20);
            $('.field-parkinglot-call_back_ring_time').show();
        } else {
            $('.field-parkinglot-call_back_ring_time').hide();
        }
    });

</script>