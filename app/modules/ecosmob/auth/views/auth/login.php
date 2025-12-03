<?php

/* @var $form yii\bootstrap\ActiveForm */

/* @var $model app\modules\ecosmob\auth\models\LoginForm */

use app\assets\LoginAsset;
use app\modules\ecosmob\auth\AuthModule;
use app\modules\ecosmob\extension\models\Extension;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = AuthModule::t('auth', 'sign_in');
$this->params['breadcrumbs'][] = $this->title;
LoginAsset::register($this);

$extensionlists = Extension::find()->where(['em_status' => '1'])->all();
foreach ($extensionlists as &$ext) {
    $ext->em_extension_name = $ext->em_extension_name . '-' . $ext->em_extension_number;
}

$ext = ArrayHelper::map($extensionlists, 'em_extension_number', 'em_extension_name');

$img = '';

$credentials = Yii::$app->commonHelper->initialGetTenantConfig($_SERVER['HTTP_HOST']);
if($credentials && $credentials['enable_sso'] && $credentials['SSO_provider']){
    Yii::$app->session->set('enable_sso', $credentials['enable_sso']);
    Yii::$app->session->set('SSO_provider', $credentials['SSO_provider']);
}
?>

<!-- <div class="row">
    <div class=" col s12 text-center">
        <h5 class="ml-4"><?= $this->title ?></h5>
    </div>
