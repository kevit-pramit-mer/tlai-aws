<?php

use app\modules\ecosmob\extension\extensionModule;
use app\modules\ecosmob\timezone\models\Timezone;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\admin\models\AdminMaster */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="row">
    <div class="col s12">
        <?php $form = ActiveForm::begin(
            [
                'id' => 'admin-update-form',
                'class' => 'row',
                'method' => 'post',
                'action' => Yii::$app->urlManager->createUrl(
                    '/extension/extension/update-extension'
                ),
                'fieldConfig' => [
                    'options' => [
                        'class' => 'input-field',
                    ],
                ],
            ]
        ); ?>
        <div id="input-fields" class="card card-tabs">
            <div class="form-card-header">
                <?= $this->title ?>
            </div>
            <div class="card-content">
                <div class="admin-update-profile-form" id="admin-update-profile-form">
                    <div class="row">
                        <div class="col s12 m6">
                            <div class="input-field ">
                                <?= $form->field($model, 'em_email')->textInput([
                                    'maxlength' => TRUE,
                                    'autofocus' => TRUE,
                                    'disabled' => TRUE
                                ])->label(extensionModule::t('app', 'email')); ?>
                            </div>
                        </div>
                        <!--<div class="col s6">
                            <div class="input-field col s12">
                                <? /*= $form->field($model, 'em_password')->textInput([
                                    'maxlength' => TRUE,
                                ])->label(extensionModule::t('app', 'sip_password')); */ ?>
                            </div>
                        </div>-->
                        <div class="col s12 m6 web-password">
                                <?= $form->field($model, 'em_password'
                                    , [
                                        'template' => "{label}\n{input}\n<div id=\"passwordStrengthDiv1\" class=\"is0\"></div><br>{hint}\n{error}",
                                    ]
                                )
                                    ->passwordInput(['maxlength' => true, 'id' => 'sip_password','placeholder' => (extensionModule::t('app', 'sip_password'))
                                    ])
                                    ->label(extensionModule::t('app', 'sip_password')); ?>
                            <div class="passowrd-btns">
                                <button type='button' class='btn waves-effect waves-light amber darken-4 getNewPass'
                                        onclick="generate('sip_password');"><i class='material-icons'>settings_backup_restore</i>
                                </button>
                                <button type='button' class='btn waves-effect waves-light amber darken-4 togglePassword'
                                        onclick="togglePassword('sip_password');"><i
                                            class='material-icons'>visibility_off</i></button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($model, 'em_extension_number')->textInput([
                                    'maxlength' => TRUE,
                                    'autofocus' => TRUE,
                                    'disabled' => TRUE,
                                ])->label(extensionModule::t('app', 'extension_number')); ?>
                            </div>
                        </div>
                        <div class="col s12 m6">
                            <div class="input-field mt-0">
                                <div class="select-wrapper">
                                    <?= $form->field($model,
                                        'em_timezone_id',
                                        [
                                            'options' => [
                                                'class' => '',
                                            ],
                                        ])->dropDownList(Timezone::getTimezone(),
                                        [
                                            'prompt' => Yii::t('app', 'select'),
                                        ])->label(extensionModule::t('app', 'timezone')); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col s12 pb-3 d-flex align-items-center gap-10">
                <?= Html::a(Yii::t('app', 'cancel'),
                    ['dashboard'],
                    ['class' => 'btn waves-effect waves-light bg-gray-200']) ?>
                <?= Html::submitButton(Yii::t('app', 'update'),
                    [
                        'class' => 'btn waves-effect waves-light amber darken-4',
                        'name' => 'apply',
                        'value' => 'update',
                    ]); ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
<script>
    function randomPassword(field_name, length, only_numbers) {
        var chars = (only_numbers == true) ? "1234567890" : (field_name == 'vm_password') ? 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOP1234567890' : "ABCDEFGHIJKLMNOPabcdefghijklmnopqrstuvwxyz1234567890$@{$(!}%)*#?&";
        var pass = "";

        for (var x = 0; x < length; x++) {
            var i = Math.floor(Math.random() * chars.length);
            pass += chars.charAt(i);
        }
        return pass;
    }

    function generate(field_name) {
        $("#" + field_name).val(randomPassword(field_name, 12, false));
        $("#" + field_name).siblings().addClass('active');
        $("#" + field_name).trigger('keyup');

    }

    function togglePassword(field_name) {
        var pwdType = $("#" + field_name).attr("type");
        var newType = (pwdType === "password") ? "text" : "password";
        $("#" + field_name).attr("type", newType);
    }

    function generatePin(field_name, length) {
        $("#" + field_name).val(randomPassword(field_name, length, true));
        $("#" + field_name).siblings().addClass('active');
        $("#" + field_name).trigger('keyup');
    }

    $(document).ready(function () {
        $('.field-groups-group_status select').formSelect('destroy');
        $('.field-groups-group_status select').css('display', 'block');
        $('.field-groups-group_status select').css('height', '200px');
        $('.field-groups-group_status select').css('border', '1px solid #bdbdbd');


        $('#em_type').on('change', function () {
            if ($(this).find(':selected').val() == 'range') {
                $("#number").hide();
                $("#range").show();
            } else {
                $("#number").show();
                $("#range").hide();
            }
        });
        var is_new = '<?=$model->isNewRecord ?>';

        if (is_new) {
            generate('web_password');
            generate('sip_password');
            generatePin('feature_code_pin', 8);
            generatePin('vm_password', 10);
        }

        $('.multiselect').multiselect({
            sort: false,
            fireSearch: function (value) {
                return value.length > 2;
            }
        });

        $('#em_type').trigger('change');
    });
</script>
