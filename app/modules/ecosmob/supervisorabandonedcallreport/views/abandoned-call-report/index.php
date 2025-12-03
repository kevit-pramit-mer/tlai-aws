<?php

use app\components\CommonHelper;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\modules\ecosmob\queue\models\QueueMaster;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\ecosmob\supervisorabandonedcallreport\models\QueueAbandonedCallsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'abandonedcallreport');
$this->params['breadcrumbs'][] = $this->title;
$this->params['pageHead'] = $this->title;
?>
<?php Pjax::begin(['id' => 'supervisor-abandoned-call-index', 'timeout' => 0, 'enablePushState' => false]); ?>
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
                                                    <?php /*= Html::a(Yii::t('app', 'export'), ['/supervisorabandonedcallreport/abandoned-call-report/export'], [
                                                        'id' => 'hov view_link',
                                                        'data-pjax' => '0',
                                                        'class' => 'exportbutton lead_group btn waves-effect waves-light darken-1 breadcrumbs-btn right',
                                                    ]) */?>
                                                    <button id="export-button" class="exportbutton lead_group btn waves-effect waves-light darken-1 breadcrumbs-btn right">
                                                        <?= Yii::t('app', 'export') ?>
                                                    </button>
                                                </div>
                                            </div>
                                            <?php //Pjax::begin(['enablePushState' => false, 'id' => 'pjax-queue-abandoned-calls']); ?>
                                            <?php try {
                                                echo GridView::widget([
                                                    'id' => 'queue-abandoned-calls-grid-index', // TODO : Add Grid Widget ID
                                                    'dataProvider' => $dataProvider,
                                                    'layout' => Yii::$app->layoutHelper->get_layout_str('#queue-abandoned-calls-search-form'),
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
                                                            //'class' => 'yii\grid\ActionColumn',
                                                            'class' => 'yii\grid\SerialColumn',
                                                            //'template' => '{update}{delete}',
                                                            //'header' => Yii::t('app', 'action'),
                                                           /* 'headerOptions' => ['class' => 'center width-10'],
                                                            'contentOptions' => ['class' => 'center width-10'],*/
                                                            /*'buttons' => [
                                                                'update' => function ($url, $model) {
                                                                    return (1 ? Html::a('<i class="material-icons">edit</i>', $url, [
                                                                        'style' => '',
                                                                        'title' => Yii::t('app', 'update'),
                                                                    ]) : '');
                                                                },
                                                                'delete' => function ($url, $model) {
                                                                    return (1 ? Html::a('<i class="material-icons">delete</i>', $url, [

                                                                        'class' => 'ml-5',
                                                                        'data-pjax' => 0,
                                                                        'style' => 'color:#FF4B56',
                                                                        'data-confirm' => Yii::t('app', 'delete_confirm'),
                                                                        'data-method' => 'post',
                                                                        'title' => Yii::t('app', 'delete'),
                                                                    ]) : '');
                                                                },
                                                            ]*/
                                                        ],

                                                        [
                                                            'attribute' => 'queue_name',
                                                            'headerOptions'=>['class'=>'text-center', 'style' => 'min-width: 150px !important;max-width: 150px !important; word-wrap:break-word'],
                                                            'contentOptions'=>['class'=>'text-center', 'style' => 'min-width: 150px !important;max-width: 150px !important; word-wrap:break-word'],
                                                            'enableSorting'=> TRUE,
                                                            'value' => function($model){
                                                                return QueueMaster::getQueueName($model->queue_name);
                                                            }
                                                        ],
                                                        [
                                                            'attribute' => 'queue_number',
                                                            'headerOptions'=>['class'=>'text-center', 'style' => 'min-width: 150px !important;max-width: 150px !important; word-wrap:break-word'],
                                                            'contentOptions'=>['class'=>'text-center', 'style' => 'min-width: 150px !important;max-width: 150px !important; word-wrap:break-word'],
                                                            'enableSorting'=> TRUE,
                                                        ],
                                                        [
                                                            'attribute' => 'caller_id_number',
                                                            'headerOptions'=>['class'=>'text-center', 'style' => 'min-width: 150px !important;max-width: 150px !important; word-wrap:break-word'],
                                                            'contentOptions'=>['class'=>'text-center', 'style' => 'min-width: 150px !important;max-width: 150px !important; word-wrap:break-word'],
                                                            'enableSorting'=> TRUE,
                                                        ],
                                                        [
                                                            'attribute' => 'call_status',
                                                            'headerOptions'=>['class'=>'text-center', 'style' => 'min-width: 150px !important;max-width: 150px !important; word-wrap:break-word'],
                                                            'contentOptions'=>['class'=>'text-center', 'style' => 'min-width: 150px !important;max-width: 150px !important; word-wrap:break-word'],
                                                            'enableSorting'=> TRUE,
                                                        ],
                                                        'start_time' =>
                                                            [
                                                                'attribute' => 'start_time',
                                                                'headerOptions'=>['class'=>'text-center', 'style' => 'min-width: 150px !important;max-width: 150px !important; word-wrap:break-word'],
                                                                'contentOptions'=>['class'=>'text-center', 'style' => 'min-width: 150px !important;max-width: 150px !important; word-wrap:break-word'],
                                                                'enableSorting' => True,
                                                                'value' => function ($model) {

                                                                    $utc = $model->start_time;
                                                                    $time = CommonHelper::tsToDt(date("Y-m-d H:i:s", substr($utc, 0, 10)));

                                                                    return ($time == "0") ?
                                                                        "N/A" : $time;
                                                                },
                                                            ],
                                                        'end_time' =>
                                                            [
                                                                'attribute' => 'end_time',
                                                                'headerOptions'=>['class'=>'text-center', 'style' => 'min-width: 150px !important;max-width: 150px !important; word-wrap:break-word'],
                                                                'contentOptions'=>['class'=>'text-center', 'style' => 'min-width: 150px !important;max-width: 150px !important; word-wrap:break-word'],
                                                                'enableSorting' => True,
                                                                'value' => function ($model) {

                                                                    $utc = $model->end_time;
                                                                    $time = CommonHelper::tsToDt(date("Y-m-d H:i:s", substr($utc, 0, 10)));

                                                                    return ($time == "0") ?
                                                                        "N/A" : $time;
                                                                },
                                                            ],
                                                        [
                                                            'attribute' => 'hold_time',
                                                            'headerOptions'=>['class'=>'text-center', 'style' => 'min-width: 150px !important;max-width: 150px !important; word-wrap:break-word'],
                                                            'contentOptions'=>['class'=>'text-center', 'style' => 'min-width: 150px !important;max-width: 150px !important; word-wrap:break-word'],
                                                            'enableSorting'=> TRUE,
                                                        ],

                                                    ],
                                                    'tableOptions' => [
                                                        'class' => 'display dataTable dtr-inline providercount',
                                                        'data-count' => $dataProvider->getTotalCount(),
                                                    ],
                                                ]);
                                            } catch (Exception $e) {
                                            } ?>

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
    </div>
</div>
<?php Pjax::end(); ?>
<?php
$this->registerJs("
    $(document).on('click', '.exportbutton', function () {
        var count = ((!$('.providercount').data('count')) ? 0 : $('.providercount').data('count'));
        if (count <= 0) {
            alert('" . Yii::t('app', 'no_records_found_to_export') . "');
            return false;
        }else{
            event.preventDefault(); 
            window.location.href = '".Url::to(['/supervisorabandonedcallreport/abandoned-call-report/export'])."';
        }
    });");
?>
