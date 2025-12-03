<?php

use app\modules\ecosmob\iptable\IpTableModule;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\iptable\models\IpTableSearch */
/* @var $form yii\widgets\ActiveForm */
$permissions = $GLOBALS['permissions'];

?>

<ul class="collapsible collapsible-accordion" data-collapsible="accordion" >
    <li>
        <div class="collapsible-header"><?php echo Yii::t('app', 'search'); ?></div>
        <div class="collapsible-body">
            <div id="input-fields">
                <div class="ip-table-search" id="ip-table-search">
                    <?php $form = ActiveForm::begin([
                        'id' => 'ip-table-search-form',
                        'action' => ['index'],
                        'method' => 'get',
                        'options' => [
                            'data-pjax' => 1,
                        ],
                        'fieldConfig' => [
                            'options' => [
                                'class' => 'input-field ',
                            ],
                        ],
                    ]); ?>

                    <div class="row">
                        <div class="col s12 m6 l4">
                            <div class="input-field ">
                                <?= $form->field($model, 'it_source')->textInput(['maxlength' => TRUE, 'placeholder' => ($model->getAttributeLabel('it_source'))]) ?>
                            </div>
                        </div>
                        <div class="col s12 m6 l4">
                            <div class="input-field ">
                                <?= $form->field($model, 'it_destination')->textInput(['maxlength' => TRUE, 'placeholder' => ($model->getAttributeLabel('it_destination'))]) ?>
                            </div>
                        </div>
                        <div class="col s12 m6 l4">
                            <div class="input-field ">
                                <?= $form->field($model, 'it_port')->textInput(['maxlength' => TRUE, 'placeholder' => ($model->getAttributeLabel('it_port'))]) ?>
                            </div>
                        </div>
                        <div class="col s12 m6 l4">
                            <div class="input-field ">
                                <div class="select-wrapper">
                                    <?= $form->field($model, 'it_direction', ['options' => ['class' => '']])
                                        ->dropDownList(['Inbound' => IpTableModule::t('it', 'inbound'), 'Outbound' => IpTableModule::t('it', 'outbound'),],
                                            ['prompt' => Yii::t('app', 'select')]) ?>
                                </div>
                            </div>
                        </div>
                        <div class="col s12 m6 l4">
                            <div class="input-field ">
                                <div class="select-wrapper">
                                    <?= $form->field($model, 'it_action', ['options' => ['class' => '']])->dropDownList([
                                        'Reject' => IpTableModule::t('it', 'reject'),
                                        'Accept' => IpTableModule::t('it', 'accept'),
                                    ],
                                        ['prompt' => Yii::t('app', 'select')]) ?>
                                </div>
                            </div>
                        </div>
                        <div class="col s12 m6 l4">
                            <div class="input-field ">
                                <div class="select-wrapper">
                                    <?= $form->field($model, 'it_protocol', ['options' => ['class' => '']])->dropDownList([
                                        'TCP' => 'TCP',
                                        'UDP' => 'UDP',
                                        'ANY' => 'ANY',
                                    ],
                                        ['prompt' => Yii::t('app', 'select')]) ?>
                                </div>
                            </div>
                        </div>
                        <div class="col s12 mt-1">
                            <div class="input-field ">
                                <?= Html::submitButton(IpTableModule::t('it', 'search'),
                                    [
                                        'class' =>
                                            'btn waves-effect waves-light amber darken-4',
                                    ]) ?>
                                <?= Html::a(IpTableModule::t('it', 'reset'),
                                    [
                                        'index',
                                        'page' =>
                                            Yii::$app->session->get('page'),
                                    ],
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
