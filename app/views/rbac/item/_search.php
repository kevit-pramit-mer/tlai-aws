<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $model yii2mod\rbac\models\search\AuthItemSearch */

?>
<ul class="collapsible collapsible-accordion" data-collapsible="accordion">
    <li>
        <div class="collapsible-header"><?= Yii::t('app', 'search') ?></div>
        <div class="collapsible-body">
            <div id="input-fields">
                <div class="shift-search"
                     id="shift-search">
                    <?php $form = ActiveForm::begin([
                        'id' => 'assignment-search-form',
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
                        <div class="col s12 m6">
                            <?= $form->field($model, 'name')->textInput(['placeholder' => Yii::t('app', 'name')])->label(Yii::t('app', 'name')) ?>
                        </div>
                        <div class="col s12 m6">
                            <?= $form->field($model, 'description')->textInput(['placeholder' => Yii::t('app', 'description')])->label(Yii::t('app', 'description')) ?>
                        </div>
                        <div class="col s12 mt-1">
                            <div class="input-field ">

                                <?= Html::submitButton(Yii::t('app', 'search'), [
                                    'class' => 'btn waves-effect waves-light amber darken-4',
                                    'id' => 'search_referesh'
                                ]) ?>
                                <?= Html::a(Yii::t('app', 'reset'), [
                                    'index',
                                    'page' => Yii::$app->session->get('page')
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
