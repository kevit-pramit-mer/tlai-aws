<?php

use yii\helpers\Url;

$permissions = $GLOBALS['permissions'];

?>

<li class="bold">
    <a class="waves-effect waves-cyan dash-active" href="<?= Yii::$app->homeUrl ?>"><i class="material-icons">settings_input_svideo</i>
        <span class="menu-title" data-i18n=""><?= Yii::t('app', 'dashboard') ?></span>
    </a>
</li>

<?php if (in_array('/logviewer/logviewer/index', $permissions) || in_array('/iptable/iptable/index', $permissions) || in_array('/fraudcall/fraud-call/index', $permissions) || in_array('/breaks/breaks/index', $permissions) || in_array('/pcap/pcap/index', $permissions)) { ?>

    <li class="navigation-header">
        <a class="navigation-header-text"><?= Yii::t('app', 'features') ?></a>
    </li>

    <?php if (in_array('/logviewer/logviewer/index', $permissions)) { ?>
        <li class="bold">
            <a class="waves-effect waves-cyan" data-href="logviewer"
               href="<?= Url::to(['/logviewer/logviewer/index']) ?>"><i class="material-icons">list_alt</i>
                <span class="menu-title" data-i18n=""><?= Yii::t('app', 'log_viewer') ?></span>
            </a>
        </li>
    <?php } ?>

    <?php if (in_array('/breaks/breaks/index', $permissions)) { ?>
        <li class="bold">
            <a title="<?= Yii::t('app', 'break_management') ?>" class="waves-effect waves-cyan" data-href="breaks"
               href="<?= Url::to(['/breaks/breaks/index']) ?>"><i
                        class="material-icons">free_breakfast</i>
                <span class="menu-title" data-i18n=""><?= Yii::t('app', 'break_management') ?></span>
            </a>
        </li>
    <?php } ?>

    <?php if (in_array('/fraudcall/fraud-call/index', $permissions)) { ?>
        <li class="bold">
            <a class="waves-effect waves-cyan fraud-child" data-href="fraudcall"
               href="<?= Url::to(['/fraudcall/fraud-call/index']) ?>"><i class="material-icons">phonelink_setup</i>
                <span class="menu-title" data-i18n=""><?= Yii::t('app', 'fraudcall') ?></span>
            </a>
        </li>
    <?php } ?>

    <?php if (in_array('/iptable/iptable/index', $permissions)) { ?>
        <li class="bold">
            <a class="waves-effect waves-cyan" data-href="iptable" href="<?= Url::to(['/iptable/iptable/index']) ?>"><i
                        class="material-icons">table_chart</i>
                <span class="menu-title" data-i18n=""><?= Yii::t('app', 'iptable') ?></span>
            </a>
        </li>
    <?php } ?>

    <?php if (in_array('/pcap/pcap/index', $permissions)) { ?>
        <li class="bold">
            <a class="waves-effect waves-cyan" data-href="pcap" href="<?= Url::to(['/pcap/pcap/index']); ?>"><i
                        class="material-icons">reorder</i>
                <span class="menu-title" data-i18n=""><?= Yii::t('app', 'pcap'); ?></span>
            </a>
        </li>
    <?php } ?>
<?php } ?>

<li class="bold" hidden>
    <a class="waves-effect waves-cyan" data-href="pcap" href="<?= Url::to(['/pcap/pcap/index']) ?>"><i
                class="material-icons">reorder</i>
        <span class="menu-title" data-i18n=""><?= Yii::t('app', 'pcap') ?></span>
    </a>
</li>

<li class="bold" hidden>
    <a class="waves-effect waves-cyan" data-href="extension-call-summary"
       href="<?= Url::to(['/reports/extension-call-summary/index']) ?>"><i class="material-icons">extension</i>
        <span class="menu-title" data-i18n=""><?= Yii::t('app', 'extension-call-summary') ?></span>
    </a>
</li>

