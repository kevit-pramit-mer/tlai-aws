<?php

use app\components\CommonHelper;
use app\modules\ecosmob\clienthistory\ClientHistoryModule;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;
use app\modules\ecosmob\crm\models\LeadGroupMember;
use app\modules\ecosmob\campaign\models\Campaign;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\ecosmob\clienthistory\models\CampCdrSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = ClientHistoryModule::t('clienthistory', 'clt_history');
$this->params['breadcrumbs'][] = $this->title;
$this->params['pageHead'] = $this->title;
?>
<?php Pjax::begin(['id' => 'client-history-index', 'timeout' => 0, 'enablePushState' => false]); ?>
<div class="search-filter">
    <?= $this->render('_search', ['model' => $searchModel]); ?>
</div>
<div class="row">
    <div class="col-xl-9 col-md-7 col-xs-12">
        <div class="profile-contain">
            <div class="section section-data-tables">
                <div class="row m-0">
                    <div class="col s12">
                        <div class="card table-structure">
                            <div class="card-content p-0">
                                <div class="card-header d-flex align-items-center justify-content-between w-100">
                                    <div class="header-title">
                                        <?= $this->title ?>
                                    </div>
                                    <div class="card-header-btns">
                                        <?= Html::a(ClientHistoryModule::t('clienthistory', 'export'), ['/clienthistory/client-history/export'], [
                                            'id' => 'hov view_link',
                                            'data-pjax' => '0',
                                            'class' => 'exportbutton lead_group btn waves-effect waves-light darken-1 breadcrumbs-btn right',
                                        ]) ?>
                                    </div>
                                </div>
                                <div class="dataTables_wrapper" id="page-length-option_wrapper">

                                    <?php //Pjax::begin(['enablePushState'=>false, 'id'=>'pjax-camp-cdr']); ?>
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
                                                ['class' => 'yii\grid\SerialColumn'],
                                                [
                                                    'attribute' => 'dial_number',
                                                    'headerOptions' => ['class' => 'text-center'],
                                                    'contentOptions' => ['class' => 'text-center'],
                                                    'enableSorting' => TRUE,
                                                    'value' => function ($model) {
                                                        if (!empty($model->dial_number)) {
                                                            return $model->dial_number;
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
                                                    'headerOptions' => ['class' => 'text-center'],
                                                    'contentOptions' => ['class' => 'text-center'],
                                                    'enableSorting' => TRUE,
                                                    'value' => function ($model) {
                                                        if (!empty($model->call_disposion_decription)) {
                                                            return $model->call_disposion_decription;
                                                        } else {
                                                            return '-';
                                                        }
                                                    }
                                                ],*/
                                                [
                                                    'attribute' => 'agent_first_name',
                                                    'headerOptions' => ['class' => 'text-center'],
                                                    'contentOptions' => ['class' => 'text-center'],
                                                    'enableSorting' => TRUE,
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
                                                    'headerOptions' => ['class' => 'text-center'],
                                                    'contentOptions' => ['class' => 'text-center'],
                                                    'enableSorting' => TRUE,
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
                                                    'headerOptions' => ['class' => 'text-center'],
                                                    'contentOptions' => ['class' => 'text-center'],
                                                    'enableSorting' => TRUE,
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
                                                                    return $lead->lg_first_name;
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
                                                    'attribute' => 'customer_last_name',
                                                    'headerOptions' => ['class' => 'text-center'],
                                                    'contentOptions' => ['class' => 'text-center'],
                                                    'enableSorting' => TRUE,
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
                                                                    return $lead->lg_last_name;
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
                                                /* [
                                                    'attribute' => 'call_disposion_start_time',
                                                    'headerOptions' => ['class' => 'text-center'],
                                                    'contentOptions' => ['class' => 'text-center'],
                                                    'enableSorting' => TRUE,
                                                    'value' => function ($model) {
                                                        if (!empty($model->call_disposion_start_time)) {
                                                            return CommonHelper::tsToDt($model->call_disposion_start_time);
                                                        } else {
                                                            return '-';
                                                        }
                                                    }
                                                ],*/
                                                [
                                                    'attribute' => 'start_time',
                                                    'headerOptions' => ['class' => 'text-center'],
                                                    'contentOptions' => ['class' => 'text-center'],
                                                    'enableSorting' => TRUE,
                                                    'value' => function ($model) {
                                                        if (!empty($model->start_time)) {
                                                            return CommonHelper::tsToDt($model->start_time);
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

                                            ],
                                            'tableOptions' => [
                                                'class' => 'display dataTable dtr-inline providercount',
                                                'data-count' => $dataProvider->getTotalCount(),
                                            ],
                                        ]);
                                    } catch (Exception $e) {
                                        //var_dump($e);
                                    }
                                    ?>
                                    <?php //Pjax::end(); ?>
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
        return checkCount(count, '" . ClientHistoryModule::t('clienthistory', 'no_records_found_to_export') . "');
    });");
?>

