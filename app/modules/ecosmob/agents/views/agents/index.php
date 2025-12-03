<?php

use app\modules\ecosmob\agents\AgentsModule;
use app\modules\ecosmob\extension\models\Extension;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\ecosmob\agents\models\AdminMasterSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'agents');
$this->params['breadcrumbs'][] = $this->title;
$this->params['pageHead'] = $this->title;
$permissions = $GLOBALS['permissions'];
?>
<?php Pjax::begin(['id' => 'agent-index', 'timeout' => 0, 'enablePushState' => false]); ?>
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
                                                if (in_array('/agents/agents/create', $permissions)) { ?>
                                                    <?= Html::a(AgentsModule::t('agents', 'add_new'), ['create'], [
                                                        'id' => 'hov',
                                                        'data-pjax' => 0,
                                                        'class' => 'btn waves-effect waves-light darken-1 breadcrumbs-btn right',
                                                    ]) ?><?php
                                                } ?>
                                            </div>
                                        </div>
                                        <div class="dataTables_wrapper" id="page-length-option_wrapper">
                                            <?php try {
                                                echo GridView::widget([
                                                    'id' => 'admin-master-grid-index', // TODO : Add Grid Widget ID
                                                    'dataProvider' => $dataProvider,
                                                    'layout' => Yii::$app->layoutHelper->get_layout_str('#admin-master-search-form'),
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
                                                            'class' => 'yii\grid\ActionColumn',
                                                            'template' => '{update}{delete}',
                                                            'header' => Yii::t('app', 'action'),
                                                            'headerOptions' => ['class' => 'center width-10'],
                                                            'contentOptions' => ['class' => 'center width-10'],
                                                            'buttons' => [
                                                                'update' => function ($url) use ($permissions) {
                                                                    if (in_array('/agents/agents/update', $permissions)) {
                                                                        return (1 ? Html::a('<i class="material-icons">edit</i>', $url, [
                                                                            'style' => '',
                                                                            'title' => Yii::t('app', 'update'),
                                                                        ]) : '');
                                                                    }else{
                                                                        return '';
                                                                    }
                                                                },
                                                                'delete' => function ($url) use ($permissions) {
                                                                    if (in_array('/agents/agents/delete', $permissions)) {
                                                                        return (1 ? Html::a('<i class="material-icons">delete</i>', $url, [

                                                                            'class' => 'ml-5',
                                                                            'data-pjax' => 0,
                                                                            'style' => 'color:#FF4B56',
                                                                            'data-confirm' => Yii::t('app', 'delete_confirm'),
                                                                            'data-method' => 'post',
                                                                            'title' => Yii::t('app', 'delete'),
                                                                        ]) : '');
                                                                    }else{
                                                                        return '';
                                                                    }
                                                                },
                                                            ]
                                                        ],

                                                        [
                                                            'attribute' => 'adm_status',
                                                            'format' => 'raw',
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'enableSorting' => True,
                                                            'value' => function ($model) {
                                                                if ($model->adm_status == 1) {
                                                                    return '<span class="new badge gradient-45deg-cyan-light-green"
                                                                            data-badge-caption="">' . Yii::t('app', 'active') . '</span>';
                                                                } else {
                                                                    return '<span class="new badge gradient-45deg-red-pink"
                                                                            data-badge-caption="">' . Yii::t('app', 'inactive') . '</span>';
                                                                }
                                                            },
                                                        ],

                                                        [
                                                            'attribute' => 'adm_firstname',
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'enableSorting' => True,
                                                        ],
                                                        [
                                                            'attribute' => 'adm_lastname',
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'enableSorting' => True,
                                                        ],
                                                        [
                                                            'attribute' => 'adm_username',
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'enableSorting' => True,
                                                        ],
                                                        [
                                                            'attribute' => 'adm_mapped_extension',
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'enableSorting' => True,
                                                            'value' => function ($model) {
                                                                if ($model->adm_mapped_extension > 0) {
                                                                    $extension = Extension::findOne($model->adm_mapped_extension);
                                                                    return $extension ? $extension->em_extension_name . '-' . $extension->em_extension_number : '-';
                                                                }else{
                                                                    return '-';
                                                                }
                                                            },
                                                        ],
                                                        [
                                                            'attribute' => 'adm_email',
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'enableSorting' => True,
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
