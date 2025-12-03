<?php

use app\modules\ecosmob\parkinglot\models\ParkingLotSearch;
use app\modules\ecosmob\parkinglot\ParkingLotModule;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;
use app\modules\ecosmob\parkinglot\models\ParkingLot;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel ParkingLotSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = ParkingLotModule::t('parkinglot', 'parking_lot');
$this->params['breadcrumbs'][] = $this->title;
$this->params['pageHead'] = $this->title;
$permissions = $GLOBALS['permissions'];
?>
<?php Pjax::begin(['id' => 'parking-lot-index', 'timeout' => 0, 'enablePushState' => false]); ?>
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
                                                        <?php if (in_array('/parkinglot/parking-lot/create', $permissions)) { ?>

                                                        <?= Html::a(ParkingLotModule::t('parkinglot', 'add_new'),
                                                            ['create'],
                                                            [
                                                                'id' => 'hov',
                                                                'data-pjax' => 0,
                                                                'class' => 'btn waves-effect waves-light darken-1 breadcrumbs-btn right',
                                                            ]) ?>
                                                        <?php } ?>
                                                        <?php if (in_array('/parkinglot/parking-lot/index', $permissions)) { ?>
                                                       <!-- --><?php /*= Html::a(ParkingLotModule::t('parkinglot', 'export'), ['/parkinglot/parking-lot/export'], [
                                                            'id' => 'hov view_link',
                                                            'data-pjax' => '0',
                                                            'class' => 'exportbutton lead_group btn waves-effect waves-light darken-1 breadcrumbs-btn right',
                                                        ]) */?>
                                                            <button id="export-button" class="exportbutton lead_group btn waves-effect waves-light darken-1 breadcrumbs-btn right">
                                                                <?= ParkingLotModule::t('parkinglot', 'Export') ?>
                                                            </button>

                                                        <?php } ?>
                                                    </div>
                                                </div>
                                                <?php try {
                                                    echo GridView::widget([
                                                        'id' => 'parking-lot-grid-index',
                                                        // TODO : Add Grid Widget ID
                                                        'dataProvider' => $dataProvider,
                                                        'layout' => Yii::$app->layoutHelper->get_layout_str('#parking-lot-search-form'),
                                                        'showOnEmpty' => true,
                                                        'pager' => [
                                                            'prevPageLabel' => '<a class="paginate_button previous disabled" aria-controls="data-table-row-grouping" data-dt-idx="0" tabindex="0" id="data-table-row-grouping_previous">' . Yii::t('app', 'previous') . '</a>',
                                                            'nextPageLabel' => '<a class="paginate_button next" aria-controls="data-table-row-grouping" data-dt-idx="4" tabindex="0" id="data-table-row-grouping_next">' . Yii::t('app', 'next') . '</a>',
                                                            'maxButtonCount' => 5,
                                                        ],
                                                        'options' => [
                                                            'tag' => FALSE,
                                                        ],
                                                        'columns' => [
                                                            [
                                                                'class' => 'yii\grid\ActionColumn',
                                                                'template' => '{update}{delete}',
                                                                'header' => Yii::t('app',
                                                                    'action'),
                                                                'headerOptions' => ['class' => 'center width-10'],
                                                                'contentOptions' => ['class' => 'center width-10'],
                                                                'buttons' => [
                                                                    'update' => function ($url) use ($permissions) {
                                                                        if (in_array('/parkinglot/parking-lot/update', $permissions)) {
                                                                            return (1 ? Html::a('<i class="material-icons">edit</i>',
                                                                                $url, [
                                                                                    'style' => '',
                                                                                    'title' => Yii::t('app',
                                                                                        'update'),
                                                                                    'data-action' => 'edit',
                                                                                ]) : '');
                                                                        } else {
                                                                            return '';
                                                                        }
                                                                    },
                                                                    'delete' => function ($url) use ($permissions) {
                                                                        if (in_array('/parkinglot/parking-lot/delete', $permissions)) {
                                                                            return Html::a('<i class="material-icons">delete</i>',
                                                                                $url, [
                                                                                    'class' => 'ml-5',
                                                                                    'data-pjax' => 0,
                                                                                    'style' => 'color:#FF4B56',
                                                                                    'data-confirm' => ParkingLotModule::t('parkinglot',
                                                                                        'delete_confirm'),
                                                                                    'data-method' => 'post',
                                                                                    'title' => ParkingLotModule::t('parkinglot',
                                                                                        'delete'),
                                                                                    'data-action' => 'delete',
                                                                                ]);
                                                                        }
                                                                    },
                                                                ],
                                                            ],
                                                            [
                                                                'attribute' => 'name',
                                                                'headerOptions' => ['class' => 'center'],
                                                                'contentOptions' => ['class' => 'center'],
                                                                'enableSorting' => True,
                                                            ],
                                                            [
                                                                'attribute' => 'park_ext',
                                                                'headerOptions' => ['class' => 'center'],
                                                                'contentOptions' => ['class' => 'center'],
                                                                'enableSorting' => True,
                                                            ],
                                                            [
                                                                'attribute' => 'park_pos_start',
                                                                'headerOptions' => ['class' => 'center'],
                                                                'contentOptions' => ['class' => 'center'],
                                                                'enableSorting' => True,
                                                                'value' => function ($model) {
                                                                    return $model->park_pos_start . ' - ' . $model->park_pos_end;
                                                                }
                                                            ],
                                                            [
                                                                'attribute' => 'return_to_origin',
                                                                'headerOptions' => ['class' => 'center'],
                                                                'contentOptions' => ['class' => 'center'],
                                                                'format' => 'raw',
                                                                'enableSorting' => True,
                                                                'value' => function ($model) {
                                                                    if ($model->return_to_origin == 1) {
                                                                        return '<span class="new badge gradient-45deg-cyan-light-green"
                                                                            data-badge-caption="">' . ParkingLotModule::t('parkinglot', 'enabled')
                                                                            . '</span>';
                                                                    } else {
                                                                        return '<span class="new badge gradient-45deg-red-pink"
                                                                            data-badge-caption="">' . ParkingLotModule::t('parkinglot', 'disabled') . '</span>';
                                                                    }
                                                                }
                                                            ],
                                                            [
                                                                'attribute' => 'destination_id',
                                                                'headerOptions' => ['class' => 'center'],
                                                                'contentOptions' => ['class' => 'center'],
                                                                'enableSorting' => True,
                                                                'value' => function ($model) {
                                                                    return (!empty($model->destination_type) ? ($model->destination_type == 6 ? ($model->timeoutAction->ser_name.' - '.$model->destination_id) : $model->timeoutAction->ser_name.' - '. ParkingLot::getTimeoutDestination($model->timeoutAction->ser_name, $model->destination_id)) : ' - ');
                                                                }
                                                            ],
                                                            [
                                                                'attribute' => 'status',
                                                                'headerOptions' => ['class' => 'center'],
                                                                'contentOptions' => ['class' => 'center'],
                                                                'format' => 'raw',
                                                                'enableSorting' => True,
                                                                'value' => function ($model) {
                                                                    if ($model->status == 1) {
                                                                        return '<span class="new badge gradient-45deg-cyan-light-green"
                                                                            data-badge-caption="">' . Yii::t('app', 'active')
                                                                            . '</span>';
                                                                    } else {
                                                                        return '<span class="new badge gradient-45deg-red-pink"
                                                                            data-badge-caption="">' . Yii::t('app', 'inactive') . '</span>';
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
<?php Pjax::end();

$this->registerJs("
    $(document).on('click', '.exportbutton', function () {
        var count = ((!$('.providercount').data('count')) ? 0 : $('.providercount').data('count'));
        if (count <= 0) {
            alert('" . Yii::t('app', 'No records found to export') . "');
            return false;
        }else{
            event.preventDefault(); 
            window.location.href = '" . Url::to(['/parkinglot/parking-lot/export']) . "';
        }
    });");

