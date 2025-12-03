<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\ecosmob\crm\CrmModule;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\crm\models\LeadGroupMemberSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<ul class="collapsible collapsible-accordion" data-collapsible="accordion" >
    <li>
        <div class="collapsible-header"><i class="material-icons">search</i>Search</div>
        <div class="collapsible-body">
            <div id="input-fields">

                <div class="lead-group-member-search"
                     id="lead-group-member-search">

                    <?php $form = ActiveForm::begin([
                    'id' => 'lead-group-member-search-form',
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
                                    <div class="input-field">
                                            <?= $form->field($model, 'lg_id') ?>

                                    </div>
                                </div>
                                <div class="col s12 m6 l4">
                                    <div class="input-field">
                                            <?= $form->field($model, 'ld_id') ?>

                                    </div>
                                </div>
                                <div class="col s12 m6 l4">
                                    <div class="input-field">
                                            <?= $form->field($model, 'lg_first_name') ?>

                                    </div>
                                </div>
                                <div class="col s12 m6 l4">
                                    <div class="input-field">
                                            <?= $form->field($model, 'lg_last_name') ?>

                                    </div>
                                </div>
                                <div class="col s12 m6 l4">
                                    <div class="input-field">
                                            <?= $form->field($model, 'lg_contact_number') ?>

                                    </div>
                                </div>
                                <div class="col s12 mt-1">
                                    <div class="input-field">
                                        <?= Html::submitButton(CrmModule::t('crm', 'search'), ['class' =>
                                        'btn waves-effect waves-light amber darken-4']) ?>
                                        <?= Html::a(CrmModule::t('crm', 'reset'), ['index', 'page' =>
                                        Yii::$app->session->get('page')],
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
