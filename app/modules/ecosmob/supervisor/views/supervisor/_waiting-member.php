<?php

use app\components\CommonHelper;
use app\modules\ecosmob\queue\models\QueueMaster;
use app\modules\ecosmob\supervisor\assets\SupervisorAsset;
use yii\grid\GridView;

/* @var $dataProvider yii\data\ActiveDataProvider */

SupervisorAsset::register($this);
?>

<?= GridView::widget([
    'id' => 'waiting_member_grid', // TODO : Add Grid Widget ID
    'dataProvider' => $dataProvider,
    'layout' => Yii::$app->layoutHelper->get_layout_str('#extension-search-form'),
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
            'attribute' => 'cid_number',
            'label' => Yii::t('app', 'cid_number'),
            'headerOptions' => ['class' => 'text-center'],
            'contentOptions' => ['class' => 'text-center'],
            'value' => function ($model) {
                if (isset($model->cid_number)) {
                    return $model->cid_number;
                } else {
                    return '-';
                }
            }
        ],
        [
            'attribute' => 'system_epoch',
            'label' => Yii::t('app', 'date'),
            'headerOptions' => ['class' => 'text-center'],
            'contentOptions' => ['class' => 'text-center'],
            'value' => function ($model) {
                $utc = $model->system_epoch;
                $time = date("Y-m-d H:i:s", substr($utc, 0, 10));
                return ($utc == "0" || $time == "0") ?
                    "N/A" : date("Y-m-d", strtotime(CommonHelper::tsToDt($time)));
            },
        ],
        [
            'attribute' => 'joined_epoch',
            'label' => Yii::t('app', 'queue_joined_time'),
            'headerOptions' => ['class' => 'text-center '],
            'contentOptions' => ['class' => 'text-center custom_queue_joined_time'],
            'value' => function ($model) {

                if ($model->joined_epoch != "") {
                    $utc = $model->joined_epoch;
                    $time = date("H:i:s", substr($utc, 0, 10));

                    $time1 = strtotime($time);
                    $time2 = strtotime(date("H:i:s"));
                    $total = $time2 - $time1;

                    $hours = floor($total / 3600);
                    $minutes = floor(($total / 60) % 60);
                    $seconds = $total % 60;
                    return "$hours:$minutes:$seconds";
                } else {
                    return '-';
                }
            }
        ],

        [
            'attribute' => 'state',
            'label' => Yii::t('app', 'state'),
            'headerOptions' => ['class' => 'text-center'],
            'contentOptions' => ['class' => 'text-center'],
            'value' => function ($model) {

                if (isset($model->state)) {
                    return $model->state;
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




