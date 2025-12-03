<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\ecosmob\pcap\PcapModule;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\pcap\models\Pcap */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
    <div class="col s12">
        <div id="input-fields" class="card card-tabs">
            <div class="card-content">
                <div class="pcap-form"
                     id="pcap-form">
                    <?php $form = ActiveForm::begin([
                        'class' => 'row',
                        'fieldConfig' => [
                            'options' => [
                                'class' => 'input-field '
                            ],
                        ],
                    ]); ?>

                    <div class="row">
                        <div class="col s6">
                            <div class="input-field col s12">
                                <?= $form->field($model, 'ct_start')->textInput(['maxlength' => true, 'placeholder' => ($model->getAttributeLabel('ct_start'))])->label($model->getAttributeLabel('ct_start')); ?>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s6">
                            <div class="input-field col s12">
                                <?= $form->field($model, 'ct_stop')->textInput(['maxlength' => true, 'placeholder' => ($model->getAttributeLabel('ct_stop'))])->label($model->getAttributeLabel('ct_stop')); ?>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s6">
                            <div class="input-field col s12">
                                <?= $form->field($model, 'ct_filename')->textInput(['maxlength' => true, 'placeholder' => ($model->getAttributeLabel('ct_filename'))])->label($model->getAttributeLabel('ct_filename')); ?>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s6">
                            <div class="input-field col s12">
                                <?= $form->field($model, 'ct_url')->textInput(['maxlength' => true, 'placeholder' => ($model->getAttributeLabel('ct_url'))])->label($model->getAttributeLabel('ct_url')); ?>

                            </div>
                        </div>
                    </div>
                    <div class="hseparator"></div>
                    <div class="col s12 ">
                        <div class="input-field col s12">
                            <?= Html::a(PcapModule::t('pcap', 'cancel'), ['index', 'page' => Yii::$app->session->get('page')],
                                ['class' => 'btn waves-effect waves-light bg-gray-200']) ?>
                            <?= Html::submitButton($model->isNewRecord ? PcapModule::t('pcap', 'create') : PcapModule::t('pcap', 'update'), ['class' => $model->isNewRecord ? 'btn waves-effect waves-light amber darken-4' :
                                'btn waves-effect waves-light cyan accent-8']) ?>
                            <?php if (!$model->isNewRecord) { ?>
                                <?= Html::submitButton(PcapModule::t('pcap', 'apply'), [
                                    'class' => 'btn waves-effect waves-light amber darken-4 ml-2',
                                    'name' => 'apply',
                                    'value' => 'update']) ?>
                            <?php } ?>
                        </div>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>

            </div>
        </div>
    </div>
</div>
