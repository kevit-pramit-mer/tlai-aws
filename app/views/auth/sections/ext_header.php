<?php

use app\modules\ecosmob\extension\models\Callsettings;
use yii\helpers\Url;
use app\modules\ecosmob\extension\models\Extension;
use app\modules\ecosmob\extension\assets\ExtensionAsset;
use app\modules\ecosmob\phonebook\models\Phonebook;

/*$admin = AdminMaster::findOne(Yii::$app->user->id);
$extensionObj = $admin ? Extension::findOne(['em_id' => $admin->adm_mapped_extension]) : '';*/
$extensionObj = Extension::findOne(Yii::$app->user->id);
$extensionNumber = $extensionObj ? $extensionObj->em_extension_number : '';
if ($extensionNumber) {
    Yii::$app->session->set('extentationNumber', $extensionNumber);
}
if (isset($_SERVER['HTTPS']) &&
    ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) ||
    isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
    $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') {
    $protocol = 'https://';
}
else {
    $protocol = 'http://';
}
ExtensionAsset::register($this);
$em_id = Yii::$app->user->identity->em_id;
$callSetting_data = Callsettings::findOne(['em_id' => $em_id]);
$videoCall = $callSetting_data->ecs_video_calling;
$userType = (isset(Yii::$app->user->identity->adm_is_admin) ? Yii::$app->user->identity->adm_is_admin : '');
?>
<style>
    .cursor-auto{
        cursor: auto !important;
    }
</style>
<script>
    var hasVideo = "<?=$videoCall?>";
    var extensionNumber = "<?=Yii::$app->user->identity->em_extension_number?>";
    var extensionPassword = "<?=Yii::$app->user->identity->em_password?>";
    var extensionName = "<?=Yii::$app->user->identity->em_extension_name?>";
    var wssURL = "wss://"+"<?=$_SERVER['HTTP_HOST']?>";
    var wssPort = "<?=Yii::$app->params['WSS_PORT']?>";
    var domainName = "<?=$_SERVER['HTTP_HOST']?>";
    var callRingFile = "<?=$protocol?><?=$_SERVER['HTTP_HOST']?>"  + '/theme/sound/bell_ring2.mp3';
    var userType = "<?= $userType ?>";

</script>

<link rel="stylesheet" href="<?php echo Url::base(true) . '/theme/assets/css/general/flatpickr.min.css' ?>">
<script type="text/javascript" src="<?php echo Url::base(true) . '/theme/assets/js/flatpickr.js' ?>"></script>
<script type="text/javascript"
        src="<?php echo Url::base(true) . '/theme/assets/js/shortcut-buttons-flatpickr.min.js' ?>"></script>
<script type="text/javascript" src="<?php echo Url::base(true) . '/theme/assets/js/rangePlugin.min.js' ?>"></script>
<script>
    $(document).ready(function(){
        var baseURL = '<?= Yii::$app->homeUrl ?>';
        $('.theme-tabs-calls').tabs({ 'swipeable': false });
        $('#tabs-swipe-demo').tabs({ 'swipeable': false });
        $('#log-tab-index').click(function() {
            setTimeout(() => {
                $('#calls-action-tabs .indicator').css('right', '249px');
            }, 500);
        })
        // close dialer
        $('.close-dialer').click(function(){
            $('.ext-dialer').addClass('d-none');
            $('.bfl-list').removeClass('active');
            $('.dialpad-section, .ext-dialer').removeClass("set-right");
        });
        // open dialer
        $('.dialer-icon-set').click(function(){
            $('.call-forward-dialer-section').addClass("d-none");
            $('.ext-dialer').removeClass('d-none');
            $('#tabs-swipe-demo li a[href="#swipe-tab-1"]')[0].click();
        });
        // dialpad open
        $('.dial-pad-open').click(function(){

            $('.incoming-call').addClass('d-none');
            if(!audioCall){
                $('.dial-pad-Videocall').removeClass("d-none");
            }else{
                $('.dial-pad-call').removeClass("d-none");
            }


        });
        // dialpad clsoe

        // videovcall open
        $(".videocall-enable").click(function(){
            $('.dial-pad-Videocall').removeClass("d-none");
        });
        // close video call
        $('.video-call-hangup').click(function(){
            $('.dial-pad-Videocall, .fullscreen-Videocall').addClass("d-none");
        });
        //fullscreen-video-open
        $(".fullscreen-video-open").click(function(){
            $('.close-fullscreen').removeClass("d-none");
            $('.bfl-list-open, .fullscreen-video-open').addClass("d-none")
            $(this).addClass('d-none');
            $('.dial-pad-Videocall').addClass('fullscreen-Videocall');


        });
        // close-fullscreen
        $(".close-fullscreen").click(function(){
            $('.close-fullscreen').addClass("d-none");
            $('.bfl-list-open, .fullscreen-video-open').removeClass("d-none")
            $('.dial-pad-Videocall').removeClass('fullscreen-Videocall');
        });
        // minimize-dialer
        $(".minimize-dialer").click(function(){
            $('.dial-pad-call, .ext-dialer').addClass("d-none");
            $('.call-minimizer').removeClass("d-none");
        });
        // close minimize-dialer
        $('.close-callminimizer').click(function(){
            $('.dial-pad-call').removeClass("d-none");
            $('.call-minimizer').addClass("d-none");
        });
        // video call confirmation
        $(".video-call-convert").click(function(){
            $('.confimation-popup').removeClass("d-none");
        });

        // reject video call confirmation
        $(".cancel-video-call").click(function(){
            $('.confimation-popup').addClass("d-none");
        });
        // bfl-list-open
      /*  $('.bfl-list-open').click(function(){
            if($('.bfl-list').hasClass('active')){
                $('.bfl-list').removeClass('active');
                $('.dialpad-section, .ext-dialer').removeClass("set-right");
            } else {
                $('.bfl-list').addClass('active');
                $('.dialpad-section, .ext-dialer').addClass("set-right");
            }
        });*/
        // close bfl list in mobile
        $('.close-list').click(function(){
                $('.bfl-list').removeClass('active');
                $('.dialpad-section, .ext-dialer').removeClass("set-right");
        });
        $('#log-tab-index').click(function(){
            $('#calls-action-tabs li a[href="#all-swipe"]')[0].click();
        });

        // show active icons
        $(".deactive-icon").click(function(){
            $(this).removeClass('d-none');
            $(this).next('active-icon').addClass('d-none');
        });

        // show deactive icon
        $(".active-icon").click(function(){
            $(this).removeClass('d-none');
            $(this).next('deactive-icon').addClass('d-none');
        });

        // set tab active
        // $("#tabs-swipe-demo li a#contact-tab-index").click(function(){
        //     setTimeout(function(){
        //         $("#tabs-swipe-demo li a").removeClass('active');
        //         $('#tabs-swipe-demo li a#contact-tab-index').addClass("active");
        //     }, 1000);
        // });
        // $("#tabs-swipe-demo li a").click(function(){
        //     getId =  $(this).attr('id');
        //     if (getId == "contact-tab-index"){
        //         setTimeout(function() {
        //             $("#tabs-swipe-demo li a").removeClass('active');
        //             $('#tabs-swipe-demo li a#contact-tab-index').addClass("active");
        //         }, 300);
        //     } else {
        //         $('#tabs-swipe-demo li a#contact-tab-index').removeClass("active");
        //     }
        // });

        //Make the DIV element draggagle:
        dragElement(document.getElementById("dragDiv"));

        function dragElement(elmnt) {
        var pos1 = 0, pos2 = 0, pos3 = 0, pos4 = 0;
        if (document.getElementById(elmnt.id + "header")) {
            /* if present, the header is where you move the DIV from:*/
            document.getElementById(elmnt.id + "header").onmousedown = dragMouseDown;
        } else {
            /* otherwise, move the DIV from anywhere inside the DIV:*/
            elmnt.onmousedown = dragMouseDown;
        }

        function dragMouseDown(e) {
            e = e || window.event;
            e.preventDefault();
            // get the mouse cursor position at startup:
            pos3 = e.clientX;
            pos4 = e.clientY;
            document.onmouseup = closeDragElement;
            // call a function whenever the cursor moves:
            document.onmousemove = elementDrag;
        }

        function elementDrag(e) {
            e = e || window.event;
            e.preventDefault();
            // calculate the new cursor position:
            pos1 = pos3 - e.clientX;
            pos2 = pos4 - e.clientY;
            pos3 = e.clientX;
            pos4 = e.clientY;
            // set the element's new position:
            elmnt.style.top = (elmnt.offsetTop - pos2) + "px";
            elmnt.style.left = (elmnt.offsetLeft - pos1) + "px";
        }

        function closeDragElement() {
            /* stop moving when mouse button is released:*/
            document.onmouseup = null;
            document.onmousemove = null;
        }
        }
    });
