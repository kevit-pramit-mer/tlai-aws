<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\ecosmob\campaign\models\Campaign;
use app\modules\ecosmob\realtimedashboard\RealTimeDashboardModule;

/* @var $this yii\web\View */
/* @var $model \app\modules\ecosmob\realtimedashboard\models\CampaignPerformance */
/* @var $form yii\widgets\ActiveForm */

$campaign = ArrayHelper::map(Campaign::find()->select(['cmp_id', 'cmp_name'])->where(['cmp_status' => 'Active'])->all(), 'cmp_id', 'cmp_name');

?>

<ul class="collapsible collapsible-accordion mb-0" data-collapsible="accordion" style="margin-top: -10px">
    <li class="active">
        <div class="collapsible-header"><?= Yii::t('app', 'search') ?></div>
        <div class="collapsible-body">
            <div id="input-fields">
                <?php $form = ActiveForm::begin([
                    'id' => 'campaign-performance-report-search-form',
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

                <div class="campaign-performance-report-search" id="campaign-performance-report-search">
                    <div class="row ">
                        <div class="col s6 ">
                            <div class="select-wrapper align-left" >
                                <?= $form->field($model, 'cmp_id', ['options' => ['class' => 'input-field']])->dropDownList($campaign, ['prompt' => 'Select Campaign Name'])->label('Campaign Name'); ?>
                            </div>
                        </div>
                        <div class="col s12 ">
                            <div class="input-field align-left">
                                <?= Html::submitButton(RealTimeDashboardModule::t('realtimedashboard', 'search'), ['class' =>
                                    'btn waves-effect waves-light amber darken-4']) ?>
                                <?= Html::a(RealTimeDashboardModule::t('realtimedashboard', 'reset'), ['index', 'page' =>
                                    Yii::$app->session->get('page')],
                                    ['class' => 'btn waves-effect waves-light bg-gray-200 ml-1']) ?>
                            </div>
                        </div>

                    </div>
                </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </li>
</ul>
