<?php

use app\modules\ecosmob\phonebook\PhoneBookModule;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\ecosmob\phonebook\models\PhoneBookSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$userType = (isset(Yii::$app->user->identity->adm_is_admin) ? Yii::$app->user->identity->adm_is_admin : '');
?>

<script>
    var hs_custom_search = "<?php echo Yii::t('app', 'search'); ?>";
    var hs_custom_no_matching_records_found = "<?php echo Yii::t('app', 'no_matching_records_found'); ?>";
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

<link rel="stylesheet" href="<?php echo Url::base(true) . '/theme/assets/css/general/flatpickr.min.css' ?>">
<script type="text/javascript" src="<?php echo Url::base(true) . '/theme/assets/js/flatpickr.js' ?>"></script>
<script type="text/javascript"
        src="<?php echo Url::base(true) . '/theme/assets/js/shortcut-buttons-flatpickr.min.js' ?>"></script>
<script type="text/javascript" src="<?php echo Url::base(true) . '/theme/assets/js/rangePlugin.min.js' ?>"></script>

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

<ul class="navbar-list right">
    <li>
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

            <div class="col s4 right alert set-alert-theme fixed-alert iframe-set" role="alert">
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
</ul>


