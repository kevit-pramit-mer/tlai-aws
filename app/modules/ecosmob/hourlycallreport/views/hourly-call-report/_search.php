<?php

use app\modules\ecosmob\admin\models\AdminMaster;
use app\modules\ecosmob\hourlycallreport\HourlyCallReportModule;
use app\modules\ecosmob\campaign\models\Campaign;
use app\modules\ecosmob\campaign\models\CampaignMappingAgents;
use app\modules\ecosmob\campaign\models\CampaignMappingUser;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\ecosmob\queue\models\QueueMaster;

/* @var $this yii\web\View */
/* @var $model \app\modules\ecosmob\hourlycallreport\models\HourlyCallReportSearch */
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
$queue = ArrayHelper::map(QueueMaster::find()->select(['qm_id', new \yii\db\Expression("SUBSTRING_INDEX(qm_name, '_', 1) AS qm_name")])->where(['qm_status' => '1'])->all(), 'qm_id', 'qm_name');

?>

<ul class="collapsible collapsible-accordion" data-collapsible="accordion" >
    <li>
        <div class="collapsible-header"><?= Yii::t('app', 'search') ?></div>
        <div class="collapsible-body">
            <div id="input-fields">

                <div class="hourly-call-report-search"
                     id="hourly-call-report-search">

                    <?php $form = ActiveForm::begin([
                        'id' => 'hourly-call-report-search-form',
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
                            <?= $form->field($model, 'from', ['options' => ['class' => 'input-field']])->textInput(['class' => 'form-control from-date-range', 'readonly' => true, 'autocomplete' => 'off'])->label(HourlyCallReportModule::t('hourlycallreport', 'from_date')) ?>
                        </div>

                        <div class="col s12 m6 l4 calender-icon">
                            <?= $form->field($model, 'to', ['options' => ['class' => 'input-field']])->textInput(['class' => 'form-control to-date-range', 'readonly' => true, 'autocomplete' => 'off'])->label(HourlyCallReportModule::t('hourlycallreport', 'to_date')) ?>
                        </div>

                        <div class="col s12 m6 l4">
                            <div class="select-wrapper ">
                                <?= $form->field($model, 'agent_id', ['options' => ['class' => 'input-field']])->dropDownList($agentName, ['prompt' => HourlyCallReportModule::t('hourlycallreport', 'prompt_agent')])->label(HourlyCallReportModule::t('hourlycallreport', 'agent_name')); ?>
                            </div>
                        </div>
                        <div class="col s12 m6 l4">
                            <div class="select-wrapper ">
                                <?= $form->field($model, 'camp_name', ['options' => ['class' => 'input-field']])->dropDownList($campaign, ['prompt' => HourlyCallReportModule::t('hourlycallreport', 'prompt_camp')])->label(HourlyCallReportModule::t('hourlycallreport', 'campaign')); ?>
                            </div>
                        </div>
                        <div class="col s12 m6 l4">
                            <div class="select-wrapper ">
                                <?= $form->field($model, 'queue', ['options' => ['class' => 'input-field']])->dropDownList($queue, ['prompt' => HourlyCallReportModule::t('hourlycallreport', 'prompt_queue')])->label(HourlyCallReportModule::t('hourlycallreport', 'queue')); ?>
                            </div>
                        </div>
                        <div class="col s12 mt-1">
                            <div class="input-field">
                                <?= Html::submitButton(HourlyCallReportModule::t('hourlycallreport', 'search'), ['class' =>
                                    'btn waves-effect waves-light amber darken-4']) ?>
                                <?= Html::a(HourlyCallReportModule::t('hourlycallreport', 'reset'), ['index', 'page' =>
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