</div> -->
<div class="card-panel login-page-section">
    <div class="row" style="display: none" id="login-sections">
    <div class="col s12">
        <ul class="tabs" id="tabs">
            <li class="tab col s6 p-0"><a href="#admin-login"><?= AuthModule::t('auth', 'admin_user') ?></a></li>
            <li class="tab col s6 p-0"><a href="#supervisor-login"><?= AuthModule::t('auth', 'call_center') ?></a></li>
        </ul>
    </div>
    <div id="admin-login" class="col s12">
        <?php $form = ActiveForm::begin([
            'id' => 'login-form',
            'action' => '#admin-login',
            'layout' => 'horizontal',
            'class' => 'login-form',
        ]); ?>
        <div class="row">
            <div class="input-field col s12">
                <label><span class="username-label">Username</span> <span class="red-text">*</span></label>
                <div class="input-group use-name">
                    <?= $form->field($model, 'username', [
                        'template' => '<i class="material-icons prefix">perm_identity</i>{input} <span class="username-error">{error}</span>',
                    ])->textInput([
                        'maxlength' => true,
                        'autofocus' => true,
                        'placeholder' => AuthModule::t('auth', 'username'),
                    ]) ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="input-field col s12 pb-2">
                <label>Password <span class="red-text">*</span></label>
                <div class="input-group">
                    <?= $form->field($model, 'password', [
                        'template' => '<i class="material-icons prefix ">lock</i>{input} {error}',
                    ])->passwordInput(['class' => 'admin-pwd', 'maxlength' => true, 'placeholder' => AuthModule::t('auth', 'password')]) ?>
                    <button type='button'
                            class='btn btn-outline pl-0 togglePassword'
                            onclick="togglePassword('admin-pwd');"><i class='material-icons' id="admin-eye">visibility_off</i>
                    </button>
                </div>
            </div>
        </div>

        <div class="row mb-2 d-flex align-items-center">
            <div class="col s12 m6 pr-0">
                <div class="switch"> 
                    <label class="medium-small">
                        <?= Html::checkbox('loginAsExtension', $model->loginAsExtension, ['uncheck' => 0]) ?>
                        <span class="lever"></span>
                        <?= AuthModule::t('auth', 'login_as_extension') ?>
                    </label>
                </div>
            </div>          
            <div class="col s12 m6">
                <p class="m-0 medium-small right">
                    <a href="<?= Url::to(['auth/forgot']) ?>"
                       class="btn-link waves-effect waves-light text-right"><?= AuthModule::t('auth', 'forgot_password') ?></a>
                </p>
            </div>
        </div>    
        <div class="row rememberme-div">
            <div class="col s12 mb-3">
                <?= $form->field($model, 'rememberMe')->checkbox([
                    'template' => "<div class=\"center medium-small\">{input} {label}</div>",
                ]) ?>
            </div>
        </div>
        <div class="row">
            <?php  if(Yii::$app->session->get('enable_sso') == 1 && !empty(Yii::$app->session->get('SSO_provider'))){ ?>
            <div class="col s6">
                <input type="hidden" name="logintype" value="admin">

                <?= Html::submitButton(AuthModule::t('auth', 'login'), [
                    'class' => 'btn waves-effect waves-light border-round gradient-45deg-purple-deep-orange col s12 auth-btn btn-height',
                    'name' => 'login-button',
                ]) ?>
            </div>

                <div class="col s6">
                    <input type="hidden" name="logintype" value="admin">
                    <?php if(Yii::$app->session->get('SSO_provider') == 'Google'){
                        $img = '<img src="'.Url::to("@web/theme/assets/images/google.png").'"
                             alt="logo"><b><span class="sso-text">Google</span></b>';
                    }else{
                        $img = '<img src="'.Url::to("@web/theme/assets/images/microsoft.png").'"
                             alt="logo">&nbsp&nbsp<b><span class="sso-text">Microsoft</span></b>';
                    }?>
                    <?= Html::a($img, ['saml-login'],
                        ['class' => 'border-round col s12 sso-btn btn-height', 'title' => 'Login With Gmail']) ?>
                </div>
            <?php }else{ ?>
                <div class="col s12">
                    <input type="hidden" name="logintype" value="admin">

                    <?= Html::submitButton(AuthModule::t('auth', 'login'), [
                        'class' => 'btn waves-effect waves-light border-round gradient-45deg-purple-deep-orange col s12 login-btn',
                        'name' => 'login-button',
                    ]) ?>
                </div>
            <?php } ?>
        </div>


        <div class="row mt-2">
           <!-- <div class="col s1 ml-4">
                <input type="hidden" name="logintype" value="admin">
                <?php /*= Html::a('<img src="'.Url::to("@web/theme/assets/images/microsoft.png").'"
                             alt="logo">', ['login-azure'],
                    ['class' => 'auth-btn', 'title' => 'Login With Microsoft']) */?>
            </div>-->
        </div>


        <?php ActiveForm::end(); ?>
    </div>
    <div id="supervisor-login" class="col s12">
        <?php $form = ActiveForm::begin([
            'id' => 'login-form',
            'action' => '#supervisor-login',
            'layout' => 'horizontal',
            'class' => 'login-form',
        ]); ?>
        <div class="row">
            <div class="input-field col s12">
                <label>Username <span class="red-text">*</span></label>
                <div class="input-group">
                    <?= $form->field($model, 'username', [
                        'template' => '<i class="material-icons prefix">perm_identity</i>{input} {error}',
                    ])->textInput([
                        'maxlength' => true,
                        'autofocus' => true,
                        'placeholder' => AuthModule::t('auth', 'username'),
                    ]) ?>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="input-field col s12 pb-2">
                <label>Password <span class="red-text">*</span></label>
                <div class="input-group">
                    <?= $form->field($model, 'password', [
                        'template' => '<i class="material-icons prefix">lock</i>{input} {error}',
                    ])->passwordInput(['class' => 'sup-pwd', 'maxlength' => true, 'placeholder' => AuthModule::t('auth', 'password')]) ?>
                    <button type='button'
                            class='btn btn-outline pl-0 togglePassword'
                            onclick="togglePassword('sup-pwd');"><i class='material-icons' id="sup-eye">visibility_off</i>
                    </button>
                </div>
            </div>
        </div>

        <div class="row mb-2 d-flex align-items-center">
            <div class="col s12 m6 pr-0">
                <div class="switch" style="visibility: hidden;">  
                    <label class="medium-small">
                        <?= Html::checkbox('loginAsExtension', $model->loginAsExtension, ['uncheck' => 0]) ?>
                        <span class="lever"></span>
                        <?= AuthModule::t('auth', 'login_as_extension') ?>
                    </label>
                </div>
            </div>          
            <div class="col s12 m6 pr-0">
                <p class="m-0 medium-small right">
                <p class="margin medium-small text-right" style="">
                    <a href="<?= Url::to(['auth/forgot']) ?>" class="btn-link waves-effect waves-light  text-right  col s12"><?= AuthModule::t('auth', 'forgot_password') ?></a>
                </p>
            </div>
        </div>    
        <div class="row rememberme-div">
            <div class="col s12 mb-3" style="visibility: hidden;">
                <?= $form->field($model, 'rememberMe')->checkbox([
                    'template' => "<div class=\"center medium-small\">{input} {label}</div>",
                ]) ?>
            </div>
        </div>
        <div class="row">
            <?php  if(Yii::$app->session->get('enable_sso') == 1 && !empty(Yii::$app->session->get('SSO_provider'))){ ?>
                <div class="col s6">
                    <input type="hidden" name="logintype" value="supervisor">
                    <?= Html::submitButton(AuthModule::t('auth', 'login'), [
                        'class' => 'btn waves-effect waves-light border-round gradient-45deg-purple-deep-orange col s12 auth-btn btn-height',
                        'id' => 'loginButton',
                        'name' => 'login-button',
                    ]) ?>
                </div>
                <div class="col s6">
                    <input type="hidden" name="logintype" value="supervisor">
                    <?php if(Yii::$app->session->get('SSO_provider') == 'Google'){
                        $img = '<img src="'.Url::to("@web/theme/assets/images/google.png").'"
                             alt="logo"><b><span class="sso-text">Google</span></b>';
                    }else{
                        $img = '<img src="'.Url::to("@web/theme/assets/images/microsoft.png").'"
                             alt="logo">&nbsp&nbsp<b><span class="sso-text">Microsoft</span></b>';
                    }?>
                    <?= Html::a($img, ['saml-login'],
                        ['class' => 'border-round col s12 sso-btn btn-height', 'title' => 'Login With Gmail']) ?>
                </div>
            <?php }else{ ?>
                <div class="col s12">
                    <input type="hidden" name="logintype" value="supervisor">

                    <?= Html::submitButton(AuthModule::t('auth', 'login'), [
                        'class' => 'btn waves-effect waves-light border-round gradient-45deg-purple-deep-orange col s12 login-btn',
                        'id' => 'loginButton',
                        'name' => 'login-button',
                    ]) ?>
                </div>
            <?php } ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
    </div>
