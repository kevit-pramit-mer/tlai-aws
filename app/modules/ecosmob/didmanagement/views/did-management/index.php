<?php

use app\modules\ecosmob\didmanagement\DidManagementModule;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;
use app\models\TenantModuleConfig;
use app\modules\ecosmob\didmanagement\models\DidManagement;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\ecosmob\didmanagement\models\DidManagementSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = DidManagementModule::t('did', 'did_mang');
$this->params['breadcrumbs'][] = $this->title;
$this->params['pageHead'] = $this->title;

$permissions = $GLOBALS['permissions']; ?>

<?php Pjax::begin(['id' => 'did-index', 'timeout' => 0, 'enablePushState' => false]); ?>
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
                                                <?php if (in_array('/didmanagement/did-management/create', $permissions)) { ?>
                                                    <?= Html::a(DidManagementModule::t('did', 'add_new'), ['create'], [
                                                        'data-pjax' => 0,
                                                        'class' => 'btn waves-effect waves-light darken-1 breadcrumbs-btn right',
                                                    ]) ?><?php } ?>
                                                <?php if (in_array('/didmanagement/did-management/import', $permissions)) { ?>
                                                    <?= Html::a(DidManagementModule::t('did', 'import'), ['import'], [
                                                        'data-pjax' => 0,
                                                        'class' => 'btn waves-effect waves-light darken-1 breadcrumbs-btn right',
                                                    ]) ?><?php } ?>
                                            </div>
                                        </div>
                                        <div class="dataTables_wrapper" id="page-length-option_wrapper">


                                            <?= GridView::widget(['id' => 'didmanagement-grid-index', // TODO : Add Grid Widget ID
                                                'dataProvider' => $dataProvider,
                                                'layout' => Yii::$app->layoutHelper->get_layout_str('#didmanagement-search-form'),
                                                'showOnEmpty' => true,
                                                'pager' => ['prevPageLabel' => '<a class="paginate_button previous disabled" aria-controls="data-table-row-grouping" data-dt-idx="0" tabindex="0" id="data-table-row-grouping_previous">' . Yii::t('app', 'previous') . '</a>',
                                                    'nextPageLabel' => '<a class="paginate_button next" aria-controls="data-table-row-grouping" data-dt-idx="4" tabindex="0" id="data-table-row-grouping_next">' . Yii::t('app', 'next') . '</a>', 'maxButtonCount' => 5],
                                                'options' => ['tag' => false,],
                                                'columns' => [['class' => 'yii\grid\ActionColumn',
                                                    'template' => '{update}{delete}',
                                                    'header' => Yii::t('app', 'action'),
                                                    'headerOptions' => ['class' => 'center width-10'],
                                                    'contentOptions' => ['class' => 'center width-10'],
                                                    'buttons' => ['update' => function ($url, $model) use ($permissions) {
                                                        if (in_array('/didmanagement/did-management/update', $permissions)) {
                                                            return (1 ? Html::a('<i class="material-icons">edit</i>', $url, ['style' => '',
                                                                'title' => Yii::t('app', 'update'),]) : '');
                                                        }else{
                                                            return '';
                                                        }
                                                    },
                                                        'delete' => function ($url, $model) use ($permissions) {
                                                            if (in_array('/didmanagement/did-management/delete', $permissions)) {
                                                                if($model->from_service == '0') {
                                                                    return (1 ? Html::a('<i class="material-icons">delete</i>', $url,
                                                                        ['class' => 'ml-5',
                                                                            'data-pjax' => 0,
                                                                            'style' => 'color:#FF4B56',
                                                                            'title' => Yii::t('app', 'delete'),
                                                                            'data-confirm' => Yii::t('app', 'delete_confirm'),
                                                                            'data-method' => 'post',]) : '');
                                                                }else{
                                                                    $canNotDelete = DidManagementModule::t('did', 'can_not_delete');
                                                                    return '<a disabled name="login-button" class="ml-5 opacity5" title="' . $canNotDelete . '"><i class="material-icons">delete</i></a>';
                                                                }
                                                            }else{
                                                                return '';
                                                            }
                                                        },
                                                    ],
                                                    //'visible' => TenantModuleConfig::isTrunkDidRoutingEnabled()
                                                ],

                                                    /*
                                                    [
                                                    'attribute'=>'did_id',
                                                    'headerOptions'=>['class' => 'text-center'],
                                                    'contentOptions' => ['class' => 'text-center'],
                                                    ],*/
                                                    ['attribute' => 'did_number',
                                                        'enableSorting' => True,
                                                        'headerOptions' => ['class' => 'text-center'],
                                                        'contentOptions' => ['class' => 'text-center'],
                                                    ],
                                                    [
                                                        'attribute' => 'action_value',
                                                        'header'=> DidManagementModule::t('did', 'destination'),
                                                        'headerOptions' => ['class' => 'center'],
                                                        'contentOptions' => ['class' => 'center'],
                                                        'enableSorting' => True,
                                                        'value' => function ($model) {
                                                            return (!empty($model->action_id) ? ($model->action_id == 6 ? ($model->didAction->ser_name.' - '.$model->action_value) : $model->didAction->ser_name.' - '.DidManagement::getDidActionValue($model->didAction->ser_name, $model->action_value)) : ' - ');
                                                        }
                                                    ],
                                                    /*[
                                                        'attribute' => 'action_id',
                                                        'headerOptions' => ['class' => 'text-center'],
                                                        'contentOptions' => ['class' => 'text-center'],
                                                    ], [
                                                        'attribute' => 'action_value',
                                                        'headerOptions' => ['class' => 'text-center'],
                                                        'contentOptions' => ['class' => 'text-center'],
                                                    ],*/
                                                    ['attribute' => 'did_status',
                                                        'enableSorting' => True,
                                                        'format' => 'raw',
                                                        'headerOptions' => ['class' => 'text-center'],
                                                        'contentOptions' => ['class' => 'text-center'],
                                                        'value' => function ($model) {

                                                            if ($model->did_status == 1) {
                                                                return '<span class="new badge gradient-45deg-cyan-light-green
" data-badge-caption="">' . Yii::t('app', 'active') . '</span>';
                                                            } else {
                                                                return '<span class="new badge gradient-45deg-red-pink
" data-badge-caption="">' . Yii::t('app', 'inactive') . '</span>';
                                                            }

                                                        }],
                                                    //            [
                                                    //'attribute'=>'did_status',
                                                    //'headerOptions'=>['class' => 'text-center'],
                                                    //'contentOptions' => ['class' => 'text-center'],
                                                    //],
                                                    //            [
                                                    //'attribute'=>'created_date',
                                                    //'headerOptions'=>['class' => 'text-center'],
                                                    //'contentOptions' => ['class' => 'text-center'],
                                                    //],
                                                    /*[
                                        'attribute'=>'updated_date',
                                        'headerOptions'=>['class' => 'text-center'],
                                        'contentOptions' => ['class' => 'text-center'],
                                        ],
                                        */],
                                                'tableOptions' => ['class' => 'display dataTable dtr-inline',
                                                ],]); ?>


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
