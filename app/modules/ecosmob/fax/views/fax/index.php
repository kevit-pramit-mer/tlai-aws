<?php

use app\modules\ecosmob\fax\FaxModule;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\ecosmob\fax\models\FaxSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = FaxModule::t('fax', 'fax');
$this->params['breadcrumbs'][] = $this->title;
$this->params['pageHead'] = $this->title;

$permissions = $GLOBALS['permissions'];
?>
<?php Pjax::begin(['id' => 'fax-index', 'timeout' => 0, 'enablePushState' => false]); ?>
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
                                                    <?php if (in_array('/fax/fax/create', $permissions)) { ?>
                                                        <?= Html::a(FaxModule::t('fax', 'add_new'), ['create'], [
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
                                                        'id' => 'fax-grid-index', // TODO : Add Grid Widget ID
                                                        'dataProvider' => $dataProvider,
                                                        'layout' => Yii::$app->layoutHelper->get_layout_str('#fax-search-form'),
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
                                                                        if (in_array('/fax/fax/update', $permissions)) {
                                                                            return (1 ? Html::a('<i class="material-icons">edit</i>', $url, [
                                                                                'style' => '',
                                                                                'title' => Yii::t('app', 'update'),
                                                                                'data-action' => 'edit',
                                                                            ]) : '');
                                                                        }else{
                                                                            return '';
                                                                        }
                                                                    },
                                                                    'delete' => function ($url) use ($permissions) {
                                                                        if (in_array('/fax/fax/delete', $permissions)) {
                                                                            return (1 ? Html::a('<i class="material-icons">delete</i>', $url, [

                                                                                'class' => 'ml-5',
                                                                                'data-pjax' => 0,
                                                                                'style' => 'color:#FF4B56',
                                                                                'data-confirm' => Yii::t('app', 'delete_confirm'),
                                                                                'data-method' => 'post',
                                                                                'title' => Yii::t('app', 'delete'),
                                                                                'data-action' => 'delete',
                                                                            ]) : '');
                                                                        }else{
                                                                            return '';
                                                                        }
                                                                    },
                                                                ]
                                                            ],
                                                            [
                                                                'attribute' => 'fax_name',
                                                                'headerOptions' => ['class' => 'text-center'],
                                                                'contentOptions' => ['class' => 'text-center'],
                                                                'enableSorting' => True,
                                                            ],
                                                            [
                                                                'attribute' => 'fax_destination',
                                                                'headerOptions' => ['class' => 'text-center'],
                                                                'contentOptions' => ['class' => 'text-center'],
                                                                'enableSorting' => True,
                                                            ],
                                                            [
                                                                'attribute' => 'fax_extension',
                                                                'headerOptions' => ['class' => 'text-center'],
                                                                'contentOptions' => ['class' => 'text-center'],
                                                                'enableSorting' => True,
                                                                'value' => function ($model) {
                                                                    if (!empty($model->fax_extension)) {
                                                                        return $model->fax_extension;
                                                                    } else {
                                                                        return '-';
                                                                    }
                                                                }
                                                            ],
                                                           /* [
                                                                'attribute' => 'fax_failure',
                                                                'headerOptions' => ['class' => 'text-center'],
                                                                'contentOptions' => ['class' => 'text-center'],
                                                                'enableSorting' => True,
                                                                'value' => function ($model) {

                                                                    if ($model->fax_failure == 0) {
                                                                        return 'OFF';
                                                                    } else {
                                                                        return 'ON';
                                                                    }

                                                                },
                                                            ],*/
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