</script>

<ul class="navbar-list right">
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
                            <button type="button" class="close white-text" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        <?php endif; ?>

        <?php if (Yii::$app->session->hasFlash('successimport')) : ?>

            <div class="col s4 right info set-alert-theme" role="alert">
                <div class="card-alert card mt-1" style="background: #43a047">
                    <div class="row">
                        <div class="col s12">
                            <div class="card-content white-text">
                                <p>
                                    <i class="material-icons">error</i><?= Yii::$app->session->getFlash('successimport') ?>
                                </p>
                            </div>
                        </div>
                        <div class="col s2">
                            <button type="button" class="close white-text" data-dismiss="alert" aria-label="Close">
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
                                    <i class="material-icons">error</i><?= Yii::$app->session->getFlash('error') ?></p>
                            </div>
                        </div>
                        <div class="col s2">
                            <button type="button" class="close white-text" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        <?php endif; ?>

        <?php if (Yii::$app->session->hasFlash('errorimport')) : ?>

            <div class="col s4 right info set-alert-theme" role="alert">
                <div class="card-alert card mt-1" style="background: red">
                    <div class="row">
                        <div class="col s12">
                            <div class="card-content white-text">
                                <p>
                                    <i class="material-icons">error</i><?= Yii::$app->session->getFlash('errorimport') ?>
                                </p>
                            </div>
                        </div>
                        <div class="col s2">
                            <button type="button" class="close white-text" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        <?php endif; ?>
        <?php if (Yii::$app->session->hasFlash('extMessage')) : ?>

            <div class="col s4 right extensionmsg set-alert-theme fixed-alert" role="alert">
                <div class="card-alert card gradient-45deg-green-teal mt-1">
                    <div class="row">
                        <div class="col s12">
                            <div class="card-content white-text">
                                <p>
                                    <i class="material-icons">error</i><?= Yii::$app->session->getFlash('extMessage') ?>
                                </p>
                            </div>
                        </div>
                        <div class="col s2">
                            <button type="button" class="close white-text" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </li>
    <audio id="audio-container" controls autoplay="autoplay"
           controlsList="nodownload" playsinline style="display: none"
          ></audio>
    <audio id="ring-audio-container"  loop controls
           controlsList="nodownload" playsinline style="display: none"
    ></audio>
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
            <?php if ($extensionObj->em_language_id == 2) { ?>
                <span class="flag-icon flag-icon-es"></span>
            <?php } else { ?>
                <span class="flag-icon flag-icon-gb"></span>
            <?php } ?>
        </a></li>
    <li class="hide-on-med-and-down">
        <a class="waves-effect waves-block waves-light toggle-fullscreen" href="javascript:void(0);"><i class="material-icons">settings_overscan</i> </a>
    </li>
    <li class="hide-on-large-only">
        <a class="waves-effect waves-block waves-light search-button" href="javascript:void(0);"><i class="material-icons">search</i></a>
    </li>
    <li>
        <a class="waves-effect waves-block waves-light profile-button" href="javascript:void(0);" data-target="profile-dropdown">
            <?php
                echo Yii::$app->user->identity->em_extension_name . ' - ' . Yii::$app->user->identity->em_extension_number;
            ?>
        </a>
    </li>
    <li>
            <img id="enable-call-dialer" src="<?php echo Url::base(true) . '/theme/assets/images/dialer-icon.png' ?>" class="dialer-icon-set"/>
            <img id="disable-call-dialer" src="<?php echo Url::base(true) . '/theme/assets/images/disable-dialer-icon.png' ?>" class="disable-dialer-icon-set d-none"/>
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
    <li><a class="grey-text text-darken-1 id-lang" href="<?= Url::to(['/site/change-language', 'lang' => 'en-US', 'ext' => 1]) ?>"
           data-value="en-US"><i class="flag-icon flag-icon-gb"></i> English</a></li>
    <li><a class="grey-text text-darken-1 id-lang" href="<?= Url::to(['/site/change-language', 'lang' => 'es-ES', 'ext' => 1]) ?>"
           data-value="es-ES"><i class="flag-icon flag-icon-es"></i> Spanish</a></li>
