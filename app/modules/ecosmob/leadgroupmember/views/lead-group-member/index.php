<?php

use app\modules\ecosmob\leadgroupmember\LeadGroupMemberModule;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;
use app\modules\ecosmob\leadgroupmember\assets\LeadGroupMemberAsset;
use app\modules\ecosmob\crm\models\LeadCommentMapping;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\ecosmob\leadgroupmember\models\LeadGroupMemberSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = LeadGroupMemberModule::t('lead-group-member', 'lead_grp_member');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'leadgroup'), 'url' => ['/leadgroup/leadgroup/index']];
$this->params['breadcrumbs'][] = $this->title;
$this->params['pageHead'] = $this->title;
$permissions = $GLOBALS['permissions'];

LeadGroupMemberAsset::register($this);
?>

<?php Pjax::begin(['id' => 'lead-group-member-index', 'timeout' => 0, 'enablePushState' => false]); ?>
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
                                            <?php if (in_array('/leadgroupmember/lead-group-member/create', $permissions)) { ?>
                                            <?= Html::a(LeadGroupMemberModule::t('lead-group-member', 'add_new'), ['create', 'ld_id' => $_GET['ld_id']], [
                                                    'id' => 'hov view_link',
                                                    'data-pjax' => '0',
                                                    'class' => 'btn waves-effect waves-light darken-1 breadcrumbs-btn right',
                                                ]) ?>
                                            <?php } ?>
                                            <?= Html::a(LeadGroupMemberModule::t('lead-group-member', 'back'), ['/leadgroup/leadgroup/index'], [
                                                'id' => 'hov view_link',
                                                'data-pjax' => '0',
                                                'class' => 'back-btn  lead_group btn waves-effect waves-light darken-1 breadcrumbs-btn right',
                                            ]) ?>

                                            <?php if (in_array('/leadgroupmember/lead-group-member/export', $permissions)) { ?>

                                            <!-- --><?php /*= Html::a(LeadGroupMemberModule::t('lead-group-member', 'export'), ['/leadgroupmember/lead-group-member/export'], [
                                                    'id' => 'hov view_link',
                                                    'data-pjax' => '0',
                                                    'class' => 'exportbutton lead_group btn waves-effect waves-light darken-1 breadcrumbs-btn right',
                                                ]) */?>
                                            <button id="export-button" class="exportbutton lead_group btn waves-effect waves-light darken-1 breadcrumbs-btn right">
                                                <?= LeadGroupMemberModule::t('lead-group-member', 'Export') ?>
                                            </button>

                                            <?php }

                                             if (in_array('/leadgroupmember/lead-group-member/import', $permissions)) { ?>

                                                <?= Html::a(LeadGroupMemberModule::t('lead-group-member', 'import'), ['/leadgroupmember/lead-group-member/import', 'ld_id' => $_GET['ld_id']], [
                                                    'data-pjax' => '0',
                                                    'class' => 'btn waves-effect waves-light darken-1 breadcrumbs-btn right',
                                                ]) ?>
                                            <?php } ?>

                                        </div>
                                    </div>
                                        <div class="dataTables_wrapper" id="page-length-option_wrapper">

                                            <?php try {
                                                echo GridView::widget([
                                                        'id' => 'lead-group-member-grid-index', // TODO : Add Grid Widget ID
                                                        'dataProvider' => $dataProvider,
                                                        'layout' => Yii::$app->layoutHelper->get_layout_str('#lead-group-member-search-form'),
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
                                                                    'update' => function ($url) use ($permissions) {
                                                                        if (in_array('/leadgroupmember/lead-group-member/update', $permissions)) {
                                                                            return (1 ? Html::a('<i class="material-icons">edit</i>',
                                                                                $url, [
                                                                                    'style' => '',
                                                                                    'title' => Yii::t('app', 'update'),
                                                                                ]) : '');
                                                                        }else{
                                                                            return '';
                                                                        }
                                                                    },
                                                                    'delete' => function ($url) use ($permissions) {
                                                                        if (in_array('/leadgroupmember/lead-group-member/delete', $permissions)) {
                                                                            return (1 ? Html::a('<i class="material-icons">delete</i>',
                                                                                $url, [

                                                                                    'class' => 'ml-5',
                                                                                    'data-pjax' => 0,
                                                                                    'style' => 'color:#FF4B56',
                                                                                    'data-confirm' => Yii::t('app', 'delete_confirm'),
                                                                                    'data-method' => 'post',
                                                                                    'title' => Yii::t('app', 'delete'),
                                                                                    'ok' => Yii::t('app', 'ok'),
                                                                                    'cancel' => Yii::t('app', 'cancel'),
                                                                                ]) : '');
                                                                        }else{
                                                                            return '';
                                                                        }
                                                                    },
                                                                ]
                                                            ],
                                                            [
                                                                'attribute' => 'lg_first_name',
                                                                'headerOptions' => ['class' => 'center width-10'],
                                                                'contentOptions' => ['class' => 'center width-10'],
                                                                'enableSorting' => True,
                                                                'value' => function ($model) {
                                                                    if (!empty($model->lg_first_name)) {
                                                                        return $model->lg_first_name;
                                                                    } else {
                                                                        return "-";
                                                                    }

                                                                }
                                                            ],
                                                            [
                                                                'attribute' => 'lg_last_name',
                                                                'headerOptions' => ['class' => 'center width-10'],
                                                                'contentOptions' => ['class' => 'center width-10'],
                                                                'enableSorting' => True,
                                                                'value' => function ($model) {
                                                                    if (!empty($model->lg_last_name)) {
                                                                        return $model->lg_last_name;
                                                                    } else {
                                                                        return "-";
                                                                    }

                                                                }
                                                            ],
                                                            [
                                                                'attribute' => 'lg_contact_number',
                                                                'headerOptions' => ['class' => 'center width-10'],
                                                                'contentOptions' => ['class' => 'center width-10'],
                                                                'enableSorting' => True,
                                                                'value' => function ($model) {
                                                                    if (!empty($model->lg_contact_number)) {
                                                                        return $model->lg_contact_number;
                                                                    } else {
                                                                        return "-";
                                                                    }

                                                                }
                                                            ],
                                                            [
                                                                'attribute' => 'lg_contact_number_2',
                                                                'headerOptions' => ['class' => 'center width-10'],
                                                                'contentOptions' => ['class' => 'center width-10'],
                                                                'enableSorting' => True,
                                                                'value' => function ($model) {
                                                                    if (!empty($model->lg_contact_number_2)) {
                                                                        return $model->lg_contact_number_2;
                                                                    } else {
                                                                        return "-";
                                                                    }

                                                                }
                                                            ],
                                                            [
                                                                'attribute' => 'lg_email_id',
                                                                'headerOptions' => ['class' => 'center width-10'],
                                                                'contentOptions' => ['class' => 'center width-10'],
                                                                'enableSorting' => True,
                                                                'value' => function ($model) {
                                                                    if (!empty($model->lg_email_id)) {
                                                                        return $model->lg_email_id;
                                                                    } else {
                                                                        return "-";
                                                                    }

                                                                }
                                                            ],
                                                            [
                                                                'attribute' => 'lg_address',
                                                                'headerOptions' => ['class' => 'center width-10'],
                                                                'contentOptions' => ['class' => 'center width-10'],
                                                                'enableSorting' => True,
                                                                'value' => function ($model) {
                                                                    if (!empty($model->lg_address)) {
                                                                        return $model->lg_address;
                                                                    } else {
                                                                        return "-";
                                                                    }

                                                                }
                                                            ],
                                                            [
                                                                'attribute' => 'lg_alternate_number',
                                                                'headerOptions' => ['class' => 'center width-10'],
                                                                'contentOptions' => ['class' => 'center width-10'],
                                                                'enableSorting' => True,
                                                                'value' => function ($model) {
                                                                    if (!empty($model->lg_alternate_number)) {
                                                                        return $model->lg_alternate_number;
                                                                    } else {
                                                                        return "-";
                                                                    }

                                                                }
                                                            ],
                                                            [
                                                                'attribute' => 'lg_pin_code',
                                                                'headerOptions' => ['class' => 'center width-10'],
                                                                'contentOptions' => ['class' => 'center width-10'],
                                                                'enableSorting' => True,
                                                                'value' => function ($model) {
                                                                    if (!empty($model->lg_pin_code)) {
                                                                        return $model->lg_pin_code;
                                                                    } else {
                                                                        return "-";
                                                                    }

                                                                }
                                                            ],
                                                            [
                                                                'attribute' => 'lg_permanent_address',
                                                                'headerOptions' => ['class' => 'center width-10'],
                                                                'contentOptions' => ['class' => 'center width-10'],
                                                                'enableSorting' => True,
                                                                'value' => function ($model) {
                                                                    if (!empty($model->lg_permanent_address)) {
                                                                        return $model->lg_permanent_address;
                                                                    } else {
                                                                        return "-";
                                                                    }

                                                                }
                                                            ],
                                                            [
                                                                'header' => LeadGroupMemberModule::t('lead-group-member', 'comment'),
                                                                'headerOptions' => ['class' => 'center'],
                                                                'contentOptions' => ['class' => 'center'],
                                                                'value' => function ($model) {
                                                                   $comment = LeadCommentMapping::find()->where(['lead_id' => $model->lg_id])->orderBy(['created_at' => SORT_DESC])->one();
                                                                   if(!empty($comment)){
                                                                       return (!empty($comment->comment) ? $comment->comment : '-');
                                                                   }else{
                                                                       return '-';
                                                                   }
                                                                }
                                                            ]

                                                        ],
                                                        'tableOptions' => [
                                                            'class' => 'display dataTable dtr-inline providercount',
                                                            'data-count' => $dataProvider->getTotalCount(),
                                                        ],
                                                    ]
                                                );
                                            } catch (Exception $e) {
                                            }
                                            ?>

                                            <?php /*Pjax::end();*/ ?>
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
$this->registerJs("
    $(document).on('click', '.exportbutton', function () {
        var count = ((!$('.providercount').data('count')) ? 0 : $('.providercount').data('count'));
        if (count <= 0) {
            alert('" . LeadGroupMemberModule::t('lead-group-member', 'No records found to export') . "');
            return false;
        }else{
            event.preventDefault(); 
            window.location.href = '".Url::to(['/leadgroupmember/lead-group-member/export'])."';
        }
    });");
?>
