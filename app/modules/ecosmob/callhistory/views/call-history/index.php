<?php

use app\components\CommonHelper;
use app\modules\ecosmob\callhistory\CallHistoryModule;
use app\modules\ecosmob\campaign\models\Campaign;
use app\modules\ecosmob\queue\models\QueueMaster;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use app\modules\ecosmob\leadgroupmember\models\LeadGroupMember;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\ecosmob\callhistory\models\CampCdrSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = CallHistoryModule::t('callhistory', 'report');
$this->params['breadcrumbs'][] = $this->title;
$this->params['pageHead'] = $this->title;

$GLOBALS['WEB_RECORDING_PATH'] = Yii::$app->params['WEB_RECORDING_PATH'] . $GLOBALS['tenantID'] . '/recordings/';

$session = yii::$app->session;
$agentCamp = $session->get('selectedCampaign');
?>
<?php Pjax::begin(['id' => 'call-history-index', 'timeout' => 0, 'enablePushState' => false]); ?>
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
                                                    <?php /*= Html::a(CallHistoryModule::t('callhistory', 'export'), ['/callhistory/call-history/export'], [
                                                        'id' => 'hov view_link',
                                                        'data-pjax' => '0',
                                                        'class' => 'exportbutton lead_group btn waves-effect waves-light darken-1 breadcrumbs-btn right',
                                                    ]) */?>
                                                    <button id="export-button" class="exportbutton lead_group btn waves-effect waves-light darken-1 breadcrumbs-btn right">
                                                        <?= CallHistoryModule::t('callhistory', 'export') ?>
                                                    </button>
                                                </div>
                                            </div>
                                            <?php try {
                                                echo GridView::widget([
                                                    'id' => 'camp-cdr-grid-index', // TODO : Add Grid Widget ID
                                                    'dataProvider' => $dataProvider,
                                                    'layout' => Yii::$app->layoutHelper->get_layout_str('#camp-cdr-search-form'),
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
                                                        // ['class'=>($agentCamp ? 'yii\grid\SerialColumn' : 'noclass')],
                                                        ['class' => 'yii\grid\SerialColumn'],
                                                        [
                                                            'attribute' => 'dial_number',
                                                            'headerOptions' => ['class' => 'text-center', 'style' => 'min-width: 150px !important;max-width: 150px !important; word-wrap:break-word'],
                                                            'contentOptions' => ['class' => 'text-center', 'style' => 'min-width: 150px !important;max-width: 150px !important; word-wrap:break-word'],
                                                            'enableSorting' => True,
                                                            'value' => function ($model) {
                                                                if (!empty($model->dial_number)) {
                                                                    return $model->dial_number;
                                                                } else {
                                                                    return '-';
                                                                }
                                                            }
                                                        ],
                                                        /*[
                                                            'attribute'=>'extension_number',
                                                            'label'=>'Caller ID',
                                                            'headerOptions'=>['class'=>'text-center'],
                                                            'contentOptions'=>['class'=>'text-center'],
                                                            'enableSorting'=> TRUE,
                                                            'value'=>function ($model) {
                                                                if (!empty($model->extension_number)) {
                                                                    return $model->extension_number;
                                                                } else {
                                                                    return '-';
                                                                }
                                                            }
                                                        ],*/
                                                        /*    [
                                                                'attribute'=>'caller_id_num',
                                                                'label'=>CallHistoryModule::t('callhistory', 'caller_id_num'),
                                                                //'label'=>'Caller ID',
                                                                'headerOptions'=>['class'=>'text-center'],
                                                                'contentOptions'=>['class'=>'text-center'],
                                                                'enableSorting'=> TRUE,
                                                                'value'=>function ($model) {
                                                                    if (!empty($model->caller_id_num)) {
                                                                        return $model->caller_id_num;
                                                                    } else {
                                                                        return '-';
                                                                    }
                                                                }
                                                            ],*/

                                                        [
                                                            'attribute' => 'caller_id_num',
                                                            'label' => CallHistoryModule::t('callhistory', 'caller_id_num'),
                                                            //'label'=>'Caller ID',
                                                            'headerOptions' => ['class' => 'text-center', 'style' => 'min-width: 150px !important;max-width: 150px !important; word-wrap:break-word'],
                                                            'contentOptions' => ['class' => 'text-center', 'style' => 'min-width: 150px !important;max-width: 150px !important; word-wrap:break-word'],
                                                            'enableSorting' => True,
                                                            'value' => function ($model) {
                                                                $cmp_type = Campaign::findOne(['cmp_id' => $model->camp_name]);
                                                                if (($cmp_type->cmp_type == 'Outbound' && $cmp_type->cmp_dialer_type == 'PROGRESSIVE') || ($cmp_type->cmp_type == 'Outbound' && $cmp_type->cmp_dialer_type == 'PREVIEW') || ($cmp_type->cmp_type == 'Blended' && $cmp_type->cmp_dialer_type == 'AUTO' && empty($model->queue))) {
                                                                    if (!empty($model->extension_number)) {
                                                                        return $model->extension_number;
                                                                    } else {
                                                                        return '-';
                                                                    }
                                                                } else {
                                                                    if (!empty($model->caller_id_num)) {
                                                                        return $model->caller_id_num;
                                                                    } else {
                                                                        return '-';
                                                                    }
                                                                }
                                                            }
                                                        ],
//                                                        [
//                                                            'attribute' => 'did',
//                                                            'headerOptions' => ['class' => 'text-center', 'style' => 'min-width: 150px !important;max-width: 150px !important; word-wrap:break-word'],
//                                                            'contentOptions' => ['class' => 'text-center', 'style' => 'min-width: 150px !important;max-width: 150px !important; word-wrap:break-word'],
//                                                            'enableSorting' => True,
//                                                            'value' => function ($model) {
//                                                                $cmp_type = Campaign::findOne(['cmp_id' => $model->camp_name]);
//                                                                if (($cmp_type->cmp_type == 'Inbound') || ($cmp_type->cmp_type == 'Blended' && $cmp_type->cmp_dialer_type == 'AUTO' && !empty($model->queue))) {
//                                                                    if (!empty($model->dial_number)) {
//                                                                        return $model->dial_number;
//                                                                    } else {
//                                                                        return '-';
//                                                                    }
//                                                                } else {
//                                                                    return '-';
//                                                                }
//                                                            }
//                                                        ],
                                                        [
                                                            'attribute' => 'queue',
                                                            'label' => CallHistoryModule::t('callhistory', 'queue'),
                                                            'headerOptions' => ['class' => 'text-center', 'style' => 'min-width: 150px !important;max-width: 150px !important; word-wrap:break-word'],
                                                            'contentOptions' => ['class' => 'text-center', 'style' => 'min-width: 150px !important;max-width: 150px !important; word-wrap:break-word'],
                                                            'enableSorting' => True,
                                                            'value' => function ($model) {
                                                                if (!empty($model->queue)) {
                                                                    return QueueMaster::getQueueName($model->queue);
                                                                } else {
                                                                    return '-';
                                                                }
                                                            }
                                                        ],
                                                        [
                                                            'attribute' => 'campaign_name',
                                                            'headerOptions' => ['class' => 'text-center', 'style' => 'min-width: 150px !important;max-width: 150px !important; word-wrap:break-word'],
                                                            'contentOptions' => ['class' => 'text-center', 'style' => 'min-width: 150px !important;max-width: 150px !important; word-wrap:break-word'],
                                                            'enableSorting' => True,
                                                            'value' => function ($model) {
                                                                if (!empty($model->current_active_camp)) {
                                                                    $campaign = Campaign::findOne($model->current_active_camp);
                                                                    if(!empty($campaign)){
                                                                        return $campaign->cmp_name;
                                                                    } else {
                                                                        return '-';
                                                                    }
                                                                }else {
                                                                    if (!empty($model->campaign_name)) {
                                                                        return $model->campaign_name;
                                                                    } else {
                                                                        return '-';
                                                                    }
                                                                }
                                                            }
                                                        ],
                                                        [
                                                            'attribute' => 'agent_first_name',
                                                            'headerOptions' => ['class' => 'text-center', 'style' => 'min-width: 150px !important;max-width: 150px !important; word-wrap:break-word'],
                                                            'contentOptions' => ['class' => 'text-center', 'style' => 'min-width: 150px !important;max-width: 150px !important; word-wrap:break-word'],
                                                            'enableSorting' => True,
                                                            'value' => function ($model) {
                                                                if (!empty($model->agent_first_name)) {
                                                                    return $model->agent_first_name;
                                                                } else {
                                                                    return '-';
                                                                }
                                                            }
                                                        ],
                                                        [
                                                            'attribute' => 'agent_last_name',
                                                            'headerOptions' => ['class' => 'text-center', 'style' => 'min-width: 150px !important;max-width: 150px !important; word-wrap:break-word'],
                                                            'contentOptions' => ['class' => 'text-center', 'style' => 'min-width: 150px !important;max-width: 150px !important; word-wrap:break-word'],
                                                            'enableSorting' => True,
                                                            'value' => function ($model) {
                                                                if (!empty($model->agent_last_name)) {
                                                                    return $model->agent_last_name;
                                                                } else {
                                                                    return '-';
                                                                }
                                                            }
                                                        ],
                                                        [
                                                            'attribute' => 'customer_first_name',
                                                            'label' => CallHistoryModule::t('callhistory', 'customer_name'),
                                                            'headerOptions' => ['class' => 'text-center', 'style' => 'min-width: 150px !important;max-width: 150px !important; word-wrap:break-word'],
                                                            'contentOptions' => ['class' => 'text-center', 'style' => 'min-width: 150px !important;max-width: 150px !important; word-wrap:break-word'],
                                                            'enableSorting' => True,
                                                            'value' => function ($model) {
                                                                $camp = '';
                                                                if (!empty($model->current_active_camp)) {
                                                                    $camp = $model->current_active_camp;
                                                                } else {
                                                                    $camp = $model->camp_name;
                                                                }
                                                                if (!empty($camp)) {
                                                                    $campaign = Campaign::findOne($camp);
                                                                    if (!empty($campaign)) {
                                                                        $lead = LeadGroupMember::find()
                                                                            ->andWhere(['ld_id' => $campaign->cmp_lead_group])
                                                                            ->andWhere(['or',
                                                                                ['lg_contact_number' => $model->caller_id_num],
                                                                                ['lg_contact_number' => $model->dial_number]
                                                                            ])->one();
                                                                        if (!empty($lead)) {
                                                                            return $lead->lg_first_name . ' ' . $lead->lg_last_name;
                                                                        }else {
                                                                            return '-';
                                                                        }
                                                                    } else {
                                                                        return '-';
                                                                    }
                                                                } else {
                                                                    return '-';
                                                                }

                                                            }
                                                        ],
                                                        /*[
                                                            'attribute' => 'customer_last_name',
                                                            'headerOptions' => ['class' => 'text-center', 'style' => 'min-width: 150px !important;max-width: 150px !important; word-wrap:break-word'],
                                                            'contentOptions' => ['class' => 'text-center', 'style' => 'min-width: 150px !important;max-width: 150px !important; word-wrap:break-word'],
                                                            'enableSorting' => True,
                                                            'value' => function ($model) {
                                                                if (!empty($model->customer_last_name)) {
                                                                    return $model->customer_last_name;
                                                                } else {
                                                                    return '-';
                                                                }
                                                            }
                                                        ],*/

                                                        [
                                                            'attribute' => 'start_time',
                                                            'headerOptions' => ['class' => 'text-center', 'style' => 'min-width: 150px !important;max-width: 150px !important; word-wrap:break-word'],
                                                            'contentOptions' => ['class' => 'text-center', 'style' => 'min-width: 150px !important;max-width: 150px !important; word-wrap:break-word'],
                                                            'enableSorting' => True,
                                                            'value' => function ($model) {
                                                                if (!empty($model->start_time)) {
                                                                    return CommonHelper::tsToDt($model->start_time);
                                                                } else {
                                                                    return '-';
                                                                }
                                                            }
                                                        ],

                                                        [
                                                            'attribute' => 'ans_time',
                                                            'headerOptions' => ['class' => 'text-center', 'style' => 'min-width: 150px !important;max-width: 150px !important; word-wrap:break-word'],
                                                            'contentOptions' => ['class' => 'text-center', 'style' => 'min-width: 150px !important;max-width: 150px !important; word-wrap:break-word'],
                                                            'enableSorting' => True,
                                                            'value' => function ($model) {
                                                                if (!empty($model->ans_time)) {
                                                                    return CommonHelper::tsToDt($model->ans_time);
                                                                } else {
                                                                    return '-';
                                                                }
                                                            }
                                                        ],

                                                        [
                                                            'attribute' => 'end_time',
                                                            'headerOptions' => ['class' => 'text-center', 'style' => 'min-width: 150px !important;max-width: 150px !important; word-wrap:break-word'],
                                                            'contentOptions' => ['class' => 'text-center', 'style' => 'min-width: 150px !important;max-width: 150px !important; word-wrap:break-word'],
                                                            'enableSorting' => True,
                                                            'value' => function ($model) {
                                                                if (!empty($model->end_time)) {
                                                                    return CommonHelper::tsToDt($model->end_time);
                                                                } else {
                                                                    return '-';
                                                                }
                                                            }
                                                        ],
                                                        [
                                                            'attribute' => 'call_waiting',
                                                            'headerOptions' => ['class' => 'text-center', 'style' => 'min-width: 150px !important;max-width: 150px !important; word-wrap:break-word'],
                                                            'contentOptions' => ['class' => 'text-center', 'style' => 'min-width: 150px !important;max-width: 150px !important; word-wrap:break-word'],
                                                            'enableSorting' => True,
                                                            'value' => function ($model) {
                                                                if (!empty($model->call_waiting)) {
                                                                    return gmdate("H:i:s", $model->call_waiting);
                                                                } else {
                                                                    return '-';
                                                                }
                                                            }
                                                        ],
                                                        [
                                                            'attribute' => 'call_duration',
                                                            'headerOptions' => ['class' => 'text-center', 'style' => 'min-width: 150px !important;max-width: 150px !important; word-wrap:break-word'],
                                                            'contentOptions' => ['class' => 'text-center', 'style' => 'min-width: 150px !important;max-width: 150px !important; word-wrap:break-word'],
                                                            'enableSorting' => True,
                                                            'value' => function ($model) {
                                                                if (!empty($model->call_duration)) {
                                                                    return gmdate("H:i:s", $model->call_duration);
                                                                } else {
                                                                    return '-';
                                                                }
                                                            }
                                                        ],
                                                        [
                                                            'attribute' => 'agent_duration',
                                                            'headerOptions' => ['class' => 'text-center', 'style' => 'min-width: 150px !important;max-width: 150px !important; word-wrap:break-word'],
                                                            'contentOptions' => ['class' => 'text-center', 'style' => 'min-width: 150px !important;max-width: 150px !important; word-wrap:break-word'],
                                                            'enableSorting' => True,
                                                            'value' => function ($model) {
                                                                if (!empty($model->agent_duration)) {
                                                                    return gmdate("H:i:s", $model->agent_duration);
                                                                } else {
                                                                    return '-';
                                                                }
                                                            }
                                                        ],
                                                        [
                                                            'attribute' => 'call_disposion_name',
                                                            'headerOptions' => ['class' => 'text-center', 'style' => 'min-width: 150px !important;max-width: 150px !important; word-wrap:break-word'],
                                                            'contentOptions' => ['class' => 'text-center', 'style' => 'min-width: 150px !important;max-width: 150px !important; word-wrap:break-word'],
                                                            'enableSorting' => True,
                                                            'value' => function ($model) {
                                                                if (!empty($model->call_disposion_name)) {
                                                                    return $model->call_disposion_name;
                                                                } else {
                                                                    return '-';
                                                                }
                                                            }
                                                        ],
                                                        [
                                                            'attribute' => 'disposition_comment',
                                                            'headerOptions' => ['class' => 'text-center', 'style' => 'min-width: 150px !important;max-width: 150px !important; word-wrap:break-word'],
                                                            'contentOptions' => ['class' => 'text-center', 'style' => 'min-width: 150px !important;max-width: 150px !important; word-wrap:break-word'],
                                                            'enableSorting' => True,
                                                            'value' => function ($model) {
                                                                if (!empty($model->disposition_comment)) {
                                                                    return $model->disposition_comment;
                                                                } else {
                                                                    return '-';
                                                                }
                                                            }
                                                        ],
                                                        /*[
                                                            'attribute' => 'call_disposion_decription',
                                                            'headerOptions' => ['class' => 'text-center', 'style' => 'min-width: 150px !important;max-width: 150px !important; word-wrap:break-word'],
                                                            'contentOptions' => ['class' => 'text-center', 'style' => 'min-width: 150px !important;max-width: 150px !important; word-wrap:break-word'],
                                                            'enableSorting' => True,
                                                            'value' => function ($model) {
                                                                if (!empty($model->call_disposion_decription)) {
                                                                    return $model->call_disposion_decription;
                                                                } else {
                                                                    return '-';
                                                                }
                                                            }
                                                        ],*/
                                                        [
                                                            'attribute' => 'call_status',
                                                            'headerOptions' => ['class' => 'text-center', 'style' => 'min-width: 150px !important;max-width: 150px !important; word-wrap:break-word'],
                                                            'contentOptions' => ['class' => 'text-center', 'style' => 'min-width: 150px !important;max-width: 150px !important; word-wrap:break-word'],
                                                            'enableSorting' => True,
                                                            'value' => function ($model) {
                                                                if (!empty($model->call_status)) {
                                                                    return $model->call_status;
                                                                } else {
                                                                    return '-';
                                                                }
                                                            }
                                                        ],
                                                        [
                                                            'attribute' => 'id',
                                                            'visible' => ($agentCamp ? false : true),
                                                            'label' => CallHistoryModule::t('callhistory', 'action'),
                                                            //'label'=>'Action',
                                                            'format' => 'raw',
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            //'contentOptions' => ['class' => 'text-center inline-class action_space'],
                                                            'enableSorting' => True,
                                                            'value' => function ($model) {

                                                                $recording_file = $model->recording_file;
                                                                if ($recording_file != '') {
                                                                    $end = explode('/', $recording_file);
                                                                    $end = array_reverse($end)[0];

                                                                    if (!file_exists(Yii::$app->params['WEB_RECORDING_PATH'] . $GLOBALS['tenantID'] . '/recordings/' . $end)) {
                                                                        return '-';
                                                                    }

                                                                    $audioFilePath = Url::to('@web' . '/media/recordings');
                                                                    $url = $audioFilePath . '/' . $GLOBALS['tenantID'] . '/' . $end;


                                                                    return '<audio controls="controls" controlsList="nodownload">
                                                                        <source src="' . $url . '" type="audio/wav">
                                                                    </audio>
                                                                    <a href="' . $url . '" download="' . $url . '"><i class="material-icons" style="color: #474747">file_download</i></a> 
                                                                    ';
                                                                } else {
                                                                    return '-';
                                                                }

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
<?php
$this->registerJs("
    $(document).on('click', '.exportbutton', function () {
        var count = ((!$('.providercount').data('count')) ? 0 : $('.providercount').data('count'));
        if (count <= 0) {
            alert('" . CallHistoryModule::t('callhistory', 'no_records_found_to_export') . "');
            return false;
        }else{
            event.preventDefault(); 
            window.location.href = '".Url::to(['/callhistory/call-history/export'])."';
        }
    });");
?>

