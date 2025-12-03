<?php

use app\modules\ecosmob\fail2ban\Fail2banModule;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\iptable\models\IpTableSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<ul class="collapsible collapsible-accordion" data-collapsible="accordion" >
    <li>
        <div class="collapsible-header"><?php echo Yii::t('app', 'search'); ?></div>
        <div class="collapsible-body">
            <div id="input-fields">

                <div class="ip-table-search"
                     id="ip-table-search">

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
                            <?= $form->field($model, 'bw_rule_value')->textInput(['maxlength' => TRUE, 'placeholder' => ($model->getAttributeLabel('bw_rule_value'))]) ?>
                        </div>
                        <div class="col s12 m6 l4">
                            <?= $form->field($model, 'ports')->textInput(['maxlength' => TRUE, 'placeholder' => ($model->getAttributeLabel('ports'))]) ?>
                        </div>
                        <div class="col s12 m6 l4">
                            <?= $form->field($model, 'protocol')->textInput(['maxlength' => TRUE, 'placeholder' => ($model->getAttributeLabel('protocol'))]) ?>
                        </div>
                        <div class="col s12 mt-1">
                            <div class="input-field">
                                <?= Html::submitButton(Fail2banModule::t('fail2ban', 'search'),
                                [
                                    'class' =>
                                        'btn waves-effect waves-light amber darken-4',
                                ]) ?>
                                <?= Html::a(Fail2banModule::t('fail2ban', 'reset'),
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