<?php if (in_array('/shift/shift/index', $permissions) || in_array('/weekoff/week-off/index', $permissions) || in_array('/holiday/holiday/index', $permissions)) { ?>

    <li class="navigation-header">
        <a class="navigation-header-text"><?= Yii::t('app', 'timing') ?></a>
        <i class="navigation-header-icon material-icons">more_horiz</i>
    </li>

    <?php if (in_array('/shift/shift/index', $permissions)) { ?>
        <li class="bold">
            <a class="waves-effect waves-cyan" data-href="shift" href="<?= Url::to(['/shift/shift/index']) ?>">
                <i class="material-icons">access_time</i>
                <span class="menu-title" data-i18n=""><?= Yii::t('app', 'shift') ?></span>
            </a>
        </li>
    <?php } ?>
    <?php if (in_array('/weekoff/week-off/index', $permissions)) { ?>
        <li class="bold">
            <a class="waves-effect waves-cyan" data-href="week-off" href="<?= Url::to(['/weekoff/week-off/index']) ?>">
                <i class="material-icons">free_breakfast</i>
                <span class="menu-title" data-i18n=""><?= Yii::t('app', 'weekoff') ?></span>
            </a>
        </li>
    <?php } ?>
    <?php if (in_array('/holiday/holiday/index', $permissions)) { ?>
        <li class="bold">
            <a class="waves-effect waves-cyan" data-href="holiday" href="<?= Url::to(['/holiday/holiday/index']) ?>">
                <i class="material-icons">airplanemode_active</i>
                <span class="menu-title" data-i18n=""><?= Yii::t('app', 'holiday') ?></span>
            </a>
        </li>
    <?php } ?>
<?php } ?>

<?php if (in_array('/plan/plan/index', $permissions) || in_array('/playback/playback/index', $permissions)) { ?>
    <li class="navigation-header">
        <a class="navigation-header-text"><?= Yii::t('app', 'config') ?></a>
        <i class="navigation-header-icon material-icons">more_horiz</i>
    </li>
    <?php if (in_array('/plan/plan/index', $permissions)) { ?>
        <li class="bold">
            <a class="waves-effect waves-cyan" data-href="=plan" href="<?= Url::to(['/plan/plan/index']) ?>">
                <i class="material-icons">tune</i>
                <span class="menu-title" data-i18n=""><?= Yii::t('app', 'plan') ?></span>
            </a>
        </li>
    <?php } ?>
    <?php if (in_array('/playback/playback/index', $permissions)) { ?>
        <li class="bold">
            <a class="waves-effect waves-cyan" data-href="playback" href="<?= Url::to(['/playback/playback/index']) ?>">
                <i class="material-icons">playlist_play</i>
                <span class="menu-title" data-i18n=""><?= Yii::t('app', 'playback') ?></span>
            </a>
        </li>
    <?php } ?>
<?php } ?>

<?php if (in_array('/carriertrunk/trunkmaster/index', $permissions) || in_array('/carriertrunk/trunkgroup/index', $permissions) || in_array('/dialplan/outbounddialplan/index', $permissions)) { ?>
    <li class="navigation-header">
        <a class="navigation-header-text"><?= Yii::t('app', 'dial_plan') ?></a>
        <i class="navigation-header-icon material-icons">more_horiz</i>
    </li>
    <?php if (in_array('/carriertrunk/trunkmaster/index', $permissions)) { ?>
        <li class="bold">
            <a class="waves-effect waves-cyan" data-href="trunkmaster"
               href="<?= Url::to(['/carriertrunk/trunkmaster/index']) ?>">
                <i class="material-icons">tune</i>
                <span class="menu-title" data-i18n=""><?= Yii::t('app', 'trunks') ?></span>
            </a>
        </li>
    <?php } ?>
    <?php if (in_array('/carriertrunk/trunkgroup/index', $permissions)) { ?>
        <li class="bold">
            <a class="waves-effect waves-cyan" data-href="trunkgroup"
               href="<?= Url::to(['/carriertrunk/trunkgroup/index']) ?>">
                <i class="material-icons">playlist_play</i>
                <span class="menu-title" data-i18n=""><?= Yii::t('app', 'trunk_groups') ?></span>
            </a>
        </li>
    <?php } ?>
    <?php if (in_array('/dialplan/outbounddialplan/index', $permissions)) { ?>
        <li class="bold">
            <a class="waves-effect waves-cyan" data-href="dialplan"
               href="<?= Url::to(['/dialplan/outbounddialplan/index']) ?>">
                <i class="material-icons">phone_forwarded</i>
                <span class="menu-title" data-i18n=""><?= Yii::t('app', 'outbounddialplans') ?></span>
            </a>
        </li>
    <?php } ?>
<?php } ?>

