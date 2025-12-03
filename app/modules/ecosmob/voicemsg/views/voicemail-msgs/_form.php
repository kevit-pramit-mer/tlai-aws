<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\voicemsg\models\VoicemailMsgs */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row">
    <div class="col s12">
        <div id="input-fields" class="card card-tabs">
            <div class="card-content">
                <div class="voicemail-msgs-form"
                     id="voicemail-msgs-form">
                    <?php $form = ActiveForm::begin([
                        'class' => 'row',
                        'fieldConfig' => [
                            'options' => [
                                'class' => 'input-field col s12'
                            ],
                        ],
                    ]); ?>
                    <div class="row">
                        <div class="col s6">
                            <div class="input-field col s12">
                                <?= $form->field($model, 'created_epoch')->textInput(['maxlength' => true])->label($model->getAttributeLabel('created_epoch')); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s6">
                            <div class="input-field col s12">
                                <?= $form->field($model, 'read_epoch')->textInput(['maxlength' => true])->label($model->getAttributeLabel('read_epoch')); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s6">
                            <div class="input-field col s12">
                                <?= $form->field($model, 'trash_epoch')->textInput(['maxlength' => true])->label($model->getAttributeLabel('trash_epoch')); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s6">
                            <div class="input-field col s12">
                                <?= $form->field($model, 'username')->textInput(['maxlength' => true])->label($model->getAttributeLabel('username')); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s6">
                            <div class="input-field col s12">
                                <?= $form->field($model, 'sip_id')->textInput(['maxlength' => true])->label($model->getAttributeLabel('sip_id')); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s6">
                            <div class="input-field col s12">
                                <?= $form->field($model, 'domain')->textInput(['maxlength' => true])->label($model->getAttributeLabel('domain')); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s6">
                            <div class="input-field col s12">
                                <?= $form->field($model, 'uuid')->textInput(['maxlength' => true])->label($model->getAttributeLabel('uuid')); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s6">
                            <div class="input-field col s12">
                                <?= $form->field($model, 'cid_name')->textInput(['maxlength' => true])->label($model->getAttributeLabel('cid_name')); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s6">
                            <div class="input-field col s12">
                                <?= $form->field($model, 'cid_number')->textInput(['maxlength' => true])->label($model->getAttributeLabel('cid_number')); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s6">
                            <div class="input-field col s12">
                                <?= $form->field($model, 'in_folder')->textInput(['maxlength' => true])->label($model->getAttributeLabel('in_folder')); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s6">
                            <div class="input-field col s12">
                                <?= $form->field($model, 'file_path')->textInput(['maxlength' => true])->label($model->getAttributeLabel('file_path')); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s6">
                            <div class="input-field col s12">
                                <?= $form->field($model, 'message_len')->textInput(['maxlength' => true])->label($model->getAttributeLabel('message_len')); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s6">
                            <div class="input-field col s12">
                                <?= $form->field($model, 'flags')->textInput(['maxlength' => true])->label($model->getAttributeLabel('flags')); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s6">
                            <div class="input-field col s12">
                                <?= $form->field($model, 'read_flags')->textInput(['maxlength' => true])->label($model->getAttributeLabel('read_flags')); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s6">
                            <div class="input-field col s12">
                                <?= $form->field($model, 'forwarded_by')->textInput(['maxlength' => true])->label($model->getAttributeLabel('forwarded_by')); ?>
                            </div>
                        </div>
                    </div>
                    <div class="hseparator"></div>
                    <div class="col s12 center">
                        <div class="input-field col s12">
                            <?= Html::submitButton($model->isNewRecord ? Yii::t('app', 'Create') : Yii::t('app', 'Update'), ['class' => $model->isNewRecord ? 'btn waves-effect waves-light amber darken-4' :
                                'btn waves-effect waves-light cyan accent-8']) ?>
                            <?php if (!$model->isNewRecord) { ?>
                                <?= Html::submitButton(Yii::t('app', 'apply'), [
                                    'class' => 'btn waves-effect waves-light amber darken-4',
                                    'name' => 'apply',
                                    'value' => 'update']) ?>
                            <?php } ?>
                            <?= Html::a(Yii::t('app', 'cancel'), ['index', 'page' => Yii::$app->session->get('page')],
                                ['class' => 'btn waves-effect waves-light bg-gray-200 ml-2']) ?>
                        </div>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
