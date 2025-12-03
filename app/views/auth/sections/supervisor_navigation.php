<?php

use yii\helpers\Url;

?>

<!-- li class="navigation-header">
    <a class="navigation-header-text"><?= Yii::t('app', 'settings') ?></a>
    <i class="navigation-header-icon material-icons">more_horiz</i>
</li -->
<li class="bold">
    <a class="waves-effect waves-cyan" data-href="supervisor"
       href="<?= Url::to(['/supervisor/supervisor/dashboard']); ?>">
        <i class="material-icons">dashboard</i>
        <span class="menu-title" data-i18n=""><?= Yii::t('app', 'dashboard') ?></span>
    </a>
</li>
<li class="bold">
    <a class="waves-effect waves-cyan" data-href="customerdetails"
       href="<?= Url::to(['/customerdetails/customer-details/index']) ?>">
        <i class="material-icons">contact_phone</i>
        <span class="menu-title" data-i18n=""><?= Yii::t('app', 'customer_details') ?></span>
    </a>
</li>
<?php /*
<li class="bold">
    <a class="waves-effect waves-cyan" data-href="supervisorcdr"
       href="<?= Url::to(['/supervisorcdr/cdr/index']); ?>">
        <i class="material-icons">record_voice_over</i>
        <span class="menu-title" data-i18n=""><?= Yii::t('app', 'cdr'); ?></span>
    </a>
</li>
<li class="bold">
    <a class="waves-effect waves-cyan" data-href="campaigncdr"
       href="<?= Url::to(['/campaigncdr/cdr/index']); ?>">
        <i class="material-icons">record_voice_over</i>
        <span class="menu-title" data-i18n=""><?= Yii::t('app', 'campaigncdr'); ?></span>
    </a>
</li>
<li class="bold">
    <a class="waves-effect waves-cyan supervisor" data-href="supervisoragentcdr"
       href="<?= Url::to(['/supervisoragentcdr/cdr/index']); ?>">
        <i class="material-icons">record_voice_over</i>
        <span class="menu-title" data-i18n=""><?= Yii::t('app', 'summary_report'); ?></span>
    </a>
</li>
<li class="bold">
    <a class="waves-effect waves-cyan agents" data-href="agentcdr"
       href="<?= Url::to(['/agentcdr/cdr/index']); ?>">
        <i class="material-icons">record_voice_over</i>
        <span class="menu-title" data-i18n=""><?= Yii::t('app', 'agentcdr'); ?></span>
    </a>
</li><?php */ ?>
<li class="bold">
    <a class="waves-effect waves-cyan" data-href="manageagent"
       href="<?= Url::to(['/manageagent/manage-agent/index']); ?>">
        <i class="material-icons">watch_later</i>
        <span class="menu-title" data-i18n=""><?= Yii::t('app', 'manage_agents'); ?></span>
    </a>
</li>

<!--<li class="bold">
    <a class="waves-effect waves-cyan" data-href="systemcode" href="<?php /*= Url::to(['/systemcode/system-code/index']) */?>">
        <i class="material-icons">stars</i>
        <span class="menu-title" data-i18n=""><?php /*= Yii::t('app', 'system_codes') */?></span>
    </a>
</li>-->

<li class="bold">
    <ul class="collapsible collapsible-accordion">
        <li>
            <a class="collapsible-header">
                <i class="material-icons">timeline</i>
                <span class="menu-title" data-i18n="">Reports</span>
            </a>
            <div class="collapsible-body">
                <ul>

                    <li class="bold">
                        <a class="waves-effect waves-cyan" data-href="agentscallreport"
                           href="<?= Url::to(['/agentscallreport/agents-call-report/index']) ?>">
                            <i class="material-icons">phone_in_talk</i>
                            <span class="menu-title" data-i18n=""><?= Yii::t('app', 'agents_wise_report') ?></span>
                        </a>
                    </li>

                    <li class="bold">
                        <a class="waves-effect waves-cyan" data-href="abandoned-call-report"
                           href="<?= Url::to(['/supervisorabandonedcallreport/abandoned-call-report/index']) ?>">
                            <i class="material-icons">call_missed_outgoing</i>
                            <span class="menu-title" data-i18n=""><?= Yii::t('app', 'abandonedcallreport') ?></span>
                        </a>
                    </li>

                    <li class="bold">
                        <a class="waves-effect waves-cyan" data-href="campaignreport"
                           href="<?= Url::to(['/campaignreport/campaign-report/index']) ?>">
                            <i class="material-icons">call</i>
                            <span class="menu-title" data-i18n=""><?= Yii::t('app', 'campaign_summary_report') ?></span>
                        </a>
                    </li>

                    <li class="bold">
                        <a class="waves-effect waves-cyan" data-href="dispositionreport"
                           href="<?= Url::to(['/dispositionreport/disposition-report/index']) ?>">
                            <i class="material-icons">group_work</i>
                            <span class="menu-title"
                                  data-i18n=""><?= Yii::t('app', 'disposition_summary_report') ?></span>
                        </a>
                    </li>

                    <li class="bold">
                        <a class="waves-effect waves-cyan" data-href="leadperformancereport"
                           href="<?= Url::to(['/leadperformancereport/lead-performance-report/index']) ?>">
                            <i class="material-icons">table_chart</i>
                            <span class="menu-title" data-i18n=""><?= Yii::t('app', 'lead_performance_report') ?></span>
                        </a>
                    </li>

                    <li class="bold">
                        <a class="waves-effect waves-cyan" data-href="timeclockreport"
                           href="<?= Url::to(['/timeclockreport/time-clock-report/index']) ?>">
                            <i class="material-icons">timer</i>
                            <span class="menu-title" data-i18n=""><?= Yii::t('app', 'time_clock_report') ?></span>
                        </a>
                    </li>
                </ul>
            </div>
        </li>
    </ul>
</li>
<!--<li class="bold">
    <a class="waves-effect waves-cyan" data-href="supervisorsummary" href="<?php /*= Url::to(['/supervisorsummary/supervisor-summary/index']) */?>">
        <i class="material-icons">perm_identity</i>
        <span class="menu-title" data-i18n=""><?php /*= Yii::t('app', 'time_clock_report') */?></span>
    </a>
</li>-->

<!--<li class="bold">
    <a class="waves-effect waves-cyan" data-href="queue-callback" href="<?php /*= Url::to(['/supervisorqueuecallback/queue-callback/index']) */?>">
        <i class="material-icons">stars</i>
        <span class="menu-title" data-i18n=""><?php /*= Yii::t('app', 'queuecallback') */?></span>
    </a>
</li>-->

<!--<li class="bold">
    <a class="waves-effect waves-cyan" data-href="clienthistory" href="<?php /*= Url::to(['/clienthistory/client-history/index']) */?>">
        <i class="material-icons">group</i>
        <span class="menu-title" data-i18n=""><?php /*= Yii::t('app', 'client_history') */?></span>
    </a>
</li>-->
<!--<li class="bold">
    <a class="waves-effect waves-cyan" data-href="callhistory"
       href="<?php /*= Url::to(['/callhistory/call-history/index']) */?>">
        <i class="material-icons">record_voice_over</i>
        <span class="menu-title" data-i18n=""><?php /*= Yii::t('app', 'call_history') */?></span>
    </a>
</li>-->


<script>
    $(document).ready(function() {
        $('.collapsible-body ul li .waves-cyan.active').closest('.collapsible-accordion>li').addClass('active');
        $('.collapsible-body ul li .waves-cyan.active').closest('.collapsible-body').css('display', 'block');
    });
</script>