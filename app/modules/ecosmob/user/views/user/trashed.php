<?php

use app\modules\ecosmob\user\UserModule;
use yii\grid\GridView;
use yii\helpers\Html;

/* @var $searchModel app\modules\ecosmob\user\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $roles */

$this->title = UserModule::t('usr', 'trash');
$this->params['breadcrumbs'][] = [
    'label' => UserModule::t('usr', 'usr'),
    'url' => ['index'],
];
$this->params['breadcrumbs'][] = UserModule::t('usr', 'trash');
$this->params['pageHead'] = $this->title;
?>

<div class="col s12 m7 pt-1 pb-1 pr-0 mob-m">
    <?= Html::a(UserModule::t('usr', 'back'), ['index'], [
        'id' => 'hov',
        'data-pjax' => 0,
        'class' => 'btn waves-effect waves-light darken-1 breadcrumbs-btn right',
    ]) ?>
</div>
<div class="row">
    <div class="col-xl-9 col-md-7 col-xs-12">
        <div class="row">
            <div class="col s12">
                <div class="profile-contain">
                    <div class="section section-data-tables">
                        <div class="row">
                            <div class="col s12 search-filter">
                                <?= $this->render('_trashsearch', ['model' => $searchModel, 'roles' => $roles]); ?>
                            </div>
                            <div class="col s12">
                                <div class="card">
                                    <div class="card-content">


                                        <div class="dataTables_wrapper" id="page-length-option_wrapper">

                                            <?php try {
                                                echo GridView::widget([
                                                        'id' => 'user-trash-grid-index',
                                                        'dataProvider' => $dataProvider,
                                                        'layout' => Yii::$app->layoutHelper->get_layout_str('#user-trash-search-form'),
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
                                                                'template' => '{restore}{delete-permanent}',
                                                                'header' => Yii::t('app', 'action'),
                                                                'headerOptions' => ['class' => 'center width-10'],
                                                                'contentOptions' => ['class' => 'center width-10'],
                                                                'buttons' => [
                                                                    'restore' => function ($url) {
                                                                        return (1 ? Html::a(
                                                                            '<i class="material-icons">loop</i>',
                                                                            $url,
                                                                            [
                                                                                'style' => '',
                                                                                'data-toggle' => 'popover',
                                                                                'container' => "body",
                                                                                'data-placement' => 'top',
                                                                                'data-trigger' => "hover",
                                                                                'data-content' => UserModule::t(
                                                                                    'usr',
                                                                                    'restore'
                                                                                ),
                                                                                'data-pjax' => 0,
                                                                                'title' => UserModule::t('usr', 'restore'),
                                                                            ]
                                                                        ) : '');
                                                                    },
                                                                    'delete-permanent' => function ($url) {
                                                                        return (1 ? Html::a('<i class="material-icons">delete</i>',
                                                                            $url,
                                                                            [
                                                                                'style' => 'color:#FF4B56',
                                                                                'data-toggle' => 'popover',
                                                                                'data-placement' => 'top',
                                                                                'data-trigger' => "hover",
                                                                                'data-content' => UserModule::t('usr', 'delete_permanent'),
                                                                                'data-confirm' => UserModule::t('usr', 'delete_confirm'),
                                                                                'data-method' => 'post',
                                                                                'data-pjax' => 0,
                                                                                'title' => Yii::t('app', 'delete'),
                                                                            ]
                                                                        ) : '');
                                                                    },
                                                                ],

                                                            ],

                                                            [
                                                                'attribute' => 'adm_status',
                                                                'headerOptions' => ['class' => 'center'],
                                                                'contentOptions' => ['class' => 'center'],
                                                                'enableSorting' => TRUE,
                                                                'format' => 'raw',
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
                                                                'enableSorting' => TRUE,
                                                            ],
                                                            [
                                                                'attribute' => 'adm_lastname',
                                                                'headerOptions' => ['class' => 'text-center'],
                                                                'contentOptions' => ['class' => 'text-center'],
                                                                'enableSorting' => TRUE,
                                                            ],
                                                            [
                                                                'attribute' => 'adm_email',
                                                                'headerOptions' => ['class' => 'text-center'],
                                                                'contentOptions' => ['class' => 'text-center'],
                                                                'enableSorting' => TRUE,
                                                            ],
                                                            [
                                                                'attribute' => 'adm_username',
                                                                'headerOptions' => ['class' => 'text-center'],
                                                                'contentOptions' => ['class' => 'text-center'],
                                                                'enableSorting' => TRUE,
                                                            ],
                                                            [
                                                                'attribute' => 'adm_is_admin',
                                                                'headerOptions' => ['class' => 'text-center'],
                                                                'contentOptions' => ['class' => 'text-center'],
                                                                'enableSorting' => TRUE,
                                                            ],
                                                        ],
                                                        'tableOptions' => [
                                                            'class' => 'display dataTable dtr-inline'
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
</div>