</ul>

<ul class="dropdown-content" id="profile-dropdown">

    <li><a class="grey-text text-darken-1" href="<?= Url::to(['/extension/extension/update-extension']) ?>" target="extensionFrame"><i
                    class="material-icons">person_outline</i> <?= Yii::t('app',
                'profile') ?></a></li>
    <li class="divider"></li>

    <li><a class="grey-text text-darken-1" href="<?= Url::to(['/extension/extension/change-password']) ?>" target="extensionFrame"><i
                    class="material-icons">vpn_key</i> <?= Yii::t('app',
                'change_password') ?></a></li>
    <li class="divider"></li>
    <li>
        <a class="grey-text text-darken-1"
           href="<?php echo Url::to(['/auth/auth/logout']) ?>" data-method="post"
           role="button"><i class="material-icons">keyboard_tab</i> <?= Yii::t('app', 'logout') ?></a>
    </li>
</ul>

<!-- Dialer :: END -->
<div class="dialer-section ext-dialer d-none">
    <div class="container2">
        <div class="d-flex align-items-center justify-content-between action-dialer mb-5">
            <img src="<?php echo Url::base(true) . '/theme/assets/images/back-arrow-icon.png' ?>" width="10" id="backToDialpad" class="d-none"/>
            <img src="<?php echo Url::base(true) . '/theme/assets/images/close-icon.png' ?>" width="10" class="close-dialer" />
            <img src="<?php echo Url::base(true) . '/theme/assets/images/back-arrow.png' ?>"  width="15" class="bfl-list-open"/>
        </div>
        <!-- Dial pad :: BEGIN -->
        <div id="swipe-tab-1">
            <div class="dialer-number-show mb-5">
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
                <div class="digit" id="zeroButton">0
                    <div class="sub">+</div>
                </div>
                <div class="digit">#
                </div>
            </div>
            <div class="row call_dialpad_color">
                <div id="call" class="d-flex align-items-center gap-1">
                    <i class="material-icons dial-pad-open" id="makeAudioCall">local_phone</i>
                    <?php if($videoCall){?>
                    <i class="material-icons videocall-enable" id="makeVideoCall">videocam</i>
                    <?php }else{ ?>
                    <i class="material-icons cursor-auto">videocam_off</i>
                    <?php } ?>
                </div>
            </div>
        </div>
        <!-- Dial pad :: END -->

        <!-- Log section :: BEGIN -->
        <div id="swipe-tab-2" class="call-log-detail-tab">
            <ul id="calls-action-tabs" class="tabs theme-tabs-calls mb-3">
                <li class="tab col s3"><a class="active call-tabs" href="#all-swipe" data-id="1">All</a></li>
                <li class="tab col s3"><a href="#missed-swipe" class="call-tabs" data-id="2">Missed</a></li>
                <li class="tab col s3"><a href="#incoming-swipe" class="call-tabs" data-id="3">Incoming</a></li>
                <li class="tab col s3"><a href="#outgoing-swipe" class="call-tabs" data-id="4">Outgoing</a></li>
            </ul>
            <div id="all-swipe" class="col s12">
                <div class="call-listings">
                    <ul class="collapsible all-swipe-ul">
                        <!--<li>
                            <div class="collapsible-header">
                                <div class="call-lists">
                                    <img src="<?php echo Url::base(true) . '/theme/assets/images/missed-call-icon.png' ?>" />
                                    <div class="caller-detail">
                                        <div>
                                            <p class="caller-name">ABCD</p>
                                            <p class="caller-time">02:30 PM</p>
                                        </div>
                                        <div class="caller-date">Today</div>
                                    </div>
                                </div>
                            </div>
                            <div class="collapsible-body">
                                <div class="call-opiton">
                                    <i class="material-icons videocall-enable">videocam</i>
                                    <i class="material-icons dial-pad-open">local_phone</i>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="collapsible-header">
                                <div class="call-lists">
                                    <img src="<?php /*echo Url::base(true) . '/theme/assets/images/missed-videocall-icon.png' */?>" />
                                    <div class="caller-detail">
                                        <div>
                                            <p class="caller-name">ABCD</p>
                                            <p class="caller-time">02:30 PM</p>
                                        </div>
                                        <div class="caller-date">Today</div>
                                    </div>
                                </div>
                            </div>
                            <div class="collapsible-body">
                                <div class="call-opiton">
                                    <i class="material-icons videocall-enable">videocam</i>
                                    <i class="material-icons dial-pad-open">local_phone</i>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="collapsible-header">
                                <div class="call-lists">
                                    <img src="<?php /*echo Url::base(true) . '/theme/assets/images/incoming-call-icon.png' */?>" />
                                    <div class="caller-detail">
                                        <div>
                                            <p class="caller-name">ABCD</p>
                                            <p class="caller-time">02:30 PM</p>
                                        </div>
                                        <div class="caller-date">Today</div>
                                    </div>
                                </div>
                            </div>
                            <div class="collapsible-body">
                                <div class="call-opiton">
                                    <i class="material-icons videocall-enable">videocam</i>
                                    <i class="material-icons dial-pad-open">local_phone</i>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="collapsible-header">
                                <div class="call-lists">
                                    <img src="<?php /*echo Url::base(true) . '/theme/assets/images/incoming-videocall-icon.png' */?>" />
                                    <div class="caller-detail">
                                        <div>
                                            <p class="caller-name">ABCD</p>
                                            <p class="caller-time">02:30 PM</p>
                                        </div>
                                        <div class="caller-date">Today</div>
                                    </div>
                                </div>
                            </div>
                            <div class="collapsible-body">
                                <div class="call-opiton">
                                    <i class="material-icons videocall-enable">videocam</i>
                                    <i class="material-icons dial-pad-open">local_phone</i>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="collapsible-header">
                                <div class="call-lists">
                                    <img src="<?php /*echo Url::base(true) . '/theme/assets/images/incoming-videocall-icon.png' */?>" />
                                    <div class="caller-detail">
                                        <div>
                                            <p class="caller-name">ABCD</p>
                                            <p class="caller-time">02:30 PM</p>
                                        </div>
                                        <div class="caller-date">Today</div>
                                    </div>
                                </div>
                            </div>
                            <div class="collapsible-body">
                                <div class="call-opiton">
                                    <i class="material-icons videocall-enable">videocam</i>
                                    <i class="material-icons dial-pad-open">local_phone</i>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="collapsible-header">
                                <div class="call-lists">
                                    <img src="<?php /*echo Url::base(true) . '/theme/assets/images/outgoing-call.png' */?>" />
                                    <div class="caller-detail">
                                        <div>
                                            <p class="caller-name">ABCD</p>
                                            <p class="caller-time">02:30 PM</p>
                                        </div>
                                        <div class="caller-date">Today</div>
                                    </div>
                                </div>
                            </div>
                            <div class="collapsible-body">
                                <div class="call-opiton">
                                    <i class="material-icons videocall-enable">videocam</i>
                                    <i class="material-icons dial-pad-open">local_phone</i>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="collapsible-header">
                                <div class="call-lists">
                                    <img src="<?php /*echo Url::base(true) . '/theme/assets/images/outgoing-videocall-icon.png' */?>" />
                                    <div class="caller-detail">
                                        <div>
                                            <p class="caller-name">ABCD</p>
                                            <p class="caller-time">02:30 PM</p>
                                        </div>
                                        <div class="caller-date">Today</div>
                                    </div>
                                </div>
                            </div>
                            <div class="collapsible-body">
                                <div class="call-opiton">
                                    <i class="material-icons videocall-enable">videocam</i>
                                    <i class="material-icons dial-pad-open">local_phone</i>
                                </div>
                            </div>
                        </li>-->
                    </ul>
                </div>
            </div>
            <div id="missed-swipe" class="col s12">
                <div class="call-listings">
                    <ul class="collapsible missed-swipe-ul">
                       <!-- <li>
                            <div class="collapsible-header">
                                <div class="call-lists">
                                    <img src="<?php /*echo Url::base(true) . '/theme/assets/images/missed-call-icon.png' */?>" />
                                    <div class="caller-detail">
                                        <div>
                                            <p class="caller-name">ABCD</p>
                                            <p class="caller-time">02:30 PM</p>
                                        </div>
                                        <div class="caller-date">Today</div>
                                    </div>
                                </div>
                            </div>
                            <div class="collapsible-body">
                                <div class="call-opiton">
                                    <i class="material-icons videocall-enable">videocam</i>
                                    <i class="material-icons dial-pad-open">local_phone</i>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="collapsible-header">
                                <div class="call-lists">
                                    <img src="<?php /*echo Url::base(true) . '/theme/assets/images/missed-videocall-icon.png' */?>" />
                                    <div class="caller-detail">
                                        <div>
                                            <p class="caller-name">ABCD</p>
                                            <p class="caller-time">02:30 PM</p>
                                        </div>
                                        <div class="caller-date">Today</div>
                                    </div>
                                </div>
                            </div>
                            <div class="collapsible-body">
                                <div class="call-opiton">
                                    <i class="material-icons videocall-enable">videocam</i>
                                    <i class="material-icons dial-pad-open">local_phone</i>
                                </div>
                            </div>
                        </li>-->
                    </ul>
                </div>
            </div>
            <div id="incoming-swipe" class="col s12">
                <div class="call-listings">
                    <ul class="collapsible incoming-swipe-ul">
                        <!--<li>
                            <div class="collapsible-header">
                                <div class="call-lists">
                                    <img src="<?php /*echo Url::base(true) . '/theme/assets/images/incoming-call-icon.png' */?>" />
                                    <div class="caller-detail">
                                        <div>
                                            <p class="caller-name">ABCD</p>
                                            <p class="caller-time">02:30 PM</p>
                                        </div>
                                        <div class="caller-date">Today</div>
                                    </div>
                                </div>
                            </div>
                            <div class="collapsible-body">
                                <div class="call-opiton">
                                    <i class="material-icons videocall-enable">videocam</i>
                                    <i class="material-icons dial-pad-open">local_phone</i>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="collapsible-header">
                                <div class="call-lists">
                                    <img src="<?php /*echo Url::base(true) . '/theme/assets/images/incoming-videocall-icon.png' */?>" />
                                    <div class="caller-detail">
                                        <div>
                                            <p class="caller-name">ABCD</p>
                                            <p class="caller-time">02:30 PM</p>
                                        </div>
                                        <div class="caller-date">Today</div>
                                    </div>
                                </div>
                            </div>
                            <div class="collapsible-body">
                                <div class="call-opiton">
                                    <i class="material-icons videocall-enable">videocam</i>
                                    <i class="material-icons dial-pad-open">local_phone</i>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="collapsible-header">
                                <div class="call-lists">
                                    <img src="<?php /*echo Url::base(true) . '/theme/assets/images/incoming-videocall-icon.png' */?>" />
                                    <div class="caller-detail">
                                        <div>
                                            <p class="caller-name">ABCD</p>
                                            <p class="caller-time">02:30 PM</p>
                                        </div>
                                        <div class="caller-date">Today</div>
                                    </div>
                                </div>
                            </div>
                            <div class="collapsible-body">
                                <div class="call-opiton">
                                    <i class="material-icons videocall-enable">videocam</i>
                                    <i class="material-icons dial-pad-open">local_phone</i>
                                </div>
                            </div>
                        </li>-->
                    </ul>
                </div>
            </div>
            <div id="outgoing-swipe" class="col s12">
                <div class="call-listings">
                    <ul class="collapsible outgoing-swipe-ul">
                       <!-- <li>
                            <div class="collapsible-header">
                                <div class="call-lists">
                                    <img src="<?php /*echo Url::base(true) . '/theme/assets/images/outgoing-call.png' */?>" />
                                    <div class="caller-detail">
                                        <div>
                                            <p class="caller-name">ABCD</p>
                                            <p class="caller-time">02:30 PM</p>
                                        </div>
                                        <div class="caller-date">Today</div>
                                    </div>
                                </div>
                            </div>
                            <div class="collapsible-body">
                                <div class="call-opiton">
                                    <i class="material-icons videocall-enable">videocam</i>
                                    <i class="material-icons dial-pad-open">local_phone</i>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="collapsible-header">
                                <div class="call-lists">
                                    <img src="<?php /*echo Url::base(true) . '/theme/assets/images/outgoing-videocall-icon.png' */?>" />
                                    <div class="caller-detail">
                                        <div>
                                            <p class="caller-name">ABCD</p>
                                            <p class="caller-time">02:30 PM</p>
                                        </div>
                                        <div class="caller-date">Today</div>
                                    </div>
                                </div>
                            </div>
                            <div class="collapsible-body">
                                <div class="call-opiton">
                                    <i class="material-icons videocall-enable">videocam</i>
                                    <i class="material-icons dial-pad-open">local_phone</i>
                                </div>
                            </div>
                        </li>-->
                    </ul>
                </div>
            </div>
        </div>
        <!-- Log section :: END -->

        <!-- Phonebook section :: BEGIN -->
        <div id="swipe-tab-3">
            <div class="phonebook-listing">
                <div class="form-group search-phonebook-data">
                    <i class="material-icons">search</i>
                    <input type="text" placeholder="Search Contact" class="form-control" id="search-contacts"/>
                </div>
                <ul class="collapsible contact-ul">
                   <!-- <li>
                        <div class="collapsible-header">
                            <div class="call-lists">
                                <div class="user-initial">A</div>
                                <div class="caller-detail">
                                    <div>
                                        <p class="caller-name">ABCD</p>
                                        <p class="contact-number">1234567890</p>
                                    </div>
                                    <div class="ml-auto">40012</div>
                                </div>
                            </div>
                        </div>
                        <div class="collapsible-body">
                            <div class="call-opiton">
                                <i class="material-icons videocall-enable">videocam</i>
                                <i class="material-icons" class="dial-pad-open">local_phone</i>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="collapsible-header">
                            <div class="call-lists">
                                <div class="user-initial">A</div>
                                <div class="caller-detail">
                                    <div>
                                        <p class="caller-name">ABCD</p>
                                        <p class="contact-number">1234567890</p>
                                    </div>
                                    <div class="ml-auto">40012</div>
                                </div>
                            </div>
                        </div>
                        <div class="collapsible-body">
                            <div class="call-opiton">
                                <i class="material-icons videocall-enable">videocam</i>
                                <i class="material-icons dial-pad-open">local_phone</i>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="collapsible-header">
                            <div class="call-lists">
                                <div class="user-initial">A</div>
                                <div class="caller-detail">
                                    <div>
                                        <p class="caller-name">ABCD</p>
                                        <p class="contact-number">1234567890</p>
                                    </div>
                                    <div class="ml-auto">40012</div>
                                </div>
                            </div>
                        </div>
                        <div class="collapsible-body">
                            <div class="call-opiton">
                                <i class="material-icons videocall-enable">videocam</i>
                                <i class="material-icons dial-pad-open">local_phone</i>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="collapsible-header">
                            <div class="call-lists">
                                <div class="user-initial">A</div>
                                <div class="caller-detail">
                                    <div>
                                        <p class="caller-name">ABCD</p>
                                        <p class="contact-number">1234567890</p>
                                    </div>
                                    <div class="ml-auto">40012</div>
                                </div>
                            </div>
                        </div>
                        <div class="collapsible-body">
                            <div class="call-opiton">
                                <i class="material-icons videocall-enable">videocam</i>
                                <i class="material-icons dial-pad-open">local_phone</i>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="collapsible-header">
                            <div class="call-lists">
                                <div class="user-initial">A</div>
                                <div class="caller-detail">
                                    <div>
                                        <p class="caller-name">ABCD</p>
                                        <p class="contact-number">1234567890</p>
                                    </div>
                                    <div class="ml-auto">40012</div>
                                </div>
                            </div>
                        </div>
                        <div class="collapsible-body">
                            <div class="call-opiton">
                                <i class="material-icons videocall-enable">videocam</i>
                                <i class="material-icons dial-pad-open">local_phone</i>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="collapsible-header">
                            <div class="call-lists">
                                <div class="user-initial">A</div>
                                <div class="caller-detail">
                                    <div>
                                        <p class="caller-name">ABCD</p>
                                        <p class="contact-number">1234567890</p>
                                    </div>
                                    <div class="ml-auto">40012</div>
                                </div>
                            </div>
                        </div>
                        <div class="collapsible-body">
                            <div class="call-opiton">
                                <i class="material-icons videocall-enable">videocam</i>
                                <i class="material-icons dial-pad-open">local_phone</i>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="collapsible-header">
                            <div class="call-lists">
                                <div class="user-initial">A</div>
                                <div class="caller-detail">
                                    <div>
                                        <p class="caller-name">ABCD</p>
                                        <p class="contact-number">1234567890</p>
                                    </div>
                                    <div class="ml-auto">40012</div>
                                </div>
                            </div>
                        </div>
                        <div class="collapsible-body">
                            <div class="call-opiton">
                                <i class="material-icons videocall-enable">videocam</i>
                                <i class="material-icons dial-pad-open">local_phone</i>
                            </div>
                        </div>
                    </li>-->
                </ul>
            </div>
        </div>
        <!-- Phonebook section :: END -->
    </div>
    <!-- Bottom Section :: BEGIN-->
    <div class="dialer-bottom">
        <ul id="tabs-swipe-demo" class="tabs">
            <li class="tab" >
                <a href="#swipe-tab-1" class="active" id="dialer-tab-index">
                    <img src="<?php echo Url::base(true) . '/theme/assets/images/dialer-call-gray.png' ?>" class="non-active-icon" />
                    <img src="<?php echo Url::base(true) . '/theme/assets/images/dialer-call-blue.png' ?>" class="active-icon" />
                    Dialer
                </a>
            </li>
            <li class="tab" >
                <a href="#swipe-tab-2" id="log-tab-index">
                    <img src="<?php echo Url::base(true) . '/theme/assets/images/log-gray.png' ?>" class="non-active-icon" />
                    <img src="<?php echo Url::base(true) . '/theme/assets/images/log-blue.png' ?>" class="active-icon" />
                    Logs
                </a>
            </li>
            <li class="tab" >
                <a href="#swipe-tab-3" class="concat-list" id="contact-tab-index">
                    <img src="<?php echo Url::base(true) . '/theme/assets/images/phonebook-gray.png' ?>" class="non-active-icon" />
                    <img src="<?php echo Url::base(true) . '/theme/assets/images/phonebook-blue.png' ?>" class="active-icon" />
                    Contact
                </a>
            </li>
        </ul>
    </div>
    <!-- Bottom Section :: BEGIN-->
