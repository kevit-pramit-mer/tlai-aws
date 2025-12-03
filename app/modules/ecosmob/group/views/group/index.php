<?php

use app\modules\ecosmob\group\GroupModule;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\ecosmob\group\models\GroupSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = GroupModule::t('group', 'grp');
$this->params['breadcrumbs'][] = $this->title;
$this->params['pageHead'] = $this->title;

$permissions = $GLOBALS['permissions'];
?>
<?php Pjax::begin(['id' => 'group-index', 'timeout' => 0, 'enablePushState' => false]); ?>
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
                                                <?php if (in_array('/group/group/create', $permissions)) { ?>
                                                    <?= Html::a(GroupModule::t('group', 'add_new'),
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
                                                        'id' => 'group-grid-index',
                                                        // TODO : Add Grid Widget ID
                                                        'dataProvider' => $dataProvider,
                                                        'layout' => Yii::$app->layoutHelper->get_layout_str('#group-search-form'),
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
                                                                        $url
                                                                    ) use ($permissions) {
                                                                        if (in_array('/group/group/update', $permissions)) {
                                                                            return (1
                                                                                ? Html::a('<i class="material-icons">edit</i>',
                                                                                    $url,
                                                                                    [
                                                                                        'style' => '',
                                                                                        'title' => Yii::t('app',
                                                                                            'update'),
                                                                                        'data-action' => 'edit',
                                                                                    ]) : '');
                                                                        }else{
                                                                            return '';
                                                                        }
                                                                    },
                                                                    'delete' => function (
                                                                        $url,
                                                                        $model
                                                                    ) use ($permissions) {
                                                                        if (in_array('/group/group/delete', $permissions)) {
                                                                            $canDelete = $model->canDelete($model->grp_id);
                                                                            if ($canDelete) {
                                                                                $canNotDeleteMsg = GroupModule::t('group', 'can_not_delete_assign_to_extension');
                                                                                return '<a disabled class="ml-5 opacity5"  title="' . $canNotDeleteMsg . '"><i class="material-icons">delete</i></a>';
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
                                                                                        'data-action' => 'delete',
                                                                                    ]);
                                                                            }
                                                                        }else{
                                                                            return '';
                                                                        }
                                                                    },
                                                                ],
                                                            ],
                                                            [
                                                                'attribute' => 'grp_name',
                                                                'headerOptions' => ['class' => 'center'],
                                                                'contentOptions' => ['class' => 'center'],
                                                                'enableSorting' => True,
                                                            ],
                                                            [
                                                                'attribute' => 'grp_description',
                                                                'headerOptions' => ['class' => 'center'],
                                                                'contentOptions' => ['class' => 'center'],
                                                                'enableSorting' => True,
                                                                'value' => function ($model) {
                                                                    return !empty($model->grp_description) ? (strlen($model->grp_description) > 30
                                                                        ? substr($model->grp_description, 0, 30) . "..." : $model->grp_description)
                                                                        : '-';
                                                                },
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
