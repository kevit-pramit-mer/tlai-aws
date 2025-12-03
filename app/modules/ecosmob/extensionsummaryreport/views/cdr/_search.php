<?php

use app\modules\ecosmob\extension\models\Extension;
use app\modules\ecosmob\extensionsummaryreport\ExtensionSummaryReportModule;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$extensionLists = Extension::find()->where(['em_status' => '1'])->all();
foreach ($extensionLists as &$ext) {
    $ext->em_extension_name = $ext->em_extension_name . '-' . $ext->em_extension_number;
}
$ext = ArrayHelper::map($extensionLists, 'em_extension_number', 'em_extension_name');

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\extensionsummaryreport\models\CdrSearch */
/* @var $form yii\widgets\ActiveForm */
?>
<ul class="collapsible collapsible-accordion" data-collapsible="accordion">
    <li>
        <div class="collapsible-header"><?= Yii::t('app', 'search') ?></div>
        <div class="collapsible-body">
            <div id="input-fields">
                <div class="cdr-search" id="cdr-search">
                    <?php $form = ActiveForm::begin([
                        'id' => 'cdr-search-form',
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
                            <?= $form->field($model, 'start_epoch',
                                ['options' => ['class' => '']])->textInput(['class' => 'form-control from-date-time-range', 'readonly' => true, 'placeholder' => ($model->getAttributeLabel('start_epoch')), 'autocomplete' => 'off']); ?>
                        </div>
                        <div class="col s12 m6 l4 calender-icon">
                            <?= $form->field($model, 'end_epoch',
                                ['options' => ['class' => '']])->textInput(['class' => 'form-control to-date-time-range', 'readonly' => true, 'placeholder' => ($model->getAttributeLabel('end_epoch')), 'autocomplete' => 'off']); ?>
                        </div>
                        <div class="col s12 m6 l4 input-field">
                            <?php echo $form->field($model, 'caller_id_number', ['options' => ['class' => '', 'id' => 'select_week_off_internal']])->dropDownList($ext, ['prompt' => ExtensionSummaryReportModule::t('extensionsummaryreport', 'select_extension')])->label(ExtensionSummaryReportModule::t('extensionsummaryreport', 'extension')); ?>
                        </div>
                        <!--<div class="col s12 m6 l4 input-field">
                            <?php /*= $form->field(
                                $model,
                                'direction',
                                ['options' => ['class' => 'mt-3']])
                                ->dropDownList(
                                    [
                                        'INCOMING' => ExtensionSummaryReportModule::t('extensionsummaryreport', "incoming"),
                                        'OUTGOING' => ExtensionSummaryReportModule::t('extensionsummaryreport', "outgoing"),
                                    ],
                                    ['prompt' => ExtensionSummaryReportModule::t('extensionsummaryreport', "both")])
                                ->label(ExtensionSummaryReportModule::t('extensionsummaryreport', 'call_type')); */?>
                        </div>-->
                        <div class="col s12 mt-1">
                            <div class="input-field">
                                <?= Html::submitButton(Yii::t('app', 'search'), [
                                    'class' =>
                                        'btn waves-effect waves-light amber darken-4'
                                ]) ?>
                                <?= Html::a(Yii::t('app', 'reset'), [
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






