<?php

use app\assets\AuthAsset;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;
use yii\widgets\Breadcrumbs;

/* @var $this View */
/* @var $content string */

AuthAsset::register($this);
$logo = Yii::getAlias('@web') . "/theme/assets/images/yaco.png";
$faviconIcon = Yii::getAlias('@web') . "/theme/assets/images/favicon.png";
$getLogo = Yii::$app->session->get('getLogo');
if(!empty($getLogo)){
    if(!empty($getLogo['logo']) && !empty($getLogo['favicon_icon'])) {
        $logo = $getLogo['logo'];
        $faviconIcon = $getLogo['favicon_icon'];
    }
}
$userType = (isset(Yii::$app->user->identity->adm_is_admin) ? Yii::$app->user->identity->adm_is_admin : '');
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" xmlns="http://www.w3.org/1999/html">

<script>
    var hs_custom_search = "<?php echo Yii::t('app', 'search'); ?>";
    var hs_custom_no_matching_records_found = "<?php echo Yii::t('app', 'no_matching_records_found'); ?>";
</script>

<?php
//if(isset(Yii::$app->user->identity->adm_username) && Yii::$app->user->identity->adm_is_admin == 'agent')
if (isset(Yii::$app->user->identity->adm_username) && (Yii::$app->user->identity->adm_username == "hardiksarodiya123" || Yii::$app->user->identity->adm_username == "manishthakor123"))
{ ?>
<?php //header('X-Frame-Options: SAMEORIGIN');
//header_remove("X-Frame-Options");
?>
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="<?= $faviconIcon ?>" type="image/x-icon"/>
    <?= Html::csrfMetaTags() ?>
    <!-- title><?php // Html::encode($this->title)
    ?></title -->
    <title><?= Yii::t('app', 'dashboard') ?></title>
    <script>
        var baseURL = '<?= Yii::$app->homeUrl ?>';
        var userType = "<?= $userType ?>";
    </script>
    <?php $this->head() ?>
</head>

<body class="vertical-layout vertical-menu-collapsible page-header-dark vertical-modern-menu 2-columns  "
      data-open="click" data-menu="vertical-modern-menu" data-col="2-columns">

<?php $this->beginBody() ?>

<header class="page-topbar" id="header">
    <div class="navbar navbar-fixed">
        <nav
                class="navbar-main navbar-color nav-collapsible sideNav-lock navbar-dark gradient-45deg-indigo-purple no-shadow">
            <div class="nav-wrapper">

                <?= $this->render('agent/header'); ?>

            </div>
        </nav>
    </div>
</header>

<?php $is_collapsed = 0; ?>

<aside
        class="sidenav-main sidenav-light sidenav-active-square nav-collapsible <?= ($is_collapsed) ? 'nav-collapsed' : 'nav-expanded nav-lock' ?> ">
    <div class="brand-sidebar" id="brand-sidebar"
         data-imgsrc="<?= $logo ?>">
        <h1 class="logo-wrapper">
            <a class="brand-logo darken-1" href="#"
               style="background-image: url('<?= $logo ?>')">
                <!--                <img src="-->
                <? //= Yii::getAlias('@web') . "/theme/assets/images/yaco.png"
                ?><!--" alt="yaco">-->
            </a>
            <a class="navbar-toggler" href="#">
                <i class="material-icons custom-sidenav-trigger"><?= ($is_collapsed) ? 'radio_button_unchecked' : 'radio_button_checked' ?></i>
            </a>
        </h1>
    </div>
    <ul class="sidenav sidenav-collapsible leftside-navigation collapsible sidenav-fixed menu-shadow" id="slide-out"
        data-menu="menu-navigation" data-collapsible="menu-accordion">
        <?php if (Yii::$app->session->get('loginAsExtension')) {
            echo $this->render('agent/ext_navigation');
        } else if (Yii::$app->user->identity->adm_is_admin == 'supervisor') {
            echo $this->render('agent/supervisor_navigation');
        } else if (Yii::$app->user->identity->adm_is_admin == 'agent') {
            echo $this->render('agent/agent_navigation');
        } else {
            echo $this->render('agent/navigation');
        }
        ?>
    </ul>
    <div class="navigation-background"></div>
    <a class="sidenav-trigger btn-sidenav-toggle btn-floating btn-medium waves-effect waves-light hide-on-large-only"
       href="#" data-target="slide-out"><i class="material-icons">menu</i></a>
</aside>

<iframe width="100%" style="height:85% !important; overflow:hidden;border-radius:5px !important;background-color:#fff;"
        frameborder="0" name="myFrame" id="agent_iframe" src="<?= Url::to(['/agents/agents/customdashboard']); ?>">
    <script>
        $(document).ready(function () {
            if (localStorage.getItem("toggle") == 1) { // open
                $('.sidenav-main').addClass('nav-expanded nav-lock').removeClass('nav-collapsed');
                $('.custom-sidenav-trigger').text('radio_button_checked');
                $('#main').removeClass('main-full');
                $('footer').removeClass('footer-full');
            } else { // close
                $('.sidenav-main').removeClass('nav-expanded nav-lock').addClass('nav-collapsed');
                $('.custom-sidenav-trigger').text('radio_button_unchecked');
                $('#main').addClass('main-full');
                $('footer').addClass('footer-full');
            }
        });
    </script>

    <?php /* <div id="main" class="main-full">
	    <div class="row">
		<div class="col s12">

		    <div class="container">

		        <div class="content-wrapper-before"></div>
		        <div class="breadcrumbs-dark pb-0 pt-1 col s6" id="breadcrumbs-wrapper">
		            <!-- Search for small screen-->

		            <div class="col m12">
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


		        </div> <?php */ ?>

    <?= $content ?>

    <?php /* </div>
		</div>
	    </div>
	</div> <?php */ ?>

</iframe>

<?= $this->render('agent/footer'); ?>

<?php $this->endBody() ?>

</body>

</html>
<?php $this->endPage() ?>

    <script>
        $(".alert").animate({opacity: 1.0}, 3000).fadeOut("slow");
    </script>

    <script>
        $(".info").animate({opacity: 1.0}, 10000).fadeOut("slow");
        $(".extensionmsg").animate({opacity: 1.0}, 10000).fadeOut("slow");
    </script>
<?php
}
else { ?>

    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" href="<?= $faviconIcon ?>" type="image/x-icon"/>
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <script>
            var baseURL = '<?= Yii::$app->homeUrl ?>';
        </script>
        <?php $this->head() ?>
    </head>

    <body class="vertical-layout vertical-menu-collapsible page-header-dark vertical-modern-menu 2-columns  "
          data-open="click" data-menu="vertical-modern-menu" data-col="2-columns">

    <?php $this->beginBody() ?>

    <header class="page-topbar" id="header">
        <div class="navbar navbar-fixed">
            <nav
                    class="navbar-main navbar-color nav-collapsible sideNav-lock navbar-dark gradient-45deg-indigo-purple no-shadow">
                <div class="nav-wrapper">

                    <?= $this->render('sections/header'); ?>

                </div>
            </nav>
        </div>
    </header>

    <?php $is_collapsed = 0; ?>

    <aside
            class="sidenav-main sidenav-light sidenav-active-square nav-collapsible <?= ($is_collapsed) ? 'nav-collapsed' : 'nav-expanded nav-lock' ?> ">
        <div class="brand-sidebar" id="brand-sidebar"
             data-imgsrc="<?= $logo ?>">
            <h1 class="logo-wrapper">
                <a class="brand-logo darken-1" href="#"
                   style="background-image: url('<?= $logo ?>')">
                    <!--                <img src="-->
                    <? //= Yii::getAlias('@web') . "/theme/assets/images/yaco.png"
                    ?><!--" alt="yaco">-->
                </a>
                <a class="navbar-toggler" href="#">
                    <i class="material-icons"><?= ($is_collapsed) ? 'radio_button_unchecked' : 'radio_button_checked' ?></i>
                </a></h1>
        </div>
        <ul class="sidenav sidenav-collapsible leftside-navigation collapsible sidenav-fixed menu-shadow" id="slide-out"
            data-menu="menu-navigation" data-collapsible="menu-accordion">
            <?php if (Yii::$app->session->get('loginAsExtension')) {
                echo $this->render('sections/ext_navigation');
            } else if (Yii::$app->user->identity->adm_is_admin == 'supervisor') {
                echo $this->render('sections/supervisor_navigation');
            } else if (Yii::$app->user->identity->adm_is_admin == 'agent') {
                echo $this->render('sections/agent_navigation');
            } else {
                echo $this->render('sections/navigation');
            }
            ?>
        </ul>
        <div class="navigation-background"></div>
        <a class="sidenav-trigger btn-sidenav-toggle btn-floating btn-medium waves-effect waves-light hide-on-large-only"
           href="#" data-target="slide-out"><i class="material-icons">menu</i></a>
    </aside>

    <script>
        $(document).ready(function () {
            if (localStorage.getItem("toggle") == 1) { // open
                $('.sidenav-main').addClass('nav-expanded nav-lock').removeClass('nav-collapsed');
                $('.custom-sidenav-trigger').text('radio_button_checked');
                $('#main').removeClass('main-full');
                $('footer').removeClass('footer-full');
            } else { // close
                $('.sidenav-main').removeClass('nav-expanded nav-lock').addClass('nav-collapsed');
                $('.custom-sidenav-trigger').text('radio_button_unchecked');
                $('#main').addClass('main-full');
                $('footer').addClass('footer-full');
            }
        });
    </script>

    <?php
    $mainClass = '';
    if (Yii::$app->session->get('loginAsExtension')) {
        $mainClass = 'extension-main';
    } else if (Yii::$app->user->identity->adm_is_admin == 'supervisor') {
        $mainClass = 'supervisor-main';
    } else if (Yii::$app->user->identity->adm_is_admin == 'agent') {
        $mainClass = 'agent-main';
    } else {
        $mainClass = 'tenant-main';
    }
    ?>
    <div id="main" class="<?= $mainClass ?> <?= ($is_collapsed) ? 'main-full' : '' ?>">
        <div class="row">
            <div class="col s12">

                <div class="container">

                    <div class="content-wrapper-before"></div>
                    <div class="breadcrumbs-dark p-0 pt-1 col s12 m6 mb-2" id="breadcrumbs-wrapper">
                        <!-- Search for small screen-->
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

    <?php $this->endBody() ?>

    </body>

    </html>
    <?php $this->endPage() ?>

    <script>
        $(".alert").animate({opacity: 1.0}, 3000).fadeOut("slow");
    </script>

    <script>
        $(".info").animate({opacity: 1.0}, 10000).fadeOut("slow");
        $(".extensionmsg").animate({opacity: 1.0}, 10000).fadeOut("slow");
    </script>


    <?php
} ?>
