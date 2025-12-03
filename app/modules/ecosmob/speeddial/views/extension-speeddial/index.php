<?php

use app\modules\ecosmob\speeddial\SpeeddialModule;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\ecosmob\speeddial\models\ExtensionSpeeddialSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = SpeeddialModule::t('app', 'ext_speeddial');
$this->params['breadcrumbs'][] = $this->title;
$this->params['pageHead'] = $this->title;
?>

<div class="col s12 m7 pt-1 pb-1 pr-0 mob-m">
    <!--<a class="mb-6 btn waves-effect waves-light green darken-1 breadcrumbs-btn right">Add New</a>-->
    <?= Html::a('Add New', ['create'], [
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
                            <div class="col s12">
                                <div class="card">
                                    <div class="card-content">
                                        <div class="dataTables_wrapper" id="page-length-option_wrapper">
                                            <?php Pjax::begin([
                                                'enablePushState' => false,
                                                'id' => 'pjax-extension-speeddial'
                                            ]); ?>
                                            <?= GridView::widget([
                                                'id' => 'extension-speeddial-grid-index', // TODO : Add Grid Widget ID
                                                'dataProvider' => $dataProvider,
                                                'layout' => Yii::$app->layoutHelper->get_layout_str('#extension-speeddial-search-form'),
                                                'showOnEmpty' => true,
                                                'pager' => [
                                                    'prevPageLabel' => '<a class="paginate_button previous disabled" aria-controls="data-table-row-grouping" data-dt-idx="0" tabindex="0" id="data-table-row-grouping_previous">Previous</a>',
                                                    'nextPageLabel' => '<a class="paginate_button next" aria-controls="data-table-row-grouping" data-dt-idx="4" tabindex="0" id="data-table-row-grouping_next">Next</a>',
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
                                                    [
                                                        'attribute' => 'es_extension',
                                                        'headerOptions' => ['class' => 'text-center'],
                                                        'contentOptions' => ['class' => 'text-center'],
                                                    ],
                                                    [
                                                        'attribute' => 'es_0',
                                                        'headerOptions' => ['class' => 'text-center'],
                                                        'contentOptions' => ['class' => 'text-center'],
                                                    ],
                                                    [
                                                        'attribute' => 'es_1',
                                                        'headerOptions' => ['class' => 'text-center'],
                                                        'contentOptions' => ['class' => 'text-center'],
                                                    ],
                                                    [
                                                        'attribute' => 'es_2',
                                                        'headerOptions' => ['class' => 'text-center'],
                                                        'contentOptions' => ['class' => 'text-center'],
                                                    ],
                                                    [
                                                        'attribute' => 'es_3',
                                                        'headerOptions' => ['class' => 'text-center'],
                                                        'contentOptions' => ['class' => 'text-center'],
                                                    ],
                                                    [
                                                        'attribute' => 'es_4',
                                                        'headerOptions' => ['class' => 'text-center'],
                                                        'contentOptions' => ['class' => 'text-center'],
                                                    ],
                                                    [
                                                        'attribute' => 'es_5',
                                                        'headerOptions' => ['class' => 'text-center'],
                                                        'contentOptions' => ['class' => 'text-center'],
                                                    ],
                                                    [
                                                        'attribute' => 'es_6',
                                                        'headerOptions' => ['class' => 'text-center'],
                                                        'contentOptions' => ['class' => 'text-center'],
                                                    ],
                                                    [
                                                        'attribute' => 'es_7',
                                                        'headerOptions' => ['class' => 'text-center'],
                                                        'contentOptions' => ['class' => 'text-center'],
                                                    ],
                                                    [
                                                        'attribute' => 'es_8',
                                                        'headerOptions' => ['class' => 'text-center'],
                                                        'contentOptions' => ['class' => 'text-center'],
                                                    ],
                                                    [
                                                        'attribute' => 'es_9',
                                                        'headerOptions' => ['class' => 'text-center'],
                                                        'contentOptions' => ['class' => 'text-center'],
                                                    ],

                                                ],
                                                'tableOptions' => [
                                                    'class' => 'display dataTable dtr-inline',

                                                ],
                                            ]); ?>
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

