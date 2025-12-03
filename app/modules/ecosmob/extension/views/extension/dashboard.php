<?php

use app\modules\ecosmob\extension\assets\ExtensionAsset;
use app\modules\ecosmob\extension\extensionModule;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\ecosmob\extension\models\ExtensionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $extension_number */
/* @var $callRecords */
/* @var $em_extension_name */
/* @var $voiceMsg */
/* @var $em_language_id */
/* @var $callSetting_data */
/* @var $is_online */
/* @var $last_callee */
/* @var $last_caller_details */

ExtensionAsset::register($this);
?>

<?= Yii::$app->view->renderFile('@app/views/auth/iframe/header.php') ?>
<div id="main" class="extension-main main-full">
    <div class="row">
        <div class="col s12">
            <div class="container">
                <div class="content-wrapper-before"></div>
                <div id="">
                    <div class="row">
                        <div class="col s12 p-0">
                            <div class="container">
                                <div id="card-stats">
                                    <div class="row">
                                        <div class="col s12 m6 l4">
                                            <div class="card animate fadeLeft">
                                                <div class="card-content">
                                                    <div class="card-stats-title">
                                                        <p class="card-counter-title"><?= extensionModule::t('app', 'extension_number') ?></p>
                                                        <h4 class="card-stats-number m-0"><?= $extension_number; ?></h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col s12 m6 l4">
                                            <div class="card animate fadeLeft">
                                                <div class="card-content">
                                                    <div class="card-stats-title">
                                                        <p class="card-counter-title"><?= extensionModule::t('app', 'total_recordings') ?></p>
                                                        <a href="/index.php?r=cdr%2Fcdr&amp;isfile=Yes">
                                                            <h4 class="card-stats-number m-0"><?= $callRecords; ?></h4>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col s12 m6 l4">
                                            <div class="card animate fadeRight">
                                                <div class="card-content">
                                                    <div class="card-stats-title">
                                                        <p class="card-counter-title"><?= extensionModule::t('app', 'total_voice_call_msg') ?></p>
                                                        <a href="<?= Url::to(['/voicemsg/voicemail-msgs']) ?>">
                                                            <h4 class="card-stats-number m-0 text-center"><?= $voiceMsg; ?></h4>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="work-collections">
                                    <div class="row">
                                        <div class="col s12 m12 l6">
                                            <ul id="projects-collection" class="collection  animate fadeLeft">
                                                <li class="collection-item">
                                                    <h6 class="collection-header"><?= extensionModule::t('app', 'caller_details') ?></h6>
                                                </li>
                                                <li class="collection-item">
                                                    <div class="row">
                                                        <div class="col s6"><p
                                                                    class="collections-content"><?= extensionModule::t('app', 'sip_user_name') ?>
                                                                : </p>
                                                        </div>
                                                        <div class="col s6"><span
                                                                    class="task-cat cyan"><?= $em_extension_name; ?></span>

                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col s6"><p
                                                                    class="collections-content"><?= extensionModule::t('app', 'language') ?>
                                                                : </p></div>
                                                        <div class="col s6"><span
                                                                    class="task-cat cyan"><?php if ($em_language_id == 1) {
                                                                    echo extensionModule::t('app', 'english');
                                                                } else {
                                                                    echo extensionModule::t('app', 'spanish');
                                                                } ?></span></div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col s6"><p
                                                                    class="collections-content"><?= extensionModule::t('app', 'auto_recording') ?>
                                                                : </p>
                                                        </div>
                                                        <div class="col s6">
                                                            <?php if ($callSetting_data->ecs_auto_recording == 0) { ?>
                                                                <span class="task-cat grey">
                                                        <?php echo extensionModule::t('app', 'disabled'); ?>
                                                </span>
                                                            <?php } else { ?>
                                                                <span class="task-cat cyan">
                                                        <?php echo extensionModule::t('app', 'enabled'); ?>
                                                    </span>
                                                            <?php } ?>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col s6"><p
                                                                    class="collections-content"><?= extensionModule::t('app', 'ring_timeout') ?>
                                                                : </p></div>
                                                        <div class="col s6"><span
                                                                    class=""><?= $callSetting_data->ecs_ring_timeout; ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col s6"><p
                                                                    class="collections-content"><?= extensionModule::t('app', 'dial_timeout') ?>
                                                                : </p></div>
                                                        <div class="col s6"><?= $callSetting_data->ecs_call_timeout; ?>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                        <!--<div class="col s12 m12 l6">
                                            <ul id="issues-collection" class="collection animate fadeRight">
                                                <li class="collection-item">
                                                    <h6 class="collection-header"><?php /*= extensionModule::t('app', 'online_offline') */?></h6>
                                                </li>
                                                <li class="collection-item">
                                                    <div class="row">
                                                        <div class="col s6">
                                                            <p class="collections-content"><?php /*= extensionModule::t('app', 'online_offline') */?>
                                                                : </p>
                                                        </div>
                                                        <div class="col s6">
                                            <span class="task-cat cyan">
                                                <?php
