<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\ecosmob\timeclockreport\TimeClockReportModule;
use app\modules\ecosmob\timeclockreport\models\TimeClockReportSearch;
use app\modules\ecosmob\auth\models\AdminMaster;

/* @var $this yii\web\View */
/* @var $model TimeClockReportSearch */
/* @var $form yii\widgets\ActiveForm */

$agentName = ArrayHelper::map(AdminMaster::find()->select(['adm_id', 'CONCAT(adm_firstname, " ", adm_lastname) as name'])->where(['adm_status' => '1'])->asArray()->all(), 'adm_id', 'name');
?>

<ul class="collapsible collapsible-accordion" data-collapsible="accordion" >
    <li>
        <div class="collapsible-header"><?= Yii::t('app', 'search') ?></div>
        <div class="collapsible-body">
            <div id="input-fields">
                <div class="time-clock-report-search" id="time-clock-report-search">
                    <?php $form = ActiveForm::begin([
                        'id' => 'time-clock-report-search-form',
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
                        <div class="col s12 m6 l4 calender-icon">
                            <?= $form->field($model, 'from', ['options' => ['class' => 'input-field ']])->textInput(['class' => 'form-control from-date-range', 'readonly' => true, 'autocomplete' => 'off'])->label(TimeClockReportModule::t('timeclockreport', 'from_date')) ?>
                        </div>

                        <div class="col s12 m6 l4 calender-icon">
                            <?= $form->field($model, 'to', ['options' => ['class' => 'input-field ']])->textInput(['class' => 'form-control to-date-range', 'readonly' => true, 'autocomplete' => 'off'])->label(TimeClockReportModule::t('timeclockreport', 'to_date')) ?>
                        </div>
                        <div class="col s12 m6 l4">
                            <div class="select-wrapper ">
                                <?= $form->field($model, 'user_id', ['options' => ['class' => 'input-field']])->dropDownList($agentName, ['prompt' => TimeClockReportModule::t('timeclockreport', 'prompt_user')])->label($model->getAttributeLabel('user_id')); ?>
                            </div>
                        </div>
                        <div class="col s12 mt-1">
                            <div class="input-field">
                                <?= Html::submitButton(TimeClockReportModule::t('timeclockreport', 'search'), ['class' =>
                                    'btn waves-effect waves-light amber darken-4']) ?>
                                <?= Html::a(TimeClockReportModule::t('timeclockreport', 'reset'), ['index', 'page' =>
                                    Yii::$app->session->get('page')],
                                    ['class' => 'btn waves-effect waves-light bg-gray-200 ml-1']) ?>
                            </div>
                        </div>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>

            </div>
        </div>
    </li>
</ul>
