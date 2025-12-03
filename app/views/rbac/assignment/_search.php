<?php
/**
 * Created by PhpStorm.
 * User: akshay
 * Date: 29/9/18
 * Time: 5:52 PM
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<ul class="collapsible collapsible-accordion" data-collapsible="accordion" >
    <li>
        <div class="collapsible-header"><i class="material-icons">search</i><?= Yii::t('app','search') ?></div>
        <div class="collapsible-body">
            <div id="input-fields">

                <?php $form = ActiveForm::begin(
                    [
                        'id' => 'assignment-search-form',
                        'action' => ['index'],
                        'class' => 'row',
                        'method' => 'get',
                        'options' => [
                            'data-pjax' => 1,
                        ],
                        'fieldConfig' => [
                            'options' => [
                                'class' => 'input-field col s12'
                            ],
                        ],
                    ]); ?>
                <div class="row">
                    <div class="col s12">
                        <div class="input-field col s12">

                            <?= $form->field($model, 'username',
                                ['options' => ['class' => 'col s12 ']])->textInput([
                                    'placeholder' => ($model->getAttributeLabel('username'))
                            ]) ?>

                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field center">
                            <?= Html::submitButton(
                                Yii::t( 'app', 'search' ),
                                [
                                    'class' => 'btn waves-effect waves-light amber darken-4',
                                    'id' => 'search_referesh',
                                ]) ?>


                            <?= Html::a(
                                Yii::t( 'app', 'reset' ),
                                [
                                    'index',
                                    'page' => Yii::$app->session->get( 'page' ),
                                ],
                                [ 'class' => 'btn waves-effect waves-light bg-gray-200 ml-1' ] ) ?>

                        </div>
                    </div>
                    <?php ActiveForm::end(); ?>

                </div>
            </div>
    </li>
</ul>