</div>
<!-- Dialer :: END -->

<!-- List of BFL :: BEGIN -->
<div class="bfl-list">
    <div class="close-list"><i class="material-icons">close</i></div>
    <ul class="blf-list-ul">
       <!-- <li class="online-user">
            <a href="#">
                <div class="online"></div>
                Yashaswi Patel
            </a>
        </li>
        <li class="offline-user">
            <a href="#">
                <div class="offline"></div>
                Yashaswi Patel
            </a>
        </li>
        <li class="inactive-user">
            <a href="#">
                <div class="inactive"></div>
                Yashaswi Patel
            </a>
        </li>-->
    </ul>
</div>
<!-- List of BFL :: END -->

<!-- call section :: BEGIN -->
<div class="dial-pad-call dialpad-section d-none">
    <div class="d-flex align-items-center justify-content-between action-dialer mb-5">
        <img src="<?php echo Url::base(true) . '/theme/assets/images/minimize-icon.png' ?>" width="10" class="minimize-dialer" />
        <img src="<?php echo Url::base(true) . '/theme/assets/images/back-arrow.png' ?>"  width="15" class="bfl-list-open" />
    </div>
    <div class="caller-details">
        (555) 234 - 000
    </div>
    <p class="text-center text-primary call-connect">Connecting...</p>
    <!-- Notes :: When call is connected than hide above p tag and show below p tag  -->
    <!-- <p class="text-center text-primary call-connect">00:09.</p> -->
    <img src="<?php echo Url::base(true) . '/theme/assets/images/caller-icon.png' ?>" class="caller-icon" />
    <div class="caller-action" id="caller-action">
        <div class="action-box">
            <img src="<?php echo Url::base(true) . '/theme/assets/images/pause-icon.png' ?>" id="holdCall" class="deactive-icon" />
            <img src="<?php echo Url::base(true) . '/theme/assets/images/play-icon.png' ?>"  id="unholdCall" class="active-icons d-none" />
        </div>
        <div class="action-box">
            <img src="<?php echo Url::base(true) . '/theme/assets/images/mice-icon.png' ?>" id="muteCall" class="deactive-icon" />
            <img src="<?php echo Url::base(true) . '/theme/assets/images/mice-mute-icon.png' ?>" id="unmuteCall" class="active-icons d-none" />
        </div>
        <!-- <div class="action-box">
            <img src="<?php echo Url::base(true) . '/theme/assets/images/add-call-icon.png' ?>"  class="deactive-icon" />
            <img src="<?php echo Url::base(true) . '/theme/assets/images/add-call-active-icon.png' ?>" class="active-icons d-none" />
        </div> -->
        <div class="action-box" >
            <img src="<?php echo Url::base(true) . '/theme/assets/images/forward-icon.png' ?>" id="switchToTransferDialpad" class="deactive-icon" />
            <img src="<?php echo Url::base(true) . '/theme/assets/images/forward-active-icon.png' ?>" class="active-icons d-none" />
        </div>
        <div id="switchToDialer" class="action-box" >
            <img src="<?php echo Url::base(true) . '/theme/assets/images/dialer-icon-gray.png' ?>" id="switchToDialer" class="deactive-icon" />
        </div>
