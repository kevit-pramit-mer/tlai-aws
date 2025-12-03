<?php

use app\modules\ecosmob\admin\models\AdminMaster;
use app\modules\ecosmob\agentscallreport\AgentsCallReportModule;
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
//$campaign = ArrayHelper::map(Campaign::find()->where(['cmp_status'=>'Active'])->all(), 'cmp_id', 'cmp_name');

$campaignList = [];
$agentsList = [];
$supervisorCamp = CampaignMappingUser::find()
    ->where(['supervisor_id' => Yii::$app->user->id])
    ->all();
foreach ($supervisorCamp as $supervisorCamps) {
    $campaignList[] = $supervisorCamps['campaign_id'];
}

/*$agentName = ArrayHelper::map(AdminMaster::find()->where(['adm_is_admin' => $arr])->all(), 'adm_id', function ($model) {
    return $model['adm_firstname'] . ' ' . $model['adm_lastname'];
});*/

/*$dataList=array();
$campaignList=CampaignMappingUser::find()->select('campaign_id')->where(['supervisor_id'=>Yii::$app->user->id])->asArray()->all();
*/
/*$ids=implode(",", array_map(function ($a) {
    return implode("~", $a);
}, $campaignList));*/

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


/*
$campaignListData=Campaign::find()->select(['cmp_id', 'cmp_name'])
    ->andWhere(new Expression('FIND_IN_SET(cmp_id,"' . $ids . '")'))
    ->andWhere(['cmp_status'=>'Active'])
    ->asArray()->all();
$campaignListType=ArrayHelper::map($campaignListData, 'cmp_id', 'cmp_name');*/
?>

<ul class="collapsible collapsible-accordion" data-collapsible="accordion" >
    <li>
        <div class="collapsible-header"><?= Yii::t('app', 'search') ?></div>
        <div class="collapsible-body">
            <div id="input-fields">
                <div class="agents-call-report-search"
                     id="agents-call-report-search">
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
                            <div class="input-field">
                                <?= $form->field($model, 'from', ['options' => ['class' => '']])->textInput(['class' => 'form-control from-date-time-range', 'readonly' => true, 'autocomplete' => 'off', 'placeholder' => ($model->getAttributeLabel('From Date'))])->label(AgentsCallReportModule::t('agentscallreport', 'From Date')) ?>

                            </div>
                        </div>
                        <div class="col s12 m6 l4 calender-icon">
                            <div class="input-field">
                                <?= $form->field($model, 'to', ['options' => ['class' => '']])->textInput(['class' => 'form-control to-date-time-range', 'readonly' => true, 'autocomplete' => 'off', 'placeholder' => ($model->getAttributeLabel('To Date'))])->label(AgentsCallReportModule::t('agentscallreport', 'To Date')) ?>

                            </div>
                        </div>
                        <div class="col s12 m6 l4">
                            <div class="input-field">
                                <?= $form->field($model, 'campaign_name', ['options' => ['class' => '']])->dropDownList($campaign, ['prompt' => AgentsCallReportModule::t('agentscallreport', 'select_campaign')])->label(AgentsCallReportModule::t('agentscallreport', 'campaign')); ?>
                            </div>
                        </div>
                        <div class="col s12 m6 l4">
                            <div class="input-field">
                                <?= $form->field($model, 'agent', ['options' => ['class' => '']])->dropDownList($agentName, ['prompt' => AgentsCallReportModule::t('agentscallreport', 'select_agent_name')])->label(AgentsCallReportModule::t('agentscallreport', 'agent_name')); ?>
                            </div>
                        </div>
                        <div class="col s12 mt-1">
                            <div class="input-field">
                                <?= Html::submitButton(Yii::t('app', 'search'), ['class' =>
                                    'btn waves-effect waves-light amber darken-4']) ?>
                                <?= Html::a(Yii::t('app', 'reset'), ['index', 'page' =>
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
