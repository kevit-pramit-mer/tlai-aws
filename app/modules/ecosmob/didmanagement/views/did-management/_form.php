<?php

use app\models\DidTimeBased;
use app\modules\ecosmob\didmanagement\DidManagementModule;
use app\modules\ecosmob\services\models\Services;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\modules\ecosmob\holiday\models\Holiday;
use app\models\TenantModuleConfig;
use app\components\ConstantHelper;
use app\components\CommonHelper;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\didmanagement\models\DidManagement */
/* @var $form yii\widgets\ActiveForm */
/* @var $didTimeBasedModel \app\models\DidTimeBased */

$arrayDiff = [];
if(!empty($didTimeBasedModel)){
    $isUpdate = 1;
    $arrayDiff  = array_diff(ConstantHelper::getDays(), $model->days);
}else{
    $isUpdate = 0;
}

?>

<div class="row">
    <div class="col s12">
        <?php $form = ActiveForm::begin([
            'id' => 'did-form',
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
                <div class="didmaster-form"
                     id="didmaster-form">
                    <div class="row">
                        <div class="col s12 m6">
                            <?= $form->field($model, 'did_number',
                                ['inputOptions' => [
                                   // 'autofocus' => 'autofocus',
                                    'class' => 'form-control',
                                    'onkeypress' => 'return isNumberKey(event);', 'onpaste' => "return paste(this)",
                                    'maxlength' => true, 'disabled' => (TenantModuleConfig::isTrunkDidRoutingEnabled() && $model->from_service == '0' ? false : true),
                                    'placeholder' => ($model->getAttributeLabel('did_number')),
                                    'tabindex' => '1']
                                ])
                                ->textInput(['maxlength' => true])
                                ->label($model->getAttributeLabel('did_number')); ?>
                        </div>

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
                    </div>

                    <div class="row">
                        <div class="col s12 m6">
                            <?= $form->field($model, 'action_id', ['options' => ['class' => '']])
                                ->dropDownList(Services::getServices(), ['prompt' => DidManagementModule::t('did', 'select')])
                                ->label($model->getAttributeLabel('action_id')); ?>
                        </div>
                        <div class="col s12 m6">
                            <div id="">
                                <?= $form->field($model, 'action_value', ['options' => ['class' => '', 'id' => 'select_action_value']])
                                    ->dropDownList([], ['prompt' => DidManagementModule::t('did', 'select')])
                                    ->label($model->getAttributeLabel('action_value')); ?>
                            </div>

                            <?= $form->field($model, 'action_value')
                                ->textInput(['maxlength' => TRUE, 'id' => 'input_action_value', 'placeholder' => ($model->getAttributeLabel('action_value'))])
                                ->label($model->getAttributeLabel('action_value')); ?>
                        </div>
                    </div>

                    <?php if (!$model->isNewRecord) { ?>
                        <div class="row">
                            <div class="col s12 m6 p-0">
                                <div class="input-field">
                                    <div class="col s12 d-flex align-items-center gap-2 switch-input">
                                        <p class=h4> <?= $model->getAttributeLabel('did_status') ?>: </p>
                                        <div class="switch">
                                            <label>
                                                Off
                                                <?= Html::checkbox('did_status', $model->did_status, ['uncheck' => 0]) ?>
                                                <span class="lever"></span>
                                                On
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
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
                            <div class="col s12">
                                <?= $form->field($model, 'holiday', ['options' => ['class' => '']])
                                    ->dropDownList(ArrayHelper::map(Holiday::find()->all(), 'hd_id', 'hd_holiday'), ['multiple' => 'multiple'])
                                    ->label($model->getAttributeLabel('holiday')); ?>

                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col s12 m12">
                                <?= $form->field($model, "days", ['options' => ['class' => '']])
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
                                        <?php
                                        if(!empty($didTimeBasedModel)){
                                        foreach ($didTimeBasedModel as $_didTimeBasedModel) { ?>
                                            <tr class="<?= $_didTimeBasedModel->day ?>-tr d-none">
                                                <td class="center"><?= $form->field($_didTimeBasedModel, "[$_didTimeBasedModel->day]day")->textInput(['readonly' => true])->label(false) ?></td>
                                                <td class="center"><?= $form->field($_didTimeBasedModel, "[$_didTimeBasedModel->day]start_time")->textInput([
                                                        'class' => "form-control $_didTimeBasedModel->day-from-time",
                                                        'value' => CommonHelper::tsToDt(date('Y-m-d '.$_didTimeBasedModel->start_time).':00', 'H:i'),
                                                        'readonly' => true,
                                                    ])->label(false) ?>
                                                </td>
                                                <td class="center"><?= $form->field($_didTimeBasedModel, "[$_didTimeBasedModel->day]end_time")->textInput([
                                                        'class' => "form-control $_didTimeBasedModel->day-to-time",
                                                        'value' => CommonHelper::tsToDt(date('Y-m-d '.$_didTimeBasedModel->end_time).':00', 'H:i'),
                                                        'readonly' => true,
                                                    ])->label(false) ?>
                                                </td>
                                                <td class="center"><?= $form->field($_didTimeBasedModel, "[$_didTimeBasedModel->day]after_hour_action_id", ['options' => ['class' => '']])
                                                        ->dropDownList(Services::getServices(), ['prompt' => DidManagementModule::t('did', 'select')])
                                                        ->label(false); ?>
                                                </td>
                                                <td class="center <?= $_didTimeBasedModel->day ?>-afterHourValue caret-remove">
                                                    <input type="hidden" id="<?= $_didTimeBasedModel->day ?>_hour_value"
                                                           name="<?= $_didTimeBasedModel->day ?>_hour_value"
                                                           value="<?= $_didTimeBasedModel->after_hour_value ?>">
                                                </td>
                                            </tr>
                                        <?php }
                                        if(!empty($arrayDiff)){
                                            $didTimeBasedModel = new DidTimeBased();
                                            foreach($arrayDiff as $_arrayDiffK => $_arrayDiffV){ ?>

                                                <tr class="<?= $_arrayDiffK ?>-tr d-none">
                                                    <td class="center"><?= $form->field($didTimeBasedModel, "[$_arrayDiffK]day")->textInput(['readonly' => true, 'value' => $_arrayDiffV])->label(false) ?></td>
                                                    <td class="center"><?= $form->field($didTimeBasedModel, "[$_arrayDiffK]start_time")->textInput([
                                                            'class' => "form-control $_arrayDiffK-from-time",
                                                            'readonly' => true,
                                                            'value' => '00:00'
                                                        ])->label(false) ?>
                                                    </td>
                                                    <td class="center"><?= $form->field($didTimeBasedModel, "[$_arrayDiffK]end_time")->textInput([
                                                            'class' => "form-control $_arrayDiffK-to-time",
                                                            'placeholder' => '00:00',
                                                            'readonly' => true,
                                                            'value' => '23:59'
                                                        ])->label(false) ?>
                                                    </td>
                                                    <td class="center"><?= $form->field($didTimeBasedModel, "[$_arrayDiffK]after_hour_action_id", ['options' => ['class' => '']])
                                                            ->dropDownList(Services::getServices(), ['prompt' => DidManagementModule::t('did', 'select')])
                                                            ->label(false); ?>
                                                    </td>
                                                    <td class="center <?= $_arrayDiffV ?>-afterHourValue caret-remove"></td>
                                                </tr>

                                           <?php }
                                        }
                                        } else {
                                            $didTimeBasedModel = new DidTimeBased();
                                            foreach (ConstantHelper::getDays() as $key => $value) { ?>
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
                                                    'placeholder' => '00:00',
                                                    'readonly' => true,
                                                    'value' => '23:59'
                                                ])->label(false) ?>
                                            </td>
                                            <td class="center"><?= $form->field($didTimeBasedModel, "[$key]after_hour_action_id", ['options' => ['class' => '']])
                                                    ->dropDownList(Services::getServices(), ['prompt' => DidManagementModule::t('did', 'select')])
                                                    ->label(false); ?>
                                            </td>
                                            <td class="center <?= $value ?>-afterHourValue caret-remove"></td>
                                        </tr>
                                            <?php }
                                        }
                                        ?>
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
                    <?= Html::a(DidManagementModule::t('did', 'cancel'), ['index', 'page' => Yii::$app->session->get('page')],
                        ['class' => 'btn waves-effect waves-light bg-gray-200']) ?>
                    <?= Html::submitButton($model->isNewRecord ? DidManagementModule::t('did', 'create') : DidManagementModule::t('did', 'update'),
                        [
                            'class' => $model->isNewRecord ?
                                'btn waves-effect waves-light amber darken-4'
                                :
                                'btn waves-effect waves-light cyan accent-8'
                        ]) ?>
                </div>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>

<script>
    var action_value = '<?= $model->action_value ?>';
    var is_update = '<?= $isUpdate ?>';

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

        if($('input[name="is_time_based"]').is(":checked")){
            //showValidation();
            $('.time-based-div').show();
        }else{
            //hideValidation();
            $('.time-based-div').hide();
        }

        $('input[name="is_time_based"]').on('change', function () {
            if($(this).is(":checked")){
                //showValidation();
                $('.time-based-div').show();
            }else{
                //hideValidation();
                $('.time-based-div').hide();
            }
        });

        $('input[name="DidManagement[days][]"]').each(function(){
            var day = $(this).val();
            var lowerDay = day.toLowerCase();
            if($(this).is(':checked')){
                $('.'+day+'-tr').show();
            }else{
              /*  $('#didtimebased-'+lowerDay+'-after_hour_action_id').val('1').trigger('change');
                timeBasedChangeAction(day, '1', '');*/
                $('.'+day+'-tr').hide();
            }

        })

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
               /* $('#didtimebased-'+lowerDay+'-after_hour_action_id').val('1').trigger('change');
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

            if ($('#didmanagement-did_number').val() != '' /*&& $('#didmanagement-action_id').val() != ''*/) {
                if($('input[name="is_time_based"]').is(':checked')) {
                    if($('input[name="DidManagement[days][]"]:checked').length == 0){
                        $('.field-didmanagement-days').find('.help-block').text('Please check at least 1 day.');
                        return false;
                    }else{
                        $('.field-didmanagement-days').find('.help-block').text('');
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

        setTimeout(function () {
            changeAction(action_value);
        }, 500);

        setTimeout(function () {
            timeBasedChangeAction('MONDAY', $('#didtimebased-monday-after_hour_action_id').val(), $('#MONDAY_hour_value').val());
            timeBasedChangeAction('TUESDAY', $('#didtimebased-tuesday-after_hour_action_id').val(), $('#TUESDAY_hour_value').val());
            timeBasedChangeAction('WEDNESDAY', $('#didtimebased-wednesday-after_hour_action_id').val(), $('#WEDNESDAY_hour_value').val());
            timeBasedChangeAction('THURSDAY', $('#didtimebased-thursday-after_hour_action_id').val(), $('#THURSDAY_hour_value').val());
            timeBasedChangeAction('FRIDAY', $('#didtimebased-friday-after_hour_action_id').val(), $('#FRIDAY_hour_value').val());
            timeBasedChangeAction('SATURDAY', $('#didtimebased-saturday-after_hour_action_id').val(), $('#SATURDAY_hour_value').val());
            timeBasedChangeAction('SUNDAY', $('#didtimebased-sunday-after_hour_action_id').val(), $('#SUNDAY_hour_value').val());
        }, 500);

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
                        $("."+day+"-afterHourValue").append('<input type="text" id="didtimebased-'+day.toLowerCase()+'-after_hour_value" class="form-control" name="DidTimeBased['+day+'][after_hour_value]" maxlength="100" value='+action_value+'><div class="help-block"></div>');
                    } else {
                        $("."+day+"-afterHourValue").html('');
                        $("."+day+"-afterHourValue").append('<select id="didtimebased-'+day.toLowerCase()+'-after_hour_value" class="form-control select2-hidden-accessible" name="DidTimeBased['+day+'][after_hour_value]" tabindex="-1" data-select2-id="select_'+day+'_action_value" aria-hidden="true"> </select><div class="help-block"></div>');
                        $('#didtimebased-'+day.toLowerCase()+'-after_hour_value').select2();
                        $('#didtimebased-'+day.toLowerCase()+'-after_hour_value').html(data);
                        $('#didtimebased-'+day.toLowerCase()+'-after_hour_value').formSelect();
                    }
                   /* $('#didtimebased-'+day.toLowerCase()+'-after_hour_value').select2('destroy');
                    $('#didtimebased-'+day.toLowerCase()+'-after_hour_value').select2();*/
                }
            });
        }
    }

    function showValidation(){
        if(is_update == 0){
            $('#didtimebased-monday-after_hour_action_id').val('').trigger('change');
            timeBasedChangeAction('MONDAY', '', '');

            $('#didtimebased-tuesday-after_hour_action_id').val('').trigger('change');
            timeBasedChangeAction('TUESDAY', '', '');

            $('#didtimebased-wednesday-after_hour_action_id').val('').trigger('change');
            timeBasedChangeAction('WEDNESDAY', '', '');

            $('#didtimebased-thursday-after_hour_action_id').val('').trigger('change');
            timeBasedChangeAction('THURSDAY', '', '');

            $('#didtimebased-friday-after_hour_action_id').val('').trigger('change');
            timeBasedChangeAction('FRIDAY', '', '');

            $('#didtimebased-saturday-after_hour_action_id').val('').trigger('change');
            timeBasedChangeAction('SATURDAY', '', '');

            $('#didtimebased-sunday-after_hour_action_id').val('').trigger('change');
            timeBasedChangeAction('SUNDAY', '', '');
        }
    }

    function hideValidation(){
        if(is_update == 0){

            $('#didtimebased-monday-after_hour_action_id').val('1').trigger('change');
            timeBasedChangeAction('MONDAY', '1', '');

            $('#didtimebased-tuesday-after_hour_action_id').val('1').trigger('change');
            timeBasedChangeAction('TUESDAY', '1', '');

            $('#didtimebased-wednesday-after_hour_action_id').val('1').trigger('change');
            timeBasedChangeAction('WEDNESDAY', '1', '');


            $('#didtimebased-thursday-after_hour_action_id').val('1').trigger('change');
            timeBasedChangeAction('THURSDAY', '1', '');

            $('#didtimebased-friday-after_hour_action_id').val('1').trigger('change');
            timeBasedChangeAction('FRIDAY', '1', '');

            $('#didtimebased-saturday-after_hour_action_id').val('1').trigger('change');
            timeBasedChangeAction('SATURDAY', '1', '');

            $('#didtimebased-sunday-after_hour_action_id').val('1').trigger('change');
            timeBasedChangeAction('SUNDAY', '1', '');
        }
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


</script>