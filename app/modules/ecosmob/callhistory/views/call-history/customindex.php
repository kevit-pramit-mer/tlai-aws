<?php

use app\components\CommonHelper;
use app\modules\ecosmob\callhistory\CallHistoryModule;
use app\modules\ecosmob\campaign\models\Campaign;
use app\modules\ecosmob\queue\models\QueueMaster;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use app\modules\ecosmob\leadgroupmember\models\LeadGroupMember;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\ecosmob\callhistory\models\CampCdrSearch */
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

<!--<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/shortcut-buttons-flatpickr@0.1.0/dist/shortcut-buttons-flatpickr.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.6.13/plugins/rangePlugin.min.js"></script>-->

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


<?php
$this->title = CallHistoryModule::t('callhistory', 'report');
$this->params['breadcrumbs'][] = $this->title;
$this->params['pageHead'] = $this->title;

$session = yii::$app->session;
$agentCamp = $session->get('selectedCampaign');
?>

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
                        'options' => ['class' => 'breadcrumbs mb-0'/*, 'target' => 'myFrame'*/],
                        'itemTemplate' => "<li class='breadcrumb-item'>{link}</li>\n",
                        'homeLink' => [
                            'label' => Yii::t('app', 'Home'),
                            'url' => Url::to(['/agents/agents/customdashboard']),
                            //'url' => 'javascript:void(0);',
                            'encode' => false
                        ],
                        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                    ]) ?>
                </div>
                <div class="row">
                    <div class="col-xl-9 col-md-7 col-xs-12">
                        <div class="row">
                            <div class="">
                                <div class="profile-contain">
                                    <div class="section section-data-tables">
                                        <div class="row">
                                            <div class="col s12 search-filter">
                                                <?= $this->render('custom_search', ['model' => $searchModel]); ?>
                                            </div>
                                            <div class="col s12">
                                                <div class="card table-structure">
                                                    <div class="card-content">
                                                        <div class="card-header d-flex align-items-center justify-content-between w-100">
                                                            <div class="header-title">
                                                                <?= $this->title ?>
                                                            </div>
                                                            <div class="card-header-btns">
                                                                <?php if (!empty($dataProvider->models)) { ?>
                                                                <?= Html::a(CallHistoryModule::t('callhistory', 'export'), ['/callhistory/call-history/export'], [
                                                                    'id' => 'hov',
                                                                    'data-pjax' => 0,
                                                                    'class' => 'exportbutton lead_group btn waves-effect waves-light darken-1 breadcrumbs-btn right',
                                                                ]) ?><?php } ?>
                                                            </div>
                                                        </div>
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
                                                                        // ['class'=>($agentCamp ? 'yii\grid\SerialColumn' : 'noclass')],
                                                                        [
                                                                            'attribute' => 'id',
                                                                            'visible' => ($agentCamp ? false : true),
                                                                            'label' => CallHistoryModule::t('callhistory', 'action'),
                                                                            //'label'=>'Action',
                                                                            'format' => 'raw',
                                                                            'headerOptions' => ['class' => 'text-center'],
                                                                            //'contentOptions' => ['class' => 'text-center inline-class action_space'],
                                                                            'enableSorting' => True,
                                                                            'value' => function ($model) {

                                                                                $recording_file = $model->recording_file;
                                                                                if ($recording_file != '') {
                                                                                    $end = explode('/', $recording_file);
                                                                                    $end = array_reverse($end)[0];
                                                                                    $audioFilePath = Url::to('@web' . '/media/recordings');
                                                                                    $url = $audioFilePath . '/' . $GLOBALS['tenantID'] . '/' . $end;

                                                                                    return '<audio controls="controls" controlsList="nodownload">
                                                                                            <source src="' . $url . '" type="audio/wav">
                                                                                        </audio>
                                                                                        <a href="' . $url . '" download="' . $url . '"><i class="material-icons" style="color: #474747">file_download</i></a> 
                                                                                        ';

                                                                                } else {
                                                                                    return '-';
                                                                                }

                                                                            },
                                                                        ],
                                                                        [
                                                                            'attribute' => 'customer_first_name',
                                                                            'label' => CallHistoryModule::t('callhistory', 'customer_name'),
                                                                            'headerOptions' => ['class' => 'text-center'],
                                                                            'contentOptions' => ['class' => 'text-center'],
                                                                            'enableSorting' => True,
                                                                            'value' => function ($model) {
                                                                                $camp = '';
                                                                                if (!empty($model->current_active_camp)) {
                                                                                    $camp = $model->current_active_camp;
                                                                                } else {
                                                                                    $camp = $model->camp_name;
                                                                                }
                                                                                if (!empty($camp)) {
                                                                                    $campaign = Campaign::findOne($camp);
                                                                                    if (!empty($campaign)) {
                                                                                        $lead = LeadGroupMember::find()
                                                                                            ->andWhere(['ld_id' => $campaign->cmp_lead_group])
                                                                                            ->andWhere(['or',
                                                                                                ['lg_contact_number' => $model->caller_id_num],
                                                                                                ['lg_contact_number' => $model->dial_number]
                                                                                            ])->one();
                                                                                        if (!empty($lead)) {
                                                                                            return $lead->lg_first_name . ' ' . $lead->lg_last_name;
                                                                                        }else {
                                                                                            return '-';
                                                                                        }
                                                                                    } else {
                                                                                        return '-';
                                                                                    }
                                                                                } else {
                                                                                    return '-';
                                                                                }

//                                                                                $lead = LeadGroupMember::findOne(['lg_contact_number' => $model->dial_number]);
//                                                                                if (!empty($lead)) {
//                                                                                    if (!empty($model->lead_member_id)) {
//                                                                                        $lead = LeadGroupMember::findOne($model->lead_member_id);
//                                                                                        if (!empty($lead)) {
//                                                                                            return $lead->lg_first_name . ' ' . $lead->lg_last_name;
//                                                                                        } else {
//                                                                                            return '-';
//                                                                                        }
//                                                                                    } else {
//                                                                                        if (!empty($model->customer_first_name)) {
//                                                                                            return $model->customer_first_name . ' ' . $model->customer_last_name;
//                                                                                        } else {
//                                                                                            return '-';
//                                                                                        }
//                                                                                    }
//                                                                                } else {
//                                                                                    return '-';
//                                                                                }
                                                                            }
                                                                        ],
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
                                                                        /*[
                                                                            'attribute'=>'extension_number',
                                                                            'label'=>'Caller ID',
                                                                            'headerOptions'=>['class'=>'text-center'],
                                                                            'contentOptions'=>['class'=>'text-center'],
                                                                            'enableSorting'=> TRUE,
                                                                            'value'=>function ($model) {
                                                                                if (!empty($model->extension_number)) {
                                                                                    return $model->extension_number;
                                                                                } else {
                                                                                    return '-';
                                                                                }
                                                                            }
                                                                        ],*/
                                                                        /*    [
                                                                                'attribute'=>'caller_id_num',
                                                                                'label'=>CallHistoryModule::t('callhistory', 'caller_id_num'),
                                                                                //'label'=>'Caller ID',
                                                                                'headerOptions'=>['class'=>'text-center'],
                                                                                'contentOptions'=>['class'=>'text-center'],
                                                                                'enableSorting'=> TRUE,
                                                                                'value'=>function ($model) {
                                                                                    if (!empty($model->caller_id_num)) {
                                                                                        return $model->caller_id_num;
                                                                                    } else {
                                                                                        return '-';
                                                                                    }
                                                                                }
                                                                            ],*/

                                                                        [
                                                                            'attribute' => 'caller_id_num',
                                                                            //'label' => CallHistoryModule::t('callhistory', 'caller_id_num'),
                                                                            //'label'=>'Caller ID',
                                                                            'headerOptions' => ['class' => 'text-center'],
                                                                            'contentOptions' => ['class' => 'text-center'],
                                                                            'enableSorting' => True,
                                                                            'value' => function ($model) {
                                                                                $cmp_type = Campaign::findOne(['cmp_id' => $model->camp_name]);
                                                                                if (($cmp_type->cmp_type == 'Outbound' && $cmp_type->cmp_dialer_type == 'PROGRESSIVE') || ($cmp_type->cmp_type == 'Outbound' && $cmp_type->cmp_dialer_type == 'PREVIEW') || ($cmp_type->cmp_type == 'Blended' && $cmp_type->cmp_dialer_type == 'AUTO' && empty($model->queue))) {
                                                                                    if (!empty($model->extension_number)) {
                                                                                        return $model->extension_number;
                                                                                    } else {
                                                                                        return '-';
                                                                                    }
                                                                                } else {
                                                                                    if (!empty($model->caller_id_num)) {
                                                                                        return $model->caller_id_num;
                                                                                    } else {
                                                                                        return '-';
                                                                                    }
                                                                                }
                                                                            }
                                                                        ],
