<?php

use yii\helpers\Url;

?>
<li class="bold">
    <a class="waves-effect waves-cyan agent_dashboard agent_navi active" data-href="dashboard"
       href="<?php echo Url::to(["/agents/agents/customdashboard"]); ?>" target="myFrame">
        <i class="material-icons">dashboard</i>
        <span class="menu-title" data-i18n=""><?= Yii::t('app', 'dashboard') ?></span>
    </a>
</li>
<li class="bold">
    <!-- a class="waves-effect waves-cyan agent_navi" data-href="clienthistory" href="<?= Url::to(['/clienthistory/client-history/customindex']) ?>" target="myFrame">
        <i class="material-icons">stars</i>
        <span class="menu-title" data-i18n=""><?= Yii::t('app', 'client_history') ?></span>
    </a -->
   <!-- <a class="waves-effect waves-cyan agent_navi" data-href="clienthistory"
       href="<?php /*echo Url::to(["/clienthistory/client-history/customindex"]); */?>" target="myFrame">
        <i class="material-icons">stars</i>
        <span class="menu-title" data-i18n=""><?php /*= Yii::t('app', 'client_history') */?></span>
    </a>-->
</li>
<li class="bold">
    <a class="waves-effect waves-cyan agent_navi" data-href="callhistory"
       href="<?php echo Url::to(["/callhistory/call-history/customindex"]); ?>" target="myFrame">
        <i class="material-icons">record_voice_over</i>
        <span class="menu-title" data-i18n=""><?= Yii::t('app', 'call_history') ?></span>
    </a>
</li>
