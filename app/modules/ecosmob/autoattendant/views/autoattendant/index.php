<?php /** @noinspection PhpUndefinedFieldInspection */

use app\modules\ecosmob\audiomanagement\models\AudioManagement;
use app\modules\ecosmob\autoattendant\AutoAttendantModule;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\ecosmob\autoattendant\models\AutoAttendantMasterSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = AutoAttendantModule::t('autoattendant', 'auto_attendant');
$this->params['breadcrumbs'][] = $this->title;
$this->params['pageHead'] = $this->title;
$permissions = $GLOBALS['permissions'];
?>
<?php Pjax::begin(['id' => 'autoattendent-index', 'timeout' => 0, 'enablePushState' => false]); ?>
<div class="row">
    <div class="col-xl-9 col-md-7 col-xs-12">
        <div class="row">
            <div class="col s12">
                <div class="profile-contain">
                    <div class="section section-data-tables">
                        <div class="row">
                            <div class="col s12 search-filter">
                                <?= $this->render('search/_search', ['model' => $searchModel]); ?>
                            </div>
                            <div class="col s12">
                                <div class="card table-structure">
                                    <div class="card-content">
                                        <div class="card-header d-flex align-items-center justify-content-between w-100">
                                            <div class="header-title">
                                                <?= $this->title ?>
                                            </div>
                                            <div class="card-header-btns">
                                                <?php if (in_array('/autoattendant/autoattendant/create', $permissions)) { ?>
                                                    <?= Html::a(AutoAttendantModule::t('autoattendant', 'add_new'),
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
                                                    'id' => 'auto-attendant-master-grid-index',
                                                    'dataProvider' => $dataProvider,
                                                    'layout' => Yii::$app->layoutHelper->get_layout_str('#auto-attendant-master-search-form'),
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
                                                            'template' => '{settings} {delete}',
                                                            'header' => Yii::t('app', 'action'),
                                                            'headerOptions' => ['class' => 'center'],
                                                            'contentOptions' => ['class' => 'center'],
                                                            'buttons' => [
                                                                'update' => function ($url) {
                                                                    return (1 ? Html::a('<i class="material-icons">edit</i>',
                                                                        $url,
                                                                        [
                                                                            'style' => '',
                                                                            'title' => Yii::t('app', 'update'),
                                                                        ]) : '');
                                                                },
                                                                'settings' => function ($url) use ($permissions) {
                                                                    if (in_array('/autoattendant/autoattendant/settings', $permissions)) {
                                                                        return (1 ? Html::a('<i class="material-icons">settings</i>',
                                                                            $url,
                                                                            [
                                                                                'style' => '',
                                                                                'title' => Yii::t('app', 'settings'),
                                                                            ]) : '');
                                                                    }else{
                                                                        return '';
                                                                    }
                                                                },
                                                                'delete' => function ($url) use ($permissions) {
                                                                    if (in_array('/autoattendant/autoattendant/delete', $permissions)) {
                                                                        return (1 ? Html::a('<i class="material-icons">delete</i>',
                                                                            $url,
                                                                            [

                                                                                'class' => 'ml-5',
                                                                                'data-pjax' => 0,
                                                                                'style' => 'color:#FF4B56',
                                                                                'title' => Yii::t('app', 'delete'),
                                                                                'data-confirm' => Yii::t('app', 'delete_confirm'),
                                                                                'data-method' => 'post',
                                                                            ]) : '');
                                                                    }else{
                                                                        return '';
                                                                    }
                                                                },
                                                            ],
                                                        ],
                                                        [
                                                            'attribute' => 'aam_status',
                                                            'headerOptions' => ['class' => 'center'],
                                                            'contentOptions' => ['class' => 'center'],
                                                            'enableSorting' => True,
                                                            'format' => 'raw',
                                                            'value' => function ($model) {
                                                                if ($model->aam_status == 1) {
                                                                    return '<span class="new badge gradient-45deg-cyan-light-green
" data-badge-caption="">' . Yii::t('app', 'active') . '</span>';
                                                                } else {
                                                                    return '<span class="new badge gradient-45deg-red-pink
" data-badge-caption="">' . Yii::t('app', 'inactive') . '</span>';
                                                                }

                                                            },
                                                        ],
                                                        [
                                                            'attribute' => 'aam_language',
                                                            'enableSorting' => True,
                                                            'headerOptions' => ['class' => 'center'],
                                                            'contentOptions' => ['class' => 'center'],
                                                            'value' => function ($model) {
                                                                if ($model->aam_language == 'en') {
                                                                    return  Yii::t('app', 'english');
                                                                } else {
                                                                    return Yii::t('app', 'spanish');
                                                                }

                                                            },
                                                        ],
                                                        [
                                                            'attribute' => 'aam_name',
                                                            'enableSorting' => True,
                                                            'headerOptions' => ['class' => 'center'],
                                                            'contentOptions' => ['class' => 'center'],
                                                        ],
                                                        [
                                                            'attribute' => 'aam_extension',
                                                            'enableSorting' => True,
                                                            'headerOptions' => ['class' => 'center'],
                                                            'contentOptions' => ['class' => 'center'],
                                                            'value' => function ($model) {
                                                                return !empty($model->aam_extension) ? $model->aam_extension : 'None';
                                                            }
                                                        ],
                                                        [
                                                            'attribute' => 'aam_greet_long',
                                                            'enableSorting' => True,
                                                            'headerOptions' => ['class' => 'center'],
                                                            'contentOptions' => ['class' => 'center'],
                                                            'format' => 'raw',
                                                            /*'value'=>function ($model) {
                                                                return AudioManagement::findOne($model->aam_greet_long)->af_name;
                                                                /*return !empty($model->aam_greet_long) ? $model->aam_greet_long : 'None';*/
                                                            /*}*/

                                                            'value' => function ($model) {
                                                                return AudioManagement::getPromptName($model->aam_greet_long);
                                                            },
                                                        ],


                                                        [
                                                            'attribute' => 'aam_greet_short',
                                                            'enableSorting' => True,
                                                            'headerOptions' => ['class' => 'center'],
                                                            'contentOptions' => ['class' => 'center'],
                                                            /* 'value'=>function ($model) {
                                                                 return !empty($model->aam_greet_short) ? $model->aam_greet_short : 'None';
                                                             }*/
                                                            /* 'value'=>function ($model) {
                                                                 return ($model->promt->af_name == ''
                                                                     || $model->promt->af_name == '0') ? 'None'
                                                                     : $model->promt->af_name;
                                                             },*/
                                                            'value' => function ($model) {
                                                                return AudioManagement::getPromptName($model->aam_greet_short);
                                                            },

                                                        ],
                                                        [
                                                            'attribute' => 'aam_invalid_sound',
                                                            'enableSorting' => True,
                                                            'headerOptions' => ['class' => 'center'],
                                                            'contentOptions' => ['class' => 'center'],
                                                            /*'value'=>function ($model) {
                                                                return !empty($model->aam_invalid_sound) ? $model->aam_invalid_sound : 'None';
                                                            }*/
                                                            'value' => function ($model) {
                                                                return AudioManagement::getPromptName($model->aam_invalid_sound);
                                                            },
                                                        ],
                                                        [
                                                            'attribute' => 'aam_exit_sound',
                                                            'enableSorting' => True,
                                                            'headerOptions' => ['class' => 'center'],
                                                            'contentOptions' => ['class' => 'center'],
                                                            'value' => function ($model) {
                                                                return AudioManagement::getPromptName($model->aam_exit_sound);
                                                            },
                                                            /*'value'=>function ($model) {
                                                                return !empty($model->aam_exit_sound) ? $model->aam_exit_sound : 'None';
                                                            },*/

                                                        ],
                                                        [
                                                            'attribute' => 'aam_failure_prompt',
                                                            'enableSorting' => True,
                                                            'headerOptions' => ['class' => 'center'],
                                                            'contentOptions' => ['class' => 'center'],
                                                            'value' => function ($model) {
                                                                return AudioManagement::getPromptName($model->aam_failure_prompt);
                                                            },


                                                        ],
//                                                        [
//                                                            'attribute' => 'aam_timeout_prompt',
//                                                            'enableSorting' => True,
//                                                            'headerOptions' => ['class' => 'center'],
//                                                            'contentOptions' => ['class' => 'center'],
//                                                            'value' => function ($model) {
//                                                                return AudioManagement::getPromptName($model->aam_timeout_prompt);
//                                                            },
//                                                            /*'value'=>function ($model) {
//                                                                return !empty($model->aam_timeout_prompt) ? $model->aam_timeout_prompt : 'None';
//                                                            },*/
//                                                        ],
                                                        [
                                                            'attribute' => 'aam_timeout',
                                                            'enableSorting' => True,
                                                            'headerOptions' => ['class' => 'center'],
                                                            'contentOptions' => ['class' => 'center'],
                                                            'value' => function ($model) {
                                                                return (int)($model->aam_timeout / 1000);
                                                            },
                                                            /*'value'=>function ($model) {
                                                                return ($model->promt->af_name == ''
                                                                    || $model->promt->af_name == '0') ? 'None'
                                                                    : $model->promt->af_name;
                                                            },*/
                                                        ],
                                                        [
                                                            'attribute' => 'aam_inter_digit_timeout',
                                                            'enableSorting' => True,
                                                            'headerOptions' => ['class' => 'center'],
                                                            'contentOptions' => ['class' => 'center'],
                                                            'value' => function ($model) {
                                                                return (int)($model->aam_inter_digit_timeout / 1000);
                                                            },
                                                        ],


                                                        [
                                                            'attribute' => 'aam_max_timeout',
                                                            'enableSorting' => True,
                                                            'headerOptions' => ['class' => 'center'],
                                                            'contentOptions' => ['class' => 'center'],
                                                            'value' => function ($model) {
                                                                return $model->aam_max_timeout;
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
<?php Pjax::end(); ?>
