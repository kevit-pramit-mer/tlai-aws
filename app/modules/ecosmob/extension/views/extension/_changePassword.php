<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Breadcrumbs;
use app\modules\ecosmob\extension\extensionModule;

/* @var $this yii\web\View */
/* @var $model \app\modules\ecosmob\extension\models\Extension */
/* @var $form yii\widgets\ActiveForm */

$this->title = extensionModule::t('app', 'change_password');
$this->params['breadcrumbs'][] = extensionModule::t('app', 'change_password');
$this->params['pageHead'] = extensionModule::t('app', 'change_password');
?>
<?= Yii::$app->view->renderFile('@app/views/auth/iframe/header.php') ?>
<div id="main" class="extension-main main-full">
    <div class="row">
        <div class="col s12">
            <div class="container">
                <div class="content-wrapper-before"></div>
                <div class="breadcrumbs-dark col s12 m6" id="breadcrumbs-wrapper">
                    <h5 class="breadcrumbs-title mt-0 mb-0"><?= (isset($this->params['pageHead']) ? $this->params['pageHead'] : "") ?></h5>
                    <?= Breadcrumbs::widget([
                        'tag' => 'ol',
                        'options' => ['class' => 'breadcrumbs mb-0'/*, 'target' => 'myFrame'*/],
                        'itemTemplate' => "<li class='breadcrumb-item'>{link}</li>\n",
                        'homeLink' => [
                            'label' => Yii::t('yii', 'Home'),
                            'url' => Url::to(['/extension/extension/dashboard']),
                            //'url' => 'javascript:void(0);',
                            'target' => "extensionFrame",
                            'encode' => false
                        ],
                        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                    ]) ?>
                </div>
                <div class="col-md-12 profile-contain">
                    <div class="row">
                        <div class="col-xl-9 col-md-7 col-xs-12">
                            <div class="content">
                                <div class="change-password">

                                    <div class="row">
                                        <div class="col s12">
                                            <?php $form = ActiveForm::begin(
                                                [
                                                    'class' => 'row mt-3',
                                                    'method' => 'post',
                                                    'id' => 'ext-change-password-active-form',
                                                    'action' => Yii::$app->urlManager->createUrl(
                                                        '/extension/extension/change-password'
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
                                                <div class="card-content change-password-form-data">
                                                    <div class="ext-change-password-form"
                                                         id="ext-change-password-form">
                                                        <div class="row">
                                                            <div class="col s12 m6 l6 web-password">
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
                                                                            onclick="togglePassword('current_password');">
                                                                        <i class='material-icons'>visibility_off</i>
                                                                    </button>
                                                                </div>
                                                            </div>

                                                            <div class="col s12 m6 l6 web-password">
                                                                <?php echo $form->field(
                                                                    $model,
                                                                    'newPassword',
                                                                    [
                                                                        'template' => "{label}\n{input}\n<div id=\"passwordStrengthDiv1\" class=\"is0\"></div>{hint}\n{error}",
                                                                    ]
                                                                )->passwordInput([
                                                                    'maxlength' => TRUE,
                                                                    'data-toggle' => "tooltip",
                                                                    'data-placement' => "left",
                                                                    'id' => "sip_password",
                                                                    'title' => extensionModule::t('app', 'new_password_title'),
                                                                    'autocomplete' => 'new-password',
                                                                    'placeholder' => $model->getAttributeLabel('newPassword')
                                                                ]); ?>
                                                                <div class="passowrd-btns">
                                                                    <button type='button'
                                                                            class='btn btn-link togglePassword sip_password'
                                                                            onclick="togglePassword('sip_password');"><i
                                                                                class='material-icons'>visibility_off</i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                            <div class="col s12 m6 l6 web-password">
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
                                                                            onclick="togglePassword('confirm-password');">
                                                                        <i
                                                                                class='material-icons'>visibility_off</i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row mt-2">
                                                <div class="col s12 pb-3 d-flex align-items-center gap-10">
                                                    <?= Html::a(extensionModule::t('app', 'cancel'),
                                                        ['dashboard'],
                                                        ['class' => 'btn waves-effect waves-light bg-gray-200']) ?>
                                                    <?= Html::submitButton(
                                                        extensionModule::t('app', 'change'),
                                                        ['id' => 'changeBtn', 'class' => 'btn waves-effect waves-light cyan ']
                                                    ) ?>
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
            </div>
        </div>
    </div>
</div>

<script>
    function togglePassword(field_name) {
        var pwdType = $("#" + field_name).attr("type");
        var newType = (pwdType === "password") ? "text" : "password";
        $("#" + field_name).attr("type", newType);
        var newEye = $('.' + field_name + ' .material-icons').text();
        $('.' + field_name + ' .material-icons').text(newEye == 'visibility' ? 'visibility_off' : 'visibility');
    }

    function customCancel() {
        window.parent.document.getElementById('agent_iframe').src = "<?php echo Url::to(['/agents/agents/customdashboard']); ?>";
    }
</script>

