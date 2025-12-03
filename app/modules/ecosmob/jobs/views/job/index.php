<?php

use app\modules\ecosmob\jobs\assets\JobsAsset;
use app\modules\ecosmob\jobs\JobsModule;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\ecosmob\jobs\models\JobSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $model */
/* @var $campaignList */

$this->title = JobsModule::t('jobs', 'job');
$this->params['breadcrumbs'][] = $this->title;
$this->params['pageHead'] = $this->title;
$permissions = $GLOBALS['permissions'];

JobsAsset::register($this);
?>
<div class="col s12 m7 pt-1 pb-1 pr-0 mob-m">
    <!--<a class="mb-6 btn waves-effect waves-light green darken-1 breadcrumbs-btn right">Add New</a>-->
    <?php if (in_array('/jobs/job/create', $permissions)) { ?>
        <?= Html::a(JobsModule::t('jobs', 'add_new'), ['create'], [
            'id' => 'hov',
            'data-pjax' => 0,
            'class' => ' btn waves-effect waves-light darken-1 breadcrumbs-btn right',
        ]) ?>
    <?php } ?>
</div>
<?php Pjax::begin(['id' => 'job-index', 'timeout' => 0, 'enablePushState' => false]); ?>
<div class="row">
    <div class="col-xl-9 col-md-7 col-xs-12">
        <div class="row">
            <div class="col s12">
                <div class="profile-contain">
                    <div class="section section-data-tables">
                        <div class="row">
                            <div class="col s12 search-filter">
                                <?= $this->render('_search', ['model' => $searchModel, 'campaignList' => $campaignList]); ?>
                            </div>
                            <div class="col s12">
                                <div class="card">
                                    <div class="card-content">
                                        <div class="dataTables_wrapper" id="page-length-option_wrapper">
                                            <?php try {
                                                echo GridView::widget([
                                                        'id' => 'job-grid-index', // TODO : Add Grid Widget ID
                                                        'dataProvider' => $dataProvider,
                                                        'layout' => Yii::$app->layoutHelper->get_layout_str('#job-search-form'),
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
                                                                'template' => '{update}{delete}{play}{pause}{copy-job}',
                                                                'header' => Yii::t('app', 'action'),
                                                                'headerOptions' => ['class' => 'center width-10'],
                                                                'contentOptions' => ['class' => 'center width-10'],
                                                                'buttons' => [
                                                                    'update' => function ($url) use ($permissions) {
                                                                        if (in_array('/jobs/job/update', $permissions)) {
                                                                            return (1 ? Html::a('<i class="material-icons">edit</i>', $url, [
                                                                                'style' => '',
                                                                                'title' => Yii::t('app', 'update'),
                                                                            ]) : '');
                                                                        } else {
                                                                            return '';
                                                                        }
                                                                    },
                                                                    'delete' => function ($url) use ($permissions) {
                                                                        if (in_array('/jobs/job/delete', $permissions)) {
                                                                            return (1 ? Html::a('<i class="material-icons">delete</i>', $url, [

                                                                                'class' => 'ml-5',
                                                                                'data-pjax' => 0,
                                                                                'style' => 'color:#FF4B56',
                                                                                'data-confirm' => Yii::t('app', 'delete_confirm'),
                                                                                'data-method' => 'post',
                                                                                'title' => Yii::t('app', 'delete'),
                                                                            ]) : '');
                                                                        } else {
                                                                            return '';
                                                                        }
                                                                    },
                                                                    'play' => function ($url, $model) use ($permissions) {
                                                                        if (in_array('/jobs/job/change-job-status', $permissions)) {
                                                                            $job_status = $model->job_status;
                                                                            $activation_status = $model->activation_status;
                                                                            $job_id = $model->job_id;
                                                                            $url = 'javascript:void(0)';

                                                                            return ($activation_status ? Html::a('<i class="material-icons">' . (($job_status) ? 'pause_circle_filled' : 'play_circle_filled') . '</i>', $url, [

                                                                                'class' => 'ml-5 play',
                                                                                'data-pjax' => 0,
                                                                                'data-id' => $job_id,
                                                                                'data-status' => $job_status,
                                                                                'data-active-status' => $activation_status,
                                                                                'style' => 'color:#FF4B56',
                                                                                'title' => JobsModule::t('jobs', 'change_activation_status'),
                                                                            ]) :

                                                                                Html::a('<i class="material-icons">error</i>', $url, [
                                                                                    'class' => 'ml-5',
                                                                                    'data-pjax' => 0,
                                                                                    'style' => 'color#8a8a8a',
                                                                                    'title' => JobsModule::t('jobs', 'can_not_start'),
                                                                                ])
                                                                            );
                                                                        } else {
                                                                            return '';
                                                                        }
                                                                    },
                                                                    'copy-job' => function ($url, $model) use ($permissions) {
                                                                        if (in_array('/jobs/job/get-job', $permissions)) {
                                                                            return html::tag("span", "<i class=\"material-icons\">content_copy</i>",
                                                                                [
                                                                                    'class' => 'ml-5 copy-job',
                                                                                    'data-pjax' => 1,
                                                                                    'data-value' => $model->job_id,
                                                                                    'data-method' => 'post',
                                                                                    'data-toggle' => "modal",
                                                                                    'data-placement' => 'top',
                                                                                    'data-keyboard' => "false",
                                                                                    'data-backdrop' => 'static',
                                                                                    'title' => JobsModule::t('jobs', 'copy'),
                                                                                ]
                                                                            );
                                                                        } else {
                                                                            return '';
                                                                        }
                                                                    },

                                                                ]
                                                            ],
                                                            [
                                                                'attribute' => 'activation_status',
                                                                'format' => 'raw',
                                                                'headerOptions' => ['class' => 'text-center'],
                                                                'contentOptions' => ['class' => 'text-center'],
                                                                'value' => function ($model) {
                                                                    if ($model->activation_status == 1) {
                                                                        return '<span class="new badge gradient-45deg-cyan-light-green"
                                                                            data-badge-caption="">' . Yii::t('app', 'active') . '</span>';
                                                                    } else {
                                                                        return '<span class="new badge gradient-45deg-red-pink"
                                                                            data-badge-caption="">' . Yii::t('app', 'inactive') . '</span>';
                                                                    }

                                                                },
                                                                'enableSorting' => TRUE,
                                                            ],
                                                            [
                                                                'attribute' => 'job_name',
                                                                'headerOptions' => ['class' => 'text-center'],
                                                                'contentOptions' => ['class' => 'text-center'],
                                                                'enableSorting' => TRUE,
                                                            ],
                                                            [
                                                                'attribute' => 'answer_timeout',
                                                                'headerOptions' => ['class' => 'text-center'],
                                                                'contentOptions' => ['class' => 'text-center'],
                                                                'enableSorting' => TRUE,
                                                            ],
                                                            [
                                                                'attribute' => 'campaign_id',
                                                                'value' => 'campaign.cmp_name',
                                                                'headerOptions' => ['class' => 'text-center'],
                                                                'contentOptions' => ['class' => 'text-center'],
                                                                'enableSorting' => TRUE,
                                                            ],
                                                        ],
                                                        'tableOptions' => [
                                                            'class' => 'display dataTable dtr-inline',

                                                        ],
                                                    ]
                                                );
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
<script type="text/javascript">
    /*FOR PLAY PAUSE BUTTON HIDE SHOW  START*/
    $(document).on('click', '.play', function (e) {
        e.preventDefault();
        var current_job_status = $(this).attr('data-status');
        var job_id = $(this).attr('data-id');
        var material_icon = $(this).find('.material-icons');
        var arr = {
            'job_id': job_id,
            'current_job_status': current_job_status,
        };
        console.log(arr);
        // if(material_icon.html() == 'play_circle_filled'){
        //     material_icon.html('pause_circle_filled');
        // }
        // else{
        //     material_icon.html('play_circle_filled');
        // }
        $.ajax({
            url: "<?= Url::to(['/jobs/job/change-job-status']) ?>",
            method: 'POST',
            data: arr
        }).done(function (data) {
            // $.pjax({container: '#pjax-job'});
            window.location.reload();
        });
    });
