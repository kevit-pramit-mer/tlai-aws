<?php

use app\modules\ecosmob\campaigncdr\models\Cdr;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;
use app\modules\ecosmob\campaigncdr\CampaignCdrModule;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\ecosmob\cdr\models\CdrSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title=CampaignCdrModule::t('cdr', 'cdr_reports');
$this->params['breadcrumbs'][]=$this->title;
$this->params['pageHead']=$this->title;

$allColumns=Cdr::allColumns();

?>

<!--<div class="col s12 m7 pt-1 pb-1 pr-0 mob-m">
    <?php
/*    if (!empty($dataProvider->models)) { */ ?>
        <?php /*echo Html::a('Export',
            ['export'],
            [
                'id'=>'hov',
                'class'=>'exportbutton lead_group btn waves-effect waves-light darken-1 breadcrumbs-btn right',
                'data-pjax'=>0,
            ])
        */ ?><?php /*} */ ?>
</div>-->

<div class="row">
    <div class="col-xl-9 col-md-7 col-xs-12">
        <div class="row">
            <div class="col s12">
                <div class="profile-contain">
                    <div class="section section-data-tables">
                        <div class="row">
                            <div class="col s12 search-filter">
                                <?= $this->render('_search', ['model'=>$searchModel]); ?>
                            </div>
                            <div class="col s12">
                                <div class="card">
                                    <div class="card-content">

                                       <!-- <div class="box-tools" style="float:right;">
                                            <?php /*echo Html::Button('Download Selected', ['class' => 'btn btn-basic download-multiple']) */?>
                                        </div>-->

                                        <div class="dataTables_wrapper" id="page-length-option_wrapper">

                                            <?php /*Pjax::begin([
                                                'enablePushState'=>false,
                                                'id'=>'pjax-phonebook'
                                            ]);*/ ?>

                                            <?php try {
                                                echo Html::beginForm(['bulk-data'], 'post', array('id' => 'data_form'));
                                                echo GridView::widget([
                                                    'id'=>'cdr-grid-index', // TODO : Add Grid Widget ID
                                                    'dataProvider'=>$dataProvider,
                                                    'layout'=>Yii::$app->layoutHelper->get_layout_str('#cdr-search-form'),
                                                    'showOnEmpty'=>false,
                                                    'pager'=>[
                                                        'prevPageLabel'=>'<a class="paginate_button previous disabled" aria-controls="data-table-row-grouping" data-dt-idx="0" tabindex="0" id="data-table-row-grouping_previous">Previous</a>',
                                                        'nextPageLabel'=>'<a class="paginate_button next" aria-controls="data-table-row-grouping" data-dt-idx="4" tabindex="0" id="data-table-row-grouping_next">Next</a>',
                                                        'maxButtonCount' => 5,
                                                    ],
                                                    'options'=>[
                                                        'tag'=>false,
                                                    ],
                                                    'columns'=>$allColumns,
                                                    'tableOptions'=>[
                                                        'class'=>'display dataTable dtr-inline',
                                                    ],
                                                ]);
                                                echo Html::endForm();
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

<!----------------------------- FOR MULTI DOWNLOAD   ---------------->

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

<script type="text/javascript">

    $(".select-on-check-all").click(function () {
        $('input:checkbox').not(this).prop('checked', this.checked);
    });

    $(document).on('click', '.download-multiple', function () {

        $('.modal').modal({ dismissible: false });
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
            $('#d_id').html('Please select atleast one record to <b>Download</b>');
        } else {
            $('#common-download').modal('open');
            $('#d_id').html('Are you sure you want to download selected ' + count + ' record(s) ?');
            $('.common_delete').attr('onclick', 'commonDownload("multiple")');
        }
    });

    function commonDownload(purpose) {
        if (purpose == 'multiple') {

            // $('#download_form').submit();

            submitVal = 'download';
            $('#data_form').append("<input type='hidden' name='optype' value='"+
                submitVal+"' />");

            $('#data_form').submit();
        }
        $('#common-download').modal('close');
        $('body').removeClass('modal-open');
        $('.modal-backdrop').hide();
    }

</script>












































