<?php

use app\components\CommonHelper;
use app\modules\ecosmob\queuewisereport\QueueWiseReportModule;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\modules\ecosmob\queue\models\QueueMaster;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\ecosmob\queuewisereport\models\QueueWiseReportSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = QueueWiseReportModule::t('queuewisereport', 'queue_wise_report');
$this->params['breadcrumbs'][] = $this->title;
$this->params['pageHead'] = $this->title;
?>


<?php Pjax::begin(['id' => 'queue-wise-report-index', 'timeout' => 0, 'enablePushState' => false]); ?>

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
                                                    <?php /*= Html::a(QueueWiseReportModule::t('queuewisereport', 'export'), ['/queuewisereport/queue-wise-report/export'], [
                                                        'id' => 'hov view_link',
                                                        'data-pjax' => '0',
                                                        'class' => 'exportbutton lead_group btn waves-effect waves-light darken-1 breadcrumbs-btn right',
                                                    ]) */?>
                                                    <button id="export-button" class="exportbutton lead_group btn waves-effect waves-light darken-1 breadcrumbs-btn right">
                                                        <?= QueueWiseReportModule::t('queuewisereport', 'export') ?>
                                                    </button>
                                                </div>
                                            </div>
                                            <?php try {
                                                echo GridView::widget([
                                                    'id' => 'queue-wise-report-grid-index', // TODO : Add Grid Widget ID
                                                    'dataProvider' => $dataProvider,
                                                    'layout' => Yii::$app->layoutHelper->get_layout_str('#queue-wise-report-search-form'),
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
                                                            'class' => 'yii\grid\SerialColumn',
                                                            'headerOptions' => ['class' => 'width-10'],
                                                            'contentOptions' => ['class' => 'width-10'],
                                                        ],
                                                        [
                                                            'attribute' => 'queue',
                                                            'label' => QueueWiseReportModule::t('queuewisereport', 'queue'),
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'enableSorting' => True,
                                                            'value' => function ($model) {
                                                                return (!empty($model['queue']) ? QueueMaster::getQueueName($model['queue']) : '-');
                                                            }
                                                        ],
                                                        [
                                                            'attribute' => 'queue_num',
                                                            'label' => QueueWiseReportModule::t('queuewisereport', 'queue_num'),
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'enableSorting' => True,
                                                            'value' => function ($model) {
                                                                return $model['queue_num'];
                                                            }
                                                        ],
                                                        [
                                                            'attribute' => 'incoming_call',
                                                            'label' => QueueWiseReportModule::t('queuewisereport', 'incoming_call'),
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'enableSorting' => True,
                                                            'value' => function ($model) {
                                                                return $model['incoming_call'];
                                                            }
                                                        ],
                                                        [
                                                            'attribute' => 'answered',
                                                            'label' => QueueWiseReportModule::t('queuewisereport', 'answered_call'),
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'enableSorting' => True,
                                                            'value' => function ($model) {
                                                                return $model['answered'];
                                                            }
                                                        ],
                                                        [
                                                            'attribute' => 'abandoned',
                                                            'label' => QueueWiseReportModule::t('queuewisereport', 'abandoned'),
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'enableSorting' => True,
                                                            'value' => function ($model) {
                                                                return $model['abandoned'];
                                                            }
                                                        ],
                                                        [
                                                            'attribute' => 'agent',
                                                            'label' => QueueWiseReportModule::t('queuewisereport', 'agent'),
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'enableSorting' => True,
                                                            'value' => function ($model) {
                                                                return $model['agent'];
                                                            }
                                                        ],
                                                        [
                                                            'attribute' => 'total_call_duration',
                                                            'label' => QueueWiseReportModule::t('queuewisereport', 'total_call_duration'),
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'enableSorting' => True,
                                                            'value' => function ($model) {
                                                                return (!empty($model['total_call_duration']) ? gmdate("H:i:s", $model['total_call_duration']) : '-');
                                                            }
                                                        ],
                                                        [
                                                            'attribute' => 'avg_call_duration',
                                                            'label' => QueueWiseReportModule::t('queuewisereport', 'avg_call_duration'),
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'enableSorting' => True,
                                                            'value' => function ($model) {
                                                                return (!empty($model['avg_call_duration']) ? gmdate("H:i:s", $model['avg_call_duration']) : '-');
                                                            }
                                                        ],
                                                        [
                                                            'attribute' => 'avg_waiting_time',
                                                            'label' => QueueWiseReportModule::t('queuewisereport', 'avg_waiting_time'),
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'enableSorting' => True,
                                                            'value' => function ($model) {
                                                                return (!empty($model['avg_waiting_time']) ? gmdate("H:i:s", $model['avg_waiting_time']) : '-');
                                                            }
                                                        ],
                                                    ],
                                                    'tableOptions' => [
                                                        'class' => 'display dataTable dtr-inline providercount providercount',
                                                        'data-count' => $dataProvider->getTotalCount(),
                                                    ],
                                                ]);
                                            } catch (Exception $e) {

                                            } ?>
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
    $(document).ready(function () {
        $('.queue-parent').addClass("active");
        $('.queue-child').removeClass("active");
    });
</script>

<?php
$this->registerJs("
    $(document).on('click', '.exportbutton', function () {
        var count = ((!$('.providercount').data('count')) ? 0 : $('.providercount').data('count'));
        if (count <= 0) {
            alert('" . QueueWiseReportModule::t('queuewisereport', 'no_records_found_to_export') . "');
            return false;
        }else{
            event.preventDefault(); 
            window.location.href = '".Url::to(['/queuewisereport/queue-wise-report/export'])."';
        }
    });");
?>

