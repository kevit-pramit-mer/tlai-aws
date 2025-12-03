<?php
/*/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

/* @var $model app\modules\ecosmob\auth\models\LoginForm */

use app\assets\LoginAsset;
use app\modules\ecosmob\auth\AuthModule;
use app\modules\ecosmob\campaign\models\Campaign;
use app\modules\ecosmob\campaign\models\CampaignMappingAgents;
use yii\bootstrap\ActiveForm;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use app\components\CommonHelper;
use yii\helpers\Url;

$this->title = AuthModule::t('auth', 'sign_in_as_agent');
$this->params['breadcrumbs'][] = $this->title;
LoginAsset::register($this);

$loginAgent = Yii::$app->user->identity->adm_id;

$dataList = array();
$campaignList = CampaignMappingAgents::find()->select('campaign_id')->where(['agent_id' => $loginAgent])->asArray()->all();


$ids = implode(",", array_map(function ($a) {
    return implode("~", $a);
}, $campaignList));

$campaignListData = Campaign::find()->select(['cmp_id', 'cmp_name', 'cmp_type', 'cmp_dialer_type'])
    ->where(new Expression('FIND_IN_SET(cmp_id,"' . $ids . '")'))
    ->andWhere(['cmp_status' => 'Active'])
    ->asArray()->all();

$campaignListType = ArrayHelper::map($campaignListData, 'cmp_id', 'cmp_name');
$campaignListType = CommonHelper::customMap($campaignListData, 'cmp_id', 'cmp_name', "cmp_type", "cmp_type");
$campaignListTypeDataAttributes = CommonHelper::customMapNew($campaignListData, 'cmp_id', 'cmp_name', "cmp_type", "cmp_type");
?>

<style>
    [type='checkbox']:not(:checked),
    [type='checkbox']:checked {
        display: none !important;
    }

    .disabled {
        pointer-events: none;
        opacity: 0.6;
    }
    [type='checkbox'] + span:not(.lever) {
        padding-left: 35px !important;
    }
    .card-panel{
        height: auto;
    }
</style>
<div class="card-panel">
    <div class="row">
        <div class="col s12 text-center">
            <h5 class="ml-4"><?= $this->title ?></h5>
        </div>
    </div>

    <div class="row auth-section">
        <div id="campaign" class="col s12">
            <?php $form = ActiveForm::begin([
                'layout' => 'horizontal',
                'class' => 'login-form',
                'action' => ['auth/login'],
            ]); ?>

            <div class="row margin">
                <div class="input-field col s12 b-radius-8">
                    <?= $form->field($model, 'campaign_type', [
                        'template' => "<i class=\"material-icons prefix\">call</i>{input}{error}",
                    ])->dropDownList($campaignListType, ['options' => $campaignListTypeDataAttributes,'multiple' => 'multiple', 'id' => 'multi_select_campaign'])->label(AuthModule::t('auth', 'sign_in_as_agent')) ?>
                </div>
            </div>

            <div class="row mt-8">
                <div class="col s12">
                    <input type="hidden" name="logintype" value="supervisor">
                    <?= Html::submitButton(AuthModule::t('auth', 'login'), [
                        'class' => 'btn waves-effect waves-light border-round gradient-45deg-purple-deep-orange col s12',
                        'name' => 'agent-button',
                    ]) ?>
                </div>
            </div>
            <div class="row">
                <div class="mt-3 col s12">
                    <p class="margin  medium-small">
                        <a href="<?= Url::to(['auth/login']) ?>" class="btn-link text-right waves-effect waves-light col s12"><?= AuthModule::t('auth', 'go_back') ?></a>
                    </p>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
<!--
<script type="text/javascript">
    $(document).ready(function () {
        $camptype  = $('#campaign_type').val();
        if ($camptype == ' '){
            alert('hello')
           return false;
        }

    });
</script>-->

<script>
    $(document).ready(function(){
        setInterval(function () {
            $('.field-multi_select_campaign .select-wrapper .select-dropdown').attr('placeholder', 'Select Campaign');
        }, 100);
    });
    $("#multi_select_campaign").change(function () {
        if($("#multi_select_campaign  option:selected").val()){
            if($("#multi_select_campaign option:selected").attr('data-campaigntype')!=='PREVIEW'){

                $("#multi_select_campaign").find("option[data-campaigntype*='PREVIEW']").prop("disabled", true);
            }else{
                $("#multi_select_campaign").find("option[data-campaigntype*='AUTO']").prop("disabled", true);
                $("#multi_select_campaign").find("option[data-campaigntype*='Inbound']").prop("disabled", true);
                $("#multi_select_campaign").find("option[data-campaigntype*='PROGRESSIVE']").prop("disabled", true);
            }

        }else{
            $("#multi_select_campaign option").prop("disabled", false);
        }

        $("#multi_select_campaign").formSelect();
    });
</script>
