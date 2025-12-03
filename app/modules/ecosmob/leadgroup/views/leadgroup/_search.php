<?php

use app\modules\ecosmob\leadgroup\LeadgroupModule;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\leadgroup\models\LeadgroupSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<ul class="collapsible collapsible-accordion" data-collapsible="accordion" >
    <li>
        <div class="collapsible-header"><?= Yii::t('app', 'search') ?></div>
        <div class="collapsible-body">
            <div id="input-fields">
                <div class="leadgroup-master-search" id="leadgroup-master-search">
                    <?php $form = ActiveForm::begin([
                        'id' => 'leadgroup-master-search-form',
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
                            <div class="select-wrapper">
                                <?= $form->field($model, 'ld_group_name')->textInput(['placeholder' => ($model->getAttributeLabel('ld_group_name'))]) ?>
                            </div>
                        </div>
                        <div class="col s12 mt-1">
                            <div class="input-field">
                                <?= Html::submitButton(LeadgroupModule::t('leadgroup', 'search'), [
                                    'class' =>
                                        'btn waves-effect waves-light amber darken-4'
                                ]) ?>
                                <?= Html::a(LeadgroupModule::t('leadgroup', 'reset'), [
                                    'index',
                                    'page' =>
                                        Yii::$app->session->get('page')
                                ],
                                    ['class' => 'btn waves-effect waves-light bg-gray-200 ml-1']) ?>
                            </div>
                        </div>
                        <?php ActiveForm::end(); ?>
                    </div>
            </div>
        </div>
    </li>
</ul>