<?php if (in_array('/rbac/role/index', $permissions)) { ?>

    <li class="navigation-header">
        <a class="navigation-header-text"><?= Yii::t('app', 'roles') ?></a>
        <i class="navigation-header-icon material-icons">more_horiz</i>
    </li>
    <!--    --><?php //if (in_array('/rbac/role/index', $permissions)) { ?>
    <li class="bold">
        <a class="waves-effect waves-cyan" data-href="role" href="<?= Url::to(['/rbac/role/index']) ?>">
            <i class="material-icons">assignment_ind</i>
            <span class="menu-title" data-i18n=""><?= Yii::t('app', 'roles') ?></span>
        </a>
    </li>
    <!--    --><?php //} ?>
<?php } ?>

<?php if (in_array('/user/user/index', $permissions) || in_array('/extension/extension/index', $permissions) || in_array('/agent/agent/index', $permissions) || in_array('/group/group/index', $permissions) || in_array('/speeddial/extension-speeddial/index', $permissions) || in_array('/fax/fax/index', $permissions) || in_array('/agents/agents/index', $permissions) || in_array('/supervisor/supervisor/index', $permissions)) { ?>
    <li class="navigation-header">
        <a class="navigation-header-text"><?= Yii::t('app', 'extension_groups') ?></a>
        <i class="navigation-header-icon material-icons">perm_identity</i>
    </li>
    <?php if (in_array('/user/user/index', $permissions)) { ?>
        <li class="bold">
            <a class="waves-effect waves-cyan" data-href="user" href="<?= Url::to(['/user/user/index']) ?>">
                <i class="material-icons">perm_identity</i>
                <span class="menu-title" data-i18n=""><?= Yii::t('app', 'users') ?></span>
            </a>
        </li>
    <?php } ?>

    <?php if (in_array('/agents/agents/index', $permissions)) { ?>
        <li class="bold">
            <a class="waves-effect waves-cyan agents" data-href="agent" href="<?= Url::to(['/agents/agents/index']) ?>">
                <i class="material-icons">perm_identity</i>
                <span class="menu-title" data-i18n=""><?= Yii::t('app', 'Agents') ?></span>
            </a>
        </li>
    <?php } ?>
    <?php if (in_array('/supervisor/supervisor/index', $permissions)) { ?>
        <li class="bold">
            <a class="waves-effect waves-cyan" data-href="supervisor"
               href="<?= Url::to(['/supervisor/supervisor/index']) ?>">
                <i class="material-icons">perm_identity</i>
                <span class="menu-title" data-i18n=""><?= Yii::t('app', 'Supervisor') ?></span>
            </a>
        </li>
    <?php } ?>
    <?php if (in_array('/extension/extension/index', $permissions)) { ?>
        <li class="bold">
            <a class="waves-effect waves-cyan extension-child" data-href="extension"
               href="<?= Url::to(['/extension/extension/index']) ?>">
                <i class="material-icons">extension</i>
                <span class="menu-title" data-i18n=""><?= Yii::t('app', 'extensions') ?></span>
            </a>
        </li>
    <?php } ?>

    <?php if (in_array('/fax/fax/index', $permissions)) { ?>
        <li class="bold">
            <a class="waves-effect waves-cyan fax-child" data-href="fax"
               href="<?= Url::to(['/fax/fax/index']) ?>">
                <i class="material-icons">email</i>
                <span class="menu-title" data-i18n=""><?= Yii::t('app', 'Fax') ?></span>
            </a>
        </li>
    <?php } ?>

    <?php if (in_array('/group/group/index', $permissions)) { ?>
        <li class="bold">
            <a class="waves-effect waves-cyan " data-href="=group"
               href="<?= Url::to(['/group/group/index']) ?>">
                <i class="material-icons">people</i>
                <span class="menu-title" data-i18n=""><?= Yii::t('app', 'groups') ?></span></a>
        </li>
    <?php } ?>

    <?php if (in_array('/speeddial/extension-speeddial/index', $permissions)) { ?>
        <li class="bold">
            <a class="waves-effect waves-cyan " data-href="speeddial"
               href="<?= Url::to(['/speeddial/extension-speeddial/index']) ?>">
                <i class="material-icons">ring_volume</i>
                <span class="menu-title" data-i18n=""><?= Yii::t('app', 'speeddial') ?></span></a>
        </li>
    <?php } ?>
    <?php if (in_array('/agent/agent/index', $permissions)) { ?>
        <li class="bold" hidden>
            <a class="waves-effect waves-cyan" data-href="agent" href="<?= Url::to(['/agent/agent/index']) ?>">
                <i class="material-icons">verified_user</i>
                <span class="menu-title" data-i18n=""><?= Yii::t('app', 'agents') ?></span>
            </a>
        </li>
    <?php } ?>
<?php } ?>

