<?php

use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii2mod\rbac\models\search\AuthItemSearch;

/* @var $this yii\web\View */
/* @var $dataProvider ArrayDataProvider */
/* @var $searchModel AuthItemSearch */

$labels = $this->context->getLabels();
$this->title = Yii::t('yii2mod.rbac', $labels['Items']);
$this->params['breadcrumbs'][] = $this->title;
$this->params['pageHead'] = $this->title;
/*$perPage = Yii::$app->request->get('per-page') ? Yii::$app->request->get('per-page') : 5;
Yii::$app->session->set('per-page-result', $perPage);*/
$permissions=$GLOBALS['permissions'];
?>
<?php Pjax::begin(['id' => 'playback-index', 'timeout' => 0, 'enablePushState' => false]); ?>

<div class="row">
    <div class="col-xl-9 col-md-7 col-xs-12">
        <div class="row">
            <div class="col s12">
                <div class="profile-contain">
                    <div class="section section-data-tables">
                        <div class="row">
                            <div class="col s12 search-filter">
                                <?php echo $this->render('_search', ['model' => $searchModel]); ?>
                            </div>
                            <div class="col s12">
                                <div class="card table-structure">
                                    <div class="card-content">
                                        <div class="card-header d-flex align-items-center justify-content-between w-100">
                                            <div class="header-title">
                                                <?= $this->title ?>
                                            </div>
                                            <div class="card-header-btns">
                                                <?php if (in_array('/rbac/role/create', $permissions)) { ?>
                                                    <?= Html::a(Yii::t('app', 'add_new'), ['create'], [
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
                                                    'dataProvider' => $dataProvider,
                                                    'layout' => Yii::$app->layoutHelper->get_layout_str('#assignment-search-form'),
                                                    'showOnEmpty' => true,
                                                    'options' => [
                                                        'class' => 'grid-view-color text-center',
                                                    ],
                                                    'pager' => [
                                                        'prevPageLabel' => '<a class="paginate_button previous disabled" aria-controls="data-table-row-grouping" data-dt-idx="0" tabindex="0" id="data-table-row-grouping_previous">Previous</a>',
                                                        'nextPageLabel' => '<a class="paginate_button next" aria-controls="data-table-row-grouping" data-dt-idx="4" tabindex="0" id="data-table-row-grouping_next">Next</a>',
                                                        'maxButtonCount' => 5,
                                                    ],
                                                    'columns' => [

                                                        [
                                                            'class' => 'yii\grid\ActionColumn',
                                                            'template' => '{update}{assign-access}',
                                                            'header' => Yii::t('app', 'action'),
                                                            'headerOptions' => ['class' => 'center width-10'],
                                                            'contentOptions' => ['class' => 'center width-10'],
                                                            'buttons' => [
                                                                'assign-access' => function ($url) use ($permissions) {
                                                                    if (in_array('/rbac/role/update', $permissions)) {
                                                                        return (1 ? Html::a('<i class="material-icons">send</i>',
                                                                            $url, [
                                                                                'class' => 'ml-5',
                                                                            ]) : '');
                                                                    }else{
                                                                        return '';
                                                                    }
                                                                },
                                                                'update' => function ($url) use ($permissions) {
                                                                    if (in_array('/rbac/role/update', $permissions)) {
                                                                        return (1 ? Html::a('<i class="material-icons">edit</i>',
                                                                            $url, [
                                                                                'class' => 'ml-5',
                                                                            ]) : '');
                                                                    }else{
                                                                        return '';
                                                                    }
                                                                },
                                                                /*'delete' => function ($url, $model) {
                                                                    return (1 ? Html::a('<i class="material-icons">delete</i>',
                                                                        $url, [

                                                                            'class' => 'ml-5',
                                                                            'data-pjax' => 0,
                                                                            'style' => 'color:#FF4B56',
                                                                            'data-confirm' => Yii::t('app',
                                                                                'delete_confirm'),
                                                                            'data-method' => 'post',
                                                                        ]) : '');
                                                                },*/
                                                            ]
                                                        ],

                                                        [
                                                            'attribute' => 'name',
                                                            'label' => Yii::t('app', 'name'),
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
                                                        ],
                                                        [
                                                            'attribute' => 'description',
                                                            'format' => 'ntext',
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'label' => Yii::t('app', 'description'),
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
