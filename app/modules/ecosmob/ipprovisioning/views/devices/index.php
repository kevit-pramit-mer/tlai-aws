<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;
use app\modules\ecosmob\ipprovisioning\models\DevicesSearch;
use app\modules\ecosmob\ipprovisioning\IpprovisioningModule;

/* @var $this yii\web\View */
/* @var $searchModel DevicesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'devices');
$this->params['breadcrumbs'][] = $this->title;
$this->params['pageHead'] = $this->title;

$permissions = $GLOBALS['permissions'];
?>
<?php Pjax::begin(['id' => 'devices-index', 'timeout' => 0, 'enablePushState' => false]); ?>
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
                                                        <?= Html::a(IpprovisioningModule::t('app', 'add_new'),
                                                            ['create'],
                                                            [
                                                                'id' => 'hov',
                                                                'data-pjax' => 0,
                                                                'class' => 'btn waves-effect waves-light darken-1 breadcrumbs-btn right',
                                                            ]) ?>
                                                </div>
                                            </div>
                                            <div class="dataTables_wrapper" id="page-length-option_wrapper">
                                                <?php try {
                                                    echo GridView::widget([
                                                        'id' => 'devices-grid-index',
                                                        // TODO : Add Grid Widget ID
                                                        'dataProvider' => $dataProvider,
                                                        'layout' => Yii::$app->layoutHelper->get_layout_str('#devices-search-form'),
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
                                                                'template' => '{update}{delete}{settings}{reboot}{reset}',
                                                                'header' => Yii::t('app', 'action'),
                                                                'headerOptions' => ['class' => 'center width-10'],
                                                                'contentOptions' => ['class' => 'center width-10'],
                                                                'buttons' => [
                                                                    'update' => function ($url) use ($permissions) {
                                                                        return Html::a('<i class="material-icons">edit</i>',
                                                                            $url,
                                                                            [
                                                                                'style' => '',
                                                                                'title' => Yii::t('app',
                                                                                    'update'),
                                                                            ]);
                                                                    },
                                                                    'delete' => function ($url, $model) use ($permissions) {
                                                                        return Html::a('<i class="material-icons">delete</i>', $url, [
                                                                            'class' => 'ml-5',
                                                                            'data-pjax' => 0,
                                                                            'style' => 'color:#FF4B56',
                                                                            'data-confirm' => Yii::t('app',
                                                                                'delete_confirm'),
                                                                            'data-method' => 'post',
                                                                            'title' => Yii::t('app',
                                                                                'delete'),
                                                                        ]);
                                                                    },
                                                                    'settings' => function ($url) {
                                                                        return (1 ? Html::a('<i class="material-icons">settings</i>',
                                                                            $url,
                                                                            [
                                                                                'class' => 'ml-5',
                                                                                'style' => '',
                                                                                'title' => Yii::t('app', 'settings'),
                                                                            ]) : '');
                                                                    },
                                                                    'reboot' => function ($url) {
                                                                        return (1 ? Html::a('<i class="material-icons">refresh</i>',
                                                                            $url,
                                                                            [
                                                                                'class' => 'ml-5',
                                                                                'style' => '',
                                                                                'data-pjax' => 0,
                                                                                'data-confirm' => Yii::t('app', 'reboot_confirm'),
                                                                                'data-method' => 'post',
                                                                                'title' => IpprovisioningModule::t('app', 'reboot'),
                                                                            ]) : '');
                                                                    },
                                                                    'reset' => function ($url) {
                                                                        return (1 ? Html::a('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-clockwise" viewBox="0 0 16 16">
  <path fill-rule="evenodd" d="M8 3a5 5 0 1 0 4.546 2.914.5.5 0 0 1 .908-.417A6 6 0 1 1 8 2z"/>
  <path d="M8 4.466V.534a.25.25 0 0 1 .41-.192l2.36 1.966c.12.1.12.284 0 .384L8.41 4.658A.25.25 0 0 1 8 4.466"/>
</svg>',
                                                                            $url,
                                                                            [
                                                                                'class' => 'ml-5',
                                                                                'style' => '',
                                                                                'data-pjax' => 0,
                                                                                'data-confirm' => Yii::t('app', 'reset_confirm'),
                                                                                'data-method' => 'post',
                                                                                'title' => IpprovisioningModule::t('app', 'reset'),
                                                                            ]) : '');
                                                                    },
                                                                ],
                                                            ],
                                                            [
                                                                'attribute' => 'device_name',
                                                                'headerOptions' => ['class' => 'center'],
                                                                'contentOptions' => ['class' => 'center'],
                                                                'enableSorting' => True,
                                                            ],
                                                            [
                                                                'attribute' => 'mac_address',
                                                                'headerOptions' => ['class' => 'center'],
                                                                'contentOptions' => ['class' => 'center'],
                                                                'enableSorting' => True,
                                                            ],
                                                            [
                                                                'attribute' => 'brand_id',
                                                                'headerOptions' => ['class' => 'center'],
                                                                'contentOptions' => ['class' => 'center'],
                                                                'enableSorting' => True,
                                                                'value' => function($model){
                                                                    return (!empty($model->brand) ? $model->brand->pv_name : '-');
                                                                }
                                                            ],
                                                            [
                                                                'attribute' => 'template_master_id',
                                                                'headerOptions' => ['class' => 'center'],
                                                                'contentOptions' => ['class' => 'center'],
                                                                'enableSorting' => True,
                                                                'value' => function($model){
                                                                    return (!empty($model->template) ? $model->template->template_name : '-');
                                                                }
                                                            ],
                                                            [
                                                                'attribute' => 'model_id',
                                                                'headerOptions' => ['class' => 'center'],
                                                                'contentOptions' => ['class' => 'center'],
                                                                'enableSorting' => True,
                                                                'value' => function($model){
                                                                    return (!empty($model->phoneModel) ? $model->phoneModel->p_model : '-');
                                                                }
                                                            ],
                                                            [
                                                                'attribute' => 'provisioning_status',
                                                                'headerOptions' => ['class' => 'center'],
                                                                'contentOptions' => ['class' => 'center'],
                                                                'format' => 'raw',
                                                                'enableSorting' => True,
                                                                'value' => function ($model) {
                                                                    if ($model->provisioning_status == 1) {
                                                                        return '<span class="new badge gradient-45deg-cyan-light-blue"
                                                                            data-badge-caption="">' . Yii::t('app', 'inprogress')
                                                                            . '</span>';
                                                                    } elseif ($model->provisioning_status == 2) {
                                                                        return '<span class="new badge gradient-45deg-cyan-light-green"
                                                                            data-badge-caption="">' . Yii::t('app', 'completed')
                                                                            . '</span>';
                                                                    } else {
                                                                        return '-';
                                                                    }

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
