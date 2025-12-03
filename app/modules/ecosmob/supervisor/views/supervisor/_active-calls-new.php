<?php

use app\components\CommonHelper;
use app\modules\ecosmob\agents\models\AdminMaster;
use app\modules\ecosmob\campaign\models\Campaign;
use app\modules\ecosmob\queue\models\QueueMaster;
use app\modules\ecosmob\supervisor\assets\SupervisorAsset;
use yii\grid\GridView;
use yii\helpers\Html;

/* @var $dataProvider yii\data\ActiveDataProvider */

SupervisorAsset::register($this);
?>

<?= GridView::widget([
    'id' => 'active_calls_grid', // TODO : Add Grid Widget ID
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
            'template' => '{update}{delete}{view}{change}',
            'header' => Yii::t('app', 'actions'),
            'headerOptions' => ['class' => 'center width-12'],
            'contentOptions' => ['class' => 'center width-12'],
            'buttons' => [
                'update' => function ($url, $model) {
                    return (1 ? Html::a('<i class="material-icons color-orange livecalls" id="live-barge-call" active_id="barge' . $model->active_id . '" barge_uuid="' . $model->uuid . '" uuid="' . $model->uuid . '"  title="' . Yii::t('app', 'Bargein') . '">wc</i>', "javascript:void(0);", ['class' => 'bargein']) : '');
                },
                'view' => function ($url, $model) {
                    return (1 ? Html::a('<i class="material-icons color-orange livecalls" id="live-listen-call" active_id="listen' . $model->active_id . '" listen_uuid="' . $model->uuid . '" uuid="' . $model->uuid . '"  title="' . Yii::t('app', 'Listen') . '">hearing</i>', "javascript:void(0);", ['class' => 'listen']) : '');
                },
                'change' => function ($url, $model) {
                    return (1 ? Html::a('<i class="material-icons color-orange livecalls" id="live-whisper-call" active_id="whisper' . $model->active_id . '" whisper_uuid="' . $model->uuid . '" uuid="' . $model->uuid . '"  title="' . Yii::t('app', 'Whisper') . '">record_voice_over</i>', "javascript:void(0);", ['class' => 'whisper']) : '');
                },
                'delete' => function ($url, $model) {
                    return (1 ? Html::a('<i class="material-icons color-orange livecalls" id="live-hangup-call" active_id="hangup' . $model->active_id . '" hangup_uuid="' . $model->uuid . '" uuid="' . $model->uuid . '"  title="' . Yii::t('app', 'Hangup') . '">call_end</i>', "javascript:void(0);", ['class' => 'hangup']) : '');
                },
            ]
        ],
        [
            'attribute' => 'caller_id',
            'label' => Yii::t('app', 'caller_id'),
            'headerOptions' => ['class' => 'text-center'],
            'contentOptions' => ['class' => 'text-center'],
            'value' => function ($model) {
                $cmp_type = Campaign::findOne(['cmp_id' => $model->campaign_id]);
                if (($cmp_type->cmp_type == 'Outbound' && $cmp_type->cmp_dialer_type == 'PROGRESSIVE') || ($cmp_type->cmp_type == 'Outbound' && $cmp_type->cmp_dialer_type == 'PREVIEW')) {
                    if (!empty($model->destination_number)) {
                        return $model->destination_number;
                    } else {
                        return '-';
                    }
                } else {
                    if (!empty($model->caller_id)) {
                        return $model->caller_id;
                    } else {
                        return '-';
                    }
                }
            }
        ],
        [
            'attribute' => 'queue',
            'label' => Yii::t('app', 'queues'),
            'headerOptions' => ['class' => 'text-center'],
            'contentOptions' => ['class' => 'text-center'],
            'value' => function ($model) {

                if (isset($model->queue)) {
                    return QueueMaster::getQueueName($model->queue);
                } else {
                    return '-';
                }
            }
        ],
        [
            'attribute' => 'agent',
            'label' => Yii::t('app', 'agent'),
            'headerOptions' => ['class' => 'text-center'],
            'contentOptions' => ['class' => 'text-center'],
            'value' => function ($model) {
                if (isset($model->agent)) {
                    $agentName = AdminMaster::findOne($model->agent);
                    return $agentName->adm_username;
                } else {
                    return '-';
                }
            }
        ],
        [
            'attribute' => 'campaign_id',
            'label' => Yii::t('app', 'campaign_id'),
            'headerOptions' => ['class' => 'text-center'],
            'contentOptions' => ['class' => 'text-center'],
            'value' => function ($model) {
                if (isset($model->campaign_id)) {
                    $campName = Campaign::findOne($model->campaign_id);
                    return $campName->cmp_name;
                } else {
                    return '-';
                }
            }
        ],
        [
            'attribute' => 'call_start_time',
            'label' => Yii::t('app', 'call_start_time'),
            'headerOptions' => ['class' => 'text-center'],
            'contentOptions' => ['class' => 'text-center'],
            'value' => function ($model) {
                if (isset($model->call_start_time)) {
                    return CommonHelper::tsToDt($model->call_start_time);
                } else {
                    return '-';
                }
            }
        ],
        [
            'attribute' => 'call_agent_time',
            'label' => Yii::t('app', 'call_agent_time'),
            'headerOptions' => ['class' => 'text-center '],
            'contentOptions' => ['class' => 'text-center custom_call_agent_time'],
            'value' => function ($model) {

                if ($model->call_agent_time != "") {

                    $time1 = strtotime($model->call_agent_time);
                    $time2 = strtotime(date("Y-m-d H:i:s"));
                    $total = $time2 - $time1;

                    $hours = floor($total / 3600);
                    if ($hours < 10) {
                        $hours = "0" . $hours;
                    }
                    $minutes = floor(($total / 60) % 60);
                    if ($minutes < 10) {
                        $minutes = "0" . $minutes;
                    }
                    $seconds = $total % 60;
                    if ($seconds < 10) {
                        $seconds = "0" . $seconds;
                    }
                    return "$hours:$minutes:$seconds";
                } else {
                    return '-';
                }
            }
        ],

    ],
    'tableOptions' => [
        'class' => 'display dataTable dtr-inline',

    ],
]);
?>



