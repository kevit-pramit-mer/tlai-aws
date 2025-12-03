<?php

use app\modules\ecosmob\campaign\models\Campaign;
use app\modules\ecosmob\campaign\models\CampaignMappingUser;
use app\modules\ecosmob\supervisorsummary\SupervisorSummaryModule;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\supervisorsummary\models\UsersActivityLogSearch */
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
                <div class="users-activity-log-search"
                     id="users-activity-log-search">
                    <?php $form = ActiveForm::begin([
                        'id' => 'users-activity-log-search-form',
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
                                <?= $form->field($model, 'from', ['options' => ['class' => '']])->textInput(['class' => 'form-control from-date-range', 'readonly' => true, 'autocomplete' => 'off', 'placeholder' => SupervisorSummaryModule::t('supervisorsummary', "From Date")])->label(SupervisorSummaryModule::t('supervisorsummary', 'From Date')) ?>   

                            </div>
                        </div>
                        <div class="col s12 m6 l4 calender-icon">
                            <div class="input-field">
                                <?= $form->field($model, 'to', ['options' => ['class' => '']])->textInput(['class' => 'form-control to-date-range', 'readonly' => true, 'autocomplete' => 'off', 'placeholder' => SupervisorSummaryModule::t('supervisorsummary', "To Date")])->label(SupervisorSummaryModule::t('supervisorsummary', 'To Date')) ?>

                            </div>
                        </div>
                        <div class="col s12 m6 l4">
                            <div class="input-field">
                                <?= $form->field($model, 'campaign', ['options' => ['class' => '']])->dropDownList($campaign, ['prompt' => SupervisorSummaryModule::t('supervisorsummary', 'prompt_camp')])->label(SupervisorSummaryModule::t('supervisorsummary', 'camp')); ?>
                            </div>
                        </div>
                        <div class="col s12 m6 l4">
                            <div class="input-field">
                                <?= $form->field($model, 'user_type', ['options' => ['class' => '']])->dropDownList(['supervisor' => SupervisorSummaryModule::t('supervisorsummary', 'supervisor'), 'agent' => SupervisorSummaryModule::t('supervisorsummary', 'agent')], ['prompt' => SupervisorSummaryModule::t('supervisorsummary', 'prompt_usr')])->label(SupervisorSummaryModule::t('supervisorsummary', 'user')); ?>
                            </div>
                        </div>
                        <div class="col s12 mt-1">
                            <div class="input-field">
                                <?= Html::submitButton(SupervisorSummaryModule::t('supervisorsummary', 'search'), ['class' =>
                                    'btn waves-effect waves-light amber darken-4']) ?>
                                <?= Html::a(SupervisorSummaryModule::t('supervisorsummary', 'reset'), ['index', 'page' =>
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
