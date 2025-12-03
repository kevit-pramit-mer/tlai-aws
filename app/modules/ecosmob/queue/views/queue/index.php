<?php

use app\modules\ecosmob\queue\QueueModule;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;
use app\modules\ecosmob\queue\assets\QueueAsset;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\ecosmob\queue\models\QueueMasterSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = QueueModule::t('queue', 'q');
$this->params['breadcrumbs'][] = $this->title;
$this->params['pageHead'] = $this->title;
$permissions = $GLOBALS['permissions'];

QueueAsset::register($this);
?>
<?php Pjax::begin(['id' => 'queue-index', 'timeout' => 0, 'enablePushState' => false]); ?>
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
                                                    <?php if (in_array('/queue/queue/create', $permissions)) { ?>
                                                        <?= Html::a(QueueModule::t('queue', 'add_new'),
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
                                                    echo GridView::widget([
                                                        'id' => 'queue-master-grid-index',
                                                        'dataProvider' => $dataProvider,
                                                        'layout' => Yii::$app->layoutHelper->get_layout_str('#queue-master-search-form'),
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
                                                                'template' => '{update}{delete}',
                                                                'header' => Yii::t('app',
                                                                    'action'),
                                                                'headerOptions' => ['class' => 'center width-10'],
                                                                'contentOptions' => ['class' => 'center width-10'],
                                                                'buttons' => [
                                                                    'update' => function (
                                                                        $url, $model
                                                                    ) use ($permissions) {
                                                                        if (in_array('/queue/queue/update', $permissions)) {
                                                                            return (1
                                                                                ? Html::a('<i class="material-icons">edit</i>',
                                                                                    $url,
                                                                                    [
                                                                                        'style' => '',
                                                                                        'title' => Yii::t('app',
                                                                                            'update'),
                                                                                    ]) : '');

                                                                        } else {
                                                                            return '';
                                                                        }
                                                                    },
                                                                    'delete' => function (
                                                                        $url,
                                                                        $model
                                                                    ) use ($permissions) {
                                                                        if (in_array('/queue/queue/delete', $permissions)) {
                                                                            $canDelete = $model->canDelete($model->qm_id);
                                                                            if ($canDelete) {
                                                                                $canNotDelete = QueueModule::t('queue', 'can_not_delete_assign_to_campaign');
                                                                                return '<a disabled class="ml-5 opacity5" title="' . $canNotDelete . '"><i class="material-icons">delete</i></a>';
                                                                            } else {
                                                                                return Html::a('<i class="material-icons">delete</i>',
                                                                                    $url,
                                                                                    [

                                                                                        'class' => 'ml-5',
                                                                                        'data-pjax' => 0,
                                                                                        'style' => 'color:#FF4B56',
                                                                                        'data-confirm' => Yii::t('app',
                                                                                            'delete_confirm'),
                                                                                        'data-method' => 'post',
                                                                                        'title' => Yii::t('app',
                                                                                            'delete'),
                                                                                    ]);
                                                                            }
                                                                        }else{
                                                                            return '';
                                                                        }
                                                                    },
                                                                ],
                                                            ],

                                                            [
                                                                'attribute' => 'qm_name',
                                                                'enableSorting' => True,
                                                                'headerOptions' => ['class' => 'text-center'],
                                                                'contentOptions' => ['class' => 'text-center'],
                                                                'value' => function ($model) {
                                                                    $qm_name = $model->getQueueName($model->qm_name);
                                                                    return $qm_name;
                                                                },
                                                            ],
                                                            [
                                                                'attribute' => 'qm_extension',
                                                                'enableSorting' => True,
                                                                'headerOptions' => ['class' => 'text-center'],
                                                                'contentOptions' => ['class' => 'text-center'],
                                                            ],
                                                            [
                                                                'attribute' => 'qm_language',
                                                                'enableSorting' => True,
                                                                'headerOptions' => ['class' => 'text-center'],
                                                                'contentOptions' => ['class' => 'text-center'],
                                                                'value' => function ($model) {
                                                                    if ($model->qm_language == 'ENGLISH') {
                                                                        return Yii::t('app', 'english');
                                                                    } else {
                                                                        return Yii::t('app', 'spanish');
                                                                    }
                                                                }
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
