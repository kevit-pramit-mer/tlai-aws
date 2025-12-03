<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\speeddial\models\ExtensionSpeeddial */

$this->title = $model->es_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Extension Speeddials'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="extension-speeddial-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->es_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->es_id], [
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
            'es_id',
            'es_extension',
            'es_*0',
            'es_*1',
            'es_*2',
            'es_*3',
            'es_*4',
            'es_*5',
            'es_*6',
            'es_*7',
            'es_*8',
            'es_*9',
        ],
    ]) ?>

</div>
