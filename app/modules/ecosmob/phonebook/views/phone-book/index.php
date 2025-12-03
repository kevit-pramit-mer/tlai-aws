<?php

use app\modules\ecosmob\phonebook\PhoneBookModule;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;

/* @var $searchModel \app\modules\ecosmob\phonebook\models\PhoneBookSearch */
/* @var $dataProvider \yii\data\ActiveDataProvider */

$this->title = PhoneBookModule::t('app', 'phone_book');
$this->params['breadcrumbs'][] = $this->title;
$this->params['pageHead'] = $this->title;
?>
<?= Yii::$app->view->renderFile('@app/views/auth/iframe/header.php') ?>
<?php /*Pjax::begin(['id' => 'phonebook-index', 'timeout' => 0, 'enablePushState' => false]); */ ?>
<div id="main" class="extension-main main-full">
    <div class="row">
        <div class="col s12">
            <div class="container">
                <div class="content-wrapper-before"></div>
                <div class="breadcrumbs-dark col s12 m6" id="breadcrumbs-wrapper">
                    <h5 class="breadcrumbs-title mt-0 mb-0"><?= (isset($this->params['pageHead']) ? $this->params['pageHead'] : "") ?></h5>
                    <?= Breadcrumbs::widget([
                        'tag' => 'ol',
                        'options' => ['class' => 'breadcrumbs mb-0'/*, 'target' => 'myFrame'*/],
                        'itemTemplate' => "<li class='breadcrumb-item'>{link}</li>\n",
                        'homeLink' => [
                            'label' => Yii::t('yii', 'Home'),
                            'url' => Url::to(['/extension/extension/dashboard']),
                            //'url' => 'javascript:void(0);',
                            'target' => "extensionFrame",
                            'encode' => false
                        ],
                        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                    ]) ?>
                </div>
                <?php //$this->render('@app/views/auth/Iframe/header'); ?>
                <div class="row">
                    <div class="col-xl-9 col-md-12 col-xs-12">
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
                                                                <?= Html::a(PhoneBookModule::t('app', 'add_new'), ['create'], [
                                                                    'id' => 'phonebook-grid-index',
                                                                    'data-pjax' => 0,
                                                                    'target' => "extensionFrame",
                                                                    'class' => 'btn waves-effect waves-light darken-1 breadcrumbs-btn right',
                                                                ]) ?>
                                                                <?= Html::a(PhoneBookModule::t('app', 'import'), ['/phonebook/phone-book/import'], [
                                                                    'data-pjax' => 0,
                                                                    'target' => "extensionFrame",
                                                                    'class' => 'btn waves-effect waves-light darken-1 breadcrumbs-btn right',
                                                                ]) ?>
                                                                <?php if (!empty($dataProvider->models)) { ?>
                                                                    <?= Html::a(PhoneBookModule::t('app', 'export'), ['/phonebook/phone-book/export'], [
                                                                        'id' => 'hov',
                                                                        'data-pjax' => 0,
                                                                        'target' => "extensionFrame",
                                                                        'class' => 'exportbutton lead_group mr-2 btn waves-effect waves-light darken-1 breadcrumbs-btn right',
                                                                    ]) ?>
                                                                <?php } ?>
                                                            </div>
                                                        </div>
                                                        <div class="dataTables_wrapper" id="page-length-option_wrapper">
                                                            <?php try {
                                                                echo GridView::widget([
                                                                    'id' => 'phonebook-grid-index', // TODO : Add Grid Widget ID
                                                                    'dataProvider' => $dataProvider,
                                                                    'layout' => Yii::$app->layoutHelper->get_layout_str('#phonebook-search-form'),
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
                                                                                'update' => function ($url) {
                                                                                    return (1 ? Html::a('<i class="material-icons">edit</i>',
                                                                                        $url, [
                                                                                            'style' => '',
                                                                                            'title' => Yii::t('app', 'update'),
                                                                                        ]) : '');
                                                                                },
                                                                                'delete' => function ($url) {
                                                                                    return (1 ? Html::a('<i class="material-icons">delete</i>',
                                                                                        $url, [
                                                                                            'class' => 'ml-5',
                                                                                            'data-pjax' => 0,
                                                                                            'style' => 'color:#FF4B56',
                                                                                            'data-confirm' => Yii::t('app',
                                                                                                'delete_confirm'),
                                                                                            'data-method' => 'post',
                                                                                            'title' => Yii::t('app', 'delete'),
                                                                                        ]) : '');
                                                                                },
                                                                            ]
                                                                        ],
                                                                        /*[
                                                                            'attribute'=>'em_extension',
                                                                            'headerOptions'=>['class'=>'text-center'],
                                                                            'contentOptions'=>['class'=>'text-center'],
                                                                            'enableSorting'=> TRUE,
                                                                        ],*/

                                                                        [
                                                                            'attribute' => 'ph_first_name',
                                                                            'headerOptions' => ['class' => 'text-center'],
                                                                            'contentOptions' => ['class' => 'text-center'],
                                                                            'enableSorting' => TRUE,
                                                                            'value' => function ($model) {
                                                                                if (!empty($model->ph_first_name)) {
                                                                                    return $model->ph_first_name;
                                                                                } else {
                                                                                    return "-";
                                                                                }

                                                                            }

                                                                        ],
                                                                        [
                                                                            'attribute' => 'ph_last_name',
                                                                            'headerOptions' => ['class' => 'text-center'],
                                                                            'contentOptions' => ['class' => 'text-center'],
                                                                            'enableSorting' => TRUE,
                                                                            'value' => function ($model) {
                                                                                if (!empty($model->ph_last_name)) {
                                                                                    return $model->ph_last_name;
                                                                                } else {
                                                                                    return "-";
                                                                                }

                                                                            }
                                                                        ],
                                                                        [
                                                                            'attribute' => 'ph_display_name',
                                                                            'headerOptions' => ['class' => 'text-center'],
                                                                            'contentOptions' => ['class' => 'text-center'],
                                                                            'enableSorting' => TRUE,
                                                                            'value' => function ($model) {
                                                                                if (!empty($model->ph_display_name)) {
                                                                                    return $model->ph_display_name;
                                                                                } else {
                                                                                    return "-";
                                                                                }

                                                                            }
                                                                        ],
                                                                        [
                                                                            'attribute' => 'ph_extension',
                                                                            'headerOptions' => ['class' => 'text-center'],
                                                                            'contentOptions' => ['class' => 'text-center'],
                                                                            'enableSorting' => TRUE,
                                                                            'value' => function ($model) {
                                                                                if (!empty($model->ph_extension)) {
                                                                                    return $model->ph_extension;
                                                                                } else {
                                                                                    return '-';

                                                                                }

                                                                            }
                                                                        ],
                                                                        [
                                                                            'attribute' => 'ph_phone_number',
                                                                            'headerOptions' => ['class' => 'text-center'],
                                                                            'contentOptions' => ['class' => 'text-center'],
                                                                            'enableSorting' => TRUE,
                                                                            'value' => function ($model) {
                                                                                if (!empty($model->ph_phone_number)) {
                                                                                    return $model->ph_phone_number;
                                                                                } else {
                                                                                    return "-";
                                                                                }

                                                                            }
                                                                        ],
                                                                        /*[
                                                                            'attribute'=>'ph_cell_number',
                                                                            'headerOptions'=>['class'=>'text-center'],
                                                                            'contentOptions'=>['class'=>'text-center'],
                                                                            'enableSorting'=> TRUE,
                                                                            'value'=>function ($model) {
                                                                                if (!empty($model->ph_cell_number)) {
                                                                                    return $model->ph_cell_number;
                                                                                } else {
                                                                                    return "-";
                                                                                }

                                                                            }
                                                                        ],*/
                                                                        [
                                                                            'attribute' => 'ph_email_id',
                                                                            'headerOptions' => ['class' => 'text-center'],
                                                                            'contentOptions' => ['class' => 'text-center'],
                                                                            'enableSorting' => TRUE,
                                                                            'value' => function ($model) {
                                                                                if (!empty($model->ph_email_id)) {
                                                                                    return $model->ph_email_id;
                                                                                } else {
                                                                                    return "-";
                                                                                }

                                                                            }
                                                                        ],
                                                                    ],
                                                                    'tableOptions' => [
                                                                        'class' => 'display dataTable dtr-inline providercount',
                                                                        'data-count' => $dataProvider->getTotalCount(),
                                                                    ],
                                                                ]);
                                                            } catch (Exception $e) {
                                                            }
                                                            ?>
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
            </div>
        </div>
    </div>
</div>
    <?php /*Pjax::end(); */ ?>

    <?php
    $this->registerJs("
    $(document).on('click', '.exportbutton', function () {
         var count = ((!$('.providercount').data('count')) ? 0 : $('.providercount').data('count'));
        return checkCount(count, '" . PhoneBookModule::t('app', 'no_records_found_to_export') . "');
    });");
    ?>

