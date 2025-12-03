<?php

use app\modules\ecosmob\holiday\HolidayModule;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\ecosmob\holiday\assets\HolidayAsset;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\holiday\models\Holiday */
/* @var $form yii\widgets\ActiveForm */

HolidayAsset::register($this);
?>

<div class="row">
    <div class="col s12">
        <?php $form = ActiveForm::begin([
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
                <div class="holiday-form" id="holiday-form">
                    <div class="row">
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($model,
                                    'hd_holiday'
                                )->textInput(['maxlength' => true, 'placeholder' => ($model->getAttributeLabel('hd_holiday'))])->label(HolidayModule::t('hd',
                                    'hd_holiday')); ?>
                            </div>
                        </div>
                        <div class="col s12 m6 calender-icon">
                            <div class="input-field">
                                <?= $form->field($model, 'hd_date')->textInput([
                                        'class' => 'form-control holiday-from-date-range',
                                    'readonly' => true, 'autocomplete' => 'off',
                                    'placeholder' => ($model->getAttributeLabel('hd_date'))

                                ])
                                    ->label(HolidayModule::t('hd',
                                    'hd_date')) ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12 m6 calender-icon">
                            <div class="input-field">
                                <?= $form->field($model, 'hd_end_date', ['labelOptions' => [ 'class' => 'hd-end-class' ]])->textInput([ 'class' => 'form-control holiday-to-date-range', 'readonly' => true, 'autocomplete' => 'off', 'placeholder' => HolidayModule::t('hd', 'hd_end_date')])->label(HolidayModule::t('hd',
                                    'hd_end_date').' <span style="color: red">*</span>'); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col s12 pb-3 d-flex align-items-center gap-10">
                <div class="input-field">
                    <?= Html::a(HolidayModule::t('hd', 'cancel'), ['index', 'page' => Yii::$app->session->get('page')],
                        ['class' => 'btn waves-effect waves-light bg-gray-200']) ?>
                    <?= Html::submitButton($model->isNewRecord ? HolidayModule::t('hd', 'create') : HolidayModule::t('hd',
                        'update'), [
                        'class' => $model->isNewRecord ? 'btn waves-effect waves-light amber darken-4' :
                            'btn waves-effect waves-light cyan accent-8'
                    ]) ?>
                </div>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
<script>
    var isNew = '<?= $model->isNewRecord ?>';
    var toDate = new Date();
    var holidayDate = '<?= json_encode(date('Y-m-d', strtotime($model->hd_date))); ?>'
    var minDate = toDate;
    if(new Date(holidayDate) <  toDate){
        minDate = holidayDate;
    }

    flatpickr('.holiday-from-date-range', {
        dateFormat: "Y-m-d",
        enableTime: false,
        monthSelectorType: 'static',
        minDate: minDate,
        "plugins": [
            new rangePlugin({input: ".holiday-to-date-range"}),
            new ShortcutButtonsPlugin({
                button: {
                    label: 'Close',
                },
                onClick: (index, fp) => {
                    fp.close();
                }
            })],
        onChange: function(selectedDates, dateStr, instance) {
           $('.hd-end-class').addClass('active');
        }
    });
</script>
