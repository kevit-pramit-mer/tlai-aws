<?php

use app\modules\ecosmob\campaign\models\Campaign;
use app\modules\ecosmob\campaign\models\CampaignMappingUser;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\queuecallback\models\QueueCallbackSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<ul class="collapsible collapsible-accordion" data-collapsible="accordion" >
    <li>
        <div class="collapsible-header"><i class="material-icons">search</i><?= Yii::t('app', 'search') ?></div>
        <div class="collapsible-body">
            <div id="input-fields">

                <div class="queue-callback-search"
                     id="queue-callback-search">

                    <?php $form = ActiveForm::begin([
                        'id' => 'queue-callback-search-form',
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
                                <?= $form->field($model, 'queue_name') ?>

                            </div>
                        </div>
                        <div class="col s12 m6 l4">
                            <div class="input-field">
                                <?= $form->field($model, 'phone_number') ?>

                            </div>
                        </div>
                        <div class="col s12 m6 l4">
                            <div class="input-field">
                                <?= $form->field($model, 'date')->textInput(['class' => 'form-control date-picker', 'readonly' => true, 'autocomplete' => 'off'])->label(Yii::t('app', 'date')) ?>

                            </div>
                        </div>
                    </div>
                    <!--  <div class="row">
                        <div class="col s12 m6 l4">
                            <div class="input-field">
                                <? /*= $form->field($model, 'created_at') */ ?>

                            </div>
                        </div>
                    </div>-->
                    <div class="row">
                        <div class="input-field center">
                            <?= Html::submitButton(Yii::t('app', 'search'), ['class' =>
                                'btn waves-effect waves-light amber darken-4']) ?>
                            <?= Html::a(Yii::t('app', 'reset'), ['index', 'page' =>
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
