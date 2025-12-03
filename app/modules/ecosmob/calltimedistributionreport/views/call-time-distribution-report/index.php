<?php

use app\modules\ecosmob\calltimedistributionreport\CallTimeDistributionReportModule;
use app\modules\ecosmob\calltimedistributionreport\models\CallTimeDistributionReport;
use app\modules\ecosmob\calltimedistributionreport\models\CallTimeDistributionReportSearch;
use app\modules\ecosmob\queue\models\QueueMaster;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel CallTimeDistributionReportSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $total */

$this->title = CallTimeDistributionReportModule::t('calltimedistributionreport', 'call_time_distribution_report');
$this->params['breadcrumbs'][] = $this->title;
$this->params['pageHead'] = $this->title;
?>

<?php Pjax::begin(['id' => 'call-time-distribution-report-index', 'timeout' => 0, 'enablePushState' => false]); ?>

<div class="row">
    <div class="col-xl-9 col-md-7 col-xs-12">
        <div class="row">
            <div class="col s12">
                <div class="profile-contain">
                    <div class="section section-data-tables">
                        <div class="row">
                            <div class="col s12 search-filter">
                                <?= $this->render('_search', ['model' => $searchModel]); ?>
                            </div>
                            <div class="col s12">
                                <div class="card table-structure">
                                    <div class="card-content">
                                        <div class="dataTables_wrapper" id="page-length-option_wrapper">
                                            <div class="card-header d-flex align-items-center justify-content-between w-100">
                                                <div class="header-title">
                                                    <?= $this->title ?>
                                                </div>
                                                <div class="card-header-btns">
                                                   <!-- --><?php /*= Html::a(CallTimeDistributionReportModule::t('calltimedistributionreport', 'export'), ['/calltimedistributionreport/call-time-distribution-report/export'], [
                                                        'id' => 'hov view_link',
                                                        'data-pjax' => '0',
                                                        'class' => 'exportbutton lead_group btn waves-effect waves-light darken-1 breadcrumbs-btn right',
                                                    ]) */?>
                                                    <button id="export-button" class="exportbutton lead_group btn waves-effect waves-light darken-1 breadcrumbs-btn right">
                                                        <?= CallTimeDistributionReportModule::t('calltimedistributionreport', 'export') ?>
                                                    </button>
                                                </div>
                                            </div>
                                            <?php try {
                                                echo GridView::widget([
                                                    'id' => 'call-time-distribution-report-grid-index', // TODO : Add Grid Widget ID
                                                    'dataProvider' => $dataProvider,
                                                    'layout' => Yii::$app->layoutHelper->get_layout_str('#call-time-distribution-report-search-form'),
                                                    'showOnEmpty' => true,
                                                    'showFooter' => true,
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
                                                            'attribute' => 'queue',
                                                            'label' => CallTimeDistributionReportModule::t('calltimedistributionreport', 'queue'),
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'enableSorting' => TRUE,
                                                            'value' => function ($model) {
                                                                return (!empty($model['queue']) ? QueueMaster::getQueueName($model['queue']) : '-');
                                                            },
                                                            'footer' => '<b>Total</b>'
                                                        ],
                                                        [
                                                            'attribute' => 'total_calls',
                                                            'label' => CallTimeDistributionReportModule::t('calltimedistributionreport', 'total_call'),
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'enableSorting' => TRUE,
                                                            'value' => function ($model) {
                                                                return $model['total_calls'];
                                                            },
                                                            'footer' => CallTimeDistributionReport::getTotal($dataProvider->allModels, 'total_calls'),
                                                        ],
                                                        [
                                                            'attribute' => 'avg_waiting_time',
                                                            'label' => CallTimeDistributionReportModule::t('calltimedistributionreport', 'avg_wait_time'),
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'enableSorting' => TRUE,
                                                            'value' => function ($model) {
                                                                return (!empty($model['avg_waiting_time']) ? gmdate("H:i:s", $model['avg_waiting_time']) : '-');
                                                            },
                                                            'footer' => gmdate("H:i:s", CallTimeDistributionReport::getTotal($dataProvider->allModels, 'avg_waiting_time')),
                                                        ],
                                                        [
                                                            'attribute' => 'answer_call_30',
                                                            'label' => CallTimeDistributionReportModule::t('calltimedistributionreport', 'answered_thirty'),
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'enableSorting' => TRUE,
                                                            'value' => function ($model) {
                                                                return (!empty($model['answer_call_30']) ? $model['answer_call_30'] : '0');
                                                            },
                                                            'footer' => CallTimeDistributionReport::getTotal($dataProvider->allModels, 'answer_call_30'),
                                                        ],
                                                        [
                                                            'attribute' => 'drop_call_30',
                                                            'label' => CallTimeDistributionReportModule::t('calltimedistributionreport', 'dropped_thirty'),
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'enableSorting' => TRUE,
                                                            'value' => function ($model) {
                                                                return (!empty($model['drop_call_30']) ? $model['drop_call_30'] : '0');
                                                            },
                                                            'footer' => CallTimeDistributionReport::getTotal($dataProvider->allModels, 'drop_call_30'),
                                                        ],
                                                        [
                                                            'attribute' => 'answer_call_60',
                                                            'label' => CallTimeDistributionReportModule::t('calltimedistributionreport', 'answered_sixty'),
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'enableSorting' => TRUE,
                                                            'value' => function ($model) {
                                                                return (!empty($model['answer_call_60']) ? $model['answer_call_60'] : '0');
                                                            },
                                                            'footer' => CallTimeDistributionReport::getTotal($dataProvider->allModels, 'answer_call_60'),
                                                        ],
                                                        [
                                                            'attribute' => 'drop_call_60',
                                                            'label' => CallTimeDistributionReportModule::t('calltimedistributionreport', 'dropped_sixty'),
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'enableSorting' => TRUE,
                                                            'value' => function ($model) {
                                                                return (!empty($model['drop_call_60']) ? $model['drop_call_60'] : '0');
                                                            },
                                                            'footer' => CallTimeDistributionReport::getTotal($dataProvider->allModels, 'drop_call_60'),
                                                        ],
                                                        [
                                                            'attribute' => 'answer_call_3',
                                                            'label' => CallTimeDistributionReportModule::t('calltimedistributionreport', 'answered_one'),
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'enableSorting' => TRUE,
                                                            'value' => function ($model) {
                                                                return (!empty($model['answer_call_3']) ? $model['answer_call_3'] : '0');
                                                            },
                                                            'footer' => CallTimeDistributionReport::getTotal($dataProvider->allModels, 'answer_call_3'),
                                                        ],
                                                        [
                                                            'attribute' => 'drop_call_3',
                                                            'label' => CallTimeDistributionReportModule::t('calltimedistributionreport', 'dropped_one'),
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'enableSorting' => TRUE,
                                                            'value' => function ($model) {
                                                                return (!empty($model['drop_call_3']) ? $model['drop_call_3'] : '0');
                                                            },
                                                            'footer' => CallTimeDistributionReport::getTotal($dataProvider->allModels, 'drop_call_3'),
                                                        ],
                                                        [
                                                            'attribute' => 'answer_call_5',
                                                            'label' => CallTimeDistributionReportModule::t('calltimedistributionreport', 'answered_three'),
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'enableSorting' => TRUE,
                                                            'value' => function ($model) {
                                                                return (!empty($model['answer_call_5']) ? $model['answer_call_5'] : '0');
                                                            },
                                                            'footer' => CallTimeDistributionReport::getTotal($dataProvider->allModels, 'answer_call_5'),
                                                        ],
                                                        [
                                                            'attribute' => 'drop_call_5',
                                                            'label' => CallTimeDistributionReportModule::t('calltimedistributionreport', 'dropped_three'),
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'enableSorting' => TRUE,
                                                            'value' => function ($model) {
                                                                return (!empty($model['drop_call_5']) ? $model['drop_call_5'] : '0');
                                                            },
                                                            'footer' => CallTimeDistributionReport::getTotal($dataProvider->allModels, 'drop_call_5'),
                                                        ],
                                                        [
                                                            'attribute' => 'answer_call_5_plush',
                                                            'label' => CallTimeDistributionReportModule::t('calltimedistributionreport', 'answered_five'),
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'enableSorting' => TRUE,
                                                            'value' => function ($model) {
                                                                return (!empty($model['answer_call_5_plush']) ? $model['answer_call_5_plush'] : '0');
                                                            },
                                                            'footer' => CallTimeDistributionReport::getTotal($dataProvider->allModels, 'answer_call_5_plush'),
                                                        ],
                                                        [
                                                            'attribute' => 'drop_call_5_plush',
                                                            'label' => CallTimeDistributionReportModule::t('calltimedistributionreport', 'dropped_five'),
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'enableSorting' => TRUE,
                                                            'value' => function ($model) {
                                                                return (!empty($model['drop_call_5_plush']) ? $model['drop_call_5_plush'] : '0');
                                                            },
                                                            'footer' => CallTimeDistributionReport::getTotal($dataProvider->allModels, 'drop_call_5_plush'),
                                                        ],
                                                    ],
                                                    'tableOptions' => [
                                                        'class' => 'display dataTable dtr-inline providercount',
                                                        'data-count' => $dataProvider->getTotalCount(),
                                                    ],
                                                ]);
                                            } catch (Exception $e) {
                                                print_r($e->getMessage());
                                                exit;
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
<?php Pjax::end(); ?>
<?php
$this->registerJs("
    $(document).on('click', '.exportbutton', function () {
        var count = ((!$('.providercount').data('count')) ? 0 : $('.providercount').data('count'));
        if (count <= 0) {
            alert('" . Yii::t('app', 'no_records_found_to_export') . "');
            return false;
        }else{
            event.preventDefault(); 
            window.location.href = '".Url::to(['/calltimedistributionreport/call-time-distribution-report/export'])."';
        }
    });");
?>