<?php if (in_array('/blacklist/black-list/index', $permissions) || in_array('/whitelist/white-list/index', $permissions) || in_array('/accessrestriction/access-restriction/index', $permissions)) { ?>

    <li class="navigation-header">
        <a class="navigation-header-text"><?= Yii::t('app', 'security') ?></a>
        <i class="navigation-header-icon material-icons">more_horiz</i>
    </li>
    <?php if (in_array('/accessrestriction/access-restriction/index', $permissions)) { ?>
        <li class="bold">
            <a class="waves-effect waves-cyan" data-href="access-restriction"
               href="<?= Url::to(['/accessrestriction/access-restriction']) ?>">
                <i class="material-icons">no_encryption</i>
                <span class="menu-title" data-i18n=""><?= Yii::t('app', 'accessrestriction') ?></span>
            </a>
        </li>
    <?php } ?>
    <?php if (in_array('/blacklist/black-list/index', $permissions)) { ?>
        <li class="bold">
            <a class="waves-effect waves-cyan" data-href="black-list"
               href="<?= Url::to(['/blacklist/black-list/index']) ?>">
                <i class="material-icons">block</i>
                <span class="menu-title" data-i18n=""><?= Yii::t('app', 'black_list') ?></span>
            </a>
        </li>
    <?php } ?>
    <?php if (in_array('/whitelist/white-list/index', $permissions)) { ?>
        <li class="bold">
            <a class="waves-effect waves-cyan" data-href="white-list"
               href="<?= Url::to(['/whitelist/white-list/index']) ?>">
                <i class="material-icons">check_circle</i>
                <span class="menu-title" data-i18n=""><?= Yii::t('app', 'white_list') ?></span>
            </a>
        </li>
    <?php } ?>

<?php } ?>

<?php if (in_array('/ringgroup/ring-group/index', $permissions)
    || in_array('/didmanagement/did-management/index', $permissions)
    || in_array('/audiomanagement/audiomanagement/index', $permissions)
    || in_array('/autoattendant/autoattendant/index', $permissions)
    || in_array('/conference/conference/index', $permissions)
    || in_array('/queue/queue/index', $permissions)
) { ?>

    <li class="navigation-header">
    <a class="navigation-header-text"><?= Yii::t('app', 'services') ?></a>
    <i class="navigation-header-icon material-icons">more_horiz</i>

    <?php if (in_array('/ringgroup/ring-group/index', $permissions)) { ?>
        <li class="bold">
            <a class="waves-effect waves-cyan " data-href="ring-group"
               href="<?= Url::to(['/ringgroup/ring-group/index']) ?>">
                <i class="material-icons">filter_tilt_shift</i>
                <span class="menu-title" data-i18n=""><?= Yii::t('app', 'ring_group') ?></span></a>
        </li>
    <?php } ?>
    <?php if (in_array('/didmanagement/did-management/index', $permissions)) { ?>
        <li class="bold">
            <a class="waves-effect waves-cyan " data-href="did-management"
               href="<?= Url::to(['/didmanagement/did-management/index']) ?>">
                <i class="material-icons">format_list_numbered</i>
                <span class="menu-title" data-i18n=""><?= Yii::t('app', 'did_management') ?></span></a>
        </li>
    <?php } ?>
    <?php if (in_array('/autoattendant/autoattendant/index', $permissions)) { ?>
        <li class="bold">
            <a class="waves-effect waves-cyan " data-href="autoattendant"
               href="<?= Url::to(['/autoattendant/autoattendant/index']) ?>">
                <i class="material-icons">brightness_auto</i>
                <span class="menu-title" data-i18n=""><?= Yii::t('app', 'ivr') ?></span></a>
        </li>
    <?php } ?>
    <?php if (in_array('/audiomanagement/audiomanagement/index', $permissions)) { ?>
        <li class="bold">
            <a class="waves-effect waves-cyan " data-href="audiomanagement"
               href="<?= Url::to(['/audiomanagement/audiomanagement/index']) ?>">
                <i class="material-icons">queue_music</i>
                <span class="menu-title" data-i18n=""><?= Yii::t('app', 'audio_libraries') ?></span></a>
        </li>
    <?php } ?>
    <?php if (in_array('/conference/conference/index', $permissions)) { ?>
        <li class="bold">
            <a class="waves-effect waves-cyan " data-href="conference"
               href="<?= Url::to(['/conference/conference/index']) ?>">
                <i class="material-icons">group_work</i>
                <span class="menu-title" data-i18n=""><?= Yii::t('app', 'conferences') ?></span></a>
        </li>
    <?php } ?>
    <?php if (in_array('/queue/queue/index', $permissions)) { ?>
        <li class="bold">
            <a class="waves-effect waves-cyan queue-child" data-href="queue"
               href="<?= Url::to(['/queue/queue/index']) ?>">
                <i class="material-icons">queue</i>
                <span class="menu-title" data-i18n=""><?= Yii::t('app', 'queue_management') ?></span></a>
        </li>
    <?php } ?>
<?php } ?>

