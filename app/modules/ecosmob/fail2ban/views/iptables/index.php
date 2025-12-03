<?php

use app\modules\ecosmob\fail2ban\Fail2banModule;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;
use app\modules\ecosmob\fail2ban\assets\Fail2banAsset;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\ecosmob\iptable\models\IpTableSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Fail2banModule::t('fail2ban', 'fail2ban');
$this->params['breadcrumbs'][] = $this->title;
$this->params['pageHead'] = $this->title;

$permissions = $GLOBALS['permissions'];

Fail2banAsset::register($this);
?>
<?php Pjax::begin(['id' => 'fail2-ban-index', 'timeout' => 0, 'enablePushState' => false]); ?>

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
                                                    'id' => 'ip-table-grid-index',
                                                    'dataProvider' => $dataProvider,
                                                    'layout' => Yii::$app->layoutHelper->get_layout_str('#ip-table-search-form'),
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
                                                            'template' => '{delete}',
                                                            'header' => Yii::t('app', 'action'),
                                                            'headerOptions' => ['class' => 'center width-10'],
                                                            'contentOptions' => ['class' => 'center width-10'],
                                                            'buttons' => [

                                                                'delete' => function ($url, $model) {
                                                                    $permissions = $GLOBALS['permissions'];
                                                                    if (in_array('/fail2ban/iptables/delete', $permissions)) {
                                                                        return (1 ? Html::a('<i class="material-icons">delete</i>',
                                                                            $url,
                                                                            [

                                                                                'class' => 'ml-5',
                                                                                'data-pjax' => 0,
                                                                                'style' => 'color:#FF4B56',
                                                                                'data-confirm' => Yii::t('app', 'delete_confirm'),
                                                                                'data-method' => 'post',
                                                                                'title' => Yii::t('app', 'delete'),
                                                                            ]) : '');
                                                                    }
                                                                },
                                                            ],
                                                        ],

                                                        [
                                                            'attribute' => 'bw_rule_value',
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'enableSorting' => True,
                                                        ],
                                                        [
                                                            'attribute' => 'ports',
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'enableSorting' => True,
                                                        ],
                                                        [
                                                            'attribute' => 'protocol',
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'enableSorting' => True,
                                                        ],
                                                        [
                                                            'attribute' => 'jail',
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
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
<?php Pjax::end(); ?>