</div>



<script>
    $(document).ready(function () {
        $("#login-sections").show();
        $('ul.tabs').tabs();

        $("ul.tabs > li > a").click(function (e) {
            e.preventDefault();
            var id = $(e.target).attr("href").slice(1);
            window.location.hash = id;
        });
        var hash = window.location.hash;
        $('ul.tabs').tabs('select', hash);

        setInterval(function () {
            if ($('[name="loginAsExtension"]').is(":checked")) {
                $('.username-label').text('Extension Number');
                $('#loginform-username').attr("placeholder", "Extension Number");
                if ($(".field-loginform-username").hasClass("has-error")) {
                    $('.username-error .help-block-error').text("Extension Number cannot be blank.");
                }
                $('.rememberme-div').css('visibility', 'hidden');
            } else {
                $('.username-label').text('Username');
                $('#loginform-username').attr("placeholder", "Username");
                if ($(".field-loginform-username").hasClass("has-error")) {
                    $('.username-error .help-block-error').text("Username cannot be blank.");
                }
                $('.rememberme-div').css('visibility', 'visible');
            }
        }, 100);

        $('[name="loginAsExtension"]').click(function(){
           if($(this).is(":checked")){
               $('.username-label').text('Extension Number');
               $('#loginform-username').attr("placeholder", "Extension Number");
               if ($(".field-loginform-username").hasClass("has-error")) {
                   $('.username-error .help-block-error').text("Extension Number cannot be blank.");
               }
               $('.rememberme-div').css('visibility', 'hidden');
           }else{
               $('.username-label').text('Username');
               $('#loginform-username').attr("placeholder", "Username");
               if ($(".field-loginform-username").hasClass("has-error")) {
                   $('.username-error .help-block-error').text("Username cannot be blank.");
               }
               $('.rememberme-div').css('visibility', 'visible');
           }
        });
    });

    $(".alert").animate({opacity: 1.0}, 3000).fadeOut("slow");

    function togglePassword(field_name) {
        var pwdType = $("." + field_name).attr("type");
        var newType = (pwdType === "password") ? "text" : "password";
        $("." + field_name).attr("type", newType);
        if (field_name == 'admin-pwd') {
            var adminEye = $('#admin-eye').text();
            $('#admin-eye').text(adminEye == 'visibility' ? 'visibility_off' : 'visibility');
        } else {
            var supEye = $('#sup-eye').text();
            $('#sup-eye').text(supEye == 'visibility' ? 'visibility_off' : 'visibility');
        }
    }

</script>