<?php

if (in_array('/disposition/disposition-master/index', $permissions) ||
    in_array('/leadgroup/leadgroup/index', $permissions) ||
    in_array('/campaign/campaign/index', $permissions) ||
    in_array('/timecondition/time-condition/index', $permissions) ||
    in_array('/script/script/index', $permissions) ||
    in_array('/jobs/job/index', $permissions) ||
    in_array('/redialcall/re-dial-call/index', $permissions)
) {

    ?>
    <li class="navigation-header">
    <a class="navigation-header-text"><?= Yii::t('app', 'campaign') ?></a>
    <i class="navigation-header-icon material-icons">more_horiz</i>
    <?php if (in_array('/disposition/disposition-master/index', $permissions)) { ?>
        <li class="bold">
            <a class="waves-effect waves-cyan " data-href="disposition"
               href="<?= Url::to(['/disposition/disposition-master/index']) ?>">
                <i class="material-icons">group_work</i>
                <span class="menu-title" data-i18n=""><?= Yii::t('app', 'disposition_master') ?></span></a>
        </li>
    <?php } ?>
    <?php if (in_array('/leadgroup/leadgroup/index', $permissions)) { ?>
        <li class="bold">
            <a class="waves-effect waves-cyan " data-href="leadgroup"
               href="<?= Url::to(['/leadgroup/leadgroup/index']) ?>">
                <i class="material-icons">group_work</i>
                <span class="menu-title" data-i18n=""><?= Yii::t('app', 'lead_group') ?></span></a>
        </li>
    <?php } ?>
    <?php if (in_array('/campaign/campaign/index', $permissions)) { ?>
        <li class="bold">
            <a class="waves-effect waves-cyan " data-href="campaign"
               href="<?= Url::to(['/campaign/campaign/index']) ?>">
                <i class="material-icons">call</i>
                <span class="menu-title" data-i18n=""><?= Yii::t('app', 'call_campaign') ?></span></a>
        </li>
    <?php } ?>
    <?php if (in_array('/timecondition/time-condition/index', $permissions)) { ?>
        <li class="bold">
            <a class="waves-effect waves-cyan" data-href="time-condition"
               href="<?= Url::to(['/timecondition/time-condition/index']) ?>">
                <i class="material-icons">av_timer</i>
                <span class="menu-title" data-i18n=""><?= Yii::t('app', 'timeCondition') ?></span>
            </a>
        </li>
    <?php } ?>
    <?php if (in_array('/script/script/index', $permissions)) { ?>
        <li class="bold">
            <a class="waves-effect waves-cyan" data-href="script"
               href="<?= Url::to(['/script/script/index']) ?>">
                <i class="material-icons">record_voice_over</i>
                <span class="menu-title" data-i18n=""><?= Yii::t('app', 'Script') ?></span>
            </a>
        </li>
    <?php } ?>
    <?php if (in_array('/jobs/job/index', $permissions)) { ?>
        <li class="bold">
            <a class="waves-effect waves-cyan" data-href="job"
               href="<?= Url::to(['/jobs/job/index']) ?>">
                <i class="material-icons">work</i>
                <span class="menu-title" data-i18n=""><?= Yii::t('app', 'jobs') ?></span>
            </a>
        </li>
    <?php } ?>

    <?php if (in_array('/redialcall/re-dial-call/index', $permissions)) { ?>
        <li class="bold">
            <a class="waves-effect waves-cyan" data-href="re-dial-call"
               href="<?= Url::to(['/redialcall/re-dial-call/index']) ?>">
                <i class="material-icons">call</i>
                <span class="menu-title" data-i18n=""><?= Yii::t('app', 'redialcall') ?></span>
            </a>
        </li>
    <?php } ?>
    <?php
} ?>

