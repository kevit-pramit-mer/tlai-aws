<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\ecosmob\services\ServicesModule;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\services\models\ServicesSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<ul class="collapsible collapsible-accordion" data-collapsible="accordion" >
    <li>
        <div class="collapsible-header"><i class="material-icons">search</i>Search</div>
        <div class="collapsible-body">
            <div id="input-fields">

                <div class="services-search"
                     id="services-search">

                    <?php $form = ActiveForm::begin([
                    'id' => 'services-search-form',
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
                                <div class="col s6">
                                    <div class="input-field col s12">
                                            <?= $form->field($model, 'ser_id') ?>

                                    </div>
                                </div>
                            </div>
                                                    <div class="row">
                                <div class="col s6">
                                    <div class="input-field col s12">
                                            <?= $form->field($model, 'ser_name') ?>

                                    </div>
                                </div>
                            </div>
                                            <div class="row">
                        <div class="form-group col-sm-offset-5 col-md-offset-5 col-xs-offset-2">
                            <?= Html::submitButton(ServicesModule::t('services', 'search'), ['class' =>
                            'btn waves-effect waves-light amber darken-4']) ?>
                            <?= Html::a(ServicesModule::t('services', 'reset'), ['index', 'page' =>
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
