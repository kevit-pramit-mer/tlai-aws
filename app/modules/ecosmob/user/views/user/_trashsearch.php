<?php

use app\modules\ecosmob\user\UserModule;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\user\models\UserSearch */
/* @var $form yii\widgets\ActiveForm */
/* @var $roles */
?>

<ul class="collapsible collapsible-accordion" data-collapsible="accordion">
    <li>
        <div class="collapsible-header"><i class="material-icons">search</i><?= Yii::t('app', 'search') ?></div>
        <div class="collapsible-body">
            <div id="input-fields">

                <div class="user-trash-search"
                     id="user-trash-search">

                    <?php $form = ActiveForm::begin([
                        'id' => 'user-trash-search-form',
                        'action' => ['trashed'],
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
                                <?= $form->field($model, 'adm_email') ?>

                            </div>
                        </div>
                        <div class="col s6">
                            <div class="input-field col s12">
                                <?= $form->field($model, 'adm_is_admin', ['options' => [
                                    'class' => '',
                                ]])->dropDownList($roles, ['prompt' => UserModule::t('usr',
                                    'select_role')])->label(UserModule::t('usr',
                                    'adm_is_admin')); ?>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col s6">
                            <div class="input-field col s12">
                                <?= $form->field($model, 'adm_username') ?>

                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field center">
                            <?= Html::submitButton(UserModule::t('usr', 'search'), ['class' =>
                                'btn waves-effect waves-light amber darken-4']) ?>
                            <?= Html::a(UserModule::t('usr', 'reset'), ['trashed', 'page' =>
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
