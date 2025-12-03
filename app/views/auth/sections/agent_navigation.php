<?php

use yii\helpers\Url;

?>

<li class="navigation-header">
    <a class="navigation-header-text"><?= Yii::t('app', 'settings') ?></a>
    <i class="navigation-header-icon material-icons">more_horiz</i>
</li>
<li class="bold">
    <a class="waves-effect waves-cyan" data-href="dashboard"
       href="<?= Url::to(['/agents/agents/dashboard']); ?>">
        <i class="material-icons">dashboard</i>
        <span class="menu-title" data-i18n=""><?= Yii::t('app', 'dashboard') ?></span>
    </a>
</li>

<!--<li class="bold">
    <a class="waves-effect waves-cyan" data-href="extension-forwarding"
       href="<? /*= Url::to(['/extensionforwarding/extension-forwarding/forwading', 'ext-forwarding-auth'=>Yii::$app->user->identity->em_extension_number]) */ ?>">

        <i class="material-icons">phone_forwarded</i>
        <span class="menu-title" data-i18n=""><? /*= Yii::t('app', 'extension_forwarding') */ ?></span>
    </a>
</li>-->

<li class="bold">
    <a class="waves-effect waves-cyan" data-href="crm"
       href="<?= Url::to(['/crm/crm/index']) ?>">
        <i class="material-icons">contact_phone</i>
        <span class="menu-title" data-i18n=""><?= Yii::t('app', 'CRM') ?></span>
    </a>
</li>
<!--<li class="bold">
    <a class="waves-effect waves-cyan" data-href="cdr"
       href="<? /*= Url::to(['/cdr/cdr/index']) */ ?>">
        <i class="material-icons">record_voice_over</i>
        <span class="menu-title" data-i18n=""><? /*= Yii::t('app', 'cdr') */ ?></span>
    </a>
</li>
<li class="bold">
    <a class="waves-effect waves-cyan" data-href="voicemsg"
       href="<? /*= Url::to(['/voicemsg/voicemail-msgs']) */ ?>">
        <i class="material-icons">record_voice_over</i>
        <span class="menu-title" data-i18n=""><? /*= Yii::t('app', 'Voicemail Msgs') */ ?></span>
    </a>
</li>
<li class="bold">
    <a class="waves-effect waves-cyan" data-href="extension-speeddial"
       href="<? /*= Url::to(['/speeddial/extension-speeddial/speeddial', 'speeddial-auth'=>Yii::$app->user->identity->em_extension_number]) */ ?>">
        <i class="material-icons">dialpad</i>
        <span class="menu-title" data-i18n=""><? /*= Yii::t('app', 'speeddial') */ ?></span>
    </a>
</li>-->


<li class="bold">
    <a class="waves-effect waves-cyan" data-href="clienthistory"
       href="<?= Url::to(['/clienthistory/client-history/index']) ?>">
        <i class="material-icons">stars</i>
        <span class="menu-title" data-i18n=""><?= Yii::t('app', 'client_history') ?></span>
    </a>
</li>
<li class="bold">
    <a class="waves-effect waves-cyan" data-href="callhistory"
       href="<?= Url::to(['/callhistory/call-history/index']) ?>">
        <i class="material-icons">record_voice_over</i>
        <span class="menu-title" data-i18n=""><?= Yii::t('app', 'call_history') ?></span>
    </a>
</li>
<?php /*
<li class="bold last-child">
    <a class="waves-effect waves-cyan" data-href="supervisorsummary" href="<?= Url::to(['/supervisorsummary/supervisor-summary/index']) ?>">
        <i class="material-icons">stars</i>
        <span class="menu-title" data-i18n=""><?= Yii::t('app', 'Agent/Supervisor Summary Report') ?></span>
    </a>
</li>
<li class="bold last-child">
    <a class="waves-effect waves-cyan" data-href="agentscallreport" href="<?= Url::to(['/agentscallreport/agents-call-report/index']) ?>">
        <i class="material-icons">stars</i>
        <span class="menu-title" data-i18n=""><?= Yii::t('app', 'Agents Call Report') ?></span>
    </a>
</li><?php */ ?>


