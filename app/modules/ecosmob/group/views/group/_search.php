<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\ecosmob\group\GroupModule;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\group\models\GroupSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<ul class="collapsible collapsible-accordion" data-collapsible="accordion" >
    <li>
        <div class="collapsible-header"><?= Yii::t('app', 'search') ?></div>
        <div class="collapsible-body">
            <div id="input-fields">

                <div class="group-search"
                     id="group-search">

                    <?php $form = ActiveForm::begin([
                        'id' => 'group-search-form',
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
                            <?= $form->field($model, 'grp_name')->textInput(['maxlength' => 50, 'placeholder' => ($model->getAttributeLabel('grp_name'))]) ?>
                        </div>
                        <div class="col s12 mt-1">
                            <div class="input-field">
                                <?= Html::submitButton(GroupModule::t('group', 'search'),
                                    [
                                        'class' =>
                                            'btn waves-effect waves-light amber darken-4',
                                    ]) ?>
                                <?= Html::a(GroupModule::t('group', 'reset'),
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
