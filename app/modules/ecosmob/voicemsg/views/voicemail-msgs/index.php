<?php

use app\components\CommonHelper;
use app\modules\ecosmob\voicemsg\VoiceMsgModule;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\widgets\Breadcrumbs;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\ecosmob\voicemsg\models\VoicemailMsgsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = VoiceMsgModule::t('voicemsg', 'voice_msgs');
$this->params['breadcrumbs'][] = $this->title;
$this->params['pageHead'] = $this->title;
?>
<?= Yii::$app->view->renderFile('@app/views/auth/iframe/header.php') ?>
<div id="main" class="extension-main main-full">
    <div class="row">
        <div class="col s12">
            <div class="container">
                <div class="content-wrapper-before"></div>
                <div class="breadcrumbs-dark col s12 m6" id="breadcrumbs-wrapper">
                    <h5 class="breadcrumbs-title mt-0 mb-0"><?= (isset($this->params['pageHead']) ? $this->params['pageHead'] : "") ?></h5>
                    <?= Breadcrumbs::widget([
                        'tag' => 'ol',
                        'options' => ['class' => 'breadcrumbs mb-0'/*, 'target' => 'myFrame'*/],
                        'itemTemplate' => "<li class='breadcrumb-item'>{link}</li>\n",
                        'homeLink' => [
                            'label' => Yii::t('yii', 'Home'),
                            'url' => Url::to(['/extension/extension/dashboard']),
                            //'url' => 'javascript:void(0);',
                            'target' => "extensionFrame",
                            'encode' => false
                        ],
                        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                    ]) ?>
                </div>
                <?php Pjax::begin(['id' => 'voicemail-msg-index', 'timeout' => 0, 'enablePushState' => false]); ?>
                <div class="row">
                    <div class="col-xl-9 col-md-12 col-xs-12">
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
                                                                <?php echo Html::Button(VoiceMsgModule::t('voicemsg', 'delete_selected'), ['class' => 'btn btn-basic delete-multiple']) ?>
                                                                <?php echo Html::Button(Yii::t('app', 'download_recording'), ['class' => 'btn btn-basic download-multiple']) ?>
                                                            </div>
                                                        </div>
                                                        <div class="dataTables_wrapper" id="page-length-option_wrapper">
                                                            <?php try {
                                                                //echo Html::beginForm(['bulk-delete'], 'post', array('id' => 'delete_form'));
                                                                echo Html::beginForm(['bulk-data'], 'post', array('id' => 'data_form'));
                                                                echo GridView::widget([
                                                                    'id' => 'voicemail-msgs-grid-index', // TODO : Add Grid Widget ID
                                                                    'dataProvider' => $dataProvider,
                                                                    'layout' => Yii::$app->layoutHelper->get_layout_str('#voicemail-msgs-search-form'),
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
                                                                            'class' => 'yii\grid\CheckboxColumn',
                                                                            'headerOptions' => ['class' => "check_boxes text-center"],
                                                                            //'contentOptions' => ['class' => 'text-center check_boxes '],
                                                                        ],
                                                                        [
                                                                            'class' => 'yii\grid\ActionColumn',
                                                                            'template' => '{delete}',
                                                                            'header' => Yii::t('app', 'action'),
                                                                            'headerOptions' => ['class' => 'center width-10'],
                                                                            'contentOptions' => ['class' => 'center width-10'],
                                                                            'buttons' => [
                                                                                'delete' => function ($url) {
                                                                                    return (1 ? Html::a('<i class="material-icons">delete</i>', $url, [

                                                                                        'class' => 'ml-5',
                                                                                        'data-pjax' => 0,
                                                                                        'style' => 'color:#FF4B56',
                                                                                        'data-confirm' => Yii::t('app', 'delete_confirm'),
                                                                                        'data-method' => 'post',
                                                                                        'title' => Yii::t('app', 'delete'),
                                                                                    ]) : '');
                                                                                },
                                                                            ]
                                                                        ],
                                                                        [
                                                                            'attribute' => 'status',
                                                                            'format' => 'raw',
                                                                            'headerOptions' => ['class' => 'text-center'],
                                                                            'contentOptions' => ['class' => 'text-center'],
                                                                            'value' => function ($model) {
                                                                                //$wantbtn_class = ($model->read_epoch =="0") ? (('Un Read')? "showbtn" :"hidebtn"):"hidebtn";
                                                                                if ($model->read_epoch == '0') {
                                                                                    return '<span class="new badge gradient-45deg-cyan-light-green"
                                                                            data-badge-caption="">' . VoiceMsgModule::t
                                                                                        ('voicemsg', 'unread') . '</span>';
                                                                                } else if ($model->read_epoch > '0') {
                                                                                    return '<span class="new badge gradient-45deg-cyan-light-green"
                                                                            data-badge-caption="">' . VoiceMsgModule::t
                                                                                        ('voicemsg', 'read') . '</span>';
                                                                                } else {
                                                                                    return '<span class="new badge gradient-45deg-red-pink"
                                                                            data-badge-caption="">' . VoiceMsgModule::t
                                                                                        ('voicemsg', 'all') . '</span>';

                                                                                }

                                                                            },
                                                                            'enableSorting' => TRUE,
                                                                        ],

                                                                        [
                                                                            'attribute' => 'cid_name',
                                                                            'headerOptions' => ['class' => 'text-center'],
                                                                            'contentOptions' => ['class' => 'text-center'],
                                                                            'enableSorting' => TRUE,
                                                                        ],
                                                                        [
                                                                            'attribute' => 'cid_number',
                                                                            'headerOptions' => ['class' => 'text-center'],
                                                                            'contentOptions' => ['class' => 'text-center'],
                                                                            'enableSorting' => TRUE,
                                                                        ],
                                                                        [
                                                                            'attribute' => 'username',
                                                                            'headerOptions' => ['class' => 'text-center'],
                                                                            'contentOptions' => ['class' => 'text-center'],
                                                                            'enableSorting' => TRUE,
                                                                        ],
                                                                        [
                                                                            'attribute' => 'message_len',
                                                                            'headerOptions' => ['class' => 'text-center'],
                                                                            'contentOptions' => ['class' => 'text-center'],
                                                                            'enableSorting' => TRUE,
                                                                        ],
                                                                        [
                                                                            'attribute' => 'created_epoch',
                                                                            'headerOptions' => ['class' => 'text-center'],
                                                                            'contentOptions' => ['class' => 'text-center'],
                                                                            'enableSorting' => TRUE,
                                                                            'value' => function ($model) {
                                                                                $utc = $model->created_epoch;
                                                                                $time = date("Y-m-d H:i:s", substr($utc, 0, 10));

                                                                                return ($time == "0") ?
                                                                                    "N/A" : CommonHelper::tsToDt($time);
                                                                            },
                                                                        ],

                                                                        [
                                                                            'attribute' => 'file_path',
                                                                            'headerOptions' => ['class' => 'text-center'],
                                                                            'contentOptions' => ['class' => 'text-center action_space'],
                                                                            'format' => 'raw',
                                                                            'value' => function ($model) {
                                                                                // $extension=pathinfo($model->file_path, PATHINFO_EXTENSION);
                                                                                if ($model->file_path != '') {
                                                                                    $data = explode('/', $model->file_path);
                                                                                    $end = array_reverse($data)[0];
                                                                                    $file = Url::to('@webroot' . Yii::$app->params['VOICEMAIL_PATH_WEB'] . $_SERVER['HTTP_HOST'] . '/' . Yii::$app->user->identity->em_extension_number . "/" . $end);
                                                                                    if (file_exists($file)) {
                                                                                        $audioFilePath = Url::to('@web' . Yii::$app->params['VOICEMAIL_PATH_WEB'] . $_SERVER['HTTP_HOST'] . '/' . Yii::$app->user->identity->em_extension_number . "/");

                                                                                        $cmd = "sudo chmod -R 777 " . Yii::$app->params['VOICEMAIL_PATH'] . $_SERVER['HTTP_HOST'] . "/" . Yii::$app->user->identity->em_extension_number . "/";
                                                                                        exec($cmd, $output, $returnCode);

                                                                                        $sourcePath = $audioFilePath . $end;
                                                                                        return '<audio controls="controls" controlsList="download">
                                                                            <source src="' . $sourcePath . '" type="audio/wav">
                                                                        </audio>';
                                                                                    } else {
                                                                                        return '-';
                                                                                    }
                                                                                } else {
                                                                                    return '-';
                                                                                }

                                                                            },
                                                                            'enableSorting' => TRUE,
                                                                        ],
                                                                    ],
                                                                    'tableOptions' => [
                                                                        'class' => 'display dataTable dtr-inline',
                                                                    ],
                                                                ]);
                                                                //echo Html::endForm();
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
                <?php Pjax::end(); ?>
                <!--<a class="waves-effect waves-light btn modal-trigger" href="#modal1">Modal</a>-->

                <div id="comman-del" class="modal">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5><?php echo VoiceMsgModule::t('voicemsg', 'delete_selected') ?></h5>
                        </div>
                        <div class="modal-body">
                            <p id="p_id"></p>
                        </div>
                        <div class="modal-footer">
                            <button type="button"
                                    class="btn btn-default modal-close"><?php echo VoiceMsgModule::t('voicemsg', 'close') ?></button>
                            <button type="button" class="btn btn-primary common_delete" data-dismiss="modal"
                                    aria-hidden="true"
                                    onclick="commonDetete('single')"><?php echo VoiceMsgModule::t('voicemsg', 'ok') ?>
                            </button>
                        </div>
                    </div>
                    <!-- <div class="modal-footer fdelete">
                         <button type="button" class="btn btn-default " data-dismiss="modal">Close</button>
                         <button type="button" class="btn btn-primary common_delete" data-dismiss="modal" aria-hidden="true"
                                 onclick="commonDetete('single')">Ok
                         </button>
                     </div>-->
                </div>

                <div id="common-download" class="modal">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5><?php echo Yii::t('app', 'download_recording') ?></h5>
                        </div>
                        <div class="modal-body">
                            <p id="d_id"></p>
                        </div>
                        <div class="modal-footer">
                            <button type="button"
                                    class="btn waves-effect waves-light bg-gray-200 modal-close"><?= VoiceMsgModule::t('voicemsg', 'close') ?></button>
                            <button type="button" class="btn btn-primary common_delete" data-dismiss="modal"
                                    aria-hidden="true"
                                    onclick="commonDownload('single')"><?php echo VoiceMsgModule::t('voicemsg', 'ok') ?>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        $(".select-on-check-all").click(function () {
            $('input:checkbox').not(this).prop('checked', this.checked);
        });
    });

    $(document).on('pjax:success', function (event) {
        $(".select-on-check-all").click(function () {
            $('input:checkbox').not(this).prop('checked', this.checked);
        });
    });

    $(document).on('click', '.delete-multiple', function () {

        $('.modal').modal({dismissible: false});
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
            $('#comman-del').modal('open');
            //$('.mtitle').html('Delete');
            $('#p_id').html('<?=VoiceMsgModule::t('voicemsg', 'please_select_atleast_one_record_delete')?>');
            //$('#com_delete').html('Please select atleast one record to delete');
        } else {
            $('#comman-del').modal('open');
            $('#p_id').html('<?=VoiceMsgModule::t('voicemsg', 'are_you_sure_want_to_delete_selected')?> ' + count +
                ' <?=VoiceMsgModule::t('voicemsg', 'records')?>?');
            $('.common_delete').attr('onclick', 'commonDetete("multiple")');
        }
    });

    $(document).on('click', '.download-multiple', function () {

        $('.modal').modal({dismissible: false});
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
            $('#d_id').html('<?=VoiceMsgModule::t('voicemsg', 'please_select_atleast_one_record_download')?>');
        } else {
            $('#common-download').modal('open');
            $('#d_id').html('<?=VoiceMsgModule::t('voicemsg', 'are_you_sure_want_to_download_selected')?> ' + count +
                ' <?=VoiceMsgModule::t('voicemsg', 'records')?>?');
            $('.common_delete').attr('onclick', 'commonDownload("multiple")');
        }
    });

    function commonDetete(purpose) {
        if (purpose == 'multiple') {

            submitVal = 'delete';
            $('#data_form').append("<input type='hidden' name='optype' value='" +
                submitVal + "' />");

            $('#data_form').submit();
        }
        $('#comman-del').modal('close');
        $('body').removeClass('modal-open');
        $('.modal-backdrop').hide();
    }

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
