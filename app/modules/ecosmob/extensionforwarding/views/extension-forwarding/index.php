<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use app\modules\ecosmob\extensionforwarding\ExtensionForwardingModule;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\ecosmob\extensionforwarding\models\ExtensionForwardingSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'update_ext_forw');
$this->params['breadcrumbs'][] = $this->title;
$this->params['pageHead'] = $this->title;

?>
<?= Yii::$app->view->renderFile('@app/views/auth/iframe/header.php') ?>

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
                            <div class="col s12 search-filter">
                                <?= $this->render('_search', ['model' => $searchModel]); ?>
                            </div>
                            <div class="col s12">
                                <div class="card">
                                    <div class="card-content">
                                        <div class="dataTables_wrapper" id="page-length-option_wrapper">

                                            <?php Pjax::begin(['enablePushState' => false, 'id' => 'pjax-extension-forwarding']); ?>
                                            <?= GridView::widget([
                                                'id' => 'extension-forwarding-grid-index', // TODO : Add Grid Widget ID
                                                'dataProvider' => $dataProvider,
                                                'layout' => Yii::$app->layoutHelper->get_layout_str('#extension-forwarding-search-form'),
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
                                                            'update' => function ($url, $model) {
                                                                return (1 ? Html::a('<i class="material-icons">edit</i>', $url, [
                                                                    'style' => '',
                                                                    'title' => Yii::t('app', 'update'),
                                                                ]) : '');
                                                            },
                                                            'delete' => function ($url, $model) {
                                                                return (1 ? Html::a('<i class="material-icons">delete</i>', $url, [

                                                                    'class' => 'ml-5',
                                                                    'data-pjax' => 0,
                                                                    'style' => 'color:#FF4B56',
                                                                    'data-confirm' => Yii::t('app', 'delete_confirm'),
                                                                    'data-method' => 'post',
                                                                    'title' => Yii::t('app', 'delete'),
                                                                ]) : '');
                                                            },
                                                        ]
                                                    ],

                                                    [
                                                        'attribute' => 'ef_extension',
                                                        'headerOptions' => ['class' => 'text-center'],
                                                        'contentOptions' => ['class' => 'text-center'],
                                                        'value'          => function ( $model ) {
                                                            return ( $model->ef_extension != '' ) ? $model->ef_extension: '-';
                                                        },
                                                    ],
                                                    [
                                                        'attribute' => 'ef_unconditional_type',
                                                        'headerOptions' => ['class' => 'text-center'],
                                                        'contentOptions' => ['class' => 'text-center'],
                                                        'value'          => function ( $model ) {
                                                            return ( $model->ef_unconditional_type != '' ) ? $model->ef_unconditional_type: '-';
                                                        },
                                                    ],
                                                    [
                                                        'attribute' => 'ef_unconditional_num',
                                                        'headerOptions' => ['class' => 'text-center'],
                                                        'contentOptions' => ['class' => 'text-center'],
                                                        'value'          => function ( $model ) {
                                                            return ( $model->ef_unconditional_num != '' ) ? $model->ef_unconditional_num: '-';
                                                        },
                                                    ],
                                                    [
                                                        'attribute' => 'ef_holiday_type',
                                                        'headerOptions' => ['class' => 'text-center'],
                                                        'contentOptions' => ['class' => 'text-center'],
                                                        'value'          => function ( $model ) {
                                                            return ( $model->ef_holiday_type != '' ) ? $model->ef_holiday_type: '-';
                                                        },
                                                    ],
                                                    /*[
                                                        'attribute' => 'ef_holiday',
                                                        'headerOptions' => ['class' => 'text-center'],
                                                        'contentOptions' => ['class' => 'text-center'],
                                                    'value'          => function ( $model ) {
                                                            return ( $model->ef_holiday != '' ) ? $model->ef_unconditional_type ef_holiday'-';
                                                        },
                                                    ],*/
                                                    [
                                                        'attribute' => 'ef_holiday_num',
                                                        'headerOptions' => ['class' => 'text-center'],
                                                        'contentOptions' => ['class' => 'text-center'],
                                                        'value'          => function ( $model ) {
                                                            return ( $model->ef_holiday_num != '' ) ? $model->ef_holiday_num: '-';
                                                        },
                                                    ],
                                                    [
                                                        'attribute' => 'ef_weekoff_type',
                                                        'headerOptions' => ['class' => 'text-center'],
                                                        'contentOptions' => ['class' => 'text-center'],
                                                        'value'          => function ( $model ) {
                                                            return ( $model->ef_weekoff_type != '' ) ? $model->ef_weekoff_type: '-';
                                                        },
                                                    ],
                                                    /*[
                                                        'attribute' => 'ef_weekoff',
                                                        'headerOptions' => ['class' => 'text-center'],
                                                        'contentOptions' => ['class' => 'text-center'],
                                                    'value'          => function ( $model ) {
                                                            return ( $model->ef_weekoff != '' ) ? $model->ef_unconditional_type ef_weekoff'-';
                                                        },
                                                    ],*/
                                                    [
                                                        'attribute' => 'ef_weekoff_num',
                                                        'headerOptions' => ['class' => 'text-center'],
                                                        'contentOptions' => ['class' => 'text-center'],
                                                        'value'          => function ( $model ) {
                                                            return ( $model->ef_weekoff_num != '' ) ? $model->ef_weekoff_num: '-';
                                                        },
                                                    ],
                                                    [
                                                        'attribute' => 'ef_shift_type',
                                                        'headerOptions' => ['class' => 'text-center'],
                                                        'contentOptions' => ['class' => 'text-center'],
                                                        'value'          => function ( $model ) {
                                                            return ( $model->ef_shift_type != '' ) ? $model->ef_shift_type: '-';
                                                        },
                                                    ],
                                                    /*[
                                                        'attribute' => 'ef_shift',
                                                        'headerOptions' => ['class' => 'text-center'],
                                                        'contentOptions' => ['class' => 'text-center'],
                                                    'value'          => function ( $model ) {
                                                            return ( $model->ef_shift != '' ) ? $model->ef_unconditional_type ef_shift'-';
                                                        },
                                                    ],*/
                                                    [
                                                        'attribute' => 'ef_shift_num',
                                                        'headerOptions' => ['class' => 'text-center'],
                                                        'contentOptions' => ['class' => 'text-center'],
                                                        'value'          => function ( $model ) {
                                                            return ( $model->ef_shift_num != '' ) ? $model->ef_shift_num: '-';
                                                        },
                                                    ],
                                                    [
                                                        'attribute' => 'ef_universal_type',
                                                        'headerOptions' => ['class' => 'text-center'],
                                                        'contentOptions' => ['class' => 'text-center'],
                                                        'value'          => function ( $model ) {
                                                            return ( $model->ef_universal_type != '' ) ? $model->ef_universal_type: '-';
                                                        },
                                                    ],
                                                    [
                                                        'attribute' => 'ef_universal_num',
                                                        'headerOptions' => ['class' => 'text-center'],
                                                        'contentOptions' => ['class' => 'text-center'],
                                                        'value'          => function ( $model ) {
                                                            return ( $model->ef_universal_num != '' ) ? $model->ef_universal_num: '-';
                                                        },
                                                    ],
                                                    [
                                                        'attribute' => 'ef_no_answer_type',
                                                        'headerOptions' => ['class' => 'text-center'],
                                                        'contentOptions' => ['class' => 'text-center'],
                                                        'value'          => function ( $model ) {
                                                            return ( $model->ef_no_answer_type != '' ) ? $model->ef_no_answer_type: '-';
                                                        },
                                                    ],
                                                    [
                                                        'attribute' => 'ef_no_answer_num',
                                                        'headerOptions' => ['class' => 'text-center'],
                                                        'contentOptions' => ['class' => 'text-center'],
                                                        'value'          => function ( $model ) {
                                                            return ( $model->ef_no_answer_num != '' ) ? $model->ef_no_answer_num: '-';
                                                        },
                                                    ],
                                                    [
                                                        'attribute' => 'ef_busy_type',
                                                        'headerOptions' => ['class' => 'text-center'],
                                                        'contentOptions' => ['class' => 'text-center'],
                                                        'value'          => function ( $model ) {
                                                            return ( $model->ef_busy_type != '' ) ? $model->ef_busy_type: '-';
                                                        },
                                                    ],
                                                    [
                                                        'attribute' => 'ef_busy_num',
                                                        'headerOptions' => ['class' => 'text-center'],
                                                        'contentOptions' => ['class' => 'text-center'],
                                                        'value'          => function ( $model ) {
                                                            return ( $model->ef_busy_num != '' ) ? $model->ef_busy_num: '-';
                                                        },
                                                    ],
                                                    [
                                                        'attribute' => 'ef_unavailable_type',
                                                        'headerOptions' => ['class' => 'text-center'],
                                                        'contentOptions' => ['class' => 'text-center'],
                                                        'value'          => function ( $model ) {
                                                            return ( $model->ef_unavailable_type != '' ) ? $model->ef_unavailable_type: '-';
                                                        },
                                                    ],
                                                    [
                                                        'attribute' => 'ef_unavailable_num',
                                                        'headerOptions' => ['class' => 'text-center'],
                                                        'contentOptions' => ['class' => 'text-center'],
                                                        'value'          => function ( $model ) {
                                                            return ( $model->ef_unavailable_num != '' ) ? $model->ef_unavailable_num: '-';
                                                        },
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

