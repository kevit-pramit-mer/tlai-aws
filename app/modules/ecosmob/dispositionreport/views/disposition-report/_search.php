<?php

use app\modules\ecosmob\admin\models\AdminMaster;
use app\modules\ecosmob\dispositionreport\DispositionReportModule;
use app\modules\ecosmob\campaign\models\Campaign;
use app\modules\ecosmob\campaign\models\CampaignMappingAgents;
use app\modules\ecosmob\campaign\models\CampaignMappingUser;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model \app\modules\ecosmob\dispositioneport\models\DispositionReportSearch */
/* @var $form yii\widgets\ActiveForm */


$arr = ['agent', 'supervisor'];

$campaignList = [];
$agentsList = [];
$supervisorCamp = CampaignMappingUser::find();
if (Yii::$app->user->identity->adm_is_admin == 'supervisor') {
    $supervisorCamp = $supervisorCamp->where(['supervisor_id' => Yii::$app->user->id]);
}
$supervisorCamp = $supervisorCamp->all();
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
                <div class="disposition-report-search" id="disposition-report-search">
                    <?php $form = ActiveForm::begin([
                        'id' => 'disposition-report-search-form',
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
                                <?= $form->field($model, 'camp_name', ['options' => ['class' => 'input-field']])->dropDownList($campaign, ['prompt' => DispositionReportModule::t('dispositionreport', 'prompt_camp')])->label(DispositionReportModule::t('dispositionreport', 'campaign')); ?>
                            </div>
                        </div>
                        <div class="col s12 m6 l4">
                            <div class="select-wrapper ">
                                <?= $form->field($model, 'agent_id', ['options' => ['class' => 'input-field']])->dropDownList($agentName, ['prompt' => DispositionReportModule::t('dispositionreport', 'prompt_agent')])->label(DispositionReportModule::t('dispositionreport', 'agent_name')); ?>
                            </div>
                        </div>
                        <div class="col s12 m6 l4 calender-icon">
                            <?= $form->field($model, 'from', ['options' => ['class' => 'input-field']])->textInput(['class' => 'form-control from-date-range', 'readonly' => true, 'autocomplete' => 'off', 'placeholder' => ($model->getAttributeLabel('from'))])->label(DispositionReportModule::t('dispositionreport', 'from_date')) ?>
                        </div>

                        <div class="col s12 m6 l4 calender-icon">
                            <?= $form->field($model, 'to', ['options' => ['class' => 'input-field']])->textInput(['class' => 'form-control to-date-range', 'readonly' => true, 'autocomplete' => 'off', 'placeholder' => ($model->getAttributeLabel('to'))])->label(DispositionReportModule::t('dispositionreport', 'to_date')) ?>
                        </div>
                        <div class="col s12 mt-1">
                            <div class="input-field ">
                                <?= Html::submitButton(DispositionReportModule::t('dispositionreport', 'search'), ['class' =>
                                    'btn waves-effect waves-light amber darken-4']) ?>
                                <?= Html::a(DispositionReportModule::t('dispositionreport', 'reset'), ['index', 'page' =>
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
