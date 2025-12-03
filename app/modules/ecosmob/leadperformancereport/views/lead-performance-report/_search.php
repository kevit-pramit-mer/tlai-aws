<?php

use app\modules\ecosmob\campaign\models\Campaign;
use app\modules\ecosmob\campaignsummaryreport\models\CampaignSearch;
use app\modules\ecosmob\leadperformancereport\LeadPerformanceReportModule;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\ecosmob\leadgroup\models\LeadgroupMaster;
use app\modules\ecosmob\campaign\models\CampaignMappingUser;

/* @var $this yii\web\View */
/* @var $model CampaignSearch */
/* @var $form yii\widgets\ActiveForm */
$campaignList = [];
if(Yii::$app->user->identity->adm_is_admin == 'supervisor') {
    $campaignList = Campaign::find()->select(['ct_call_campaign.cmp_id', 'ct_call_campaign.cmp_name'])
        ->leftJoin('campaign_mapping_user', 'campaign_mapping_user.campaign_id = ct_call_campaign.cmp_id')
        ->where(['ct_call_campaign.cmp_status' => 'Active', 'campaign_mapping_user.supervisor_id' => Yii::$app->user->id])->asArray()->all();
}else {
    $campaignList = Campaign::find()->where(['cmp_status' => 'Active'])->all();
}
$campaign = ArrayHelper::map($campaignList, 'cmp_id', 'cmp_name');

if (isset($_GET['LeadPerformanceSearchReport']['campaign']) && $_GET['LeadPerformanceSearchReport']['campaign'] != '') {
    $leadGroupList = LeadgroupMaster::find()
        ->innerJoin('ct_call_campaign', 'ld_id = ct_call_campaign.cmp_lead_group')
        ->where(['ct_call_campaign.cmp_id' => $_GET['LeadPerformanceSearchReport']['campaign']])->all();
} else {
    $leadGroupList = LeadgroupMaster::find()->all();
}
$leadGroups = ArrayHelper::map($leadGroupList, 'ld_id', 'ld_group_name');

?>

<ul class="collapsible collapsible-accordion" data-collapsible="accordion" >
    <li>
        <div class="collapsible-header"><i class="material-icons">search</i><?= Yii::t('app', 'search') ?></div>
        <div class="collapsible-body">
            <div id="input-fields">
                <div class="lead-performance-report-search"
                     id="lead-performance-report-search">
                    <?php $form = ActiveForm::begin([
                        'id' => 'lead-performance-report-search-form',
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
                            <div class="select-wrapper ">
                                <?= $form->field($model, 'campaign', ['options' => ['class' => 'input-field', 'id' => 'campaign_id']])->dropDownList($campaign, ['prompt' => LeadPerformanceReportModule::t('leadperformancereport', 'prompt_camp')])->label(LeadPerformanceReportModule::t('leadperformancereport', 'campaign')); ?>
                            </div>
                        </div>
                        <div class="col s12 m6">
                            <div class="select-wrapper ">
                                <?= $form->field($model, 'leadgroup', ['options' => ['class' => 'input-field']])->dropDownList($leadGroups, ['prompt' => LeadPerformanceReportModule::t('leadperformancereport', 'select_lead_group')])->label(LeadPerformanceReportModule::t('leadperformancereport', 'leadgroup')); ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12 m6 calender-icon">
                            <?= $form->field($model, 'from', ['options' => ['class' => 'input-field']])->textInput(['class' => 'form-control from-date-range', 'readonly' => true, 'autocomplete' => 'off'])->label(LeadPerformanceReportModule::t('leadperformancereport', 'from_date')) ?>
                        </div>
                        <div class="col s12 m6 calender-icon">
                            <?= $form->field($model, 'to', ['options' => ['class' => 'input-field']])->textInput(['class' => 'form-control to-date-range', 'readonly' => true, 'autocomplete' => 'off'])->label(LeadPerformanceReportModule::t('leadperformancereport', 'to_date')) ?>
                        </div>
                        <div class="col s12 mt-1">
                            <div class="input-field">
                                <?= Html::submitButton(LeadPerformanceReportModule::t('leadperformancereport', 'search'), ['class' =>
                                    'btn waves-effect waves-light amber darken-4']) ?>
                                <?= Html::a(LeadPerformanceReportModule::t('leadperformancereport', 'reset'), ['index', 'page' =>
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
