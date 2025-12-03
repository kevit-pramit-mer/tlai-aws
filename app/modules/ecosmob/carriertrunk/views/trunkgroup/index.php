<?php /** @noinspection PhpUndefinedFieldInspection */

use app\modules\ecosmob\carriertrunk\CarriertrunkModule;
use app\modules\ecosmob\carriertrunk\models\TrunkGroup;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\ecosmob\carriertrunk\models\TrunkGroupSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = CarriertrunkModule::t('carriertrunk', 'trunk_groups');
$this->params['breadcrumbs'][] = $this->title;
$this->params['pageHead'] = $this->title;

$permissions = $GLOBALS['permissions'];
?>
<?php Pjax::begin(['id' => 'trunk-group-index', 'timeout' => 10000, 'enablePushState' => false]); ?>
    <div class="row">
        <div class="col-xl-9 col-md-7 col-xs-12">
            <div class="row">
                <div class="col s12">
                    <div class="profile-contain">
                        <div class="section section-data-tables">
                            <div class="row">
                                <div class="col s12 search-filter">
                                    <?php echo $this->render('search/_search', ['model' => $searchModel]); ?>
                                </div>
                                <div class="col s12">
                                    <div class="card table-structure">
                                        <div class="card-content">
                                            <div class="card-header d-flex align-items-center justify-content-between w-100">
                                                <div class="header-title">
                                                    <?= $this->title ?>
                                                </div>
                                                <div class="card-header-btns">
                                                <?php if (in_array('/carriertrunk/trunkgroup/create', $permissions)) { ?>
                                                    <?= Html::a(CarriertrunkModule::t('carriertrunk', 'add_new'),
                                                        ['create'],
                                                        [
                                                            'id' => 'hov',
                                                            'data-pjax' => 0,
                                                            'class' => 'btn waves-effect waves-light darken-1 breadcrumbs-btn right',
                                                        ]) ?>
                                                <?php } ?>
                                                </div>
                                            </div>
                                            <div class="dataTables_wrapper" id="page-length-option_wrapper">
                                                <?php try {
                                                    echo GridView::widget(
                                                        [
                                                            'dataProvider' => $dataProvider,
                                                            'layout' => Yii::$app->layoutHelper->get_layout_str(
                                                                '#trunkgroup-search-form'
                                                            ),
                                                            'pager' => [
                                                                'prevPageLabel' => '<a class="paginate_button previous disabled" aria-controls="data-table-row-grouping" data-dt-idx="0" tabindex="0" id="data-table-row-grouping_previous">' . Yii::t('app', 'previous') . '</a>',
                                                                'nextPageLabel' => '<a class="paginate_button next" aria-controls="data-table-row-grouping" data-dt-idx="4" tabindex="0" id="data-table-row-grouping_next">' . Yii::t('app', 'next') . '</a>',
                                                                'maxButtonCount' => 5,
                                                            ],
                                                            'options' => [
                                                                'tag' => FALSE,
                                                            ],
                                                            'showOnEmpty' => TRUE,
                                                            'columns' => [
                                                                [
                                                                    'class' => 'yii\grid\ActionColumn',
                                                                    'template' => '{update}{delete}',
                                                                    'header' => Yii::t(
                                                                        'app',
                                                                        'action'
                                                                    ),
                                                                    'headerOptions' => ['class' => 'center  width-10'],
                                                                    'contentOptions' => ['class' => 'center  width-10'],
                                                                    'buttons' => [
                                                                        'update' => function ($url) use ($permissions) {
                                                                            if (in_array('/carriertrunk/trunkgroup/update', $permissions)) {
                                                                                return (1 ? Html::a(
                                                                                    '<i class="material-icons color-orange">edit</i>',
                                                                                    $url,
                                                                                    [
                                                                                        'title' => Yii::t('app', 'update'),
                                                                                    ]
                                                                                ) : '');
                                                                            }else{
                                                                                return '';
                                                                            }
                                                                        },
                                                                        'delete' => function (
                                                                            $url,
                                                                            $searchModel
                                                                        ) use ($permissions) {
                                                                            if (in_array('/carriertrunk/trunkgroup/delete', $permissions)) {
                                                                                /** @var TrunkGroup $candelete */
                                                                                $canDelete = $searchModel->canDelete($searchModel->trunk_grp_id);
                                                                                if ($canDelete) {
                                                                                    return '<a disabled class="ml-5 opacity5" title="' . CarriertrunkModule::t('carriertrunk', 'cannot_delete_assign_to_outbound') . '"><i class="material-icons">delete</i></a>';
                                                                                } else {
                                                                                    return Html::a(
                                                                                        '<i class="material-icons">delete</i>',
                                                                                        $url,
                                                                                        [
                                                                                            'data-pjax' => 0,
                                                                                            'class' => 'ml-5',
                                                                                            'style' => 'color:#FF4B56',
                                                                                            'title' => Yii::t('app', 'delete'),
                                                                                            'data-method' => 'post',
                                                                                            'data-confirm' => CarriertrunkModule::t(
                                                                                                'carriertrunk',
                                                                                                'delete_record'
                                                                                            ),
                                                                                        ]
                                                                                    );
                                                                                }
                                                                            }else{
                                                                                return '';
                                                                            }
                                                                        },
                                                                    ],

                                                                ],
                                                                [
                                                                    'attribute' => 'trunk_grp_status',
                                                                    'format' => 'raw',
                                                                    'contentOptions' => ['class' => 'center'],
                                                                    'headerOptions' => ['class' => 'center'],
                                                                    'enableSorting' => True,
                                                                    'value' => function ($model
                                                                    ) {
                                                                        return $model->trunk_grp_status
                                                                        == '1'
                                                                            ? '<span class="new badge gradient-45deg-cyan-light-green" data-badge-caption="">' . CarriertrunkModule::t('carriertrunk', 'active') . '</span>'
                                                                            : '<span class="new badge gradient-45deg-red-pink" data-badge-caption="">' . CarriertrunkModule::t('carriertrunk', 'inactive') . '</span>';
                                                                    },
                                                                ],
                                                                [
                                                                    'attribute' => 'trunk_grp_name',
                                                                    'headerOptions' => ['class' => 'center'],
                                                                    'contentOptions' => ['class' => 'center'],
                                                                    'enableSorting' => TRUE,
                                                                ],
                                                                [
                                                                    'attribute' => 'trunk_grp_desc',
                                                                    'format' => 'raw',
                                                                    'headerOptions' => ['class' => 'center'],
                                                                    'contentOptions' => ['class' => 'center'],
                                                                    'enableSorting' => True,
                                                                    'value' => function ($model) {
                                                                        return $model->trunk_grp_desc != ''
                                                                            ?
                                                                            '<span title="' . $model->trunk_grp_desc . '">'
                                                                            . substr($model->trunk_grp_desc, 0, 30)
                                                                            . ((strlen($model->trunk_grp_desc) > 30) ? '...' : '') . '</span>'
                                                                            : '-';
                                                                    },
                                                                ],


                                                            ],
                                                            'tableOptions' => [
                                                                'class' => 'display dataTable dtr-inline',

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
<?php Pjax::end();
