<?php

use app\modules\ecosmob\weekoff\WeekOffModule;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\ecosmob\weekoff\models\WeekOffSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = WeekOffModule::t('wo', 'wo');
$this->params['breadcrumbs'][] = $this->title;
$this->params['pageHead'] = $this->title;

$permissions = $GLOBALS['permissions']; ?>
<?php Pjax::begin(['id' => 'week-off-index', 'timeout' => 0, 'enablePushState' => false]); ?>

    <div class="row">
        <div class="col-xl-9 col-md-7 col-xs-12">
            <div class="row">
                <div class="col s12">
                    <div class="profile-contain">
                        <div class="section section-data-tables">
                            <div class="row">
                                <div class="col s12">
                                    <div class="card table-structure">
                                        <div class="card-content">
                                            <div class="card-header d-flex align-items-center justify-content-between w-100">
                                                <div class="header-title">
                                                    <?= $this->title ?>
                                                </div>
                                                <div class="card-header-btns">
                                                    <?php if (in_array('/weekoff/week-off/create', $permissions)) { ?>
                                                        <?= Html::a(WeekOffModule::t('wo', 'add_new'), ['create'], [
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
                                                        'id' => 'week-off-grid-index',
                                                        'dataProvider' => $dataProvider,
                                                        'layout' => Yii::$app->layoutHelper->get_layout_without_pager(),
                                                        'showOnEmpty' => true,
                                                        'pager' => [
                                                            'prevPageLabel' => '<a class="paginate_button previous disabled" aria-controls="data-table-row-grouping" data-dt-idx="0" tabindex="0" id="data-table-row-grouping_previous">Previous</a>',
                                                            'nextPageLabel' => '<a class="paginate_button next" aria-controls="data-table-row-grouping" data-dt-idx="4" tabindex="0" id="data-table-row-grouping_next">Next</a>',
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
                                                                        if (in_array('/weekoff/week-off/update', $permissions)) {
                                                                            return (1 ? Html::a('<i class="material-icons color-orange">edit</i>',
                                                                                $url,
                                                                                [
                                                                                    'title' => Yii::t('app', 'update')
                                                                                ]) : '');
                                                                        }else{
                                                                            return '';
                                                                        }
                                                                    },
                                                                    'delete' => function ($url) use ($permissions) {
                                                                        if (in_array('/weekoff/week-off/delete', $permissions)) {
                                                                            return (1 ? Html::a('<i class="material-icons">delete</i>',
                                                                                $url,
                                                                                [
                                                                                    'class' => 'ml-5',
                                                                                    'data-pjax' => 0,
                                                                                    'style' => 'color:#FF4B56',
                                                                                    'data-confirm' => Yii::t('app', 'delete_confirm'),
                                                                                    'data-method' => 'post',
                                                                                    'title' => Yii::t('app', 'delete')
                                                                                ]) : '');
                                                                        }else{
                                                                            return '';
                                                                        }
                                                                    },
                                                                ],
                                                            ],
                                                            [
                                                                'attribute' => 'wo_day',
                                                                'enableSorting' => TRUE,
                                                            ],
                                                            [
                                                                'attribute' => 'wo_start_time',
                                                                'enableSorting' => TRUE,
                                                            ],
                                                            [
                                                                'attribute' => 'wo_end_time',
                                                                'enableSorting' => TRUE,
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
