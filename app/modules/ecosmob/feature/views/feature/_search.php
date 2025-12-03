<?php

use app\modules\ecosmob\feature\FeatureModule;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\customerdetails\models\CampaignMappingUserSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<ul class="collapsible collapsible-accordion" data-collapsible="accordion" >
    <li>
        <div class="collapsible-header">Search</div>
        <div class="collapsible-body">
            <div id="input-fields">

                <div class="campaign-mapping-user-search"
                     id="campaign-mapping-user-search">

                    <?php $form = ActiveForm::begin([
                        'id' => 'feature-search-form',
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
                        <div class="col s6">
                            <div class="input-field">
                                <?= $form->field($model, 'feature_name')->textInput(['maxlength' => true, 'class' => '','placeholder' => FeatureModule::t('feature', 'sts_code_name')]); ?>
                            </div>
                        </div>
                        <div class="col s6">
                            <div class="input-field">
                                <?= $form->field($model, 'feature_code')->textInput(['maxlength' => true, 'class' => '','placeholder' => FeatureModule::t('feature', 'sts_code')]); ?>
                            </div>
                        </div>
                        <div class="col s12 mt-1">
                            <div class="input-field">
                                <?= Html::submitButton(FeatureModule::t('feature', 'search'), ['class' =>
                                    'btn waves-effect waves-light amber darken-4']) ?>
                                <?= Html::a(FeatureModule::t('feature', 'reset'), ['index', 'page' =>
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
