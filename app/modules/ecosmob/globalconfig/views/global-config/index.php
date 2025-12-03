<?php

use app\modules\ecosmob\globalconfig\GlobalConfigModule;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\ecosmob\globalconfig\models\GlobalConfigSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = GlobalConfigModule::t('gc', 'global_configurations');
$this->params['breadcrumbs'][] = $this->title;
$this->params['pageHead'] = $this->title;
$permissions = $GLOBALS['permissions'];
?>
<?php Pjax::begin(['id' => 'global-config-index', 'timeout' => 0, 'enablePushState' => false]); ?>

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
                                                </div>
                                                <?php try {
                                                    echo GridView::widget([
                                                        'id' => 'global-config-grid-index',
                                                        'dataProvider' => $dataProvider,
                                                        'layout' => Yii::$app->layoutHelper->get_layout_str('#global-config-search-form'),
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
                                                                'template' => '{update}',
                                                                'header' => Yii::t('app', 'action'),
                                                                'headerOptions' => ['class' => 'center width-10'],
                                                                'contentOptions' => ['class' => 'center width-10'],
                                                                'buttons' => [
                                                                    'update' => function ($url) use ($permissions) {
                                                                        if (in_array('/globalconfig/global-config/update', $permissions)) {
                                                                            return (1 ? Html::a('<i class="material-icons">edit</i>',
                                                                                $url,
                                                                                [
                                                                                    'style' => '',
                                                                                    'title' => Yii::t('app', 'update')
                                                                                ]) : '');
                                                                        }else{
                                                                            return '';
                                                                        }
                                                                    },
                                                                ],
                                                            ],

                                                            [
                                                                'attribute' => 'gwc_key',
                                                                'enableSorting' => TRUE,
                                                                'headerOptions' => ['class' => 'text-center'],
                                                                'contentOptions' => ['class' => 'text-center'],
                                                            ],
                                                            [
                                                                'attribute' => 'gwc_value',
                                                                'enableSorting' => TRUE,
                                                                'headerOptions' => ['class' => 'text-center'],
                                                                'contentOptions' => ['class' => 'text-center'],
                                                                'value' => function($model){
                                                                    if($model->gwc_key == 'moh_file'){
                                                                        $end = explode('/', $model->gwc_value);
                                                                        $end = array_reverse($end)[0];
                                                                        return $end;
                                                                    }else{
                                                                        return $model->gwc_value;
                                                                    }
                                                                }
                                                            ],
                                                            [
                                                                'attribute' => 'gwc_description',
                                                                'enableSorting' => TRUE,
                                                                'headerOptions' => ['class' => 'text-center'],
                                                                'contentOptions' => ['class' => 'text-center'],
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

<?php Pjax::end();
