<?php

use app\modules\ecosmob\accessrestriction\AccessRestrictionModule;
use app\modules\ecosmob\accessrestriction\models\AccessRestriction;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\accessrestriction\models\AccessRestrictionSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<ul class="collapsible collapsible-accordion" data-collapsible="accordion" >
    <li>
        <div class="collapsible-header"><?= Yii::t('app', 'search') ?></div>
        <div class="collapsible-body">
            <div id="input-fields">
                <div class="access-restriction-search" id="access-restriction-search">
                    <?php $form = ActiveForm::begin([
                        'id' => 'access-restriction-search-form',
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
                            <?= $form->field($model, 'ar_ipaddress')->textInput( [ 'placeholder' => ($model->getAttributeLabel('ar_ipaddress')) ] ) ?>
                        </div>
                        <div class="col s12 m6 l4">
                            <?= $form->field($model, 'ar_maskbit')->textInput( [ 'placeholder' => ($model->getAttributeLabel('ar_maskbit')) ] ) ?>
                        </div>
                        <div class="col s12 m6 l4">
                            <?= $form->field($model, 'ar_status',
                                ['options' => ['class' => 'input-field']])->dropDownList(AccessRestriction::status_list(), [
                                'prompt' => AccessRestrictionModule::t('accessrestriction', 'select')
                            ])->label(AccessRestrictionModule::t('accessrestriction', 'ar_status')); ?>
                        </div>
                        <div class="col s12 mt-1">
                            <div class="input-field">
                                <?= Html::submitButton(AccessRestrictionModule::t('accessrestriction', 'search'), [
                                    'class' =>
                                        'btn waves-effect waves-light amber darken-4'
                                ]) ?>
                                <?= Html::a(AccessRestrictionModule::t('accessrestriction', 'reset'), [
                                    'index',
                                    'page' =>
                                        Yii::$app->session->get('page')
                                ],
                                    ['class' => 'btn waves-effect waves-light bg-gray-200 ml-1']) ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </li>
</ul>
