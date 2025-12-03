<?php

use app\modules\ecosmob\fraudcalldetectionreport\FraudCallDetectionReportModule;
use app\modules\ecosmob\report\assets\ApplyDaterangepickerAsset;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

ApplyDaterangepickerAsset::register($this);

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\fraudcalldetectionreport\models\InboundCdrSearch */
/* @var $form yii\widgets\ActiveForm */
?>

    <div class="inboundcdr-search"
         id="inboundcdr-search">

        <?php $form = ActiveForm::begin([
            'id' => 'inboundcdr-search-form',
            'action' => ['index'],
            'method' => 'get',
            'options' => [
                'data-pjax' => 1
            ],
        ]); ?>
        <div class="card-accordions">
            <div id="accordion" role="tablist" aria-multiselectable="true">
                <div class="card">
                    <div class="card-header card-custom">
                        <a class="card-title" data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                            <h6 class="mb-0"><span class="fa fa-search"></span>
                                <?= FraudCallDetectionReportModule::t('cdr', 'search') ?>
                            </h6></a>
                    </div>
                    <div id="collapseOne" class="collapse">
                        <div class="card-block">
                            <div class="row">
                                <div class="form-group" style="display: none">
                                    <?= $form->field($model, 'main_uuid', ['options' => ['class' => 'col-xs-12 col-md-6']])->textInput(['maxlength' => true, 'placeholder' => FraudCallDetectionReportModule::t('cdr', "main_uuid")]); ?>
                                </div>
                                <div class="form-group">
                                    <?= $form->field($model, 'outpluse_dialed_number', ['options' => ['class' => 'col-xs-12 col-md-6']])->textInput(['maxlength' => true, 'placeholder' => FraudCallDetectionReportModule::t('cdr', "outpluse_dialed_number")]); ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <?= $form->field($model, 'start_epoch',
                                        ['options' => ['class' => 'col-xs-12 col-md-6']])->textInput(['class' => 'form-control drp', 'autocomplete' => 'off', 'placeholder' => date('Y-m-d 00:00:00') . " - " . date('Y-m-d 23:59:59')]); ?>

                                    <?= $form->field($model, 'answer_epoch',
                                        ['options' => ['class' => 'col-xs-12 col-md-6']])->textInput(['class' => 'form-control drp', 'autocomplete' => 'off', 'placeholder' => 'Answer Time']); ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group">
                                    <?= $form->field($model, 'end_epoch',
                                        ['options' => ['class' => 'col-xs-12 col-md-6']])->textInput(['class' => 'form-control drp', 'autocomplete' => 'off', 'placeholder' => 'End Time']); ?>

                                    <?= $form->field(
                                        $model,
                                        'callstatus',
                                        ['options' => ['class' => 'col-xs-12 col-md-6']]
                                    )->dropDownList(
                                        [
                                            'failed' => 'failed',
                                            'completed' => 'completed',
                                        ],
                                        ['prompt' => FraudCallDetectionReportModule::t('cdr', "all")]
                                    ); ?>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group">
                                    <?= $form->field(
                                        $model,
                                        'call_type',
                                        ['options' => ['class' => 'col-xs-12 col-md-6']]
                                    )->dropDownList(
                                        [
                                            'onnet' => 'onnet',
                                            'offnet' => 'offnet',
                                        ],
                                        ['prompt' => FraudCallDetectionReportModule::t('cdr', "all")]
                                    ); ?>

                                    <?php if (Yii::$app->user->identity->user_type == 'admin') { ?>
                                        <?= $form->field(
                                            $model,
                                            'sp_name',
                                            ['options' => ['class' => 'col-xs-12 col-md-6']]
                                        )->dropDownList(
                                            ArrayHelper::map($DdData, function ($DdData) {
                                                return $DdData['first_name'] . " " . $DdData['last_name'];
                                            }, function ($DdData) {
                                                return $DdData['first_name'] . " " . $DdData['last_name'];
                                            }),
                                            ['prompt' => FraudCallDetectionReportModule::t('cdr', "all")]
                                        ); ?>
                                    <?php } ?>

                                    <?php if (Yii::$app->user->identity->user_type == 'service_provider') { ?>
                                        <?= $form->field(
                                            $model,
                                            'user_name',
                                            ['options' => ['class' => 'col-xs-12 col-md-6']]
                                        )->dropDownList(
                                            ArrayHelper::map($DdData, function ($DdData) {
                                                return $DdData['first_name'] . " " . $DdData['last_name'];
                                            }, function ($DdData) {
                                                return $DdData['first_name'] . " " . $DdData['last_name'];
                                            }),
                                            ['prompt' => FraudCallDetectionReportModule::t('cdr', "all")]
                                        ); ?>
                                    <?php } ?>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-sm-offset-5 col-md-offset-5 col-xs-offset-2">
                                    <?= Html::submitButton(FraudCallDetectionReportModule::t('cdr', 'search'), ['class' =>
                                        'btn btn-primary btn-round-left']) ?>
                                    <?= Html::a(FraudCallDetectionReportModule::t('cdr', 'reset'), ['index', 'page' =>
                                        Yii::$app->session->get('page')],
                                        ['class' => 'btn btn-danger btn-round-right']) ?>
                                </div>
                            </div>

                            <?php ActiveForm::end(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php

$this->registerCssFile("@web/themes/assets/global/plugins/bootstrap-daterangepicker-master/daterangepicker.css", [
    'depends' => \app\assets\AppAuthAsset::className(),
    'position' => \yii\web\View::POS_END,
]);

$this->registerjsFile("@web/themes/assets/global/plugins/bootstrap-daterangepicker-master/moment.min.js", [
    'depends' => \app\assets\AppAuthAsset::className(),
    'position' => \yii\web\View::POS_END,
]);

$this->registerjsFile("@web/themes/assets/global/plugins/bootstrap-daterangepicker-master/daterangepicker.js", [
    'depends' => \app\assets\AppAuthAsset::className(),
    'position' => \yii\web\View::POS_END,
]);
