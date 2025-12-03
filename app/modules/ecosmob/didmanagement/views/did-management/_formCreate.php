<?php

use app\modules\ecosmob\didmanagement\DidManagementModule;
use app\modules\ecosmob\services\models\Services;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\modules\ecosmob\holiday\models\Holiday;
use app\components\ConstantHelper;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\didmanagement\models\DidManagement */
/* @var $form yii\widgets\ActiveForm */
/* @var $didTimeBasedModel \app\models\DidTimeBased */
?>

<div class="row">
    <div class="col s12">
        <?php $form = ActiveForm::begin([
            'id' => 'did-form',
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
                <div class="didmaster-form"
                     id="didmaster-form">
                    <div class="row">
                        <div class="col s12 m6">
                            <div class="select-wrapper">
                                <?= $form->field($model,
                                    'type',
                                    [
                                        'options' => [
                                            'id' => 'type',
                                            'class' => '',
                                        ],
                                        'inputOptions' => [
                                            'autofocus' => true,
                                        ],

                                    ])->dropDownList(
                                    ['number' => DidManagementModule::t('did', 'number'), 'range' =>
                                        DidManagementModule::t('did', 'range')])
                                    ->label($model->getAttributeLabel('type')); ?>
                            </div>
                        </div>
                        <div class="col s12 m6" id="number">
                            <?= $form->field($model,
                                'did_number',
                                [
                                    'inputOptions' => [
                                        'onkeypress' => 'return isNumberKey(event);', 'onpaste' => "return paste(this)",
                                        'maxlength' => TRUE,
                                        'placeholder' => ($model->getAttributeLabel('did_number'))
                                    ],
                                ])
                                ->textInput(['maxlength' => TRUE])
                                ->label($model->getAttributeLabel('did_number').' <span style="color: red;">*</span>'); ?>
                        </div>
                        <div id="range" class="input-field col s12 m6 p-0" style="display:none;">
                            <div class="col s6">
                                <?= $form->field($model, 'did_range_from')->textInput([
                                    'maxlength' => TRUE,
                                    'class' => 'mt-0',
                                    'onkeypress' => 'return isNumberKey(event);', 'onpaste' => "return paste(this)",
                                    'placeholder' => ($model->getAttributeLabel('did_range_from'))
                                ])->label($model->getAttributeLabel('did_range_from').' <span style="color: red;">*</span>'); ?>
                            </div>

                            <div class="col s6">
                                <?= $form->field($model, 'did_range_to')->textInput([
                                    'maxlength' => TRUE,
                                    'class' => 'mt-0',
                                    'onkeypress' => 'return isNumberKey(event);', 'onpaste' => "return paste(this)",
                                    'placeholder' => ($model->getAttributeLabel('did_range_to'))
                                ])->label($model->getAttributeLabel('did_range_to').' <span style="color: red;">*</span>'); ?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col s12 m6">
                            <?= $form->field($model, 'did_description')->textArea([
                                'rows' => '3',
                                'cols' => '3',
                                'maxlength' => TRUE,
                                'class' => 'materialize-textarea',
                                'placeholder' => ($model->getAttributeLabel('did_description'))
                            ])
                                ->label($model->getAttributeLabel('did_description')); ?>
                        </div>
                        <div class="col s12 m6">
                            <?= $form->field($model, 'action_id', ['options' => ['class' => 'mb-2']])
                                ->dropDownList(Services::getServices(), ['prompt' => DidManagementModule::t('did', 'select')])
                                ->label($model->getAttributeLabel('action_id')); ?>

                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12 m6 ml-0">
                            <div id="">
                                <?= $form->field($model, 'action_value', ['options' => ['class' => 'mb-2', 'id' => 'select_action_value']])
                                    ->dropDownList([], ['prompt' => DidManagementModule::t('did', 'select')])
                                    ->label($model->getAttributeLabel('action_value')); ?>
                            </div>

                            <?= $form->field($model, 'action_value')
                                ->textInput(['maxlength' => TRUE, 'id' => 'input_action_value'])
                                ->label($model->getAttributeLabel('action_value')); ?>
                        </div>
                    </div>
                    <!--    <div class="row">
                        <div class="col m6">
                            <div class="input-field col s12">
                                <?php
                    /*
                                                    $faxList=ArrayHelper::map($faxList, 'id', 'fax_name');
                                                    echo $form->field($model, 'fax', ['options'=>['class'=>'']])->dropDownList($faxList, ['prompt'=>Yii::t('app', 'Select')])->label( $model->getAttributeLabel( 'fax' ));
                                                    */ ?>
                            </div>
                        </div>

                    </div>-->

                    <div class="row">
                        <div class="col s12 m6 p-0">
                            <div class="input-field">
                                <div class="col s12 d-flex align-items-center gap-2 switch-input">
                                    <p class=h4> <?= $model->getAttributeLabel('is_time_based') ?>: </p>
                                    <div class="switch">
                                        <label>
                                            Off
                                            <?= Html::checkbox('is_time_based', $model->is_time_based, ['uncheck' => 0]) ?>
                                            <span class="lever"></span>
                                            On
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="time-based-div">
                        <div class="row">
                            <div class="col s12 m6">
                                <?= $form->field($model, 'holiday', ['options' => ['class' => '']])
                                    ->dropDownList(ArrayHelper::map(Holiday::find()->all(), 'hd_id', 'hd_holiday'), ['multiple' => 'multiple'])
                                    ->label($model->getAttributeLabel('holiday')); ?>
                            </div>
                        </div>
                        <div class="row mt-2">
                          <!-- <?php /*foreach (Yii::$app->params['days'] as $_day) { */?>
                                <div class="col s12 m1 p-0">
                                    <?php /*= $form->field($model, "days[$_day]", ['options' => ['class' => '']])
                                        ->checkbox(['class' => 'check-day', 'id' => "$_day-day", 'label' => $_day])->label(false); */?>
                                </div>
                            --><?php /*} */?>
                            <div class="col s12 m12 ">
                                <?= $form->field($model, "days[]", ['options' => ['class' => '']])
                                    ->checkboxList(ConstantHelper::getDays(), ['class' => 'check-day'])->label(DidManagementModule::t('did', 'days'). ' <span style="color: red;">*</span>'); ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col s12 m12 p-0">
                                <div class="input-field col s12">
                                    <table class="table table-striped table-responsive">
                                        <thead>
                                        <tr>
                                            <th class="center"
                                                style="width: 130px; text-align: center"><?php echo DidManagementModule::t('did', 'day'); ?></th>
                                            <th class="center"
                                                style="width: 200px"><?php echo DidManagementModule::t('did', 'start_time'); ?></th>
                                            <th class="center"
                                                style="width: 200px"><?php echo DidManagementModule::t('did', 'end_time'); ?></th>
                                            <th class="center"
                                                style="width: 200px"><?php echo DidManagementModule::t('did', 'after_hour_action'); ?>&nbsp;<span style="color: red;">*</span></th>
                                            <th class="center"
                                                style="width: 200px"><?php echo DidManagementModule::t('did', 'after_hour_value'); ?>&nbsp;<span style="color: red;">*</span></th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php foreach (ConstantHelper::getDays() as $key => $value) { ?>
                                            <tr class="<?= $key ?>-tr d-none">
                                                <td class="center"><?= $form->field($didTimeBasedModel, "[$key]day")->textInput(['readonly' => true, 'value' => $value])->label(false) ?></td>
                                                <td class="center"><?= $form->field($didTimeBasedModel, "[$key]start_time")->textInput([
                                                        'class' => "form-control $key-from-time",
                                                        'readonly' => true,
                                                        'value' => '00:00'
                                                    ])->label(false) ?>
                                                </td>
                                                <td class="center"><?= $form->field($didTimeBasedModel, "[$key]end_time")->textInput([
                                                        'class' => "form-control $key-to-time",
                                                        'readonly' => true,
                                                        'value' => '23:59'
                                                    ])->label(false) ?>
                                                </td>
                                                <td class="center"><?= $form->field($didTimeBasedModel, "[$key]after_hour_action_id", ['options' => ['class' => 'mt-6']])
                                                        ->dropDownList(Services::getServices(), ['prompt' => DidManagementModule::t('did', 'select')])
                                                        ->label(false); ?>
                                                </td>
                                                <td class="center <?= $value ?>-afterHourValue caret-remove"></td>
                                            </tr>
                                        <?php } ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col s12 pb-3 d-flex align-items-center gap-10">
                <div class="input-field">
                    <?= Html::a(DidManagementModule::t('did', 'cancel'),
                        ['index', 'page' => Yii::$app->session->get('page')],
                        ['class' => 'btn waves-effect waves-light bg-gray-200']) ?>
                    <?= Html::submitButton(DidManagementModule::t('did', 'create'),
                        [
                            'class' => 'btn waves-effect waves-light amber darken-4 submit-btn',
                        ]) ?>
                </div>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>

<Script>
    var action_value = '<?= $model->action_value ?>';

    const monToTimePicker = flatpickr(".MONDAY-to-time", {
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
        time_24hr: true,
        minTime: "00:00",
        maxTime: "23:59",
        enableSeconds: false,
        minuteIncrement: 1,
        "plugins": [
            new ShortcutButtonsPlugin({
                button: {
                    label: 'Close',
                },
                onClick: (index, fp) => {
                    fp.close();
                }
            })],
    });
    const monFromTimePicker = flatpickr(".MONDAY-from-time", {
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
        time_24hr: true,
        minTime: "00:00",
        maxTime: "23:59",
        enableSeconds: false,
        minuteIncrement: 1,
        "plugins": [
            new ShortcutButtonsPlugin({
                button: {
                    label: 'Close',
                },
                onClick: (index, fp) => {
                    fp.close();
                }
            })],
        onChange: function(selectedDates, dateStr, instance) {
            // Update minTime for the "To" time picker
            const selectedTime = selectedDates[0];
            selectedTime.setMinutes(selectedTime.getMinutes() + 1);
            if (selectedTime) {
                monToTimePicker.setDate(selectedTime);
                monToTimePicker.set("minTime", selectedTime);

            }
        }
    });

    const tueToTimePicker = flatpickr(".TUESDAY-to-time", {
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
        time_24hr: true,
        minTime: "00:00",
        maxTime: "23:59",
        enableSeconds: false,
        minuteIncrement: 1,
        "plugins": [
            new ShortcutButtonsPlugin({
                button: {
                    label: 'Close',
                },
                onClick: (index, fp) => {
                    fp.close();
                }
            })],
    });
    const tueFromTimePicker = flatpickr(".TUESDAY-from-time", {
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
        time_24hr: true,
        minTime: "00:00",
        maxTime: "23:59",
        enableSeconds: false,
        minuteIncrement: 1,
        "plugins": [
            new ShortcutButtonsPlugin({
                button: {
                    label: 'Close',
                },
                onClick: (index, fp) => {
                    fp.close();
                }
            })],
        onChange: function(selectedDates, dateStr, instance) {
            // Update minTime for the "To" time picker
            const selectedTime = selectedDates[0];
            selectedTime.setMinutes(selectedTime.getMinutes() + 1);
            if (selectedTime) {
                tueToTimePicker.setDate(selectedTime);
                tueToTimePicker.set("minTime", selectedTime);

            }
        }
    });

    const wedToTimePicker = flatpickr(".WEDNESDAY-to-time", {
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
        time_24hr: true,
        minTime: "00:00",
        maxTime: "23:59",
        enableSeconds: false,
        minuteIncrement: 1,
        "plugins": [
            new ShortcutButtonsPlugin({
                button: {
                    label: 'Close',
                },
                onClick: (index, fp) => {
                    fp.close();
                }
            })],
    });
    const wedFromTimePicker = flatpickr(".WEDNESDAY-from-time", {
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
        time_24hr: true,
        minTime: "00:00",
        maxTime: "23:59",
        enableSeconds: false,
        minuteIncrement: 1,
        "plugins": [
            new ShortcutButtonsPlugin({
                button: {
                    label: 'Close',
                },
                onClick: (index, fp) => {
                    fp.close();
                }
            })],
        onChange: function(selectedDates, dateStr, instance) {
            // Update minTime for the "To" time picker
            const selectedTime = selectedDates[0];
            selectedTime.setMinutes(selectedTime.getMinutes() + 1);
            if (selectedTime) {
                wedToTimePicker.setDate(selectedTime);
                wedToTimePicker.set("minTime", selectedTime);

            }
        }
    });

    const thuToTimePicker = flatpickr(".THURSDAY-to-time", {
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
        time_24hr: true,
        minTime: "00:00",
        maxTime: "23:59",
        enableSeconds: false,
        minuteIncrement: 1,
        "plugins": [
            new ShortcutButtonsPlugin({
                button: {
                    label: 'Close',
                },
                onClick: (index, fp) => {
                    fp.close();
                }
            })],
    });
    const thuFromTimePicker = flatpickr(".THURSDAY-from-time", {
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
        time_24hr: true,
        minTime: "00:00",
        maxTime: "23:59",
        enableSeconds: false,
        minuteIncrement: 1,
        "plugins": [
            new ShortcutButtonsPlugin({
                button: {
                    label: 'Close',
                },
                onClick: (index, fp) => {
                    fp.close();
                }
            })],
        onChange: function(selectedDates, dateStr, instance) {
            // Update minTime for the "To" time picker
            const selectedTime = selectedDates[0];
            selectedTime.setMinutes(selectedTime.getMinutes() + 1);
            if (selectedTime) {
                thuToTimePicker.setDate(selectedTime);
                thuToTimePicker.set("minTime", selectedTime);

            }
        }
    });

    const friToTimePicker = flatpickr(".FRIDAY-to-time", {
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
        time_24hr: true,
        minTime: "00:00",
        maxTime: "23:59",
        enableSeconds: false,
        minuteIncrement: 1,
        "plugins": [
            new ShortcutButtonsPlugin({
                button: {
                    label: 'Close',
                },
                onClick: (index, fp) => {
                    fp.close();
                }
            })],
    });
    const friFromTimePicker = flatpickr(".FRIDAY-from-time", {
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
        time_24hr: true,
        minTime: "00:00",
        maxTime: "23:59",
        enableSeconds: false,
        minuteIncrement: 1,
        "plugins": [
            new ShortcutButtonsPlugin({
                button: {
                    label: 'Close',
                },
                onClick: (index, fp) => {
                    fp.close();
                }
            })],
        onChange: function(selectedDates, dateStr, instance) {
            // Update minTime for the "To" time picker
            const selectedTime = selectedDates[0];
            selectedTime.setMinutes(selectedTime.getMinutes() + 1);
            if (selectedTime) {
                friToTimePicker.setDate(selectedTime);
                friToTimePicker.set("minTime", selectedTime);

            }
        }
    });

    const satToTimePicker = flatpickr(".SATURDAY-to-time", {
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
        time_24hr: true,
        minTime: "00:00",
        maxTime: "23:59",
        enableSeconds: false,
        minuteIncrement: 1,
        "plugins": [
            new ShortcutButtonsPlugin({
                button: {
                    label: 'Close',
                },
                onClick: (index, fp) => {
                    fp.close();
                }
            })],
    });
    const satFromTimePicker = flatpickr(".SATURDAY-from-time", {
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
        time_24hr: true,
        minTime: "00:00",
        maxTime: "23:59",
        enableSeconds: false,
        minuteIncrement: 1,
        "plugins": [
            new ShortcutButtonsPlugin({
                button: {
                    label: 'Close',
                },
                onClick: (index, fp) => {
                    fp.close();
                }
            })],
        onChange: function(selectedDates, dateStr, instance) {
            // Update minTime for the "To" time picker
            const selectedTime = selectedDates[0];
            selectedTime.setMinutes(selectedTime.getMinutes() + 1);
            if (selectedTime) {
                satToTimePicker.setDate(selectedTime);
                satToTimePicker.set("minTime", selectedTime);

            }
        }
    });

    const sunToTimePicker = flatpickr(".SUNDAY-to-time", {
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
        time_24hr: true,
        minTime: "00:00",
        maxTime: "23:59",
        enableSeconds: false,
        minuteIncrement: 1,
        "plugins": [
            new ShortcutButtonsPlugin({
                button: {
                    label: 'Close',
                },
                onClick: (index, fp) => {
                    fp.close();
                }
            })],
    });
    const sunFromTimePicker = flatpickr(".SUNDAY-from-time", {
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
        time_24hr: true,
        minTime: "00:00",
        maxTime: "23:59",
        enableSeconds: false,
        minuteIncrement: 1,
        "plugins": [
            new ShortcutButtonsPlugin({
                button: {
                    label: 'Close',
                },
                onClick: (index, fp) => {
                    fp.close();
                }
            })],
        onChange: function(selectedDates, dateStr, instance) {
            // Update minTime for the "To" time picker
            const selectedTime = selectedDates[0];
            selectedTime.setMinutes(selectedTime.getMinutes() + 1);
            if (selectedTime) {
                sunToTimePicker.setDate(selectedTime);
                sunToTimePicker.set("minTime", selectedTime);

            }
        }
    });

    $(document).ready(function () {
        $('.field-input_action_value').hide();
        $('#select_action_value').hide();
        $('.time-based-div').hide();
        //hideValidation();

        $('input[name="is_time_based"]').on('change', function () {
           if($(this).is(":checked")){
               //showValidation();
               $('.time-based-div').show();
           }else{
               //hideValidation();
               $('.time-based-div').hide();
           }
        });

        setTimeout(function () {
            changeAction(action_value);
        }, 500);

        checkType();

        $('#type').on('change', function () {
            checkType();
        });

        function checkType() {
            if ($('#type').find(':selected').val() == 'number') {
                $("#number").show();
                $("#range").hide();
            } else {
                $("#number").hide();
                $("#range").show();
            }
        }

        setInterval(function () {
            if ($("#didmanagement-holiday").val() == '') {
                $('.field-didmanagement-holiday .select-wrapper .select2-container .selection .select2-selection--multiple ul li .select2-search__field').attr('placeholder', 'Select Holiday');
                $('.field-didmanagement-holiday .select-wrapper .select2-container .selection .select2-selection--multiple ul li .select2-search__field').css('width', 'auto');
            }
        }, 100);
    });

    $(document).on('change', '#didmanagement-action_id', function () {
        changeAction('');
    });

    $(document).on('click', 'input[name="DidManagement[days][]"]', function() {
        var day = $(this).val();
        var lowerDay = day.toLowerCase();
        if($('input[name="DidManagement[days][]"]:checked').length == 0){
            $('.field-didmanagement-days').find('.help-block').text('Please check at least 1 day.');
        }else{
            $('.field-didmanagement-days').find('.help-block').text('');
        }
        if($(this).is(':checked')){
            setStartEndTime(day);
            $('#didtimebased-'+lowerDay+'-after_hour_action_id').val('').trigger('change');
            timeBasedChangeAction(day, '', '');
            $('.field-didtimebased-' + lowerDay + '-after_hour_action_id').find('.help-block').text('');
            $('.'+day+'-tr').show();
        }else{
            /*$('#didtimebased-'+lowerDay+'-after_hour_action_id').val('1').trigger('change');
            timeBasedChangeAction(day, '1', '');*/
            $('.'+day+'-tr').hide();
        }

    });


    $('#did-form').submit(function(e){
        if($('#didmanagement-action_id').val() == 6 && $('#input_action_value').val() == ''){
            $('.field-input_action_value').find('.help-block').text('Action value cannot be blank.');
            return false;
        }else{
            if($('#didmanagement-action_id').val() != 6 && $('#didmanagement-action_value').val() == null){
                $('.field-didmanagement-action_value').find('.help-block').text('Action value cannot be blank.');
                return false;
            }
        }
        var didCount = 0;
        if ($('#didmanagement-type').val() == 'number') {
            if ($('#didmanagement-did_number').val() == '' /*|| $('#didmanagement-action_id').val() == ''*/) {
                didCount++;
            }
        } else {
            if ($('#didmanagement-did_range_from').val() == '' || $('#didmanagement-did_range_to').val() == '' /*|| $('#didmanagement-action_id').val() == ''*/) {
                didCount++;
            }
        }

        if(didCount == 0){
            if($('input[name="is_time_based"]').is(':checked')) {
                if($('input[name="DidManagement[days][]"]:checked').length == 0){
                    $('.field-didmanagement-days').find('.help-block').text('Please check at least 1 day.');
                    return false;
                }
                var count = 0;
                $('input[name="DidManagement[days][]"]:checked').each(function () {
                    var day = $(this).val();
                    var lowerDay = day.toLowerCase();
                    if ($('#didtimebased-' + lowerDay + '-after_hour_action_id').val() == '') {
                        $('.field-didtimebased-' + lowerDay + '-after_hour_action_id').find('.help-block').text('Please select after hour action.');
                        count++;
                    }
                    if ($('#didtimebased-' + lowerDay + '-after_hour_value').val() == null || $('#didtimebased-' + lowerDay + '-after_hour_value').val() == '') {
                        $('.'+day+'-afterHourValue').find('.help-block').text('After hour value cannot be blank.');
                        count++;
                    }

                });

                if (count > 0) {
                    return false;
                } else {
                    return true;
                }
            }else{
                return true;
            }
        }else{
           return true;
        }
    });

    function changeAction(action_value) {
        var action_id = $('#didmanagement-action_id').val();

        if (action_id != '') {
            var csrfToken = $('meta[name="csrf-token"]').attr("content");
            $.ajax({
                type: "POST",
                url: '<?= Url::to(['change-action']); ?>',
                data: {_csrf : csrfToken, 'action_id': action_id, 'action_value': action_value},
                success: function (data) {
                    if (action_id == '6') { // external
                        // show textbox
                        $('.field-input_action_value').show();
                        // remove disabled from textbox
                        $('#input_action_value').removeAttr('disabled');
                        $('#input_action_value').val(action_value);

                        // hide select
                        $('#select_action_value').hide();
                        // add disabled in input
                        $('#didmanagement-action_value').attr('disabled', 'disabled');


                    } else {
                        $('#input_action_value').attr('disabled', 'disabled');
                        $('#didmanagement-action_value').removeAttr('disabled');
                        $('#didmanagement-action_value').select2();
                        $('#didmanagement-action_value').html(data);
                        $('#didmanagement-action_value').formSelect();
                        $('#select_action_value').show();
                        $('.field-input_action_value').hide();
                    }
                    $('#didmanagement-action_value').select2('destroy');
                    $('#didmanagement-action_value').select2();
                }
            });
        }else{
            $('.field-input_action_value').hide();
            $('#select_action_value').hide();
        }
    }

    $(document).on('change', '#didtimebased-monday-after_hour_action_id', function () {
        timeBasedChangeAction('MONDAY', $(this).val(), '');
        if($(this).val() == '') {
            $(".MONDAY-afterHourValue").hide();
        }else{
            $(".MONDAY-afterHourValue").show();
        }
    });
    $(document).on('change', '#didtimebased-tuesday-after_hour_action_id', function () {
        timeBasedChangeAction('TUESDAY', $(this).val(), '');
        if($(this).val() == '') {
            $(".TUESDAY-afterHourValue").hide();
        }else{
            $(".TUESDAY-afterHourValue").show();
        }
    });
    $(document).on('change', '#didtimebased-wednesday-after_hour_action_id', function () {
        timeBasedChangeAction('WEDNESDAY', $(this).val(), '');
        if($(this).val() == '') {
            $(".WEDNESDAY-afterHourValue").hide();
        }else{
            $(".WEDNESDAY-afterHourValue").show();
        }
    });
    $(document).on('change', '#didtimebased-thursday-after_hour_action_id', function () {
        timeBasedChangeAction('THURSDAY', $(this).val(), '');
        if($(this).val() == '') {
            $(".THURSDAY-afterHourValue").hide();
        }else{
            $(".THURSDAY-afterHourValue").show();
        }
    });
    $(document).on('change', '#didtimebased-friday-after_hour_action_id', function () {
        timeBasedChangeAction('FRIDAY', $(this).val(), '');
        if($(this).val() == '') {
            $(".FRIDAY-afterHourValue").hide();
        }else{
            $(".FRIDAY-afterHourValue").show();
        }
    });
    $(document).on('change', '#didtimebased-saturday-after_hour_action_id', function () {
        timeBasedChangeAction('SATURDAY', $(this).val(), '');
        if($(this).val() == '') {
            $(".SATURDAY-afterHourValue").hide();
        }else{
            $(".SATURDAY-afterHourValue").show();
        }
    });
    $(document).on('change', '#didtimebased-sunday-after_hour_action_id', function () {
        timeBasedChangeAction('SUNDAY', $(this).val(), '');
        if($(this).val() == '') {
            $(".SUNDAY-afterHourValue").hide();
        }else{
            $(".SUNDAY-afterHourValue").show();
        }
    });

    function timeBasedChangeAction(day, action_id, action_value) {

        if (action_id != '') {
            var csrfToken = $('meta[name="csrf-token"]').attr("content");
            $.ajax({
                type: "POST",
                url: '<?= Url::to(['change-action']); ?>',
                data: {_csrf : csrfToken, 'action_id': action_id, 'action_value': action_value},
                success: function (data) {

                    if (action_id == '6') { // external
                        $("."+day+"-afterHourValue").html('');
                        $("."+day+"-afterHourValue").append('<input type="text" id="didtimebased-'+day.toLowerCase()+'-after_hour_value" class="form-control" name="DidTimeBased['+day+'][after_hour_value]" maxlength="100"><div class="help-block"></div>');
                    } else {
                        $("."+day+"-afterHourValue").html('');
                        $("."+day+"-afterHourValue").append('<select id="didtimebased-'+day.toLowerCase()+'-after_hour_value" class="form-control select2-hidden-accessible" name="DidTimeBased['+day+'][after_hour_value]" tabindex="-1" data-select2-id="select_'+day+'_action_value" aria-hidden="true"> </select><div class="help-block"></div>');
                        $('#didtimebased-'+day.toLowerCase()+'-after_hour_value').select2();
                        $('#didtimebased-'+day.toLowerCase()+'-after_hour_value').html(data);
                        $('#didtimebased-'+day.toLowerCase()+'-after_hour_value').formSelect();
                    }
                    /*$('#didtimebased-'+day.toLowerCase()+'-after_hour_value').select2('destroy');
                    $('#didtimebased-'+day.toLowerCase()+'-after_hour_value').select2();*/
                }
            });
        }
    }

    function showValidation(){
        $('#didtimebased-monday-after_hour_action_id').val('').trigger('change');
        $('#didtimebased-tuesday-after_hour_action_id').val('').trigger('change');
        $('#didtimebased-wednesday-after_hour_action_id').val('').trigger('change');
        $('#didtimebased-thursday-after_hour_action_id').val('').trigger('change');
        $('#didtimebased-friday-after_hour_action_id').val('').trigger('change');
        $('#didtimebased-saturday-after_hour_action_id').val('').trigger('change');
        $('#didtimebased-sunday-after_hour_action_id').val('').trigger('change');

        timeBasedChangeAction('MONDAY', '', '');
        timeBasedChangeAction('TUESDAY', '', '');
        timeBasedChangeAction('WEDNESDAY', '', '');
        timeBasedChangeAction('THURSDAY', '', '');
        timeBasedChangeAction('FRIDAY', '', '');
        timeBasedChangeAction('SATURDAY', '', '');
        timeBasedChangeAction('SUNDAY', '', '');
    }

    function hideValidation(){
        $('#didtimebased-monday-after_hour_action_id').val('1').trigger('change');
        $('#didtimebased-tuesday-after_hour_action_id').val('1').trigger('change');
        $('#didtimebased-wednesday-after_hour_action_id').val('1').trigger('change');
        $('#didtimebased-thursday-after_hour_action_id').val('1').trigger('change');
        $('#didtimebased-friday-after_hour_action_id').val('1').trigger('change');
        $('#didtimebased-saturday-after_hour_action_id').val('1').trigger('change');
        $('#didtimebased-sunday-after_hour_action_id').val('1').trigger('change');

        timeBasedChangeAction('MONDAY', '1', '');
        timeBasedChangeAction('TUESDAY', '1', '');
        timeBasedChangeAction('WEDNESDAY', '1', '');
        timeBasedChangeAction('THURSDAY', '1', '');
        timeBasedChangeAction('FRIDAY', '1', '');
        timeBasedChangeAction('SATURDAY', '1', '');
        timeBasedChangeAction('SUNDAY', '1', '');
    }

    function setStartEndTime(day){
        if(day == 'MONDAY'){
            monFromTimePicker.setDate('00:00');
            monToTimePicker.setDate('23:59');
        }
        if(day == 'TUESDAY'){
            tueFromTimePicker.setDate('00:00');
            tueToTimePicker.setDate('23:59');
        }
        if(day == 'WEDNESDAY'){
            wedFromTimePicker.setDate('00:00');
            wedToTimePicker.setDate('23:59');
        }
        if(day == 'THURSDAY'){
            thuFromTimePicker.setDate('00:00');
            thuToTimePicker.setDate('23:59');
        }
        if(day == 'FRIDAY'){
            friFromTimePicker.setDate('00:00');
            friToTimePicker.setDate('23:59');
        }
        if(day == 'SATURDAY'){
            satFromTimePicker.setDate('00:00');
            satToTimePicker.setDate('23:59');
        }
        if(day == 'SUNDAY'){
            sunFromTimePicker.setDate('00:00');
            sunToTimePicker.setDate('23:59');
        }
    }


</Script>
