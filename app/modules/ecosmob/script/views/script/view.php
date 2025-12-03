<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\script\models\Script */

$this->title = $model->scr_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Scripts'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="script-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->scr_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->scr_id], [
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
            'scr_id',
            'scr_name',
            'scr_description:ntext',
            'scr_status',
        ],
    ]) ?>

</div>
