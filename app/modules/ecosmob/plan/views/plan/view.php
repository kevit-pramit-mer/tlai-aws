<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\plan\models\Plan */

$this->title = $model->pl_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Plans'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="plan-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->pl_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->pl_id], [
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
            'pl_id',
            'pl_name',
            'pl_holiday',
            'pl_week_off',
            'pl_bargain',
            'pl_dnd',
            'pl_park',
            'pl_transfer',
            'pl_call_record',
            'pl_white_list',
            'pl_black_list',
            'pl_caller_id_block',
            'pl_universal_forward',
            'pl_no_ans_forward',
            'pl_busy_forward',
            'pl_timebase_forward',
            'pl_selective_forward',
            'pl_shift_forward',
            'created_date',
            'updated_date',
        ],
    ]) ?>

</div>
