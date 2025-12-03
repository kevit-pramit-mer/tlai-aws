<?php

use app\modules\ecosmob\extension\models\Extension;
use app\modules\ecosmob\extensionsummaryreport\ExtensionSummaryReportModule;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\ecosmob\fail2ban\assets\Fail2banAsset;

$extensionlists = Extension::find()->where(['em_status' => '1'])->all();
foreach ($extensionlists as &$ext) {
    $ext->em_extension_name = $ext->em_extension_name . '-' . $ext->em_extension_number;
}
$ext = ArrayHelper::map($extensionlists, 'em_extension_number', 'em_extension_name');

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\extensionsummaryreport\models\CdrSearch */
/* @var $form yii\widgets\ActiveForm */

Fail2banAsset::register($this);
?>
<ul class="collapsible collapsible-accordion" data-collapsible="accordion" >
    <li>
        <div class="collapsible-header"><i class="material-icons">search</i><?= Yii::t('app', 'search') ?></div>
        <div class="collapsible-body">
            <div id="input-fields">

                <div class="cdr-search"
                     id="cdr-search">

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
                        <div class="col s12 m6 11">
                            <?= $form->field($model, 'start_epoch',
                                ['options' => ['class' => 'col-xs-12 col-md-6']])->textInput(['class' => 'form-control drp datepicker', 'readonly' => true, 'autocomplete' => 'off', 'placeholder' => date('Y-m-d')]); ?>
                        </div>
                        <div class="col s12 m6 11">
                            <?= $form->field($model, 'end_epoch',
                                ['options' => ['class' => 'col-xs-12 col-md-6']])->textInput(['class' => 'form-control drp datepicker', 'readonly' => true, 'autocomplete' => 'off', 'placeholder' => date('Y-m-d')]); ?>
                        </div>
                        <div class="col s12 m6 11">
                            <?php echo $form->field($model, 'caller_id_number', ['options' => ['class' => '', 'id' => 'select_week_off_internal']])->dropDownList($ext, ['prompt' => ExtensionSummaryReportModule::t('extensionsummaryreport', 'select_extension')])->label(ExtensionSummaryReportModule::t('extensionsummaryreport', 'extension')); ?>
                        </div>
                        <div class="col s12 m6 11">
                            <?= $form->field(
                                $model,
                                'direction',
                                ['options' => ['class' => 'col-xs-12 col-md-6']])
                                ->dropDownList(
                                    [
                                        'INCOMING' => ExtensionSummaryReportModule::t('extensionsummaryreport', "incoming"),
                                        'OUTGOING' => ExtensionSummaryReportModule::t('extensionsummaryreport', "outgoing"),
                                    ],
                                    ['prompt' => ExtensionSummaryReportModule::t('extensionsummaryreport', "both")])
                                ->label(ExtensionSummaryReportModule::t('extensionsummaryreport', 'call_type')); ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field center">
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






