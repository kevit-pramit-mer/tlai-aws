<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\modules\ecosmob\enterprisePhonebook\EnterprisePhonebookModule;
use yii\widgets\Pjax;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\ecosmob\enterprisePhonebook\models\EnterprisePhonebookSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = EnterprisePhonebookModule::t('app', 'enterprise_phonebook');
$this->params['breadcrumbs'][] = $this->title;
$this->params['pageHead'] = $this->title;

$permissions = $GLOBALS['permissions'];
?>

<?php Pjax::begin(['id' => 'enterprise-phonebook-index', 'timeout' => 0, 'enablePushState' => false]); ?>
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
                                                    <?php if (in_array('/enterprisePhonebook/enterprise-phonebook/create', $permissions)) { ?>
                                                    <?= Html::a(EnterprisePhonebookModule::t('app', 'add_new'), ['create'], [
                                                        'id' => 'phonebook-grid-index',
                                                        'data-pjax' => '0',
                                                        'class' => 'btn waves-effect waves-light darken-1 breadcrumbs-btn right',
                                                    ]) ?>
                                                    <?php } ?>
                                                    <?php if (in_array('/enterprisePhonebook/enterprise-phonebook/create', $permissions)) { ?>
                                                    <?= Html::a(EnterprisePhonebookModule::t('app', 'import'), ['import'], [
                                                        'data-pjax' => '0',
                                                        'class' => 'btn waves-effect waves-light darken-1 breadcrumbs-btn right',
                                                    ]) ?>
                                                    <?php } ?>
                                                    <?php if (in_array('/enterprisePhonebook/enterprise-phonebook/index', $permissions)) { ?>
                                                        <?php /*= Html::a(EnterprisePhonebookModule::t('app', 'export'), ['export'], [
                                                            'id' => 'hov view_link',
                                                            'data-pjax' => '0',
                                                            'class' => 'exportbutton lead_group mr-2 btn waves-effect waves-light darken-1 breadcrumbs-btn right',
                                                        ]) */?>
                                                        <button id="export-button" class="exportbutton lead_group btn waves-effect waves-light darken-1 breadcrumbs-btn right">
                                                            <?= EnterprisePhonebookModule::t('app', 'Export') ?>
                                                        </button>
                                                    <?php } ?>
                                                </div>
                                        </div>
                                        <div class="dataTables_wrapper" id="page-length-option_wrapper">
                                            <?php try {
                                                echo GridView::widget([
                                                    'id' => 'enterprise-phonebook-grid-index', // TODO : Add Grid Widget ID
                                                    'dataProvider' => $dataProvider,
                                                    'layout' => Yii::$app->layoutHelper->get_layout_str('#enterprise-phonebook-search-form'),
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
                                                            'headerOptions' => ['class' => 'center width-10'],
                                                            'contentOptions' => ['class' => 'center width-10'],
                                                            'buttons' => [
                                                                'update' => function ($url) use ($permissions){
                                                                    if (in_array('/enterprisePhonebook/enterprise-phonebook/update', $permissions)) {
                                                                        return (1 ? Html::a('<i class="material-icons">edit</i>',
                                                                            $url, [
                                                                                'style' => '',
                                                                                'title' => Yii::t('app', 'update'),
                                                                                'data-action' => 'edit',
                                                                            ]) : '');
                                                                    }else{
                                                                        return '';
                                                                    }
                                                                },
                                                                'delete' => function ($url) use ($permissions){
                                                                    if (in_array('/enterprisePhonebook/enterprise-phonebook/delete', $permissions)) {
                                                                        return (1 ? Html::a('<i class="material-icons">delete</i>',
                                                                            $url, [
                                                                                'class' => 'ml-5',
                                                                                'data-pjax' => 0,
                                                                                'style' => 'color:#FF4B56',
                                                                                'data-confirm' => Yii::t('app',
                                                                                    'delete_confirm'),
                                                                                'data-method' => 'post',
                                                                                'title' => Yii::t('app', 'delete'),
                                                                                'data-action' => 'delete',
                                                                            ]) : '');
                                                                    }else{
                                                                        return '';
                                                                    }
                                                                },
                                                            ]
                                                        ],
                                                        [
                                                            'attribute' => 'en_first_name',
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'enableSorting' => TRUE,
                                                            'value' => function ($model) {
                                                                if (!empty($model->en_first_name)) {
                                                                    return $model->en_first_name;
                                                                } else {
                                                                    return "-";
                                                                }

                                                            }

                                                        ],
                                                        [
                                                            'attribute' => 'en_last_name',
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'enableSorting' => TRUE,
                                                            'value' => function ($model) {
                                                                if (!empty($model->en_last_name)) {
                                                                    return $model->en_last_name;
                                                                } else {
                                                                    return "-";
                                                                }

                                                            }
                                                        ],
                                                        [
                                                            'attribute' => 'en_extension',
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'enableSorting' => TRUE,
                                                            'value' => function ($model) {
                                                                if (!empty($model->extension)) {
                                                                    return $model->extension->em_extension_name." - ".$model->extension->em_extension_number;
                                                                } else {
                                                                    return '-';

                                                                }

                                                            }
                                                        ],
                                                        [
                                                            'attribute' => 'en_mobile',
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'enableSorting' => TRUE,
                                                            'value' => function ($model) {
                                                                if (!empty($model->en_mobile)) {
                                                                    return $model->en_mobile;
                                                                } else {
                                                                    return "-";
                                                                }

                                                            }
                                                        ],
                                                        [
                                                            'attribute'=>'en_phone',
                                                            'headerOptions'=>['class'=>'text-center'],
                                                            'contentOptions'=>['class'=>'text-center'],
                                                            'enableSorting'=> TRUE,
                                                            'value'=>function ($model) {
                                                                if (!empty($model->en_phone)) {
                                                                    return $model->en_phone;
                                                                } else {
                                                                    return "-";
                                                                }

                                                            }
                                                        ],
                                                        [
                                                            'attribute' => 'en_email_id',
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'enableSorting' => TRUE,
                                                            'value' => function ($model) {
                                                                if (!empty($model->en_email_id)) {
                                                                    return $model->en_email_id;
                                                                } else {
                                                                    return "-";
                                                                }

                                                            }
                                                        ],
                                                    ],
                                                    'tableOptions' => [
                                                        'class' => 'display dataTable dtr-inline providercount',
                                                        'data-count' => $dataProvider->getTotalCount(),
                                                    ],
                                                ]);
                                            } catch (Exception $e) {
                                            }
                                            ?>
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
            alert('" . Yii::t('app', 'No records found to export') . "');
            return false;
        }else{
            event.preventDefault(); 
            window.location.href = '".Url::to(['/enterprisePhonebook/enterprise-phonebook/export'])."';
        }
    });");
?>

