<?php

use app\assets\AuthAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use yii\web\View;

/* @var $this View */
/* @var $content string */

AuthAsset::register($this);
$logo = Yii::getAlias('@web') . "/theme/assets/images/yaco.png";
$faviconIcon = Yii::getAlias('@web') . "/theme/assets/images/favicon.png";
$getLogo = Yii::$app->session->get('getLogo');
if (!empty($getLogo)) {
    if (!empty($getLogo['logo']) && !empty($getLogo['favicon_icon'])) {
        $logo = $getLogo['logo'];
        $faviconIcon = $getLogo['favicon_icon'];
    }
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
$userType = (isset(Yii::$app->user->identity->adm_is_admin) ? Yii::$app->user->identity->adm_is_admin : '');
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" xmlns="http://www.w3.org/1999/html">
<style>
    .navbar nav {
        background: none !important;
    }
</style>
<script>
    var hs_custom_search = "<?php echo Yii::t('app', 'search'); ?>";
    var hs_custom_no_matching_records_found = "<?php echo Yii::t('app', 'no_matching_records_found'); ?>";
    //var wss_url = "<?php //=$_SERVER['HTTP_HOST']?>//";
    //var extensionRegisterURL = "<?php //=$_SERVER['HTTP_HOST']?>//";
    //var baseURL = '<?php //= Yii::$app->homeUrl ?>//';
    var wss_url = "<?=$_SERVER['HTTP_HOST']?>";
    var extensionRegisterURL = "<?=$_SERVER['HTTP_HOST']?>";
    var baseURL = '<?= Yii::$app->homeUrl ?>';
    var wsHostname = "<?=$_SERVER['HTTP_HOST']?>".split(':')[0];
    var wssURL = "ws://" + wsHostname;
    var wssPort = "<?=Yii::$app->params['WSS_PORT']?>";
    var domainName = "<?=$_SERVER['HTTP_HOST']?>";
    var sipDomain = "<?=isset(Yii::$app->params['SIP_DOMAIN']) ? Yii::$app->params['SIP_DOMAIN'] : 'tenant1.teleaon.ai'?>";
    console.log("DEBUG: sipDomain =", sipDomain, "domainName =", domainName);
    var callRingFile = "<?=$protocol?><?=$_SERVER['HTTP_HOST']?>"  + '/theme/sound/bell_ring2.mp3';
    var userType = "<?= $userType ?>";
</script>

<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="<?= $faviconIcon ?>" type="image/x-icon"/>
    <?= Html::csrfMetaTags() ?>
    <?php if (isset(Yii::$app->user->identity->adm_username) && Yii::$app->user->identity->adm_is_admin == 'agent') { ?>
        <title><?= Yii::t('app', 'dashboard') ?></title>
        <?php }elseif(Yii::$app->session->get('loginAsExtension')){ ?>
        <title><?= Yii::t('app', 'extension') ?></title>
    <?php }else{ ?>
        <title><?= Html::encode($this->title) ?></title>
    <?php } ?>
    <?php $this->head() ?>
</head>
<body class="vertical-layout vertical-menu-collapsible page-header-dark vertical-modern-menu 2-columns  "
      data-open="click" data-menu="vertical-modern-menu" data-col="2-columns">

<?php $this->beginBody() ?>

<?php $is_collapsed = 0; ?>

<header class="page-topbar" id="header">
    <div class="navbar navbar-fixed">
        <nav
                class="navbar-main navbar-color nav-collapsible sideNav-lock no-shadow">
            <div class="nav-wrapper" style="background: var(--ec-white-text)">
                <a class="navbar-toggler" href="#">
                    <i class="material-icons custom-sidenav-trigger"><?= ($is_collapsed) ? 'radio_button_unchecked' : 'radio_button_checked' ?></i>
                </a>
                <?php if (isset(Yii::$app->user->identity->adm_username) && Yii::$app->user->identity->adm_is_admin == 'agent') { ?>
                    <?= $this->render('agent/header'); ?>
                <?php } elseif (Yii::$app->session->get('loginAsExtension')) { ?>
                    <?= $this->render('sections/ext_header'); ?>
                <?php } else { ?>
                    <?= $this->render('sections/header'); ?>
                <?php } ?>
            </div>
        </nav>
    </div>
</header>

<aside class="sidenav-main sidenav-light sidenav-active-square nav-collapsible <?= ($is_collapsed) ? 'nav-collapsed' : 'nav-expanded nav-lock' ?> ">
    <div class="brand-sidebar" id="brand-sidebar"
         data-imgsrc="<?= $logo ?>">
        <h1 class="logo-wrapper">
            <?php
            if (isset(Yii::$app->user->identity->adm_username) && Yii::$app->user->identity->adm_is_admin == 'agent') { ?>
                <a class="brand-logo darken-1 remove-active-class-home"
                   href="<?= Url::to(['/agents/agents/customdashboard']); ?>"
                   style="background-image: url('<?= $logo ?>')"
                   target="myFrame">
                </a>
            <?php }else if(isset(Yii::$app->user->identity->em_extension_number)){?>
                <a class="brand-logo darken-1 remove-active-class-home"
                   href="<?= Url::to(['/extension/extension/dashboard']); ?>"
                   style="background-image: url('<?= $logo ?>')"
                   target="extensionFrame">
                </a>
            <?php } else { ?>
                <a class="brand-logo darken-1" href="#"
                   style="background-image: url('<?= $logo ?>')">
                </a>
            <?php } ?>
        </h1>
    </div>
    <ul class="sidenav sidenav-collapsible leftside-navigation collapsible sidenav-fixed menu-shadow" id="slide-out"
        data-menu="menu-navigation" data-collapsible="menu-accordion">
        <?php
        if (isset(Yii::$app->user->identity->adm_username) && Yii::$app->user->identity->adm_is_admin == 'agent') {
            echo $this->render('agent/agent_navigation');
        } elseif (Yii::$app->session->get('loginAsExtension')) {
            echo $this->render('sections/ext_navigation');
        } else if (Yii::$app->user->identity->adm_is_admin == 'supervisor') {
            echo $this->render('sections/supervisor_navigation');
        } else {
            echo $this->render('sections/navigation');
        }
        ?>
    </ul>
    <div class="navigation-background"></div>
    <a class="sidenav-trigger btn-sidenav-toggle btn-floating btn-medium waves-effect waves-light hide-on-large-only"
       href="#" data-target="slide-out"><i class="material-icons">menu</i></a>
</aside>
<?php if (isset(Yii::$app->user->identity->adm_username) && Yii::$app->user->identity->adm_is_admin == 'agent') { ?>
    <iframe width="100%"
            style="height:85% !important; overflow:hidden;border-radius:5px !important;background-color:#fff;"
            frameborder="0" name="myFrame" id="agent_iframe" src="<?= Url::to(['/agents/agents/customdashboard']); ?>">
        <?= $content ?>
    </iframe>

    <?= $this->render('agent/footer'); ?>

    <script>
        $('.navbar-toggler').click(function(){
            if($(this).find('.custom-sidenav-trigger').html() == "radio_button_checked")            {
                $('.sidenav-main').removeClass('nav-expanded nav-lock').addClass('nav-collapsed');
                $('.navbar-main').addClass('nav-collapsed');
                $('#main').addClass('main-full');
                $('footer').addClass('footer-full');
            }else{
                $('.sidenav-main').addClass('nav-expanded nav-lock').removeClass('nav-collapsed');
                $('.navbar-main').removeClass('nav-collapsed');
                $('footer').removeClass('footer-full');
                $('#main').removeClass('main-full');
            }
        })
    </script>

<?php } else if(isset(Yii::$app->user->identity->em_extension_number)){
    ?>
    <iframe width="100%"
            style="height:85% !important; overflow:hidden;border-radius:5px !important;background-color:#fff;"
            frameborder="0" name="extensionFrame" id="extension_iframe" src="<?= Url::to(['/extension/extension/dashboard']); ?>">

        <?= $content ?>
    </iframe>
    <?= $this->render('iframe/footer'); ?>
    <script>
        $('.navbar-toggler').click(function(){
            if($(this).find('.custom-sidenav-trigger').html() == "radio_button_checked"){
                $('.sidenav-main').removeClass('nav-expanded nav-lock').addClass('nav-collapsed');
                $('.navbar-main').addClass('nav-collapsed');
                $('#main').addClass('main-full');
                $('footer').addClass('footer-full');
            }else{
                $('.sidenav-main').addClass('nav-expanded nav-lock').removeClass('nav-collapsed');
                $('.navbar-main').removeClass('nav-collapsed');
                $('footer').removeClass('footer-full');
                $('#main').removeClass('main-full');
            }
        });
    </script>
<?php } else {
    $mainClass = '';
    if (Yii::$app->session->get('loginAsExtension')) {
        $mainClass = 'extension-main';
    } else if (Yii::$app->user->identity->adm_is_admin == 'supervisor') {
        $mainClass = 'supervisor-main';
    } else {
        $mainClass = 'tenant-main';
    }
    ?>
    <div id="main" class="<?= $mainClass ?> <?= ($is_collapsed) ? 'main-full' : '' ?>">
        <div class="row">
            <div class="col s12">
                <div class="container extension-container">
                    <div class="breadcrumbs-dark col s12 m5" id="breadcrumbs-wrapper">
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
                    <?= $content ?>
                </div>
            </div>
        </div>
    </div>

    <?= $this->render('sections/footer'); ?>

    <script>
        $('.navbar-toggler').click(function(){
            if($(this).find('.custom-sidenav-trigger').html() == "radio_button_checked")            {
                $('.sidenav-main').removeClass('nav-expanded nav-lock').addClass('nav-collapsed');
                $('.navbar-main').addClass('nav-collapsed');
                $('footer').addClass('footer-full');
            }else{
                $('.sidenav-main').addClass('nav-expanded nav-lock').removeClass('nav-collapsed');
                $('.navbar-main').removeClass('nav-collapsed');
                $('footer').removeClass('footer-full');
            }
        })
    </script>

<?php } ?>

<script type="application/javascript">
    if (localStorage.getItem("toggle") == 1) { // open

        $('.sidenav-main').addClass('nav-expanded nav-lock').removeClass('nav-collapsed');
        $('.navbar-main').removeClass('nav-collapsed');
        $('.custom-sidenav-trigger').text('radio_button_checked');
        $('#main').removeClass('main-full');
        $('footer').removeClass('footer-full');

    } else { // close
        $('.sidenav-main').removeClass('nav-expanded nav-lock').addClass('nav-collapsed');
        $('.navbar-main').addClass('nav-collapsed');
        $('.custom-sidenav-trigger').text('radio_button_unchecked');
        $('#main').addClass('main-full');
        $('footer').addClass('footer-full');
    }
    $(".alert").animate({opacity: 1.0}, 3000).fadeOut("slow");
    $(".info").animate({opacity: 1.0}, 10000).fadeOut("slow");
    $(".extensionmsg").animate({opacity: 1.0}, 10000).fadeOut("slow");

</script>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
<script type="application/javascript">
    $(document).ready(function() {
        function fadeOutFlashMessages() {
            $("#extension_iframe").on('load', function() {
                $(this).contents().find(".alert").animate({opacity: 1.0}, 3000).fadeOut("slow");
                $(this).contents().find(".info").animate({opacity: 1.0}, 10000).fadeOut("slow");
                $(this).contents().find(".extensionmsg").animate({opacity: 1.0}, 10000).fadeOut("slow");
            });
        }

        // Call the function to fade out flash messages
        fadeOutFlashMessages();
    });
</script>