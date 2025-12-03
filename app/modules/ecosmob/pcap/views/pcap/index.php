<?php

use app\components\CommonHelper;
use app\modules\ecosmob\pcap\models\Pcap;
use app\modules\ecosmob\pcap\PcapModule;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\widgets\ActiveForm;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\ecosmob\pcap\models\PcapSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $model PcapModule */

$this->title = PcapModule::t('pcap', 'pcap_management');
$this->params['breadcrumbs'][] = $this->title;
$this->params['pageHead'] = $this->title;
?>

<style>
    progress:before{
        content: attr(data-before);
        position: relative;
        top: 6px;
        font-size: small;
        color: black;
    }
</style>
<?php Pjax::begin(['id' => 'pcap-index', 'timeout' => 0, 'enablePushState' => false]); ?>

<div class="row">
    <div class="col-xl-9 col-md-7 col-xs-12">
        <div class="row">
            <div class="col s12">
                <div class="profile-contain">
                    <div class="section section-data-tables">
                        <div class="row">
                            <div class="col s12">
                                <div class="card table-structure">
                                    <div class="card-content">
                                        <div class="dataTables_wrapper" id="page-length-option_wrapper">
                                            <div class="card-header d-flex align-items-center justify-content-between w-100">
                                                <div class="header-title">
                                                    <?= $this->title ?>
                                                </div>
                                                <div class="card-header-btns">
                                                    <?= Html::button(PcapModule::t('pcap', 'add_new'),
                                                    [
                                                        'id' => 'add',
                                                        'class' => 'btn waves-effect waves-light darken-1 breadcrumbs-btn right',
                                                    ]) ?>
                                                </div>
                                            </div>
                                            <?php try {
                                                echo GridView::widget([
                                                    'id' => 'pcap-grid-index',
                                                    'dataProvider' => $dataProvider,
                                                    'layout' => Yii::$app->layoutHelper->get_layout_without_pager('#pcap-search-form'),
                                                    'showOnEmpty' => true,
                                                    'options' => [
                                                        'tag' => FALSE,
                                                    ],
                                                    'columns' => [

                                                        [
                                                            'class' => 'yii\grid\ActionColumn',
                                                            'template' => '{update}{delete}',
                                                            'header' => Yii::t('app', 'action'),
                                                            'headerOptions' => ['class' => 'center width-10'],
                                                            'contentOptions' => ['class' => 'center width-10'],
                                                            'buttons' => [
                                                                'update' => function ($url, $model) {
                                                                    if(!empty($model->ct_filename) && $model->ct_status == 'stop') {
                                                                        ini_set('max_execution_time', 300);
                                                                        set_time_limit(0);
                                                                        $filePath = Yii::$app->params['PCAP_PATH'] ."pcap/". $GLOBALS['tenantID']."/";
                                                                        $filename = $model->ct_filename;
                                                                        if (file_exists($filePath . $filename)) {
                                                                            return Html::a(
                                                                                '<i class="material-icons">file_download</i>',
                                                                                'javascript:void(0)',
                                                                                ['title' => 'Download', 'class' => 'download_file', 'data-id' => Yii::$app->params['tenantStoragePath'] . "pcap/".$GLOBALS['tenantID']."/",
                                                                                    'data-id1' => $model->ct_filename]
                                                                            );
                                                                        } else {
                                                                            return '';
                                                                        }
                                                                    }
                                                                },
                                                                'delete' => function ($url) {
                                                                    $permissions = $GLOBALS['permissions'];
                                                                    if (in_array('/pcap/pcap/delete', $permissions)) {
                                                                        return (1 ? Html::a('<i class="material-icons">delete</i>',
                                                                            $url,
                                                                            [

                                                                                'class' => 'ml-5',
                                                                                'data-pjax' => 0,
                                                                                'style' => 'color:#FF4B56',
                                                                                'data-confirm' => Yii::t('app', 'delete_confirm'),
                                                                                'data-method' => 'post',
                                                                                'title' => Yii::t('app', 'delete'),
                                                                            ]) : '');
                                                                    }else{
                                                                        return '';
                                                                    }
                                                                },
                                                            ],
                                                        ],
                                                        [
                                                            'attribute' => 'ct_name',
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'enableSorting' => TRUE,
                                                        ],
                                                        [
                                                            'attribute' => 'filter',
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'enableSorting' => TRUE,
                                                        ],
                                                        [
                                                            'attribute' => 'buffer_size',
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'enableSorting' => FALSE,
                                                            'format' => 'raw',
                                                            'value' => function ($model) {
                                                                $value = 0;
                                                                if(!empty($model->ct_url)) {
                                                                    if(file_exists($model->ct_url)) {
                                                                        $per = number_format((round(filesize($model->ct_url) / (1024 * 1024), 2) * 100)/ $model->buffer_size, 1, '.', '');
                                                                        $max = 200;
                                                                        $value = round(($per * $max) / 100, 0);
                                                                        $padding = round(($per * 70) / 100, 0);
                                                                        return '<progress value="'.$value.'" max="'.$max.'" style="height: 35px; padding-left:'.$padding.'px;" id="p1" data-before="'.$per.'%"></progress>';
                                                                    }
                                                                }else{
                                                                    return '-';
                                                                }
                                                            }
                                                        ],
                                                        [
                                                            'attribute' => 'packets_limit',
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'enableSorting' => TRUE,
                                                        ],
                                                        [
                                                            'attribute' => 'ct_start',
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'enableSorting' => TRUE,
                                                            'value' => function ($model) {
                                                                return ($model->ct_start == NULL ? "-" : ($model->ct_start == "" ? "-" : CommonHelper::tsToDt($model->ct_start)));
                                                            },

                                                        ],
                                                        [
                                                            'attribute' => 'ct_stop',
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'enableSorting' => TRUE,
                                                            'value' => function ($model) {
                                                                return ($model->ct_stop == NULL ? "-" : ($model->ct_stop == "" ? "-" : CommonHelper::tsToDt($model->ct_stop)));
                                                            },

                                                        ],
                                                        /* [
                                                             'attribute' => 'ct_filename',
                                                             'enableSorting' => TRUE,
                                                             'headerOptions' => ['class' => 'text-center'],
                                                             'contentOptions' => ['class' => 'text-center'],
                                                         ],*/
                                                        [
                                                            'header' => 'Start / Stop',
                                                            'enableSorting' => TRUE,
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'format' => 'raw',
                                                            'value' => function ($model) {
                                                                $start = Html::button(PcapModule::t('pcap', 'start_capture'),
                                                                    [
                                                                        'id' => 'start',
                                                                        'class' => 'btn',
                                                                        'style' => 'width: min-content',
                                                                        //'data-pjax' => 1,
                                                                        'data-id' => $model->ct_id
                                                                    ]);
                                                                $stop = Html::button(PcapModule::t('pcap', 'stop_capture'),
                                                                    [
                                                                        'id' => 'stop',
                                                                        'class' => 'btn',
                                                                        'style' => 'width: min-content; background-color: red !important;',
                                                                        //'data-pjax' => 1,
                                                                        'data-id' => $model->ct_id
                                                                    ]);

                                                                return ($model->ct_status == NULL ? $start : ($model->ct_status == 'start' ? $stop : ''));

                                                            }
                                                        ],
                                                    ],
                                                    'tableOptions' => [
                                                        'class' => 'display dataTable dtr-inline',

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

<div id="pcapModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h5><?=PcapModule::t('pcap','create')?></h5>
        </div>
        <?php $form = ActiveForm::begin([
            'id' => 'pcap-form',
            'enableAjaxValidation' => true,
            'action' => Url::to(['/pcap/pcap/create']),
        ]); ?>
        <div class="modal-body">
            <div class="row">
                <div class="col m6">
                    <div class="input-field col s12">
                        <?= $form->field($model, 'ct_name')->textInput(['maxlength' => true, 'placeholder' => $model->getAttributeLabel('ct_name')])->label($model->getAttributeLabel('ct_name')); ?>

                    </div>
                </div>
                <div class="col m6">
                    <div class="input-field col s12">
                        <?= $form->field($model, 'filter', ['options' => ['class' => '']])->dropDownList(['Any' => 'Any', 'IPV4' => 'IPV4', 'IPV6' => 'IPV6'])->label($model->getAttributeLabel('filter')); ?>

                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col m6">
                    <div class="input-field col s12">
                        <?= $form->field($model, 'buffer_size')->textInput(['maxlength' => true, 'value' => 10, 'placeholder' => $model->getAttributeLabel('buffer_size')])->label($model->getAttributeLabel('buffer_size')); ?>
                        <span style="font-size: 10px">*Max Buffer Size 100MB</span>
                    </div>
                </div>
                <div class="col m6">
                    <div class="input-field col s12">
                        <?= $form->field($model, 'packets_limit')->textInput(['maxlength' => true, 'value' => 10000, 'placeholder' => $model->getAttributeLabel('packets_limit')])->label($model->getAttributeLabel('packets_limit')); ?>
                        <span style="font-size: 10px">*Max Packets Limit 1000000</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <?= Html::a(PcapModule::t('pcap', 'cancel'), ['index', 'page' => Yii::$app->session->get('page')],
                ['class' => 'btn waves-effect waves-light bg-gray-200 mr-1']) ?>
            <?= Html::submitButton(Yii::t('app','create'), ['class' => 'btn btn-success grid-top-button common-btn-radius mr-top-right']) ?>
            <!--<a href="#" class="modal-close waves-effect waves-green bg-gray-200 btn-flat"><?php /*=Yii::t('app','cancel')*/?></a>-->
        </div>
        <?php ActiveForm::end(); ?>
    </div>

</div>

<script>
    $(document).on('click', '#add', function () {
        $('.modal').modal({ dismissible: false });
        $('#pcapModal').modal('open');
    });


    var processData;

    $("#start").click(function () {
        var pcapId = $(this).attr('data-id');
        $.ajax({
            async: false,
            url: baseURL + "index.php?r=pcap/pcap/start-capture",
            data: {id: pcapId},
            success: function (data) {
                location.reload();
                return false;
                processData = data;
            }
        })
    });

    $("#stop").click(function () {
        stopCapture($(this).attr('data-id'));
        //pcapList();
        //autoDeletePcap();

    });

    function stopCapture(pcapId) {

        $.ajax({
            async: false,
            url: baseURL + "index.php?r=pcap/pcap/stop-capture",
            data: {id: pcapId},
            success: function (data) {
                location.reload();
            }
        })
    }

    function pcapList() {
        $('#pcap_list_grid').html('');
        $.ajax({
            async: false,
            url: baseURL + "index.php?r=pcap/pcap/pcap-list",
            success: function (data) {
                if (data) {
                    //$('#pcap_list_grid').html(data);
                    location.reload();
                }
            }
        })
    }

    function autoDeletePcap() {
        $.ajax({
            async: false,
            url: baseURL + "index.php?r=pcap/pcap/auto-delete-pcap",
            success: function (data) {
//                $('#pcap_list_grid').html(data);
            }
        })
    }

    $(".download_file").click(function () {
        downloadFile($(this).attr('data-id')+$(this).attr('data-id1'));

    });

    function downloadFile(url) {
        const a = document.createElement('a')
        a.href = url
        a.download = url.split('/').pop()
        document.body.appendChild(a)
        a.click()
        document.body.removeChild(a)
    }
    /* function downloadFile(urlToSend, fileName) {
         var req = new XMLHttpRequest();
         alert(urlToSend, fileName);
         req.open("GET", urlToSend+fileName, true);
         req.responseType = "blob";
         req.onload = function (event) {
             var blob = req.response;
             //var fileName = req.getResponseHeader("fileName") //if you have the fileName header available
             var link = document.createElement('a');
             link.href = window.URL.createObjectURL(blob);
             link.download = fileName;
             link.click();
         };


         req.send();
     }*/

    refInt = setInterval(ref, 5000);
    function refreshTime(input) {
        clearInterval(refInt);
        refInt = setInterval(ref, 5000)
    }

    function ref() {
        $.ajax({
            async: false,
            url: baseURL + "index.php?r=pcap/pcap/auto-stop-capture",
            success: function (data) {
            }
        })
        $.pjax.reload({
            container: "#pcap-index",
            async: false,
           // data: "PcapSearch[ct_name]=" + $('#pcapsearch-ct_name').val(),
            replace: false
        });
    }

    $(document).on("pjax:success", function (e) {
        $(document).on('click', '#start', function () {
            var pcapId = $(this).attr('data-id');
            $.ajax({
                async: false,
                url: baseURL + "index.php?r=pcap/pcap/start-capture",
                data: {id: pcapId},
                success: function (data) {
                    location.reload();
                    return false;
                    processData = data;
                }
            })
        });

        $(document).on('click', '#stop', function () {
            stopCapture($(this).attr('data-id'));
            //pcapList();
            //autoDeletePcap();

        });

        function stopCapture(pcapId) {

            $.ajax({
                async: false,
                url: baseURL + "index.php?r=pcap/pcap/stop-capture",
                data: {id: pcapId},
                success: function (data) {
                    location.reload();
                }
            })
        }

        function pcapList() {
            $('#pcap_list_grid').html('');
            $.ajax({
                async: false,
                url: baseURL + "index.php?r=pcap/pcap/pcap-list",
                success: function (data) {
                    if (data) {
                        //$('#pcap_list_grid').html(data);
                        location.reload();
                    }
                }
            })
        }

        function autoDeletePcap() {
            $.ajax({
                async: false,
                url: baseURL + "index.php?r=pcap/pcap/auto-delete-pcap",
                success: function (data) {

                }
            })
        }

        /* $("#download_file").click(function () {
             downloadFile($(this).attr('data-id'), $(this).attr('data-id1'));

         });

         function downloadFile(urlToSend, fileName) {
             alert(urlToSend+fileName);
             var req = new XMLHttpRequest();
             req.open("GET", urlToSend+fileName, true);
             req.responseType = "blob";
             req.onload = function (event) {
                 var blob = req.response;
                 //var fileName = req.getResponseHeader("fileName") //if you have the fileName header available
                 var link = document.createElement('a');
                 link.href = window.URL.createObjectURL(blob);
                 link.download = fileName;
                 link.click();
             };

             req.send();
         }*/

        $(".download_file").click(function () {
            downloadFile($(this).attr('data-id')+$(this).attr('data-id1'));

        });

        function downloadFile(url) {
            const a = document.createElement('a')
            a.href = url
            a.download = url.split('/').pop()
            document.body.appendChild(a)
            a.click()
            document.body.removeChild(a)
        }
    });
</script>
