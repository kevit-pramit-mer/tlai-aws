<?php

use app\modules\ecosmob\agent\models\Agent;
use app\modules\ecosmob\agents\models\AdminMaster;
use app\modules\ecosmob\campaign\models\Campaign;
use app\modules\ecosmob\crm\models\AgentDispositionMapping;
use app\modules\ecosmob\crm\models\LeadCommentMapping;
use app\modules\ecosmob\crm\models\LeadGroupMember;
use app\modules\ecosmob\crm\models\LeadGroupMemberSearch;
use app\modules\ecosmob\dispositionType\models\DispositionType;
use app\modules\ecosmob\extension\models\Extension;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use app\modules\ecosmob\supervisor\models\BreakReasonMapping;

$admin = AdminMaster::findOne(Yii::$app->user->id);
$extensionObj = Extension::findOne(['em_id' => $admin->adm_mapped_extension]);
$extensionNumber = $extensionObj ? $extensionObj->em_extension_number : '';
Yii::$app->session->set('extentationNumber', $extensionNumber);
?>
<script>
    //var urlMute = '<?php //= Yii::getAlias('@web') . "/theme/assets/images/media/images/mute.png" ?>//';
    //var urlUmute = '<?php //= Yii::getAlias('@web') . "/theme/assets/images/media/images/unmute.png" ?>//';
    //var EnableUnmute = '<?php //= Yii::getAlias('@web') . "/theme/assets/images/media/images/unmute.png" ?>//';
    //var urlHold = '<?php //= Yii::getAlias('@web') . "/theme/assets/images/media/images/hold.png" ?>//';
    //var urlUnHold = '<?php //= Yii::getAlias('@web') . "/theme/assets/images/media/images/unhold.png" ?>//';
    //var urlDisableUnHold = '<?php //= Yii::getAlias('@web') . "/theme/assets/images/media/images/unhold.png" ?>//';
    //var urlDialnext = '<?php //= Yii::getAlias('@web') . "/theme/assets/images/media/images/dial-next.png" ?>//';
    //var urlDisableDialnext = '<?php //= Yii::getAlias('@web') . "/theme/assets/images/media/images/disable-dialnext.png" ?>//';
    //var urlHangup = '<?php //= Yii::getAlias('@web') . "/theme/assets/images/media/images/hang-up.png" ?>//';
    //var urlDisableHangup = '<?php //= Yii::getAlias('@web') . "/theme/assets/images/media/images/hangup-disable.png" ?>//';
    //var urlTransfercall = '<?php //= Yii::getAlias('@web') . "/theme/assets/images/media/images/transfer-call.png" ?>//';
    //var urlDisableTransfercall = '<?php //= Yii::getAlias('@web') . "/theme/assets/images/media/images/disable-transfer-call.png" ?>//';
    //var urlPause = '<?php //= Yii::getAlias('@web') . "/theme/assets/images/media/images/pause.png" ?>//';
    //var urlDisablePause = '<?php //= Yii::getAlias('@web') . "/theme/assets/images/media/images/pause-disable.png" ?>//';
    //var urlResume = '<?php //= Yii::getAlias('@web') . "/theme/assets/images/media/images/resume.png" ?>//';
    //var urlDisableResume = '<?php //= Yii::getAlias('@web') . "/theme/assets/images/media/images/resume-disable.png" ?>//';
    //
    //var titleUnhold = "<?php //echo Yii::t('app', 'hold'); ?>//";
    //var titlehold = "<?php //echo Yii::t('app', 'unhold'); ?>//";
    //
    //var titleMute = "<?php //echo Yii::t('app', 'unmute'); ?>//";
    //var titleUnmute = "<?php //echo Yii::t('app', 'mute'); ?>//";

    var urlDisablePause = '<?php Yii::getAlias('@web') . "/theme/assets/images/call-icons/pause-disabled.png" ?>';
    var urlDisableResume = '<?php Yii::getAlias('@web') . "/theme/assets/images/call-icons/resume-disabled.png" ?>';

</script>

