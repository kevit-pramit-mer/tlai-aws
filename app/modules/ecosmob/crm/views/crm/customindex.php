<?php

use app\modules\ecosmob\agent\models\Agent;
use app\modules\ecosmob\crm\assets\CrmAsset;
use app\modules\ecosmob\crm\CrmModule;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Breadcrumbs;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\ecosmob\crm\models\LeadGroupMemberSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = CrmModule::t('crm', 'crm');
$this->params['breadcrumbs'][] = $this->title;
$this->params['pageHead'] = $this->title;
CrmAsset::register($this);
//$this->registerJs('')
/*$campaignDialerType['cmp_dialer_type']=='AUTO'*/
/*$campaignDialerType['cmp_type']=='Blended'*/
$userType = (isset(Yii::$app->user->identity->adm_is_admin) ? Yii::$app->user->identity->adm_is_admin : '');
?>



<style>
    #main .content-wrapper-before {
        top: 0px !important;
    }
</style>


<script>
    var hs_custom_search = "<?php echo Yii::t('app', 'search'); ?>";
    var hs_custom_no_matching_records_found = "<?php echo Yii::t('app', 'no_matching_records_found'); ?>";
    var baseURL = '<?= Yii::$app->homeUrl ?>';
    var userType = "<?= $userType ?>";
</script>


<link rel="stylesheet" href="<?php echo Url::base(true) . '/theme/assets/css/general/materialize.css' ?>">
<link rel="stylesheet" href="<?php echo Url::base(true) . '/theme/assets/css/general/style.css' ?>">
<link rel="stylesheet" href="<?php echo Url::base(true) . '/theme/assets/css/general/newvendors.css' ?>">
<link rel="stylesheet" href="<?php echo Url::base(true) . '/theme/assets/css/select2.min.css' ?>">
<link rel="stylesheet" href="<?php echo Url::base(true) . '/theme/assets/css/data-tables/data-tables.css' ?>">
<link rel="stylesheet" href="<?php echo Url::base(true) . '/theme/assets/css/data-tables/jquery.dataTables.min.css' ?>">
<link rel="stylesheet"
      href="<?php echo Url::base(true) . '/theme/assets/css/data-tables/responsive.dataTables.min.css' ?>">
<link rel="stylesheet" href="<?php echo Url::base(true) . '/theme/assets/css/data-tables/select.dataTables.min.css' ?>">

<script type="text/javascript" src="<?php echo Url::base(true) . '/theme/assets/js/jquery.min.js' ?>"></script>
<script type="text/javascript" src="<?php echo Url::base(true) . '/theme/assets/js/plugins.min.js' ?>"></script>
<script type="text/javascript" src="<?php echo Url::base(true) . '/theme/assets/js/vendors.min.js' ?>"></script>

<script type="text/javascript" src="<?php echo Url::base(true) . '/theme/assets/js/select2.min.js' ?>"></script>
<script type="text/javascript"
        src="<?php echo Url::base(true) . '/theme/assets/js/form-mask/form-masks.js' ?>"></script>
<script type="text/javascript"
        src="<?php echo Url::base(true) . '/theme/assets/js/form-mask/form-layouts.js' ?>"></script>
<script type="text/javascript"
        src="<?php echo Url::base(true) . '/theme/assets/js/form-mask/jquery.formatter.min.js' ?>"></script>
<script type="text/javascript"
        src="<?php echo Url::base(true) . '/theme/assets/js/data-tables/data-tables.js' ?>"></script>
<script type="text/javascript"
        src="<?php echo Url::base(true) . '/theme/assets/js/data-tables/jquery.dataTables.min.js' ?>"></script>
<script type="text/javascript"
        src="<?php echo Url::base(true) . '/theme/assets/js/data-tables/dataTables.select.min.js' ?>"></script>
<script type="text/javascript"
        src="<?php echo Url::base(true) . '/theme/assets/js/data-tables/dataTables.responsive.min.js' ?>"></script>
<script type="text/javascript" src="<?php echo Url::base(true) . '/theme/assets/js/multiselect.min.js' ?>"></script>
<script type="text/javascript" src="<?php echo Url::base(true) . '/theme/assets/js/custom.js' ?>"></script>




