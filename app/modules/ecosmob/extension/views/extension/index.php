<?php

use app\modules\ecosmob\extension\extensionModule;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\helpers\Url;
use app\modules\ecosmob\extension\models\Extension;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\ecosmob\extension\models\ExtensionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = extensionModule::t('app', 'extensions');
$this->params['breadcrumbs'][] = $this->title;
$this->params['pageHead'] = $this->title;
$permissions = $GLOBALS['permissions'];
/*if (Yii::$app->session->get('isTragofone') == 1) {
    Extension::getTrgofoneStatus();
}*/
?>

<?php Pjax::begin(['id' => 'extension-index', 'timeout' => 0, 'enablePushState' => false]); ?>

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
                                                <?php if (in_array('/extension/extension/create', $permissions)) { ?>
                                                <?= Html::a(extensionModule::t('app', 'add_new'), ['create'], [
                                                'id' => 'hov',
                                                'data-pjax' => 0,
                                                'class' => 'btn waves-effect waves-light darken-1 breadcrumbs-btn right',
                                                ]) ?>
                                                <?php } ?>
                                                <?php if (in_array('/extension/extension/import', $permissions)) { ?>

                                                <?= Html::a(extensionModule::t('app', 'import'), ['import'], [
                                                'data-pjax' => 0,
                                                'class' => 'btn waves-effect waves-light darken-1 breadcrumbs-btn right',
                                                ]) ?>
                                                <?php } ?>
                                                <?php if (in_array('/extension/extension/export', $permissions)) { ?>

                                               <!-- --><?php /*= Html::a(extensionModule::t('app', 'export'), ['/extension/extension/export'], [
                                                'id' => 'hov view_link',
                                                'data-pjax' => '0',
                                                'class' => 'exportbutton lead_group btn waves-effect waves-light darken-1 breadcrumbs-btn right',
                                                ]) */?>

                                                    <button id="export-button" class="exportbutton lead_group btn waves-effect waves-light darken-1 breadcrumbs-btn right">
                                                        <?= extensionModule::t('app', 'Export') ?>
                                                    </button>

                                                <?php } ?>
                                            </div>
                                        </div>
                                        <div class="dataTables_wrapper" id="page-length-option_wrapper">
                                            <?= GridView::widget([
                                                'id' => 'extension-grid-index', // TODO : Add Grid Widget ID
                                                'dataProvider' => $dataProvider,
                                                'layout' => Yii::$app->layoutHelper->get_layout_str('#extension-search-form'),
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
                                                    [
                                                        'class' => 'yii\grid\ActionColumn',
                                                        'template' => '{update}{delete}',
                                                        'header' => Yii::t('app', 'action'),
                                                        'headerOptions' => ['class' => 'center'],
                                                        'contentOptions' => ['class' => 'center'],
                                                        'buttons' => [
                                                            'update' => function ($url) use ($permissions) {
                                                                if (in_array('/extension/extension/update', $permissions)) {
                                                                    return (1 ? Html::a('<i class="material-icons color-orange">edit</i>', $url, [
                                                                    'data-action' => 'edit',
                                                                    ]) : '');
                                                                } else {
                                                                    return '';
                                                                }
                                                            },
                                                            'delete' => function ($url) use ($permissions) {
                                                                if (in_array('/extension/extension/delete', $permissions)) {
                                                                    return (1 ? Html::a('<i class="material-icons">delete</i>', $url, [

                                                                        'class' => 'ml-5',
                                                                        'data-pjax' => 0,
                                                                        'style' => 'color:#FF4B56',
                                                                        'data-confirm' => Yii::t('app', 'delete_confirm'),
                                                                        'data-method' => 'post',
                                                                        'data-action' => 'delete',
                                                                    ]) : '');
                                                                } else {
                                                                    return '';
                                                                }
                                                            },
                                                           /* 'tragofone' => function ($url, $model) use ($permissions) {
                                                                if (in_array('/extension/extension/update', $permissions)) {
                                                                    if(Yii::$app->session->get('isTragofone') == 1) {
                                                                        return (1 ? Html::a('<i class="material-icons">'.($model->is_tragofone == '1' ? 'check_circle' : 'cancel').'</i>', $url, [

                                                                            'class' => 'ml-5',
                                                                            'data-pjax' => 0,
                                                                            'style' => 'color:#FF4B56',
                                                                            'title' => ($model->is_tragofone == '1' ? 'Disable' : 'Enable'),
                                                                            'data-method' => 'post',
                                                                        ]) : '');
                                                                    }else{
                                                                        return '';
                                                                    }
                                                                } else {
                                                                    return '';
                                                                }
                                                            },*/
                                                        ]
                                                    ],
                                                    [
                                                        'attribute' => 'em_status',
                                                        'headerOptions' => ['class' => 'center'],
                                                        'contentOptions' => ['class' => 'center'],
                                                        'format' => 'raw',
                                                        'enableSorting' => True,
                                                        'value' => function ($model) {
                                                            if ($model->em_status == 1) {
                                                                return '<span class="new badge gradient-45deg-cyan-light-green"
                                                                            data-badge-caption="">' . Yii::t('app', 'active')
                                                                    . '</span>';
                                                            } else {
                                                                return '<span class="new badge gradient-45deg-red-pink"
                                                                            data-badge-caption="">' . Yii::t('app', 'inactive') . '</span>';
                                                            }

                                                        },
                                                    ],
                                                    [
                                                        'attribute' => 'em_extension_number',
                                                        'headerOptions' => ['class' => 'text-center'],
                                                        'contentOptions' => ['class' => 'text-center'],
                                                        'enableSorting' => True,
                                                    ],
                                                    [
                                                        'attribute' => 'em_extension_name',
                                                        'headerOptions' => ['class' => 'text-center'],
                                                        'contentOptions' => ['class' => 'text-center'],
                                                        'enableSorting' => True,
                                                    ],

                                                    [
                                                        'attribute' => 'em_email',
                                                        'headerOptions' => ['class' => 'text-center'],
                                                        'contentOptions' => ['class' => 'text-center'],
                                                        'enableSorting' => True,
                                                    ],
                                                    [
                                                        'attribute' => 'ecs_multiple_registeration',
                                                        'enableSorting' => True,
                                                        'value' => function ($model) {
                                                            return ($model->callsettings['ecs_multiple_registeration'] == 1) ? Yii::t('app', 'active') : Yii::t('app', 'inactive');
                                                        },
                                                    ],
                                                    [
                                                        'attribute' => 'em_shift_id',
                                                        'value' => 'shift.sft_name',
                                                        'enableSorting' => True,
                                                    ],
                                                    [
                                                        'attribute' => 'ecs_fax2mail',
                                                        'enableSorting' => True,
                                                        'value' => function ($model) {
                                                            return ($model->callsettings['ecs_fax2mail'] == 1) ? Yii::t('app', 'active') : Yii::t('app', 'inactive');
                                                        },
                                                    ],
                                                    [
                                                        'attribute' => 'is_tragofone',
                                                        'format' => 'raw',
                                                        'value' => function ($model) {
                                                            if (Yii::$app->session->get('isTragofone') == 1) {
                                                                if ($model->is_tragofone == '1') {
                                                                    $status = '<span class="new badge gradient-45deg-cyan-light-green"
                                                                            data-badge-caption="">' . Yii::t('app', 'active')
                                                                        . '</span>';
                                                                } else {
                                                                    $status = '<span class="new badge gradient-45deg-red-pink"
                                                                            data-badge-caption="">' . Yii::t('app', 'inactive') . '</span>';
                                                                }
                                                                return (1 ? Html::a($status, Url::to(['/extension/extension/tragofone', 'id' => $model->em_id]), [

                                                                    'class' => 'ml-5',
                                                                    'data-pjax' => 0,
                                                                    'style' => 'color:#FF4B56',
                                                                    'title' => ($model->is_tragofone == '1' ? 'Disable' : 'Enable'),
                                                                    'data-method' => 'post',
                                                                ]) : '');
                                                            } else {
                                                                $status = '<span class="new badge gradient-45deg-red-pink"
                                                                            data-badge-caption="">' . Yii::t('app', 'inactive') . '</span>';
                                                                return $status;
                                                            }
                                                        },
                                                        'visible' => Yii::$app->session->get('isTragofone')
                                                    ]
                                                ],
                                                'tableOptions' => [
                                                    'class' => 'display dataTable dtr-inline providercount',
                                                    'data-count' => $dataProvider->getTotalCount(),
                                                ],
                                            ]); ?>
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
<?php
$this->registerJs("
    $(document).on('click', '.exportbutton', function () {
        var count = ((!$('.providercount').data('count')) ? 0 : $('.providercount').data('count'));
        if (count <= 0) {
            alert('" . extensionModule::t('app', 'No records found to export') . "');
            return false;
        }else{
            event.preventDefault(); 
            window.location.href = '".Url::to(['/extension/extension/export'])."';
        }
    });");
?>