<!--        <div id="turnOnVideo" class="action-box ">-->
<!--            <img src="--><?php //echo Url::base(true) . '/theme/assets/images/video-call.png' ?><!--"  class="video-call-convert"/>-->
<!--            <img src="--><?php //echo Url::base(true) . '/theme/assets/images/video-call-mute.png' ?><!--" class="active-icons d-none" />-->
<!--        </div>-->
        <div id="hangup-call" class="action-box hangup-call">
            <img src="<?php echo Url::base(true) . '/theme/assets/images/hangup-icon.png' ?>" class="call-hangup" />
        </div>
    </div>
    <!-- Video call confirmation section :: BEGIN -->
    <div class="confimation-popup d-none">
        <div class="confirmation-box">
            <p>Enable camera to switch video call?</p>
            <div class="box-confirm-action">
                <button type="cancel" class="btn waves-effect waves-light bg-gray-200 cancel-video-call">No</button>
                <button type="submit" class="btn waves-effect waves-light cyan accent-8 videocall-enable">Yes</button>
            </div>
        </div>
    </div>
    <!-- Video call confirmation section :: END -->
</div>
<!-- call section :: END -->



<!-- call section minimizer :: BEGIN -->
<div class="call-minimizer dialpad-section d-none">
    <img src="<?php echo Url::base(true) . '/theme/assets/images/exit-full-screen-icon.png' ?>" width="10" class="close-callminimizer" />

    <div class="caller-details">
        (555) 234 - 000
    </div>
    <p class="text-center white-text call-connect">Connecting..</p>
    <div class="call-actions-minimizer">
        <div class="action-box">
            <i id="micIcon" class="material-icons" onclick="toggleMic()" data-id="mic-ele">mic</i>
        </div>
        <div class="action-box hangup-call">
            <img src="<?php echo Url::base(true) . '/theme/assets/images/hangup-icon.png' ?>" class="call-hangup" width="30" height="30"/>
        </div>
    </div>
