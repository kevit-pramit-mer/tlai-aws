<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\ecosmob\ipprovisioning\models\DeviceTemplates */

$this->title = $model->device_templates_id;
$this->params['breadcrumbs'][] = ['label' => 'Device Templates', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="device-templates-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->device_templates_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->device_templates_id], [
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
            'device_templates_id',
            'template_name:ntext',
            'brand_id',
            'voipservice_key',
            'profile_count',
            'supported_models_id',
            'upload_csv',
            'createdAt',
            'updatedAt',
        ],
    ]) ?>

</div>
