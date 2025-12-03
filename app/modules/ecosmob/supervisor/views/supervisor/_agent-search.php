<?php

use app\modules\ecosmob\campaign\models\Campaign;
use app\modules\ecosmob\supervisor\assets\SupervisorAsset;
use app\modules\ecosmob\supervisor\SupervisorModule;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\supervisor\models\AdminMasterSearch */
/* @var $form yii\widgets\ActiveForm */

SupervisorAsset::register($this);

$campaign = ArrayHelper::map(Campaign::find()->all(), 'cmp_name', 'cmp_name');
?>

<ul class="collapsible collapsible-accordion" data-collapsible="accordion" >
    <li>
        <div class="collapsible-header"><i class="material-icons">search</i>Search</div>
        <div class="collapsible-body">
            <div id="input-fields">

                <div class="agent-master-search"
                     id="agent-master-search">

                    <?php $form = ActiveForm::begin([
                        'id' => 'agent-master-search-form',
                        'action' => ['agent-report'],
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
                        <div class="col s6">
                            <div class="input-field">
                                <?= $form->field($model, 'from')->textInput(['class' => 'datepicker', 'readonly' => true, 'placeholder' => ($model->getAttributeLabel('from'))]) ?>
                            </div>
                        </div>
                        <div class="col s6">
                            <div class="input-field">

                                <?= $form->field($model, 'to')->textInput(['class' => 'datepicker', 'readonly' => true, 'placeholder' => ($model->getAttributeLabel('to'))]) ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s6">
                            <div class="input-field">
                                <div class="select-wrapper">
                                    <?= $form->field($model, 'campaign', ['options' => ['class' => '']])->dropDownList($campaign, ['prompt' => SupervisorModule::t('supervisor', 'Select Campaign')])->label(SupervisorModule::t('supervisor', 'campaign')); ?>
                                </div>
                            </div>
                        </div>

                        <div class="col s6">
                            <div class="input-field col s12">
                                <div class="select-wrapper">
                                    <?= $form->field($model, 'user_type', ['options' => ['class' => '']])->dropDownList(['supervisor' => 'Supervisor', 'agent' => 'Agent'], ['prompt' => SupervisorModule::t('supervisor', 'Select User Type')])->label(SupervisorModule::t('supervisor', 'User')); ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field center">
                            <?= Html::submitButton(SupervisorModule::t('supervisor', 'search'), ['class' =>
                                'btn waves-effect waves-light amber darken-4']) ?>
                            <?= Html::a(SupervisorModule::t('supervisor', 'reset'), ['agent-report', 'page' =>
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
<script>
    $(document).ready(function () {
        $('.datepicker').datepicker();
    });
</script>