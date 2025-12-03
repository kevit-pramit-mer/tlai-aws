<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\modules\ecosmob\auth\models\LoginForm */
/* @var $userType */
/* @var $userId */
/* @var $token */

use app\modules\ecosmob\auth\AuthModule;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use app\modules\ecosmob\auth\assets\AuthAsset;

$this->title = AuthModule::t('auth', 'reset_password');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="card-panel">
    <div class="login_v2">
        <div class="login_v2_main">
            <div class="login_v2_contain reset-password-form">
                <div class="login_v2_form text-xs-center">
                    <i class="login_v2_profile_icon icon icon_lock"></i>
                    <h5><?= Html::encode($this->title) ?></h5>

                    <?php $form = ActiveForm::begin([
                        'id' => 'reset-pass-form',
                        'layout' => 'horizontal',
                        'action' => ['/auth/auth/update-password'],
                    ]); ?>
                    <div class="d-flex align-items-start w-100 position-relative mb-5">
                        <?= $form->field($model,
                            'newPassword',
                            [
                                'template' => "<div class=\"login_v2_text_field w-100\">{input}</div>{error}",
                            ])->passwordInput([
                            'class' => 'new-pwd w-100',
                            'autofocus' => TRUE,
                            'maxlength' => TRUE,
                            'data-toggle' => "tooltip",
                            'data-placement' => "left",
                            'title' => "Ensure that your password is a combination of letters in upper and lower case, digits and special characters",
                            'placeholder' => Yii::t('app', 'newPassword'),
                        ])->label(Yii::t('app', 'newPassword')) ?>
                        <button type='button'
                                class='btn btn-outline togglePassword'
                                onclick="togglePassword('new-pwd');"><i class='material-icons' id="new-eye">visibility_off</i>
                        </button>
                    </div>
                    <?php echo Html::hiddenInput('userId', $userId); ?>
                    <?php echo Html::hiddenInput('userType', $userType); ?>
                    <div class="d-flex align-items-start w-100 position-relative mb-5">
                        <?= $form->field($model,
                            'confirmPassword',
                            [
                                'template' => "<div class=\"login_v2_text_field w-100\">{input}<i class=\"icon icon_key\"></i></div>{error}",
                            ])->passwordInput(['class' => 'confirm-pwd w-100', 'placeholder' => Yii::t('app', 'confirmPassword'), 'maxlength' => TRUE])->label(Yii::t('app', 'newPassword')) ?>
                            <button type='button'
                                    class='btn btn-outline pl-0 togglePassword'
                                    onclick="togglePassword('confirm-pwd');"><i class='material-icons' id="confirm-eye">visibility_off</i>
                            </button>
                    </div>
                    <?= Html::hiddenInput('token', $token) ?>

                    <div class="row">
                        <div class="input-field col s12">
                            <?= Html::submitButton(AuthModule::t('auth', 'reset_password'), [
                                'class' => 'btn waves-effect waves-light border-round gradient-45deg-purple-deep-orange col s12',
                                'name' => 'login-button',
                            ]) ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="mt-3 col s12">
                            <p class="margin  medium-small"><a href="<?= Url::home(TRUE); ?>"
                                                            class="btn-link text-right waves-effect waves-light col s12"><?= AuthModule::t('auth', 'go_to_login') ?></a>
                            </p>
                        </div>
                    </div>

                    <?php ActiveForm::end(); ?>

                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(".alert").animate({opacity: 1.0}, 3000).fadeOut("slow");

    function togglePassword(field_name) {
        var pwdType = $("." + field_name).attr("type");
        var newType = (pwdType === "password") ? "text" : "password";
        $("." + field_name).attr("type", newType);
        if (field_name == 'new-pwd') {
            var newEye = $('#new-eye').text();
            $('#new-eye').text(newEye == 'visibility' ? 'visibility_off' : 'visibility');
        } else {
            var conEye = $('#confirm-eye').text();
            $('#confirm-eye').text(conEye == 'visibility' ? 'visibility_off' : 'visibility');
        }
    }
</script>
