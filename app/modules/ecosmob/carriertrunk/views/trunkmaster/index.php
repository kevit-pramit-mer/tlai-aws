<?php

use app\modules\ecosmob\carriertrunk\CarriertrunkModule;
use app\modules\ecosmob\carriertrunk\models\TrunkMaster;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\ecosmob\carriertrunk\models\TrunkMasterSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = CarriertrunkModule::t('carriertrunk', 'trunks');
$this->params['breadcrumbs'][] = $this->title;
$this->params['pageHead'] = $this->title;

$permissions = $GLOBALS['permissions'];

TrunkMaster::updateTrunkLiveStatus();
?>
<div class="row">
    <?php Pjax::begin(['id' => 'trunk-index', 'timeout' => 0, 'enablePushState' => false]); ?>
    <div class="col-xl-9 col-md-7 col-xs-12">
        <div class="row">
            <div class="col s12">
                <div class="profile-contain">
                    <div class="section section-data-tables">
                        <div class="row">
                            <div class="col s12 search-filter">
                                <?= $this->render('search/_search', ['model' => $searchModel]); ?>
                            </div>
                            <div class="col s12">
                                <div class="card table-structure">
                                    <div class="card-content">
                                        <div class="card-header d-flex align-items-center justify-content-between w-100">
                                            <div class="header-title">
                                                <?= $this->title ?>
                                            </div>
                                            <div class="card-header-btns">
                                                <?php if (in_array('/carriertrunk/trunkmaster/create', $permissions)) { ?>
                                                    <?= Html::a(CarriertrunkModule::t('carriertrunk', 'add_new'),
                                                        ['create'],
                                                        [
                                                            'id' => 'hov',
                                                            'data-pjax' => 0,
                                                            'class' => 'btn waves-effect waves-light darken-1 breadcrumbs-btn right',
                                                        ]) ?>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <div class="dataTables_wrapper" id="page-length-option_wrapper">

                                            <?php try {
                                                echo GridView::widget(
                                                    [
                                                        'dataProvider' => $dataProvider,
                                                        'layout' => Yii::$app->layoutHelper->get_layout_str(
                                                            '#trunk-search-form'
                                                        ),
                                                        'pager' => [
                                                            'prevPageLabel' => '<a class="paginate_button previous disabled" aria-controls="data-table-row-grouping" data-dt-idx="0" tabindex="0" id="data-table-row-grouping_previous">' . Yii::t('app', 'previous') . '</a>',
                                                            'nextPageLabel' => '<a class="paginate_button next" aria-controls="data-table-row-grouping" data-dt-idx="4" tabindex="0" id="data-table-row-grouping_next">' . Yii::t('app', 'next') . '</a>',
                                                            'maxButtonCount' => 5,
                                                        ],
                                                        'options' => [
                                                            'tag' => FALSE,
                                                        ],
                                                        'showOnEmpty' => TRUE,
                                                        'columns' => [
                                                            [
                                                                'class' => 'yii\grid\ActionColumn',
                                                                'template' => '{update}{delete}',
                                                                'header' => Yii::t(
                                                                    'app',
                                                                    'action'
                                                                ),
                                                                'contentOptions' => ['class' => 'center width-10'],
                                                                'headerOptions' => ['class' => 'center width-10'],
                                                                'buttons' => [
                                                                    'update' => function ($url) use ($permissions) {
                                                                        if (in_array('/carriertrunk/trunkmaster/update', $permissions)) {
                                                                            return (1 ? Html::a(
                                                                                '<i class="material-icons">edit</i>',
                                                                                $url,
                                                                                [
                                                                                    'title' => Yii::t('app', 'update'),
                                                                                ]
                                                                            ) : '');
                                                                        }else{
                                                                            return '';
                                                                        }
                                                                    },
                                                                    'delete' => function (
                                                                        $url,
                                                                        $searchModel
                                                                    ) use ($permissions) {
                                                                        if (in_array('/carriertrunk/trunkmaster/delete', $permissions)) {
                                                                            /** @var TrunkMaster $searchModel */
                                                                            $candelete = $searchModel->canDelete($searchModel->trunk_id);
                                                                            if ($candelete) {
                                                                                $can_not_delete_message = CarriertrunkModule::t('carriertrunk', 'can_not_delete');
                                                                                return '<a disabled class="ml-5 opacity5" title="' . $can_not_delete_message . '"><i class="material-icons">delete</i></a>';
                                                                            } else {
                                                                                return (1
                                                                                    ? Html::a(
                                                                                        '<i class="material-icons">delete</i>',
                                                                                        $url,
                                                                                        [
                                                                                            'class' => 'ml-5',
                                                                                            'data-pjax' => 0,
                                                                                            'title' => Yii::t('app', 'delete'),
                                                                                            'data-confirm' => CarriertrunkModule::t(
                                                                                                'carriertrunk',
                                                                                                'delete_confirm_trunk'
                                                                                            ),
                                                                                            'data-method' => 'post',
                                                                                        ]
                                                                                    )
                                                                                    : Html::a(
                                                                                        '<i class="material-icons">delete</i>',
                                                                                        $url,
                                                                                        [
                                                                                            'data-pjax' => 1,
                                                                                            'disabled' => TRUE,
                                                                                            'class' => 'ml-5',
                                                                                            'title' => CarriertrunkModule::t('carriertrunk', 'cannot_delete_assign_togrp'),
                                                                                        ]
                                                                                    ));
                                                                            }
                                                                        }else{
                                                                            return '';
                                                                        }
                                                                    },
                                                                ],
                                                            ],
                                                            [
                                                                'attribute' => 'trunk_status',
                                                                'format' => 'raw',
                                                                'headerOptions' => ['class' => 'center'],
                                                                'contentOptions' => ['class' => 'center'],
                                                                'enableSorting' => True,
                                                                'value' => function ($model
                                                                ) {
                                                                    return $model->trunk_status
                                                                    == 'Y'
                                                                        ? '<span class="new badge gradient-45deg-cyan-light-green" data-badge-caption="">' . CarriertrunkModule::t('carriertrunk', 'active') . '</span>'
                                                                        :
                                                                        '<span class="new badge gradient-45deg-red-pink" data-badge-caption="">' . CarriertrunkModule::t('carriertrunk', 'inactive') . '</span>';
                                                                },
                                                            ],
                                                            [
                                                                'attribute' => 'trunk_name',
                                                                'headerOptions' => ['class' => 'center'],
                                                                'contentOptions' => ['class' => 'center'],
                                                                'enableSorting' => True,
                                                            ],
                                                            [
                                                                'attribute' => 'trunk_ip',
                                                                'headerOptions' => ['class' => 'center'],
                                                                'contentOptions' => ['class' => 'center'],
                                                                'enableSorting' => True,
                                                            ],
                                                            [
                                                                'attribute' => 'trunk_ip_type',
                                                                'format' => 'raw',
                                                                'headerOptions' => ['class' => 'center'],
                                                                'contentOptions' => ['class' => 'center'],
                                                                'value' => function ($model
                                                                ) {
                                                                    return $model->trunk_ip_type
                                                                    == 'PRIVATE'
                                                                        ? '<label class="tag tag-pill tag-info tag-lg">'
                                                                        . CarriertrunkModule::t(
                                                                            'carriertrunk',
                                                                            "private"
                                                                        ) . '</label>'
                                                                        : '<label class="tag tag-pill tag-success tag-lg">'
                                                                        . CarriertrunkModule::t(
                                                                            'carriertrunk',
                                                                            "public"
                                                                        ) . '</label>';
                                                                },
                                                                'enableSorting' => True,
                                                            ],
                                                            [
                                                                'attribute' => 'trunk_register',
                                                                'format' => 'raw',
                                                                'headerOptions' => ['class' => 'center'],
                                                                'contentOptions' => ['class' => 'center'],
                                                                'value' => function ($model
                                                                ) {
                                                                    return $model->trunk_register
                                                                    == '1'
                                                                        ? '<span class="tag square-tag tag-primary tag-sm"><i class="material-icons">check</i></span>'
                                                                        : '<span class="tag square-tag tag-danger tag-sm"><i class="material-icons">clear</i></span>';
                                                                },
                                                                'enableSorting' => True,
                                                            ],

                                                            [
                                                                'attribute' => 'trunk_username',
                                                                'format' => 'raw',
                                                                'headerOptions' => ['class' => 'center'],
                                                                'contentOptions' => ['class' => 'center'],
                                                                'value' => function ($model
                                                                ) {
                                                                    return $model->trunk_register
                                                                    == '1'
                                                                        ? (!empty($model->trunk_username)
                                                                            ? $model->trunk_username
                                                                            : '-') : '-';
                                                                },
                                                                'enableSorting' => True,
                                                            ],
                                                            [
                                                                'attribute' => 'trunk_live_status',
                                                                'format' => 'raw',
                                                                'headerOptions' => ['class' => 'center'],
                                                                'contentOptions' => ['class' => 'center'],
                                                                'value' => function ($model
                                                                ) {
                                                                    return $model->trunk_live_status
                                                                    == '1'
                                                                        ? '<span class="new badge light-green-bg" data-badge-caption="">' . CarriertrunkModule::t('carriertrunk', 'UP') . '</span>'
                                                                        :
                                                                        '<span class="new badge light-red-bg" data-badge-caption="">' . CarriertrunkModule::t('carriertrunk', 'DOWN') . '</span>';
                                                                },
                                                                'enableSorting' => True,
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
    <?php Pjax::end(); ?>
</div>
