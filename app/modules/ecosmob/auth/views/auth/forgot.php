<?php
/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\modules\ecosmob\auth\models\LoginForm */

/* @var $error app\modules\ecosmob\auth\controllers\AuthController */

use app\modules\ecosmob\auth\AuthModule;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = AuthModule::t('auth', 'recover_password');
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="card-panel">
    <div class="forgot_passoword_v2">
        <div class="forgot_v2_main">
            <div class="forgot_v2_contain">
                <div class="forgot_v2_form text-xs-center">
                    <i class="forgot_v2_profile_icon icon icon_key"></i>
                    <div class="text-center">
                        <h5 class="ml-4"><?= $this->title ?></h5>
                        <p><?= AuthModule::t('auth', 'fill_out_form') ?></p>
                    </div>

                    <?php $form = ActiveForm::begin([
                        'id' => 'forgot-pass-form',
                        'layout' => 'horizontal',
                        'action' => ['auth/forgot'],
                    ]); ?>
                    <div class="input-field col s12 mb-3 b-radius-8 p-0" id="username-div">
                        <?= $form->field($model,
                            'adm_username',
                            [
                                'template' => "<i class='material-icons prefix'>perm_identity</i>{input}{error}",
                            ])->textInput([
                        // 'autofocus' => TRUE,
                            'maxlength' => TRUE,
                            'placeholder' => AuthModule::t('auth', 'enter_username_extension'),
                        ]) ?>
                    <div class="has-error">
                            <div class="col-lg-12">
                                <div class="help-block help-block-error ">
                                    <?php $error; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col s12 mt-4 mb-4 p-0">
                        <div class="switch">
                            <label>
                                <?= Html::checkbox('asExtension', '0', ['uncheck' => 0]) ?>
                                <span class="lever"></span>
                                <?= AuthModule::t('auth', 'forgot_as_extension') ?>
                            </label>
                        </div>
                    </div>

                    <div class="row">
                        <div class=" col s12">
                            <?= Html::submitButton(AuthModule::t('auth', 'reset_password'), [
                                'class' => 'btn waves-effect waves-light border-round gradient-45deg-purple-deep-orange col s12 reset-btn',
                                'name' => 'reset-button',
                            ]) ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="mt-3 col s12">
                            <p class="margin  medium-small"><a href="<?= Url::to(['auth/login']) ?>"
                                                            class="btn-link text-right waves-effect waves-light col s12"><?= AuthModule::t('auth', 'go_back') ?></a>
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
</script>