<?php
echo $this->render('webrtc');
$selectedCampaignData = (isset($_SESSION['selectedCampaign']) && !empty($_SESSION['selectedCampaign'])) ? $_SESSION['selectedCampaign'] : '0';
if ($selectedCampaignData > 0) {

    $selectedCampaign = $selectedCampaignData;
    $agentId = Yii::$app->user->identity->adm_id;

    $leadCommentMapping = new LeadCommentMapping();
    $crmList = new LeadGroupMember();
    $progresiveDataList = new LeadGroupMember();
    $agentDispoMapping = new AgentDispositionMapping();
    $campaignDialerType = Campaign::find()->select(['cmp_dialer_type', 'cmp_type'])->where(['cmp_id' => $selectedCampaign])->one();


    $selectedCampaignData = (isset($_SESSION['selectedCampaign']) && !empty($_SESSION['selectedCampaign'])) ? $_SESSION['selectedCampaign'] : '0';

    $selectedCampaign = $selectedCampaignData;
    $agentId = Yii::$app->user->identity->adm_id;

    $campaignDialerType = Campaign::find()->select(['cmp_dialer_type', 'cmp_type'])->where(['cmp_id' => $selectedCampaign])->one();

    if ($campaignDialerType->cmp_type == 'Outbound' && $campaignDialerType->cmp_dialer_type == 'PREVIEW') {
        $dispotionData = Campaign::find()->select(['cmp_disposition'])->where(['cmp_id' => $selectedCampaign])->one();
        $dispotionData = isset($dispotionData) ? $dispotionData : '';
    } else if ($campaignDialerType->cmp_type == 'Outbound' && $campaignDialerType->cmp_dialer_type == 'PROGRESSIVE') {
        $dispotionData = Campaign::find()->select(['cmp_disposition'])->where(['cmp_id' => $selectedCampaign])->one();
        $dispotionData = isset($dispotionData) ? $dispotionData : '';
    } else if ($campaignDialerType->cmp_type == 'Blended' && $campaignDialerType->cmp_dialer_type == 'AUTO') {
        $dispotionData = Campaign::find()->select(['cmp_disposition'])->where(['cmp_id' => $selectedCampaign])->one();
        $dispotionData = isset($dispotionData) ? $dispotionData : '';
    } else if ($campaignDialerType->cmp_type == 'Inbound') {

        $dispotionData = Campaign::find()->select(['cmp_disposition'])->where(['cmp_id' => $selectedCampaign])->one();
        $dispotionData = isset($dispotionData) ? $dispotionData : '';
    }
    $dispotionData = isset($dispotionData) ? $dispotionData : '';

    $disposionList = DispositionType::find()->select(['ds_type_id'])
        ->from('ct_disposition_type cdt')
        ->innerJoin('ct_disposition_group_status_mapping dgm','dgm.ds_status_id = cdt.ds_type_id')
        ->where(['dgm.ds_group_id' => $dispotionData->cmp_disposition])
        ->asArray()->all();

    //$disposionList = DispositionType::find()->select(['ds_type_id'])->where(['ds_id' => $dispotionData])->asArray()->all();
    $disposionList = isset($disposionList) ? $disposionList : '';

    $disposionIds = implode(",", array_map(function ($a) {
        return implode("~", $a);
    }, $disposionList));

    $disposionData = DispositionType::find()->select(['ds_type_id', 'ds_type'])
        ->andWhere(new Expression('FIND_IN_SET(ds_type_id,"' . $disposionIds . '")'))
        ->asArray()->all();

    $disposionListType = ArrayHelper::map($disposionData, 'ds_type_id', 'ds_type');

    $searchModel = new LeadGroupMemberSearch();
    $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

    $scrdataProvider = $searchModel->scrsearch(Yii::$app->request->queryParams);
    $extentationNumber = $_SESSION['extentationNumber'];
    $extensionInformation = Extension::find()->select(['em_extension_number', 'em_password', 'em_extension_name'])->where(['em_extension_number' => $extentationNumber])->one();

    $crmList = new LeadGroupMember();
    $progresiveDataList = new LeadGroupMember();
    $leadCommentMapping = new LeadCommentMapping();
    $agentDispoMapping = new AgentDispositionMapping();

    $selectedCampaign = $_SESSION['selectedCampaign'];

    $campaignData = Campaign::find()->select(['cmp_caller_id', 'cmp_caller_name'])->where(['cmp_id' => $selectedCampaign])->asArray()->one();

    $searchModel = $searchModel;
    $crmList = $crmList;
    $progresiveDataList = $progresiveDataList;
    $leadCommentMapping = $leadCommentMapping;
    $dataProvider = $dataProvider;
    $scrdataProvider = $scrdataProvider;
    $extensionInformation = $extensionInformation;
    $campaignDialerType = $campaignDialerType;
    $disposionListType = $disposionListType;
    $agentDispoMapping = $agentDispoMapping;
    $selectedCampaign = $selectedCampaign;
    $agentId = $agentId;
    $campaignData = $campaignData;
} else {
    return false;
}

