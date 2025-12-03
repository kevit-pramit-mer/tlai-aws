<?php

use app\components\CommonHelper;
use app\modules\ecosmob\supervisorsummary\SupervisorSummaryModule;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\ecosmob\supervisorsummary\models\UsersActivityLogSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = SupervisorSummaryModule::t('supervisorsummary', 'report');
$this->params['breadcrumbs'][] = $this->title;
$this->params['pageHead'] = $this->title;
?>
<?php Pjax::begin(['id' => 'supervisor-summary-index', 'timeout' => 0, 'enablePushState' => false]); ?>
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
                                                <div class="card-header-btns">
                                                    <?php /*= Html::a(SupervisorSummaryModule::t('supervisorsummary', 'export'), ['/supervisorsummary/supervisor-summary/export'], [
                                                        'id' => 'hov view_link',
                                                        'data-pjax' => '0',
                                                        'class' => 'exportbutton lead_group btn waves-effect waves-light darken-1 breadcrumbs-btn right',
                                                    ]) */?>
                                                    <button id="export-button" class="exportbutton lead_group btn waves-effect waves-light darken-1 breadcrumbs-btn right">
                                                        <?= SupervisorSummaryModule::t('supervisorsummary', 'export') ?>
                                                    </button>
                                                </div>
                                            </div>
                                            <?php
                                            echo GridView::widget([
                                                'id' => 'users-activity-log-grid-index', // TODO : Add Grid Widget ID
                                                'dataProvider' => $dataProvider,
                                                'layout' => Yii::$app->layoutHelper->get_layout_str('#users-activity-log-search-form'),
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
                                                    ['class' => 'yii\grid\SerialColumn'],
                                                    [
                                                        'attribute' => 'adm_firstname',
                                                        'headerOptions' => ['class' => 'text-center'],
                                                        'label' => SupervisorSummaryModule::t('supervisorsummary', 'name'),
                                                        'contentOptions' => ['class' => 'text-center'],
                                                        'enableSorting' => TRUE,
                                                        'value' => function ($model) {
                                                            if (!empty($model->adm_firstname)) {
                                                                return $model->adm_firstname . ' ' . $model->adm_lastname;
                                                            } else {
                                                                return '-';
                                                            }
                                                        }
                                                    ],

                                                    [
                                                        'attribute' => 'date',
                                                        'headerOptions' => ['class' => 'text-center'],
                                                        'label' => SupervisorSummaryModule::t('supervisorsummary', 'date'),
                                                        'contentOptions' => ['class' => 'text-center'],
                                                        'enableSorting' => true,
                                                        'value' => function ($model) {
                                                            return date('Y-m-d', strtotime(CommonHelper::tsToDt($model->login_time)));
                                                        }
                                                    ],
                                                    [
                                                        'attribute' => 'login_time',
                                                        'headerOptions' => ['class' => 'text-center'],
                                                        'label' => SupervisorSummaryModule::t('supervisorsummary', 'login_time'),
                                                        'contentOptions' => ['class' => 'text-center'],
                                                        'enableSorting' => TRUE,
                                                        'value' => function ($model) {
                                                            if (!empty($model->login_time)) {
                                                                return date('H:i:s', strtotime(CommonHelper::tsToDt($model->login_time)));
                                                            } else {
                                                                return '-';
                                                            }
                                                        }
                                                    ],

                                                    [
                                                        'attribute' => 'logout_time',
                                                        'headerOptions' => ['class' => 'text-center'],
                                                        'label' => SupervisorSummaryModule::t('supervisorsummary', 'logout_time'),
                                                        'contentOptions' => ['class' => 'text-center'],
                                                        'enableSorting' => TRUE,
                                                        'value' => function ($model) {
                                                            if ($model->logout_time != '0000-00-00 00:00:00') {
                                                                return date('H:i:s', strtotime(CommonHelper::tsToDt($model->logout_time)));
                                                            } else {
                                                                return '-';
                                                            }
                                                        }
                                                    ],

                                                    [
                                                        'attribute' => 'break_time',
                                                        'headerOptions' => ['class' => 'text-center'],
                                                        'label' => SupervisorSummaryModule::t('supervisorsummary', 'brk_time'),
                                                        'contentOptions' => ['class' => 'text-center'],
                                                        'enableSorting' => TRUE,
                                                        'value' => function ($model) {
                                                            return (!empty($model->break_time)) ? gmdate("H:i:s", $model->break_time) : '-';
                                                        },
                                                    ],

                                                    [
                                                        'attribute' => 'campaign_name',
                                                        'headerOptions' => ['class' => 'text-center'],
                                                        'label' => SupervisorSummaryModule::t('supervisorsummary', 'camp'),
                                                        'contentOptions' => ['class' => 'text-center'],
                                                        'enableSorting' => TRUE,
                                                        'value' => function ($model) {
                                                            return (!empty($model->campaign_name)) ? $model->campaign_name : '-';
                                                        },
                                                    ],
                                                ],
                                                'tableOptions' => [
                                                    'class' => 'display dataTable dtr-inline providercount',
                                                    'data-count' => $dataProvider->getTotalCount(),
                                                ],
                                            ]);
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
            alert('" . SupervisorSummaryModule::t('supervisorsummary', 'no_records_found_to_export') . "');
            return false;
        }else{
            event.preventDefault(); 
            window.location.href = '".Url::to(['/supervisorsummary/supervisor-summary/export'])."';
        }
    });");
?>

