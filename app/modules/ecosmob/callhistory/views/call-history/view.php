<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\callhistory\models\CampCdr */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Camp Cdrs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="camp-cdr-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->id], [
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
            'id',
            'caller_id_num',
            'dial_number',
            'extension_number',
            'call_status',
            'start_time',
            'ans_time',
            'end_time',
            'call_id',
            'camp_name',
            'call_disposion_start_time',
            'call_disposion_name',
        ],
    ]) ?>

</div>