?>
<div class="header-opt-section">
    <ul class="navbar-list call-icons-group">
        <div class="nav-crm-icon with-audio">

            <?php
            if (isset($campaignDialerType) && !empty($campaignDialerType)) {
                if ($campaignDialerType->cmp_dialer_type == 'PREVIEW') {
                    $data = Agent::find()->where(['name' => Yii::$app->user->identity->adm_id.'_'.$GLOBALS['tenantID'], 'status' => 'Available'])->count();
                    $is_in = 0;
                    if ($data) {
                        $is_in = 1;
                    }
                    ?>
                    <!-- ul class="navbar-list right">
                    <li -->

                    <img id="CRM" class="cursor"
                         src="<?= Yii::getAlias('@web') . "/theme/assets/images/call-icons/CRM-icon.svg" ?>"
                         alt="<?php echo Yii::t('app', 'crm'); ?>"
                         title="<?php echo Yii::t('app', 'crm'); ?>">
                    <img id="script" class="cursor"
                         src="<?= Yii::getAlias('@web') . "/theme/assets/images/call-icons/script-icon.svg" ?>"
                         alt="<?php echo Yii::t('app', 'script'); ?>" title="<?php echo Yii::t('app', 'script'); ?>">

                    <img id="dialNext" class="dialnext cursor"
                         src="<?= Yii::getAlias('@web') . "/theme/assets/images/call-icons/dialnext-enabled.png" ?>"
                         alt="<?php echo Yii::t('app', 'dial_next'); ?>"
                         title="<?php echo Yii::t('app', 'dial_next'); ?>">

                    <img id="disable-dialnext" class="d-none"
                         src="<?= Yii::getAlias('@web') . "/theme/assets/images/call-icons/dialnext-disabled.png" ?>"
                         alt="<?php echo Yii::t('app', 'dial_next'); ?>"
                         title="<?php echo Yii::t('app', 'dial_next'); ?>">

                    <img id="hang-up-call" class="cursor d-none active"
                         src="<?= Yii::getAlias('@web') . "/theme/assets/images/call-icons/hangup-enabled.png" ?>"
                         alt="<?php echo Yii::t('app', 'hang_up'); ?>"
                         title="<?php echo Yii::t('app', 'hang_up'); ?>">

                    <img id="hold-call" class="unhold cursor d-none"
                         src="<?= Yii::getAlias('@web') . "/theme/assets/images/call-icons/hold-enabled.png" ?>"
                         alt="<?php echo Yii::t('app', 'unhold'); ?>" title="<?php echo Yii::t('app', 'unhold'); ?>">
                    <img id="unhold-call" class="hold cursor d-none"
                         src="<?= Yii::getAlias('@web') . "/theme/assets/images/call-icons/unhold-enabled.png" ?>"
                         alt="<?php echo Yii::t('app', 'hold'); ?>" title="<?php echo Yii::t('app', 'hold'); ?>">


                    <img id="mute-call" class="mute cursor d-none"
                         src="<?= Yii::getAlias('@web') . "/theme/assets/images/call-icons/unmute-enabled.png" ?>"
                         alt="<?php echo Yii::t('app', 'mute'); ?>" title="<?php echo Yii::t('app', 'mute'); ?>">
                    <img id="unmute-call" class="unmute cursor d-none"
                         src="<?= Yii::getAlias('@web') . "/theme/assets/images/call-icons/mute-enabled.png" ?>"
                         alt="<?php echo Yii::t('app', 'unmute'); ?>" title="<?php echo Yii::t('app', 'unmute'); ?>"
                         disabled="disabled">

                    <img id="transfer-call" class="transfer-call cursor d-none"
                         src="<?= Yii::getAlias('@web') . "/theme/assets/images/call-icons/transfer-enabled.png" ?>"
                         alt="<?php echo Yii::t('app', 'transfer_call'); ?>"
                         title="<?php echo Yii::t('app', 'transfer_call'); ?>" disabled="disabled">


                    <img id="dial-pad" class="dial-pad cursor"
                         src="<?= Yii::getAlias('@web') . "/theme/assets/images/call-icons/dialpad-enabled.png" ?>"
                         alt="<?php echo Yii::t('app', 'dial_pad'); ?>"
                         title="<?php echo Yii::t('app', 'dial_pad'); ?>" />

                    <img id="disabled-dial-pad" class="d-none"
                         src="<?= Yii::getAlias('@web') . "/theme/assets/images/call-icons/dialpad-disabled.png" ?>"
                         alt="<?php echo Yii::t('app', 'dial_pad'); ?>"
                         title="<?php echo Yii::t('app', 'dial_pad'); ?>"/>

                    <span class="call-timer" hidden>00:00:00</span>
                    <div class="audio-class">
                        <audio id="audio-container" controls autoplay="autoplay"
                               controlsList="nodownload" playsinline
                               style="visibility: hidden"></audio>
                        <audio id="ring-audio-container" style="visibility: hidden"></audio>
                        <?php //echo Html::Button('dial_pad', ['class' => 'btn btn-basic dial-pad', 'id' => 'dial-pad', 'style' => 'margin-right: 12px']) ?>
                    </div>


                    <!-- /li>
                </ul -->
                <?php } else {
                    $data = Agent::find()->where(['name' => Yii::$app->user->identity->adm_id.'_'.$GLOBALS['tenantID'], 'status' => 'Available'])->count();
                    $is_in = 0;
                    if ($data) {
                        $is_in = 1;
                    }
                    ?>


                    <img id="CRM" class="cursor" src="<?= Yii::getAlias('@web') . "/theme/assets/images/call-icons/CRM-icon.svg" ?>"
                         alt="<?php echo Yii::t('app', 'crm'); ?>"
                         title="<?php echo Yii::t('app', 'crm'); ?>"/>
                    <img id="script" class="cursor"
                         src="<?= Yii::getAlias('@web') . "/theme/assets/images/call-icons/script-icon.svg" ?>"
                         alt="<?php echo Yii::t('app', 'script'); ?>" title="<?php echo Yii::t('app', 'script'); ?>"/>

                    <?php $pause = (!$is_in) ? 'd-none' : ''; ?>
                    <?php $resume = ($is_in) ? 'd-none' : ''; ?>
                    <?php if($campaignDialerType->cmp_type != 'Inbound'){?>
                        <img id="disable-pause" class="d-none"
                             src="<?= Yii::getAlias('@web') . "/theme/assets/images/call-icons/resume-disabled.png" ?>"
                             alt="<?php echo Yii::t('app', 'pause'); ?>" title="<?php echo Yii::t('app', 'pause'); ?>"
                             style="<?php echo $pause; ?>">
                        <img id="disable-resume" class="d-none"
                             src="<?= Yii::getAlias('@web') . "/theme/assets/images/call-icons/pause-disabled.png" ?>"
                             alt="<?php echo Yii::t('app', 'pause'); ?>" title="<?php echo Yii::t('app', 'pause'); ?>"
                             style="<?php echo $pause; ?>">

                        <img id="pause" class="pause cursor <?=$pause?>"
                             src="<?= Yii::getAlias('@web') . "/theme/assets/images/call-icons/resume-enabled.png" ?>"
                             alt="<?php echo Yii::t('app', 'pause'); ?>" title="<?php echo Yii::t('app', 'pause'); ?>"
                        >
                        <img id="resume" class="resume cursor <?=$resume?>"
                             src="<?= Yii::getAlias('@web') . "/theme/assets/images/call-icons/pause-enabled.png" ?>"
                             alt="<?php echo Yii::t('app', 'resume'); ?>"
                             title="<?php echo Yii::t('app', 'resume'); ?>">
                    <?php } ?>

                    <img id="hang-up-call" class="cursor d-none active"
                         src="<?= Yii::getAlias('@web') . "/theme/assets/images/call-icons/hangup-enabled.png" ?>"
                         alt="<?php echo Yii::t('app', 'hang_up'); ?>"
                         title="<?php echo Yii::t('app', 'hang_up'); ?>">


                    <img id="hold-call" class="unhold cursor d-none"
                         src="<?= Yii::getAlias('@web') . "/theme/assets/images/call-icons/hold-enabled.png" ?>"
                         alt="<?php echo Yii::t('app', 'unhold'); ?>" title="<?php echo Yii::t('app', 'unhold'); ?>">
                    <img id="unhold-call" class="hold cursor d-none"
                         src="<?= Yii::getAlias('@web') . "/theme/assets/images/call-icons/unhold-enabled.png" ?>"
                         alt="<?php echo Yii::t('app', 'hold'); ?>" title="<?php echo Yii::t('app', 'hold'); ?>">


                    <img id="mute-call" class="mute cursor d-none"
                         src="<?= Yii::getAlias('@web') . "/theme/assets/images/call-icons/unmute-enabled.png" ?>"
                         alt="<?php echo Yii::t('app', 'mute'); ?>" title="<?php echo Yii::t('app', 'mute'); ?>">
                    <img id="unmute-call" class="unmute cursor d-none"
                         src="<?= Yii::getAlias('@web') . "/theme/assets/images/call-icons/mute-enabled.png" ?>"
                         alt="<?php echo Yii::t('app', 'unmute'); ?>" title="<?php echo Yii::t('app', 'unmute'); ?>"
                         disabled="disabled">

                    <img id="transfer-call" class="transfer-call cursor d-none"
                         src="<?= Yii::getAlias('@web') . "/theme/assets/images/call-icons/transfer-enabled.png" ?>"
                         alt="<?php echo Yii::t('app', 'transfer_call'); ?>"
                         title="<?php echo Yii::t('app', 'transfer_call'); ?>" disabled="disabled">

                    <img id="dial-pad" class="dial-pad cursor"
                         src="<?= Yii::getAlias('@web') . "/theme/assets/images/call-icons/dialpad-enabled.png" ?>"
                         alt="<?php echo Yii::t('app', 'dial_pad'); ?>"
                         title="<?php echo Yii::t('app', 'dial_pad'); ?>" />
                    <img id="disabled-dial-pad" class="d-none"
                         src="<?= Yii::getAlias('@web') . "/theme/assets/images/call-icons/dialpad-disabled.png" ?>"
                         alt="<?php echo Yii::t('app', 'dial_pad'); ?>"
                         title="<?php echo Yii::t('app', 'dial_pad'); ?>"/>

                    <span class="call-timer" hidden>00:00:00</span>
                    <div class="audio-class">
                        <audio id="audio-container" controls autoplay="autoplay"
                               controlsList="nodownload" playsinline
                               style="visibility: hidden"></audio>
                        <audio id="ring-audio-container" style="visibility: hidden"></audio>
                    </div>
                <?php }
            } ?>

        </div>
    </ul>
    <div class="profile-header-section">
        <ul class="navbar-list  extention-show">
            <?php
            if (!Yii::$app->session->get('loginAsExtension')) {
                if (Yii::$app->user->identity->adm_is_admin == 'agent' && isset($_SESSION['extentationNumber'])) { ?>
                    <li>
                        <?//= 'Extension: ' . $_SESSION['extentationNumber']; ?>
                        <?= Yii::t('app', 'agentextension') . $_SESSION['extentationNumber']; ?>
                    </li>
                <?php }
            } ?>
        </ul>
        <ul class="navbar-list">
            <li>
                <?php if (Yii::$app->session->hasFlash('success')) : ?>

                    <div class="col s4 right alert set-alert-theme fixed-alert" role="alert">
                        <div class="card-alert card gradient-45deg-green-teal mt-1">
                            <div class="row">
                                <div class="col s10">
                                    <div class="card-content white-text">
                                        <p>
                                            <i class="material-icons">error</i><?= Yii::$app->session->getFlash('success') ?>
                                        </p>
                                    </div>
                                </div>
                                <div class="col s2">
                                    <button type="button" class="close white-text" data-dismiss="alert"
                                            aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php endif; ?>

                <?php if (Yii::$app->session->hasFlash('successimport')) : ?>

                    <div class="col s4 right info set-alert-theme fixed-alert" role="alert">
                        <div class="card-alert card gradient-45deg-green-teal mt-1">
                            <div class="row">
                                <div class="col s12">
                                    <div class="card-content white-text">
                                        <p>
                                            <i class="material-icons">error</i><?= Yii::$app->session->getFlash('successimport') ?>
                                        </p>
                                    </div>
                                </div>
                                <div class="col s2">
                                    <button type="button" class="close white-text" data-dismiss="alert"
                                            aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php endif; ?>


                <?php if (Yii::$app->session->hasFlash('error')) : ?>

                    <div class="col s4 right alert set-alert-theme fixed-alert" role="alert">
                        <div class="card-alert card gradient-45deg-red-pink mt-1">
                            <div class="row">
                                <div class="col s10">
                                    <div class="card-content white-text">
                                        <p>
                                            <i class="material-icons">error</i><?= Yii::$app->session->getFlash('error') ?>
                                        </p>
                                    </div>
                                </div>
                                <div class="col s2">
                                    <button type="button" class="close white-text" data-dismiss="alert"
                                            aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php endif; ?>

                <?php if (Yii::$app->session->hasFlash('errorimport')) : ?>

                    <div class="col s4 right info set-alert-theme" role="alert">
                        <div class="card-alert card gradient-45deg-red-pink mt-1">
                            <div class="row">
                                <div class="col s12">
                                    <div class="card-content white-text">
                                        <p>
                                            <i class="material-icons">error</i><?= Yii::$app->session->getFlash('errorimport') ?>
                                        </p>
                                    </div>
                                </div>
                                <div class="col s2">
                                    <button type="button" class="close white-text" data-dismiss="alert"
                                            aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php endif; ?>
                <?php if (Yii::$app->session->hasFlash('extMessage')) : ?>

                    <div class="col s4 right extensionmsg set-alert-theme fixed-alert" role="alert">
                        <div class="card-alert card gradient-45deg-green-teal mt-1"
                             style="max-width: 859px;">
                            <div class="row">
                                <div class="col s12">
                                    <div class="card-content white-text">
                                        <p>
                                            <i class="material-icons">error</i><?= Yii::$app->session->getFlash('extMessage') ?>
                                        </p>
                                    </div>
                                </div>
                                <div class="col s2">
                                    <button type="button" class="close white-text" data-dismiss="alert"
                                            aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php endif; ?>
            </li>
            <!-- <li class="">
                <a class="waves-effect waves-block waves-light notification-button" href="javascript:void(0);"
                    data-target="notification-dropdown">
                    <i class="material-icons">notifications_none</i>
                    <div class="notification-badge">10</div>
                </a>
            </li> -->
            <li class="hide-on-med-and-down">
                <a class="waves-effect waves-block waves-light translation-button" href="javascript:void(0);"
                   data-target="translation-dropdown">
                    <?php if (Yii::$app->language == 'es-ES') { ?>
                        <span class="flag-icon flag-icon-es"></span>
                    <?php } else { ?>
                        <span class="flag-icon flag-icon-gb"></span>
                    <?php } ?>
                </a></li>
            <li class="hide-on-med-and-down"><a class="waves-effect waves-block waves-light toggle-fullscreen"
                                                href="javascript:void(0);"><i
                            class="material-icons">settings_overscan</i></a>
            </li>
            <li>
                <a class="waves-effect waves-block waves-light profile-button" href="javascript:void(0);"
                   data-target="profile-dropdown">
                    <?php
                    if (Yii::$app->session->get('loginAsExtension')) {
                        echo Yii::$app->user->identity->em_extension_name . ' - ' . Yii::$app->user->identity->em_extension_number;
                    } else {
                        echo Yii::$app->user->identity->adm_firstname . ' ' . Yii::$app->user->identity->adm_lastname;
                    }
                    ?>
                </a>
            </li>
        </ul>
        <ul class="dropdown-content" id="notification-dropdown">
            <li class="set-padding">You have 6 new notifications</li>
            <div class="notification-box">
                <li>
                    <a href="#" class="notification-set">
                        <div class="icon-set-notification">
                            <i class="material-icons">check</i>
                        </div>
                        <div class="notification-content">
                            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
                            <p class="notification-time">July 22, 2024 6:55 PM </p>
                        </div>
                    </a>
                </li>    
                <li>
                    <a href="#" class="notification-set">
                        <div class="icon-set-notification">
                            <i class="material-icons">close</i>
                        </div>
                        <div class="notification-content">
                            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
                            <p class="notification-time">July 22, 2024 6:55 PM </p>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="#" class="notification-set">
                        <div class="icon-set-notification">
                            <i class="material-icons">check</i>
                        </div>
                        <div class="notification-content">
                            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
                            <p class="notification-time">July 22, 2024 6:55 PM </p>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="#" class="notification-set">
                        <div class="icon-set-notification">
                            <i class="material-icons">check</i>
                        </div>
                        <div class="notification-content">
                            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
                            <p class="notification-time">July 22, 2024 6:55 PM </p>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="#" class="notification-set">
                        <div class="icon-set-notification">
                            <i class="material-icons">check</i>
                        </div>
                        <div class="notification-content">
                            <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
                            <p class="notification-time">July 22, 2024 6:55 PM </p>
                        </div>
                    </a>
                </li>
            </div>
            <li>
                <a href="#">See All Notification</a>
            </li>    
        </ul>
        <ul class="dropdown-content" id="translation-dropdown">
            <li><a class="grey-text text-darken-1 id-lang"
                   href="<?= Url::to(['/site/change-language', 'lang' => 'en-US', 'ext' => 0]) ?>"
                   data-value="en-US"><i class="flag-icon flag-icon-gb"></i> English</a></li>
            <li><a class="grey-text text-darken-1 id-lang"
                   href="<?= Url::to(['/site/change-language', 'lang' => 'es-ES', 'ext' => 0]) ?>"
                   data-value="es-ES"><i class="flag-icon flag-icon-es"></i> Spanish</a></li>
        </ul>

        <ul class="dropdown-content" id="profile-dropdown">
            <?php if (!Yii::$app->session->get('loginAsExtension')) { ?>
                <li><a class="grey-text text-darken-1 remove-active-class"
                       href="<?= Url::to(['/admin/admin/customupdate-profile']) ?>"
                       target="myFrame"><i
                                class="material-icons">person_outline</i> <?= Yii::t('app',
                            'profile') ?></a></li>
                <li class="divider"></li>
            <?php } else { ?>
                <li><a class="grey-text text-darken-1 remove-active-class"
                       href="<?= Url::to(['/admin/admin/customupdate-profile']) ?>"><i
                                class="material-icons">person_outline</i> <?= Yii::t('app',
                            'profile') ?></a></li>
                <li class="divider"></li>

            <?php } ?>
            <li><a class="grey-text text-darken-1 remove-active-class"
                   href="<?= Url::to(['/admin/admin/customchange-password']) ?>"
                   target="myFrame"><i
                            class="material-icons">vpn_key</i> <?= Yii::t('app',
                        'change_password') ?></a></li>
            <li class="divider"></li>
            <li>
                <!--<a class="grey-text text-darken-1" href="user-login.html"><i class="material-icons">keyboard_tab</i> Logout</a>-->
                <!-- a class="grey-text text-darken-1"
                href="<?php echo Url::to(['/auth/auth/logout']) ?>" data-method="post"
                role="button"><i class="material-icons">keyboard_tab</i> <?= Yii::t('app', 'logout') ?></a -->
                <a id="user_logout" class="grey-text text-darken-1"
                   href="javascript:void(0);" data-method="post"
                   role="button"><i class="material-icons">keyboard_tab</i> <?= Yii::t('app', 'logout') ?></a>
            </li>
        </ul>
    </div>
