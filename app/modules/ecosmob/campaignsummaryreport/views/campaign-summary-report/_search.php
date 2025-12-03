<?php

use app\modules\ecosmob\campaign\models\Campaign;
use app\modules\ecosmob\campaignsummaryreport\CampaignSummaryReportModule;
use app\modules\ecosmob\campaignsummaryreport\models\CampaignSearch;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model CampaignSearch */
/* @var $form yii\widgets\ActiveForm */
$campaignList = [];
$campaignList = Campaign::find()->where(['cmp_status' => 'Active'])->all();
$campaign = ArrayHelper::map($campaignList, 'cmp_id', 'cmp_name');
?>

<ul class="collapsible collapsible-accordion" data-collapsible="accordion" >
    <li>
        <div class="collapsible-header"><?= Yii::t('app', 'search') ?></div>
        <div class="collapsible-body">
            <div id="input-fields">
                <div class="campaign-summary-report-search"
                     id="campaign-summary-report-search">
                    <?php $form = ActiveForm::begin([
                        'id' => 'campaign-summary-report-search-form',
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
                            <div class="select-wrapper ">
                                <?= $form->field($model, 'campaign', ['options' => ['class' => 'input-field']])->dropDownList($campaign, ['prompt' => CampaignSummaryReportModule::t('campaignsummaryreport', 'prompt_camp')])->label(CampaignSummaryReportModule::t('campaignsummaryreport', 'campaign')); ?>
                            </div>
                        </div>
                        <div class="col s12 m6 l4 calender-icon">
                            <?= $form->field($model, 'from', ['options' => ['class' => 'input-field']])->textInput(['class' => 'form-control from-date-range', 'readonly' => true, 'autocomplete' => 'off', 'placeholder' => ($model->getAttributeLabel('from'))])->label(CampaignSummaryReportModule::t('campaignsummaryreport', 'from_date')) ?>
                        </div>
                        <div class="col s12 m6 l4 calender-icon">
                            <?= $form->field($model, 'to', ['options' => ['class' => 'input-field']])->textInput(['class' => 'form-control to-date-range', 'readonly' => true, 'autocomplete' => 'off', 'placeholder' => ($model->getAttributeLabel('to'))])->label(CampaignSummaryReportModule::t('campaignsummaryreport', 'to_date')) ?>
                        </div>
                        <div class="col s12 mt-1">
                            <div class="input-field">
                                <?= Html::submitButton(CampaignSummaryReportModule::t('campaignsummaryreport', 'search'), ['class' =>
                                    'btn waves-effect waves-light amber darken-4']) ?>
                                <?= Html::a(CampaignSummaryReportModule::t('campaignsummaryreport', 'reset'), ['index', 'page' =>
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
