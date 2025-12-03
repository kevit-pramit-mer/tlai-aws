<?php

use app\modules\ecosmob\accessrestriction\AccessRestrictionModule;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\ecosmob\accessrestriction\models\AccessRestrictionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = AccessRestrictionModule::t('accessrestriction', 'access_restriction');
$this->params['breadcrumbs'][] = $this->title;
$this->params['pageHead'] = $this->title;
$permissions = $GLOBALS['permissions']; ?>
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
                                                    <?php if (in_array('/accessrestriction/access-restriction/create', $permissions)) { ?>
                                                        <?= Html::a(AccessRestrictionModule::t('accessrestriction', 'add_new'), ['create'], [
                                                            'id' => 'hov',
                                                            'data-pjax' => 0,
                                                            'class' => 'btn waves-effect waves-light gradient-45deg-red-pink darken-1 breadcrumbs-btn right',
                                                        ]) ?>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                            <?php Pjax::begin(['enablePushState' => false, 'id' => 'pjax-access-restriction']); ?>
                                            <?php try {
                                                echo GridView::widget([
                                                    'id' => 'access-restriction-grid-index', // TODO : Add Grid Widget ID
                                                    'dataProvider' => $dataProvider,
                                                    'layout' => Yii::$app->layoutHelper->get_layout_str('#access-restriction-search-form'),
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
                                                                    if (in_array('/accessrestriction/access-restriction/update', $permissions)) {
                                                                        return (1 ? Html::a('<i class="material-icons">edit</i>', $url, [
                                                                            'style' => '',
                                                                            'title' => Yii::t('app', 'update'),
                                                                        ]) : '');
                                                                    }else{
                                                                        return '';
                                                                    }
                                                                },
                                                                'delete' => function ($url) use ($permissions) {
                                                                    if (in_array('/accessrestriction/access-restriction/delete', $permissions)) {
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
                                                            'attribute' => 'ar_ipaddress',
                                                            'headerOptions' => ['class' => 'text-center', 'style' => 'min-width: 150px !important;max-width: 150px !important; word-wrap:break-word'],
                                                            'contentOptions' => ['class' => 'text-center', 'style' => 'min-width: 150px !important;max-width: 150px !important; word-wrap:break-word'],
                                                            'enableSorting' => TRUE,
                                                        ],
                                                        [
                                                            'attribute' => 'ar_maskbit',
                                                            'headerOptions' => ['class' => 'text-center', 'style' => 'min-width: 100px !important;max-width: 100px !important; word-wrap:break-word'],
                                                            'contentOptions' => ['class' => 'text-center', 'style' => 'min-width: 100px !important;max-width: 100px !important; word-wrap:break-word'],
                                                            'enableSorting' => TRUE,
                                                        ],
                                                        [
                                                            'attribute' => 'ar_description',
                                                            'headerOptions' => ['class' => 'text-center', 'style' => 'min-width: 200px !important;max-width: 200px !important; word-wrap:break-word'],
                                                            'contentOptions' => ['class' => 'text-center', 'style' => 'min-width: 200px !important;max-width: 200px !important; word-wrap:break-word'],
                                                            'enableSorting' => TRUE,
                                                        ],

                                                        [
                                                            'attribute' => 'ar_status',
                                                            'enableSorting' => True,
                                                            'format' => 'raw',
                                                            'headerOptions' => ['class' => 'text-center', 'style' => 'min-width: 100px !important;max-width: 100px !important; word-wrap:break-word'],
                                                            'contentOptions' => ['class' => 'text-center', 'style' => 'min-width: 100px !important;max-width: 100px !important; word-wrap:break-word'],
                                                            'value' => function ($model) {

                                                                if ($model->ar_status == 1) {
                                                                    return '<span class="new badge gradient-45deg-cyan-light-green
" data-badge-caption="">' . Yii::t('app', 'active') . '</span>';
                                                                } else {
                                                                    return '<span class="new badge gradient-45deg-red-pink
" data-badge-caption="">' . Yii::t('app', 'inactive') . '</span>';
                                                                }

                                                            }
                                                        ],

                                                    ],
                                                    'tableOptions' => [
                                                        'class' => 'display dataTable dtr-inline',

                                                    ],
                                                ]);
                                            } catch (Exception $e) {
                                            } ?>

                                            <?php Pjax::end(); ?>
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
