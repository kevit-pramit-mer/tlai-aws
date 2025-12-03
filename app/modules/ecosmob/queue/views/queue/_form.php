<?php

use app\modules\ecosmob\audiomanagement\models\AudioManagement;
use app\modules\ecosmob\queue\assets\QueueAsset;
use app\modules\ecosmob\queue\QueueModule;
use app\modules\ecosmob\services\models\Services;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\queue\models\QueueMaster */
/* @var $form yii\widgets\ActiveForm */
/* @var $availableAgents */
/* @var $availableAgentsUpdate */
/* @var $agents */

QueueAsset::register($this);
?>

<div class="row">
    <div class="col s12 mb-3">
        <?php $form = ActiveForm::begin([
            'id' => 'queue-form',
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
                <div class="queue-master-form" id="queue-master-form">
                    <div class="row">
                        <div class="col s12 m6 input-field">
                            <?= $form->field($model, 'qm_name')
                                ->textInput(['maxlength' => TRUE,'placeholder' => ($model->getAttributeLabel('qm_name'))])
                                ->label($model->getAttributeLabel('qm_name')); ?>
                        </div>
                        <div class="col s12 m6 input-field">
                            <?= $form->field( $model, 'qm_extension' )
                                ->textInput( [
                                    'maxlength'  => TRUE,
                                    'onkeypress' => 'return isNumberKey(event);',
                                    'placeholder' => ($model->getAttributeLabel('qm_extension'))
                                ] )
                                ->label( $model->getAttributeLabel( 'qm_extension' ) ); ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($model, 'qm_strategy', ['options' => ['class' => '']])
                                    ->dropDownList([
                                        //'agent-with-most-talk-time'  => 'Agent-with-most-talk-time',
                                        'agent-with-least-talk-time' => 'Agent-with-least-talk-time',
                                        'longest-idle-agent' => 'Longest-idle-agent',
                                        'random' => 'Random',
                                        'ring-all' => 'Ring-all',
                                        'round-robin' => 'Round-robin',
                                        //  'agent-with-most-calls'      => 'Agent-with-most-calls',
                                        'top-down' => 'Top-Down',
                                    ],
                                        ['prompt' => QueueModule::t('queue', 'select')])
                                    ->label($model->getAttributeLabel('qm_strategy')); ?>
                            </div>
                        </div>
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($model, 'qm_moh', ['options' => ['class' => '']])
                                    ->dropDownList(AudioManagement::getMohFiles(), ['prompt' => QueueModule::t('queue', 'select')])
                                    ->label($model->getAttributeLabel('qm_moh')); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($model, 'qm_language', ['options' => ['class' => '']])
                                    ->dropDownList(['ENGLISH' => Yii::t('app', 'english'), 'SPANISH' => Yii::t('app', 'spanish'),],
                                        ['prompt' => QueueModule::t('queue', 'select')])
                                    ->label($model->getAttributeLabel('qm_language')); ?>
                            </div>
                        </div>
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($model, 'qm_info_prompt', ['options' => ['class' => '']])
                                    ->dropDownList(AudioManagement::getPromptFiles(), ['prompt' => QueueModule::t('queue', 'select')])
                                    ->label($model->getAttributeLabel('qm_info_prompt')); ?>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col s12 m6 input-field">
                            <?= $form->field($model, 'qm_timeout_sec')
                                ->textInput([
                                    'maxlength' => TRUE,
                                    'min' => 0,
                                    'type' => 'number',
                                    'placeholder' => ($model->getAttributeLabel('qm_timeout_sec')),
                                    'value' => !empty($model->qm_timeout_sec) ? $model->qm_timeout_sec : 120,
                                ])
                                ->label($model->getAttributeLabel('qm_timeout_sec')); ?>
                        </div>
                        <div class="col s12 m6 input-field p-0 d-flex align-items-center">
                            <div class="col s6 input-field">
                                <p > <?= $model->getAttributeLabel('qm_is_recording') ?>: </p>
                            </div>
                            <div class="col s6 input-field">
                                <div class="switch">
                                    <label>
                                        <?= QueueModule::t('queue', 'off') ?>
                                        <?= Html::activeCheckbox($model, 'qm_is_recording', ['uncheck' => 0, 'label' => FALSE]) ?>
                                        <span class="lever"></span>
                                        <?= QueueModule::t('queue', 'on') ?>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!--<div class="col s12 m6 input-field p-0">
                            <div class="col s6 input-field">
                                <p class=h4> <?php /*= $model->getAttributeLabel('qm_display_name_in_caller_id') */?>: </p>
                            </div>
                            <div class="col s6 input-field">
                                <div class="switch">
                                    <label>
                                        <?php /*= QueueModule::t('queue', 'off') */?>
                                        <?php /*= Html::activeCheckbox($model, 'qm_display_name_in_caller_id', ['uncheck' => 0, 'label' => FALSE]) */?>
                                        <span class="lever"></span>
                                        <?php /*= QueueModule::t('queue', 'on') */?>
                                    </label>
                                </div>
                            </div>
                        </div>-->
                        <div class="col s12 m6 input-field p-0 d-flex align-items-center">
                            <div class="col s6 input-field pr-0">
                                <p> <?= $model->getAttributeLabel('qm_exit_caller_if_no_agent_available') ?>
                                    : </p>
                            </div>
                            <div class="col s6 input-field">
                                <div class="switch">
                                    <label>
                                        <?= QueueModule::t('queue', 'off') ?>
                                        <?= Html::activeCheckbox($model,
                                            'qm_exit_caller_if_no_agent_available',
                                            ['uncheck' => 0, 'label' => FALSE]) ?>
                                        <span class="lever"></span>
                                        <?= QueueModule::t('queue', 'on') ?>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12 m6 p-0 input-field d-flex align-items-center">
                            <div class="col s6 input-field">
                                <p > <?= $model->getAttributeLabel('qm_play_position_on_enter') ?>: </p>
                            </div>
                            <div class="col s6 input-field">
                                <div class="switch">
                                    <label>
                                        <?= QueueModule::t('queue', 'off') ?>
                                        <?= Html::activeCheckbox($model, 'qm_play_position_on_enter', ['uncheck' => 0, 'label' => FALSE]) ?>
                                        <span class="lever"></span>
                                        <?= QueueModule::t('queue', 'on') ?>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col s12 m6 p-0 input-field d-flex align-items-center">
                            <div class="col s6 input-field">
                                <p > <?= $model->getAttributeLabel('qm_play_position_periodically') ?>: </p>
                            </div>
                            <div class="col s6 input-field">
                                <div class="switch">
                                    <label>
                                        <?= QueueModule::t('queue', 'off') ?>
                                        <?= Html::activeCheckbox($model, 'qm_play_position_periodically', ['uncheck' => 0, 'label' => FALSE]) ?>
                                        <span class="lever"></span>
                                        <?= QueueModule::t('queue', 'on') ?>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col s12 m6 p-0 input-field d-flex align-items-center">
                            <div class="col s6 input-field">
                                <p > <?= $model->getAttributeLabel('qm_auto_answer') ?>: </p>
                            </div>
                            <div class="col s6 input-field">
                                <div class="switch">
                                    <label>
                                        <?= QueueModule::t('queue', 'off') ?>
                                        <?= Html::activeCheckbox($model, 'qm_auto_answer', ['uncheck' => 0, 'label' => FALSE]) ?>
                                        <span class="lever"></span>
                                        <?= QueueModule::t('queue', 'on') ?>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col s12 m6 p-0 input-field d-flex align-items-center">
                            <div class="col s6 input-field">
                                <p > <?= $model->getAttributeLabel('qm_callback') ?>: </p>
                            </div>
                            <div class="col s6 input-field">
                                <div class="switch">
                                    <label>
                                        <?= QueueModule::t('queue', 'off') ?>
                                        <?= Html::activeCheckbox($model, 'qm_callback', ['uncheck' => 0, 'label' => FALSE]) ?>
                                        <span class="lever"></span>
                                        <?= QueueModule::t('queue', 'on') ?>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mg-t7">
                        <div class="col s12 m6 input-field">
                            <?= $form->field($model, 'qm_periodic_announcement')
                                ->textInput([
                                    'maxlength' => TRUE,
                                    'placeholder' => ($model->getAttributeLabel('qm_periodic_announcement')),
                                    'value' => !empty($model->qm_periodic_announcement) ? $model->qm_periodic_announcement : 10,
                                ])
                                ->label($model->getAttributeLabel('qm_periodic_announcement')); ?>
                        </div>
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($model, 'qm_periodic_announcement_prompt', ['options' => ['class' => '']])
                                    ->dropDownList(AudioManagement::getPromptFiles(), ['prompt' => QueueModule::t('queue', 'select')])
                                    ->label($model->getAttributeLabel('qm_periodic_announcement_prompt')); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12 m6 input-field p-0 d-flex align-items-center">
                            <div class="col s6 input-field">
                                <p > <?= $model->getAttributeLabel('qm_is_failed') ?>: </p>
                            </div>
                            <div class="col s6 input-field">
                                <div class="switch">
                                    <label>
                                        <?= QueueModule::t('queue', 'off') ?>
                                        <?= Html::activeCheckbox($model,
                                            'qm_is_failed',
                                            ['uncheck' => 0, 'id' => 'is_failed', 'label' => FALSE]) ?>
                                        <span class="lever"></span>
                                        <?= QueueModule::t('queue', 'on') ?>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row hide1">
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($model, 'qm_failed_service_id', ['options' => ['class' => '']])
                                    ->dropDownList(Services::getQueueOnFailServices(), ['prompt' => QueueModule::t('queue', 'select')])
                                    ->label($model->getAttributeLabel('qm_failed_service_id').' <span style="color: red;">*</span>'); ?>
                            </div>
                        </div>
                        <div class="col s12 m6 select_fail_dropdown">
                            <div class="input-field">
                                <?= $form->field($model, 'qm_failed_action', ['options' => ['class' => '', 'id' => 'select_action_value_fail']])
                                    ->dropDownList([], ['prompt' => QueueModule::t('queue', 'select')])
                                    ->label($model->getAttributeLabel('qm_failed_action').' <span style="color: red;">*</span>'); ?>
                            </div>
                        </div>
                        <div class="col s12 m6 input-field">
                            <?= $form->field($model, 'qm_failed_action')
                                ->textInput([
                                    'maxlength' => 15,
                                    'onkeypress' => 'return isNumberKeyWithPlus(event);',
                                    'id' => 'input_action_value_fail',
                                    'placeholder' => ($model->getAttributeLabel('qm_failed_action'))
                                ])
                                ->label($model->getAttributeLabel('qm_failed_action').' <span style="color: red;">*</span>'); ?>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12 m6 input-field p-0 d-flex align-items-center">
                            <div class="col s6 input-field">
                                <p > <?= $model->getAttributeLabel('qm_is_interrupt') ?>: </p>
                            </div>
                            <div class="col s6 input-field">
                                <div class="switch">
                                    <label>
                                        <?= QueueModule::t('queue', 'off') ?>
                                        <?= Html::activeCheckbox($model,
                                            'qm_is_interrupt',
                                            ['uncheck' => 0, 'id' => 'is_interrupt', 'label' => FALSE]) ?>
                                        <span class="lever"></span>
                                        <?= QueueModule::t('queue', 'on') ?>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col s12 m6 input-field hide2">
                            <?= $form->field($model, 'qm_exit_key')
                                ->textInput([
                                    'maxlength' => TRUE,
                                    'min' => 0,
                                    'onKeyPress' => "return isExitNumberKeyHcustom(event)",
                                    'placeholder' => ($model->getAttributeLabel('qm_exit_key')),
                                    'value' => !empty($model->qm_exit_key) ? $model->qm_exit_key : '1',
                                ])
                                ->label($model->getAttributeLabel('qm_exit_key').' <span style="color: red;">*</span>'); ?>
                        </div>
                    </div>

                    <div class="row hide2">
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($model, 'qm_interrupt_service_id', ['options' => ['class' => '']])
                                    ->dropDownList(Services::getHCustomServices(), ['prompt' => QueueModule::t('queue', 'select')])
                                    ->label($model->getAttributeLabel('qm_interrupt_service_id').' <span style="color: red;">*</span>'); ?>
                            </div>
                        </div>
                        <div class="col s12 m6 select_inter_dropdown">
                            <div class="input-field">
                                <?= $form->field($model,
                                    'qm_interrupt_action',
                                    ['options' => ['class' => '', 'id' => '']])
                                    ->dropDownList([], ['prompt' => QueueModule::t('queue', 'select'), 'id' => 'select_action_value_inter'])
                                    ->label($model->getAttributeLabel('qm_interrupt_action').' <span style="color: red;">*</span>'); ?>
                            </div>
                        </div>
                        <div class="col s12 m6 input-field">
                            <?= $form->field($model, 'qm_interrupt_action')
                                ->textInput([
                                    'maxlength' => 15,
                                    'onkeypress' => 'return isNumberKeyWithPlus(event);',
                                    'id' => 'input_action_value_inter',
                                    'placeholder' => ($model->getAttributeLabel('qm_interrupt_action'))
                                ])
                                ->label($model->getAttributeLabel('qm_interrupt_action')); ?>
                        </div>
                    </div>

                    <div class="row mg-t7">
                        <header class="panel-heading col s12">
                            <h6>
                                <strong><?= QueueModule::t('queue', 'assign_agents'); ?><strong>
                            </h6>
                        </header>
                    </div>
                    <div class="row avagent">
                        <div class="col s12 p-0 align-items-center d-flex">
                            <div class="form-group field-groups-group_status required col s5">
                                <label class="tag tag-pill tag-danger">
                                    <?php echo QueueModule::t(
                                        'queue',
                                        'available'
                                    ) ?></label>
                                <select id="availableAgents"
                                        size="8"
                                        multiple="multiple"
                                        class="multiselect form-control"
                                        data-target="avaliable">
                                    <?php
                                    if ($model->isNewRecord) {
                                        foreach (
                                            $availableAgents as $key =>
                                            $value
                                        ) {
                                            echo "<option value='"
                                                . $key . "'>" . $value
                                                . "</option>";
                                        }
                                    } else {
                                        foreach (
                                            $availableAgentsUpdate
                                            as $key => $value
                                        ) {
                                            echo "<option value='"
                                                . $key . "'>" . $value
                                                . "</option>";

                                        }
                                    } ?>
                                </select>
                            </div>
                            <div class="col s2 text-center ml-auto mr-auto p-0 btn_div mt-0 multiselect-btn-pad">
                                <button type="button" id="btnRight"
                                        class="btn-margin-bottom btn btn-block multiselect_btn"
                                        data-target='avaliable'>
                                    <i class="material-icons">keyboard_arrow_right</i>
                                </button>
                                <button type="button" id="btnLeft"
                                        class="btn-margin-bottom btn btn-block multiselect_btn"
                                        data-target='assigned'>
                                    <i class="material-icons">keyboard_arrow_left</i>
                                </button>
                            </div>
                            <div class="form-group field-groups-group_status required col s5">

                                <label class="tag tag-pill tag-success">
                                    <?php echo QueueModule::t(
                                        'queue',
                                        'assigned'
                                    ) ?>
                                </label>
                                <select id="assignedAgents"
                                        multiple size="7"
                                        class="multiselect form-control list"
                                        data-target="avaliable">

                                    <?php

                                    if ($model->isNewRecord) {

                                    } else {
                                        foreach (
                                            $agents as $key =>
                                            $value
                                        ) {
                                            echo "<option value='"
                                                . $key . "'>" . $value
                                                . "</option>";
                                        }
                                    } ?>
                                </select>
                                <?php
                                echo $form->field($model, 'agents')->hiddenInput(['id' => 'selectedAgents'])->label(FALSE)
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col s12 pb-3 d-flex align-items-center gap-10">
                <div class="input-field">
                    <?= Html::a(QueueModule::t('queue', 'cancel'),
                        ['index', 'page' => Yii::$app->session->get('page')],
                        ['class' => 'btn waves-effect waves-light bg-gray-200']) ?>
                    <?= Html::submitButton($model->isNewRecord ? QueueModule::t('queue', 'create') : QueueModule::t('queue', 'update'),
                        [
                            'class' => $model->isNewRecord
                                ? 'btn waves-effect waves-light amber darken-4'
                                :
                                'btn waves-effect waves-light cyan accent-8',
                        ]) ?>
                </div>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
<script>
    var action_value_failed = '<?= $model->qm_failed_action ?>';
    var action_value_inter = '<?= $model->qm_interrupt_action ?>';

    $(document).ready(function () {
        $(".hide1").hide();
        $(".hide2").hide();
        $('.field-input_action_value_fail').hide();
        $('.field-input_action_value_inter').hide();

        setTimeout(function () {
            changeActionFail(action_value_failed);
            changeActionInter(action_value_inter);
        }, 500);

        setTimeout(function () {
            $('#is_failed').is(':checked') ? $(".hide1").show() : '';
            $('#is_interrupt').is(':checked') ? $(".hide2").show() : '';
        });

        $('.field-groups-group_status select').formSelect('destroy');
        $('.field-groups-group_status select').css('display', 'block');
        $('.field-groups-group_status select').css('height', '200px');
        $('.field-groups-group_status select').css('border', '1px solid #bdbdbd');
    });

    $(document).on('change', '#queuemaster-qm_failed_service_id', function () {
        changeActionFail('');
    });

    $(document).on('change', '#queuemaster-qm_interrupt_service_id', function () {
        changeActionInter('');
    });

    function changeActionFail(action_value_failed) {
        var action_id = $('#queuemaster-qm_failed_service_id').val();

        if (action_id != '') {
            $.ajax({
                type: "POST",
                url: '<?= Url::to(['change-action']); ?>',
                data: {'action_id': action_id, 'action_value': action_value_failed},
                success: function (data) {
                    if (action_id == '6') { // external
                        // remove disabled from textbox
                        $('#input_action_value_fail').removeAttr('disabled');
                        $('#input_action_value_fail').val(action_value_failed);

                        // hide select
                        $('.select_fail_dropdown').hide();
                        // add disabled in input
                        $('#queuemaster-qm_failed_action').attr('disabled', 'disabled');

                        // show textbox
                        $('.field-input_action_value_fail').show();

                    } else {
                        $('#input_action_value_fail').attr('disabled', 'disabled');
                        $('#queuemaster-qm_failed_action').removeAttr('disabled');
                        $('#queuemaster-qm_failed_action').html(data);
                        $('select').formSelect();
                        $('.select_fail_dropdown').show();
                        $('.field-input_action_value_fail').hide();
                    }
                    $('select').select2();
                    $('#availableAgents').select2('destroy');
                    $('#assignedAgents').select2('destroy');

                }
            });
        } else {
            //$('#queuemaster-qm_failed_action').html('');
            $('.select_fail_dropdown').hide();
            $('.field-input_action_value_fail').hide();
        }
    }

    function changeActionInter(action_value_inter) {
        var action_id = $('#queuemaster-qm_interrupt_service_id').val();

        if (action_id != '') {
            $.ajax({
                type: "POST",
                url: '<?= Url::to(['change-action']); ?>',
                data: {'action_id': action_id, 'action_value': action_value_inter},
                success: function (data) {
                    if (action_id == '6') { // external
                        // remove disabled from textbox
                        $('#input_action_value_inter').removeAttr('disabled');
                        $('#input_action_value_inter').val(action_value_inter);

                        // hide select
                        $('.select_inter_dropdown').hide();
                        // add disabled in input
                        $('#select_action_value_inter').attr('disabled', 'disabled');

                        // show textbox
                        $('.field-input_action_value_inter').show();

                    } else {
                        $('#input_action_value_inter').attr('disabled', 'disabled');
                        $('#select_action_value_inter').removeAttr('disabled');
                        $('#select_action_value_inter').html(data);
                        $('select').formSelect();
                        $('.select_inter_dropdown').show();
                        $('.field-input_action_value_inter').hide();
                    }
                    $('select').select2();
                    $('#availableAgents').select2('destroy');
                    $('#assignedAgents').select2('destroy');

                }
            });
        } else {
            //$('#select_action_value_inter').html('');
            $('.select_inter_dropdown').hide();
            $('.field-input_action_value_inter').hide();
        }
    }

    $('#is_failed').change(function () {
        var optionSelected = $(this).is(':checked');
        if (optionSelected) {
            $(".hide1").show(function () {
                $('select').select2();
                $('#availableAgents').select2('destroy');
                $('#assignedAgents').select2('destroy');
            });
        } else {
            $(".hide1").hide(500);
        }

    });

    $('#is_interrupt').change(function () {
        var optionSelected = $(this).is(':checked');
        if (optionSelected) {
            $(".hide2").show(function () {
                $('select').select2();
                $('#availableAgents').select2('destroy');
                $('#assignedAgents').select2('destroy');
            });
        } else {
            $(".hide2").hide(500);
        }

    });
</script>
