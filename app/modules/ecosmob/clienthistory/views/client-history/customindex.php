<?php

use app\components\CommonHelper;
use app\modules\ecosmob\clienthistory\ClientHistoryModule;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\ecosmob\clienthistory\models\CampCdrSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = ClientHistoryModule::t('clienthistory', 'clt_history');
$this->params['breadcrumbs'][] = $this->title;
$this->params['pageHead'] = $this->title;
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

<!--<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/shortcut-buttons-flatpickr@0.1.0/dist/shortcut-buttons-flatpickr.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/plugins/rangePlugin.min.js"></script>-->

<link rel="stylesheet" href="<?php echo Url::base(true) . '/theme/assets/css/general/flatpickr.min.css' ?>">
<script type="text/javascript" src="<?php echo Url::base(true) . '/theme/assets/js/flatpickr.js' ?>"></script>
<script type="text/javascript" src="<?php echo Url::base(true) . '/theme/assets/js/shortcut-buttons-flatpickr.min.js' ?>"></script>
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
<div id="main" class="<?= $mainClass ?> main-full">
    <div class="row">
        <div class="col s12">

            <div class="container">

                <div class="content-wrapper-before"></div>
                <div class="breadcrumbs-dark col s12 m6" id="breadcrumbs-wrapper">
                    <h5 class="breadcrumbs-title mt-0 mb-0"><?= (isset($this->params['pageHead']) ? $this->params['pageHead'] : "") ?></h5>
                    <?= Breadcrumbs::widget([
                        'tag' => 'ol',
                        'options' => ['class' => 'breadcrumbs mb-0'],
                        'itemTemplate' => "<li class='breadcrumb-item'>{link}</li>\n",
                        'homeLink' => [
                            'label' => Yii::t('yii', 'Home'),
                            'url' => Url::to(['/agents/agents/customdashboard']),
                            //'url' => 'javascript:void(0);',
                            'encode' => false
                        ],
                        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                    ]) ?>
                </div>


                <?php if (!empty($dataProvider->models)) { ?>
                <div class="col s12 m6 pt-1 pb-1 pr-0">
                    <?= Html::a(ClientHistoryModule::t('clienthistory', 'export'), ['/clienthistory/client-history/export'], [
                        'id' => 'hov',
                        'data-pjax' => 0,
                        'class' => 'exportbutton lead_group btn waves-effect waves-light darken-1 breadcrumbs-btn right',
                    ]) ?><?php } ?>
                </div>
                <div class="row">
                    <div class="col-xl-9 col-md-7 col-xs-12">
                        <div class="row">
                            <div class="col s12">
                                <div class="profile-contain">
                                    <div class="section section-data-tables">
                                        <div class="row">
                                            <div class="col s12 search-filter">
                                                <?= $this->render('custom_search', ['model' => $searchModel]); ?>
                                            </div>
                                            <div class="col s12">
                                                <div class="card">
                                                    <div class="card-content">
                                                        <div class="dataTables_wrapper" id="page-length-option_wrapper">
                                                            <?php try {
                                                                echo GridView::widget([
                                                                    'id' => 'camp-cdr-grid-index', // TODO : Add Grid Widget ID
                                                                    'dataProvider' => $dataProvider,
                                                                    'layout' => Yii::$app->layoutHelper->get_layout_str('#camp-cdr-search-form'),
                                                                    'showOnEmpty' => true,
                                                                    'pager' => [
                                                                        'prevPageLabel' => '<a class="paginate_button previous disabled" aria-controls="data-table-row-grouping" data-dt-idx="0" tabindex="0" id="data-table-row-grouping_previous">' . Yii::t('app', 'previous') . '</a>',
                                                                        'nextPageLabel' => '<a class="paginate_button next" aria-controls="data-table-row-grouping" data-dt-idx="4" tabindex="0" id="data-table-row-grouping_next">' . Yii::t('app', 'next') . '</a>',
                                                                        'maxButtonCount' => 5,
                                                                    ],
                                                                    'options' => [
                                                                        'tag' => false,
                                                                    ],
                                                                    'columns' => [
                                                                        ['class' => 'yii\grid\SerialColumn'],
                                                                        [
                                                                            'attribute' => 'dial_number',
                                                                            'headerOptions' => ['class' => 'text-center'],
                                                                            'contentOptions' => ['class' => 'text-center'],
                                                                            'enableSorting' => True,
                                                                            'value' => function ($model) {
                                                                                if (!empty($model->dial_number)) {
                                                                                    return $model->dial_number;
                                                                                } else {
                                                                                    return '-';
                                                                                }
                                                                            }
                                                                        ],
                                                                        [
                                                                            'attribute' => 'call_disposion_name',
                                                                            'headerOptions' => ['class' => 'text-center'],
                                                                            'contentOptions' => ['class' => 'text-center'],
                                                                            'enableSorting' => True,
                                                                            'value' => function ($model) {
                                                                                if (!empty($model->call_disposion_name)) {
                                                                                    return $model->call_disposion_name;
                                                                                } else {
                                                                                    return '-';
                                                                                }
                                                                            }
                                                                        ],
                                                                        [
                                                                            'attribute' => 'disposition_comment',
                                                                            'headerOptions' => ['class' => 'text-center', 'style' => 'min-width: 150px !important;max-width: 150px !important; word-wrap:break-word'],
                                                                            'contentOptions' => ['class' => 'text-center', 'style' => 'min-width: 150px !important;max-width: 150px !important; word-wrap:break-word'],
                                                                            'enableSorting' => True,
                                                                            'value' => function ($model) {
                                                                                if (!empty($model->disposition_comment)) {
                                                                                    return $model->disposition_comment;
                                                                                } else {
                                                                                    return '-';
                                                                                }
                                                                            }
                                                                        ],
                                                                        /*[
                                                                            'attribute' => 'call_disposion_decription',
                                                                            'headerOptions' => ['class' => 'text-center'],
                                                                            'contentOptions' => ['class' => 'text-center'],
                                                                            'enableSorting' => True,
                                                                            'value' => function ($model) {
                                                                                if (!empty($model->call_disposion_decription)) {
                                                                                    return $model->call_disposion_decription;
                                                                                } else {
                                                                                    return '-';
                                                                                }
                                                                            }
                                                                        ],*/
                                                                        [
                                                                            'attribute' => 'agent_first_name',
                                                                            'headerOptions' => ['class' => 'text-center'],
                                                                            'contentOptions' => ['class' => 'text-center'],
                                                                            'enableSorting' => True,
                                                                            'value' => function ($model) {
                                                                                if (!empty($model->agent_first_name)) {
                                                                                    return $model->agent_first_name;
                                                                                } else {
                                                                                    return '-';
                                                                                }
                                                                            }
                                                                        ],
                                                                        [
                                                                            'attribute' => 'agent_last_name',
                                                                            'headerOptions' => ['class' => 'text-center'],
                                                                            'contentOptions' => ['class' => 'text-center'],
                                                                            'enableSorting' => True,
                                                                            'value' => function ($model) {
                                                                                if (!empty($model->agent_last_name)) {
                                                                                    return $model->agent_last_name;
                                                                                } else {
                                                                                    return '-';
                                                                                }
                                                                            }
                                                                        ],
                                                                        [
                                                                            'attribute' => 'customer_first_name',
                                                                            'headerOptions' => ['class' => 'text-center'],
                                                                            'contentOptions' => ['class' => 'text-center'],
                                                                            'enableSorting' => True,
                                                                            'value' => function ($model) {
                                                                                if (!empty($model->customer_first_name)) {
                                                                                    return $model->customer_first_name;
                                                                                } else {
                                                                                    return '-';
                                                                                }
                                                                            }
                                                                        ],
                                                                        [
                                                                            'attribute' => 'customer_last_name',
                                                                            'headerOptions' => ['class' => 'text-center'],
                                                                            'contentOptions' => ['class' => 'text-center'],
                                                                            'enableSorting' => True,
                                                                            'value' => function ($model) {
                                                                                if (!empty($model->customer_last_name)) {
                                                                                    return $model->customer_last_name;
                                                                                } else {
                                                                                    return '-';
                                                                                }
                                                                            }
                                                                        ],
                                                                        [
                                                                            'attribute' => 'call_disposion_start_time',
                                                                            'headerOptions' => ['class' => 'text-center'],
                                                                            'contentOptions' => ['class' => 'text-center'],
                                                                            'enableSorting' => True,
                                                                            'value' => function ($model) {
                                                                                if (!empty($model->call_disposion_start_time)) {
                                                                                    return CommonHelper::tsToDt($model->call_disposion_start_time);
                                                                                } else {
                                                                                    return '-';
                                                                                }
                                                                            }
                                                                        ],
                                                                        [
                                                                            'attribute' => 'start_time',
                                                                            'headerOptions' => ['class' => 'text-center'],
                                                                            'contentOptions' => ['class' => 'text-center'],
                                                                            'enableSorting' => True,
                                                                            'value' => function ($model) {
                                                                                if (!empty($model->start_time)) {
                                                                                    return CommonHelper::tsToDt($model->start_time);
                                                                                } else {
                                                                                    return '-';
                                                                                }
                                                                            }
                                                                        ],
                                                                        [
                                                                            'attribute' => 'campaign_name',
                                                                            'headerOptions' => ['class' => 'text-center'],
                                                                            'contentOptions' => ['class' => 'text-center'],
                                                                            'enableSorting' => True,
                                                                            'value' => function ($model) {
                                                                                if (!empty($model->campaign_name)) {
                                                                                    return $model->campaign_name;
                                                                                } else {
                                                                                    return '-';
                                                                                }
                                                                            }
                                                                        ],

                                                                    ],
                                                                    'tableOptions' => [
                                                                        'class' => 'display dataTable dtr-inline',

                                                                    ],
                                                                ]);
                                                            } catch (Exception $e) {
                                                            }
                                                            ?>
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
        </div>
    </div>
</div>

<style>
    #main .content-wrapper-before {
        top: 0 !important;
    }
</style>

<?php
$this->registerJs("
    $(document).on('click', '.exportbutton', function () {
        return checkCount($dataProvider->count, '" . ClientHistoryModule::t('clienthistory', 'no_records_found_to_export') . "');
    });");
?>