//                                                                        [
//                                                                            'attribute' => 'did',
//                                                                            'headerOptions' => ['class' => 'text-center'],
//                                                                            'contentOptions' => ['class' => 'text-center'],
//                                                                            'enableSorting' => True,
//                                                                            'value' => function ($model) {
//                                                                                $cmp_type = Campaign::findOne(['cmp_id' => $model->camp_name]);
//                                                                                if (($cmp_type->cmp_type == 'Inbound') || ($cmp_type->cmp_type == 'Blended' && $cmp_type->cmp_dialer_type == 'AUTO' && !empty($model->queue))) {
//                                                                                    if (!empty($model->dial_number)) {
//                                                                                        return $model->dial_number;
//                                                                                    } else {
//                                                                                        return '-';
//                                                                                    }
//                                                                                } else {
//                                                                                    return '-';
//                                                                                }
//                                                                            }
//                                                                        ],
                                                                        [
                                                                            'attribute' => 'queue',
                                                                            'label' => CallHistoryModule::t('callhistory', 'queue'),
                                                                            'headerOptions' => ['class' => 'text-center'],
                                                                            'contentOptions' => ['class' => 'text-center'],
                                                                            'enableSorting' => True,
                                                                            'value' => function ($model) {
                                                                                if (!empty($model->queue)) {
                                                                                    return QueueMaster::getQueueName($model->queue);
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
                                                                                if (!empty($model->current_active_camp)) {
                                                                                    $campaign = Campaign::findOne($model->current_active_camp);
                                                                                    if(!empty($campaign)){
                                                                                            return $campaign->cmp_name;
                                                                                        } else {
                                                                                            return '-';
                                                                                        }
                                                                                }else {
                                                                                    if (!empty($model->campaign_name)) {
                                                                                        return $model->campaign_name;
                                                                                    } else {
                                                                                        return '-';
                                                                                    }
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
                                                                            'attribute' => 'ans_time',
                                                                            'headerOptions' => ['class' => 'text-center'],
                                                                            'contentOptions' => ['class' => 'text-center'],
                                                                            'enableSorting' => True,
                                                                            'value' => function ($model) {
                                                                                if (!empty($model->ans_time)) {
                                                                                    return CommonHelper::tsToDt($model->ans_time);
                                                                                } else {
                                                                                    return '-';
                                                                                }
                                                                            }
                                                                        ],

                                                                        [
                                                                            'attribute' => 'end_time',
                                                                            'headerOptions' => ['class' => 'text-center'],
                                                                            'contentOptions' => ['class' => 'text-center'],
                                                                            'enableSorting' => True,
                                                                            'value' => function ($model) {
                                                                                if (!empty($model->end_time)) {
                                                                                    return CommonHelper::tsToDt($model->end_time);
                                                                                } else {
                                                                                    return '-';
                                                                                }
                                                                            }
                                                                        ],
                                                                        [
                                                                            'attribute' => 'call_waiting',
                                                                            'headerOptions' => ['class' => 'text-center'],
                                                                            'contentOptions' => ['class' => 'text-center'],
                                                                            'enableSorting' => True,
                                                                            'value' => function ($model) {
                                                                                if (!empty($model->call_waiting)) {
                                                                                    return gmdate("H:i:s", $model->call_waiting);
                                                                                } else {
                                                                                    return '-';
                                                                                }
                                                                            }
                                                                        ],
                                                                        [
                                                                            'attribute' => 'call_duration',
                                                                            'headerOptions' => ['class' => 'text-center'],
                                                                            'contentOptions' => ['class' => 'text-center'],
                                                                            'enableSorting' => True,
                                                                            'value' => function ($model) {
                                                                                if (!empty($model->call_duration)) {
                                                                                    return gmdate("H:i:s", $model->call_duration);
                                                                                } else {
                                                                                    return '-';
                                                                                }
                                                                            }
                                                                        ],
                                                                        [
                                                                            'attribute' => 'agent_duration',
                                                                            'headerOptions' => ['class' => 'text-center'],
                                                                            'contentOptions' => ['class' => 'text-center'],
                                                                            'enableSorting' => True,
                                                                            'value' => function ($model) {
                                                                                if (!empty($model->agent_duration)) {
                                                                                    return gmdate("H:i:s", $model->agent_duration);
                                                                                } else {
                                                                                    return '-';
                                                                                }
                                                                            }
                                                                        ],
                                                                        [
                                                                            'attribute' => 'call_disposion_name',
                                                                            'label' => CallHistoryModule::t('callhistory', 'disposition'),
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
                                                                            'headerOptions' => ['class' => 'text-center', 'style' => 'min-width: 160px !important;max-width: 160px !important; word-wrap:break-word'],
                                                                            'contentOptions' => ['class' => 'text-center', 'style' => 'min-width: 160px !important;max-width: 160px !important; word-wrap:break-word'],
                                                                            'enableSorting' => True,
                                                                            'value' => function ($model) {
                                                                                if (!empty($model->disposition_comment)) {
                                                                                    return $model->disposition_comment;
                                                                                } else {
                                                                                    return '-';
                                                                                }
                                                                            }
                                                                        ],
                                                                        [
                                                                            'attribute' => 'call_status',
                                                                            'headerOptions' => ['class' => 'text-center'],
                                                                            'contentOptions' => ['class' => 'text-center'],
                                                                            'enableSorting' => True,
                                                                            'value' => function ($model) {
                                                                                if (!empty($model->call_status)) {
                                                                                    return $model->call_status;
                                                                                } else {
                                                                                    return '-';
                                                                                }
                                                                            }
                                                                        ],

                                                                    ],
                                                                    'tableOptions' => [
                                                                        'class' => 'display dataTable dtr-inline'
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


<!--<script>
    function checkCount(totalData, errorMessage) {
        if (totalData <= 0) {
            alert(errorMessage);
            return false;
        }
    }

    $(document).on('click', '.exportbutton', function () {
        return checkCount(0, 'No records found to export');
    });

</script>-->

<?php
/*$this->registerJs("
    $(document).on('click', '.exportbutton', function () {
        return checkCount($dataProvider->count, '" . CallHistoryModule::t('callhistory', 'no_records_found_to_export') . "');
    });");*/
?>
