<?php

use app\modules\ecosmob\campaign\models\Campaign;
use app\modules\ecosmob\campaign\models\CampaignMappingUser;
use app\modules\ecosmob\campaignreport\CampaignReportModule;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model \app\modules\ecosmob\campaignreport\models\CampaignCdrReportSearch */
/* @var $form yii\widgets\ActiveForm */
$campaignList = [];
$supervisorCamp = CampaignMappingUser::find()
    ->where(['supervisor_id' => Yii::$app->user->id])
    ->all();
foreach ($supervisorCamp as $supervisorCamps) {
    $campaignList[] = $supervisorCamps['campaign_id'];
}
$campaignList = Campaign::find()->where(['cmp_id' => $campaignList])->andWhere(['cmp_status' => 'Active'])->all();
$campaign = ArrayHelper::map($campaignList, 'cmp_id', 'cmp_name');
?>

<ul class="collapsible collapsible-accordion" data-collapsible="accordion" >
    <li>
        <div class="collapsible-header"><?= Yii::t('app', 'search') ?></div>
        <div class="collapsible-body">
            <div id="input-fields">
                <div class="campaign-cdr-report-search"
                     id="campaign-cdr-report-search">
                    <?php $form = ActiveForm::begin([
                        'id' => 'campaign-cdr-report-search-form',
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
                            <div class="input-field ">
                                <?= $form->field($model, 'campaign', ['options' => ['class' => '']])->dropDownList($campaign, ['prompt' => CampaignReportModule::t('campaignreport', 'prompt_camp')])->label(CampaignReportModule::t('campaignreport', 'campaign')); ?>
                            </div>
                        </div>
                        <div class="col s12 m6 l4 calender-icon">
                            <div class="input-field ">
                                <?= $form->field($model, 'from', ['options' => ['class' => '']])->textInput(['class' => 'form-control from-date-range', 'readonly' => true, 'autocomplete' => 'off', 'placeholder'=> (CampaignReportModule::t('campaignreport', 'From Date'))])->label(CampaignReportModule::t('campaignreport', 'From Date')) ?>

                            </div>
                        </div>
                        <div class="col s12 m6 l4 calender-icon">
                            <div class="input-field ">
                                <?= $form->field($model, 'to', ['options' => ['class' => '']])->textInput(['class' => 'form-control to-date-range', 'readonly' => true, 'autocomplete' => 'off', 'placeholder'=> (CampaignReportModule::t('campaignreport', 'To Date'))])->label(CampaignReportModule::t('campaignreport', 'To Date')) ?>

                            </div>
                        </div>
                        <div class="col s12 mt-1">
                            <div class="input-field ">
                                <?= Html::submitButton(CampaignReportModule::t('campaignreport', 'search'), ['class' =>
                                    'btn waves-effect waves-light amber darken-4']) ?>
                                <?= Html::a(CampaignReportModule::t('campaignreport', 'reset'), ['index', 'page' =>
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

