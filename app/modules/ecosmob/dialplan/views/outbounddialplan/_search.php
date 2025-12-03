<?php

use app\modules\ecosmob\carriertrunk\models\TrunkGroup;
use app\modules\ecosmob\dialplan\DialPlanModule;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\dialplan\models\OutboundDialPlansDetailsSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<ul class="collapsible collapsible-accordion" data-collapsible="accordion" >
    <li>
        <div class="collapsible-header"><?= Yii::t('app', 'search') ?></div>
        <div class="collapsible-body">
            <div id="input-fields">

                <div class="outbound-dial-plans-details-search"
                     id="outbound-dial-plans-details-search">

                    <?php $form = ActiveForm::begin([
                        'id' => 'outbound-dial-plans-details-search-form',
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
                            <?= $form->field($model, 'odpd_prefix_match_string')->textInput([ 'placeholder' => ($model->getAttributeLabel('odpd_prefix_match_string'))]); ?>
                        </div>
                        <div class="col s12 m6">
                            <div class="select-wrapper">
                                <?= $form->field($model, 'trunk_grp_id', ['options' => ['class' => '']])
                                    ->dropDownList(TrunkGroup::getAllTrunkGroups(), ['prompt' => DialPlanModule::t('dp', 'select')])
                                    ->label($model->getAttributeLabel('trunk_grp_id')); ?>
                            </div>
                        </div>
                        <div class="col s12 mt-1">
                            <div class="input-field">
                                <?= Html::submitButton(DialPlanModule::t('dp', 'search'),
                                    [
                                        'class' =>
                                            'btn waves-effect waves-light amber darken-4',
                                    ]) ?>
                                <?= Html::a(DialPlanModule::t('dp', 'reset'),
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
