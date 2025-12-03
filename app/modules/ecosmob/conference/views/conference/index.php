<?php

use app\modules\ecosmob\audiomanagement\models\AudioManagement;
use app\modules\ecosmob\conference\ConferenceModule;
use app\modules\ecosmob\conference\models\ConferenceMaster;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\ecosmob\conference\models\ConferenceMasterSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'conferences');
$this->params['breadcrumbs'][] = $this->title;
$this->params['pageHead'] = $this->title;
$permissions = $GLOBALS['permissions'];
?>
<?php Pjax::begin(['id' => 'conference-index', 'timeout' => 0, 'enablePushState' => false]); ?>
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
                                                <?php if (in_array('/conference/conference/create', $permissions)) { ?>
                                                    <?= Html::a(ConferenceModule::t('conference', 'add_new'),
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
                                                        'id' => 'conference-master-grid-index',
                                                        'dataProvider' => $dataProvider,
                                                        'layout' => Yii::$app->layoutHelper->get_layout_str('#conference-master-search-form'),
                                                        'showOnEmpty' => TRUE,
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
                                                                'template' => '{update}{delete}{configuration}',
                                                                'header' => Yii::t('app', 'action'),
                                                                'headerOptions' => ['class' => 'center width-10'],
                                                                'contentOptions' => ['class' => 'center width-10'],
                                                                'buttons' => [
                                                                    'update' => function ($url) use ($permissions) {
                                                                        if (in_array('/conference/conference/update', $permissions)) {
                                                                            return (1 ? Html::a('<i class="material-icons">edit</i>',
                                                                                $url,
                                                                                [
                                                                                    'style' => '',
                                                                                    'title' => Yii::t('app', 'update'),
                                                                                    'data-action' => 'edit',
                                                                                ]) : '');
                                                                        } else {
                                                                            return '';
                                                                        }
                                                                    },
                                                                    'delete' => function ($url) use ($permissions) {
                                                                        if (in_array('/conference/conference/delete', $permissions)) {
                                                                            return (1 ? Html::a('<i class="material-icons">delete</i>',
                                                                                $url,
                                                                                [

                                                                                    'class' => 'ml-5',
                                                                                    'data-pjax' => 0,
                                                                                    'style' => 'color:#FF4B56',
                                                                                    'data-confirm' => Yii::t('app', 'delete_confirm'),
                                                                                    'data-method' => 'post',
                                                                                    'title' => Yii::t('app', 'delete'),
                                                                                    'data-action' => 'delete',
                                                                                ]) : '');
                                                                        } else {
                                                                            return '';
                                                                        }
                                                                    },
                                                                    'configuration' => function ($url) use ($permissions) {
                                                                        if (in_array('/conference/conference/configuration', $permissions)) {
                                                                            return (1 ? Html::a('<i class="material-icons">settings</i>',
                                                                                $url,
                                                                                [

                                                                                    'class' => 'ml-5',
                                                                                    'data-pjax' => 0,
                                                                                    'style' => '',
                                                                                    'data-content' => ConferenceModule::t('conference', 'configuration'),
                                                                                    //                                                                            'data-method' => 'post',
                                                                                    'title' => ConferenceModule::t('conference', 'configuration'),
                                                                                    'data-action' => 'configuration',
                                                                                ]) : '');
                                                                        } else {
                                                                            return '';
                                                                        }
                                                                    },
                                                                ],
                                                            ],

                                                            [
                                                                'attribute' => 'cm_status',
                                                                'headerOptions' => ['class' => 'center'],
                                                                'contentOptions' => ['class' => 'center'],
                                                                'format' => 'raw',
                                                                'enableSorting' => True,
                                                                'value' => function ($model) {
                                                                    if ($model->cm_status == '1') {
                                                                        return '<span class="new badge gradient-45deg-cyan-light-green" data-badge-caption="">' . Yii::t('app', 'active') . '</span>';
                                                                    } else {
                                                                        return '<span class="new badge gradient-45deg-red-pink" data-badge-caption="">' . Yii::t('app', 'inactive') . '</span>';
                                                                    }

                                                                },
                                                            ],
                                                            [
                                                                'attribute' => 'cm_name',
                                                                'headerOptions' => ['class' => 'center'],
                                                                'contentOptions' => ['class' => 'center'],
                                                                'enableSorting' => True,
                                                                'value' => function ($model) {
                                                                    $cm_name = ConferenceMaster::getConferenceName($model->cm_name);
                                                                    return $cm_name;
                                                                },

                                                            ],
                                                            [
                                                                'attribute' => 'cm_extension',
                                                                'headerOptions' => ['class' => 'center'],
                                                                'contentOptions' => ['class' => 'center'],
                                                                'enableSorting' => True,
                                                            ],
                                                            [
                                                                'attribute' => 'cm_part_code',
                                                                'headerOptions' => ['class' => 'center'],
                                                                'contentOptions' => ['class' => 'center'],
                                                                'enableSorting' => True,
                                                                'value' => function ($model) {
                                                                    return (!empty($model->cm_part_code) ? $model->cm_part_code : '-');
                                                                },
                                                            ],
                                                            [
                                                                'attribute' => 'cm_mod_code',
                                                                'headerOptions' => ['class' => 'center'],
                                                                'contentOptions' => ['class' => 'center'],
                                                                'enableSorting' => True,
                                                                'value' => function ($model) {
                                                                    return (!empty($model->cm_mod_code) ? $model->cm_mod_code : '-');
                                                                },
                                                            ],
                                                            [
                                                                'attribute' => 'cm_moh',
                                                                'headerOptions' => ['class' => 'center'],
                                                                'contentOptions' => ['class' => 'center'],
                                                                'enableSorting' => True,
                                                                'value' => function ($model) {
                                                                    if ($model->cm_moh) {
                                                                        return AudioManagement::getPromptName($model->cm_moh);
                                                                    } else {
                                                                        return 'None';
                                                                    }

                                                                },
                                                            ],
                                                            [
                                                                'attribute' => 'cm_language',
                                                                'enableSorting' => True,
                                                                'value' => function ($model) {
                                                                    return (($model->cm_language == 'en') ? Yii::t('app', 'english') : Yii::t('app', 'spanish'));
                                                                },
                                                                'headerOptions' => ['class' => 'center'],
                                                                'contentOptions' => ['class' => 'center'],
                                                            ],
                                                        ],
                                                        'tableOptions' => [
                                                            'class' => 'display dataTable dtr-inline confernce-table',

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
