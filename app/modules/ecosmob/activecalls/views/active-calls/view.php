<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\activecalls\models\ActiveCalls */

$this->title = $model->active_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Active Calls'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="active-calls-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->active_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->active_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'active_id',
            'caller_id',
            'did',
            'destination_number',
            'uuid',
            'status',
            'queue',
            'agent',
            'call_queue_time',
            'call_start_time',
            'call_agent_time',
        ],
    ]) ?>

</div>
