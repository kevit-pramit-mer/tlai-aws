<?php

use app\components\CommonHelper;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\ecosmob\supervisorqueuecallback\models\QueueCallbackSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'queuecallback');
$this->params['breadcrumbs'][] = $this->title;
$this->params['pageHead'] = $this->title;
?>
<?php Pjax::begin(['id' => 'supervisor-queue-call-index', 'timeout' => 0, 'enablePushState' => false]); ?>

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
                                        <div class="card-header d-flex align-items-center justify-content-between w-100">
                                            <div class="header-title">
                                                    <?= $this->title ?>
                                                </div>
                                                <div class="card-header-btns">
                                                    <?= Html::a(Yii::t('app', 'export'), ['/supervisorqueuecallback/queue-callback/export'], [
                                                        'id' => 'hov view_link',
                                                        'data-pjax' => '0',
                                                        'class' => 'exportbutton lead_group btn waves-effect waves-light darken-1 breadcrumbs-btn right',
                                                    ]) ?>
                                                </div>
                                        </div>
                                        <div class="dataTables_wrapper" id="page-length-option_wrapper">

                                            <?php //Pjax::begin(['enablePushState' => false, 'id' => 'pjax-queue-callback']); ?>
                                            <?php try {
                                                echo GridView::widget([
                                                    'id' => 'queue-callback-grid-index', // TODO : Add Grid Widget ID
                                                    'dataProvider' => $dataProvider,
                                                    'layout' => Yii::$app->layoutHelper->get_layout_str('#queue-callback-search-form'),
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
                                                            'class' => 'yii\grid\SerialColumn',
                                                            //'class' => 'yii\grid\ActionColumn',
                                                            //'template' => '{update}{delete}',
                                                            //'header' => Yii::t('app', 'action'),
                                                            'headerOptions' => ['class' => 'center width-10'],
                                                            'contentOptions' => ['class' => 'center width-10'],
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
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'enableSorting' => True,
                                                        ],
                                                        [
                                                            'attribute' => 'phone_number',
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'enableSorting' => True,
                                                        ],
                                                        [
                                                            'attribute' => 'created_at',
                                                            'label' => Yii::t('app', 'date'),
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'enableSorting' => True,
                                                            'value' => function ($model) {
                                                                return CommonHelper::tsToDt($model->created_at);
                                                            }
                                                        ],
                                                    ],
                                                    'tableOptions' => [
                                                        'class' => 'display dataTable dtr-inline'
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
        return checkCount($dataProvider->count, '" . Yii::t('app', 'no_records_found_to_export') . "');
    });");
?>

<script>
    $(document).ready(function () {
        $('.main-queue').addClass("active");
        $('.queue-child').removeClass("active");
        //$('.main-cdr').removeClass("active");
    });
</script>



