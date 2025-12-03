<?php

use app\modules\ecosmob\campaign\models\Campaign;
use app\modules\ecosmob\campaign\models\CampaignMappingUser;
use app\modules\ecosmob\manageagent\ManageAgentModule;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\manageagent\models\ManageAgentSearch */
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
                <div class="manage-agent-search" id="manage-agent-search">
                    <?php $form = ActiveForm::begin([
                        'id' => 'manage-agent-search-form',
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
                            <div class="input-field">
                                <?= $form->field($model, 'adm_firstname')->label(ManageAgentModule::t('manageagent', 'adm_firstname'))->textInput( [ 'placeholder' => ($model->getAttributeLabel('adm_firstname')) ] ) ?>
                            </div>
                        </div>
                        <div class="col s12 m6 l4">
                            <div class="input-field">
                                <?= $form->field($model, 'adm_lastname')->label(ManageAgentModule::t('manageagent', 'adm_lastname'))->textInput( [ 'placeholder' => ($model->getAttributeLabel('adm_lastname')) ] ) ?>
                            </div>
                        </div>
                        <div class="col s12 m6 l4">
                            <div class="input-field">
                                <?= $form->field($model, 'adm_username')->label(ManageAgentModule::t('manageagent', 'adm_username'))->textInput( [ 'placeholder' => ($model->getAttributeLabel('adm_username')) ] ) ?>
                            </div>
                        </div>
                        <div class="col s12 m6 l4">
                            <div class="select-wrapper">
                                <?= $form->field($model, 'adm_status', ['options' => ['class' => 'input-field']])->dropDownList
                                ([1 => Yii::t('app', 'active'), 0 => Yii::t('app', 'inactive')],
                                    ['prompt' => ManageAgentModule::t('manageagent',
                                        'select')])->label(ManageAgentModule::t('manageagent', 'adm_status')); ?>
                            </div>
                        </div>
                        <div class="col s12 m6 l4">
                            <div class="select-wrapper">
                                <?= $form->field($model, 'campaign', ['options' => ['class' => 'input-field']])->dropDownList($campaign, ['prompt' => ManageAgentModule::t('manageagent', 'select')])->label(ManageAgentModule::t('manageagent', 'campaign')); ?>
                            </div>
                        </div>
                        <div class="col s12 mt-1">
                            <div class="input-field">
                                <?= Html::submitButton(ManageAgentModule::t('manageagent', 'search'), ['class' =>
                                    'btn waves-effect waves-light amber darken-4']) ?>
                                <?= Html::a(ManageAgentModule::t('manageagent', 'reset'), ['index', 'page' =>
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
