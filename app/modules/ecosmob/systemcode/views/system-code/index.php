<?php

use app\modules\ecosmob\systemcode\SystemCodeModule;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\ecosmob\systemcode\models\FeatureMasterSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = SystemCodeModule::t('systemcode', 'sys_codes');
$this->params['breadcrumbs'][] = $this->title;
$this->params['pageHead'] = $this->title;
?>
<?php Pjax::begin(['id' => 'system-code-index', 'timeout' => 0, 'enablePushState' => false]); ?>

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
                                                        'id' => 'feature-master-grid-index', // TODO : Add Grid Widget ID
                                                        'dataProvider' => $dataProvider,
                                                        'layout' => Yii::$app->layoutHelper->get_layout_str('#feature-master-search-form'),
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

                                                            ['class' => 'yii\grid\SerialColumn'],
                                                            [
                                                                'attribute' => 'feature_name',
                                                                'headerOptions' => ['class' => 'text-center'],
                                                                'contentOptions' => ['class' => 'text-center'],
                                                                'enableSorting' => TRUE,
                                                            ],
                                                            [
                                                                'attribute' => 'feature_code',
                                                                'headerOptions' => ['class' => 'text-center'],
                                                                'contentOptions' => ['class' => 'text-center'],
                                                                'enableSorting' => TRUE,
                                                            ],
                                                            [
                                                                'attribute' => 'feature_desc',
                                                                'headerOptions' => ['class' => 'text-center'],
                                                                'contentOptions' => ['class' => 'text-center'],
                                                                'enableSorting' => TRUE,
                                                            ],
                                                        ],
                                                        'tableOptions' => [
                                                            'class' => 'display dataTable dtr-inline',

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

<?php Pjax::end();
