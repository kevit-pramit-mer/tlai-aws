<?php

use app\modules\ecosmob\cdr\CdrModule;
use app\modules\ecosmob\cdr\models\Cdr;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\ecosmob\cdr\models\CdrSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = CdrModule::t('cdr', 'cdr_reports');
$this->params['breadcrumbs'][] = $this->title;
$this->params['pageHead'] = $this->title;

$allColumns = Cdr::allColumns();

?>

<?php Pjax::begin(['id' => 'cdr-index', 'timeout' => 0, 'enablePushState' => false]); ?>
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
                                                       <!-- --><?php /*echo Html::a(CdrModule::t('cdr', 'export'),
                                                            ['export'],
                                                            [
                                                                'id' => 'hov view_link',
                                                                'class' => 'exportbutton lead_group btn waves-effect waves-light darken-1 breadcrumbs-btn',
                                                                'data-pjax' => '0',
                                                                'options' => []
                                                            ])
                                                        */?>
                                                        <button id="export-button" class="exportbutton lead_group btn waves-effect waves-light darken-1 breadcrumbs-btn right">
                                                            <?= CdrModule::t('cdr', 'Export') ?>
                                                        </button>
                                                        <?php echo Html::Button(Yii::t('app', 'download_recording'), ['class' => 'btn btn-basic download-multiple']) ?>
                                                    </div>
                                            </div>
                                            <?php try {
                                                echo Html::beginForm(['bulk-data'], 'post', array('id' => 'data_form'));
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
                                                        'class' => 'display dataTable dtr-inline providercount',
                                                        'data-count' => $dataProvider->getTotalCount(),
                                                    ],
                                                ]);
                                                echo Html::endForm();
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
            <h5><?= Yii::t('app', 'download_recording') ?></h5>
        </div>
        <div class="modal-body">
            <p id="d_id"></p>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn waves-effect waves-light bg-gray-200 modal-close"><?= CdrModule::t('cdr', 'close') ?></button>
            <button type="button" class="btn waves-effect waves-light cyan accent-8 common_delete" data-dismiss="modal" aria-hidden="true"
                    onclick="commonDownload('single')"><?= CdrModule::t('cdr', 'ok') ?>
            </button>
        </div>
    </div>

</div>

<script type="text/javascript">
    $(document).ready(function() {
        $(".select-on-check-all").click(function () {
            $('input:checkbox').not(this).prop('checked', this.checked);
        });
    });
    $(document).on('pjax:success', function(event) {
        $(".select-on-check-all").click(function () {
            $('input:checkbox').not(this).prop('checked', this.checked);
        });
    });

    $(document).on('click', '.download-multiple', function () {

        $('.modal').modal({
            dismissible: false
        });
        $('.trigger-modal').modal();

        ckbox = document.getElementsByName('selection[]');
        count = 0;
        for (var i = 0; i < ckbox.length; i++) {
            element = ckbox[i];
            if (element.checked) {
                count++;
            }
        }

        if (count == 0) {
            $('#common-download').modal('open');
            //$('.mtitle').html('Delete');
            $('#d_id').html('<?=CdrModule::t('cdr', 'please_select_at_least_one_record_to_download')?>');
        } else {
            $('#common-download').modal('open');
            $('#d_id').html('Are you sure you want to download selected ' + count + ' record(s) ?');
            $('#d_id').html('<?=CdrModule::t('cdr', 'are_you_sure_want_to_download_selected')?> ' + count
                + ' <?=CdrModule::t
                ('cdr', 'records')?>');
            $('.common_delete').attr('onclick', 'commonDownload("multiple")');
        }
    });

    function commonDownload(purpose) {
        if (purpose == 'multiple') {

            // $('#download_form').submit();

            submitVal = 'download';
            $('#data_form').append("<input type='hidden' name='optype' value='" +
                submitVal + "' />");

            $('#data_form').submit();
        }
        $('#common-download').modal('close');
        $('body').removeClass('modal-open');
        $('.modal-backdrop').hide();
    }

</script>

<?php
$this->registerJs("
    $(document).on('click', '.exportbutton', function () {
        var count = ((!$('.providercount').data('count')) ? 0 : $('.providercount').data('count'));
        if (count <= 0) {
            alert('" . Yii::t('app', 'No records found to export') . "');
            return false;
        }else{
            event.preventDefault(); 
            window.location.href = '".Url::to(['/cdr/cdr/export'])."';
        }
    });");
?>













































