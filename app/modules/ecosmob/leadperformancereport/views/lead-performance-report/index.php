<?php

use app\modules\ecosmob\leadperformancereport\LeadPerformanceReportModule;
use app\modules\ecosmob\leadperformancereport\models\LeadPerformanceSearchReport;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;


/* @var $this yii\web\View */
/* @var $searchModel LeadPerformanceSearchReport */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $total */

$this->title = LeadPerformanceReportModule::t('leadperformancereport', 'lead_performance_report');
$this->params['breadcrumbs'][] = $this->title;
$this->params['pageHead'] = $this->title;
?>

<?php Pjax::begin(['id' => 'lead-performance-report-index', 'timeout' => 0, 'enablePushState' => false]); ?>

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
                                            <div class="card-header d-flex align-items-center justify-content-between w-100">
                                                <div class="header-title">
                                                    <?= $this->title ?>
                                                </div>
                                                <div class="card-header-btns">
                                                   <!-- --><?php /*= Html::a(LeadPerformanceReportModule::t('leadperformancereport', 'export'), ['/leadperformancereport/lead-performance-report/export'], [
                                                        'id' => 'hov view_link',
                                                        'data-pjax' => '0',
                                                        'class' => 'exportbutton lead_group btn waves-effect waves-light darken-1 breadcrumbs-btn right',
                                                    ]) */?>
                                                    <button id="export-button" class="exportbutton lead_group btn waves-effect waves-light darken-1 breadcrumbs-btn right">
                                                        <?= LeadPerformanceReportModule::t('leadperformancereport', 'export') ?>
                                                    </button>
                                                </div>
                                            </div>
                                        <div class="dataTables_wrapper" id="page-length-option_wrapper">
                                            <?php try {
                                                echo GridView::widget([
                                                    'id' => 'lead-performance-report-grid-index', // TODO : Add Grid Widget ID
                                                    'dataProvider' => $dataProvider,
                                                    'layout' => Yii::$app->layoutHelper->get_layout_str('#lead-performance-report-search-form'),
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
                                                            'attribute' => 'ld_group_name',
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'enableSorting' => TRUE,
                                                            'value' => function ($model) {
                                                                return (!empty($model->ld_group_name) ? $model->ld_group_name : '0');
                                                            }
                                                        ],
                                                        [
                                                            'attribute' => 'dialed_count',
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'enableSorting' => TRUE,
                                                            'value' => function ($model) {
                                                                return (!empty($model->dialed_count) ? $model->dialed_count : '0');
                                                            }
                                                        ],
                                                        [
                                                            'attribute' => 'remaining_count',
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'enableSorting' => TRUE,
                                                            'value' => function ($model) {
                                                                return (!empty($model->remaining_count) ? $model->remaining_count : '0');
                                                            }
                                                        ],
                                                        [
                                                            'attribute' => 'contacted_count',
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'enableSorting' => TRUE,
                                                            'value' => function ($model) {
                                                                return (!empty($model->contacted_count) ? $model->contacted_count : '0');
                                                            }
                                                        ],
                                                        [
                                                            'attribute' => 'noncontacted_count',
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'enableSorting' => TRUE,
                                                            'value' => function ($model) {
                                                                return (!empty($model->noncontacted_count) ? $model->noncontacted_count : '0');
                                                            }
                                                        ],
                                                        [
                                                            'attribute' => 'redial_count',
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'enableSorting' => TRUE,
                                                            'value' => function ($model) {
                                                                return (!empty($model->redial_count) ? $model->redial_count : '0');
                                                            }
                                                        ],
                                                        [
                                                            'attribute' => 'redial_contacted_count',
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'enableSorting' => TRUE,
                                                            'value' => function ($model) {
                                                                return (!empty($model->redial_contacted_count) ? $model->redial_contacted_count : '0');
                                                            }
                                                        ],
                                                        [
                                                            'attribute' => 'redial_noncontacted_count',
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'enableSorting' => TRUE,
                                                            'value' => function ($model) {
                                                                return (!empty($model->redial_noncontacted_count) ? $model->redial_noncontacted_count : '0');
                                                            }
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
<script>
    function getLeadGroups() {
        var campId = $('#leadperformancesearchreport-campaign').val();
        /*if(campId){*/
            $.ajax({
                type: 'POST',
                url: baseURL + "index.php?r=leadperformancereport/lead-performance-report/get-lead-groups",
                data: {campId: campId},
                success: function (result) {
                    $('#leadperformancesearchreport-leadgroup').html(result);
                }
            });
        /*}*/

    }

    $(document).on('change', '#leadperformancesearchreport-campaign', function () {
        getLeadGroups();
    });
    $(document).ready(function () {
        getLeadGroups();
    });
</script>

<?php
$this->registerJs("
    $(document).on('click', '.exportbutton', function () {
        var count = ((!$('.providercount').data('count')) ? 0 : $('.providercount').data('count'));
        if (count <= 0) {
            alert('" . Yii::t('app', 'no_records_found_to_export') . "');
            return false;
        }else{
            event.preventDefault(); 
            window.location.href = '".Url::to(['/leadperformancereport/lead-performance-report/export'])."';
        }
    });");
?>