</div>
<!-- call section minimizer :: END -->

<!-- Videocall section :: BEGIN -->
<div class="dial-pad-Videocall dialpad-section d-none">
    <!-- Note::
        When video call starts remove videocall-connecting class
    -->
    <div class="set-video">
        <div class="d-flex align-items-center justify-content-between action-dialer mb-5 pt-5">
            <img src="<?php echo Url::base(true) . '/theme/assets/images/exit-full-screen-icon.png' ?>" width="10" class="close-fullscreen d-none" />
            <img src="<?php echo Url::base(true) . '/theme/assets/images/full-screen-icon.png' ?>" width="10" class="fullscreen-video-open" />
            <img src="<?php echo Url::base(true) . '/theme/assets/images/back-arrow.png' ?>"  width="15" class="bfl-list-open" />
        </div>
        <div class="caller-details white-text">
            (555) 234 - 000
        </div>
        <div class="call-connect white-text">Connecting...</div>
        <!-- Notes :: When call is connected than hide above p tag and show below p tag  -->
        <!-- <p class="text-center white-text call-connect">Connecting...</p> -->
        <video id="remoteVideo" class="recipient-video"></video>
        <video id="localVideo" class="caller-video"></video>
        <!--        <img src="--><?php //echo Url::base(true) . '/theme/assets/images/recipient-video.png' ?><!--"   class="recipient-video"/>-->
        <!--        <img src="--><?php //echo Url::base(true) . '/theme/assets/images/caller-video.png' ?><!--" class="caller-video" />-->
        <div class="video-call-action">
            <div class="action-box">
                <?php if($videoCall){?>
                    <i id="videoIcon" class="material-icons" onclick="toggleVideoIcon()">videocam</i>
                <?php }else{ ?>
                    <i id="videoIcon" class="material-icons cursor-auto">videocam_off</i>
                <?php } ?>
            </div>
            <div class="action-box">
                <i id="micIcon" class="material-icons" onclick="toggleMic()" data-id="mic-ele">mic</i>
            </div>
            <div class="action-box" id="switchToTransferDialpad">
                <i class="material-icons">phone_forwarded</i>
            </div>
            <div class="action-box hangup-call">
                <img src="<?php echo Url::base(true) . '/theme/assets/images/hangup-icon.png' ?>" width="40" height="40" class="video-call-hangup" />
            </div>
        </div>
    </div>
