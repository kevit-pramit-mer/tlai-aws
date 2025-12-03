<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\dbbackup\models\DbBackupSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<ul class="collapsible collapsible-accordion" data-collapsible="accordion" >
    <li>
        <div class="collapsible-header"><i class="material-icons">search</i><?=Yii::t('app','search')?></div>
        <div class="collapsible-body">
            <div id="input-fields">
                <div class="db-backup-search" id="db-backup-search">
                    <?php $form = ActiveForm::begin([
                        'id' => 'db-backup-search-form',
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
                                <?= $form->field($model, 'db_name', ['options' => ['class' => 'col-xs-12 col-md-6']]) ?>
                        </div>
                        <div class="col s12 m6 l4">
                                <?= $form->field($model, 'from_db_date',
                                    ['options' => ['class' => 'col-xs-12 col-md-6']])->textInput(['class' => 'form-control drp datepicker', 'readonly' => true, 'autocomplete' => 'off', 'placeholder' => date('Y-m-d')]); ?>
                        </div>
                        <div class="col s12 m6 l4">
                                <?= $form->field($model, 'to_db_date',
                                    ['options' => ['class' => 'col-xs-12 col-md-6']])->textInput(['class' => 'form-control drp datepicker', 'readonly' => true, 'autocomplete' => 'off', 'placeholder' => date('Y-m-d')]); ?>
                        </div>
                    </div>
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

