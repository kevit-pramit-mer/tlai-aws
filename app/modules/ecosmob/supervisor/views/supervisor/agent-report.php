<?php

use app\modules\ecosmob\supervisor\SupervisorModule;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\ecosmob\supervisor\models\AdminMasterSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = SupervisorModule::t('supervisor', 'Agent/Supervisor Summary Report');
$this->params['breadcrumbs'][] = $this->title;
$this->params['pageHead'] = $this->title;
?>

<div class="row">
    <div class="col-xl-9 col-md-7 col-xs-12">
        <div class="row">
            <div class="col s12">
                <div class="profile-contain">
                    <div class="section section-data-tables">
                        <div class="row">
                            <div class="col s12">
                                <div class="card">
                                    <div class="card-content">
                                        <?= $this->render('_agent-search', ['model' => $searchModel]); ?>
                                        <div class="dataTables_wrapper" id="page-length-option_wrapper">
                                            <?php try {
                                                echo GridView::widget([
                                                    'id' => 'admin-master-grid-index', // TODO : Add Grid Widget ID
                                                    'dataProvider' => $dataProvider,
                                                    'layout' => Yii::$app->layoutHelper->get_layout_str('#admin-master-search-form'),
                                                    'showOnEmpty' => true,
                                                    'pager' => [
                                                        'prevPageLabel' => '<a class="paginate_button previous disabled" aria-controls="data-table-row-grouping" data-dt-idx="0" tabindex="0" id="data-table-row-grouping_previous">Previous</a>',
                                                        'nextPageLabel' => '<a class="paginate_button next" aria-controls="data-table-row-grouping" data-dt-idx="4" tabindex="0" id="data-table-row-grouping_next">Next</a>',
                                                        'maxButtonCount' => 5,
                                                    ],
                                                    'options' => [
                                                        'tag' => false,
                                                    ],
                                                    'columns' => [

                                                        [
                                                            'attribute' => 'adm_firstname',
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'label' => yii::t('app', 'Agent Name / Supervisor Name'),
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'enableSorting' => TRUE,
                                                            'value' => function ($model) {
                                                                return $model->adm_firstname . ' ' . $model->adm_lastname;
                                                            }
                                                        ],

                                                        [
                                                            'attribute' => 'date',
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'label' => yii::t('app', 'Date'),
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'enableSorting' => TRUE,
                                                        ],
                                                        [
                                                            'attribute' => 'login_time',
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'label' => yii::t('app', 'Login Time'),
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'enableSorting' => TRUE,
                                                        ],

                                                        [
                                                            'attribute' => 'break_time',
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'label' => yii::t('app', 'Break Time'),
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'enableSorting' => TRUE,
                                                            'value' => function ($model) {
                                                                return (!empty($model->break_time)) ? $model->break_time : '-';
                                                            },
                                                        ],

                                                        [
                                                            'attribute' => 'campaign_name',
                                                            'headerOptions' => ['class' => 'text-center'],
                                                            'label' => yii::t('app', 'Campaign'),
                                                            'contentOptions' => ['class' => 'text-center'],
                                                            'enableSorting' => TRUE,
                                                            'value' => function ($model) {
                                                                return (!empty($model->campaign_name)) ? $model->campaign_name : '-';
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