</div>
<!-- Videocall section :: END -->

<!-- fullscreen Videocall section :: BEGIN -->
<div class="fullscreen-Videocall  d-none">
    <div class="set-video">
        <div class="d-flex align-items-center justify-content-between action-dialer mb-2 pt-1">
            <img src="<?php echo Url::base(true) . '/theme/assets/images/exit-full-screen-icon.png' ?>" width="10" class="close-fullscreen" />
            <!-- <img src="<?php echo Url::base(true) . '/theme/assets/images/back-arrow.png' ?>"  width="15" class="bfl-list-open" /> -->
        </div>
        <div class="caller-data-show">
            <div class="caller-details white-text">
                (555) 234 - 000
            </div>
            <div class="calling-time white-text">
                00:05
            </div>
        </div>
        <img src="<?php echo Url::base(true) . '/theme/assets/images/recipient-video.png' ?>"   class="recipient-video"/>
        <img src="<?php echo Url::base(true) . '/theme/assets/images/caller-video.png' ?>" class="caller-video" />
        <div class="video-call-action">
            <div class="action-box">
               <i class="material-icons">videocam</i>
            </div>
            <div class="action-box">
                <i class="material-icons">mic</i>
            </div>
            <div class="action-box">
                <i class="material-icons">phone_forwarded</i>
            </div>
            <div class="action-box hangup-call">
                <img src="<?php echo Url::base(true) . '/theme/assets/images/hangup-icon.png' ?>" width="40" height="40" class="video-call-hangup" />
            </div>
        </div>
    </div>
