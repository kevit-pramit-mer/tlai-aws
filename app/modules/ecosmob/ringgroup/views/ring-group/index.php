<?php

use app\modules\ecosmob\ringgroup\RingGroupModule;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;
use app\modules\ecosmob\audiomanagement\models\AudioManagement;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\ecosmob\ringgroup\models\RingGroupSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = RingGroupModule::t('rg', 'ringgroup');
$this->params['breadcrumbs'][] = $this->title;
$this->params['pageHead'] = $this->title;

$permissions = $GLOBALS['permissions'];
?>

<?php Pjax::begin(['id' => 'ring-group-index', 'timeout' => 0, 'enablePushState' => false]); ?>
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
                                                <?php
                                                if (in_array('/ringgroup/ring-group/create', $permissions)) { ?>
                                                    <?= Html::a(RingGroupModule::t('rg', 'add_new'),
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
                                                echo GridView::widget([
                                                    'id' => 'ring-group-grid-index', // TODO : Add Grid Widget ID
                                                    'dataProvider' => $dataProvider,
                                                    'layout' => Yii::$app->layoutHelper->get_layout_str('#ring-group-search-form'),
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
                                                            'headerOptions' => ['class' => 'center width-10'],
                                                            'contentOptions' => ['class' => 'center width-10'],
                                                            'buttons' => [
                                                                'update' => function ($url) use ($permissions) {
                                                                    if (in_array('/ringgroup/ring-group/update', $permissions)) {
                                                                        return (1 ? Html::a('<i class="material-icons">edit</i>',
                                                                            $url,
                                                                            [
                                                                                'style' => '',
                                                                                'title' => Yii::t('app', 'update'),
                                                                                'data-action' => 'edit',
                                                                            ]) : '');
                                                                    }else{
                                                                        return '';
                                                                    }
                                                                },
                                                                'delete' => function ($url) use ($permissions) {
                                                                    if (in_array('/ringgroup/ring-group/delete', $permissions)) {
                                                                        return (1 ? Html::a('<i class="material-icons">delete</i>',
                                                                            $url,
                                                                            [

                                                                                'class' => 'ml-5',
                                                                                'data-pjax' => 0,
                                                                                'style' => 'color:#FF4B56',
                                                                                'title' => Yii::t('app', 'delete'),
                                                                                'data-confirm' => Yii::t('app', 'delete_confirm'),
                                                                                'data-method' => 'post',
                                                                                'data-action' => 'delete',
                                                                            ]) : '');
                                                                    }else{
                                                                        return '';
                                                                    }
                                                                },
                                                            ],
                                                        ],
                                                        [
                                                            'attribute' => 'rg_status',
                                                            'enableSorting' => True,
                                                            'headerOptions' => ['class' => 'center'],
                                                            'contentOptions' => ['class' => 'center'],
                                                            'format' => 'raw',
                                                            'value' => function ($model) {
                                                                if ($model->rg_status == 1) {
                                                                    return '<span class="new badge gradient-45deg-cyan-light-green" data-badge-caption="">' . Yii::t('app', 'active') . '</span>';
                                                                } else {
                                                                    return '<span class="new badge gradient-45deg-red-pink" data-badge-caption="">' . Yii::t('app', 'inactive') . '</span>';
                                                                }

                                                            },
                                                        ],
                                                        [
                                                            'attribute' => 'rg_name',
                                                            'enableSorting' => True,
                                                        ],
                                                        [
                                                            'attribute' => 'rg_extension',
                                                            'enableSorting' => True,
                                                            'headerOptions' => ['class' => 'center'],
                                                            'contentOptions' => ['class' => 'center'],

                                                        ],
                                                        [
                                                            'attribute' => 'rg_type',
                                                            'enableSorting' => True,
                                                            'headerOptions' => ['class' => 'center'],
                                                            'contentOptions' => ['class' => 'center'],
                                                            'value' => function ($model) {
                                                                if ($model->rg_type == 'SIMULTANEOUS') {
                                                                    return RingGroupModule::t('rg', 'simultaneous');
                                                                } else {
                                                                    return RingGroupModule::t('rg', 'sequential');
                                                                }

                                                            },

                                                        ],
                                                        [
                                                            'attribute' => 'rg_language',
                                                            'enableSorting' => True,
                                                            'headerOptions' => ['class' => 'center'],
                                                            'contentOptions' => ['class' => 'center'],
                                                            'value' => function ($model) {
                                                                if ($model->rg_language == 'ENGLISH') {
                                                                    return Yii::t('app', 'english');
                                                                } else {
                                                                    return Yii::t('app', 'spanish');
                                                                }

                                                            },

                                                        ],
                                                        [
                                                            'attribute' => 'rg_moh',
                                                            'enableSorting' => True,
                                                            'headerOptions' => ['class' => 'center'],
                                                            'contentOptions' => ['class' => 'center'],
                                                            'value' => function ($model) {
                                                                if ($model->rg_moh) {
                                                                    return AudioManagement::getPromptName($model->rg_moh);
                                                                } else {
                                                                    return 'None';
                                                                }
                                                            },
                                                        ],
                                                        [
                                                            'attribute' => 'rg_is_recording',
                                                            'enableSorting' => True,
                                                            'value' => function ($model) {
                                                                return ($model->rg_is_recording) ? Yii::t('app', 'on') : Yii::t('app', 'off');

                                                            },
                                                        ],

                                                        /*[
                                                            'attribute' => 'rg_callerid_name',
                                                            'enableSorting' => True,
                                                            'value' => function ($model) {
                                                                return ($model->rg_callerid_name) ? Yii::t('app', 'on') : Yii::t('app', 'off');

                                                            },
                                                        ],*/

                                                    ],
                                                    'tableOptions' => [
                                                        'class' => 'display dataTable dtr-inline',

                                                    ],
                                                ]);
                                            } catch (Exception $e) {
                                                var_dump($e);
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
