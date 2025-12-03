<?php

use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this \yii\web\View */
/* @var $gridViewColumns array */
/* @var $dataProvider \yii\data\ArrayDataProvider */
/* @var $searchModel \yii2mod\rbac\models\search\AssignmentSearch */

$this->title = Yii::t('yii2mod.rbac', 'Assignments');
$this->params['breadcrumbs'][] = $this->title;
$this->params['pageHead'] = $this->title;
$perPage = Yii::$app->request->get('per-page') ? Yii::$app->request->get('per-page') : 5;
Yii::$app->session->set('per-page-result', $perPage);
$userNameField = isset($gridViewColumns[1]) ? $gridViewColumns[1] : null;
$columns[$userNameField] = [
    'attribute' => $userNameField,
    'header' => Yii::t('app', 'username'),
    'headerOptions' => ['class' => 'text-center'],
    'contentOptions' => ['class' => 'text-center'],
]
?>
<div class="col s6">

</div>
</div>
</div>
</div>


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
                                <div class="card">
                                    <div class="card-content">
                                        <?php Pjax::begin(['timeout' => 5000]); ?>
                                        <div class="dataTables_wrapper" id="page-length-option_wrapper">
                                            <?php echo GridView::widget([
                                                'dataProvider' => $dataProvider,
                                                'id' => 'rbac-assignment-index', // TODO : Add Grid Widget ID
                                                'showOnEmpty' => true,
                                                'pager' => [
                                                    'prevPageLabel' => '<a class="paginate_button previous disabled" aria-controls="data-table-row-grouping" data-dt-idx="0" tabindex="0" id="data-table-row-grouping_previous">Previous</a>',
                                                    'nextPageLabel' => '<a class="paginate_button next" aria-controls="data-table-row-grouping" data-dt-idx="4" tabindex="0" id="data-table-row-grouping_next">Next</a>',
                                                    'maxButtonCount' => 5,
                                                ],
                                                'layout' => Yii::$app->layoutHelper->get_layout_str('#time-condition-search-form'),
                                                'options' => [
                                                    'tag' => false,
                                                ],
                                                'columns' => ArrayHelper::merge([
                                                    [
                                                        'class' => 'yii\grid\ActionColumn',
                                                        'template' => '{view}',
                                                        'header' => Yii::t(
                                                            'app', 'action'
                                                        ),
                                                        'contentOptions' => ['class' => 'text-center btn-space'],
                                                        'headerOptions' => ['class' => 'text-center'],
                                                        'buttons' => [
                                                            'view' => function ($url) {
                                                                return (1 ? Html::a('<i class="material-icons">edit</i>',
                                                                    $url,
                                                                    [
                                                                        'style' => '',
                                                                    ]) : '');

                                                            },
                                                        ],
                                                    ],
                                                ], $columns),
                                                'tableOptions' => [
                                                    'class' => 'display dataTable dtr-inline',
                                                    
                                                ],
                                            ]); ?>
                                        </div>
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