</div>
<!-- fullscreen section :: END -->

<!-- Incoming call section :: BEGIN -->
<div class="incoming-call dialpad-section d-none">
    <div class="caller-details">
        (654) 234 - 9787
    </div>
    <p class="text-center text-primary call-connect">Incoming Call</p>
    <!-- NOTE
        -------------------------
        If Video call is coming than below text show
    -->
    <!-- <p class="text-center text-primary call-connect">Incoming Video Call</p> -->
    <div class="call-animation">
        <img src="<?php echo Url::base(true) . '/theme/assets/images/caller-icon.png' ?>" class="caller-icon " />
    </div>
    <div class="calling-action">
        <div class="action-box">
            <img id="acceptCall" src="<?php echo Url::base(true) . '/theme/assets/images/incoming-call.png' ?>" width="50"  class="dial-pad-open"/>
            <!-- NOTE
            -------------------------
                If Video call is coming than below icon show
            -->
            <!-- <img src="<?php echo Url::base(true) . '/theme/assets/images/incoming-videocall.png' ?>" width="50" class="videocall-enable" /> -->
        </div>
        <div class="action-box hangup-call">
            <img id="rejectCall" src="<?php echo Url::base(true) . '/theme/assets/images/hangup-icon.png' ?>" class="call-hangup" width="50" />
        </div>

    </div>
</div>
<!-- Incoming call section :: END -->

<!-- Incoming call section minimizer :: BEGIN -->
<div class="dialpad-section incoming-call-minimizer d-none" >
    <div class="incoming-call-info">
        <div class="caller-details">
            (654) 234 - 9787
            <p class="text-center white-text call-connect">Incoming Call...</p>
        </div>
        <div class="call-actions-minimizer">
            <div class="action-box">
                <!-- <img src="<?php echo Url::base(true) . '/theme/assets/images/incoming-call.png' ?>" width="30" class="dial-pad-open" /> -->
                <!-- NOTE
                -------------------------
                    If Video call is coming than below icon show
                -->
                <img src="<?php echo Url::base(true) . '/theme/assets/images/incoming-videocall.png' ?>" width="30" class="videocall-enable" />
            </div>
            <div class="action-box hangup-call ml-10">
            <img src="<?php echo Url::base(true) . '/theme/assets/images/hangup-icon.png' ?>" class="call-hangup" width="30" />
            </div>
        </div>
    </div>
</div>
<!-- call section minimizer :: END -->

<!-- Videocall section minimizer :: BEGIN -->
<div class="dialpad-section video-call-minimizer d-none">
    <!-- <img src="<?php echo Url::base(true) . '/theme/assets/images/exit-full-screen-icon.png' ?>" width="10" class="close-callminimizer" /> -->
    <div class="video-caller-details">
        <img src="<?php echo Url::base(true) . '/theme/assets/images/recipient-video.png' ?>"   class="recipient-video"/>
    </div>
    <p class="text-center white-text call-timer">Connecting..</p>
    <div class="call-actions-minimizer">
        <div class="action-box">
            <i class="material-icons">mic</i>
        </div>
        <div class="action-box hangup-call">
            <img src="<?php echo Url::base(true) . '/theme/assets/images/hangup-icon.png' ?>" class="call-hangup" width="30" height="30"/>
        </div>
    </div>
</div>
<!-- Videocall section minimizer :: END -->

<!-- call forwarding diler :: BEGIN -->
<div class="call-forward-dialer-section d-none">
    <div class="container2">
        <div class="d-flex align-items-center justify-content-between action-dialer mb-5">
            <img src="<?php echo Url::base(true) . '/theme/assets/images/back-arrow-icon.png' ?>" width="10" id="backToDialpad"/>
            <img src="<?php echo Url::base(true) . '/theme/assets/images/back-arrow.png' ?>"  width="15" class="bfl-list-open"/>
        </div>
        <!-- Dial pad :: BEGIN -->
        <div class="call-forward-dialer">
            <div class="dialer-number-show mb-5">
                <input type="text" id="transfer-call-number" class="dialer-input">
                <i id="transfer-text-clear" class="material-icons">backspace</i>
            </div>
            <div class="contact-suggestion">
                <!--<div class="call-lists">
                    <div class="user-initial">A</div>
                    <div class="caller-detail">
                        <div>
                            <p class="caller-name">ABCD</p>
                            <p class="contact-number">1234567890</p>
                        </div>
                        <div class="ml-auto">40012</div>
                    </div>
                </div>
                <div class="call-lists">
                    <div class="user-initial">A</div>
                    <div class="caller-detail">
                        <div>
                            <p class="caller-name">ABCD</p>
                            <p class="contact-number">1234567890</p>
                        </div>
                        <div class="ml-auto">40012</div>
                    </div>
                </div>
                <div class="call-lists">
                    <div class="user-initial">A</div>
                    <div class="caller-detail">
                        <div>
                            <p class="caller-name">ABCD</p>
                            <p class="contact-number">1234567890</p>
                        </div>
                        <div class="ml-auto">40012</div>
                    </div>
                </div>-->
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
                <div class="digit" id="zeroButton_transfer">0
                    <div class="sub">+</div>
                </div>
                <div class="digit">#
                </div>
            </div>
            <div class="row call_dialpad_color" id="call-forward">
                <div  class="d-flex align-items-center gap-1 call-forward" id="transferCall">
                    <i class="material-icons dial-pad-open" >local_phone</i>
                </div>
            </div>
        </div>
        <!-- Dial pad :: END -->
    </div>
</div>
<!-- call forwarding diler :: END -->