/*                                                if ($is_online) {
                                                    echo extensionModule::t('app', 'online');
                                                } else {
                                                    echo extensionModule::t('app', 'offline');
                                                }
                                                */?>
                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col s6"><p
                                                                    class="collections-content"><?php /*= extensionModule::t('app', 'last_callee') */?>
                                                                : </p>
                                                        </div>
                                                        <div class="col s6">
                                            <span class="task-cat cyan"><?php /*echo $last_callee; */?>
                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col s6">
                                                            <p class="collections-content"><?php /*= extensionModule::t('app', 'last_caller') */?>
                                                                : </p>
                                                        </div>
                                                        <div class="col s6">
                                            <span class="task-cat cyan"><?php /*echo $last_caller_details; */?>
                                            </span>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>-->
                                        <div class="col s12 m12 l6">
                                            <div class="extension-card">
                                                <ul id="issues-collection" class="collection animate fadeRight">
                                                    <li class="collection-item">
                                                        <h6 class="collection-header"><?= extensionModule::t('app', 'extension_setting') ?></h6>
                                                    </li>
                                                    <!--<li class="collection-item" style="padding: 0 20px !important;">
                            <div class="row">
                                <div class="col s7">
                                    <p class="collections-title"><?php /*= extensionModule::t('app', 'shift_forward') */ ?>
                                    </p>
                                </div>
                                <div class="col s3" style="margin-top: 12px;">
                                    <?php /*if ($callSetting_data->ecs_forwarding == 1 && isset($forwarding_data->ef_shift_type) != '') { */ ?>
                                        <span class="task-cat cyan">
                                           <?php /*echo extensionModule::t('app', 'enabled'); */ ?>
                                        </span>
                                    <?php /*} else { */ ?>
                                    <span class="task-cat grey">
                                            <?php /*echo extensionModule::t('app', 'disabled'); */ ?>
                                            <?php /*} */ ?>
                                    </span>
                                </div>
                            </div>
                        </li>-->
                                                    <!--<li class="collection-item" style="padding: 0 20px !important;">
                            <div class="row">
                                <div class="col s7">
                                    <p class="collections-title"><?php /*= extensionModule::t('app', 'universal_forward') */ ?></p>
                                </div>
                                <div class="col s3" style="margin-top: 12px;">
                                    <?php /*if ($callSetting_data->ecs_forwarding == 1 && isset($forwarding_data->ef_universal_type) != '') { */ ?>
                                        <span class="task-cat cyan">
                                           <?php /*echo extensionModule::t('app', 'enabled'); */ ?>
                                        </span>
                                    <?php /*} else { */ ?>
                                        <span class="task-cat grey">
                                           <?php /*echo extensionModule::t('app', 'disabled'); */ ?>
                                        </span>
                                    <?php /*} */ ?>
                                </div>
                            </div>
                        </li>-->
                                                    <!--<li class="collection-item" style="padding: 0 20px !important;">
                            <div class="row">
                                <div class="col s7">
                                    <p class="collections-title">
                                        <strong><?php /*= extensionModule::t('app', 'busy_forward') */ ?></p>
                                </div>
                                <div class="col s3" style="margin-top: 12px;">
                                    <?php /*if ($callSetting_data->ecs_forwarding == 1 && isset($forwarding_data->ef_busy_type) != '') { */ ?>
                                        <span class="task-cat cyan">
                                          <?php /*echo extensionModule::t('app', 'enabled'); */ ?>
                                        </span>
                                    <?php /*} else { */ ?>

                                    <span class="task-cat grey">
                                           <?php /*echo extensionModule::t('app', 'disabled'); */ ?>
                                           <?php /*} */ ?>
                                    </span>
                                </div>
                            </div>
                        </li>-->
                                                    <!--<li class="collection-item" style="padding: 0 20px !important;">
                            <div class="row">
                                <div class="col s7">
                                    <p class="collections-title">
                                        <strong><?php /*= extensionModule::t('app', 'no_ans_forward') */ ?></p>
                                </div>
                                <div class="col s3" style="margin-top: 12px;">
                                    <?php /*if ($callSetting_data->ecs_forwarding == 1 && isset($forwarding_data->ef_no_answer_type) != '') { */ ?>
                                        <span class="task-cat cyan">
                                            <?php /*echo extensionModule::t('app', 'enabled'); */ ?>
                                        </span>
                                    <?php /*} else { */ ?>
                                        <span class="task-cat grey">
                                           <?php /*echo extensionModule::t('app', 'disabled'); */ ?>
                                        </span>
                                    <?php /*} */ ?>
                                </div>
                            </div>
                        </li>-->
                                                    <!-- <li class="collection-item" style="padding: 0 20px !important;">
                            <div class="row">
                                <div class="col s7">
                                    <p class="collections-title">
                                        <strong><?php /*= extensionModule::t('app', 'unavailable_forward') */ ?></p></div>
                                <div class="col s3" style="margin-top: 12px;">
                                    <?php /*if ($callSetting_data->ecs_forwarding == 1 && isset($forwarding_data->ef_unavailable_type) != '') { */ ?>
                                        <span class="task-cat cyan">
                                          <?php /*echo extensionModule::t('app', 'enabled'); */ ?>
                                        </span>
                                    <?php /*} else { */ ?>
                                        <span class="task-cat grey">
                                            <?php /*echo extensionModule::t('app', 'disabled'); */ ?>
                                        </span>
                                    <?php /*} */ ?>
                                </div>
                            </div>
                        </li>-->
                                                    <!--<li class="collection-item" style="padding: 0 20px !important;">
                            <div class="row">
                                <div class="col s7">
                                    <p class="collections-title">
                                        <strong><?php /*= extensionModule::t('app', 'follow_me') */ ?></strong>
                                    </p>
                                </div>
                                <div class="col s3" style="margin-top: 12px;">
                                    <?php /*if ($callSetting_data->ecs_forwarding == 2) { */ ?>
                                        <span class="task-cat cyan">
                                          <?php /*echo extensionModule::t('app', 'enabled'); */ ?>
                                        </span>
                                    <?php /*} else { */ ?>
                                    <span class="task-cat grey">
                                        <?php /*echo extensionModule::t('app', 'disabled'); */ ?>
                                        <?php /*} */ ?>
                                    </span>
                                </div>
                            </div>
                        </li>-->
                                                    <!-- <li class="collection-item" style="padding: 0 20px !important;">
                            <div class="row">
                                <div class="col s7">
                                    <p class="collections-title">
                                        <strong><?php /*= extensionModule::t('app', 'week_off') */ ?></strong>
                                    </p>
                                </div>
                                <div class="col s3" style="margin-top: 12px;">
                                    <?php /*if ($callSetting_data->ecs_forwarding == 1 && isset($forwarding_data->ef_weekoff_type) != '') { */ ?>
                                        <span class="task-cat cyan">
                                            <?php /*echo extensionModule::t('app', 'enabled'); */ ?>
                                        </span>
                                    <?php /*} else { */ ?>
                                        <span class="task-cat grey">
                                           <?php /*echo extensionModule::t('app', 'disabled'); */ ?>
                                        </span>
                                    <?php /*} */ ?>
                                </div>
                            </div>
                        </li>-->
                                                    <!--<li class="collection-item" style="padding: 0 20px !important;">
                            <div class="row">
                                <div class="col s7">
                                    <p class="collections-title">
                                        <strong><?php /*= extensionModule::t('app', 'holiday') */ ?></strong>
                                    </p>
                                </div>
                                <div class="col s3" style="margin-top: 12px;">
                                    <?php /*if ($callSetting_data->ecs_forwarding == 1 && isset($forwarding_data->ef_holiday_type) != '') { */ ?>
                                        <span class="task-cat cyan">
                                           <?php /*echo extensionModule::t('app', 'enabled'); */ ?>
                                        </span>
                                    <?php /*} else { */ ?>
                                    <span class="task-cat grey">
                                            <?php /*echo extensionModule::t('app', 'disabled'); */ ?>
                                            <?php /*} */ ?>
                                        </span>
                                </div>
                            </div>
                        </li>-->
                                                    <!--<li class="collection-item" style="padding: 0 20px !important;">
                            <div class="row">
                                <div class="col s7">
                                    <p class="collections-title">
                                        <strong><?php /*= extensionModule::t('app', 'dial_out') */ ?></strong>
                                    </p>
                                </div>
                                <div class="col s3" style="margin-top: 12px;">
                                    <?php /*if ($callSetting_data->ecs_dial_out == 1) { */ ?>
                                        <span class="task-cat cyan">
                                            <?php /*echo extensionModule::t('app', 'enabled'); */ ?>
                                        </span>
                                    <?php /*} else { */ ?>
                                        <span class="task-cat grey">
                                            <?php /*echo extensionModule::t('app', 'disabled'); */ ?>
                                        </span>
                                    <?php /*} */ ?>
                                </div>
                            </div>
                        </li>-->
                                                    <!--<li class="collection-item" style="padding: 0 20px !important;">
                            <div class="row">
                                <div class="col s7">
                                    <p class="collections-title">
                                        <strong><?php /*= extensionModule::t('app', 'do_not_disturb') */ ?></strong>
                                    </p>
                                </div>
                                <div class="col s3" style="margin-top: 12px;">
                                    <?php /*if ($callSetting_data->ecs_do_not_disturb == 1) { */ ?>
                                        <span class="task-cat cyan">
                                           <?php /*echo extensionModule::t('app', 'enabled'); */ ?>
                                        </span>
                                    <?php /*} else { */ ?>
                                        <span class="task-cat grey">
                                           <?php /*echo extensionModule::t('app', 'disabled'); */ ?>
                                        </span>
                                    <?php /*} */ ?>
                                </div>
                            </div>
                        </li>-->
                                                    <!-- <li class="collection-item" style="padding: 0 20px !important;">
                            <div class="row">
                                <div class="col s7">
                                    <p class="collections-title">
                                        <strong><?php /*= extensionModule::t('app', 'whitelist') */ ?></strong>
                                    </p>
                                </div>
                                <div class="col s3" style="margin-top: 12px;">
                                    <?php /*if ($callSetting_data->ecs_whitelist == 1) { */ ?>
                                        <span class="task-cat cyan">
                                            <?php /*echo extensionModule::t('app', 'enabled'); */ ?>
                                        </span>
                                    <?php /*} else { */ ?>
                                        <span class="task-cat grey">
                                            <?php /*echo extensionModule::t('app', 'disabled'); */ ?>
                                        </span>
                                    <?php /*} */ ?>
                                </div>
                            </div>
                        </li>-->
                                                    <!--<li class="collection-item" style="padding: 0 20px !important;">
                            <div class="row">
                                <div class="col s7">
                                    <p class="collections-title">
                                        <strong><?php /*= extensionModule::t('app', 'blacklist') */ ?></strong>
                                    </p>
                                </div>
                                <div class="col s3" style="margin-top: 12px;">
                                    <?php /*if ($callSetting_data->ecs_blacklist == 1) { */ ?>
                                        <span class="task-cat cyan">
                                          <?php /*echo extensionModule::t('app', 'enabled'); */ ?>
                                        </span>
                                    <?php /*} else { */ ?>
                                        <span class="task-cat grey">
                                            <?php /*echo extensionModule::t('app', 'disabled'); */ ?>
                                        </span>
                                    <?php /*} */ ?>
                                </div>
                            </div>
                        </li>-->
                                                    <!--<li class="collection-item" style="padding: 0 20px !important;">
                            <div class="row">
                                <div class="col s7">
                                    <p class="collections-title">
                                        <strong> <?php /*= extensionModule::t('app', 'caller_id_block') */ ?></strong>
                                    </p>
                                </div>
                                <div class="col s3" style="margin-top: 12px;">
                                    <?php /*if ($callSetting_data->ecs_caller_id_block == 1) { */ ?>
                                        <span class="task-cat cyan">
                                            <?php /*echo extensionModule::t('app', 'enabled'); */ ?>
                                        </span>
                                    <?php /*} else { */ ?>
                                        <span class="task-cat grey">
                                            <?php /*echo extensionModule::t('app', 'disabled'); */ ?>
                                        </span>
                                    <?php /*} */ ?>
                                </div>
                            </div>
                        </li>-->
                                                    <!--<li class="collection-item" style="padding: 0 20px !important;">
                            <div class="row">
                                <div class="col s7">
                                    <p class="collections-title">
                                        <strong> <?php /*= extensionModule::t('app', 'accept_blocked_caller_id') */ ?></strong>
                                    </p>
                                </div>
                                <div class="col s3" style="margin-top: 12px;">
                                    <?php /*if ($callSetting_data->ecs_accept_blocked_caller_id == 1) { */ ?>
                                        <span class="task-cat cyan">
                                            <?php /*echo extensionModule::t('app', 'enabled'); */ ?>
                                    </span>
                                    <?php /*} else { */ ?>
                                        <span class="task-cat grey">
                                            <?php /*echo extensionModule::t('app', 'disabled'); */ ?>
                                            </span>
                                    <?php /*} */ ?>
                                </div>
                            </div>
                        </li>-->
                                                    <li class="collection-item">
                                                        <div class="row">
                                                            <div class="col s6">
                                                                <p class="collections-content">
                                                                    <?= extensionModule::t('app', 'voice_mail') ?>
                                                                </p>
                                                            </div>
                                                            <div class="col s6">
                                                                <?php if ($callSetting_data->ecs_voicemail == 1) { ?>
                                                                    <span class="task-cat cyan">
                                            <?php echo extensionModule::t('app', 'enabled'); ?>
                                        </span>
                                                                <?php } else { ?>
                                                                    <span class="task-cat grey">
                                            <?php echo extensionModule::t('app', 'disabled'); ?>
                                        </span>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <!--<li class="collection-item" style="padding: 0 20px !important;">
                            <div class="row">
                                <div class="col s7">
                                    <p class="collections-title">
                                        <strong>  <?php /*= extensionModule::t('app', 'bargein') */ ?></strong>
                                    </p>
                                </div>
                                <div class="col s3" style="margin-top: 12px;">
                                    <?php /*if ($callSetting_data->ecs_bargein == 1) { */ ?>
                                        <span class="task-cat cyan">
                                            <?php /*echo extensionModule::t('app', 'enabled'); */ ?>
                                        </span>
                                    <?php /*} else { */ ?>
                                        <span class="task-cat grey">
                                          <?php /*echo extensionModule::t('app', 'disabled'); */ ?>
                                        </span>
                                    <?php /*} */ ?>
                                </div>
                            </div>
                        </li>-->
                                                    <!--<li class="collection-item" style="padding: 0 20px !important;">
                            <div class="row">
                                <div class="col s7">
                                    <p class="collections-title">
                                        <strong> <?php /*= extensionModule::t('app', 'transfer') */ ?></strong>
                                    </p>
                                </div>
                                <div class="col s3" style="margin-top: 12px;">
                                    <?php /*if ($callSetting_data->ecs_transfer == 1) { */ ?>
                                        <span class="task-cat cyan">
                                          <?php /*echo extensionModule::t('app', 'enabled'); */ ?>
                                        </span>
                                    <?php /*} else { */ ?>
                                        <span class="task-cat grey">
                                            <?php /*echo extensionModule::t('app', 'disabled'); */ ?>
                                        </span>
                                    <?php /*} */ ?>
                                </div>
                            </div>
                        </li>-->
                                                    <!-- <li class="collection-item" style="padding: 0 20px !important;">
                            <div class="row">
                                <div class="col s7">
                                    <p class="collections-title">
                                        <strong><?php /*= extensionModule::t('app', 'park') */ ?></strong>
                                    </p>
                                </div>
                                <div class="col s3" style="margin-top: 12px;">
                                    <?php /*if ($callSetting_data->ecs_park == 1) { */ ?>
                                        <span class="task-cat cyan">
                                            <?php /*echo extensionModule::t('app', 'enabled'); */ ?>
                                    </span>
                                    <?php /*} else { */ ?>
                                        <span class="task-cat grey">
                                            <?php /*echo extensionModule::t('app', 'disabled'); */ ?>
                                            </span>
                                    <?php /*} */ ?>
                                </div>
                            </div>
                        </li>-->
                                                    <!-- <li class="collection-item" style="padding: 0 20px !important;">
                            <div class="row">
                                <div class="col s7">
                                    <p class="collections-title">
                                        <strong><?php /*= extensionModule::t('app', 'call_recording') */ ?></strong>
                                    </p>
                                </div>
                                <div class="col s3" style="margin-top: 12px;">
                                    <?php /*if ($callSetting_data->ecs_call_recording == 1) { */ ?>
                                        <span class="task-cat cyan">
                                            <?php /*echo extensionModule::t('app', 'enabled'); */ ?>
                                        </span>
                                    <?php /*} else { */ ?>
                                        <span class="task-cat grey">
                                            <?php /*echo extensionModule::t('app', 'disabled'); */ ?>
                                        </span>
                                    <?php /*} */ ?>
                                </div>
                            </div>
                        </li>-->
                                                </ul>
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
</div>
