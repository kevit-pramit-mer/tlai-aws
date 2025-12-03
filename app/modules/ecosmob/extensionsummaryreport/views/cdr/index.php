<?php

use app\modules\ecosmob\extensionsummaryreport\ExtensionSummaryReportModule;
use app\modules\ecosmob\extensionsummaryreport\models\Cdr;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\ecosmob\extensionsummaryreport\models\CdrSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = ExtensionSummaryReportModule::t('extensionsummaryreport', 'cdr_reports');
$this->params['breadcrumbs'][] = $this->title;
$this->params['pageHead'] = $this->title;

$allColumns = Cdr::allColumns();
?>
<?php Pjax::begin(['id' => 'extension-summary-report-index', 'timeout' => 0, 'enablePushState' => false]); ?>
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
                                                      <!-- --><?php /*echo Html::a(ExtensionSummaryReportModule::t('extensionsummaryreport', 'export'),
                                                            ['export'],
                                                            [
                                                                'id' => 'hov view_link',
                                                                'class' => 'exportbutton lead_group btn waves-effect waves-light darken-1 breadcrumbs-btn right',
                                                                'data-pjax' => '0',
                                                            ])
                                                        */?>
                                                        <button id="export-button" class="exportbutton lead_group btn waves-effect waves-light darken-1 breadcrumbs-btn right">
                                                            <?= ExtensionSummaryReportModule::t('extensionsummaryreport', 'Export') ?>
                                                        </button>
                                                    </div>
                                            </div>
                                            <?php try {
                                                echo GridView::widget([
                                                    'id' => 'cdr-grid-index', // TODO : Add Grid Widget ID
                                                    'dataProvider' => $dataProvider,
                                                    'layout' => Yii::$app->layoutHelper->get_layout_str('#cdr-search-form'),
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
                                                            'attribute' => 'extension',
                                                            'label' => ExtensionSummaryReportModule::t('extensionsummaryreport', 'extension'),
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'enableSorting' => True,
                                                            'value' => function ($model) {
                                                                return $model['extension'];
                                                            },
                                                        ],
                                                        [
                                                            'attribute' => 'extension_name',
                                                            'label' => ExtensionSummaryReportModule::t('extensionsummaryreport', 'extension_name'),
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'enableSorting' => True,
                                                            'value' => function ($model) {
                                                                return $model['extension_name'];
                                                            },
                                                        ],
                                                        [
                                                            'attribute' => 'total_calls',
                                                            'label' => ExtensionSummaryReportModule::t('extensionsummaryreport', 'total_calls'),
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'enableSorting' => True,
                                                            'value' => function ($model) {
                                                                return $model['total_calls'];
                                                            },
                                                        ],
                                                        [
                                                            'attribute' => 'total_duration',
                                                            'label' => ExtensionSummaryReportModule::t('extensionsummaryreport', 'total_duration'),
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'enableSorting' => True,
                                                            'value' => function ($model) {
                                                                return gmdate('H:i:s', $model['total_duration']);
                                                            },
                                                        ],
                                                        [
                                                            'attribute' => 'average_call_duration',
                                                            'label' => ExtensionSummaryReportModule::t('extensionsummaryreport', 'average_call_duration'),
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'enableSorting' => True,
                                                            'value' => function ($model) {
                                                                return gmdate('H:i:s', $model['average_call_duration']);
                                                            },
                                                        ],
                                                        [
                                                            'attribute' => 'total_answered_calls',
                                                            'label' => ExtensionSummaryReportModule::t('extensionsummaryreport', 'total_answered_calls'),
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'enableSorting' => True,
                                                            'value' => function ($model) {
                                                                return $model['total_answered_calls'];
                                                            },
                                                        ],
                                                        [
                                                            'attribute' => 'total_abandoned_calls',
                                                            'label' => ExtensionSummaryReportModule::t('extensionsummaryreport', 'total_abandoned_calls'),
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'enableSorting' => True,
                                                            'value' => function ($model) {
                                                                return $model['total_abandoned_calls'];
                                                            },
                                                        ],
                                                        [
                                                            'attribute' => 'total_inbound_calls',
                                                            'label' => ExtensionSummaryReportModule::t('extensionsummaryreport', 'total_inbound_calls'),
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'enableSorting' => True,
                                                            'value' => function ($model) {
                                                                return $model['total_inbound_calls'];
                                                            },
                                                        ],
                                                        [
                                                            'attribute' => 'total_inbound_call_duration',
                                                            'label' => ExtensionSummaryReportModule::t('extensionsummaryreport', 'total_inbound_call_duration'),
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'enableSorting' => True,
                                                            'value' => function ($model) {
                                                                return gmdate('H:i:s',$model['total_inbound_call_duration']);
                                                            },
                                                        ],
                                                        [
                                                            'attribute' => 'total_outbound_calls',
                                                            'label' => ExtensionSummaryReportModule::t('extensionsummaryreport', 'total_outbound_calls'),
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'enableSorting' => True,
                                                            'value' => function ($model) {
                                                                return $model['total_outbound_calls'];
                                                            },
                                                        ],
                                                        [
                                                            'attribute' => 'total_outbound_call_duration',
                                                            'label' => ExtensionSummaryReportModule::t('extensionsummaryreport', 'total_outbound_call_duration'),
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'enableSorting' => True,
                                                            'value' => function ($model) {
                                                                return gmdate('H:i:s',$model['total_outbound_call_duration']);
                                                            },
                                                        ],
                                                    ],
                                                    'tableOptions' => [
                                                        'class' => 'display dataTable dtr-inline providercount',
                                                        'data-count' => $dataProvider->getTotalCount(),
                                                    ],
                                                ]);
                                            } catch (Exception $e) {
                                            }
                                            ?>
                                        </div>
                                        <div class="empty materialize-red-text">
                                            <?= Yii::t('app', 'export_limit_note') ?>
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

<div id="common-download" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h5>Download</h5>
        </div>
        <div class="modal-body">
            <p id="d_id"></p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default modal-close">Close</button>
            <button type="button" class="btn btn-primary common_delete" data-dismiss="modal" aria-hidden="true"
                    onclick="commonDownload('single')">Ok
            </button>
        </div>
    </div>
</div>

<div id="loader" style="display: none;">
    <div class="spinner"></div>
</div>

<style>
    #loader {
        position: fixed;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(255, 255, 255, 0.8);
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .spinner {
        border: 16px solid #f3f3f3; /* Light grey */
        border-top: 16px solid #3498db; /* Blue */
        border-radius: 50%;
        width: 120px;
        height: 120px;
        animation: spin 2s linear infinite;
    }
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>

<script>
    $(document).ready(function () {
        $('.extension-report').addClass("active");
        $('.extension-child').removeClass("active");
        $('.main-cdr').removeClass("active");
    });

    $(document).on('pjax:start', function() {
        $('#loader').show();
    });

    $(document).on('pjax:end', function() {
        $('#loader').hide();
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
            window.location.href = '".Url::to(['/extensionsummaryreport/cdr/export'])."';
        }
    });");
?>