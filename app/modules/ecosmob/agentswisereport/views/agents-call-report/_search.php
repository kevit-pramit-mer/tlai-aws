<?php

use app\modules\ecosmob\admin\models\AdminMaster;
use app\modules\ecosmob\agentswisereport\AgentsWiseReportModule;
use app\modules\ecosmob\campaign\models\Campaign;
use app\modules\ecosmob\campaign\models\CampaignMappingAgents;
use app\modules\ecosmob\campaign\models\CampaignMappingUser;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\agentscallreport\models\AgentsCallReportSearch */
/* @var $form yii\widgets\ActiveForm */


$arr = ['agent', 'supervisor'];

$campaignList = [];
$agentsList = [];
$supervisorCamp = CampaignMappingUser::find()
    //->where(['supervisor_id' => Yii::$app->user->id])
    ->all();
foreach ($supervisorCamp as $supervisorCamps) {
    $campaignList[] = $supervisorCamps['campaign_id'];
}

$agentsCamp = CampaignMappingAgents::find()
    ->where(['campaign_id' => $campaignList])
    ->all();

foreach ($agentsCamp as $agentsCamps) {
    $agentsList[] = $agentsCamps['agent_id'];
}

$agentName = ArrayHelper::map(AdminMaster::find()->where(['adm_is_admin' => $arr])->andWhere(['adm_id' => $agentsList])->all(), 'adm_id', function ($model) {
    return $model['adm_firstname'] . ' ' . $model['adm_lastname'];
});

$campaignList = Campaign::find()->where(['cmp_id' => $campaignList])->andWhere(['cmp_status' => 'Active'])->all();

$campaign = ArrayHelper::map($campaignList, 'cmp_id', 'cmp_name');

?>

<ul class="collapsible collapsible-accordion" data-collapsible="accordion" >
    <li>
        <div class="collapsible-header"><?= Yii::t('app', 'search') ?></div>
        <div class="collapsible-body">
            <div id="input-fields">
                <div class="agents-call-report-search" id="agents-call-report-search">
                    <?php $form = ActiveForm::begin([
                        'id' => 'agents-call-report-search-form',
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
                        <div class="col s12 m6 l4 calender-icon">
                            <?= $form->field($model, 'from', ['options' => ['class' => 'input-field']])->textInput(['class' => 'form-control from-date-time-range', 'readonly' => true, 'autocomplete' => 'off', 'placeholder' => ($model->getAttributeLabel('from'))])->label(AgentsWiseReportModule::t('agentswisereport', 'From Date')) ?>
                        </div>

                        <div class="col s12 m6 l4 calender-icon">
                            <?= $form->field($model, 'to', ['options' => ['class' => 'input-field']])->textInput(['class' => 'form-control to-date-time-range', 'readonly' => true, 'autocomplete' => 'off', 'placeholder' => ($model->getAttributeLabel('to'))])->label(AgentsWiseReportModule::t('agentswisereport', 'To Date')) ?>
                        </div>

                        <div class="col s12 m6 l4">
                            <div class="input-field mt-2">
                                <?= $form->field($model, 'campaign', ['options' => ['class' => 'input-field']])->dropDownList($campaign, ['prompt' => AgentsWiseReportModule::t('agentswisereport', 'select_campaign')])->label(AgentsWiseReportModule::t('agentswisereport', 'campaign')); ?>
                            </div>
                        </div>
                        <div class="col s12 m6 l4">
                            <div class="input-field mt-2">
                                <?= $form->field($model, 'agent', ['options' => ['class' => 'input-field']])->dropDownList($agentName, ['prompt' => AgentsWiseReportModule::t('agentswisereport', 'select_agent_name')])->label(AgentsWiseReportModule::t('agentswisereport', 'agent_name')); ?>
                            </div>
                        </div>
                        <div class="col s12 mt-1">
                            <div class="input-field">
                                <?= Html::submitButton(AgentsWiseReportModule::t('agentswisereport', 'search'), ['class' =>
                                    'btn waves-effect waves-light amber darken-4']) ?>
                                <?= Html::a(AgentsWiseReportModule::t('agentswisereport', 'reset'), ['index', 'page' =>
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
