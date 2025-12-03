<?php

use app\modules\ecosmob\admin\AdminModule;
use app\modules\ecosmob\timezone\models\Timezone;
use yii\helpers\Html;
use yii\helpers\Url;
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
                    '/admin/admin/customupdate-profile'
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
                        <div class="col s12 m4 l4">
                            <div class="input-field">
                                <?= $form->field($model, 'adm_firstname')->textInput([
                                    'maxlength' => TRUE,
                                    //'autofocus' => TRUE,
                                    'placeholder' => AdminModule::t('admin', 'f_name')
                                ])->label(AdminModule::t('admin', 'f_name')) ?>
                            </div>
                        </div>
                        <div class="col s12 m4 l4">
                            <div class="input-field">
                                <?= $form->field($model, 'adm_lastname')->textInput([
                                    'maxlength' => TRUE,
                                    'placeholder' => AdminModule::t('admin', 'l_name')
                                ])->label(AdminModule::t('admin', 'l_name')) ?>
                            </div>
                        </div>
                        <div class="col s12 m4 l4">
                            <div class="input-field">
                                <?= $form->field($model, 'adm_email')->textInput([
                                    'maxlength' => TRUE,
                                    'readonly' => (!$model->isNewRecord) ? 'readOnly' : false,
                                ])->label(AdminModule::t('admin', 'email')) ?>
                            </div>
                        </div>
                        <div class="col s12 m4 l4 clear-both">
                            <div class="input-field">
                                <?= $form->field($model, 'adm_username')->textInput(['maxlength' => true, 'readOnly' => (!$model->isNewRecord) ? 'readOnly' : false])
                                    ->label(AdminModule::t('admin',
                                        'adm_username')); ?>
                            </div>
                        </div>
                        <div class="col s12 m4 l4">
                            <div class="input-field">
                                <?= $form->field($model, 'adm_contact')->textInput([
                                    'maxlength' => TRUE,
                                    'onkeypress' => 'return isNumberKey(event);',
                                    'placeholder' => AdminModule::t('admin', 'contact')
                                ])->label(AdminModule::t('admin', 'contact')) ?>
                            </div>
                        </div>
                        <div class="col s12 m4 l4">
                            <div class="input-field ">
                                <div class="select-wrapper">
                                    <?= $form->field($model,
                                        'adm_timezone_id',
                                        [
                                            'options' => [
                                                'class' => '',
                                            ],
                                        ])->dropDownList(Timezone::getTimezone(),
                                        [
                                            'prompt' => AdminModule::t('admin',
                                                'select'),
                                        ])->label(AdminModule::t('admin',
                                        'time_zone')); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col s12 pb-3 d-flex align-items-center gap-10">
                <?= Html::a(AdminModule::t('admin', 'cancel'),
                    "javascript:void(0)",
                    ['class' => 'btn waves-effect waves-light bg-gray-200 custom-profile-cancel', 'onclick' => 'customCancel()']) ?>
                <?=  Html::submitButton(AdminModule::t('admin', 'update'),
                    [
                        'class' => 'btn waves-effect waves-light amber darken-4',
                        'name' => 'apply',
                        'value' => 'update',
                    ]);  ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>

<script>
    function customCancel() {
        window.parent.document.getElementById('agent_iframe').src = "<?php echo Url::to(['/agents/agents/customdashboard']); ?>";
    }
</script>

