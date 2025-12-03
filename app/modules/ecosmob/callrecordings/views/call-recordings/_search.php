<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\ecosmob\callrecordings\CallRecordingsModule;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\callrecordings\models\CallRecordingsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<ul class="collapsible collapsible-accordion" data-collapsible="accordion" >
    <li>
        <div class="collapsible-header"><i class="material-icons">search</i>Search</div>
        <div class="collapsible-body">
            <div id="input-fields">

                <div class="call-recordings-search"
                     id="call-recordings-search">

                    <?php $form = ActiveForm::begin([
                        'id' => 'call-recordings-search-form',
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
                        <div class="col s12">
                                <?= $form->field($model, 'cr_date') ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="input-field center">
                            <?= Html::submitButton(CallRecordingsModule::t('app', 'search'), [
                                'class' =>
                                    'btn waves-effect waves-light amber darken-4'
                            ]) ?>
                            <?= Html::a(CallRecordingsModule::t('app', 'reset'), [
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
