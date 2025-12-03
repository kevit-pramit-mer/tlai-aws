<?php

use app\modules\ecosmob\carriertrunk\models\TrunkGroup;
use app\modules\ecosmob\dialplan\DialPlanModule;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\ecosmob\dialplan\models\OutboundDialPlansDetailsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $dataProviderDefault */

$this->title = DialPlanModule::t('dp', 'outbound_dialplans');
$this->params['breadcrumbs'][] = $this->title;
$this->params['pageHead'] = $this->title;
$permissions = $GLOBALS['permissions'];
?>
<?php Pjax::begin(['id' => 'playback-index', 'timeout' => 0, 'enablePushState' => false]); ?>
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
                            <div class="col s12 search-filter">
                                <div class="card table-structure">
                                    <div class="card-header d-flex align-items-center justify-content-between w-100">
                                        <div class="header-title">
                                            <?= DialPlanModule::t('dp', 'default_label') ?>
                                        </div>
                                    </div>
                                    <div class="card-content default-card">
                                        <div class="dataTables_wrapper" id="page-length-option_wrapper2">
                                            <?php try {
                                                echo GridView::widget([
                                                    'id' => 'outbound-dial-plans-details-grid-index-default',
                                                    'dataProvider' => $dataProviderDefault,
                                                    'layout' => Yii::$app->layoutHelper->get_layout_without_pager(),
                                                    'showOnEmpty' => TRUE,
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
                                                            'template' => '{update}',
                                                            'header' => Yii::t('app',
                                                                'action'),
                                                            'headerOptions' => ['class' => 'center width-10'],
                                                            'contentOptions' => ['class' => 'center width-10'],
                                                            'buttons' => [
                                                                'update' => function (
                                                                    $url
                                                                ) use ($permissions) {
                                                                    if (in_array('/dialplan/outbounddialplan/update', $permissions)) {
                                                                        return (1
                                                                            ? Html::a('<i class="material-icons">edit</i>',
                                                                                $url,
                                                                                [
                                                                                    'style' => '',
                                                                                    'title' => Yii::t('app',
                                                                                        'update'),
                                                                                ]) : '');
                                                                    }else{
                                                                        return '';
                                                                    }
                                                                },
                                                            ],
                                                        ],

                                                        [
                                                            'attribute' => 'odpd_prefix_match_string',
                                                            'headerOptions' => ['class' => 'center'],
                                                            'contentOptions' => ['class' => 'center'],
                                                            'enableSorting' => FALSE,
                                                        ],
                                                        [
                                                            'attribute' => 'trunk_grp_id',
                                                            'headerOptions' => ['class' => 'center'],
                                                            'contentOptions' => ['class' => 'center'],
                                                            'enableSorting' => FALSE,
                                                            'value' => function ($model) {
                                                                $trunkGroup = TrunkGroup::findOne($model->trunk_grp_id);

                                                                return !empty($trunkGroup->trunk_grp_name) ? $trunkGroup->trunk_grp_name : '-';
                                                            }
                                                        ],
                                                        [
                                                            'attribute' => 'odpd_strip_prefix',
                                                            'headerOptions' => ['class' => 'center'],
                                                            'contentOptions' => ['class' => 'center'],
                                                            'enableSorting' => FALSE,
                                                            'value' => function ($model) {
                                                                return empty($model->odpd_strip_prefix) ? '-' : $model->odpd_strip_prefix;
                                                            }
                                                        ],
                                                        [
                                                            'attribute' => 'odpd_add_prefix',
                                                            'headerOptions' => ['class' => 'center'],
                                                            'contentOptions' => ['class' => 'center'],
                                                            'enableSorting' => FALSE,
                                                            'value' => function ($model) {
                                                                return empty($model->odpd_add_prefix) ? '-' : $model->odpd_add_prefix;
                                                            }
                                                        ],
                                                    ],
                                                    'tableOptions' => [
                                                        'class' => 'display dataTable dtr-inline',
                                                        'id' => 'page-length-option2',
                                                    ],
                                                ]);
                                            } catch (Exception $e) {
                                            } ?>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="col s12">
                                <div class="card table-structure">
                                    <div class="card-content">
                                        <div class="card-header d-flex align-items-center justify-content-between w-100">
                                            <div class="header-title">
                                                <?= $this->title ?>
                                            </div>
                                            <div class="card-header-btns">
                                                <?php if (in_array('/dialplan/outbounddialplan/create', $permissions)) { ?>
                                                    <?= Html::a(DialPlanModule::t('dp', 'add_new'),
                                                        ['create'],
                                                        [
                                                            'data-pjax' => 0,
                                                            'class' => 'btn waves-effect waves-light darken-1 breadcrumbs-btn right',
                                                        ]) ?>
                                                <?php } ?>
                                            </div>
                                        </div>    
                                        <div class="dataTables_wrapper" id="page-length-option_wrapper">
                                            <?php try {
                                                echo GridView::widget([
                                                    'id' => 'outbound-dial-plans-details-grid-index',
                                                    'dataProvider' => $dataProvider,
                                                    'layout' => Yii::$app->layoutHelper->get_layout_str('#outbound-dial-plans-details-search-form'),
                                                    'showOnEmpty' => TRUE,
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
                                                                'update' => function (
                                                                    $url
                                                                ) use ($permissions) {
                                                                    if (in_array('/dialplan/outbounddialplan/update', $permissions)) {
                                                                        return (1
                                                                            ? Html::a('<i class="material-icons">edit</i>',
                                                                                $url,
                                                                                [
                                                                                    'style' => '',
                                                                                    'title' => Yii::t('app',
                                                                                        'update'),
                                                                                ]) : '');
                                                                    }else{
                                                                        return '';
                                                                    }
                                                                },
                                                                'delete' => function (
                                                                    $url
                                                                ) use ($permissions) {
                                                                    if (in_array('/dialplan/outbounddialplan/delete', $permissions)) {
                                                                        return (1
                                                                            ? Html::a('<i class="material-icons">delete</i>',
                                                                                $url,
                                                                                [

                                                                                    'class' => 'ml-5',
                                                                                    'data-pjax' => 0,
                                                                                    'style' => 'color:#FF4B56',
                                                                                    'data-confirm' => Yii::t('app',
                                                                                        'delete_confirm'),
                                                                                    'data-method' => 'post',
                                                                                    'title' => Yii::t('app',
                                                                                        'delete'),
                                                                                ]) : '');
                                                                    }else{
                                                                        return '';
                                                                    }
                                                                },
                                                            ],
                                                        ],

                                                        [
                                                            'attribute' => 'odpd_prefix_match_string',
                                                            'headerOptions' => ['class' => 'center width-20'],
                                                            'contentOptions' => ['class' => 'center width-20'],
                                                            'enableSorting' => TRUE,
                                                        ],
                                                        [
                                                            'attribute' => 'trunk_grp_id',
                                                            'headerOptions' => ['class' => 'center width-20'],
                                                            'contentOptions' => ['class' => 'center width-20'],
                                                            'enableSorting' => True,
                                                            'value' => function ($model) {
                                                                $trunkGroup = TrunkGroup::findOne($model->trunk_grp_id);

                                                                return !empty($trunkGroup->trunk_grp_name) ? $trunkGroup->trunk_grp_name : '-';
                                                            }
                                                        ],
                                                        [
                                                            'attribute' => 'odpd_strip_prefix',
                                                            'headerOptions' => ['class' => 'center width-20'],
                                                            'contentOptions' => ['class' => 'center width-20'],
                                                            'enableSorting' => True,
                                                            'value' => function ($model) {
                                                                return empty($model->odpd_strip_prefix) ? '-' : $model->odpd_strip_prefix;
                                                            }
                                                        ],
                                                        [
                                                            'attribute' => 'odpd_add_prefix',
                                                            'headerOptions' => ['class' => 'center width-20'],
                                                            'contentOptions' => ['class' => 'center width-20'],
                                                            'enableSorting' => True,
                                                            'value' => function ($model) {
                                                                return empty($model->odpd_add_prefix) ? '-' : $model->odpd_add_prefix;
                                                            }
                                                        ],
                                                    ],
                                                    'tableOptions' => [
                                                        'class' => 'display dataTable dtr-inline'
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