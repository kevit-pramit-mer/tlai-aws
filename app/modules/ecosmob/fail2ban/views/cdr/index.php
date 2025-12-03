<?php

use app\modules\ecosmob\extensionsummaryreport\ExtensionSummaryReportModule;
use app\modules\ecosmob\extensionsummaryreport\models\Cdr;
use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\ecosmob\extensionsummaryreport\models\CdrSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = ExtensionSummaryReportModule::t('extensionsummaryreport', 'cdr_reports');
$this->params['breadcrumbs'][] = $this->title;
$this->params['pageHead'] = $this->title;

$allColumns = Cdr::allColumns();
?>

<div class="col s12 m7 pt-1 pb-1 pr-0 mob-m">
    <?php echo Html::a(ExtensionSummaryReportModule::t('extensionsummaryreport', 'export'),
        ['export'],
        [
            'id' => 'hov',
            'class' => 'exportbutton lead_group btn waves-effect waves-light darken-1 breadcrumbs-btn right',
            'data-pjax' => 0,
        ])
    ?>
</div>

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
                                <div class="card">
                                    <div class="card-content">
                                        <div class="dataTables_wrapper" id="page-length-option_wrapper">
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
                                                    'columns' => $allColumns,
                                                    'tableOptions' => [
                                                        'class' => 'display dataTable dtr-inline',
                                                    ],
                                                ]);
                                            } catch (Exception $e) {
                                            }
                                            ?>
                                        </div>
                                        <div style="color: red;">
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

<script>
    $(document).ready(function () {
        $('.extension-report').addClass("active");
        $('.extension-child').removeClass("active");
        $('.main-cdr').removeClass("active");
    });
</script>

<?php
$this->registerJs("
    $(document).on('click', '.exportbutton', function () {
        return checkCount($dataProvider->count, '" . ExtensionSummaryReportModule::t('extensionsummaryreport', 'no_record_found_to_export') . "');
    });");
?>












































