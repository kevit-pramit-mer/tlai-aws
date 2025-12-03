<?php

use app\modules\ecosmob\extension\models\Extension;
use app\modules\ecosmob\extensionsettings\models\ExtensionCallSetting;
use yii\helpers\Url;

$extensionData = Extension::find()->where(['em_extension_number' => Yii::$app->user->identity->em_extension_number])->one();
$phoneBookStatus = $extensionData->is_phonebook;


if (!empty(Yii::$app->user->identity->em_id)) {
    $em_id = Yii::$app->user->identity->em_id;
}
$callSettingData = ExtensionCallSetting::find()->where(['em_id' => $em_id])->one();

$forwardingStatus = $callSettingData->ecs_forwarding;
$voiceMailStatus = $callSettingData->ecs_voicemail;

?>

<!--Dashboard ends -->
<!-- <li class="navigation-header">
    <a class="navigation-header-text"><?= Yii::t('app', 'settings') ?></a>
    <i class="navigation-header-icon material-icons">more_horiz</i>
</li> -->
<li class="bold">
    <a class="waves-effect waves-cyan extension_navi" data-href="dashboard"
       href="<?= Url::to(['/extension/extension/dashboard']) ?>" target="extensionFrame">
        <i class="material-icons">dashboard</i>
        <span class="menu-title" data-i18n=""><?= Yii::t('app', 'dashboard') ?></span>
    </a>
</li>

<?php if ($forwardingStatus != '0') { ?>
    <li class="bold">
        <a class="waves-effect waves-cyan extension_navi" data-href="extension-forwarding" target="extensionFrame"
           href="<?= Url::to(['/extensionforwarding/extension-forwarding/forwading', 'ext-forwarding-auth' => Yii::$app->user->identity->em_extension_number]) ?>">

            <i class="material-icons">phone_forwarded</i>
            <span class="menu-title" data-i18n=""><?= Yii::t('app', 'extension_forwarding') ?></span>
        </a>
    </li>
<?php } ?>

<!--<li class="bold">
    <a class="waves-effect waves-cyan" data-href="leadgroup"
       href="<? /*= Url::to(['/leadgroup/leadgroup/index']) */ ?>">
        <i class="material-icons">group</i>
        <span class="menu-title" data-i18n=""><? /*= Yii::t('app', 'leadgroup') */ ?></span>
    </a>
</li>-->
<?php /*if ($phoneBookStatus == 1) { */?><!--
    <li class="bold">
        <a class="waves-effect waves-cyan extension_navi" data-href="phone-book"
           href="<?php /*= Url::to(['/phonebook/phone-book/index']) */?>" target="extensionFrame">
            <i class="material-icons">contact_phone</i>
            <span class="menu-title" data-i18n=""><?php /*= Yii::t('app', 'phone_book') */?></span>
        </a>
    </li>
--><?php /*} */?>

<li class="bold">
    <a class="waves-effect waves-cyan extension_navi" data-href="cdr"
       href="<?= Url::to(['/cdr/cdr/extension-cdr', 'CdrSearch[isfile]=Yes']) ?>" target="extensionFrame">
        <i class="material-icons">record_voice_over</i>
        <span class="menu-title" data-i18n=""><?= Yii::t('app', 'cdr') ?></span>
    </a>
</li>

<?php if ($voiceMailStatus == 1) { ?>
    <li class="bold">
        <a class="waves-effect waves-cyan extension_navi" data-href="voicemsg"
           href="<?= Url::to(['/voicemsg/voicemail-msgs']) ?>" target="extensionFrame">
            <i class="material-icons">email</i>
            <span class="menu-title" data-i18n=""><?= Yii::t('app', 'Voicemail Msgs') ?></span>
        </a>
    </li>
<?php } ?>


<!--<li class="bold">
    <a class="waves-effect waves-cyan" data-href="disposition-master"
       href="<? /*= Url::to(['/disposition/disposition-master']) */ ?>">
        <i class="material-icons">bookmark_border</i>
        <span class="menu-title" data-i18n=""><? /*= Yii::t('app', 'disposition') */ ?></span>
    </a>
</li>-->
<!--<li class="bold">
    <a class="waves-effect waves-cyan" data-href="call-campaign"
       href="<? /*= Url::to(['/call-campaign/call-campaign/index']) */ ?>">
        <i class="material-icons">phone_in_talk</i>
        <span class="menu-title" data-i18n=""><? /*= Yii::t('app', 'callCampaign') */ ?></span>
    </a>
</li>-->
<!--<li class="bold">
    <a class="waves-effect waves-cyan" data-href="call-recordings"
       href="<? /*= Url::to(['/call-recordings/call-recordings/index']) */ ?>">
        <i class="material-icons">ring_volume</i>
        <span class="menu-title" data-i18n=""><? /*= Yii::t('app', 'callRecordings') */ ?></span>
    </a>
</li>-->
<li class="bold">
    <a class="waves-effect waves-cyan extension_navi" data-href="extension-speeddial" target="extensionFrame"
       href="<?= Url::to(['/speeddial/extension-speeddial/speeddial', 'speeddial-auth' => Yii::$app->user->identity->em_extension_number]) ?>">
        <i class="material-icons">dialpad</i>
        <span class="menu-title" data-i18n=""><?= Yii::t('app', 'speed_dial') ?></span>
    </a>
</li>
<li class="bold">
    <a class="waves-effect waves-cyan extension_navi" data-href="extension-blf" id="blf-link" target="extensionFrame"
       href="<?= Url::to(['/blf/extension-blf/blf']) ?>">
        <i class="material-icons">call</i>
        <span class="menu-title" data-i18n=""><?= Yii::t('app', 'blf') ?></span>
    </a>
</li>

<li class="bold">
    <a class="waves-effect waves-cyan extension_navi" data-href="enterprisePhonebook" target="extensionFrame"
       href="<?= Url::to(['/enterprisePhonebook/enterprise-phonebook/view']) ?>">
        <i class="material-icons">contact_phone</i>
        <span class="menu-title" data-i18n=""><?= Yii::t('app', 'enterprise_phonebook') ?></span>
    </a>
</li>
