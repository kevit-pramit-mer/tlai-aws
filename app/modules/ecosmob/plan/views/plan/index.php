<?php

use app\modules\ecosmob\plan\PlanModule;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\ecosmob\plan\models\PlanSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = PlanModule::t('pl', 'plan');
$this->params['breadcrumbs'][] = $this->title;
$this->params['pageHead'] = $this->title;

$permissions = $GLOBALS['permissions'];
if (in_array('/plan/plan/create', $permissions)) { ?>
    <div class="col s12 m7 pt-1 pb-1 pr-0 mob-m">
        <!--<a class="mb-6 btn waves-effect waves-light green darken-1 breadcrumbs-btn right">Add New</a>-->
        <?= Html::a(PlanModule::t('pl',
            'add_new'), ['create'], [
            'id' => 'hov',
            'data-pjax' => 0,
            'class' => 'btn waves-effect waves-light darken-1 breadcrumbs-btn right',
        ]) ?>
    </div>
<?php } ?>
<?php Pjax::begin(['id' => 'plan-index', 'timeout' => 0, 'enablePushState' => false]); ?>
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
                                    <div class="card">
                                        <div class="card-content">

                                            <div class="dataTables_wrapper" id="page-length-option_wrapper">

                                                <?php try {
                                                    echo GridView::widget([
                                                        'id' => 'plan-grid-index',
                                                        'dataProvider' => $dataProvider,
                                                        'layout' => Yii::$app->layoutHelper->get_layout_str('#plan-search-form'),
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
                                                                'header' => Yii::t('app', 'action'),
                                                                'headerOptions' => ['class' => 'center'],
                                                                'contentOptions' => ['class' => 'center'],
                                                                'buttons' => [
                                                                    'update' => function ($url, $model) use ($permissions) {
                                                                        if (in_array('/plan/plan/update', $permissions)) {
                                                                            return (1 ? Html::a('<i class="material-icons color-orange">edit</i>',
                                                                                $url,
                                                                                [
                                                                                    'title' => Yii::t('app', 'update'),
                                                                                ]) : '');
                                                                        }
                                                                    },
                                                                    'delete' => function ($url, $model) use ($permissions) {
                                                                        if (in_array('/plan/plan/delete', $permissions)) {
                                                                            $candelete = $model->canDelete($model->pl_id);
                                                                            if ($candelete) {
                                                                                $canNotDeleteMessage = PlanModule::t('pl', 'can_not_delete');
                                                                                return '<a disabled name="login-button" class="ml-5 opacity5" title="' . $canNotDeleteMessage . '"><i class="material-icons">delete</i></a>';
                                                                            } else {
                                                                                return Html::a('<i class="material-icons">delete</i>',
                                                                                    $url,
                                                                                    [

                                                                                        'class' => 'ml-5',
                                                                                        'data-pjax' => 0,
                                                                                        'style' => 'color:#FF4B56',
                                                                                        'data-confirm' => Yii::t('app', 'delete_confirm'),
                                                                                        'data-method' => 'post',
                                                                                        'title' => Yii::t('app', 'delete'),
                                                                                    ]);
                                                                            }
                                                                        }
                                                                    },

                                                                ],
                                                            ],

                                                            [
                                                                'attribute' => 'pl_name',
                                                                'headerOptions' => ['class' => 'text-center'],
                                                                'contentOptions' => ['class' => 'text-center'],
                                                                'enableSorting'=> TRUE,
                                                            ],
                                                            [
                                                                'attribute' => 'pl_holiday',
                                                                'headerOptions' => ['class' => 'text-center'],
                                                                'contentOptions' => ['class' => 'text-center'],
                                                                'enableSorting' => True,
                                                                'value' => function ($model) {
                                                                    return ($model->pl_holiday == '0') ? 'NO' : 'YES';
                                                                },
                                                            ],
                                                            [
                                                                'attribute' => 'pl_week_off',
                                                                'headerOptions' => ['class' => 'text-center'],
                                                                'contentOptions' => ['class' => 'text-center'],
                                                                'enableSorting' => True,
                                                                'value' => function ($model) {
                                                                    return ($model->pl_week_off == '0') ? 'NO' : 'YES';
                                                                },
                                                            ],
                                                            [
                                                                'attribute' => 'pl_bargain',
                                                                'headerOptions' => ['class' => 'text-center'],
                                                                'contentOptions' => ['class' => 'text-center'],
                                                                'enableSorting' => True,
                                                                'value' => function ($model) {
                                                                    return ($model->pl_bargain == '0') ? 'NO' : 'YES';
                                                                },
                                                            ],
                                                            [
                                                                'attribute' => 'pl_dnd',
                                                                'headerOptions' => ['class' => 'text-center'],
                                                                'contentOptions' => ['class' => 'text-center'],
                                                                'enableSorting' => True,
                                                                'value' => function ($model) {
                                                                    return ($model->pl_dnd == '0') ? 'NO' : 'YES';
                                                                },
                                                            ],
                                                            [
                                                                'attribute' => 'pl_park',
                                                                'headerOptions' => ['class' => 'text-center'],
                                                                'contentOptions' => ['class' => 'text-center'],
                                                                'enableSorting' => True,
                                                                'value' => function ($model) {
                                                                    return ($model->pl_park == '0') ? 'NO' : 'YES';
                                                                },
                                                            ],
                                                            [
                                                                'attribute' => 'pl_transfer',
                                                                'headerOptions' => ['class' => 'text-center'],
                                                                'contentOptions' => ['class' => 'text-center'],
                                                                'enableSorting' => True,
                                                                'value' => function ($model) {
                                                                    return ($model->pl_transfer == '0') ? 'NO' : 'YES';
                                                                },
                                                            ],
                                                            [
                                                                'attribute' => 'pl_call_record',
                                                                'headerOptions' => ['class' => 'text-center'],
                                                                'contentOptions' => ['class' => 'text-center'],
                                                                'enableSorting' => True,
                                                                'value' => function ($model) {
                                                                    return ($model->pl_call_record == '0') ? 'NO' : 'YES';
                                                                },
                                                            ],
                                                            [
                                                                'attribute' => 'pl_white_list',
                                                                'headerOptions' => ['class' => 'text-center'],
                                                                'contentOptions' => ['class' => 'text-center'],
                                                                'enableSorting' => True,
                                                                'value' => function ($model) {
                                                                    return ($model->pl_white_list == '0') ? 'NO' : 'YES';
                                                                },
                                                            ],
                                                            [
                                                                'attribute' => 'pl_black_list',
                                                                'headerOptions' => ['class' => 'text-center'],
                                                                'contentOptions' => ['class' => 'text-center'],
                                                                'enableSorting' => True,
                                                                'value' => function ($model) {
                                                                    return ($model->pl_black_list == '0') ? 'NO' : 'YES';
                                                                },
                                                            ],
                                                            [
                                                                'attribute' => 'pl_caller_id_block',
                                                                'headerOptions' => ['class' => 'text-center'],
                                                                'contentOptions' => ['class' => 'text-center'],
                                                                'enableSorting' => True,
                                                                'value' => function ($model) {
                                                                    return ($model->pl_caller_id_block == '0') ? 'NO' : 'YES';
                                                                },
                                                            ],
                                                            [
                                                                'attribute' => 'pl_universal_forward',
                                                                'headerOptions' => ['class' => 'text-center'],
                                                                'contentOptions' => ['class' => 'text-center'],
                                                                'enableSorting' => True,
                                                                'value' => function ($model) {
                                                                    return ($model->pl_universal_forward == '0') ? 'NO' : 'YES';
                                                                },
                                                            ],
                                                            [
                                                                'attribute' => 'pl_no_ans_forward',
                                                                'headerOptions' => ['class' => 'text-center'],
                                                                'contentOptions' => ['class' => 'text-center'],
                                                                'enableSorting' => True,
                                                                'value' => function ($model) {
                                                                    return ($model->pl_no_ans_forward == '0') ? 'NO' : 'YES';
                                                                },
                                                            ],
                                                            [
                                                                'attribute' => 'pl_busy_forward',
                                                                'headerOptions' => ['class' => 'text-center'],
                                                                'contentOptions' => ['class' => 'text-center'],
                                                                'enableSorting' => True,
                                                                'value' => function ($model) {
                                                                    return ($model->pl_busy_forward == '0') ? 'NO' : 'YES';
                                                                },
                                                            ],
                                                            [
                                                                'attribute' => 'pl_timebase_forward',
                                                                'headerOptions' => ['class' => 'text-center'],
                                                                'contentOptions' => ['class' => 'text-center'],
                                                                'enableSorting' => True,
                                                                'value' => function ($model) {
                                                                    return ($model->pl_timebase_forward == '0') ? 'NO' : 'YES';
                                                                },
                                                            ],
                                                            [
                                                                'attribute' => 'pl_selective_forward',
                                                                'headerOptions' => ['class' => 'text-center'],
                                                                'contentOptions' => ['class' => 'text-center'],
                                                                'enableSorting' => True,
                                                                'value' => function ($model) {
                                                                    return ($model->pl_selective_forward == '0') ? 'NO' : 'YES';
                                                                },
                                                            ],
                                                            [
                                                                'attribute' => 'pl_shift_forward',
                                                                'headerOptions' => ['class' => 'text-center'],
                                                                'contentOptions' => ['class' => 'text-center'],
                                                                'enableSorting' => True,
                                                                'value' => function ($model) {
                                                                    return ($model->pl_shift_forward == '0') ? 'NO' : 'YES';
                                                                },
                                                            ],
                                                            [
                                                                'attribute' => 'pl_unavailable_forward',
                                                                'headerOptions' => ['class' => 'text-center'],
                                                                'contentOptions' => ['class' => 'text-center'],
                                                                'enableSorting' => True,
                                                                'value' => function ($model) {
                                                                    return ($model->pl_unavailable_forward == '0') ? 'NO' : 'YES';
                                                                },
                                                            ],
                                                            [
                                                                'attribute' => 'pl_redial',
                                                                'headerOptions' => ['class' => 'text-center'],
                                                                'contentOptions' => ['class' => 'text-center'],
                                                                'enableSorting' => True,
                                                                'value' => function ($model) {
                                                                    return ($model->pl_redial == '0') ? 'NO' : 'YES';
                                                                },
                                                            ],
                                                            [
                                                                'attribute' => 'pl_call_return',
                                                                'headerOptions' => ['class' => 'text-center'],
                                                                'contentOptions' => ['class' => 'text-center'],
                                                                'enableSorting' => True,
                                                                'value' => function ($model) {
                                                                    return ($model->pl_call_return == '0') ? 'NO' : 'YES';
                                                                },
                                                            ],
                                                            [
                                                                'attribute' => 'pl_busy_callback',
                                                                'headerOptions' => ['class' => 'text-center'],
                                                                'contentOptions' => ['class' => 'text-center'],
                                                                'enableSorting' => True,
                                                                'value' => function ($model) {
                                                                    return ($model->pl_busy_callback == '0') ? 'NO' : 'YES';
                                                                },
                                                            ],
                                                        ],
                                                        'tableOptions' => [
                                                            'class' => 'display dataTable dtr-inline',
                                                            
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
