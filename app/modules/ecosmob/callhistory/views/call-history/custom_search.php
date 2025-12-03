<?php

use app\modules\ecosmob\callhistory\CallHistoryModule;
use app\modules\ecosmob\campaign\models\Campaign;
use app\modules\ecosmob\campaign\models\CampaignMappingUser;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\callhistory\models\CampCdrSearch */
/* @var $form yii\widgets\ActiveForm */

$campaignList = [];
$supervisorCamp = CampaignMappingUser::find()
    ->where(['supervisor_id' => Yii::$app->user->id])
    ->all();
foreach ($supervisorCamp as $supervisorCamps) {
    $campaignList[] = $supervisorCamps['campaign_id'];
}

$session = yii::$app->session;
$agentCamp = $session->get('selectedCampaign');

if ($agentCamp) {
    $campaignList = Campaign::find()->where(['cmp_id' => explode(',', $agentCamp)])->andWhere(['cmp_status' => 'Active'])->all();
} else {
    $campaignList = Campaign::find()->where(['cmp_id' => explode(',', $agentCamp)])->andWhere(['cmp_status' => 'Active'])->all();
}

$campaignList = Campaign::find()->where(['cmp_id' => $campaignList])->andWhere(['cmp_status' => 'Active'])->all();
$campaign = ArrayHelper::map($campaignList, 'cmp_id', 'cmp_name');
?>

<ul class="collapsible collapsible-accordion" data-collapsible="accordion">
    <li class="<?= $model->hasErrors() ? 'active' : '' ?>">
        <div class="collapsible-header"><?= Yii::t('app', 'search') ?></div>
        <div class="collapsible-body">
            <div id="input-fields">
                <div class="camp-cdr-search"
                     id="camp-cdr-search">
                    <?php $form = ActiveForm::begin([
                        'id' => 'camp-cdr-search-form',
                        'action' => ['customindex'],
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
                                <?= $form->field($model, 'from', ['options' => ['class' => '']])->textInput(['class' => 'form-control from-date-range', 'readonly' => true, 'autocomplete' => 'off'])->label(CallHistoryModule::t('callhistory', 'from_date')) ?>

                            </div>
                        </div>
                        <div class="col s12 m6 l4 calender-icon">
                            <div class="input-field">
                                <?= $form->field($model, 'to', ['options' => ['class' => '']])->textInput(['class' => 'form-control to-date-range', 'readonly' => true, 'autocomplete' => 'off'])->label(CallHistoryModule::t('callhistory', 'to_date')) ?>
                            </div>
                        </div>
                        <div class="col s12 m6 l4">
                            <div class="input-field">
                                <?= $form->field($model, 'campaign_name', ['options' => ['class' => '']])->dropDownList($campaign, ['prompt' => CallHistoryModule::t('callhistory', 'select_campaign')])->label(CallHistoryModule::t('callhistory', 'campaign')); ?>
                            </div>
                        </div>
                        <div class="col s12 mt-1">
                            <div class="input-field">
                                <?= Html::submitButton(CallHistoryModule::t('callhistory', 'search'), ['class' =>
                                    'btn waves-effect waves-light amber darken-4']) ?>
                                <?= Html::a(CallHistoryModule::t('callhistory', 'reset'), ['customindex', 'page' =>
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
