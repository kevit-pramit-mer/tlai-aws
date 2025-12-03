<?php

use app\modules\ecosmob\enterprisePhonebook\EnterprisePhonebookModule;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\ecosmob\enterprisePhonebook\models\EnterprisePhonebookSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = EnterprisePhonebookModule::t('app', 'enterprise_phonebook');
$this->params['breadcrumbs'][] = $this->title;
$this->params['pageHead'] = $this->title;
?>
<?= Yii::$app->view->renderFile('@app/views/auth/iframe/header.php') ?>
<div id="main" class="extension-main main-full">
    <div class="row">
        <div class="col s12">
            <div class="container">
                <div class="content-wrapper-before"></div>
                <div class="breadcrumbs-dark col s12 m6" id="breadcrumbs-wrapper">
                    <h5 class="breadcrumbs-title mt-0 mb-0"><?= (isset($this->params['pageHead']) ? $this->params['pageHead'] : "") ?></h5>
                    <?= Breadcrumbs::widget([
                        'tag' => 'ol',
                        'options' => ['class' => 'breadcrumbs mb-0'/*, 'target' => 'myFrame'*/],
                        'itemTemplate' => "<li class='breadcrumb-item'>{link}</li>\n",
                        'homeLink' => [
                            'label' => Yii::t('yii', 'Home'),
                            'url' => Url::to(['/extension/extension/dashboard']),
                            //'url' => 'javascript:void(0);',
                            'target' => "extensionFrame",
                            'encode' => false
                        ],
                        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                    ]) ?>
                </div>
                <div class="row">
                    <div class="col-xl-9 col-md-12 col-xs-12">
                        <div class="row">
                            <div class="col s12">
                                <div class="profile-contain">
                                    <div class="section section-data-tables">
                                        <div class="row">
                                            <div class="col s12 search-filter">
                                                <?= $this->render('_viewsearch', ['model' => $searchModel]); ?>
                                            </div>
                                            <div class="col s12">
                                                <div class="card table-structure">
                                                    <div class="card-content">
                                                        <div class="card-header d-flex align-items-center justify-content-between w-100">
                                                            <div class="header-title">
                                                                <?= $this->title ?>
                                                            </div>
                                                            <div class="card-header-btns">
                                                                <?php if (!empty($dataProvider->models)) { ?>
                                                                <?= Html::a(EnterprisePhonebookModule::t('app', 'export'), ['export'], [
                                                                    'id' => 'hov',
                                                                    'data-pjax' => 0,
                                                                    'class' => 'exportbutton lead_group mr-2 btn waves-effect waves-light darken-1 breadcrumbs-btn right',
                                                                ]) ?>
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
                                                                                    return $model->extension->em_extension_name . " - " . $model->extension->em_extension_number;
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
                                                                            'attribute' => 'en_phone',
                                                                            'headerOptions' => ['class' => 'text-center'],
                                                                            'contentOptions' => ['class' => 'text-center'],
                                                                            'enableSorting' => TRUE,
                                                                            'value' => function ($model) {
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
            </div>
        </div>
    </div>
</div>
<div class="col s12 m7 pt-1 pb-1 pr-0 mob-m">

</div>


<?php
$this->registerJs("
    $(document).on('click', '.exportbutton', function () {
         var count = ((!$('.providercount').data('count')) ? 0 : $('.providercount').data('count'));
        return checkCount(count, '" . Yii::t('app', 'no_records_found_to_export') . "');
    });");
?>