</div>
<div id="crmmodal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h5><?php echo Yii::t('app', 'CRM Screen'); ?></h5>
            <span aria-hidden="true" class="close">&times;</span>
        </div>
        <?php
        //Pjax::begin();
        $form = ActiveForm::begin(['id' => 'lead-ajax-form',
            'action' => ['/crm/crm/index'],
            'options' => ['data-pjax' => '#x1'],]);
        ?>
        <?php if (isset($crmList)) { ?>
        <div class="modal-body">
            <div class="lead-group-member-form" id="lead-group-member-form">
                <div class="row">

                    <div class="row">
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($crmList, 'lg_first_name')->textInput(['maxlength' => true, 'placeholder' => Yii::t('app', 'First Name')])->label(Yii::t('app', 'First Name')); ?>

                            </div>
                        </div>
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($crmList, 'lg_last_name')->textInput(['maxlength' => true, 'placeholder' => Yii::t('app', 'Last Name')])->label(Yii::t('app', 'Last Name')); ?>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($crmList, 'lg_contact_number')->textInput(['maxlength' => true, 'id' => 'lg_contact_number', 'readonly' => true, 'placeholder' => Yii::t('app', 'Contact Number')])->label(Yii::t('app', 'Contact Number')); ?>

                            </div>
                        </div>
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($crmList, 'lg_contact_number_2')->textInput(['maxlength' => true, 'placeholder' => Yii::t('app', 'Contact Number2')])->label(Yii::t('app', 'Contact Number2')); ?>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($crmList, 'lg_email_id')->textInput(['maxlength' => true, 'placeholder' => Yii::t('app', 'Email')])->label(Yii::t('app', 'Email')); ?>

                            </div>
                        </div>
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($crmList, 'lg_address')->textInput(['maxlength' => true, 'placeholder' => Yii::t('app', 'Address')])->label(Yii::t('app', 'Address')); ?>

                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col s12 m6">
                            <div class="input-field ">
                                <?= $form->field($crmList, 'lg_alternate_number')->textInput(['maxlength' => true, 'placeholder' => Yii::t('app', 'Alternate Number')])->label(Yii::t('app', 'Alternate Number')); ?>

                            </div>
                        </div>
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($crmList, 'lg_pin_code')->textInput(['maxlength' => true, 'placeholder' => Yii::t('app', 'Pin Code')])->label(Yii::t('app', 'Pin Code')); ?>

                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($crmList, 'lg_permanent_address')->textInput(['maxlength' => true, 'placeholder' => Yii::t('app', 'Permanent Address')])->label(Yii::t('app', 'Permanent Address')); ?>
                            </div>
                        </div>
                        <div class="col s12 m6">
                            <div class="input-field">
                                <?= $form->field($leadCommentMapping, 'comment')->textarea(['rows' => 6, 'class' => 'materialize-textarea', 'placeholder' => Yii::t('app', 'Comment')])->label(Yii::t('app', 'Comment')); ?>

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
                            <div class="col s12 m6">
                                <div class="input-field">
                                    <?= $form->field($progresiveDataList, 'lg_first_name')->textInput(['maxlength' => true, 'placeholder' => Yii::t('app', 'First Name')])->label(Yii::t('app', 'First Name')); ?>

                                </div>
                            </div>
                            <div class="col s12 m6">
                                <div class="input-field">
                                    <?= $form->field($progresiveDataList, 'lg_last_name')->textInput(['maxlength' => true, 'placeholder' => Yii::t('app', 'Last Name')])->label(Yii::t('app', 'Last Name')); ?>

                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col s12 m6">
                                <div class="input-field">
                                    <?= $form->field($progresiveDataList, 'lg_contact_number')->textInput(['maxlength' => true, 'id' => 'lg_contact_number', 'placeholder' => Yii::t('app', 'Contact Number')])->label(Yii::t('app', 'Contact Number')); ?>

                                </div>
                            </div>
                            <div class="col s12 m6">
                                <div class="input-field">
                                    <?= $form->field($progresiveDataList, 'lg_contact_number_2')->textInput(['maxlength' => true, 'placeholder' => Yii::t('app', 'Contact Number2')])->label(Yii::t('app', 'Contact Number2')); ?>

                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col s12 m6">
                                <div class="input-field">
                                    <?= $form->field($progresiveDataList, 'lg_email_id')->textInput(['maxlength' => true, 'placeholder' => Yii::t('app', 'Email')])->label(Yii::t('app', 'Email')); ?>

                                </div>
                            </div>
                            <div class="col s12 m6">
                                <div class="input-field">
                                    <?= $form->field($progresiveDataList, 'lg_address')->textInput(['maxlength' => true, 'placeholder' => Yii::t('app', 'Address')])->label(Yii::t('app', 'Address')); ?>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col s12 m6">
                                <div class="input-field">
                                    <?= $form->field($progresiveDataList, 'lg_alternate_number')->textInput(['maxlength' => true, 'placeholder' => Yii::t('app', 'Alternate Number')])->label(Yii::t('app', 'Alternate Number')); ?>

                                </div>
                            </div>
                            <div class="col s12 m6">
                                <div class="input-field">
                                    <?= $form->field($progresiveDataList, 'lg_pin_code')->textInput(['maxlength' => true, 'placeholder' => Yii::t('app', 'Pin Code')])->label(Yii::t('app', 'Pin Code')); ?>

                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col s12 m6">
                                <div class="input-field ">
                                    <?= $form->field($progresiveDataList, 'lg_permanent_address')->textInput(['maxlength' => true, 'placeholder' => Yii::t('app', 'Permanent Address')])->label(Yii::t('app', 'Permanent Address')); ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col s12">
                                <div class="input-field ">
                                    <?= $form->field($leadCommentMapping, 'comment')->textarea(['rows' => 6, 'class' => 'materialize-textarea', 'placeholder' => Yii::t('app', 'Comment')])->label(Yii::t('app', 'Comment')); ?>

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
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <?= Html::submitButton(Yii::t('app', 'SUBMIT'), ['class' => 'btn waves-effect waves-light amber darken-4 crm-submit',
                'name' => 'apply',
                'value' => 'update']) ?>
            <?php echo Html::Button(Yii::t('app', 'CANCEL'), ['class' => 'btn waves-effect waves-light bg-gray-200 ml-2 cancel']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>

<div id="modal1" class="modal modal-small" style="top:10%!important;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <!--<span aria-hidden="true" class="close">&times;</span>-->
                <h5><?php echo Yii::t('app', 'Disposition Screen'); ?></h5>
            </div>
            <?php $form = ActiveForm::begin(['class' => 'row',
                'id' => 'submit-disposition-form',
                'enableClientValidation' => true,
                'action' => ['/crm/crm/submit-disposition'],
                'fieldConfig' => ['options' => ['class' => 'input-field'],],]); ?>
            <div class="modal-body">
                <div class="disposition-form pt-4" id="disposition-form">
                    <div class="row">
                        <div class="col s12">
                            <div class="input-field" id="week_off_queue">
                                <div class="select-wrapper">
                                    <?php
                                    echo $form->field($agentDispoMapping, 'disposition', ['options' => ['class' => '', 'id' => 'disposition_type']])->dropDownList($disposionListType, ['prompt' => Yii::t('app', 'Select Disposition')])->label(Yii::t('app', 'Disposition'));
                                    ?>
                                </div>
                            </div>
                        </div>

                        <div class="col s12">
                            <div class="input-field">
                                <div class="form-group field-leadcommentmapping-comment has-success">
                                    <?= $form->field($agentDispoMapping, 'comment')->textarea(['rows' => 6, 'class' => 'materialize-textarea', 'id' => 'disposition_desc', 'placeholder' => Yii::t('app', 'Comment')])->label(Yii::t('app', 'Comment')); ?><?php
                                    echo $form->field(
                                        $agentDispoMapping,
                                        'lead_id'
                                    )->hiddenInput(['id' => 'lg_id1'])->label(FALSE);
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <?= Html::submitButton(Yii::t('app', 'SUBMIT'), ['class' => 'btn waves-effect waves-light darken-4 ml-2',
                    'name' => 'apply', 'id' => 'dis-sub', 'disabled' => true,
                    'value' => 'update']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<div id="transferCallModal" class="modal">
    <div class="modal-content">

        <div class="tranfer-call-form" id="incoming-call-form">
            <?php $form = ActiveForm::begin(['class' => 'row',
                'id' => 'transfer-call-form',
                'fieldConfig' => ['options' => ['class' => 'input-field '],],]); ?>
            <div class="row pt-4">
                <div class="col s12">
                    <div class="input-field" style="line-height: 1;">
                        <?= $form->field($agentDispoMapping, 'comment')
                            ->textInput(['maxlength' => TRUE, 'autofocus' => TRUE, 'id' => 'destination-number',  'onkeypress' => 'return isValidPhoneNumber(event)'])
                            ->label(Yii::t('app', 'Extension Number')); ?>
                    </div>
                </div>
            </div>
            <div class="hseparator"></div>
            <div class="col s12 center">
                <div class="input-field mrg-btn">
                    <?php echo Html::Button(Yii::t('app', 'Transfer'), ['class' => 'btn btn-basic acceptCall', 'id' => 'transferCallBtn']) ?>
                    <button type="button"
                            class="btn btn-default modal-close"><?php echo Yii::t('app', 'Close'); ?></button>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>

<!-- agent connectd popup :: BEGIN -->
<div id="connected-popup" class="connection-popup d-none">
    You are connected with <span class="extention_number">1008</span>
</div>
<!-- agent connectd popup :: END -->

<!-- agent connectd popup :: END -->
<div id="dialPadModal" class="dialer-section" style="display: none;">
    <div class="container2">
        <div class="dialer-number-show">
            <input type="text" id="output" class="dialer-input">
            <i id="clear" class="material-icons">backspace</i>
        </div>
        <div class="row call_dialpad_color">
            <div class="digit" id="one">1</div>
            <div class="digit" id="two">2
                <div class="sub">ABC</div>
            </div>
            <div class="digit" id="three">3
                <div class="sub">DEF</div>
            </div>
        </div>
        <div class="row call_dialpad_color">
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
        <div class="row call_dialpad_color">
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
        <div class="row call_dialpad_color">
            <div class="digit">*
            </div>
            <div class="digit">0
            </div>
            <div class="digit">#
            </div>
        </div>
        <div class="botrow">
            <div id="call"><i class="material-icons">local_phone</i></div>
            <!-- <div id="clear"><?php echo Yii::t('app', 'Clear'); ?></div> -->
        </div>
    </div>
</div>

<div id="incomingCall" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h5><?php echo Yii::t('app', 'Incoming Call Screen'); ?></h5>
        </div>
        <?php $form = ActiveForm::begin(['class' => 'row',
            'id' => 'incoming-call-form',
            'fieldConfig' => ['options' => ['class' => 'input-field'],],]); ?>
        <div class="modal-body">
            <div class="incoming-call-form" id="incoming-call-form">
                <div class="row">
                    <div class="col s12">
                        <div class="input-field">
                            <?= $form->field($agentDispoMapping, 'comment')
                                ->textInput(['maxlength' => TRUE, 'autofocus' => TRUE, 'id' => 'caller_id_number', 'disabled' => 'disabled'])
                                ->label(Yii::t('app', 'Caller Id Number')); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <?php echo Html::Button(Yii::t('app', 'Accept'), ['class' => 'btn btn-basic acceptCall', 'id' => 'acceptCall']) ?>
            <?php echo Html::Button(Yii::t('app', 'Reject'), ['class' => 'btn btn-basic rejectCall', 'id' => 'rejectCall']) ?>
            <?php /* <button type="button" class="btn btn-default modal-close"><?php echo Yii::t('app', 'Close');?></button> */ ?>

        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>

<div id="scriptModal" class="modal script">
    <div class="modal-content">
        <div class="modal-header">
            <h5><?php echo Yii::t('app', 'Script Screen'); ?></h5>
            <span aria-hidden="true" class="close">&times;</span>
        </div>
        <div class="modal-body">
            <div class="script-form" id="script-form">
                <div class="row">
                    <div id="Script" class="col s12">
                        Loading ...
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="logoutModal" class="modal logout" style="width:35%!important;">
    <div class="modal-content">
        <span aria-hidden="true" class="close" style="padding-top: 12px;">&times;</span>

        <!-- div class="script-form" id="script-form" -->
        <div class="row" style="color: #4a4a4a!important;">
            <?php echo Yii::t('app', 'call_in_active_logout_ristriction'); ?>
        </div>
        <!-- /div -->
    </div>
</div>

<div id="noleadmodal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h5><?php echo Yii::t('app', 'CRM Screen'); ?></h5>
            <span aria-hidden="true" class="close">&times;</span>
        </div>
        <div class="modal-body">
            <div id="no-lead-body">
                <div class="row">
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default modal-close"><?php echo Yii::t('app', 'Close'); ?></button>
        </div>
    </div>
</div>

<script>
    var isPause = '<?=$is_in?>';
    $(document).ready(function (events) {
        var isPause = '<?=$is_in?>';

        // $('#dialPadModal').modal();
        $('#crmmodal').modal();
        $('#scriptModal').modal();
        $('#logoutModal').modal();
    });
    $(document).on("click", "#CRM", function () {
        $('#crmmodal').modal({
            dismissible: false, // Modal can be dismissed by clicking outside of the modal
        });
        $('#crmmodal').modal('open');
    });
    $(document).on("click", "#script", function () {
        $('#scriptModal').modal({
            dismissible: false, // Modal can be dismissed by clicking outside of the modal
        });
        $('#scriptModal').modal('open');
    });

    // $('#dialPadModal').modal();
    $(document).on("click", "#dial-pad", function () {
        $('#dialPadModal').toggle();
        $('body').toggleClass("dialer-open");
    });
    $("body").on('click', function (event) {
        // alert($(event.target));
        // if (!$(event.target).closest('#dialPadModal').length) {
        // $('#dialPadModal').toggle();
        // $('body').toggleClass("dialer-open");
        // }
    });
    $(document).on("click", ".close", function () {
        $('#crmmodal').modal('close');
        // $('#dialPadModal').modal('close');
        $('#scriptModal').modal('close');
        $('#logoutModal').modal('close');
    });

    $(document).on("click", ".cancel", function () {
        $('#crmmodal').modal('close');
        // $('#dialPadModal').modal('close');
        $('#scriptModal').modal('close');
        $('#logoutModal').modal('close');
    });

    // Remove dialer
    $(document).on("click",".sidenav li a", function(){
        $('#dialPadModal').css("display", "none");
        $('body').removeClass("dialer-open");
        $("#dial-pad.active").hide();
        $("#dial-pad.disabled").show();
    });
</script>


<style>
    #lead-ajax-form input {
        font-size: 1rem;
        -webkit-box-sizing: content-box;
        -moz-box-sizing: content-box;
        box-sizing: content-box;
        width: -webkit-fill-available;
        height: 37px;
        margin: 0 0 8px 0;
        padding: 0 10px;
        -webkit-transition: border .3s, -webkit-box-shadow .3s;
        -moz-transition: box-shadow .3s, border .3s;
        -o-transition: box-shadow .3s, border .3s;
        transition: border .3s, -webkit-box-shadow .3s;
        transition: box-shadow .3s, border .3s;
        transition: box-shadow .3s, border .3s, -webkit-box-shadow .3s;
        border: none;
        border-bottom: 1px solid #9e9e9e;
        border-radius: 3px;
        outline: none;
        background-color: transparent;
        -webkit-box-shadow: none;
        box-shadow: none;
    }

    #lead-ajax-form .form-group {
        line-height: 40px !important;
    }

    #submit-disposition-form input. #submit-disposition-form textarea {
        font-size: 1rem;
        -webkit-box-sizing: content-box;
        -moz-box-sizing: content-box;
        box-sizing: content-box;
        width: 100%;
        height: 3rem;
        margin: 0 0 8px 0;
        padding: 0;
        -webkit-transition: border .3s, -webkit-box-shadow .3s;
        -moz-transition: box-shadow .3s, border .3s;
        -o-transition: box-shadow .3s, border .3s;
        transition: border .3s, -webkit-box-shadow .3s;
        transition: box-shadow .3s, border .3s;
        transition: box-shadow .3s, border .3s, -webkit-box-shadow .3s;
        border: none;
        border-bottom: 1px solid #9e9e9e;
        border-radius: 0;
        outline: none;
        background-color: transparent;
        -webkit-box-shadow: none;
        box-shadow: none;
    }

    #submit-disposition-form .form-group {
        line-height: 40px !important;
    }
</style>

<script>
    $(document).on("click", ".remove-active-class", function () {
        //$(".agent_navi").hasClass('active').removeClass("active");
        $(".agent_navi.active").each(function () {
            $(this).removeClass("active");
        });
    });
    $(document).on("click", ".remove-active-class-home", function () {
        $(".agent_navi.active").each(function () {
            $(this).removeClass("active");
        });
        $(".agent_dashboard").addClass('active');
    });


</script>