<?php
if (in_array('/cdr/cdr/index', $permissions) ||
    in_array('/blacklistnumberdetails/cdr/index', $permissions) ||
    /* in_array('/queuecallback/queue-callback/index', $permissions) ||*/
    in_array('/abandonedcallreport/abandoned-call-report/index', $permissions) ||
    in_array('/faxdetailsreport/cdr/index', $permissions) ||
    in_array('/fraudcalldetectionreport/cdr/index', $permissions) ||
    in_array('/queuewisereport/queue-wise-report/index', $permissions) ||
    in_array('/agentswisereport/agents-call-report/index', $permissions) ||
    in_array('/extensionsummaryreport/cdr/index', $permissions)) { ?>

    <li class="navigation-header">
    <a class="navigation-header-text"><?= Yii::t('app', 'cdr') ?></a>
    <i class="navigation-header-icon material-icons">more_horiz</i>

    <?php if (in_array('/cdr/cdr/index', $permissions)) { ?>
        <li class="bold">
            <a title="<?= Yii::t('app', 'cdr') ?>" class="waves-effect waves-cyan main-cdr" data-href="cdr"
               href="<?= Url::to(['/cdr/cdr/index']) ?>">
                <i class="material-icons">record_voice_over</i>
                <span class="menu-title" data-i18n=""><?= Yii::t('app', 'cdr') ?></span>
            </a>
        </li>
    <?php } ?>
    <?php if (in_array('/blacklistnumberdetails/cdr/index', $permissions)) { ?>
        <li class="bold">
            <a title="<?= Yii::t('app', 'blacklist_number') ?>" class="waves-effect waves-cyan blacklist-cdr"
               data-href="blacklistnumberdetails"
               href="<?= Url::to(['/blacklistnumberdetails/cdr/index']) ?>">
                <i class="material-icons">block</i>
                <span class="menu-title" data-i18n=""><?= Yii::t('app', 'blacklist_number') ?></span>
            </a>
        </li>
    <?php } ?>

        <!-- <?php /*if (in_array('/queuecallback/queue-callback/index', $permissions)) { */ ?>
        <li class="bold">
            <a title="<?php /*= Yii::t('app', 'queuecallback') */ ?>" class="waves-effect waves-cyan main-queue" data-href="queue-callback"
               href="<?php /*= Url::to(['/queuecallback/queue-callback/index']) */ ?>">
                <i class="material-icons">queue</i>
                <span class="menu-title" data-i18n=""><?php /*= Yii::t('app', 'queuecallback') */ ?></span>
            </a>
        </li>
    --><?php /*} */ ?>

    <?php if (in_array('/abandonedcallreport/abandoned-call-report/index', $permissions)) { ?>
        <li class="bold">
            <a title="<?= Yii::t('app', 'abandonedcallreport') ?>" class="waves-effect waves-cyan"
               data-href="abandoned-call-report"
               href="<?= Url::to(['/abandonedcallreport/abandoned-call-report/index']) ?>">
                <i class="material-icons">call_missed_outgoing</i>
                <span class="menu-title" data-i18n=""><?= Yii::t('app', 'abandonedcallreport') ?></span>
            </a>
        </li>
    <?php } ?>

    <?php if (in_array('/faxdetailsreport/cdr/index', $permissions)) { ?>
        <li class="bold">
            <a title="<?= Yii::t('app', 'fax_detail_report') ?>" class="waves-effect waves-cyan fax-report"
               data-href="faxdetailsreport"
               href="<?= Url::to(['/faxdetailsreport/cdr/index']) ?>">
                <i class="material-icons">email</i>
                <span class="menu-title" data-i18n=""><?= Yii::t('app', 'fax_detail_report') ?></span>
            </a>
        </li>
    <?php } ?>

    <?php if (in_array('/fraudcalldetectionreport/cdr/index', $permissions)) { ?>
        <li class="bold">
            <a title="<?= Yii::t('app', 'detection_report') ?>" class="waves-effect waves-cyan fraud-report"
               data-href="fraudcalldetectionreport"
               href="<?= Url::to(['/fraudcalldetectionreport/cdr/index']) ?>">
                <i class="material-icons">phonelink_setup</i>
                <span class="menu-title" data-i18n=""><?= Yii::t('app', 'detection_report') ?></span>
            </a>
        </li>
    <?php } ?>

    <?php if (in_array('/queuewisereport/queue-wise-report/index', $permissions)) { ?>
        <li class="bold">
            <a title="<?= Yii::t('app', 'queuewise_report') ?>" class="waves-effect waves-cyan"
               data-href="queuewisereport"
               href="<?= Url::to(['/queuewisereport/queue-wise-report/index']) ?>">
                <i class="material-icons">queue</i>
                <span class="menu-title" data-i18n=""><?= Yii::t('app', 'queuewise_report') ?></span>
            </a>
        </li>
    <?php } ?>

    <?php if (in_array('/agentswisereport/agents-call-report/index', $permissions)) { ?>
        <li class="bold">
            <a title="<?= Yii::t('app', 'agents_wise_report') ?>" class="waves-effect waves-cyan agentswisereport-link"
               data-href="agentswisereport"
               href="<?= Url::to(['/agentswisereport/agents-call-report/index']) ?>">
                <i class="material-icons">perm_identity</i>
                <span class="menu-title" data-i18n=""><?= Yii::t('app', 'agents_wise_report') ?></span>
            </a>
        </li>
    <?php } ?>

    <?php if (in_array('/extensionsummaryreport/cdr/index', $permissions)) { ?>
        <li class="bold">
            <a title="<?= Yii::t('app', 'extension_summary_report') ?>" class="waves-effect waves-cyan extension-report"
               data-href="extensionsummaryreport"
               href="<?= Url::to(['/extensionsummaryreport/cdr/index']) ?>">
                <i class="material-icons">extension</i>
                <span class="menu-title" data-i18n=""><?= Yii::t('app', 'extension_summary_report') ?></span>
            </a>
        </li>
    <?php } ?>
    <?php
} ?>

<?php if (in_array('/globalconfig/global-config/index', $permissions) || in_array('/feature/feature/index', $permissions)) { ?>
    <li class="navigation-header">
    <a class="navigation-header-text"><?= Yii::t('app', 'settings') ?></a>
    <i class="navigation-header-icon material-icons">more_horiz</i>
    <?php if (in_array('/globalconfig/global-config/index', $permissions)) { ?>
        <li class="bold ">
            <a class="waves-effect waves-cyan" data-href="global-config"
               href="<?= Url::to(['/globalconfig/global-config/index']) ?>">
                <i class="material-icons">settings</i>
                <span class="menu-title" data-i18n=""><?= Yii::t('app', 'globalconfig') ?></span>
            </a>
        </li>
    <?php } ?>
    <?php if (in_array('/feature/feature/index', $permissions)) { ?>
        <li class="bold last-child">
            <a class="waves-effect waves-cyan" data-href="feature" href="<?= Url::to(['/feature/feature/index']) ?>">
                <i class="material-icons">stars</i>
                <span class="menu-title" data-i18n=""><?= Yii::t('app', 'feature_codes') ?></span>
            </a>
        </li>
    <?php } ?>
<?php } ?>