</script>

<div id="copyModal" class="modal">
    <div class="modal-content">
        <div class="pl-3 pr-3 pt-2">
            <h6><?=JobsModule::t('jobs','copy')?> : <span
                        id="jobName"></span></h6>

            <?php $form = ActiveForm::begin([
                'id' => 'outbound-dial-plans-modal-form',
                'enableAjaxValidation' => true,
                'action' => Url::to(['/jobs/job/copy-job']),
            ]); ?>
            <div class="col-xs-12">
                <?= $form->field($model, 'job_name',
                    ['options' => ['class' => '']])->textInput([
                    'maxlength' => true,
                    'placeholder' => JobsModule::t('jobs','job_name'),
                    'class' => 'jobname',
                ]); /* ->label(DialPlanModule::t('dialplan',
                                    "odp_name") . " <span><i class='icon fa fa-question-circle fa-lg' data-toggle='popover'  data-trigger ='hover' data-content='" . DialPlanModule::t('dialplan',
                                    "odp_name_label") . "' ></i></span>"); */  ?>
                <div class="msgbox"></div>
                <!--<div class="msgbox" style="color: #d32f2f !important"></div>-->
                <?= Html::hiddenInput('jobId', '', ['id' => 'jobId']) ?>
            </div>
        </div>
    </div>
    <div class="modal-footer pb-2">
        <?= Html::submitButton(Yii::t('app','create'), ['class' => 'btn btn-success grid-top-button common-btn-radius mr-top-right']) ?>
        <a href="#" class="modal-close waves-effect waves-green bg-gray-200 btn-flat"><?=Yii::t('app','cancel')?></a>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<script type="text/javascript">

    $(function () {
        $(".jobname").keypress(function (e) {
            var keyCode = e.keyCode || e.which;
            $(".msgbox").html("");

            var regex = /^[A-Za-z ]+$/;

            var isValid = regex.test(String.fromCharCode(keyCode));
            if (!isValid) {
                //$(".msgbox").html("Only Alphabets and Space allowed.");
                $(".msgbox").html("<?php echo JobsModule::t('jobs', 'job_name_validation') ?>");
                return false;
            }
            return isValid;
        });
    });

    /*    $('.jobname').keypress(function (e) {
            var regex = new RegExp("^[a-zA-Z ]+$");
            var strigChar = String.fromCharCode(!e.charCode ? e.which : e.charCode);
            if (regex.test(strigChar)) {
                $('.help-block').val('Invalid name');
                return true;
            }
            return false
        })*/

    $(document).on('click', '.copy-job', function (e) {
        e.preventDefault();
        let id = $(this).attr('data-value');
        getJobName(id);
        $('.modal').modal({dismissible: false});
        $('#copyModal').modal('open');

    });

    function getJobName(id) {
        $.post("<?php echo Url::toRoute('/jobs/job/get-job'); ?>", {'id': id}, function (response, status) {
            var job = $.parseJSON(response);
            $("#jobId").val(job[0]);
            $("#jobName").html(job[1]);
        });
    }
</script>
