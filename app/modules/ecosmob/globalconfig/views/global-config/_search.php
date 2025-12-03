<?php

use app\modules\ecosmob\globalconfig\GlobalConfigModule;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\globalconfig\models\GlobalConfigSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<ul class="collapsible collapsible-accordion" data-collapsible="accordion" >
    <li>
        <div class="collapsible-header"><?= Yii::t('app', 'search') ?></div>
        <div class="collapsible-body">
            <div id="input-fields">
                <div class="global-config-search"  id="global-config-search">

                    <?php $form = ActiveForm::begin([
                        'id' => 'global-config-search-form',
                        'action' => ['index'],
                        'method' => 'get',
                        'options' => [
                            'data-pjax' => 1,
                        ],
                        'fieldConfig' => [
                            'options' => [
                                'class' => 'input-field',
                            ],
                        ],
                    ]); ?>

                    <div class="row">
                        <div class="col s12 m6">
                            <?= $form->field($model, 'gwc_key', ['options' => ['class' => 'input-field']])->textInput( [ 'placeholder' => ($model->getAttributeLabel('gwc_key')) ] ) ?>
                        </div>
                        <div class="col s12 m6">
                            <?= $form->field($model, 'gwc_value', ['options' => ['class' => 'input-field']])->textInput( [ 'placeholder' => ($model->getAttributeLabel('gwc_value')) ] ) ?>
                        </div>
                        <div class="col s12 mt-1">
                            <div class="input-field">
                                <?= Html::submitButton(GlobalConfigModule::t('gc', 'search'),
                                    [
                                        'class' =>
                                            'btn waves-effect waves-light amber darken-4',
                                    ]) ?>
                                <?= Html::a(GlobalConfigModule::t('gc', 'reset'),
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
