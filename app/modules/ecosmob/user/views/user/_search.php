<?php

use app\modules\ecosmob\user\UserModule;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\user\models\UserSearch */
/* @var $form yii\widgets\ActiveForm */
/* @var $roles */
?>

<ul class="collapsible collapsible-accordion" data-collapsible="accordion" >
    <li>
        <div class="collapsible-header"><?= Yii::t('app', 'search') ?></div>
        <div class="collapsible-body">
            <div id="input-fields">

                <div class="user-search"
                     id="user-search">

                    <?php $form = ActiveForm::begin([
                        'id' => 'user-search-form',
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
                            <?= $form->field($model, 'adm_email')->textInput( [ 'placeholder' => ($model->getAttributeLabel('adm_email')) ] )  ?>
                        </div>
                        <div class="col s12 m6 l4 select-dropdown-selection">
                            <?= $form->field($model, 'adm_is_admin', ['options' => [
                                'class' => '',
                            ]])->dropDownList($roles, ['prompt' => UserModule::t('usr',
                                'select_role')])->label(UserModule::t('usr',
                                'adm_is_admin')); ?>
                        </div>
                        <div class="col s12 m6 l4">
                            <?= $form->field($model, 'adm_username')->textInput( [ 'placeholder' => ($model->getAttributeLabel('adm_username')) ] )  ?>
                        </div>
                        <div class="col s12 m6 l4">
                            <?= $form->field($model, 'adm_status', ['options' => ['class' => 'input-field']])->dropDownList
                            (['0' => Yii::t('app', 'inactive'), '1' => Yii::t('app', 'active')], ['prompt' =>
                                UserModule::t('usr',
                                    'adm_status')])
                                ->label(UserModule::t('usr', 'adm_status')); ?>
                        </div>
                        <div class="col s12 mt-1">
                            <div class="input-field">
                                <?= Html::submitButton(UserModule::t('usr', 'search'), ['class' =>
                                    'btn waves-effect waves-light amber darken-4']) ?>
                                <?= Html::a(UserModule::t('usr', 'reset'), ['index', 'page' =>
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