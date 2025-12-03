<?php

use app\modules\ecosmob\admin\AdminModule;
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
                'class' => 'row',
                'method' => 'post',
                'id' => 'admin-change-password-active-form',
                'action' => Yii::$app->urlManager->createUrl(
                    '/admin/admin/change-password'
                ),
                'fieldConfig' => [
                    'options' => [
                        'class' => 'input-field ',
                    ],
                ],
            ]
        ); ?>
        <div id="input-fields" class="card card-tabs">
            <div class="form-card-header">
                <?= $this->title ?>
            </div>
            <div class="card-content change-password-form-data">
                <div class="admin-change-password-form" id="admin-change-password-form">
                    <div class="row">
                        <div class="col s12 m6 web-password">
                            <?php echo $form->field(
                                $model,
                                'oldPassword'
                            )->passwordInput([
                                'maxlength' => TRUE,
                                'id' => 'current_password',
                                'autocomplete' => 'new-password',
                                'autofocus' => 'on',
                                'placeholder' => $model->getAttributeLabel('oldPassword')
                            ]); ?>
                            <div class="passowrd-btns">
                                <button type='button'
                                        class='btn btn-link togglePassword current_password'
                                        onclick="togglePassword('current_password');"><i class='material-icons'>visibility_off</i>
                                </button>
                            </div>
                        </div>

                        <div class="col s12 m6 web-password">
                            <?php echo $form->field(
                                $model,
                                'newPassword',
                                [
                                    'template' => "{label}\n{input}\n<div id=\"passwordStrengthDiv1\" class=\"is0 mt-1\"></div>{hint}\n{error}",
                                ]
                            )->passwordInput([
                                'maxlength' => TRUE,
                                'data-toggle' => "tooltip",
                                'data-placement' => "left",
                                'id' => "sip_password",
                                'title' => AdminModule::t('admin', 'new_password_title'),
                                'autocomplete' => 'new-password',
                                'placeholder' => $model->getAttributeLabel('newPassword')
                            ]); ?>
                            <div class="passowrd-btns">
                                <button type='button'
                                        class='btn btn-link togglePassword sip_password'
                                        onclick="togglePassword('sip_password');"><i class='material-icons'>visibility_off</i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12 m6 web-password">
                            <?php echo $form->field(
                                $model,
                                'confirmPassword'
                            )->passwordInput([
                                'maxlength' => TRUE,
                                'id' => 'confirm-password',
                                'autocomplete' => 'new-password',
                                'placeholder' => $model->getAttributeLabel('confirmPassword')
                            ]); ?>
                            <div class="passowrd-btns">    
                                <button type='button'
                                        class='btn btn-link togglePassword confirm-password'
                                        onclick="togglePassword('confirm-password');"><i class='material-icons'>visibility_off</i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="row mt-2">
            <div class="col s12 pb-3 d-flex align-items-center gap-10">
                <?= Html::a(AdminModule::t('admin', 'cancel'),
                    ['index'],
                    ['class' => 'btn waves-effect waves-light bg-gray-200']) ?>
                <?= Html::submitButton(
                    AdminModule::t('admin', 'change'),
                    ['id' => 'changeBtn', 'class' => 'btn waves-effect waves-light cyan ']
                ) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>

<Script>
    function togglePassword(field_name) {
        var pwdType = $("#" + field_name).attr("type");
        var newType = (pwdType === "password") ? "text" : "password";
        $("#" + field_name).attr("type", newType);
        var newEye = $('.'+ field_name +' .material-icons').text();
        $('.'+ field_name +' .material-icons').text(newEye == 'visibility' ? 'visibility_off' : 'visibility');
    }
</Script>
