<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\redialcall\ReDialCallSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<ul class="collapsible collapsible-accordion" data-collapsible="accordion" >
    <li>
        <div class="collapsible-header"><?= Yii::t('app', 'search') ?></div>
        <div class="collapsible-body">
            <div id="input-fields">
                <div class="re-dial-call-search"
                     id="re-dial-call-search">
                    <?php $form = ActiveForm::begin([
                        'id' => 're-dial-call-search-form',
                        'action' => ['index'],
                        'method' => 'get',
                        'options' => [
                            'data-pjax' => 1
                        ],
                        'fieldConfig' => [
                            'options' => [
                                'class' => 'input-field col s12'
                            ],
                        ],
                    ]); ?>
                    <div class="row">
                        <div class="col s12 m6">
                            <div class="input-field col s12">
                                <?= $form->field($model, 'ld_id') ?>

                            </div>
                        </div>
                        <div class="col s12 m6">
                            <div class="input-field col s12">
                                <?= $form->field($model, 'ld_group_name') ?>

                            </div>
                        </div>
                        <div class="col s12 mt-1">
                            <div class="input-field">
                                <?= Html::submitButton(Yii::t('app', 'Search'), ['class' =>
                                    'btn waves-effect waves-light amber darken-4']) ?>
                                <?= Html::a(Yii::t('app', 'Reset'), ['index', 'page' =>
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
