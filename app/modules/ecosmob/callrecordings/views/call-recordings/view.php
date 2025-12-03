<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\callrecordings\models\CallRecordings */

$this->title = $model->cr_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Call Recordings'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="call-recordings-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->cr_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->cr_id], [
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
            'cr_id',
            'cr_name',
            'cr_files',
            'cr_date',
        ],
    ]) ?>

</div>
