<?php

use yii\grid\GridView;
use app\modules\ecosmob\supervisor\assets\SupervisorAsset;

/* @var $dataProvider yii\data\ActiveDataProvider */

SupervisorAsset::register($this);
?>

<?= GridView::widget([
    'id' => 'active_agent_grid', // TODO : Add Grid Widget ID
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
            'attribute' => 'agent_name',
            'label' => Yii::t('app', 'agent_name'),
            'headerOptions' => ['class' => 'text-center'],
            'contentOptions' => ['class' => 'text-center'],
            'value' => function ($model) {
                if (isset($model->agent_name)) {
                    return $model->agent_name;
                } else {
                    return '-';
                }
            }
        ],
        [
            'attribute' => 'status',
            'label' => Yii::t('app', 'status'),
            'headerOptions' => ['class' => 'text-center'],
            'contentOptions' => ['class' => 'text-center'],
            'value' => function ($model) {
                if (isset($model->status)) {
                    return $model->status;
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




