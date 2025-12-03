<?php

use yii\helpers\Url;
$language = 'en-US';
if (Yii::$app->user->id) {
    if (!empty(Yii::$app->user->identity->adm_language)) {
        $language = Yii::$app->user->identity->adm_language;
    }
}
$supExt = '';
if (Yii::$app->user->identity->adm_is_admin == 'supervisor'){
    $ext = \app\modules\ecosmob\extension\models\Extension::findOne(Yii::$app->user->identity->adm_mapped_extension);
    if(!empty($ext)){
        $supExt = $ext->em_extension_number;
    }
}
$permissions = $GLOBALS['permissions'];
?>

<link rel="stylesheet" href="<?php echo Url::base(true) . '/theme/assets/css/general/flatpickr.min.css' ?>">
<script type="text/javascript" src="<?php echo Url::base(true) . '/theme/assets/js/flatpickr.js' ?>"></script>
<script type="text/javascript" src="<?php echo Url::base(true) . '/theme/assets/js/shortcut-buttons-flatpickr.min.js' ?>"></script>
<script type="text/javascript" src="<?php echo Url::base(true) . '/theme/assets/js/rangePlugin.min.js' ?>"></script>

<ul class="navbar-list right">
    <?php if (!empty($supExt)) { ?>
        <li>
            <?= Yii::t('app', 'extension') .' : '. $supExt; ?>
        </li>
    <?php } ?>
    <li>
        <?php if (Yii::$app->session->hasFlash('success')) : ?>

            <div class="col s4 right alert fixed-alert" role="alert">
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

            <div class="col s4 right info fixed-alert alert" role="alert">
                <div class="card-alert card mt-1" style="background: #43a047">
                    <div class="row">
                        <div class="col s10">
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

            <div class="col s4 right alert fixed-alert" role="alert">
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

            <div class="col s4 right info fixed-alert alert" role="alert">
                <div class="card-alert card mt-1" style="background: red">
                    <div class="row">
                        <div class="col s10">
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

            <div class="col s4 right extensionmsg fixed-alert" role="alert">
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
                                <span aria-hidden="true" style="position:relative;bottom: 8px !important;">×</span>
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
            <?php if ($language == 'es-ES') { ?>
                <span class="flag-icon flag-icon-es"></span>
            <?php } else { ?>
                <span class="flag-icon flag-icon-gb"></span>
            <?php } ?>
        </a></li>
    <li class="hide-on-med-and-down"><a class="waves-effect waves-block waves-light toggle-fullscreen"
                                        href="javascript:void(0);"><i class="material-icons">settings_overscan</i></a>
    </li>
    <li class="hide-on-large-only"><a class="waves-effect waves-block waves-light search-button"
                                      href="javascript:void(0);"><i class="material-icons">search</i></a></li>
    <li>
        <a class="waves-effect waves-block waves-light profile-button" href="javascript:void(0);"
           data-target="profile-dropdown">
            &nbsp;&nbsp;
            <?php
            if(isset(Yii::$app->user->identity)){
                if(isset(Yii::$app->user->identity->adm_firstname)){
                    echo Yii::$app->user->identity->adm_firstname . ' ' . Yii::$app->user->identity->adm_lastname;
                }else{
                    echo '';
                }
            }else{
                echo '';
            }
            ?>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        </a>
    </li>
</ul>
<?php
    if (!Yii::$app->session->get('loginAsExtension')) {
        if (Yii::$app->user->identity->adm_is_admin == 'agent' && isset($_SESSION['extentationNumber'])) { ?>
            <ul class="navbar-list right">
                <li>
                    <?//= 'Extension: ' . $_SESSION['extentationNumber']; ?>
                    <?= Yii::t('app', 'agentextension') . $_SESSION['extentationNumber']; ?>
                </li>
            </ul>
    <?php }
} ?>
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
    <li><a class="grey-text text-darken-1 id-lang" href="<?= Url::to(['/site/change-language', 'lang' => 'en-US', 'ext' => 0]) ?>"
           data-value="en-US"><i class="flag-icon flag-icon-gb"></i> English</a></li>
    <li><a class="grey-text text-darken-1 id-lang" href="<?= Url::to(['/site/change-language', 'lang' => 'es-ES', 'ext' => 0]) ?>"
           data-value="es-ES"><i class="flag-icon flag-icon-es"></i> Spanish</a></li>
</ul>

<ul class="dropdown-content" id="profile-dropdown">
    <?php if (!Yii::$app->session->get('loginAsExtension')) { ?>
        <li><a class="grey-text text-darken-1" href="<?= Url::to(['/admin/admin/update-profile']) ?>"><i
                        class="material-icons">person_outline</i> <?= Yii::t('app',
                    'profile') ?></a></li>
        <li class="divider"></li>
    <?php } else { ?>
        <li><a class="grey-text text-darken-1" href="<?= Url::to(['/extension/extension/update-extension']) ?>"><i
                        class="material-icons">person_outline</i> <?= Yii::t('app',
                    'profile') ?></a></li>
        <li class="divider"></li>

    <?php } ?>
    <li><a class="grey-text text-darken-1" href="<?= Url::to(['/admin/admin/change-password']) ?>"><i
                    class="material-icons">vpn_key</i> <?= Yii::t('app',
                'change_password') ?></a></li>
    <li class="divider"></li>
    <?php if (in_array('/license/license/index', $permissions)){ ?>
    <li><a class="grey-text text-darken-1" href="<?= Url::to(['/license/license/index']) ?>">
            <img src="<?= Url::to("@web/theme/assets/images/license.png") ?>" alt="license_icon" style="width: 20px;margin: 0 5px 0 -2px;vertical-align: middle;font-size: 10px;">
            <?= Yii::t('app', 'account_info') ?></a></li>
    <li class="divider"></li>
    <?php } ?>
    <li>
        <!--<a class="grey-text text-darken-1" href="user-login.html"><i class="material-icons">keyboard_tab</i> Logout</a>-->
        <a class="grey-text text-darken-1"
           href="<?php echo Url::to(['/auth/auth/logout']) ?>" data-method="post"
           role="button"><i class="material-icons">keyboard_tab</i> <?= Yii::t('app', 'logout') ?></a>
    </li>
</ul>
