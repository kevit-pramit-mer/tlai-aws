<?php

use app\assets\AuthAsset;
use app\modules\ecosmob\timecondition\TimeConditionModule;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\ecosmob\timecondition\models\TimeConditionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = TimeConditionModule::t('tc', 'time_conditions');
$this->params['breadcrumbs'][] = $this->title;
$this->params['pageHead'] = $this->title;

AuthAsset::register($this);

$permissions = $GLOBALS['permissions'];
?>

    <div class="col s12 m7 pt-1 pb-1 pr-0 mob-m">
        <?php if (in_array('/timecondition/time-condition/create', $permissions)) { ?>
            <?= Html::a(TimeConditionModule::t('tc', 'add_new'), ['create'], [
                'id' => 'hov',
                'data-pjax' => 0,
                'class' => 'btn waves-effect waves-light darken-1 breadcrumbs-btn right',
            ]) ?>
        <?php } ?>
    </div>
<?php Pjax::begin(['id' => 'time-condition-index', 'timeout' => 0, 'enablePushState' => false]); ?>
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
                                            <div class="dataTables_wrapper" id="page-length-option_wrapper">

                                                <?php try {
                                                    echo GridView::widget([
                                                        'id' => 'time-condition-index',
                                                        'dataProvider' => $dataProvider,
                                                        'showOnEmpty' => true,
                                                        'pager' => [
                                                            'prevPageLabel' => '<a class="paginate_button previous disabled" aria-controls="data-table-row-grouping" data-dt-idx="0" tabindex="0" id="data-table-row-grouping_previous">' . Yii::t('app', 'previous') . '</a>',
                                                            'nextPageLabel' => '<a class="paginate_button next" aria-controls="data-table-row-grouping" data-dt-idx="4" tabindex="0" id="data-table-row-grouping_next">' . Yii::t('app', 'next') . '</a>',
                                                            'maxButtonCount' => 5,
                                                        ],
                                                        'layout' => Yii::$app->layoutHelper->get_layout_str('#time-condition-search-form'),
                                                        'options' => [
                                                            'tag' => false,
                                                        ],
                                                        'columns' => [
                                                            [
                                                                'class' => 'yii\grid\ActionColumn',
                                                                'template' => '{update}{delete}',
                                                                'header' => Yii::t('app', 'action'),
                                                                'headerOptions' => ['class' => 'center'],
                                                                'contentOptions' => ['class' => 'center'],
                                                                'buttons' => [
                                                                    'update' => function ($url) use ($permissions) {
                                                                        if (in_array('/timecondition/time-condition/update', $permissions)) {
                                                                            return (1 ? Html::a('<i class="material-icons color-orange">edit</i>',
                                                                                $url,
                                                                                [
                                                                                    'title' => Yii::t('app', 'update')
                                                                                ]) : '');
                                                                        }else{
                                                                            return '';
                                                                        }
                                                                    },
                                                                    'delete' => function ($url, $model) use ($permissions) {
                                                                        if (in_array('/timecondition/time-condition/delete', $permissions)) {
                                                                            $canDelete = $model->canDelete($model->tc_id);
                                                                            if ($canDelete) {
                                                                                $canNotDeleteMsg = TimeConditionModule::t
                                                                                ('tc', 'can_not_delete_assign_to_job');
                                                                                return '<a disabled name="login-button"  class="ml-5 opacity5"  title="' . $canNotDeleteMsg . '"><i class="material-icons">delete</i></a>';
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
                                                                                        'title' => Yii::t('app', 'delete')
                                                                                    ]);
                                                                            }
                                                                        }else{
                                                                            return '';
                                                                        }
                                                                    },
                                                                ],
                                                            ],
                                                            [
                                                                'attribute' => 'tc_name',
                                                                'enableSorting' => True,
                                                            ],
                                                            [
                                                                'attribute' => 'tc_start_month',
                                                                'enableSorting' => True,
                                                            ],
                                                            [
                                                                'attribute' => 'tc_end_month',
                                                                'enableSorting' => True,
                                                            ],
                                                            [
                                                                'attribute' => 'tc_start_date',
                                                                'enableSorting' => True,
                                                            ],
                                                            [
                                                                'attribute' => 'tc_end_date',
                                                                'enableSorting' => True,
                                                            ],
                                                            [
                                                                'attribute' => 'tc_start_day',
                                                                'enableSorting' => True,
                                                            ],
                                                            [
                                                                'attribute' => 'tc_end_day',
                                                                'enableSorting' => True,
                                                            ],
                                                            [
                                                                'attribute' => 'tc_start_time',
                                                                'enableSorting' => True,
                                                            ],
                                                            [
                                                                'attribute' => 'tc_end_time',
                                                                'enableSorting' => True,
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
