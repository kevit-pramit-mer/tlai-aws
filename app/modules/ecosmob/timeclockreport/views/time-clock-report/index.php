<?php

use app\modules\ecosmob\timeclockreport\models\TimeClockReportSearch;
use app\modules\ecosmob\timeclockreport\TimeClockReportModule;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use app\components\CommonHelper;
use app\modules\ecosmob\timeclockreport\models\TimeClockReport;


/* @var $this yii\web\View */
/* @var $searchModel TimeClockReportSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $total */

$this->title = TimeClockReportModule::t('timeclockreport', 'time_clock_report');
$this->params['breadcrumbs'][] = $this->title;
$this->params['pageHead'] = $this->title;
?>

<?php Pjax::begin(['id' => 'time-clock-report-index', 'timeout' => 0, 'enablePushState' => false]); ?>

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
                                                    <?php /*= Html::a(TimeClockReportModule::t('timeclockreport', 'export'), ['/timeclockreport/time-clock-report/export'], [
                                                        'id' => 'hov view_link',
                                                        'data-pjax' => '0',
                                                        'class' => 'exportbutton lead_group btn waves-effect waves-light darken-1 breadcrumbs-btn right',
                                                    ]) */?>
                                                    <button id="export-button" class="exportbutton lead_group btn waves-effect waves-light darken-1 breadcrumbs-btn right">
                                                        <?= TimeClockReportModule::t('timeclockreport', 'export') ?>
                                                    </button>
                                                </div>
                                            </div>
                                            <?php try {
                                                echo GridView::widget([
                                                    'id' => 'time-clock-report-grid-index', // TODO : Add Grid Widget ID
                                                    'dataProvider' => $dataProvider,
                                                    'layout' => Yii::$app->layoutHelper->get_layout_str('#time-clock-report-search-form'),
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
                                                        [
                                                            'class' => 'yii\grid\ActionColumn',
                                                            'header' => 'Action',
                                                            'template' => '{view}',
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'buttons' => [
                                                                'view' => function ($url, $model) use($searchModel){
                                                                    return Html::a('<i class="material-icons">visibility</i>', '', [
                                                                        'data-id' => $model->user_id,
                                                                        'data-from' => $searchModel->from,
                                                                        'data-to' => $searchModel->to,
                                                                        'class' => 'view_details',
                                                                        'title' => 'Click the view icon to access additional records',
                                                                        'data-pjax' => '0'
                                                                    ]);
                                                                },
                                                            ],
                                                        ],
                                                        //['class' => 'yii\grid\SerialColumn'],
                                                        [
                                                            'attribute' => 'agent',
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'enableSorting' => TRUE,
                                                        ],
                                                        [
                                                            'attribute' => 'login_time',
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'enableSorting' => TRUE,
                                                            'value' => function ($model) {
                                                                return CommonHelper::tsToDt($model->login_time);
                                                            }
                                                        ],
                                                        [
                                                            'attribute' => 'logout_time',
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'enableSorting' => TRUE,
                                                            'value' => function ($model) {
                                                                return $model->logout_time == '0000-00-00 00:00:00' ? '-'/*date('Y-m-d H:i:s')*/ : CommonHelper::tsToDt($model->logout_time);
                                                            }
                                                        ],
                                                        [
                                                            'attribute' => 'total_log_hours',
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'enableSorting' => TRUE,
                                                            'value' => function ($model) {
                                                                $from = date('Y-m-d', strtotime($model->login_time));
                                                                $to = $model->logout_time == '0000-00-00 00:00:00' ? date('Y-m-d'): $model->logout_time;
                                                                //print_r($date);exit;
                                                                $hours = TimeClockReport::getTotalLogHours($from, $to, $model->user_id);
                                                                return gmdate('H:i:s', $hours);
                                                            },
                                                        ],
                                                        [
                                                            'attribute' => 'total_break_time',
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'enableSorting' => TRUE,
                                                            'value' => function ($model) {
                                                                return (!empty($model->total_break_time) ? date("H:i:s", $model->total_break_time) : '-');
                                                            },
                                                        ],
                                                        [
                                                            'attribute' => 'total_breaks',
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'enableSorting' => TRUE,
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

<div id="view_modal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h5>Time Clock Detail</h5>
        </div>

        <div class="modal-body">
            <div class="row">
                <div class="col s12 m12">
                    <p id="agent_detail"></p>
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-default modal-close">Close</button>
        </div>
    </div>
</div>
<script>
    var baseURL = '<?= Yii::$app->homeUrl ?>';
</script>
<script>
    $(document).ready(function (e) {
        $(document).on("click", ".view_details", function () {
            $('.modal').modal({
                dismissible: false
            });

            getAgentDetail($(this).attr("data-id"), $(this).attr("data-from"), $(this).attr("data-to"));

            $("#view_modal").modal('open');
        });
        $(document).on("click", ".modal-close", function () {
            $("#agent_detail").html('');
            $("#view_modal").modal('close');
        });
    });

    function getAgentDetail(id, from, to) {
        if (id != "" && from != "" && to != "") {
            $.ajax({
               // url: baseURL + "index.php?r=timeclockreport/time-clock-report/agent-detail",
                url: "<?= Url::to(['/timeclockreport/time-clock-report/agent-detail']) ?>",
                data: {id: id, from: from, to: to},
                type: "POST",
                success: function (data) {
                    var parsed_array = JSON.parse(data);
                    $("#agent_detail").html(parsed_array);
                }
            });
        }
    }

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
            window.location.href = '".Url::to(['/timeclockreport/time-clock-report/export'])."';
        }
    });");
?>