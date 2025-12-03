<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model \app\modules\ecosmob\parkinglot\models\ParkingLot */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Parking Lots', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="parking-lot-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'park_ext',
            'slot_qty',
            'park_pos_start',
            'park_pos_end',
            'grp_id',
            'parking_time:datetime',
            'park_moh',
            'return_to_origin',
            'call_back_ring_time:datetime',
            'destination_type',
            'destination_id',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