<script>
    $(document).ready(function () {
        if (localStorage.getItem("toggle") == 1) { // open
            $('.sidenav-main').addClass('nav-expanded nav-lock').removeClass('nav-collapsed');
            $('.custom-sidenav-trigger').text('radio_button_checked');
            $('#main').removeClass('main-full');
            $('footer').removeClass('footer-full');
        } else { // close
            $('.sidenav-main').removeClass('nav-expanded nav-lock').addClass('nav-collapsed');
            $('.custom-sidenav-trigger').text('radio_button_unchecked');
            $('#main').addClass('main-full');
            $('footer').addClass('footer-full');
        }
    });
</script>

<!--*********************** CONTAINER ***********************-->
<?php
$mainClass = '';
if (Yii::$app->session->get('loginAsExtension')) {
    $mainClass = 'extension-main';
} else if (Yii::$app->user->identity->adm_is_admin == 'supervisor') {
    $mainClass = 'supervisor-main';
} else if (Yii::$app->user->identity->adm_is_admin == 'agent') {
    $mainClass = 'agent-main';
} else {
    $mainClass = 'tenant-main';
}
?>
<div id="main" class="<?=$mainClass?> main-full">
    <div class="row">
        <div class="col s12">

            <div class="container">

                 <div class="content-wrapper-before"></div>
                <div class="breadcrumbs-dark pb-0 pt-1 col s12 m5" id="breadcrumbs-wrapper">
                    <!-- Search for small screen-->

                    <div class="col m12">
                        <h5 class="breadcrumbs-title mt-0 mb-0"><?= (isset($this->params['pageHead']) ? $this->params['pageHead'] : "") ?></h5>
                        <?= Breadcrumbs::widget([
                            'tag' => 'ol',
                            'options' => ['class' => 'breadcrumbs mb-0'],
                            'itemTemplate' => "<li class='breadcrumb-item'>{link}</li>\n",
                            'homeLink' => [
                                'label' => Yii::t('yii', 'Home'),
                                'url' => Yii::$app->homeUrl,
                                'encode' => false// Requested feature
                            ],
                            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                        ]) ?>
                    </div>


                </div>




                <script type="text/javascript">
                    var extensionNumber = '<?php echo $extensionInformation['em_extension_number']; ?>'
                    var extensionPassword = '<?php echo $extensionInformation['em_password']; ?>'
                    var extensionName = '<?php echo $extensionInformation['em_extension_name']; ?>'
                    var campaign_id = '<?php echo $selectedCampaign; ?>'
                    var agent_id = '<?php echo $agentId; ?>'
                    var campaignDialerType = '<?php echo $campaignDialerType['cmp_dialer_type']; ?>'
                    var campaignType = '<?php echo $campaignDialerType['cmp_type']; ?>'
                    var ringFile = '<?php echo Url::to('@web' . '/media/recordings/12121435421212_5a16c955-7a87-9f05-e34c-96bb2bd8b485.wav'); ?>'
                    var cmp_caller_id = '<?php echo $campaignData['cmp_caller_id']; ?>';
                    var cmp_caller_name = '<?php echo $campaignData['cmp_caller_name']; ?>';
                    // var no_more_lead_in_hopper = '<?php //echo CrmModule::t('crm', 'no_more_leads'); ?>';
                    var no_more_lead_in_hopper = "<h6 class='pl-3'><?php echo Yii::t('app', 'lead_msg') ?></h6>";
                    //var no_more_lead_in_hopper = "<h6><?php echo CrmModule::t('crm', 'no_more_leads') ?></h6>";
                    var mute = "<?php echo CrmModule::t('crm', 'mute') ?>";
                    var unmute = "<?php echo CrmModule::t('crm', 'unmute') ?>";
                    var hold = "<?php echo CrmModule::t('crm', 'hold') ?>";
                    var unhold = "<?php echo CrmModule::t('crm', 'unhold') ?>";
                </script>

                <div class="row">
                    <div class="col-xl-9 col-md-7 col-xs-12">
                        <div class="row">
                            <div class="col s12">
                                <div class="profile-contain">
                                    <div class="section section-data-tables">
                                        <div class="row">
                                            <div class="col s12">
                                                <div class="card">
                                                    <div class="card-content">
                                                        <div class="box-tools">
                                                            <?php
                                                            if (isset($campaignDialerType) && !empty($campaignDialerType)) {
                                                                if ($campaignDialerType->cmp_dialer_type == 'PREVIEW') { ?>
                                                                    <div style="float:right;">
                                                                        <?php echo Html::Button(CrmModule::t('crm', 'dial_next'), ['class' => 'btn btn-basic dialnext', 'id' => 'dialNext']) ?>
                                                                        <?php /*echo Html::Button('Answering', ['class'=>'btn btn-basic', 'id'=>'answer-call', 'disabled'=>'disabled']) */ ?>
                                                                        <?php echo Html::Button(CrmModule::t('crm', 'hang_up'), ['class' => 'btn btn-basic', 'id' => 'hang-up-call', 'disabled' => 'disabled']) ?>
                                                                        <?php echo Html::Button(CrmModule::t('crm', 'hold'), ['class' => 'btn btn-basic hold-unhold', 'id' => 'hold-call', 'disabled' => 'disabled']) ?>
                                                                        <?php echo Html::Button(CrmModule::t('crm', 'mute'), ['class' => 'btn btn-basic mute-unmute', 'id' => 'mute-call', 'disabled' => 'disabled']) ?>
                                                                        <?php echo Html::Button(CrmModule::t('crm', 'transfer_call'), ['class' => 'btn btn-basic transfer-call', 'id' => 'transfer-call', 'disabled' => 'disabled']) ?>
                                                                        <?php /*echo Html::Button('Resume', ['class'=>'btn btn-basic', 'id'=>'unhold-call','disabled'=>'disabled']) */ ?>
                                                                        <?php /*echo Html::Button('Unmute call', ['class'=>'btn btn-basic', 'id'=>'unmute-call','disabled'=>'disabled'])*/ ?>
                                                                        <?php /*echo Html::Button('Mute/Unmute', ['class'=>'btn btn-basic', 'id'=>'mute-unmute-call', ]) */ ?>
                                                                        <!--<audio id="audio-container" autoplay playsinline></audio>-->
                                                                    </div>
                                                                <?php } else {
                                                                    $data = Agent::find()->where(['name' => Yii::$app->user->identity->adm_id.'_'.$GLOBALS['tenantID'], 'status' => 'Available'])->count();
                                                                    $is_in = 0;
                                                                    if ($data) {
                                                                        $is_in = 1;
                                                                    }
                                                                    ?>
                                                                    <div style="float:right;">
                                                                        <?php echo Html::Button(CrmModule::t('crm', 'pause'), ['class' => 'btn btn-basic pause', 'style' => (!$is_in) ? 'display: none' : 'display:inline-block']) ?>
                                                                        <?php echo Html::Button(CrmModule::t('crm', 'resume'), ['class' => 'btn btn-basic resume', 'style' => ($is_in) ? 'display: none' : 'display:inline-block']) ?>
                                                                        <?php echo Html::Button(CrmModule::t('crm', 'hang_up'), ['class' => 'btn btn-basic', 'id' => 'hang-up-call', 'disabled' => 'disabled']) ?>
                                                                        <?php echo Html::Button(CrmModule::t('crm', 'hold'), ['class' => 'btn btn-basic hold-unhold', 'id' => 'hold-call', 'disabled' => 'disabled']) ?>
                                                                        <?php echo Html::Button(CrmModule::t('crm', 'mute'), ['class' => 'btn btn-basic mute-unmute', 'id' => 'mute-call', 'disabled' => 'disabled']) ?>
                                                                        <?php echo Html::Button(CrmModule::t('crm', 'transfer_call'), ['class' => 'btn btn-basic transfer-call', 'id' => 'transfer-call', 'disabled' => 'disabled']) ?>
                                                                        <!-- <audio id="audio-container" controls autoplay="autoplay" controlsList="nodownload" playsinline style="visibility: hidden;"></audio>-->
                                                                    </div>
                                                                    <div style="float:right;margin-right: 15px;">
                                                                    </div>
                                                                <?php }
                                                            } ?>
                                                            <div class="row">
                                                                <div style="float:left;">
                                                                    <ul class="tabs" id="tabs">
                                                                        <li class="tab col s6"><a class="crm"
                                                                                                  href="#CRM"
                                                                                                  style="width: 100px;">CRM</a>
                                                                        </li>
                                                                        <li class="tab col s6"><a class="script"
                                                                                                  href="#Script"
                                                                                                  style="width: 100px;">Script</a>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                            <div class="row">
                                                                <div style="float:right;">
                                                                    <audio id="audio-container" controls
                                                                           autoplay="autoplay"
                                                                           controlsList="nodownload" playsinline
                                                                           style="display: none;"></audio>
                                                                    <span class="call-timer" hidden>00:00:00</span>
                                                                    <?php echo Html::Button(CrmModule::t('crm', 'dial_pad'), ['class' => 'btn btn-basic dial-pad', 'id' => 'dial-pad', 'style' => 'margin-right: 12px']) ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="dataTables_wrapper" id="page-length-option_wrapper">
                                                        <div id="CRM" class="col s12">
                                                            <div class="row">
                                                                <div class="col s12">
                                                                    <div id="input-fields" class="card card-tabs">
                                                                        <div class="card-content">
                                                                            <div class="lead-group-member-form"
                                                                                 id="lead-group-member-form">
                                                                                <?php
                                                                                //Pjax::begin();
                                                                                $form = ActiveForm::begin([
                                                                                    'id' => 'lead-ajax-form',
                                                                                    'action' => ['/crm/crm/index'],
//                                                                    'enableAjaxValidation'=>true,
                                                                                    'options' => ['data-pjax' => '#x1'],
//                                                                    'enableClientValidation'=>true
                                                                                ]);
                                                                                ?>

                                                                                <?php if (isset($crmList)) { ?>
                                                                                    <div class="row">
                                                                                        <div class="col s6">
                                                                                            <div class="input-field col s12">
                                                                                                <?= $form->field($crmList, 'lg_first_name')->textInput(['maxlength' => true])->label(CrmModule::t('crm', 'firstname')); ?>

                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col s6">
                                                                                            <div class="input-field col s12">
                                                                                                <?= $form->field($crmList, 'lg_last_name')->textInput(['maxlength' => true])->label(CrmModule::t('crm', 'lastname')); ?>

                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="row">
                                                                                        <div class="col s6">
                                                                                            <div class="input-field col s12">
                                                                                                <?= $form->field($crmList, 'lg_contact_number')->textInput(['maxlength' => true, 'id' => 'lg_contact_number', 'disabled' => 'disabled'])->label(CrmModule::t('crm', 'lg_contact_number')); ?>

                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col s6">
                                                                                            <div class="input-field col s12">
                                                                                                <?= $form->field($crmList, 'lg_contact_number_2')->textInput(['maxlength' => true])->label(CrmModule::t('crm', 'lg_contact_number_2')); ?>

                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="row">
                                                                                        <div class="col s6">
                                                                                            <div class="input-field col s12">
                                                                                                <?= $form->field($crmList, 'lg_email_id')->textInput(['maxlength' => true])->label(CrmModule::t('crm', 'lg_email_id')); ?>

                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col s6">
                                                                                            <div class="input-field col s12">
                                                                                                <?= $form->field($crmList, 'lg_address')->textInput(['maxlength' => true])->label(CrmModule::t('crm', 'lg_address')); ?>

                                                                                            </div>
                                                                                        </div>
                                                                                    </div>

                                                                                    <div class="row">
                                                                                        <div class="col s6">
                                                                                            <div class="input-field col s12">
                                                                                                <?= $form->field($crmList, 'lg_alternate_number')->textInput(['maxlength' => true])->label(CrmModule::t('crm', 'lg_alternate_number')); ?>

                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col s6">
                                                                                            <div class="input-field col s12">
                                                                                                <?= $form->field($crmList, 'lg_pin_code')->textInput(['maxlength' => true])->label(CrmModule::t('crm', 'lg_pin_code')); ?>

                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="row">
                                                                                        <div class="col s6">
                                                                                            <div class="input-field col s12">
                                                                                                <?= $form->field($crmList, 'lg_permanent_address')->textInput(['maxlength' => true])->label(CrmModule::t('crm', 'lg_permanent_address')); ?>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="row">
                                                                                        <div class="col s12">
                                                                                            <div class="input-field col s12">
                                                                                                <?= $form->field($leadCommentMapping, 'comment')->textarea(['rows' => 6, 'class' => 'materialize-textarea'])->label(CrmModule::t('crm', 'comment')); ?>
                                                                                                <!--<input type="hidden" id="pk_id" value=" <? /*= $leadCommentMapping->id; */ ?>" name="LeadCommentMapping[id]">
                                                                            <input type="hidden" id="lg_id" value=" <? /*= $crmList->lg_id; */ ?>" name="LeadCommentMapping[lead_id]">
-->
                                                                                                <?php
                                                                                                echo $form->field(
                                                                                                    $leadCommentMapping,
                                                                                                    'id'
                                                                                                )->hiddenInput(['id' => 'pk_id'])->label(
                                                                                                    FALSE
                                                                                                );

                                                                                                echo $form->field(
                                                                                                    $leadCommentMapping,
                                                                                                    'lead_id'
                                                                                                )->hiddenInput(['id' => 'lg_id'])->label(
                                                                                                    FALSE
                                                                                                );
                                                                                                echo $form->field(
                                                                                                    $crmList,
                                                                                                    'lg_id'
                                                                                                )->hiddenInput(['id' => 'other_lg_id'])->label(
                                                                                                    FALSE
                                                                                                );
                                                                                                ?>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                <?php } else { ?>

                                                                                    <div class="row">
                                                                                        <div class="col s6">
                                                                                            <div class="input-field col s12">
                                                                                                <?= $form->field($progresiveDataList, 'lg_first_name')->textInput(['maxlength' => true])->label(CrmModule::t('crm', 'firstname')); ?>

                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col s6">
                                                                                            <div class="input-field col s12">
                                                                                                <?= $form->field($progresiveDataList, 'lg_last_name')->textInput(['maxlength' => true])->label(CrmModule::t('crm', 'lastname')); ?>

                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="row">
                                                                                        <div class="col s6">
                                                                                            <div class="input-field col s12">
                                                                                                <?= $form->field($progresiveDataList, 'lg_contact_number')->textInput(['maxlength' => true, 'id' => 'lg_contact_number'])->label(CrmModule::t('crm', 'lg_contact_number')); ?>

                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col s6">
                                                                                            <div class="input-field col s12">
                                                                                                <?= $form->field($progresiveDataList, 'lg_contact_number_2')->textInput(['maxlength' => true])->label(CrmModule::t('crm', 'lg_contact_number_2')); ?>

                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="row">
                                                                                        <div class="col s6">
                                                                                            <div class="input-field col s12">
                                                                                                <?= $form->field($progresiveDataList, 'lg_email_id')->textInput(['maxlength' => true])->label(CrmModule::t('crm', 'lg_email_id')); ?>

                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col s6">
                                                                                            <div class="input-field col s12">
                                                                                                <?= $form->field($progresiveDataList, 'lg_address')->textInput(['maxlength' => true])->label(CrmModule::t('crm', 'lg_address')); ?>

                                                                                            </div>
                                                                                        </div>
                                                                                    </div>

                                                                                    <div class="row">
                                                                                        <div class="col s6">
                                                                                            <div class="input-field col s12">
                                                                                                <?= $form->field($progresiveDataList, 'lg_alternate_number')->textInput(['maxlength' => true])->label(CrmModule::t('crm', 'lg_alternate_number')); ?>

                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="col s6">
                                                                                            <div class="input-field col s12">
                                                                                                <?= $form->field($progresiveDataList, 'lg_pin_code')->textInput(['maxlength' => true])->label(CrmModule::t('crm', 'lg_pin_code')); ?>

                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="row">
                                                                                        <div class="col s6">
                                                                                            <div class="input-field col s12">
                                                                                                <?= $form->field($progresiveDataList, 'lg_permanent_address')->textInput(['maxlength' => true])->label(CrmModule::t('crm', 'lg_permanent_address')); ?>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="row">
                                                                                        <div class="col s12">
                                                                                            <div class="input-field col s12">
                                                                                                <?= $form->field($leadCommentMapping, 'comment')->textarea(['rows' => 6, 'class' => 'materialize-textarea'])->label(CrmModule::t('crm', 'comment')); ?>
                                                                                                <!--<input type="hidden" id="pk_id" value=" <? /*= $leadCommentMapping->id; */ ?>" name="LeadCommentMapping[id]">
                                                                            <input type="hidden" id="lg_id" value=" <? /*= $progresiveDataList->lg_id; */ ?>" name="LeadCommentMapping[lead_id]">
-->
                                                                                                <?php
                                                                                                echo $form->field(
                                                                                                    $leadCommentMapping,
                                                                                                    'id'
                                                                                                )->hiddenInput(['id' => 'pk_id'])->label(
                                                                                                    FALSE
                                                                                                );

                                                                                                echo $form->field(
                                                                                                    $leadCommentMapping,
                                                                                                    'lead_id'
                                                                                                )->hiddenInput(['id' => 'lg_id'])->label(
                                                                                                    FALSE
                                                                                                );
                                                                                                echo $form->field(
                                                                                                    $progresiveDataList,
                                                                                                    'lg_id'
                                                                                                )->hiddenInput(['id' => 'other_lg_id'])->label(
                                                                                                    FALSE
                                                                                                );
                                                                                                ?>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>

                                                                                <?php } ?>

                                                                                <div class="hseparator"></div>

                                                                                <div class="col s12 center">
                                                                                    <div class="input-field col s12 mrg-btn "
                                                                                         id="submitbtn"
                                                                                         style="display: none">

                                                                                        <?= Html::submitButton(CrmModule::t('crm', 'update'), [
                                                                                            'class' => 'btn waves-effect waves-light amber darken-4',
                                                                                            'name' => 'Update',
                                                                                            'value' => 'update']) ?>
                                                                                        <?php echo Html::Button(CrmModule::t('crm', 'cancel'), ['class' => 'btn waves-effect waves-light amber darken-4 cancel']) ?>
                                                                                    </div>
                                                                                </div>
                                                                                <?php
                                                                                ActiveForm::end();
                                                                                //                                                                Pjax::end();
                                                                                ?>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div id="Script" class="col s12">
                                                            Loading ...
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Trigger -->
                <!--<a class="waves-effect waves-light btn modal-trigger" href="#modal1">Modal</a>-->

                <!-- Disposition Screen Start --------------------------------------->
                <div id="modal1" class="modal">
                    <div class="modal-content">
                        <!--<span aria-hidden="true" class="close">&times;</span>-->
                        <center>
                            <h5><?php echo CrmModule::t('crm', 'dispo_screen') ?></h5>
                        </center>
                        <div class="disposition-form" id="disposition-form">
                            <?php $form = ActiveForm::begin([
                                'class' => 'row',
                                'id' => 'submit-disposition-form',
                                'action' => ['/crm/crm/submit-disposition'],
                                'fieldConfig' => [
                                    'options' => [
                                        'class' => 'input-field col s12'
                                    ],
                                ],
                            ]); ?>

                            <div class="row">
                                <div class="col s12">
                                    <div class="input-field col s12" id="week_off_queue">
                                        <div class="select-wrapper">
                                            <?php
                                            echo $form->field($agentDispoMapping, 'disposition', ['options' => ['class' => '', 'id' => 'disposition_type']])->dropDownList($disposionListType, ['prompt' => CrmModule::t('crm', 'select_dispo_screen')])->label(CrmModule::t('crm', 'disposition'));
                                            ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="col s12">
                                    <div class="input-field col s12">
                                        <?= $form->field($agentDispoMapping, 'comment')->textarea(['rows' => 6, 'class' => 'materialize-textarea', 'id' => 'disposition_desc', 'placeholder' => CrmModule::t('crm', 'comment')])->label(CrmModule::t('crm', 'comment')); ?><?php
                                        echo $form->field(
                                            $agentDispoMapping,
                                            'lead_id'
                                        )->hiddenInput(['id' => 'lg_id1'])->label(FALSE);
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <div class="hseparator"></div>
                            <div class="col s12 center">
                                <div class="input-field col s12 mrg-btn">
                                    <?= Html::submitButton(CrmModule::t('crm', 'submit'), [
                                        'class' => 'btn waves-effect waves-light darken-4 ml-2',
                                        'name' => 'apply', 'id' => 'dis-sub', 'disabled' => true,
                                        'value' => 'update']) ?>
                                </div>
                                <?php ActiveForm::end(); ?>
                                <!--     <div class="input-field col s6 mrg-btn">
                    <? /*= Html::submitButton(Yii::t('app', 'Submit'), [
                        'class'=>'btn waves-effect waves-light amber darken-4',
                        'name'=>'apply',
                        'value'=>'update']) */ ?>
                </div>-->
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Disposition Screen End  -------------------------------  -->

                <!-- Incoming Call Start  -------------------------------------->

                <div id="incomingCall" class="modal">
                    <div class="modal-content">
                        <!--<span aria-hidden="true" class="close">&times;</span>-->
                       <div class="modal-header">
                            <h5><?php echo CrmModule::t('crm', 'incoming_screen') ?></h5>
                        </div>
                        <?php $form = ActiveForm::begin([
                            'class' => 'row',
                            'id' => 'incoming-call-form',
                            //'action'=>['/crm/crm/submit-disposition'],
                            'fieldConfig' => [
                                'options' => [
                                    'class' => 'input-field'
                                ],
                            ],
                        ]); ?>
                        <div class="modal-body">
                            <div class="incoming-call-form" id="incoming-call-form">
                            <div class="row">
                                <div class="col s12">
                                    <div class="input-field">
                                        <?= $form->field($agentDispoMapping, 'comment')
                                            ->textInput(['maxlength' => TRUE, 'autofocus' => TRUE, 'id' => 'caller_id_number', 'disabled' => 'disabled'])
                                            ->label(Yii::t('app', 'caller_id_number')); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <?php echo Html::Button(CrmModule::t('crm', 'accept'), ['class' => 'btn btn-basic acceptCall', 'id' => 'acceptCall']) ?>
                            <?php echo Html::Button(CrmModule::t('crm', 'reject'), ['class' => 'btn btn-basic rejectCall', 'id' => 'rejectCall']) ?>
                            <button type="button"
                                    class="btn btn-default modal-close"><?= CrmModule::t('crm', 'close') ?></button>

                        </div>
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
                <!-- End Incoming Call ------------------------------------------->

                <!-- Transfer Call Start -->

                <div id="transferCallModal" class="modal">
                    <div class="modal-content">
                        <?php $form = ActiveForm::begin([
                            'class' => 'row',
                            'id' => 'transfer-call-form',
                            //'action'=>['/crm/crm/submit-disposition'],
                            'fieldConfig' => [
                                'options' => [
                                    'class' => 'input-field'
                                ],
                            ],
                        ]); ?>
                        <div class="modal-body">
                            <div class="tranfer-call-form" id="incoming-call-form">
                                <div class="row">
                                    <div class="col s12">
                                        <div class="input-field">
                                            <?= $form->field($agentDispoMapping, 'comment')
                                                ->textInput(['maxlength' => TRUE, 'autofocus' => TRUE, 'id' => 'destination-number'])
                                                ->label(CrmModule::t('crm', 'extension_number')); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <?php echo Html::Button(CrmModule::t('crm', 'transfer'), ['class' => 'btn btn-basic acceptCall', 'id' => 'transferCallBtn']) ?>
                            <button type="button"
                                    class="btn btn-default modal-close"><?= CrmModule::t('crm', 'close') ?></button>
                        </div>
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>

                <div id="dialPadModal" class="modal">
                    <div class="modal-content">
                        <span aria-hidden="true" class="close">&times;</span>
                        <div class="container2">
                            <input type="text" id="output" class="dialer-input">
                            <div class="row">
                                <div class="digit" id="one">1</div>
                                <div class="digit" id="two">2
                                    <div class="sub">ABC</div>
                                </div>
                                <div class="digit" id="three">3
                                    <div class="sub">DEF</div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="digit" id="four">4
                                    <div class="sub">GHI</div>
                                </div>
                                <div class="digit" id="five">5
                                    <div class="sub">JKL</div>
                                </div>
                                <div class="digit">6
                                    <div class="sub">MNO</div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="digit">7
                                    <div class="sub">PQRS</div>
                                </div>
                                <div class="digit">8
                                    <div class="sub">TUV</div>
                                </div>
                                <div class="digit">9
                                    <div class="sub">WXYZ</div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="digit">*
                                </div>
                                <div class="digit">0
                                </div>
                                <div class="digit">#
                                </div>
                            </div>
                            <div class="botrow">
                                <div id="call"><?= CrmModule::t('crm', 'call') ?></div>
                                <div id="clear"><?= CrmModule::t('crm', 'clear') ?></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Transfer Call End---------------------------------------------------  -->
                <script type="text/javascript">
                    $(document).ready(function (events) {
                        //$("#login-sections").show();
                        $('ul.tabs').tabs();
                        //events.preventDefault();
                        $('#dialPadModal').modal();
                    });
                    $(document).ready(function () {
                        $("ul.tabs > li > a").click(function (e) {
                            //e.preventDefault();
                            var id = $(e.target).attr("href").slice(1);
                            window.location.hash = id;
                        });
                        var hash = window.location.hash;
                        $('ul.tabs').tabs('select', hash);
                    });

                    $(document).on("click", "#dial-pad", function () {
                        $('#dialPadModal').modal('open');
                    });
                    $(document).on("click", ".close", function () {
                        $('#dialPadModal').modal('close');
                    });
                </script>


            </div>
        </div>
    </div>
</div>



