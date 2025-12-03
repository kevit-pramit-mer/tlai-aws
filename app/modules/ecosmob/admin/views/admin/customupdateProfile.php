<?php

use app\modules\ecosmob\admin\AdminModule;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\admin\models\AdminMaster */

$is_update = "0";
if(isset($_GET['action']) && $_GET['action'] != "")
{
	$is_update = "1";
}
$userType = (isset(Yii::$app->user->identity->adm_is_admin) ? Yii::$app->user->identity->adm_is_admin : '');
?>

<script>
    var hs_custom_search = "<?php echo Yii::t('app', 'search'); ?>";
    var hs_custom_no_matching_records_found = "<?php echo Yii::t('app', 'no_matching_records_found'); ?>";
    var is_update = "<?php echo $is_update; ?>";
    if (is_update == "1") {
        window.parent.document.getElementById('agent_iframe').src = "<?php echo Url::to(['/agents/agents/customdashboard']); ?>";
    }
    var baseURL = '<?= Yii::$app->homeUrl ?>';
    var userType = "<?= $userType ?>";
</script>


<link rel="stylesheet" href="<?php echo Url::base(true) . '/theme/assets/css/general/materialize.css' ?>">
<link rel="stylesheet" href="<?php echo Url::base(true) . '/theme/assets/css/general/style.css' ?>">
<link rel="stylesheet" href="<?php echo Url::base(true) . '/theme/assets/css/general/newvendors.css' ?>">
<link rel="stylesheet" href="<?php echo Url::base(true) . '/theme/assets/css/select2.min.css' ?>">
<link rel="stylesheet" href="<?php echo Url::base(true) . '/theme/assets/css/data-tables/data-tables.css' ?>">
<link rel="stylesheet" href="<?php echo Url::base(true) . '/theme/assets/css/data-tables/jquery.dataTables.min.css' ?>">
<link rel="stylesheet"
      href="<?php echo Url::base(true) . '/theme/assets/css/data-tables/responsive.dataTables.min.css' ?>">
<link rel="stylesheet" href="<?php echo Url::base(true) . '/theme/assets/css/data-tables/select.dataTables.min.css' ?>">

<script type="text/javascript" src="<?php echo Url::base(true) . '/theme/assets/js/jquery.min.js' ?>"></script>
<script type="text/javascript" src="<?php echo Url::base(true) . '/theme/assets/js/plugins.min.js' ?>"></script>
<script type="text/javascript" src="<?php echo Url::base(true) . '/theme/assets/js/vendors.min.js' ?>"></script>

<script type="text/javascript" src="<?php echo Url::base(true) . '/theme/assets/js/select2.min.js' ?>"></script>
<script type="text/javascript"
        src="<?php echo Url::base(true) . '/theme/assets/js/form-mask/form-masks.js' ?>"></script>
<script type="text/javascript"
        src="<?php echo Url::base(true) . '/theme/assets/js/form-mask/form-layouts.js' ?>"></script>
<script type="text/javascript"
        src="<?php echo Url::base(true) . '/theme/assets/js/form-mask/jquery.formatter.min.js' ?>"></script>
<script type="text/javascript"
        src="<?php echo Url::base(true) . '/theme/assets/js/data-tables/data-tables.js' ?>"></script>
<script type="text/javascript"
        src="<?php echo Url::base(true) . '/theme/assets/js/data-tables/jquery.dataTables.min.js' ?>"></script>
<script type="text/javascript"
        src="<?php echo Url::base(true) . '/theme/assets/js/data-tables/dataTables.select.min.js' ?>"></script>
<script type="text/javascript"
        src="<?php echo Url::base(true) . '/theme/assets/js/data-tables/dataTables.responsive.min.js' ?>"></script>
<script type="text/javascript" src="<?php echo Url::base(true) . '/theme/assets/js/multiselect.min.js' ?>"></script>
<script type="text/javascript" src="<?php echo Url::base(true) . '/theme/assets/js/custom.js' ?>"></script>


<?php
$this->params['breadcrumbs'][] = [
    'label' => AdminModule::t('admin', 'update_profile'),
];
$this->params['pageHead'] = AdminModule::t('admin', 'update_profile');
$this->title = AdminModule::t('admin', 'update_profile');
?>


<style>
    #main .content-wrapper-before {
        top: 0 !important;
    }
</style>


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

<?php if (Yii::$app->session->hasFlash('success')) : ?>
    <div class="col s4 right alert set-alert-theme fixed-alert iframe-set" role="alert">
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
                    <button type="button" class="close white-text" data-dismiss="alert"
                            aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php if (Yii::$app->session->hasFlash('error')) : ?>
    <div class="col s4 right alert set-alert-theme fixed-alert iframe-set" role="alert">
        <div class="card-alert card gradient-45deg-red-pink mt-1">
            <div class="row">
                <div class="col s10">
                    <div class="card-content white-text">
                        <p>
                            <i class="material-icons">error</i><?= Yii::$app->session->getFlash('error') ?>
                        </p>
                    </div>
                </div>
                <div class="col s2">
                    <button type="button" class="close white-text" data-dismiss="alert"
                            aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

<?php endif; ?>
<script>
    $(".alert").animate({opacity: 1.0}, 3000).fadeOut("slow");
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

<div id="main" class="<?= $mainClass ?> main-full">
    <div class="row">
        <div class="col s12">
            <div class="container">
                <div class="content-wrapper-before"></div>
                <div class="breadcrumbs-dark col s12 m6" id="breadcrumbs-wrapper">
                    <div class="col m12">
                        <h5 class="breadcrumbs-title mt-0 mb-0"><?= (isset($this->params['pageHead']) ? $this->params['pageHead'] : "") ?></h5>
                        <?= Breadcrumbs::widget([
                            'tag' => 'ol',
                            'options' => ['class' => 'breadcrumbs mb-0',/* 'target' => 'myFrame'*/],
                            'itemTemplate' => "<li class='breadcrumb-item'>{link}</li>\n",
                            'homeLink' => [
                                'label' => Yii::t('yii', 'Home'),
                                // 'url' => Url::to(['/agents/agents/customdashboard']),
                                'url' => 'javascript:void(0);',
                                'encode' => false// Requested feature
                            ],
                            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                        ]) ?>
                    </div>
                </div>
                <div class="col-md-12 profile-contain">
                    <div class="row">
                        <div class="col-xl-9 col-md-7 col-xs-12">
                            <div class="content">
                                <div class="admin-update-profile" id="admin-update-profile">
                                    <?= $this->render('form/custom_updateProfile', [
                                        'model' => $model,
                                    ]) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>






