<?php

use app\modules\ecosmob\voicemsg\VoiceMsgModule;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\voicemsg\models\VoicemailMsgsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<ul class="collapsible collapsible-accordion" data-collapsible="accordion" >
    <li>
        <div class="collapsible-header"><?= Yii::t('app', 'search') ?></div>
        <div class="collapsible-body">
            <div id="input-fields">
                <div class="voicemail-msgs-search"
                     id="voicemail-msgs-search">
                    <?php $form = ActiveForm::begin([
                        'id' => 'voicemail-msgs-search-form',
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
                            <div class="input-field">
                                <?= $form->field($model, 'cid_name')->textInput(['placeholder' => $model->getAttributeLabel('cid_name')]); ?>
                            </div>
                        </div>
                        <div class="col s12 m6 l4">
                            <div class="input-field">
                                <?= $form->field($model, 'cid_number')->textInput(['placeholder' => $model->getAttributeLabel('cid_number')]); ?>
                            </div>
                        </div>
                        <div class="col s12 m6 l4">
                            <div class="input-field">
                                <?= $form->field($model, 'username')->textInput(['placeholder' => $model->getAttributeLabel('username')]); ?>
                            </div>
                        </div>
                        <div class="col s12 m6 l4">
                            <div class="input-field">
                                <?= $form->field($model, 'message_len')->textInput(['placeholder' => $model->getAttributeLabel('message_len')]); ?>

                            </div>
                        </div>
                        <div class="col s12 m6 l4">
                            <div class="input-field">
                                <div class="select-wrapper">
                                    <?= $form->field($model, 'read_epoch', ['options' => ['class' => '']])->dropDownList(['2' => VoiceMsgModule::t
                                    ('voicemsg', 'all'), '1' => VoiceMsgModule::t
                                    ('voicemsg', 'read'), '0' => VoiceMsgModule::t
                                    ('voicemsg', 'unread'),])->label(VoiceMsgModule::t('voicemsg', 'status')); ?>
                                </div>
                            </div>
                        </div>
                        <div class="col s12 mt-1">
                            <?= Html::submitButton(VoiceMsgModule::t('voicemsg', 'search'), ['class' =>
                                'btn waves-effect waves-light amber darken-4']) ?>
                            <?= Html::a(VoiceMsgModule::t('voicemsg', 'reset'), ['index', 'page' =>
                                Yii::$app->session->get('page')],
                                ['class' => 'btn waves-effect waves-light bg-gray-200 ml-1']) ?>
                        </div>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </li>
</ul>
