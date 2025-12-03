<?php

use app\modules\ecosmob\feature\FeatureModule;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;
use app\modules\ecosmob\feature\assets\FeatureAsset;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = FeatureModule::t('feature', 'feature_code');
$this->params['breadcrumbs'][] = $this->title;
$this->params['pageHead'] = $this->title;
$permissions = $GLOBALS['permissions'];

FeatureAsset::register($this);
?>
<?php Pjax::begin(['id' => 'feature-index', 'timeout' => 0, 'enablePushState' => false]); ?>

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
                                        </div>
                                        <div class="dataTables_wrapper" id="page-length-option_wrapper">

                                            <?php try {
                                                echo GridView::widget([
                                                    'id' => 'feature-grid-index',
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
                                                            'template' => '{update}',
                                                            'header' => Yii::t('app',
                                                                'action'),
                                                            'headerOptions' => ['class' => 'center width-10'],
                                                            'contentOptions' => ['class' => 'center width-10'],
                                                            'buttons' => [
                                                                'update' => function (
                                                                    $url
                                                                ) use ($permissions) {
                                                                    if (in_array('/feature/feature/update', $permissions)) {
                                                                        return (1
                                                                            ? Html::a('<i class="material-icons">edit</i>',
                                                                                $url,
                                                                                [
                                                                                    'title' => Yii::t('app',
                                                                                        'update'),
                                                                                    'data-action' => 'edit',
                                                                                ]) : '');
                                                                    }else{
                                                                        return '';
                                                                    }
                                                                },
                                                            ],
                                                        ],

                                                        [
                                                            'attribute' => 'feature_name',
                                                            'headerOptions' => ['class' => 'center'],
                                                            'contentOptions' => ['class' => 'center'],
                                                            'enableSorting' => TRUE,
                                                        ],
                                                        [
                                                            'attribute' => 'feature_code',
                                                            'headerOptions' => ['class' => 'center'],
                                                            'contentOptions' => ['class' => 'center'],
                                                            'enableSorting' => TRUE,
                                                        ],
                                                        [
                                                            'attribute' => 'feature_desc',
                                                            'headerOptions' => ['class' => 'center'],
                                                            'contentOptions' => ['class' => 'center'],
                                                            'enableSorting' => True,
                                                            'value' => function ($model) {
                                                                return !empty($model->feature_desc) ? (strlen($model->feature_desc) > 30
                                                                    ? substr($model->feature_desc, 0, 30) . "..." : $model->feature_desc)
                                                                    : '-';
                                                            }
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

