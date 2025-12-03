<?php

use app\modules\ecosmob\agentperformancereport\AgentPerformanceReportModule;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\modules\ecosmob\agentperformancereport\models\AgentPerformanceReport;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel \app\modules\ecosmob\agentperformancereport\models\AgentPerformanceReportSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $total */

$this->title = AgentPerformanceReportModule::t('agentperformancereport', 'agent_performance_report');
$this->params['breadcrumbs'][] = $this->title;
$this->params['pageHead'] = $this->title;
?>

<?php Pjax::begin(['id' => 'agent-performance-report-index', 'timeout' => 0, 'enablePushState' => false]); ?>

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
                                                   <!-- --><?php /*= Html::a(AgentPerformanceReportModule::t('agentperformancereport', 'export'), ['/agentperformancereport/agent-performance-report/export'], [
                                                        'id' => 'hov view_link',
                                                        'data-pjax' => '0',
                                                        'class' => 'exportbutton lead_group btn waves-effect waves-light darken-1 breadcrumbs-btn right',
                                                    ]) */?>
                                                    <button id="export-button" class="exportbutton lead_group btn waves-effect waves-light darken-1 breadcrumbs-btn right">
                                                        <?= AgentPerformanceReportModule::t('agentperformancereport', 'export') ?>
                                                    </button>
                                                </div>
                                            </div>
                                            <?php try {
                                                echo GridView::widget([
                                                    'id' => 'agent-performance-report-grid-index', // TODO : Add Grid Widget ID
                                                    'dataProvider' => $dataProvider,
                                                    'layout' => Yii::$app->layoutHelper->get_layout_str('#agent-performance-report-search-form'),
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
                                                            'attribute' => 'agent',
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'enableSorting' => TRUE,
                                                            'value' => function ($model) {
                                                                return (!empty($model->agent) ? $model->agent : '-');
                                                            }
                                                        ],
                                                        [
                                                            'attribute' => 'total_call',
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'enableSorting' => TRUE,
                                                            'value' => function ($model) {
                                                                return (!empty($model->total_call) ? $model->total_call : '0');
                                                            }
                                                        ],
                                                        [
                                                            'attribute' => 'answered',
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'enableSorting' => TRUE,
                                                            'value' => function ($model) {
                                                                return (!empty($model->answered) ? $model->answered : '0');
                                                            }
                                                        ],
                                                        [
                                                            'attribute' => 'abandoned',
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'enableSorting' => TRUE,
                                                            'value' => function ($model) {
                                                                return (!empty($model->abandoned) ? $model->abandoned : '0');
                                                            }
                                                        ],
                                                        [
                                                            'attribute' => 'break_time',
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'enableSorting' => TRUE,
                                                            'value' => function ($model) {
                                                                $totalBreakTime = 0;
                                                                if (!empty($_GET) && isset($_GET['AgentPerformanceReportSearch'])) {
                                                                    $totalBreakTime = AgentPerformanceReport::getTotalBreakTime($_GET['AgentPerformanceReportSearch']['from'], $_GET['AgentPerformanceReportSearch']['to'], $model->agent_id);
                                                                } else {
                                                                    $totalBreakTime = AgentPerformanceReport::getTotalBreakTime(date('Y-m-d'), date('Y-m-d'), $model->agent_id);
                                                                }
                                                                return ($totalBreakTime ? date("H:i:s", $totalBreakTime) : '-');
                                                            }
                                                        ],
                                                        [
                                                            'attribute' => 'avg_break_time',
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'enableSorting' => TRUE,
                                                            'value' => function ($model) {
                                                                $avgBreakTime = 0;
                                                                if (!empty($_GET) && isset($_GET['AgentPerformanceReportSearch'])) {
                                                                    $avgBreakTime = AgentPerformanceReport::getAvgBreakTime($_GET['AgentPerformanceReportSearch']['from'], $_GET['AgentPerformanceReportSearch']['to'], $model->agent_id);
                                                                } else {
                                                                    $avgBreakTime = AgentPerformanceReport::getAvgBreakTime(date('Y-m-d'), date('Y-m-d'), $model->agent_id);
                                                                }
                                                                return ($avgBreakTime ? date("H:i:s", $avgBreakTime) : '-');
                                                            }
                                                        ],
                                                        [
                                                            'attribute' => 'wait_time',
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'enableSorting' => TRUE,
                                                            'value' => function ($model) {
                                                                return (!empty($model->wait_time) ? date("H:i:s", $model->wait_time) : '-');
                                                            }
                                                        ],
                                                        [
                                                            'attribute' => 'avg_wait_time',
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'enableSorting' => TRUE,
                                                            'value' => function ($model) {
                                                                return (!empty($model->avg_wait_time) ? date("H:i:s", $model->avg_wait_time) : '-');
                                                            }
                                                        ],
                                                        [
                                                            'attribute' => 'call_duration',
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'enableSorting' => TRUE,
                                                            'value' => function ($model) {
                                                                return (!empty($model->call_duration) ? date("H:i:s", $model->call_duration) : '-');
                                                            }
                                                        ],
                                                        [
                                                            'attribute' => 'avg_call_duration',
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'enableSorting' => TRUE,
                                                            'value' => function ($model) {
                                                                return (!empty($model->avg_call_duration) ? date("H:i:s", $model->avg_call_duration) : '-');
                                                            }
                                                        ],
                                                        [
                                                            'attribute' => 'disposion_time',
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'enableSorting' => TRUE,
                                                            'value' => function ($model) {
                                                                return ((!empty($model->disposion_time) && $model->disposion_time > 0) ? date("H:i:s", $model->disposion_time) : '-');
                                                            }
                                                        ],
                                                        [
                                                            'attribute' => 'avg_disposion_time',
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'enableSorting' => TRUE,
                                                            'value' => function ($model) {
                                                                return ((!empty($model->avg_disposion_time)) ? date("H:i:s", $model->avg_disposion_time) : '-');
                                                            }
                                                        ],
                                                    ],
                                                    'tableOptions' => [
                                                        'class' => 'display dataTable dtr-inline providercount',
                                                        'data-count' => $dataProvider->getTotalCount(),
                                                    ],
                                                ]);
                                            } catch (Exception $e) {
                                                print_r($e->getMessage());exit;
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
            window.location.href = '".Url::to(['/agentperformancereport/agent-performance-report/export'])."';
        }
    });");
?>
