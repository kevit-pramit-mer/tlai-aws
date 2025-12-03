<?php

use app\modules\ecosmob\timezone\models\Timezone;
use app\modules\ecosmob\user\assets\UserAsset;
use app\modules\ecosmob\user\UserModule;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\user\models\User */
/* @var $form yii\widgets\ActiveForm */
/* @var $roles */

UserAsset::register($this);
?>

<div class="row">
    <div class="col s12">
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
            <div class="card-content">
                <div class="user-form" id="user-form">
                    <div class="row">
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($model, 'adm_firstname', [
                                    'inputOptions' => [
                                        //'autofocus' => 'autofocus',
                                        'class' => 'form-control',
                                    ],
                                ])
                                    ->textInput(['maxlength' => true, 'placeholder' => ($model->getAttributeLabel('adm_firstname'))])
                                    ->label(UserModule::t('usr',
                                        'adm_firstname')); ?>
                            </div>
                        </div>
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($model, 'adm_lastname')->textInput(['maxlength' => true, 'placeholder' => ($model->getAttributeLabel('adm_lastname'))])
                                    ->label(UserModule::t('usr',
                                        'adm_lastname')); ?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($model, 'adm_username')->textInput(['maxlength' => true, 'placeholder' => ($model->getAttributeLabel('adm_username')), 'readOnly' => (!$model->isNewRecord) ? 'readOnly' : false])
                                    ->label(UserModule::t('usr',
                                        'adm_username')); ?>
                            </div>
                        </div>
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($model, 'adm_email')->textInput(['maxlength' => true, 'placeholder' => ($model->getAttributeLabel('adm_email')), 'readOnly' => (!$model->isNewRecord) ? 'readOnly' : false])->label(UserModule::t('usr',
                                    'adm_email')); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12 m6">
                            <div class="input-field">
                                <div class="select-wrapper">
                                    <?= $form->field($model, 'adm_timezone_id', ['options' => [
                                        'class' => '',
                                    ]])->dropDownList(Timezone::getTimezone(), ['prompt' => UserModule::t('usr',
                                        'select_timezone')])->label(UserModule::t('usr',
                                        'adm_timezone_id')); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col s12 m6">
                            <div class="input-field">
                                <div class="select-wrapper">
                                    <?= $form->field($model, 'adm_is_admin', ['options' => [
                                        'class' => '',
                                    ]])->dropDownList($roles, ['prompt' => UserModule::t('usr',
                                        'select_role')])->label(UserModule::t('usr',
                                        'adm_is_admin')); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12 m6">
                            <div class="input-field">
                                <div class="select-wrapper">
                                    <?= $form->field($model, 'adm_status', ['options' => ['class' => '']])
                                        ->dropDownList([1 => Yii::t('app', 'active'), 0 => Yii::t('app',
                                            'inactive')], ['prompt' => UserModule::t('usr',
                                            'select_status')])->label(UserModule::t('usr',
                                            'adm_status')); ?>
                                </div>
                            </div>
                        </div>
                        <div class="input-field col s12 m6 web-password">
                            <?php
                                if (!$model->isNewRecord) {
                                    $model->adm_password = base64_decode($model->adm_password_hash);
                                }
                            ?>
                            <?= $form->field($model, 'adm_password'
                                , [
                                    'template' => "{label}\n{input}\n<div id=\"passwordStrengthDiv1\" class=\"is0\"></div><div class='mb-2'>{hint}</div>\n{error}",
                                ]
                            )
                            ->passwordInput(['maxlength' => true, 'id' => 'sip_password', 'placeholder' => ($model->getAttributeLabel('adm_password'))
                            ])
                            ->label($model->getAttributeLabel('adm_password')); ?>
                            <div class="passowrd-btns">
                                <button type='button' class='btn waves-effect waves-light amber darken-4 getNewPass'
                                        onclick="generate('sip_password');"><i class='material-icons'>settings_backup_restore</i>
                                </button>
                                <button type='button'
                                    class='btn waves-effect waves-light amber darken-4 togglePassword sip_password'
                                    onclick="togglePassword('sip_password');"><i class='material-icons'>visibility_off</i>
                                </button>
                            </div>                
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col s12 pb-3 d-flex align-items-center gap-10">                
                <?= Html::a(UserModule::t('usr', 'cancel'), ['index', 'page' => Yii::$app->session->get('page')],
                    ['class' => 'btn waves-effect waves-light bg-gray-200']) ?>
                <?= Html::submitButton($model->isNewRecord ? UserModule::t('usr', 'create') : UserModule::t('usr', 'update'), ['class' => $model->isNewRecord ? 'btn waves-effect waves-light amber darken-4' :
                    'btn waves-effect waves-light cyan accent-8']) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>

<script>
    function randomPassword(field_name, length, only_numbers) {
        var str1 = '1234567890';
        var str2 = 'abcdefghijklmnopqrstuvwxyz';
        var str3 = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        var str4 = '$@{$(!}%)*#?&';

        var pass = "";
        var chars = "";

        if (only_numbers == true) {
            chars = str1;
        } else {
            if (length > 0) {
                var i = Math.floor(Math.random() * str1.length);
                pass += str1.charAt(i); // Add 1 numeric
                length--;
            }
            if (length > 0) {
                var i = Math.floor(Math.random() * str2.length);
                pass += str2.charAt(i); // Add 1 small character
                length--;
            }
            if (length > 0) {
                var i = Math.floor(Math.random() * str3.length);
                pass += str3.charAt(i); // Add 1 capital character
                length--;
            }
            chars = str1 + str2 + str3;
            if (field_name == 'sip_password') {  // for em_password only
                if (length > 0) {
                    var i = Math.floor(Math.random() * str4.length); // Add 1 special character
                    pass += str4.charAt(i);
                    chars += str4;
                    length--;
                }
            }
        }
        console.log("length: " + length + ':' + field_name + ':');
        for (var x = 0; x < length; x++) {
            var i = Math.floor(Math.random() * chars.length);
            pass += chars.charAt(i);
        }
        return shuffle(pass);
    }

    function shuffle(s) {
        var arr = s.split('');           // Convert String to array
        var n = arr.length;              // Length of the array

        for (var i = 0; i < n - 1; ++i) {
            var j = getRandomInt(n);       // Get random of [0, n-1]

            var temp = arr[i];             // Swap arr[i] and arr[j]
            arr[i] = arr[j];
            arr[j] = temp;
        }

        s = arr.join('');                // Convert Array to string
        return s;                        // Return shuffled string
    }

    function getRandomInt(n) {
        return Math.floor(Math.random() * n);
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
            var newEye = $('.'+ field_name +' .material-icons').text();
            $('.'+ field_name +' .material-icons').text(newEye == 'visibility' ? 'visibility_off' : 'visibility');
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
            //generate('web_password');
            generate('sip_password');
            // generatePin('feature_code_pin', 8);
            // generatePin('vm_password', 10);
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


<!--<script type="text/javascript">
        $(document).ready(function () {
            Materialize.updateTextFields();
        });

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
            var is_new = '<? /*=$model->isNewRecord */ ?>';

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
-->
