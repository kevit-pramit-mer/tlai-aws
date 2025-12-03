<?php

use app\components\CommonHelper;
use app\modules\ecosmob\agentscallreport\AgentsCallReportModule;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\modules\ecosmob\campaign\models\Campaign;
use yii\helpers\Url;
use app\modules\ecosmob\queue\models\QueueMaster;
use app\modules\ecosmob\leadgroupmember\models\LeadGroupMember;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\ecosmob\agentscallreport\models\AgentsCallReportSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = AgentsCallReportModule::t('agentscallreport', 'agentsreport');
$this->params['breadcrumbs'][] = $this->title;
$this->params['pageHead'] = $this->title;
?>
<?php Pjax::begin(['id' => 'agents-call-report-index', 'timeout' => 0, 'enablePushState' => false]); ?>
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
                                                   <!-- --><?php /*= Html::a(AgentsCallReportModule::t('agentscallreport', 'export'), ['/agentscallreport/agents-call-report/export'], [
                                                        'id' => 'hov view_link',
                                                        'data-pjax' => '0',
                                                        'class' => 'exportbutton lead_group btn waves-effect waves-light darken-1 breadcrumbs-btn right',
                                                    ]) */?>
                                                    <button id="export-button" class="exportbutton lead_group btn waves-effect waves-light darken-1 breadcrumbs-btn right">
                                                        <?= AgentsCallReportModule::t('agentscallreport', 'export') ?>
                                                    </button>
                                                </div>
                                            </div>
                                            <?php try {
                                                echo GridView::widget([
                                                    'id' => 'agents-call-report-grid-index', // TODO : Add Grid Widget ID
                                                    'dataProvider' => $dataProvider,
                                                    'layout' => Yii::$app->layoutHelper->get_layout_str('#agents-call-report-search-form'),
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
                                                        ['class' => 'yii\grid\SerialColumn'],
                                                        [
                                                            'attribute' => 'agent_name',
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'enableSorting' => TRUE,
                                                            'value' => function ($model) {
                                                                return (!empty($model->agent_name) ? $model->agent_name : '-');
                                                            }
                                                        ],
                                                        [
                                                            'attribute' => 'caller_id_num',
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'enableSorting' => TRUE,
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
                                                                //return (!empty($model->caller_id_num) ? $model->caller_id_num : '-');
                                                            }
                                                        ],
                                                        [
                                                            'attribute' => 'dial_number',
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'enableSorting' => TRUE,
                                                            'value' => function ($model) {
                                                                return (!empty($model->dial_number) ? $model->dial_number : '-');
                                                            }
                                                        ],
                                                        [
                                                            'attribute' => 'queue',
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
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
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'enableSorting' => TRUE,
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
                                                            'attribute' => 'cmp_type',
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'enableSorting' => TRUE,
                                                            'value' => function ($model) {
                                                                if (!empty($model->current_active_camp)) {
                                                                    $campaign = Campaign::findOne($model->current_active_camp);
                                                                    if(!empty($campaign)){
                                                                        return $campaign->cmp_type;
                                                                    } else {
                                                                        return '-';
                                                                    }
                                                                }else {
                                                                    if (!empty($model->cmp_type)) {
                                                                        return $model->cmp_type;
                                                                    } else {
                                                                        return '-';
                                                                    }
                                                                }
                                                            }
                                                        ],
                                                        [
                                                            'attribute' => 'customer_name',
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
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
                                                        [
                                                            'attribute' => 'date',
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'value' => function ($model) {
                                                                return (!empty($model->start_time) ?
                                                                    date('Y-m-d', strtotime(CommonHelper::tsToDt($model->start_time))) : '-');
                                                            }
                                                        ],
                                                        [
                                                            'attribute' => 'start_time',
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'enableSorting' => TRUE,
                                                            'value' => function ($model) {
                                                                return (!empty($model->start_time) ?
                                                                    date('H:i:s', strtotime(CommonHelper::tsToDt($model->start_time))) : '-');
                                                            }
                                                        ],
                                                        [
                                                            'attribute' => 'ans_time',
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'enableSorting' => True,
                                                            'value' => function ($model) {
                                                                if (!empty($model->ans_time)) {
                                                                    return date('H:i:s', strtotime(CommonHelper::tsToDt($model->ans_time)));
                                                                } else {
                                                                    return '-';
                                                                }
                                                            }
                                                        ],
                                                        [
                                                            'attribute' => 'end_time',
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'enableSorting' => TRUE,
                                                            'value' => function ($model) {
                                                                return (!empty($model->end_time) ?
                                                                    date('H:i:s', strtotime(CommonHelper::tsToDt($model->end_time))) : '-');
                                                            }
                                                        ],
                                                        [
                                                            'attribute' => 'call_waiting',
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
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
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'enableSorting' => TRUE,
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
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'enableSorting' => TRUE,
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
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'enableSorting' => TRUE,
                                                            'value' => function ($model) {
                                                                return (!empty($model->call_disposion_name) ? $model->call_disposion_name : '-');
                                                            }
                                                        ],
                                                        [
                                                            'attribute' => 'wrap_up_time',
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'enableSorting' => TRUE,
                                                            'value' => function ($model) {
                                                                if (!empty($model->wrap_up_time)) {
                                                                    return gmdate("H:i:s", $model->wrap_up_time);
                                                                } else {
                                                                    return '-';
                                                                }
                                                            }
                                                        ],
                                                        [
                                                            'attribute' => 'disposition_comment',
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'enableSorting' => TRUE,
                                                            'value' => function ($model) {
                                                                return (!empty($model->disposition_comment) ? $model->disposition_comment : '-');
                                                            }
                                                        ],
                                                        [
                                                            'attribute' => 'call_status',
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'enableSorting' => TRUE,
                                                            'value' => function ($model) {
                                                                return (!empty($model->call_status) ? $model->call_status : '-');
                                                            }
                                                        ],
                                                        [
                                                            'attribute' => 'id',
                                                            'label' => AgentsCallReportModule::t('agentscallreport', 'action'),
                                                            'format' => 'raw',
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'enableSorting' => True,
                                                            'value' => function ($model) {
                                                                if (!empty($model->ans_time)) {
                                                                    $recording_file = $model->recording_file;
                                                                    if ($recording_file != '') {
                                                                        $end = explode('/', $recording_file);
                                                                        $end = array_reverse($end)[0];
                                                                        $audioFilePath = Url::to('@web' . '/media/recordings');
                                                                        $url = $audioFilePath . '/' . $GLOBALS['tenantID'] . '/' . $end;
                                                                        exec("chmod -R 0777 " . $url);
                                                                        return '<audio controls="controls" controlsList="nodownload">
                                                                        <source src="' . $url . '" type="audio/wav">
                                                                    </audio>';
                                                                    } else {
                                                                        return '-';
                                                                    }
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
            alert('" . AgentsCallReportModule::t('agentscallreport', 'no_records_found_to_export') . "');
            return false;
        }else{
            event.preventDefault(); 
            window.location.href = '".Url::to(['/agentscallreport/agents-call-report/export'])."';
        }
    });");
?>

