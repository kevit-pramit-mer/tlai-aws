<?php

use app\modules\ecosmob\holiday\HolidayModule;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\holiday\models\HolidaySearch */
/* @var $form yii\widgets\ActiveForm */
?>

<ul class="collapsible collapsible-accordion" data-collapsible="accordion" >
    <li>
        <div class="collapsible-header"><?php echo Yii::t('app', 'search'); ?></div>
        <div class="collapsible-body">
            <div id="input-fields">
                <div class="holiday-search"
                     id="holiday-search">
                    <?php $form = ActiveForm::begin([
                        'id' => 'holiday-search-form',
                        'action' => ['index'],
                        'method' => 'get',
                        'options' => [
                            'data-pjax' => 1
                        ],
                        'fieldConfig' => [
                            'options' => [
                                'class' => 'input-field'
                            ],
                        ],
                    ]); ?>
                    <div class="row">
                        <div class="col s12 m6 l4">
                                <?= $form->field($model, 'hd_holiday')->textInput(['placeholder' => ($model->getAttributeLabel('hd_holiday'))]) ?>
                        </div>

                        <div class="col s12 m6 l4 calender-icon">
                                <?= $form->field($model,'hd_date')->textInput(['class' => 'form-control holiday-from-date-range','readonly'=>true, 'autocomplete' => 'off', 'placeholder' => ($model->getAttributeLabel('hd_date'))])->label(HolidayModule::t('hd',
                                    'hd_date')); ?>
                        </div>
                        <div class="col s12 m6 l4 calender-icon">
                                <?= $form->field($model,'hd_end_date', ['labelOptions' => [ 'class' => 'hd-end-class' ]])->textInput(['class' => 'form-control holiday-to-date-range','readonly'=>true, 'autocomplete' => 'off', 'placeholder' => ($model->getAttributeLabel('hd_end_date'))])->label(HolidayModule::t('hd',
                                    'hd_end_date')); ?>
                        </div>
                        <div class="col s12 mt-1">
                            <div class="input-field">
                            <?= Html::submitButton(HolidayModule::t('hd', 'search'), [
                                'class' =>
                                    'btn waves-effect waves-light amber darken-4'
                            ]) ?>
                            <?= Html::a(HolidayModule::t('hd', 'reset'), [
                                'index',
                                'page' =>
                                    Yii::$app->session->get('page')
                            ],
                                ['class' => 'btn waves-effect waves-light bg-gray-200 ml-1']) ?>
                        </div>
                    </div>


                    <?php ActiveForm::end(); ?>
                </div>

            </div>
        </div>
    </li>
</ul>
<script>
    flatpickr('.holiday-from-date-range', {
        dateFormat: "Y-m-d",
        enableTime: false,
        monthSelectorType: 'static',
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
