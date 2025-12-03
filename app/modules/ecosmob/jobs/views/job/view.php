<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\jobs\models\Job */

$this->title = $model->job_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Jobs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="job-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->job_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->job_id], [
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
            'job_id',
            'job_name',
            'timezone_id:datetime',
            'campaign_id',
            'concurrent_calls_limit',
            'answer_timeout:datetime',
            'ring_timeout:datetime',
            'retry_on_no_answer',
            'retry_on_busy',
            'retry_num',
            'job_status',
            'activation_status',
            'time_id:datetime',
            'job_dial_status',
        ],
    ]) ?>

</div>
