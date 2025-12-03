<?php

use app\modules\ecosmob\campaign\CampaignModule;
use app\modules\ecosmob\jobs\models\Job;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;
use app\modules\ecosmob\queue\models\QueueMaster;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\ecosmob\campaign\models\CampaignSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $timeZoneList */
/* @var $dispositionList */

$this->title = CampaignModule::t('campaign', 'campaign_management');
$this->params['breadcrumbs'][] = $this->title;
$this->params['pageHead'] = $this->title;
$permissions = $GLOBALS['permissions'];
?>
<?php Pjax::begin(['id' => 'campaign-index', 'timeout' => 0, 'enablePushState' => false]); ?>
    <div class="row">
        <div class="col-xl-9 col-md-7 col-xs-12">
            <div class="row">
                <div class="col s12">
                    <div class="profile-contain">
                        <div class="section section-data-tables">
                            <div class="row">
                                <div class="col s12 search-filter">
                                    <?= $this->render('_search', ['model' => $searchModel, 'timeZoneList' => $timeZoneList, 'dispositionList' => $dispositionList]); ?>
                                </div>
                                <div class="col s12">
                                    <div class="card table-structure">
                                        <div class="card-content">
                                            <div class="card-header d-flex align-items-center justify-content-between w-100">
                                                <div class="header-title">
                                                    <?= $this->title ?>
                                                </div>
                                                <div class="card-header-btns">
                                                    <?php if (in_array('/campaign/campaign/create', $permissions)) { ?>
                                                        <?= Html::a(CampaignModule::t('campaign', 'add_new'), ['create'], [
                                                            'id' => 'hov',
                                                            'data-pjax' => 0,
                                                            'class' => 'btn waves-effect waves-light darken-1 breadcrumbs-btn right',
                                                        ]) ?>
                                                    <?php } ?>
                                                </div>
                                            </div>
                                            <div class="dataTables_wrapper" id="page-length-option_wrapper">

                                                <?php /*Pjax::begin(['enablePushState'=>false, 'id'=>'pjax-campaign']);*/ ?>
                                                <?php try {
                                                    echo GridView::widget([
                                                            'id' => 'campaign-grid-index', // TODO : Add Grid Widget ID
                                                            'dataProvider' => $dataProvider,
                                                            'layout' => Yii::$app->layoutHelper->get_layout_str('#campaign-search-form'),
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
                                                                            if (in_array('/campaign/campaign/update', $permissions)) {
                                                                                return (1 ? Html::a('<i class="material-icons">edit</i>', $url, [
                                                                                    'style' => '',
                                                                                    'title' => Yii::t('app', 'update'),
                                                                                ]) : '');
                                                                            }else{
                                                                                return '';
                                                                            }
                                                                        },
                                                                        'delete' => function ($url, $model) use ($permissions) {
                                                                            if (in_array('/campaign/campaign/delete', $permissions)) {
                                                                                $canDelete = $model->canDelete($model->cmp_id);
                                                                                if ($canDelete) {

                                                                                    $campaignCount = Job::find()->where(['campaign_id' => $model->cmp_id])->all();
                                                                                    $i = 0;
                                                                                    $blockedContactList = [];
                                                                                    if (!empty($campaignCount)) {
                                                                                        foreach ($campaignCount as $key) {
                                                                                            if ($i == 3) {
                                                                                                break;
                                                                                            }
                                                                                            $blockedContactList[] = $key['job_name'];
                                                                                            $i++;
                                                                                        }
                                                                                    }
                                                                                    if ($i == 3) {
                                                                                        $message = implode(",", $blockedContactList) . ',...';
                                                                                    } else {
                                                                                        $message = implode(",", $blockedContactList);
                                                                                    }
                                                                                    $canNotDeleteMsg = CampaignModule::t('campaign', 'can_not_delete_assign_to_job') . ' (' . $message . ')';
                                                                                    return '<a disabled class="ml-5 opacity5"  title="' . $canNotDeleteMsg . '"><i class="material-icons">delete</i></a>';
                                                                                } else {
                                                                                    return Html::a('<i class="material-icons">delete</i>', $url, [

                                                                                        'class' => 'ml-5',
                                                                                        'data-pjax' => 0,
                                                                                        'style' => 'color:#FF4B56',
                                                                                        'data-confirm' => Yii::t('app', 'delete_confirm'),
                                                                                        'data-method' => 'post',
                                                                                        'title' => Yii::t('app', 'delete'),
                                                                                    ]);
                                                                                }
                                                                            }else{
                                                                                return '';
                                                                            }
                                                                        },
                                                                    ]
                                                                ],
                                                                [
                                                                    'attribute' => 'cmp_status',
                                                                    'format' => 'raw',
                                                                    'headerOptions' => ['class' => 'text-center'],
                                                                    'contentOptions' => ['class' => 'text-center'],
                                                                    'value' => function ($model) {
                                                                        if ($model->cmp_status == 'Active') {
                                                                            return '<span class="new badge gradient-45deg-cyan-light-green"
                                                                            data-badge-caption="">' . Yii::t('app', 'active') . '</span>';
                                                                        } else {
                                                                            return '<span class="new badge gradient-45deg-red-pink"
                                                                            data-badge-caption="">' . Yii::t('app', 'inactive') . '</span>';
                                                                        }
                                                                    },
                                                                    'enableSorting' => True,
                                                                ],
                                                                [
                                                                    'attribute' => 'cmp_name',
                                                                    'headerOptions' => ['class' => 'text-center'],
                                                                    'contentOptions' => ['class' => 'text-center'],
                                                                    'enableSorting' => True,
                                                                    'value' => function ($model) {
                                                                        if (!empty($model->cmp_name)) {
                                                                            return $model->cmp_name;
                                                                        } else {
                                                                            return '-';
                                                                        }
                                                                    }
                                                                ],
                                                                [
                                                                    'attribute' => 'cmp_type',
                                                                    'headerOptions' => ['class' => 'text-center'],
                                                                    'contentOptions' => ['class' => 'text-center'],
                                                                    'enableSorting' => True,
                                                                    'value' => function ($model) {
                                                                        if (!empty($model->cmp_type)) {
                                                                            return $model->cmp_type;
                                                                        } else {
                                                                            return '-';
                                                                        }

                                                                    }
                                                                ],
                                                               /* [
                                                                    'attribute' => 'cmp_caller_id',
                                                                    'headerOptions' => ['class' => 'text-center'],
                                                                    'contentOptions' => ['class' => 'text-center'],
                                                                    'enableSorting' => True,
                                                                    'value' => function ($model) {
                                                                        if (!empty($model->cmp_caller_id)) {
                                                                            return $model->cmp_caller_id;
                                                                        } else {
                                                                            return '-';
                                                                        }
                                                                    }
                                                                ],*/
                                                                [
                                                                    'attribute' => 'cmp_queue_id',
                                                                    'headerOptions' => ['class' => 'text-center'],
                                                                    'contentOptions' => ['class' => 'text-center'],
                                                                    'enableSorting' => True,
                                                                    'value' => function ($model) {
                                                                        if (!empty($model->cmp_queue_id) && !empty($model->queue)) {
                                                                            return QueueMaster::getQueueName($model->queue->qm_name);
                                                                        } else {
                                                                            return '-';
                                                                        }
                                                                    }
                                                                ],
                                                                [
                                                                    'attribute' => 'cmp_timezone',
                                                                    'headerOptions' => ['class' => 'text-center'],
                                                                    'contentOptions' => ['class' => 'text-center'],
                                                                    //'value' => 'timezone.tz_zone',
                                                                    'enableSorting' => True,
                                                                    'value' => function ($model) {
                                                                        if (!empty($model->timezone)) {
                                                                            return $model->timezone->tz_zone;
                                                                        } else {
                                                                            return '-';
                                                                        }
                                                                    }

                                                                ],
                                                                [
                                                                    'attribute' => 'cmp_disposition',
                                                                    'headerOptions' => ['class' => 'text-center'],
                                                                    'contentOptions' => ['class' => 'text-center'],
                                                                    //'value' => 'disposition.ds_name',
                                                                    'enableSorting' => True,
                                                                    'value' => function ($model) {
                                                                        if (!empty($model->disposition)) {
                                                                            return $model->disposition->ds_name;
                                                                        } else {
                                                                            return '-';
                                                                        }
                                                                    }

                                                                ],
                                                            ],
                                                            'tableOptions' => [
                                                                'class' => 'display dataTable dtr-inline',

                                                            ],
                                                        ]
                                                    );
                                                } catch (Exception $e) {
                                                    //var_dump($e);
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

