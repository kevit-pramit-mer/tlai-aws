<?php


use app\modules\ecosmob\audiomanagement\models\AudioManagement;
use app\modules\ecosmob\extension\models\Extension;
use app\modules\ecosmob\ringgroup\assets\RingGroupAsset;
use app\modules\ecosmob\ringgroup\RingGroupModule;
use app\modules\ecosmob\services\models\Services;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\ringgroup\models\RingGroup */
/* @var $form yii\widgets\ActiveForm */

RingGroupAsset::register($this);
?>
<script type="text/javascript">
    var deletemsg = "<?php echo RingGroupModule::t('rg', 'delete') ?>";
    var valid_extension = "<?php echo RingGroupModule::t('rg', 'valid_extension') ?>";
    var delete_confirm = "<?php echo Yii::t('app', 'delete_confirm') ?>";
    var already_exits = "<?php echo RingGroupModule::t('rg', 'already_exits') ?>";
</script>


<div class="row">
    <div class="col s12 mb-3">
        <?php $form = ActiveForm::begin([
            'id' => 'ring-group-form',
            'fieldConfig' => [
                'options' => [
                    'class' => 'input-field',
                ],
            ],
            'class' => 'row',
        ]); ?>
        <div id="input-fields" class="card card-tabs mb-2">
            <div class="form-card-header">
                <?= $this->title ?>
            </div>
            <div class="card-content">
                <div class="ring-group-form">
                    <div class="row">
                        <div class="col s12 m6">
                            <?= $form->field($model,
                                'rg_name',
                                [
                                    'inputOptions' => [
                                        'class' => 'form-control',
                                    ],
                                ])->textInput(['maxlength' => true, 'placeholder' => ($model->getAttributeLabel('rg_name'))])->label($model->getAttributeLabel('rg_name')); ?>
                        </div>
                        <div class="col s12 m6">
                            <?= $form->field($model, 'rg_extension')
                                ->textInput([
                                    'maxlength' => 20,
                                    'type' => 'number',
                                    'onkeypress' => 'return isNumberKey(event);',
                                    'oninput' => "maxLengthCheck(this)",
                                    'placeholder' => ($model->getAttributeLabel('rg_extension'))
                                ])
                                ->label($model->getAttributeLabel('rg_extension')); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12 m6">
                            <div class="input-field mt-0">
                                <?= $form->field($model, 'rg_type', ['options' => ['class' => '']])
                                    ->dropDownList(['SIMULTANEOUS' => RingGroupModule::t('rg', 'simultaneous'), 'SEQUENTIAL' => RingGroupModule::t('rg', 'sequential'),],
                                        ['prompt' => RingGroupModule::t('rg', 'select')])
                                    ->label($model->getAttributeLabel('rg_type')); ?>
                            </div>
                        </div>
                        <div class="col s12 m6">
                            <div class="input-field mt-0">
                                <?= $form->field($model, 'rg_moh', ['options' => ['class' => '']])
                                    ->dropDownList(AudioManagement::getMohFiles(),
                                        ['prompt' => RingGroupModule::t('rg', 'select')])
                                    ->label($model->getAttributeLabel('rg_moh')); ?>
                            </div>
                        </div>
                        <div class="col s12 m6">
                            <div class="input-field mt-1">
                                <?= $form->field($model, 'rg_language', ['options' => ['class' => '']])
                                    ->dropDownList(['ENGLISH' => Yii::t('app', 'english'), 'SPANISH' => Yii::t('app', 'spanish'),],
                                        ['prompt' => RingGroupModule::t('rg', 'select')])
                                    ->label($model->getAttributeLabel('rg_language')); ?>
                            </div>
                        </div>
                        <div class="col s12 m6">
                            <div class="input-field mt-1">
                                <?= $form->field($model, 'rg_info_prompt', ['options' => ['class' => '']])
                                    ->dropDownList(AudioManagement::getPromptFiles(),
                                        ['prompt' => RingGroupModule::t('rg', 'select')])
                                    ->label($model->getAttributeLabel('rg_info_prompt')); ?>
                            </div>
                        </div>
                        <div class="col s12 m6">
                            <?= $form->field($model, 'rg_timeout_sec')
                                ->textInput([
                                    'maxlength' => true,
                                    'min' => 0,
                                    'type' => 'number',
                                    'placeholder' => ($model->getAttributeLabel('rg_timeout_sec'))
                                ])
                                ->label($model->getAttributeLabel('rg_timeout_sec')); ?>
                        </div>
                    </div>
                    <div class="row hide1" hidden>
                        <div class="col s6">
                            <div class="input-field mt-0">
                                <?= $form->field($model, 'rg_failed_action', ['options' => ['class' => '']])
                                    ->dropDownList(Services::getServices(), ['prompt' => RingGroupModule::t('rg', 'select')])
                                    ->label($model->getAttributeLabel('rg_failed_action')); ?>
                            </div>
                        </div>
                        <div class="col s6">
                            <div class="input-field mt-0">
                                <?= $form->field($model, 'rg_failed_service_id',
                                    ['options' => ['class' => '', 'id' => 'select_action_value']])
                                    ->dropDownList([], ['prompt' => RingGroupModule::t('rg', 'select')])
                                    ->label($model->getAttributeLabel('rg_failed_service_id')); ?>
                                <?= $form->field($model, 'rg_failed_service_id')
                                    ->textInput(['maxlength' => true, 'id' => 'input_action_value', 'class' => 'mg-t7', 'placeholder' => ($model->getAttributeLabel('rg_failed_service_id'))])
                                    ->label($model->getAttributeLabel('rg_failed_service_id')); ?>

                            </div>
                        </div>
                    </div>
                    <div class="row" hidden>
                        <div class="col s6 p-0 mb-1 mt-1">
                            <div class="col s12 d-flex align-items-center gap-2 switch-input">
                                <p class=h4> <?= $model->getAttributeLabel('rg_call_feature') ?>: </p>
                                <div class="switch">
                                    <label>
                                        <?= RingGroupModule::t('rg', 'off') ?>
                                        <?= Html::activeCheckbox($model, 'rg_call_feature',
                                            ['uncheck' => 0, 'label' => false]) ?>
                                        <span class="lever"></span>
                                        <?= RingGroupModule::t('rg', 'on') ?>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col s6 p-0 mb-1 mt-1">
                            <div class="col s12 d-flex align-items-center gap-2 switch-input">
                                <p class=h4> <?= $model->getAttributeLabel('rg_call_confirm') ?>: </p>
                                <div class="switch">
                                    <label>
                                        <?= RingGroupModule::t('rg', 'off') ?>
                                        <?= Html::activeCheckbox($model, 'rg_call_confirm',
                                            ['uncheck' => 0, 'label' => false]) ?>
                                        <span class="lever"></span>
                                        <?= RingGroupModule::t('rg', 'on') ?>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col s12 m6 p-0 mb-2 mt-1">
                            <div class="col s12 d-flex align-items-center gap-2 switch-input">
                                <p class=h4> <?= $model->getAttributeLabel('rg_is_recording') ?>: </p>
                                <div class="switch">
                                    <label>
                                        <?= RingGroupModule::t('rg', 'off') ?>
                                        <?= Html::activeCheckbox($model, 'rg_is_recording',
                                            ['uncheck' => 0, 'label' => false]) ?>
                                        <span class="lever"></span>
                                        <?= RingGroupModule::t('rg', 'on') ?>
                                    </label>
                                </div>
                            </div>
                        </div>

                       <!-- <div class="col s12 m6 p-0 mb-2 mt-1">
                            <div class="col s12 d-flex align-items-center gap-2 switch-input">
                                <p class=h4> <?php /*= $model->getAttributeLabel('rg_callerid_name') */?>: </p>
                                <div class="switch">
                                    <label>
                                        <?php /*= RingGroupModule::t('rg', 'off') */?>
                                        <?php /*= Html::activeCheckbox($model, 'rg_callerid_name',
                                            ['uncheck' => 0, 'label' => false]) */?>
                                        <span class="lever"></span>
                                        <?php /*= RingGroupModule::t('rg', 'on') */?>
                                    </label>
                                </div>
                            </div>
                        </div>-->
                    </div>

                    <?php if (!$model->isNewRecord) { ?>
                        <div class="row mb-2">
                            <div class="col s12 d-flex align-items-center gap-2 switch-input">
                                <p class=h4> <?= $model->getAttributeLabel('rg_status') ?>: </p>

                                <div class="switch">
                                    <label>
                                        <?= RingGroupModule::t('rg', 'off') ?>
                                        <?= Html::activeCheckbox($model, 'rg_status',
                                            ['uncheck' => 0, 'label' => false]) ?>
                                        <span class="lever"></span>
                                        <?= RingGroupModule::t('rg', 'on') ?>
                                    </label>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <div> <?= $form->field($model,
                            'extension_list')->hiddenInput(['maxlength' => true])->label(false); ?></div>
                </div>
            </div>
        </div>
        <ul class="collapsible collapsible-accordion" data-collapsible="accordion" >
            <li>
                <div class="collapsible-header">
                    <?= RingGroupModule::t('rg', 'add_extension') ?>
                </div>
                <div class="collapsible-body">
                    <div class="form-collapse-body">
                        <div id="Form-advance" class="card-default scrollspy">
                            <div class="row">
                                <div class="col s12 s6 input-field">
                                    <?= $form->field($model, 'etype', ['options' => ['class' => '']])
                                        ->dropDownList(['INTERNAL' => RingGroupModule::t('rg', 'internal'), 'EXTERNAL' => RingGroupModule::t('rg', 'external')],
                                            ['prompt' => RingGroupModule::t('rg', 'select'), 'id' => 'extensiontype'])
                                        ->label(RingGroupModule::t('rg', 'type').' <span style="color: red">*</span>');
                                    ?>
                                </div>
                                <div class="col s12 m6 int-type" hidden>
                                    <div class="input-field">
                                        <?php

                                        $extensionlists = Extension::find()->where(['em_status' => '1'])->all();
                                        foreach ($extensionlists as &$ext) {
                                            $ext->em_extension_name = $ext->em_extension_name . '-' . $ext->em_extension_number;
                                        }
                                        $ext = ArrayHelper::map($extensionlists, 'em_id', 'em_extension_name');
                                        ?>
                                        <?= $form->field($model, 'internal_extention',
                                            ['options' => ['class' => 'intvalue']])
                                            ->dropDownList($ext,
                                                ['prompt' => RingGroupModule::t('rg', 'select'), 'id' => 'intvalue'])
                                            ->label($model->getAttributeLabel('internal_extention').' <span style="color: red">*</span>');
                                        ?>
                                    </div>
                                </div>
                                <div class="col s6 ext-type" hidden>
                                    <div class="input-field">
                                        <?= $form->field($model, 'external_extention',
                                            ['options' => ['class' => 'extvalue']])
                                            ->textInput([
                                                'id' => 'extvalue',
                                                'maxlength' => 15,
                                                /*'type' => 'number',*/
                                                'placeholder' => ($model->getAttributeLabel('external_extention'))
                                            ])
                                            ->label($model->getAttributeLabel('external_extention').' <span style="color: red">*</span>'); ?>
                                    </div>
                                </div>
                                <div class="col s12">
                                    <span id="extension_error" class="help-block" hidden> <?= RingGroupModule::t('rg',
                                            'extension_error'); ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="row form-collapse-body">
                            <div class="col s12 p-0">
                                <div class="input-field center">
                                    <?= Html::button(RingGroupModule::t('rg', 'add_extension'),
                                        ['class' => 'btn btn-primary waves-effect waves-light mb-2 add-extension']); ?>
                                    <?= Html::button(RingGroupModule::t('rg', 'add_all_extensions'),
                                        ['class' => 'btn btn-primary waves-effect waves-light mb-2 add-all-extensions']); ?>
                                </div>
                            </div>
                            <div class="col s12 p-0">
                                <div id="Form-advance" class="card-default scrollspy">
                                    <header class="panel-heading">
                                        <h6 class="panel-title ">
                                            <strong><?= RingGroupModule::t('rg', 'list_extension') ?></strong>
                                        </h6>
                                    </header>
                                    <div class="max-height-300 auto-scroll">
                                        <table class="tbody1">
                                            <thead>
                                                <tr>
                                                    <th><?= Yii::t('app', 'action') ?></th>
                                                    <th><?= RingGroupModule::t('rg', 'type') ?></th>
                                                    <th><?= RingGroupModule::t('rg', 'extension') ?></th>
                                                    <th><?= RingGroupModule::t('rg', 'rank') ?></th>
                                                </tr>
                                            </thead>
                                            <tbody id="dispaly">
                                            <tr id="no_record">
                                                <td colspan="4"><?= RingGroupModule::t('rg', 'no_result') ?></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </li>
        </ul>
        <div class="row mt-2">
            <div class="col s12 pb-3 d-flex align-items-center gap-10">
                <?= Html::a(RingGroupModule::t('rg', 'cancel'),
                    ['index', 'page' => Yii::$app->session->get('page')],
                    ['class' => 'btn waves-effect waves-light bg-gray-200']) ?>
                <input type="hidden" name="apply" id="submittype">
                <?= Html::button($model->isNewRecord ? RingGroupModule::t('rg', 'create') : RingGroupModule::t('rg',
                    'update'),
                    [
                        'class' => $model->isNewRecord
                            ?
                            'btn waves-effect waves-light amber darken-4 submitfrom'
                            :
                            'btn waves-effect waves-light cyan accent-8 submitfrom',
                    ]) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>

<Script>
    var action_value = '<?= $model->rg_failed_service_id ?>';

    $(document).ready(function () {
        $('.field-rg_failed_service_id').hide();
        $('#select_action_value').hide();

        setTimeout(function () {
            changeAction(action_value);
        }, 500);
    });
    // For Apply
   /* $(document).on('click', '.submitfrom', function () {
        $('#submittype').val($(this).val());
    });*/
    //End Apply
    $(document).on('change', '#ringgroup-rg_failed_action', function () {
        changeAction('');
    });

    function changeAction(action_value) {
        var action_id = $('#ringgroup-rg_failed_action').val();

        if (action_id != '') {
            $.ajax({
                type: "POST",
                url: '<?= Url::to(['change-action']); ?>',
                data: {'action_id': action_id, 'action_value': action_value},
                success: function (data) {
                    console.log(action_id);
                    if (action_id == '6') { // external
                        // remove disabled from textbox
                        $('#input_action_value').removeAttr('disabled');
                        $('#input_action_value').val(action_value);

                        // hide select
                        $('#select_action_value').hide();
                        // add disabled in input
                        $('#ringgroup-rg_failed_service_id').attr('disabled', 'disabled');

                        // show textbox
                        $('.field-input_action_value').show();

                    } else {
                        $('#input_action_value').attr('disabled', 'disabled');
                        $('#ringgroup-rg_failed_service_id').removeAttr('disabled');
                        $('#ringgroup-rg_failed_service_id').html(data);

                        $('#select_action_value').show();
                        $('.field-input_action_value').hide();
                    }
                }
            });
        }
    }

    function maxLengthCheck(object) {
        if (object.value.length > object.maxLength)
            object.value = object.value.slice(0, object.maxLength)
    }
</script>