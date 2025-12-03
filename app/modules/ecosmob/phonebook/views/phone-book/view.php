<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\phonebook\models\Phonebook */

$this->title = $model->ph_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Phonebooks'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="phonebook-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->ph_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->ph_id], [
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
            'ph_id',
            'ph_first_name',
            'ph_last_name',
            'ph_display_name',
            'ph_extension',
            'ph_phone_number',
            'ph_cell_number',
            'ph_email_id:email',
        ],
    ]) ?>

</div>
